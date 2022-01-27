<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PartResource;
use App\Http\Resources\Api\PartResourceCollection;
use App\Models\Part;
use App\Traits\Api\ApiResponseTrait;

class PartController extends Controller
{
    use ApiResponseTrait;


    /**
     *  VARIJANTA:
     * - Zadovoljava zahtev dobijen u zadatku
     * - URI query format: ?supplier_id[]=1 (&supplier_id[]=2 .... itd)
     * - Omogucava filtriranje po vise supplier-a
     * - Omogucava uvodjenje dodatnih filtera izmenom
     *   koda u Part\scopeFilterSupplierId()
     * - Zahteva dodatni kod na modelu
     *
     * @return PartResourceCollection
     */
    public function index()
    {
        $parts = Part::filterSupplierId(request()->input('supplier_id'))
            ->get();

        /* ------------------------------------------------------------------------ *
         *           VARIJANTA:                                                     *
         *                                                                          *
         *                                                                          *
         * - URI query format: ?supplier_id=1                                       *
         * - Ograniceno na jednog supplier-a                                        *
        */

        //        $supplierId = request()->input('supplier_id');
        //
        //        if(!empty($supplierId)){
        //            $parts = Part::where('supplier_id', $supplierId)->get();
        //        } else {
        //            $parts = Part::all();
        //        }
        // ------------------------------------------------------------------------ *

        return new PartResourceCollection($parts);
    }

    /**
     * @param Part $part
     * @return PartResource
     */
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

    /**
     * @param Part $part
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Part $part)
    {
        $partNumber = $part->part_number;

        $part->delete();

        return $this->respond('Part number -- ' . $partNumber . ' -- is deleted');
    }
}
