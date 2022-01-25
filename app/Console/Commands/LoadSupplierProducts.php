<?php
/*
|--------------------------------------------------------------------------
| LoadSupplierProducts Command
|--------------------------------------------------------------------------
| This class:
| - contains default path for the CSC file &
| - calls the FileParser
|
*/

namespace App\Console\Commands;

use App\Services\CsvFileParser;
use Illuminate\Console\Command;

class LoadSupplierProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loadsupplierproducts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loads suppliers and their products from CSV file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param CsvFileParser $fileParser
     * @return int
     */
    public function handle(CsvFileParser $fileParser)
    {
        // Default path where the file should be stored
        $filePath = storage_path(
            'app' . DIRECTORY_SEPARATOR .
            'file_parsing' . DIRECTORY_SEPARATOR .
            'input_files' . DIRECTORY_SEPARATOR .
            'suppliers_test.csv'
        );

        // We ask the user to choose between default and custom path
        $this->confirm("### Do you want to use default path for input file ?\n\n ### Default path is:\n" . $filePath) ? :
            $filePath = $this->ask('### Please enter custom path.');

        $fileParser->parseFile($filePath);

        $this->info("\n### Command is executed.\nPlease check for results.");

    }
}
