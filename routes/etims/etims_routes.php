<?php

use App\Http\Controllers\Etims\EtimsController;
use Illuminate\Support\Facades\Route;

Route::get('select-item-classification-list', [EtimsController::class,'select_item_classification_list']); // to be depracated
Route::get('fetch-item-classification-codes', [EtimsController::class,'select_item_classification_list']);

Route::get('select-code-list', [EtimsController::class,'select_code_list']); // to be depracated
Route::get('fetch-taxation-codes', [EtimsController::class,'select_code_list']);

Route::get('select-notice-list', [EtimsController::class,'select_notice_list']); // to be depracated
Route::get('fetch-notices', [EtimsController::class,'select_notice_list']);

Route::get('select-customer-list/{tracking_number}', [EtimsController::class,'select_customer_list']); // to be depracated
Route::get('fetch-customer/{tracking_number}', [EtimsController::class,'select_customer_list']);

Route::get('select-item-list/{tracking_number}',[EtimsController::class,'select_item_list']); // to be depracated
Route::get('fetch-products-list/{tracking_number}',[EtimsController::class,'select_item_list']);

Route::get('select-branch-list', [EtimsController::class,'select_branch_list']);  // to be depracated
Route::get('fetch-branches', [EtimsController::class,'select_branch_list']);

Route::get('select-import-list/{tracking_number}', [EtimsController::class,'select_import_list']);
Route::get('select-purchase-list/{tracking_number}',[EtimsController::class,'select_purchase_list']);

Route::get('select-stock-movement-list/{tracking_number}', [EtimsController::class,'select_stock__movement_list']); // to be depracated
Route::get('fetch-stock-movement-list/{tracking_number}', [EtimsController::class,'select_stock__movement_list']);

Route::get('select-sales-list/{tracking_number}', [EtimsController::class,'select_sales_list']); // to be depracated
Route::get('fetch-sales-list/{tracking_number}', [EtimsController::class,'select_sales_list']);
Route::get('select-stock-movement-list/{tracking_number}', [EtimsController::class,'select_stock__movement_list']);
Route::get('select-sales-list/{tracking_number}', [EtimsController::class,'select_sales_list']); // tobe deprecated
Route::get('fetch-invoices/{tracking_number}', [EtimsController::class,'select_sales_list']);


Route::post('initialize-branch/{tracking_number}', [EtimsController::class,'initialize_branch']);
Route::post('initialize-pin/{tracking_number}', [EtimsController::class,'initialize_branch']);
Route::post('update-import-item/{tracking_number}/{item}', [EtimsController::class,'update_import_item']);
Route::post('save-branch-customer/{tracking_number}', [EtimsController::class,'save_branch_customer']);
Route::post('save-branch-user/{tracking_number}', [EtimsController::class,'save_branch_user']);
Route::post('save-branch-insurance/{tracking_number}', [EtimsController::class,'save_branch_insurance']);
Route::post('save-item/{tracking_number}', [EtimsController::class,'save_item']);

// Route::post('decrease-item-inventory/{branch}',[EtimsController::class,'decrease_item_inventory']);

Route::post('save-stock-io/{tracking_number}',[EtimsController::class,'save_stock_io']);
Route::post('save-item-composition/{tracking_number}', [EtimsController::class,'save_item_composition']);
Route::post('save-transaction-sale/{tracking_number}', [EtimsController::class,'save_transaction_sale']);
Route::post('create-credit-note/{tracking_number}', [EtimsController::class,'create_credit_note']);




