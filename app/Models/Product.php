<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'image',
        'barcode',
        'title',
        'description',
        'buy_price',
        'sell_price',
        'stock',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    ## ACCESSOR, MUTATOR, AND CASTING

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('/storage/categories/' . $value),
        );
    }
}
