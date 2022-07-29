<?php

namespace App\Http\Controllers\Apps;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::when(request()->q, function ($products) {
            $products = $products->where('title', 'like', '%' . request()->q . '%');
        })->latest()->paginate(5);

        return Inertia::render('Apps/Products/Index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Apps/Products/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'         => 'required|image|mimes:png,jpg,jpeg|max:2000',
            'barcode'       => 'required|unique:products',
            'title'         => 'required|string',
            'description'   => 'required|string',
            'category_id'   => 'required|string',
            'buy_price'     => 'required|string',
            'sell_price'    => 'required|string',
            'stock'         => 'required|string'
        ]);

        // upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        // create product
        Product::create([
            'image'         => $image->hashName(),
            'barcode'       => $request->barcode,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'buy_price'     => $request->buy_price,
            'sell_price'    => $request->sell_price,
            'stock'         => $request->stock
        ]);

        return redirect()->route('apps.products.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return Inertia::render('Apps/Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'barcode'       => 'required|unique:products,barcode,' . $product->id,
            'title'         => 'required|string',
            'description'   => 'required|string',
            'category_id'   => 'required|numeric',
            'buy_price'     => 'required|numeric',
            'sell_price'    => 'required|numeric',
            'stock'         => 'required|numeric'
        ]);

        // check image update
        if ($request->file('file')) {
            // remove old image
            Storage::disk('local')->delete('public/products/' . $product->image);

            // upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            $product->update([
                'image'         => $image->hashName(),
                'barcode'       => $request->barcode,
                'title'         => $request->title,
                'description'   => $request->description,
                'category_id'   => $request->category_id,
                'buy_price'     => $request->buy_price,
                'sell_price'    => $request->sell_price,
                'stock'         => $request->stock
            ]);
        }

        // without image
        $product->update([
            'barcode'       => $request->barcode,
            'title'         => $request->title,
            'description'   => $request->description,
            'category_id'   => $request->category_id,
            'buy_price'     => $request->buy_price,
            'sell_price'    => $request->sell_price,
            'stock'         => $request->stock
        ]);

        return redirect()->route('apps.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // find by ID
        $product = Product::findOrFail($id);

        // delete image
        Storage::disk('local')->delete('public/products/' . $product->image);

        // delete
        $product->delete();

        return redirect()->route('apps.products.index');
    }
}
