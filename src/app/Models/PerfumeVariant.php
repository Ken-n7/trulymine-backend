<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfumeVariant extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'perfume_id',
        'size_id',
        'tier_id',
        'stock_quantity',
        'price',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function perfume()
    {
        return $this->belongsTo(Perfume::class);
    }

    public function size()
    {
        return $this->belongsTo(PerfumeSize::class, 'size_id');
    }

    public function tier()
    {
        return $this->belongsTo(PerfumeTier::class, 'tier_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'variant_id');
    }
}
