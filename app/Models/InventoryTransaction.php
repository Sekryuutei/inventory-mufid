<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'notes',
        'transaction_date'
    ];

protected $casts = [
    'transaction_date' => 'datetime'
];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}