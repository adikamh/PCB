<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class PublicProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(6);
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalCategories = Category::count();
        
        return view('home.index', compact('products', 'totalProducts', 'totalStock', 'totalCategories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with('category')->firstOrFail();
        return view('products.show', compact('product'));
    }

    public function getByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->get();
        return response()->json($products);
    }
}