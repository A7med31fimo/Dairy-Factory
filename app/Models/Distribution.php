<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distribution extends Model
{
    protected $fillable = [
        'shop_name', 'driver_name', 'vehicle_number',
        'total_value', 'delivery_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'delivery_date' => 'datetime',
        'total_value'   => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(DistributionItem::class);
    }
}
