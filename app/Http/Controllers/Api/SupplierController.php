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

    public function index(Request $request)
    {
        $list = Supplier::all();

        return new SupplierResourceCollection($list);
    }

    public function update(Supplier $supplier)
    {
         $supplier->update([
            'name' => request()->get('name'),
            'priority' => request()->get('priority')
        ]);

        return new SupplierResource($supplier->fresh());
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return $this->respond('Supplier is deleted');
    }
}
