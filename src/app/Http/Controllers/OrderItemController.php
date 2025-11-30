<?php

// app/Http/Controllers/OrderItemController.php
namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Http\Resources\OrderItemResource;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['variant', 'discount'])->get();
        return OrderItemResource::collection($orderItems);
    }

    public function show(OrderItem $orderItem)
    {
        return new OrderItemResource($orderItem->load(['variant', 'discount']));
    }
}