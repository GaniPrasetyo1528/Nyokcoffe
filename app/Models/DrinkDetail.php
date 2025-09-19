<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrinkDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'is_available', 'size', 'price', 'image'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
