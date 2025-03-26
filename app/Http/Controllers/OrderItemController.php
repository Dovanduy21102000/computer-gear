<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orders = OrderItem::all();
        $template = 'backend.orderitems.index';
        // dd($orders);
        return view('backend.dashboard.layout', compact('orders', 'template'));
    }
    public function show(OrderItem $order)
    {
        //
    }
}