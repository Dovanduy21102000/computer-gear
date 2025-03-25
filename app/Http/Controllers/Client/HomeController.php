<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner; // Import model Banner

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {
        // Lấy danh sách banner có status = 1 (đang hoạt động)
        $banners = Banner::where('status', 1)->get();

        // Trả về view với dữ liệu banners
        $template = 'fontend.home.index';
        return view('fontend.layout', compact('template', 'banners'));
    }
}
