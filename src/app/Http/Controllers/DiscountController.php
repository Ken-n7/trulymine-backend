<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Http\Resources\DiscountResource;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::with('discountType')
            ->where('is_active', true)
            ->get();
        
        return DiscountResource::collection($discounts);
    }

    public function store(Request $request)
    {
        $request->validate([
            'discount_name' => 'required|string|max:100',
            'discount_code' => 'required|string|max:100|unique:discounts,discount_code',
            'discount_type_id' => 'required|exists:discount_types,id',
            'value' => 'required|numeric|min:0',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $discount = Discount::create([
            ...$request->only(['discount_name', 'discount_code', 'discount_type_id', 'value', 'description', 'start_date', 'end_date']),
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => $request->is_active ?? true,
        ]);

        return new DiscountResource($discount->load('discountType'));
    }

    public function show(Discount $discount)
    {
        return new DiscountResource($discount->load('discountType'));
    }

    public function update(Request $request, Discount $discount)
    {
        $request->validate([
            'discount_name' => 'sometimes|string|max:100',
            'discount_code' => 'sometimes|string|max:100|unique:discounts,discount_code,' . $discount->id,
            'discount_type_id' => 'sometimes|exists:discount_types,id',
            'value' => 'sometimes|numeric|min:0',
            'description' => 'sometimes|string',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after:start_date',
            'is_active' => 'sometimes|boolean',
        ]);

        $discount->update([
            ...$request->only(['discount_name', 'discount_code', 'discount_type_id', 'value', 'description', 'start_date', 'end_date', 'is_active']),
            'last_updated' => now(),
        ]);

        return new DiscountResource($discount->fresh('discountType'));
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();

        return response()->json([
            'message' => 'Discount deleted successfully'
        ]);
    }
}