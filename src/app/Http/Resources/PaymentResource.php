<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'order_id' => $this->order_id,
            'amount' => $this->amount,
            'payment_mode' => [
                'id' => $this->paymentMode->id,
                'mode' => $this->paymentMode->mode,
            ],
            'reference_number' => $this->reference_number,
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
            'is_active' => $this->is_active,
        ];
    }
}
