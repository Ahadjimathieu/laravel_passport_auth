<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'quantity',
        'selling_price',
        'description',
        'discount',
        'product_id',
        'product_status',
        'reference',
        'manufacturing_date',
        'expiry_date',
    ];
    public function shopping_items()
    {
        return $this->hasMany(Shopping_item::class);
    }


    public function stocks()
    {
        return $this->hasOne(Stock::class);
    }


}
