<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'is_available', 'level', 'price', 'image'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}
