<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'buying_price',
        'selling_price',
        'quantity',
        'description',
        'shopping_date',
        'shopping_id',
        'shopping_number',
        'total',
        'user_id',
    ];


    public function users()
    {
        return $this->belongTo(User::class);
    }

    public function shopping_items()
    {
        return $this->hasMany(Shopping_item::class);
    }
}
