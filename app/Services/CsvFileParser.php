<?php
/*
|--------------------------------------------------------------------------
| CSV File Parser - transforms CSV file (suppliers & products)
|--------------------------------------------------------------------------
| This class:
| - Is called by "loadsupplierproducts" artisan command
| - Parses CSV files
| - Prepares CSV content to be stored in DB (
|   adds column names to values)
| - Removes invalid rows
| - Calls the class in charge for storing products & suppliers to DB
|
*/

namespace App\Services;


class CsvFileParser
{

    /**
     * @param $filePath
     */
    public function parseFile($filePath)
    {
        if (($csvContent = fopen($filePath, 'r')) !== FALSE) {
            $row = 0;
            $columnNames = [];
            $productsWithoutNames = [];

            while (($csvLine = fgetcsv($csvContent, 250, ",")) !== FALSE) {

                // Extract CSV file column names and put them into $columnNames;
                if ($row === 0) {
                    $columnNames = $csvLine;
                    $row++;

                    continue;
                }

                // Add each CSV line to $productsWithoutNames starting with 2nd line (without column names)
                $productsWithoutNames[] = $csvLine;
            }

            fclose($csvContent);
        }

        $this->addNamesToFields($columnNames, $productsWithoutNames);
    }

    /**
     * @param $columnNames
     * @param $products
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

        $this->removeInvalidProducts($products);
    }

    /**
     * @param $productsToCheck
     */
    public function removeInvalidProducts($productsToCheck)
    {
        // Remove the product without: supplier_name & part_number & condition
        foreach ($productsToCheck as $key => $productToCheck) {
            if ($productToCheck['supplier_name'] === "" || $productToCheck['part_number'] === "" || $productToCheck['condition'] === "") {
                unset($productsToCheck[$key]);
            }
        }

        $products = array_values($productsToCheck);

        $productsLoader = new ProductsLoader();
        $productsLoader->store($products);
    }
}
