<?php
/*
|--------------------------------------------------------------------------
| LoadSupplierProducts Command
|--------------------------------------------------------------------------
| This class:
| - contains default path for the CSV file &
| - calls the FileParser
| - Calls the class which stores products & suppliers to DB
|
*/

namespace App\Console\Commands;

use App\Services\CsvFileParser;
use App\Services\ProductsLoader;
use Illuminate\Console\Command;

class LoadSupplierProducts extends Command
{
    protected $signature = 'loadsupplierproducts';

    protected $description = 'Loads suppliers and their products from CSV file';

    /**
     * @param CsvFileParser $fileParser
     * @param ProductsLoader $productsLoader
     */
    public function handle(CsvFileParser $fileParser, ProductsLoader $productsLoader)
    {
        // Default path where the file should be stored
        $filePath = storage_path(
            'app' . DIRECTORY_SEPARATOR .
            'file_parsing' . DIRECTORY_SEPARATOR .
            'input_files' . DIRECTORY_SEPARATOR .
            'suppliers.csv'
        );


        // We ask the user to choose between default and custom path
        if ($this->confirm("### Do you want to use default path for input file ?\n\n ### Default path is:\n" . $filePath, true)) {
            $this->info("\n### You chose default path.\n");
        } else {
            $filePath = $this->ask('### Please enter custom path.');
            $this->info("\n### You chose a custom path.\n");
        }

        // Check if path exists/can be opened; If not - inform the user & abort;
        try {
            $csvContent = fopen($filePath, 'r');
        } catch (\Exception $exception) {
            $this->info("\n!!!!!! No such path or a file.\nPlease check. !!!!!!!");

            return;
        }

        // If path accessible, parse & load content & inform the user;
        $products = $fileParser->parse($csvContent);

        $productsLoader->store($products);

        $this->info("\n### Command is executed.\nPlease check for results.");
    }
}
