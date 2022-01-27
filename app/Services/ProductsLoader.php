<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Condition;
use App\Models\Supplier;

class ProductsLoader
{
    /**
     * @param $products
     */
    public function store($products)
    {
        // Persist data to DB
        foreach ($products as $product) {
            $supplier = Supplier::firstOrCreate(
                ['name' => $product['supplier_name']],
                ['priority' => $product['priority']]
            );

            $condition = Condition::firstOrCreate([
                'name' => $product['condition']
            ]);

            $category = Category::firstOrCreate([
                'name' => $product['category']
            ]);

            $supplier->parts()->create([
                'supplier_id' => $supplier->id,
                'condition_id' => $condition->id,
                'category_id' => $category->id,
                'days_valid' => $product['days_valid'],
                'part_number' => $product['part_number'],
                'part_description' => $product['part_desc'],
                'quantity' => $product['quantity'],
                'price' => $product['price']
            ]);
        }
    }
}
