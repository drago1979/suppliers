<?php


namespace App\Traits\Api;


trait ApiResponseTrait
{
    public function respond($data)
    {
        return response()->json($data, 200);
    }
}
