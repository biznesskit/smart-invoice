<?php

use App\Http\Controllers\Api\v1\Supplier\SupplierController;
use App\Http\Controllers\Etims\EtimsController;
use App\Http\Controllers\Etims\ReverseInvoicingController;
use Illuminate\Support\Facades\Route;




Route::post('initialize-company-pin/{tracking_number}', [EtimsController::class,'initialize_branch']);

/*
|--------------------------------------------------------------------------
|  Farmers routes
|--------------------------------------------------------------------------
*/
Route::apiResource('suppliers',SupplierController::class);

Route::post('create-vendor',[SupplierController::class,'store']);
Route::post('create-farmer',[SupplierController::class,'store']);  // tobe deprecated
Route::post('generate-farmer-token',[ReverseInvoicingController::class,'generateSupplierToken']);
Route::post('validate-farmer-token',[ReverseInvoicingController::class,'validateSupplierToken']);
Route::post('initialize-farmer-pin',[ReverseInvoicingController::class,'initializeSupplierDevice']);
Route::post('create-reverse-invoice/{tracking_number}',[ReverseInvoicingController::class,'createReverseInvoice']);
Route::get('fetch-farmers-list/{tracking_number}', [ReverseInvoicingController::class,'selectSupplierList']);
Route::post('create-product/{tracking_number}', [EtimsController::class,'save_item']); 


