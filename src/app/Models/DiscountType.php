<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountType extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description'
    ];

    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }
}
