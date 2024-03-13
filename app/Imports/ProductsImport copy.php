<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel,WithHeadingRow, WithBatchInserts, WithChunkReading
{
    public function model(array $product)
    {

           return Product::updateOrCreate(
            [
                'product_name' => $product['designation'],
           'selling_price' => $product['prix_dachat'],
           'buying_price' => $product['prix_public'],
            ]);

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
