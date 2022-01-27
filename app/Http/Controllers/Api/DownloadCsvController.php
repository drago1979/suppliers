<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PreparePartsForCsvDownload;

class DownloadCsvController extends Controller
{
    /**
     * @param $supplierId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($supplierId)
    {
        $filePath = (new PreparePartsForCsvDownload())->prepare($supplierId);

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
