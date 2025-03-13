<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct() {}

    public function index()
    {

        $template = 'backend.dashboard.home.index';
        return view('backend.dashboard.layout', compact('template'));
    }

}
