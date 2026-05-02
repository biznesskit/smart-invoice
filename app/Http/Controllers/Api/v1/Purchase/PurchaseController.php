<?php

namespace App\Http\Controllers\Api\v1\Purchase;

use App\Helpers\DateRangeFromStringHelper;
use App\Helpers\ETIMSHelper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'branch_id' => 'numeric|required'
        ]);

        $per_page = $request->per_page ? $request->per_page : env('API_PAGINATION', 20);
        $searchKeyword = $request->search_string  ? ($request->search_string  !== 'null' ? $request->search_string  : null) : null;
        $dates = DateRangeFromStringHelper::determineDatesRange($request->period, $request->start_date, $request->end_date);

        $user = $request->user();
        $branch = Branch::find($request->branch_id);
        $branch = $branch? $branch : $user->branch;

        if (empty($branch))  return response()->json([
            'success' => false,
            'message' => 'Company not found',
            'data' => []
        ], 404);


        $purchases = $branch->purchases()
            ->when($searchKeyword, function ($query) use ($searchKeyword) {
                $query->where('purchase_number', 'like', '%' . $searchKeyword . '%');
            })
            ->whereBetween('created_at', [$dates->start, $dates->end])
            ->paginate($per_page);



        return response()->json([
            'success' => true,
            'message' => 'A list of Etims purchases',
            'data' => $purchases
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        $purchase->items;

        return response()->json([
            'success' => true,
            'message' => 'A list of purchased items',
            'data' => $purchase
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }

    public function process_purchase_item(Request $request, PurchaseItem $purchaseItem)
    {
        $purchaseItem->update(['processed_at' => now()]);
        $purchase =$purchaseItem->purchase;
        $mappedProduct = Item::where('item_code',$request->item_code)->first();

        if( $mappedProduct ){
            $purchaseItem->update(['item_id' => $mappedProduct->id]);
            $purchaseItem = $purchaseItem->fresh();
            ETIMSHelper::recordNewPurchase($purchase,$purchaseItem);
        }

        return response()->json([
            'success' => true,
            'message' => 'Purchase item processed',
            'data' => $purchaseItem
        ], 200);
    }
}
