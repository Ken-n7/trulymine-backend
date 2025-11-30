<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfumeVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can update variants
        return $this->user() && $this->user()->role->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'perfume_id' => 'sometimes|exists:perfumes,id',
            'size_id' => 'sometimes|exists:perfume_sizes,id',
            'tier_id' => 'sometimes|exists:perfume_tiers,id',
            'stock_quantity' => 'sometimes|integer|min:0',
            'price' => 'sometimes|numeric|min:0|max:99999999.99',
            'is_active' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'perfume_id.exists' => 'Selected perfume does not exist',
            'size_id.exists' => 'Selected size does not exist',
            'tier_id.exists' => 'Selected tier does not exist',
            'stock_quantity.min' => 'Stock quantity cannot be negative',
            'price.min' => 'Price cannot be negative',
            'price.max' => 'Price exceeds maximum allowed',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if variant combination already exists (excluding current variant)
            if ($this->hasAny(['perfume_id', 'size_id', 'tier_id'])) {
                $variant = $this->route('variant');

                $exists = \App\Models\PerfumeVariant::where('perfume_id', $this->perfume_id ?? $variant->perfume_id)
                    ->where('size_id', $this->size_id ?? $variant->size_id)
                    ->where('tier_id', $this->tier_id ?? $variant->tier_id)
                    ->where('id', '!=', $variant->id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'perfume_id',
                        'This variant combination (perfume + size + tier) already exists'
                    );
                }
            }
        });
    }
}
