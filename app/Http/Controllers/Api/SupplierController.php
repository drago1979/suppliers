<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SupplierResource;
use App\Http\Resources\Api\SupplierResourceCollection;
use App\Models\Supplier;
use App\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $suppliers = Supplier::all();

        return new SupplierResourceCollection($suppliers);
    }

    public function update(Supplier $supplier)
    {
        $supplier->update([
            'name' => request()->input('name')
        ]);

        return new SupplierResource($supplier->fresh());
    }

    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->name;

        $supplier->delete();

        return $this->respond('Supplier --' . $supplierName . '-- is deleted');
    }

    public function downloadCsv(Supplier $supplier)
    {
        $filePath = $this->createCsvFile($supplier);

        return Storage::download($filePath);

    }

    public function createCsvFile($supplier)
    {
        $filePath = $this->createDirectoryAndFilePath($supplier);

        $handle = fopen($filePath, 'w');

        $supplier->parts()->chunk(100, function ($users) use ($handle) {
            foreach ($users as $row) {
                fputcsv($handle, $row->toArray(), ';');
            }
        });

        fclose($handle);

        dd('done');
    }

    public function createDirectoryAndFilePath($supplier)
    {
        $directoryPath = 'app' . DIRECTORY_SEPARATOR .
            'file_parsing' . DIRECTORY_SEPARATOR .
            'download_files';

        $fileName = $supplier->id;

        // Create a folder if it does not exist
        if (!file_exists(storage_path($directoryPath))) {

            File::makeDirectory(storage_path($directoryPath), 0766, true);
        }

        // Return full $filePath
        return storage_path($directoryPath . DIRECTORY_SEPARATOR . $fileName);
    }
}
