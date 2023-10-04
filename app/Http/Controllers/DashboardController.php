<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Profit;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {

        // $salesDate = [];
        // $grandTotal = [];

        // $product = [];
        // $total = [];

        // day
        $day = date('Y');

        // week
        $week = Carbon::now()->subDays(7);

        //chart sales 7 days
        $chartSalesWeek = DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(grand_total) as total'))
            ->where('created_at', '>=', $week)
            ->groupBy('date')
            ->get();

        if (count($chartSalesWeek)) {
            foreach ($chartSalesWeek as $key => $value) {
                $salesDate[] = $value->date;
                $grandTotal[] = (int) $value->total;
            }
        } else {
            $salesDate[] = '';
            $grandTotal[] = '';
        }

        //count sales today
        $countSalesToday = Transaction::whereDay('created_at', $day)->count();

        //sum sales today
        $sumSalesToday = Transaction::whereDay('created_at', $day)->sum('grand_total');

        //sum profits today
        $sumProfitToday = Profit::whereDay('created_at', $day)->sum('total');

        //get product limit stock
        $productLimitStock = Product::with('category')->where('stock', '<=', 10)->get();

        //chart best selling product
        $chartBestProduct = DB::table('transaction_details')
            ->addSelect(DB::raw('products.title as title, SUM(transaction_details.qty) as total'))
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->groupBy('transaction_details.product_id')
            ->orderBy('total', 'DESC')
            ->limit(5)
            ->get();

        if(count($chartBestProduct)) {
            foreach ($chartBestProduct as $key => $value) {
                $product[] = $value->title;
                $total[] = (int) $value->total;
            }
        } else {
            $product[] = '';
            $total[]  = '';
        }

        return Inertia::render('Apps/Dashboard/Index', [
            'salesDate'             => $salesDate,
            'grandTotal'            => $grandTotal,
            'countSalesToday'       => (int) $countSalesToday,
            'sumSalesToday'         => (int) $sumSalesToday,
            'sumProfitToday'        => (int) $sumProfitToday,
            'productLimitStock'     => $productLimitStock,
            'product'               => $product,
            'total'                 => $total
        ]);
    }
}
