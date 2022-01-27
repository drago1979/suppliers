<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SupplierResource;
use App\Http\Resources\Api\SupplierResourceCollection;
use App\Models\Supplier;
use App\Traits\Api\ApiResponseTrait;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    /**
     * @return SupplierResourceCollection
     */
    public function index()
    {
        $suppliers = Supplier::all();

        return new SupplierResourceCollection($suppliers);
    }

    /**
     * @param Supplier $supplier
     * @return SupplierResource
     */
    public function update(Supplier $supplier)
    {
        $supplier->update([
            'name' => request()->input('name')
        ]);

        return new SupplierResource($supplier->fresh());
    }

    /**
     * @param Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->name;

        $supplier->delete();

        return $this->respond('Supplier --' . $supplierName . '-- is deleted');
    }
}
