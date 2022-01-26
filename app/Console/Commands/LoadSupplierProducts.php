<?php
/*
|--------------------------------------------------------------------------
| LoadSupplierProducts Command
|--------------------------------------------------------------------------
| This class:
| - contains default path for the CSV file &
| - calls the FileParser
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
        $this->confirm("### Do you want to use default path for input file ?\n\n ### Default path is:\n" . $filePath, true) ?:
            $filePath = $this->ask('### Please enter custom path.');

        if (($csvContent = fopen($filePath, 'r')) !== FALSE) {
            $products = $fileParser->parse($csvContent);

            $productsLoader->store($products);

            $this->info("\n### Command is executed.\nPlease check for results.");
        } else {
            $this->info("\n### No such path or a file.\nPlease check.");
        }

    }
}
