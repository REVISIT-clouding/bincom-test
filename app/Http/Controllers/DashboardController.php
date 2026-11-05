<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rows = collect(DB::select("
            SELECT
                l.lga_name AS lga_id,
                l.party_abbreviation,
                l.party_score AS announced_total,
                COALESCE(SUM(r.party_score), 0) AS pu_total,
                l.party_score - COALESCE(SUM(r.party_score), 0) AS missing_votes
            FROM announced_lga_results l
            LEFT JOIN polling_unit p ON p.lga_id = l.lga_name
            LEFT JOIN announced_pu_results r
                   ON r.polling_unit_uniqueid = p.uniqueid
                  AND r.party_abbreviation = l.party_abbreviation
            GROUP BY l.lga_name, l.party_abbreviation, l.party_score
        "));

        $total   = $rows->count();
        $matches = $rows->where('missing_votes', 0)->count();
        $gaps    = $total - $matches;

        return view('dashboard', compact('rows', 'total', 'matches', 'gaps'));
    }
}