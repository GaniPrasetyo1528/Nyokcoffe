<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug',
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function drinkdetail(){
        return $this->hasMany(DrinkDetail::class);
    }

    public function fooddetail(){
        return $this->hasMany(FoodDetail::class);
    }

    public function scopeByCategory($query, $kategori)
    {
        return $query->when($kategori, function ($query, $kategori) {
            return $query->whereHas('category', function ($q) use ($kategori) {
                $q->where('name', $kategori);
            });
        });
    }
}
