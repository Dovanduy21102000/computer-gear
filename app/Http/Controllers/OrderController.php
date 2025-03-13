<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        $template = 'backend.orders.index';
        // dd($orders);
        return view('backend.dashboard.layout', compact('orders', 'template'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $oder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $oder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $oder)
    {
        //
    }
    /**
     * Force Remove the specified resource from storage.
     */
    public function forceDestroy(Order $oder)
    {
        //
    }
}