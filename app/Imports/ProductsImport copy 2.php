<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToArray,WithHeadingRow
{
    public function array(array $data)
    {
        foreach ($data as $product) {
           Product::create(
            ['product_name' => $product['designation'],
            'selling_price' => $product['prix_dachat'],
            'buying_price' => $product['prix_public'],]
           );
        }
    }
}
