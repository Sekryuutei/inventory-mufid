<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'stock',
        'price',
    ];

    /**
     * The "booted" method of the model.
     * Otomatis mengisi UUID saat produk baru dibuat.
     */
    protected static function booted(): void
    {
        static::creating(function ($product) {
            if (empty($product->uuid)) {
                $product->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Gunakan 'uuid' untuk route model binding, bukan 'id'.
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
