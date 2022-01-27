<?php
/*
|--------------------------------------------------------------------------
| PreparePartsForCsvDownload:
|--------------------------------------------------------------------------
| This class:
| - Creates directory and file path
| - Deletes a file with the same name if exists
| - Creates & stores CSV file
|
*/

namespace App\Services;

use App\Models\Part;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PreparePartsForCsvDownload
{
    /**
     * @param $supplierId
     * @return string
     */
    public function prepare($supplierId)
    {
        $fileName = $this->createFileName($supplierId);

        $filePath = $this->createDirectoryAndFilePath($fileName);

        $this->createCsvFile($filePath, $supplierId);

        return $filePath;
    }

    /**
     * @param $supplierId
     * @return string
     */
    public function createFileName($supplierId)
    {
        // Get suppliers` name; replace non characters with underscores
        $supplierName = preg_replace('/[^a-zA-Z0-9]+/', '_', Supplier::find($supplierId)->name);

        $timeStamp = Carbon::now()->format('Y_m_d-H_i');

        return $supplierName . '_' . $timeStamp . '.csv';
    }

    /**
     * @param $fileName
     * @return string
     */
    public function createDirectoryAndFilePath($fileName)
    {
        // Create Directory Path & File Path
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

        return $filePath;
    }

    /**
     * @param $filePath
     * @param $supplierId
     */
    public function createCsvFile($filePath, $supplierId)
    {
        $handle = fopen($filePath, 'w');

        // Create column names list & write them to CSV file
        $columnNames = ['supplier_name', 'part_number', 'part_description', 'quantity', 'price', 'condition', 'category'];
        fputcsv($handle, $columnNames);

        // Write parts information to CSV file
        Part::query()
            ->select([
                'suppliers.name AS supplier_name',
                'parts.part_number',
                'parts.part_description',
                'parts.quantity',
                'parts.price',
                'conditions.name AS condition',
                'categories.name AS category',
            ])
            ->join('suppliers', function ($query) use ($supplierId) {
                $query->on('parts.supplier_id', '=', 'suppliers.id')
                    ->on('suppliers.id', '=', DB::raw($supplierId));
            })
            ->join('conditions', 'parts.condition_id', '=', 'conditions.id')
            ->leftJoin('categories', 'parts.category_id', '=', 'categories.id')
            ->chunk(100, function ($supplierParts) use ($handle) {

                foreach ($supplierParts as $row) {
                    fputcsv($handle, $row->toArray());
                }
            });

        fclose($handle);
    }
}
