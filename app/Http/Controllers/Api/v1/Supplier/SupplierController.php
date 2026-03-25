<?php

namespace App\Http\Controllers\Api\v1\Supplier;

use App\Helpers\ETIMSHelper;
use App\Http\Controllers\Controller;
use App\Jobs\Etims\ValidateASupplierJob;
use App\Models\Branch;
use App\Models\Insurance;
use App\Models\Supplier;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log as FacadesLog;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $branch = Branch::where('tracking_number',$request->tracking_number)->first();
        $branch = $branch ? $branch : $user->branch;

        $insurances = $branch->insurances()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'A list of insurances',
            'data' => $insurances,
          
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

     $user = $request->user();
        $branch = $user->branch;
        $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'full_name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'tracking_number' => 'required',
          'kra_pin' => 'required|regex:/^[PA][0-9]{9}[A-Z]$/i|unique:suppliers,kra_pin',

        ]);

        $data['solution_type'] = $branch->solution_type;

        $supplier = $branch->suppliers()->create($data);

      ValidateASupplierJob::dispatch($supplier, $branch);

        return response()->json([
            'success' => true,
            'message' => 'Record created',
            'data' =>  collect($supplier)->except([
                    'created_at',
                    'updated_at',
                    'branch_id',
                    'id',                  
                ]),
        ]);

           }


    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
           $data = $request->validate([
            'first_name' => 'nullable|string',
            'last_name' => 'nullable|string',
            'full_name' => 'required|string',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'tracking_number' => 'required',
                 'kra_pin' => 'required|regex:/^[PA][0-9]{9}[A-Z]$/i|unique:suppliers,kra_pin,' . $supplier->id,


        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        //
    }
}
