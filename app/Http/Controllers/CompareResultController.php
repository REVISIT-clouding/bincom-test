<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AnnouncedLgaResult;
use App\Models\AnnouncedPuResult;

class CompareResultController extends Controller
{
    public function index(Request $request)
    {

    // I CODDE THIS WITH HELP OF GEMINI, 
        // 1. Fetch all polling units for the dropdown menu
        $allPollingUnits = DB::table('polling_unit')
            ->select('uniqueid', 'polling_unit_name', 'polling_unit_number', 'lga_id')
            ->orderBy('polling_unit_name', 'asc')
            ->get();

        // 2. Fetch results for a selected polling unit (if any)
        $selectedPuId = $request->input('pu_id');
        $selectedPuResults = collect();
        $selectedPuInfo = null;

        if ($selectedPuId) {
            $selectedPuResults = AnnouncedPuResult::where('polling_unit_uniqueid', $selectedPuId)->get();
            $selectedPuInfo = DB::table('polling_unit')->where('uniqueid', $selectedPuId)->first();
        }

        // 3. Group Official LGA Results by LGA
        $lgaPivoted = AnnouncedLgaResult::all()
            ->groupBy('lga_name')
            ->map(function ($group) {
                return [
                    'lga_id' => $group->first()->lga_name,
                    'total_votes' => $group->sum('party_score'),
                    'parties' => $group->pluck('party_score', 'party_abbreviation')->toArray(),
                ];
            });

        // 4. Aggregate PU Results grouped by LGA & Party
        $puAggregated = DB::table('announced_pu_results as apu')
            ->join('polling_unit as pu', 'apu.polling_unit_uniqueid', '=', 'pu.uniqueid')
            ->select(
                'pu.lga_id',
                'apu.party_abbreviation',
                DB::raw('SUM(apu.party_score) as total_score')
            )
            ->groupBy('pu.lga_id', 'apu.party_abbreviation')
            ->get()
            ->groupBy('lga_id')
            ->map(function ($group) {
                return [
                    'lga_id' => $group->first()->lga_id,
                    'total_votes' => $group->sum('total_score'),
                    'parties' => $group->pluck('total_score', 'party_abbreviation')->toArray(),
                ];
            });

        $emptyUserEntriesCount = AnnouncedPuResult::whereNull('entered_by_user')
            ->orWhere('entered_by_user', '')
            ->count();

       return view('results', [
    'lgaRows'               => $lgaPivoted,
    'puRows'                => $puAggregated,
    'lgaPivoted'            => $lgaPivoted,
    'puAggregated'          => $puAggregated,
    'emptyUserEntriesCount' => $emptyUserEntriesCount,
    'allPollingUnits'        => $allPollingUnits,
    'selectedPuId'           => $selectedPuId,
    'selectedPuResults'      => $selectedPuResults,
    'selectedPuInfo'         => $selectedPuInfo,
]);
    }
}