<?php


namespace App\Services;


use App\Models\Supplier;

class ProductsLoader
{
    public function store($products)
    {
        dd($products);

// !!!! Check if input product has supplier_name & part number & condition; If no - ignore it


//        dd($products);

        // Persist data to DB
        foreach ($products as $product) {
            $supplier = Supplier::firstOrCreate([
                'name' => $product['supplier_name']
            ]);

            $supplier->parts()->create([
                'name' => $product['supplier_name'],
                'supplier_id' => $supplier->id,
//                'condition_id' => $product['supplier_name'],
//                'category_id' => $product['supplier_name'],
                'days_valid' => $product['days_valid'],
                'priority' => $product['priority'],
                'part_number' => $product['part_number'],
                'part_description' => $product['part_desc'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ]);
        }
    }
}
