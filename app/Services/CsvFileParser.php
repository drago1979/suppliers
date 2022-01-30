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
    protected $products = [];

    /**
     * @param $csvContent
     * @return array
     */
    public function parse($csvContent)
    {
        $this->getContentFromCsv($csvContent)
            ->addNamesToFields()
            ->removeInvalidProducts()
            ->harmonizeValueTypes();

        return $this->products;
    }

    /**
     * @param $csvContent
     * @return $this
     */
    protected function getContentFromCsv($csvContent)
    {
        while (($csvLine = fgetcsv($csvContent, 250, ",")) !== FALSE) {
            $this->products[] = $csvLine;
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function addNamesToFields()
    {
        $columnNames = array_shift($this->products);

        foreach ($this->products as &$productWithoutNames) {

            foreach ($productWithoutNames as $index => $productAttribute) {
                $columnName = $columnNames[$index];

                $productWithoutNames[$columnName] = $productAttribute;
                unset($productWithoutNames[$index]);
            }
        }

        return $this;
    }


    /**
     * @return $this
     */
    protected function removeInvalidProducts()
    {
        foreach ($this->products as $key => $productToCheck) {
            if ($productToCheck['supplier_name'] === "" || $productToCheck['part_number'] === "" || $productToCheck['condition'] === "") {
                unset($this->products[$key]);
            }
        }

        array_values($this->products);

        return $this;
    }


    /**
     * DB doesn`t take empty string ("") for float data type;
     * We change it to NULL
     */
    protected function harmonizeValueTypes()
    {
        foreach ($this->products as &$productToHarmonize) {
            if ($productToHarmonize['price'] === "") {
                $productToHarmonize['price'] = null;
            }
        }
    }
}
