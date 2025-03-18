<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index()
    {
        

        // Trả về view với dữ liệu banners
        $template = 'fontend.products.index';
        $products = Product::all();
        return view('fontend.layout', compact('template', 'products'));
    }

    public function detail(string $slug) {
        // Trả về view với dữ liệu product
        $product = Product::where('slug', $slug)->firstOrFail();
        $template = 'fontend.products.detail';
        
        return view('fontend.layout', compact('template','product'));
    }
}
