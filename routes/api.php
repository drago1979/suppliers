<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| Default Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Custom  Routes
|--------------------------------------------------------------------------
*/

// Suppliers
Route::get('suppliers', [App\Http\Controllers\Api\SupplierController::class, 'index']);
Route::patch('suppliers/{supplier}', [App\Http\Controllers\Api\SupplierController::class, 'update']);
Route::delete('suppliers/{supplier}', [App\Http\Controllers\Api\SupplierController::class, 'destroy']);
Route::get('suppliers/{supplier}/download_supplier_parts_csv', [App\Http\Controllers\Api\SupplierController::class, 'downloadCsv']);



// Parts
Route::get('parts', [App\Http\Controllers\Api\PartController::class, 'index']);
Route::patch('parts/{part}', [App\Http\Controllers\Api\PartController::class, 'update']);
Route::delete('parts/{part}', [App\Http\Controllers\Api\PartController::class, 'destroy']);
