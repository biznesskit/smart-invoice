<?php

namespace App\Http\Controllers\Api\v1\Branch;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Landlord\ItemClassification;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->only(['id','company_id','name','slug','location','phone','email']);
        return Branch::updateOrCreate(['id'=>$request->id],$data);
    }

    /**
     * Display the specified resource.
     */
    public function show(Branch $branch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $branch->update(['name'=>$request->name]);
        return $branch;
    }

    public function update_branch(Request $request, $tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();
        $branch->update(['name'=>$request->name]);
        return $branch;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        //
    }

    public function get_item_classification_list(Request $request)
    {

        $searchString = $request->search_string;
        $perPage = $request->perPage ? ($request->perPage !== 'null' ? $request->perPage : env('API_PAGINATION', 20)) : env('API_PAGINATION', 20);

        $codes = ItemClassification::when($searchString, function ($query)  use ($searchString) {
            return $query->where('name', 'like', '%' . $searchString . '%');
        })
        ->paginate($perPage);

         return response()->json([
            'success' => true,
            'message' => 'Etims item code list returned',
            'data' => $codes
        ], 200);
    }

    public function patch_branch(Request $request, $tracking_number)
    {
        $request->validate([
            'field' => 'required',
            'value' => 'required'
        ]);

        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);


        $branch->update([$request->field => $request->value]);

        return response()->json([
                'success' => true,
                'message' => 'Branch not found.',
                'data' => [],

            ], 200);

        return $branch;
    }
}
