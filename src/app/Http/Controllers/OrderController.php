<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PerfumeVariant;
use App\Http\Resources\OrderResource;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Order::with(['user', 'status', 'paymentStatus', 'orderItems.variant', 'payments']);
        
        // Resellers can only see their own orders
        if ($user->role->role !== 'Admin') {
            $query->where('user_id', $user->id);
        }
        
        $orders = $query->orderBy('created_date', 'desc')->get();
        
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = auth()->user();
            
            // Determine user_id: admin can specify, reseller uses auth user
            $userId = $user->role->role === 'Admin' && $request->has('user_id') 
                ? $request->user_id 
                : $user->id;
            
            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'total_amount' => 0, // Will calculate below
                'status_id' => $request->status_id,
                'payment_status_id' => $request->payment_status_id,
                'created_date' => now(),
                'last_updated' => now(),
                'is_active' => true,
            ]);
            
            $totalAmount = 0;
            
            // Create order items
            foreach ($request->items as $item) {
                $variant = PerfumeVariant::findOrFail($item['variant_id']);
                
                $subTotal = $variant->price * $item['quantity'];
                
                // Apply discount if provided
                if (isset($item['discount_id'])) {
                    $discount = \App\Models\Discount::findOrFail($item['discount_id']);
                    if ($discount->discountType->name === 'Percentage') {
                        $subTotal -= ($subTotal * ($discount->value / 100));
                    } else {
                        $subTotal -= $discount->value;
                    }
                }
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'variant_id' => $item['variant_id'],
                    'quantity' => $item['quantity'],
                    'price_at_reservation' => $variant->price,
                    'discount_id' => $item['discount_id'] ?? null,
                    'sub_total' => $subTotal,
                    'created_date' => now(),
                    'last_updated' => now(),
                ]);
                
                $totalAmount += $subTotal;
            }
            
            // Update order total
            $order->update(['total_amount' => $totalAmount]);
            
            return new OrderResource($order->load(['user', 'status', 'paymentStatus', 'orderItems.variant']));
        });
    }

    public function show(Order $order)
    {
        // Check authorization
        $user = auth()->user();
        if ($user->role->role !== 'Admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return new OrderResource($order->load(['user', 'status', 'paymentStatus', 'orderItems.variant', 'payments']));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        return DB::transaction(function () use ($request, $order) {
            // Update order status if provided
            if ($request->has('status_id')) {
                $order->update(['status_id' => $request->status_id]);
            }
            
            if ($request->has('payment_status_id')) {
                $order->update(['payment_status_id' => $request->payment_status_id]);
            }
            
            // Update order items if provided
            if ($request->has('items')) {
                // Delete existing items
                $order->orderItems()->delete();
                
                $totalAmount = 0;
                
                // Create new items
                foreach ($request->items as $item) {
                    $variant = PerfumeVariant::findOrFail($item['variant_id']);
                    
                    $subTotal = $variant->price * $item['quantity'];
                    
                    // Apply discount if provided
                    if (isset($item['discount_id'])) {
                        $discount = \App\Models\Discount::findOrFail($item['discount_id']);
                        if ($discount->discountType->name === 'Percentage') {
                            $subTotal -= ($subTotal * ($discount->value / 100));
                        } else {
                            $subTotal -= $discount->value;
                        }
                    }
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'variant_id' => $item['variant_id'],
                        'quantity' => $item['quantity'],
                        'price_at_reservation' => $variant->price,
                        'discount_id' => $item['discount_id'] ?? null,
                        'sub_total' => $subTotal,
                        'created_date' => now(),
                        'last_updated' => now(),
                    ]);
                    
                    $totalAmount += $subTotal;
                }
                
                // Update order total
                $order->update(['total_amount' => $totalAmount]);
            }
            
            $order->update(['last_updated' => now()]);
            
            return new OrderResource($order->fresh(['user', 'status', 'paymentStatus', 'orderItems.variant']));
        });
    }

    public function destroy(Order $order)
    {
        // Check authorization
        $user = auth()->user();
        if ($user->role->role !== 'Admin' && $order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}