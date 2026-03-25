<?php

use App\Http\Controllers\Api\v1\Auth\RegisterTenantController;
use App\Http\Controllers\Api\v1\Branch\BranchController;
use App\Http\Controllers\Api\v1\Import\ImportController;
use App\Http\Controllers\Api\v1\Insurance\InsuranceController;
use App\Http\Controllers\Api\v1\Purchase\PurchaseController;
use App\Http\Controllers\Etims\EtimsController;
use App\Jobs\TestEmailJob;
use App\Models\Import;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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

Route::get('health-check', function () {
    return response()->json([
        'success' => true,
        'message' => 'Service healthy',
        'data' => []
    ], 200);
});

Route::post('/pts', function (Request $request) {// for testing PTS incoming packets
    Log::info('PTS Incoming', $request->all());

    $packets = $request->input('Packets', []);

    $responses = [];

    foreach ($packets as $packet) {
        $responses[] = [
            "Id" => $packet['Id'],
            "Type" => $packet['Type'],
            "Message" => "OK"
        ];
    }

    return response()->json([
        "Protocol" => "jsonPTS",
        "Packets" => $responses
    ]);
});

Route::post('account-exists', [RegisterTenantController::class, 'account_exists']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('branch', BranchController::class);
    Route::get('get-item-classification-list', [BranchController::class, 'get_item_classification_list']);
    Route::patch('patch-branch/{tracking_number}', [BranchController::class, 'patch_branch']);
    Route::put('update-branch/{tracking_number}', [BranchController::class, 'update_branch']);
    Route::resource('purchase', PurchaseController::class);
    Route::post('process-purchase-item/{purchaseItem}', [PurchaseController::class, 'process_purchase_item']);
    Route::resource('import', ImportController::class);
    Route::post('process-import-item/{importItem}', [ImportController::class, 'process_import_item']);

    Route::resource('insurance', InsuranceController::class);

    Route::post('set-esb-client-webhooks-endpoint', [EtimsController::class,'registerClientWebHookURLs']);
    Route::post('save-client-webhooks-endpoint', [EtimsController::class,'registerClientWebHookURLs']); // tobe depracated
    Route::post('update-client-webhooks', [EtimsController::class,'registerClientWebHookURLs']);
    Route::get('get-invoice/{tracking_number}', [EtimsController::class, 'getInvoice']);

    require __DIR__ . '/etims/etims_routes.php';
    require __DIR__ . '/etims/reverse_invoicing_routes.php';
});

require __DIR__ . '/auth/auth_routes.php';
