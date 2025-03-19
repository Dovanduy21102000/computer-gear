<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductClientController extends Controller
{
    public function __construct() {}

    public function index()
    {
        

        // Trả về view với dữ liệu banners
        $template = 'fontend.products.index';
        return view('fontend.layout', compact('template'));
    }
}
