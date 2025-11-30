<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
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
            'discount_name' => $this->discount_name,
            'discount_code' => $this->discount_code,
            'discount_type' => [
                'id' => $this->discountType->id,
                'name' => $this->discountType->name,
            ],
            'value' => $this->value,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
            'is_active' => $this->is_active,
        ];
    }
}
