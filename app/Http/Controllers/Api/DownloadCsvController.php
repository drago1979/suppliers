<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Part;
use App\Models\Supplier;
use App\Services\PreparePartsForCsvDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DownloadCsvController extends Controller
{
    public function downloadCsv($supplierId)
    {
        $filePath = (new PreparePartsForCsvDownload())->createCsvFile($supplierId);

        return response()->download($filePath);
    }
}
