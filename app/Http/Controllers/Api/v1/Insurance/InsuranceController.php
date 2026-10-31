<?php

namespace App\Http\Controllers\Api\v1\Insurance;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsuranceController extends Controller
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
    public function update(Request $request, Insurance $insurance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        //
    }
}
