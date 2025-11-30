<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Resellers can create their own orders, admins can create any order
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'sometimes|exists:users,id', // Admin can specify user, reseller uses auth user
            'status_id' => 'required|exists:order_status,id',
            'payment_status_id' => 'required|exists:payment_status,id',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:perfume_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount_id' => 'nullable|exists:discounts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required' => 'Order must contain at least one item',
            'items.min' => 'Order must contain at least one item',
            'items.*.variant_id.required' => 'Each item must have a variant',
            'items.*.variant_id.exists' => 'Selected variant does not exist',
            'items.*.quantity.required' => 'Quantity is required for each item',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.discount_id.exists' => 'Selected discount does not exist',
            'status_id.required' => 'Order status is required',
            'payment_status_id.required' => 'Payment status is required',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check stock availability for each item
            if ($this->has('items')) {
                foreach ($this->items as $index => $item) {
                    $variant = \App\Models\PerfumeVariant::find($item['variant_id']);
                    if ($variant && $variant->stock_quantity < $item['quantity']) {
                        $validator->errors()->add(
                            "items.{$index}.quantity",
                            "Insufficient stock. Only {$variant->stock_quantity} available."
                        );
                    }
                }
            }
        });
    }
}
