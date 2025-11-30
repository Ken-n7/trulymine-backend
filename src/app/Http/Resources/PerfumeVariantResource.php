<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerfumeVariantResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'perfume' => $this->when($this->relationLoaded('perfume'), [
                'id' => $this->perfume->id,
                'name' => $this->perfume->name,
            ]),
            'size' => [
                'id' => $this->size->id,
                'size_ml' => $this->size->size_ml,
            ],
            'tier' => [
                'id' => $this->tier->id,
                'tier' => $this->tier->tier,
            ],
            'stock_quantity' => $this->stock_quantity,
            'price' => $this->price,
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
            'is_active' => $this->is_active,
        ];
    }
}
