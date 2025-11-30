<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can record payments
        return $this->user() && $this->user()->role->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01|max:99999999.99',
            'payment_mode_id' => 'required|exists:payment_modes,id',
            'reference_number' => 'required|string|max:100|unique:payments,reference_number',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Order is required',
            'order_id.exists' => 'Selected order does not exist',
            'amount.required' => 'Payment amount is required',
            'amount.min' => 'Payment amount must be greater than 0',
            'amount.max' => 'Payment amount exceeds maximum allowed',
            'payment_mode_id.required' => 'Payment mode is required',
            'payment_mode_id.exists' => 'Selected payment mode does not exist',
            'reference_number.required' => 'Reference number is required',
            'reference_number.unique' => 'This reference number has already been used',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if payment amount doesn't exceed order total
            if ($this->has('order_id') && $this->has('amount')) {
                $order = \App\Models\Order::find($this->order_id);
                if ($order) {
                    $totalPaid = $order->payments()->sum('amount');
                    $remaining = $order->total_amount - $totalPaid;

                    if ($this->amount > $remaining) {
                        $validator->errors()->add(
                            'amount',
                            "Payment amount exceeds remaining balance. Remaining: " . number_format($remaining, 2)
                        );
                    }
                }
            }
        });
    }
}
