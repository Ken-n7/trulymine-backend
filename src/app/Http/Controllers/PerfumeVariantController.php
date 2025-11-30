<?php

namespace App\Http\Controllers;

use App\Models\PerfumeVariant;
use App\Http\Resources\PerfumeVariantResource;
use App\Http\Requests\StorePerfumeVariantRequest;
use App\Http\Requests\UpdatePerfumeVariantRequest;

class PerfumeVariantController extends Controller
{
    public function index()
    {
        $variants = PerfumeVariant::with(['perfume', 'size', 'tier'])
            ->where('is_active', true)
            ->get();
        
        return PerfumeVariantResource::collection($variants);
    }

    public function store(StorePerfumeVariantRequest $request)
    {
        $variant = PerfumeVariant::create([
            'perfume_id' => $request->perfume_id,
            'size_id' => $request->size_id,
            'tier_id' => $request->tier_id,
            'stock_quantity' => $request->stock_quantity,
            'price' => $request->price,
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => $request->is_active ?? true,
        ]);

        return new PerfumeVariantResource($variant->load(['perfume', 'size', 'tier']));
    }

    public function show(PerfumeVariant $variant)
    {
        return new PerfumeVariantResource($variant->load(['perfume', 'size', 'tier']));
    }

    public function update(UpdatePerfumeVariantRequest $request, PerfumeVariant $variant)
    {
        $variant->update([
            ...$request->only(['perfume_id', 'size_id', 'tier_id', 'stock_quantity', 'price', 'is_active']),
            'last_updated' => now(),
        ]);

        return new PerfumeVariantResource($variant->fresh(['perfume', 'size', 'tier']));
    }

    public function destroy(PerfumeVariant $variant)
    {
        $variant->delete();

        return response()->json([
            'message' => 'Variant deleted successfully'
        ]);
    }
}