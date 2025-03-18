<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $template = 'fontend.blog.index';
        return view('fontend.layout', compact('template'));
    }
}
