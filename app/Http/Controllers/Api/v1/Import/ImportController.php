<?php

namespace App\Http\Controllers\Api\v1\Import;

use App\Helpers\DateRangeFromStringHelper;
use App\Helpers\ETIMSHelper;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Import;
use App\Models\ImportItem;
use App\Models\Item;
use Illuminate\Http\Request;

class ImportController extends Controller
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


        $imports = $branch->imports()
            ->when($searchKeyword, function ($query) use ($searchKeyword) {
                $query->where('import_number', 'like', '%' . $searchKeyword . '%');
            })
            ->whereBetween('created_at', [$dates->start, $dates->end])
            ->paginate($per_page);



        return response()->json([
            'success' => true,
            'message' => 'A list of Etims imports',
            'data' => $imports
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
    public function show(Import $import)
    {
        $import->items;

        return response()->json([
            'success' => true,
            'message' => 'A list of imported products',
            'data' => $import
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Import $import)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Import $import)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Import $import)
    {
        //
    }

    public function process_import_item(Request $request,ImportItem $importItem)
    {
        $importItem->update(['processed_at' => now()]);

        $mappedProduct = Item::where('item_code',$request->item_code)->first();
        if( $mappedProduct ){
            $importItem->update(['item_id' => $mappedProduct->id]);
            $importItem = $importItem->fresh();
            ETIMSHelper::updateImportItem($importItem->toArray(), $importItem->import->branch,$mappedProduct);
        }

        return response()->json([
            'success' => true,
            'message' => 'Import item processed',
            'data' => $importItem
        ], 200);
    }
}
