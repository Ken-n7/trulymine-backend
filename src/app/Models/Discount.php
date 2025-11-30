<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'discount_name',
        'discount_code',
        'discount_type_id',
        'value',
        'description',
        'start_date',
        'end_date',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function discountType()
    {
        return $this->belongsTo(DiscountType::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
