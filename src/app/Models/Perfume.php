<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfume extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'created_date',
        'last_updated',
        'is_active'
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'last_updated' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(PerfumeVariant::class);
    }
}
