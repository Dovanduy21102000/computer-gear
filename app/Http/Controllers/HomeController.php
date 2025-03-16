<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct() {}

    public function index()
    {

        $template = 'fontend.home.index';
        return view('fontend.layout', compact('template'));
    }

}
