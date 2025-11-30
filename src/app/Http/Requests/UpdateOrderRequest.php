<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $order = $this->route('order');
        $user = $this->user();

        // Admin can update any order, resellers can only update their own orders
        if ($user->role->role === 'Admin') {
            return true;
        }

        // Resellers can only update their own orders that are not yet approved
        return $order->user_id === $user->id &&
            $order->status->status === 'Pending';
    }

    public function rules(): array
    {
        return [
            'status_id' => 'sometimes|exists:order_status,id',
            'payment_status_id' => 'sometimes|exists:payment_status,id',
            'items' => 'sometimes|array|min:1',
            'items.*.id' => 'sometimes|exists:order_items,id',
            'items.*.variant_id' => 'required|exists:perfume_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.discount_id' => 'nullable|exists:discounts,id',
        ];
    }

    public function messages(): array
    {
        return [
            'items.min' => 'Order must contain at least one item',
            'items.*.variant_id.required' => 'Each item must have a variant',
            'items.*.variant_id.exists' => 'Selected variant does not exist',
            'items.*.quantity.required' => 'Quantity is required for each item',
            'items.*.quantity.min' => 'Quantity must be at least 1',
            'items.*.discount_id.exists' => 'Selected discount does not exist',
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
