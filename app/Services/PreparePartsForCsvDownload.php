<?php


namespace App\Services;


use App\Models\Part;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PreparePartsForCsvDownload
{
    public function createCsvFile($supplierId)
    {
        $filePath = $this->createDirectoryAndFilePath($supplierId);

        $handle = fopen($filePath, 'w');

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

                // Write column names to CSV file
                $columnNames = array_keys($supplierParts[0]->attributesToArray());
                fputcsv($handle, $columnNames);

                foreach ($supplierParts as $row) {
                    fputcsv($handle, $row->toArray());
                }
            });

        fclose($handle);

//        dd('done');
//        dd($filePath);

        return $filePath;
    }

    public function createDirectoryAndFilePath($supplierId)
    {
        $fileName = $this->createFileName($supplierId);

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

//        dd($filePath);

        return $filePath;
    }

    public function createFileName($supplierId)
    {
        // Get suppliers` name; replace non characters with underscores
        $supplierName = preg_replace('/[^a-zA-Z0-9]+/', '_', Supplier::find($supplierId)->name);

        $timeStamp = Carbon::now()->format('Y_m_d-H_m_s');

        $filePath = $supplierName . '_' . $timeStamp . '.csv';

        Cache::forget($timeStamp);

        var_dump($filePath);
        dd();
        return $filePath;
    }
}
