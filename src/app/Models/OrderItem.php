<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'variant_id',
        'quantity',
        'price_at_reservation',
        'discount_id',
        'sub_total',
        'created_date',
        'last_updated'
    ];

    protected $casts = [
        'price_at_reservation' => 'decimal:2',
        'sub_total' => 'decimal:2',
        'created_date' => 'datetime',
        'last_updated' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function variant()
    {
        return $this->belongsTo(PerfumeVariant::class, 'variant_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
}
