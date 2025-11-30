<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerfumeVariantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin can create variants
        return $this->user() && $this->user()->role->role === 'Admin';
    }

    public function rules(): array
    {
        return [
            'perfume_id' => 'required|exists:perfumes,id',
            'size_id' => 'required|exists:perfume_sizes,id',
            'tier_id' => 'required|exists:perfume_tiers,id',
            'stock_quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0|max:99999999.99',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'perfume_id.required' => 'Perfume is required',
            'perfume_id.exists' => 'Selected perfume does not exist',
            'size_id.required' => 'Size is required',
            'size_id.exists' => 'Selected size does not exist',
            'tier_id.required' => 'Tier is required',
            'tier_id.exists' => 'Selected tier does not exist',
            'stock_quantity.required' => 'Stock quantity is required',
            'stock_quantity.min' => 'Stock quantity cannot be negative',
            'price.required' => 'Price is required',
            'price.min' => 'Price cannot be negative',
            'price.max' => 'Price exceeds maximum allowed',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Check if this variant combination already exists
            $exists = \App\Models\PerfumeVariant::where('perfume_id', $this->perfume_id)
                ->where('size_id', $this->size_id)
                ->where('tier_id', $this->tier_id)
                ->exists();

            if ($exists) {
                $validator->errors()->add(
                    'perfume_id',
                    'This variant combination (perfume + size + tier) already exists'
                );
            }
        });
    }
}
