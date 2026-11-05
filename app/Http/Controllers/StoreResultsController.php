<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\AnnouncedPuResult;
use App\Models\PollingUnit;
use Illuminate\Support\Facades\DB;

class StoreResultsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $parties = Party::all();

        response()->json($parties, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $pollingUnits = DB::table('polling_unit')
        ->select('uniqueid', 'polling_unit_name', 'polling_unit_number', 'lga_id')
        ->orderBy('polling_unit_name', 'asc')
        ->get();

    $parties = ['PDP', 'DPP', 'ACN', 'PPA', 'CDC', 'JP', 'ANPP', 'LABO', 'CPP'];

   
    return view('create-result', [
        'pollingUnits' => $pollingUnits,
        'parties'      => $parties,
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        // Loop through each party score and save directly to database
        foreach ($request->input('party_scores') as $party => $score) {
            if ($score !== null && $score !== '') {
                DB::table('announced_pu_results')->insert([
                    'polling_unit_uniqueid' => $request->input('polling_unit_uniqueid'),
                    'party_abbreviation'    => $party,
                    'party_score'           => (int) $score,
                    'entered_by_user'       => $request->input('entered_by_user'),
                    'date_entered'          => now(),
                    'user_ip_address'       => $request->ip(),
                ]);
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Polling unit results saved successfully!'
        ], 201);
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
