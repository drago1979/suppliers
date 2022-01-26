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
}
