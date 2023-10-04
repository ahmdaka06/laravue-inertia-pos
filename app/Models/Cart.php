<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'product_id',
        'qty',
        'price',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
