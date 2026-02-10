<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MilkCollection extends Model
{
    protected $fillable = [
        'farmer_name', 'driver_name', 'vehicle_number',
        'quantity_liters', 'price_per_liter', 'total_amount',
        'collection_date', 'notes', 'user_id'
    ];

    protected $casts = [
        'collection_date' => 'datetime',
        'quantity_liters' => 'decimal:2',
        'price_per_liter' => 'decimal:2',
        'total_amount'    => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
