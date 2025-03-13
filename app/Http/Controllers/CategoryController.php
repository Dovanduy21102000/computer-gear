<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $template = 'backend.categories.index';
        $categories = Category::with('parent')->get();
        return view('backend.dashboard.layout',compact('categories','template'));
    }

    public function create()
    {
        return view('backend.dashboard.layout', compact('backend.coupons.create'));
    }


}
