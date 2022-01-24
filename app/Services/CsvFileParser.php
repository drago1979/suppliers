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
|
*/

namespace App\Services;


class CsvFileParser
{

    public function parseFile($filePath)
    {
        if (($csvContent = fopen($filePath, 'r')) !== FALSE) {
            $row = 0;
            $columnNames = [];
            $products = [];

            while (($csvLine = fgetcsv($csvContent, 250, ",")) !== FALSE) {

                if ($row === 0) {
                    $columnNames = $csvLine;
                    $row++;

                    continue;
                }

                $products[] = $csvLine;
            }

            fclose($csvContent);
        }

    }
}
