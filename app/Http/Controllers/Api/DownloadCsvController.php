<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PreparePartsForCsvDownload;
use Illuminate\Support\Facades\File;

class DownloadCsvController extends Controller
{
    /**
     * @param $supplierId
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($supplierId)
    {
        $filePath = (new PreparePartsForCsvDownload())->createCsvFile($supplierId);

        return response()->download($filePath)->deleteFileAfterSend();
    }
}
