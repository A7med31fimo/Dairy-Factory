<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DistributionItem extends Model
{
    protected $fillable = [
        'distribution_id', 'product_name', 'quantity',
        'unit', 'unit_price', 'subtotal'
    ];

    protected $casts = [
        'quantity'   => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    public function distribution()
    {
        return $this->belongsTo(Distribution::class);
    }
}
