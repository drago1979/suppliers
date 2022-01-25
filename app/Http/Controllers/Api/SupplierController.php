<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SupplierResource;
use App\Http\Resources\Api\SupplierResourceCollection;
use App\Models\Supplier;
use App\Traits\Api\ApiResponseTrait;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $suppliers = Supplier::all();

        return new SupplierResourceCollection($suppliers);
    }

    public function update(Supplier $supplier)
    {
         $supplier->update([
            'name' => request()->input('name'),
            'priority' => request()->input('priority')
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