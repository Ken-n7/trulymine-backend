<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'category',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function perfumes()
    {
        return $this->hasMany(Perfume::class);
    }
}
