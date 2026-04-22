<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();
        $totalProducts = Product::count();
        $totalStock = Product::sum('stock');
        $totalCategories = Category::count();

        return view('home.index', compact('products', 'totalProducts', 'totalStock', 'totalCategories'));
    }
}