<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PreparePartsForCsvDownload;

class DownloadCsvController extends Controller
{
    public function download($supplierId)
    {
        $filePath = (new PreparePartsForCsvDownload())->createCsvFile($supplierId);

        return response()->download($filePath);
    }
}
