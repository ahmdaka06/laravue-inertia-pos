<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        //get cart
        $carts = Cart::with('product')->where('cashier_id', auth()->user()->id)->latest()->get();

        //get all customers
        $customers = Customer::latest()->get();

        return Inertia::render('Apps/Transactions/Index', [
            'carts'         => $carts,
            'carts_total'   => $carts->sum('price'),
            'customers'     => $customers
        ]);
    }

    public function searchProduct(Request $request)
    {
        // find product by barcode or code
        $product = Product::where('barcode', $request->barcode)
                    ->first();
        if ($product) {
            return response()->json([
                'status' => true,
                'data' => $product
            ]);
        }

        return response()->json([
            'status' => false,
            'data' => null,
            'msg' => 'Product not found!.'
        ], 404);
    }

    public function addToCart(Request $request)
    {
        // get product
        $product = Product::whereId($request->product)
            ->first();

        if ($product->stock < $request->quantity) {
            // redirect
            return redirect()->back()->with('error', 'Out of Stock Product!.');
        }

        // check cart
        $cart = Cart::with('product')
            ->whereProductId($request->product)
            ->whereCashierId(auth()->user()->id)
            ->first();

        if ($cart) {
            $cart->increment('qty', $request->quantity);
            // sum price * quantity
            $cart->price = $cart->product->sell_price * $cart->qty;
            $cart->save();
        } else {
            Cart::create([
                'cashier_id'    => auth()->user()->id,
                'product_id'    => $request->product,
                'qty'           => $request->quantity,
                'price'         => $product->sell_price * $request->quantity,
            ]);
        }

        return redirect()->route('apps.transactions.index')->with([
            'success' => 'Product added to cart successfully',
        ]);
    }

    public function destroyCart(Request $request)
    {
        // find cart by id
        $cart = Cart::with('product')
            ->whereId($request->cart)
            ->first();

        // delete cart
        $cart->delete();

        return redirect()->back()->with([
            'success' => 'Product deleted from cart successfully',
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        // generate invoice number
        $invoice = 'TRX-' . time();

        DB::beginTransaction();

        try {
            // create transaction
            $transaction = Transaction::create([
                'cashier_id'    => $user->id,
                'customer_id'   => $request->customer,
                'invoice'       => $invoice,
                'cash'          => $request->cash,
                'change'        => $request->change,
                'discount'      => $request->discount,
                'grand_total'   => $request->grand_total,
            ]);

            // get cart
            $carts = Cart::whereCashierId($user->id)->get();

            // inseert to transaction detail
            foreach ($carts as $c => $cart) {
                $transaction->details()->create([
                    'transaction_id'    => $transaction->id,
                    'product_id'        => $cart->product_id,
                    'qty'               => $cart->qty,
                    'price'             => $cart->price,
                ]);

                // get price
                $totalBuyPrice = $cart->product->buy_price * $cart->qty;
                $totalSellPrice = $cart->product->sell_price * $cart->qty;

                // get profit
                $profit = $totalSellPrice - $totalBuyPrice;

                // insert profit
                $transaction->profits()->create([
                    'transaction_id'    => $transaction->id,
                    'total'             => $profit,
                ]);

                // update stock product
                $product = Product::find($cart->product_id);
                $product->stock = $product->stock - $cart->qty;
                $product->save();
            }

            // delete cart
            Cart::whereCashierId($user->id)->delete();
            DB::commit();
            return response()->json([
                'status' => true,
                'data'    => $transaction
            ]);
        } catch (\Throwable $th) {
            Log::error('TransactionController - store : ' . $th->getMessage());
            return response()->json([
                'status' => false,
                'data'    => null,
                'msg' => 'System error!.'
            ]);
        }
    }

    public function print(Request $request)
    {
        // get transaction
        $transaction = Transaction::with('details.product', 'cashier', 'customer')
            ->whereInvoice($request->invoice)
            ->firstOrFail();

        // return view
        return view('print.transaction', [
            'transaction' => $transaction
        ]);
    }
}
