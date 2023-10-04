<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'customer_id',
        'invoice',
        'cash',
        'change',
        'discount',
        'grand_total',
    ];

    public function cashier(): BelongsTo
    {
        return $this->belongsTo(Cashier::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function details(): HasMany
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function profit(): HasMany
    {
        return $this->hasMany(Profit::class);
    }

    ## ACCESSOR, MUTATOR, AND CASTING

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-M-Y H:i:s'),
        );
    }

}
