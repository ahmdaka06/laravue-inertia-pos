<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get categories
        $categories = Category::when(request()->q, function($categories) {
                        $categories = $categories->where('name', 'like', '%'. request()->q . '%');
                    })
                    ->latest()
                    ->paginate(5);

        //return inertia
        return Inertia::render('Apps/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Apps/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name'          => 'required|unique:categories',
            'description'   => 'required'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        //create category
        Category::create([
            'image'         => $image->hashName(),
            'name'          => $request->name,
            'description'   => $request->description
        ]);

        //redirect
        return redirect()->route('apps.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('Apps/Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        /**
         * validate
         */
        $this->validate($request, [
            'name'          => 'required|unique:categories,name,'.$category->id,
            'description'   => 'required'
        ]);

        //check image update
        if ($request->file('image')) {

            //remove old image
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/categories', $image->hashName());

            //update category with new image
            $category->update([
                'image'=> $image->hashName(),
                'name' => $request->name,
                'description'   => $request->description
            ]);
        }
        //update category without image
        $category->update([
            'name'          => $request->name,
            'description'   => $request->description
        ]);

        //redirect
        return redirect()->route('apps.categories.index')->with([
            'success' => 'Category created successfully!.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //find by ID
        $category = Category::findOrFail($id);

        //remove image
        Storage::disk('local')->delete('public/categories/' . basename($category->image));

        //delete
        $category->delete();

        //redirect
        return redirect()->route('apps.categories.index');
    }
}
