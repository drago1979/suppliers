<?php
/*
|--------------------------------------------------------------------------
| CSV File Parser - transforms CSV file (suppliers & products)
|--------------------------------------------------------------------------
| This class:
| - Is called by "loadsupplierproducts" artisan command
| - Parses CSV files
| - Adds column names to values
| - Removes invalid rows
| - Harmonizes value types
|
*/

namespace App\Services;


class CsvFileParser
{

    /**
     * @param $csvContent
     * @return array
     */
    public function parse($csvContent)
    {
        $products = [];

        while (($csvLine = fgetcsv($csvContent, 250, ",")) !== FALSE) {
            $products[] = $csvLine;
        }

        $columnNames = array_shift($products);

        fclose($csvContent);

        $products = $this->addNamesToFields($columnNames, $products);
        $products = $this->removeInvalidProducts($products);
        $products = $this->harmonizeValueTypes($products);

        return $products;
    }

    /**
     * Add names (keys) to suppliers` and products` attributes
     *
     * @param $columnNames
     * @param $productsWithoutNames
     * @return array
     */
    public function addNamesToFields($columnNames, $productsWithoutNames)
    {
        $products = [];

        foreach ($productsWithoutNames as $productWithoutNames) {
            $product = [];

            foreach ($productWithoutNames as $index => $productAttribute) {
                $columnName = $columnNames[$index];

                $product[$columnName] = $productAttribute;
            }

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Remove products without: supplier_name & part_number & condition
     *
     * @param $productsToCheck
     * @return array
     */
    public function removeInvalidProducts($productsToCheck)
    {
        foreach ($productsToCheck as $key => $productToCheck) {
            if ($productToCheck['supplier_name'] === "" || $productToCheck['part_number'] === "" || $productToCheck['condition'] === "") {
                unset($productsToCheck[$key]);
            }
        }

        $products = array_values($productsToCheck);

        return $products;
    }

    /**
     * DB doesn`t take empty string ("") for float data type;
     * We change it to NULL
     *
     * @param $productsToHarmonize
     * @return array
     */
    public function harmonizeValueTypes($productsToHarmonize)
    {
        $products = [];

        foreach ($productsToHarmonize as $productToHarmonize) {
            $product = $productToHarmonize;

            if ($product['price'] === "") {
                $product['price'] = null;
            }

            $products[] = $product;
        }

        return $products;
    }
}
