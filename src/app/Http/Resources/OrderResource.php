<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'user' => $this->when($this->relationLoaded('user'), [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ]),
            'total_amount' => $this->total_amount,
            'status' => [
                'id' => $this->status->id,
                'status' => $this->status->status,
            ],
            'payment_status' => [
                'id' => $this->paymentStatus->id,
                'status_name' => $this->paymentStatus->status_name,
            ],
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'payments' => PaymentResource::collection($this->whenLoaded('payments')),
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
            'is_active' => $this->is_active,
        ];
    }
}
