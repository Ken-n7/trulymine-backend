<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'payment_status';
    public $timestamps = false;

    protected $fillable = [
        'status_name',
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
        return $this->hasMany(Order::class, 'payment_status_id');
    }
}
