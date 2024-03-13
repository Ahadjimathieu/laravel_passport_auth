<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'cardboard',
        'shopping_item_id',
        'product_id',
        'shopping_id',
        'total',
        'wholesaleprice',
    ];

    public function products()
    {
        return $this->belongTo(Product::class);
    }

    public function shoppings()
    {
        return $this->belongTo(Shopping::class);
    }
}
