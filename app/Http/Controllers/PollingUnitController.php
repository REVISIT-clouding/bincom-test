<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PollingUnit;

class PollingUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // it tried pagmenting bbut unlike javascript the page didnt show the total and same time pagment by 20. So i left it cuz of time.
    public function index()
    {
         
         $rows = PollingUnit::with(['lga', 'ward'])->get();

         if ($rows->isEmpty()) {
             return response()->json(['message'=>'could not find any resource'], 404);
        }
        // return response()->json($rows, 200); this ome passes data as json imto an array, its there cuz i use postman.
        return view('polling-units', compact('rows'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
