<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // dd($request->all());
    // Validate the form data
    $validatedData = $request->validate([
        'product_name' => 'required|string|max:255',
        // 'product_sku' => 'required|string|max:255|unique:products',
        'product_description' => 'required|string',
        // 'product_variant.*.variant' => 'required|string',
        // 'product_variant_prices.*.price' => 'required|numeric',
        // 'product_variant_prices.*.stock' => 'required|integer',
        // 'product_images.*' => 'required|image|mimes:jpeg,jpg,png',
    ]);

    // Create a new product
    $product = new Product;
    $product->title = $request->product_name;
    $product->sku = $request->product_sku;
    $product->description = $request->product_description;
    $product->save();

    // Create product variants
    foreach ($request->product_variant as $variant) {
        $productVariant = new ProductVariant;
        $productVariant->variant = implode('/', $variant['value']);
        $productVariant->variant_id = $variant['option'];
        $productVariant->product_id = $product->id;
        $productVariant->save();
    }

    // Create product variant prices
    // foreach ($request->product_variant_prices as $price) {
    //     $productVariantPrice = new ProductVariantPrice;
    //     $productVariantPrice->product_variant_one = $price['product_variant_one'];
    //     $productVariantPrice->product_variant_two = $price['product_variant_two'];
    //     $productVariantPrice->product_variant_three = $price['product_variant_three'];
    //     $productVariantPrice->price = $price['price'];
    //     $productVariantPrice->stock = $price['stock'];
    //     $productVariantPrice->product_id = $product->id;
    //     $productVariantPrice->save();
    // }
    // Create product images
    if ($request->hasFile('product_images')) {
        foreach ($request->file('product_images') as $image) {
            $path = $image->store('public/product_images');
            $productImage = new ProductImage();
            $productImage->file_path = $path;
            $productImage->product_id = $product->id;
            $productImage->save();
        }
    }
    return redirect()->route('product.index')->with('success', 'Product created successfully!');
}


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
