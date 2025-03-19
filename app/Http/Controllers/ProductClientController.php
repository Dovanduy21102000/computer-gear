<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import model Product
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $products = Product::where('status', true)->get(); // Không dùng paginate
        $template = 'fontend.products.index';
        return view('fontend.layout', compact('template', 'products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        $template = 'fontend.products.detail'; // Thêm biến này
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('fontend.layout', compact('template', 'product', 'relatedProducts'));
    }
}
