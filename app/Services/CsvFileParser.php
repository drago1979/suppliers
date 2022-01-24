<?php
/*
|--------------------------------------------------------------------------
| CSV File Parser
|--------------------------------------------------------------------------
| This class:
| - Is called by "loadsupplierproducts" artisan command
| - Parses CSV files
| - Prepares CSV content to be stored in DB (
|   adds column names to values)
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
            $products = [];

            while (($csvLine = fgetcsv($csvContent, 250, ",")) !== FALSE) {

                // Extract CSV file column names and put them into $columnNames;
                if ($row === 0) {
                    $columnNames = $csvLine;
                    $row++;

                    continue;
                }

                // Add each CSV line to $products starting with 2nd line (without column names)
                $products[] = $csvLine;
            }

            fclose($csvContent);
        }

        $this->addNamesToFields($columnNames, $products);
    }

    /**
     * @param $columnNames
     * @param $products
     */
    public function addNamesToFields($columnNames, $products)
    {
        $results = [];

        foreach ($products as $product) {
            $result = [];

            foreach ($product as $index => $productAttribute) {
                $columnName = $columnNames[$index];

                $result[$columnName] = $productAttribute;
            }

            $results[] = $result;
        }

        $loadProducts = new LoadProducts();

        $loadProducts->storeProducts($results);
    }
}
