<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadCsvController extends Controller
{
    public function downloadCsv($supplierId)
    {

        $partsForCsv = (new Part())->getPartsForCsvDownloadFile($supplierId);


        $filePath = $this->createCsvFile($supplierId);

        return Storage::download($filePath);

    }

    public function createCsvFile($supplierId)
    {
        $supplier = new Supplier();
        $supplier = $supplier->getSupplierWithPartsForCsvExport($supplierId);


        $filePath = $this->createDirectoryAndFilePath($supplier);

        $handle = fopen($filePath, 'w');

        // Write column names to CSV file
        $columnNames = array_keys($supplier->parts()->first()->attributesToArray());
        fputcsv($handle, $columnNames);

        // Write parts information to CSV file
        $supplier->parts()->chunk(100, function ($supplierParts) use ($handle) {
            foreach ($supplierParts as $row) {
                fputcsv($handle, $row->toArray());
            }
        });

        fclose($handle);

        dd('done');
    }

    public function createDirectoryAndFilePath($supplier)
    {
        $fileName = $supplier->id . '.csv';
        $directoryPath = storage_path(
            'app' . DIRECTORY_SEPARATOR .
            'file_parsing' . DIRECTORY_SEPARATOR .
            'download_files'
        );
        $filePath = $directoryPath . DIRECTORY_SEPARATOR . $fileName;

        // If there is a file with the same name - delete it
        if (file_exists($filePath)) {
            File::delete($filePath);
        }

        // Create a folder if it does not exist
        if (!file_exists($directoryPath)) {
            File::makeDirectory($directoryPath, 0766, true);
        }

        // Return full $filePath
        return $filePath;
    }
}
