<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;


class ProductContoller extends Controller
{
    public function index()
    {
        //get products
        $products = Product::when(request()->q, function($products) {
                    $products = $products->where('title', 'like', '%'. request()->q . '%');
                })->latest()
                ->paginate(5);

        //return inertia
        return Inertia::render('Apps/Product/Index', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        //get categories
        $categories = Category::all();

        //return inertia
        return Inertia::render('Apps/Product/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'barcode'       => 'required|unique:products',
            'title'         => 'required',
            'description'   => 'required',
            'category_id'   => 'required',
            'buy_price'     => 'required',
            'sell_price'    => 'required',
            'stock'         => 'required',
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
            'image'         => $image->hashName(),
            'barcode'       => $request->barcode,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'buy_price'     => $request->buy_price,
            'sell_price'    => $request->sell_price,
            'stock'         => $request->stock,
        ]);

        //redirect
        return redirect()->route('apps.products.index')->with([
            'success' => 'Product created successfully!.',
        ]);
    }

    public function edit(Product $product)
    {
        //get categories
        $categories = Category::all();

        return Inertia::render('Apps/Product/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'barcode'       => 'required|unique:products,barcode,'.$product->id,
            'title'         => 'required',
            'description'   => 'required',
            'category_id'   => 'required',
            'buy_price'     => 'required',
            'sell_price'    => 'required',
            'stock'         => 'required',
        ]);

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/products/'.basename($product->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //update product with new image
            $product->update([
                'image'=> $image->hashName(),
                'barcode'       => $request->barcode,
                'title'         => $request->title,
                'description'   => $request->description,
                'category_id'   => $request->category_id,
                'buy_price'     => $request->buy_price,
                'sell_price'    => $request->sell_price,
                'stock'         => $request->stock,
            ]);

        }

        //update product without image
        $product->update([
            'barcode'       => $request->barcode,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'buy_price'     => $request->buy_price,
            'sell_price'    => $request->sell_price,
            'stock'         => $request->stock,
        ]);

        //redirect
        return redirect()->route('apps.products.index')->with([
            'success' => 'Product updated successfully!.',
        ]);
    }

    public function destroy($id)
    {
        //find by ID
        $product = Product::findOrFail($id);

        //remove image
        Storage::disk('local')->delete('public/products/'.basename($product->image));

        //delete
        $product->delete();

        //redirect
        return redirect()->route('apps.products.index');
    }
}
