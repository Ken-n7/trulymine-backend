<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfumeSize extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'size_ml',
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
        return $this->hasMany(PerfumeVariant::class, 'size_id');
    }
}
