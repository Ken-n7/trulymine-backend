<?php

// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Resources\PaymentResource;
use App\Http\Requests\StorePaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $query = Payment::with(['paymentMode']);
        
        // Resellers can only see payments for their own orders
        if ($user->role->role !== 'Admin') {
            $query->whereHas('order', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $payments = $query->orderBy('created_date', 'desc')->get();
        
        return PaymentResource::collection($payments);
    }

    public function store(StorePaymentRequest $request)
    {
        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'payment_mode_id' => $request->payment_mode_id,
            'reference_number' => $request->reference_number,
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => $request->is_active ?? true,
        ]);

        // Check if order is fully paid and update payment status
        $order = $payment->order;
        $totalPaid = $order->payments()->sum('amount');
        
        if ($totalPaid >= $order->total_amount) {
            $paidStatus = \App\Models\PaymentStatus::where('status_name', 'Paid')->first();
            if ($paidStatus) {
                $order->update(['payment_status_id' => $paidStatus->id]);
            }
        } elseif ($totalPaid > 0) {
            $partialStatus = \App\Models\PaymentStatus::where('status_name', 'Partial')->first();
            if ($partialStatus) {
                $order->update(['payment_status_id' => $partialStatus->id]);
            }
        }

        return new PaymentResource($payment->load('paymentMode'));
    }

    public function show(Payment $payment)
    {
        // Check authorization
        $user = auth()->user();
        if ($user->role->role !== 'Admin' && $payment->order->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return new PaymentResource($payment->load('paymentMode'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'amount' => 'sometimes|numeric|min:0.01|max:99999999.99',
            'payment_mode_id' => 'sometimes|exists:payment_modes,id',
            'reference_number' => 'sometimes|string|max:100|unique:payments,reference_number,' . $payment->id,
            'is_active' => 'sometimes|boolean',
        ]);

        $payment->update([
            ...$request->only(['amount', 'payment_mode_id', 'reference_number', 'is_active']),
            'last_updated' => now(),
        ]);

        return new PaymentResource($payment->fresh('paymentMode'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return response()->json([
            'message' => 'Payment deleted successfully'
        ]);
    }
}