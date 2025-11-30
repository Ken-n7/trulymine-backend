<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    public $timestamps = false;

    protected $fillable = [
        'status',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }
}
