<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'description',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    ## ACCESSOR, MUTATOR, AND CASTING

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => asset('/storage/categories/' . $value),
        );
    }
}
