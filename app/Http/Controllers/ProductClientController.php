<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $products = Product::with(['category', 'brand'])->paginate(10);

        // Trả về view với dữ liệu banners
        $template = 'fontend.products.index';
        return view('fontend.layout', compact('template', 'products'));
    }
}
