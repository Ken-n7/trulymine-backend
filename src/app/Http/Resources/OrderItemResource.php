<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
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
            'variant' => new PerfumeVariantResource($this->whenLoaded('variant')),
            'quantity' => $this->quantity,
            'price_at_reservation' => $this->price_at_reservation,
            'discount' => $this->when(
                $this->relationLoaded('discount') && $this->discount,
                [
                    'id' => $this->discount?->id,
                    'discount_code' => $this->discount?->discount_code,
                    'value' => $this->discount?->value,
                ]
            ),
            'sub_total' => $this->sub_total,
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
        ];
    }
}
