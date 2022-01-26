<?php

namespace App\Traits\Api;

trait ApiResponseTrait
{
    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data)
    {
        return response()->json($data);
    }
}
