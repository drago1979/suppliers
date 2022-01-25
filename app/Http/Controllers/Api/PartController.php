<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartResource;
use App\Http\Resources\Api\PartResourceCollection;
use App\Models\Part;
use App\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;

class PartController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $parts = Part::all();

        return new PartResourceCollection($parts);
    }

    public function show(Part $part)
    {
        return new PartResource($part);
    }

    public function update(Part $part)
    {
        $part->update([
            'supplier_id' => request()->input('supplier_id'),
            'condition_id' => request()->input('condition_id'),
            'category_id' => request()->input('category_id'),
            'days_valid' => request()->input('days_valid'),
            'part_number' => request()->input('part_number'),
            'part_description' => request()->input('part_description'),
            'quantity' => request()->input('quantity'),
            'price' => request()->input('price'),
        ]);

        return new PartResource($part->fresh());
    }

    public function destroy(Part $part)
    {
        $partNumber = $part->part_number;

        $part->delete();

        return $this->respond('Part number -- ' . $partNumber . ' -- is deleted');
    }
}
