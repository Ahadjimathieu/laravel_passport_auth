<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Shopping;
use App\Models\Shopping_item;
use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToArray,WithHeadingRow
{
    public function array(array $data)
    {
        foreach ($data as $product) {
            // Validation des données
            $shopping = Shopping::updateOrCreate(
                [
                    'product_name' => $product['designation']
                ],
                [
                    'shopping_id' => Str::random(7),
                    'shopping_number' => 'Sho' . (Shopping::count() + 1),
                    'buying_price' => $product['prix_dachat'],
                    'selling_price' => $product['prix_public']
                ]
            );

        //     //Create or update  product
            $product = Product::updateOrCreate(
                [
                    'product_name' => $product['designation']
                ],
                [
                    'product_id' => Str::random(7),
                    'product_name' => $product['designation'],
                    'selling_price' => $product['prix_public'],
                    //Default image
                ]
            );
        //     $product->save();

            $stock = new Stock();
            $stock->product_id = $product->id;
            $stock->stock_code =  Str::random(7);

            $product = Product::find($product->id);
            $product->stocks()->save($stock);

            Shopping_item::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'shopping_id' => $shopping->id,
                ],
                [
                    'shopping_item_id' => Str::random(7),
                    'product_id' => $product->id,
                    'shopping_id' => $shopping->id,
                ]
            );
         }

    }
}
