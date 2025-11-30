<?php

namespace App\Http\Controllers;

use App\Models\Perfume;
use App\Http\Resources\PerfumeResource;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    public function index()
    {
        $perfumes = Perfume::with(['category', 'variants.size', 'variants.tier'])
            ->where('is_active', true)
            ->get();
        
        return PerfumeResource::collection($perfumes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:perfumes,name',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $perfume = Perfume::create([
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'created_date' => now(),
            'last_updated' => now(),
            'is_active' => $request->is_active ?? true,
        ]);

        return new PerfumeResource($perfume->load('category'));
    }

    public function show(Perfume $perfume)
    {
        return new PerfumeResource($perfume->load(['category', 'variants.size', 'variants.tier']));
    }

    public function update(Request $request, Perfume $perfume)
    {
        $request->validate([
            'name' => 'sometimes|string|max:100|unique:perfumes,name,' . $perfume->id,
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|exists:categories,id',
            'is_active' => 'sometimes|boolean',
        ]);

        $perfume->update([
            ...$request->only(['name', 'description', 'category_id', 'is_active']),
            'last_updated' => now(),
        ]);

        return new PerfumeResource($perfume->fresh('category'));
    }

    public function destroy(Perfume $perfume)
    {
        $perfume->delete();

        return response()->json([
            'message' => 'Perfume deleted successfully'
        ]);
    }
}