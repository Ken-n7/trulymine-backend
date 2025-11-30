<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfumeTier extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tier',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function variants()
    {
        return $this->hasMany(PerfumeVariant::class, 'tier_id');
    }
}
