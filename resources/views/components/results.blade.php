
@extends('layouts.dashboard')

@section('content')

<style>
    .results-page {
        width: 100%;
        max-width: 100%;
        color: #1e293b;
    }

    .results-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        min-width: 0;
    }

    .section-heading {
        font-size: 19px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 6px;
    }

    .section-description {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .lookup-form {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .lookup-form select {
        flex: 1;
        min-width: 0;
        padding: 11px 13px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #fff;
        font-size: 14px;
        outline: none;
    }

    .lookup-form button {
        padding: 11px 20px;
        background: #172554;
        color: #fff;
        border: 1px solid #172554;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        white-space: nowrap;
    }

    .lookup-form button:hover {
        background: #1e3a8a;
    }

    .selected-unit {
        margin-top: 22px;
        padding: 18px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        background: #f8fafc;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .results-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        white-space: nowrap;
    }

    .results-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        text-align: left;
        padding: 13px 14px;
        border-bottom: 1px solid #e2e8f0;
    }

    .results-table td {
        padding: 13px 14px;
        border-bottom: 1px solid #edf0f4;
        color: #334155;
    }

    .results-table tbody tr:last-child td {
        border-bottom: none;
    }

    .results-table tbody tr:hover {
        background: #fafafa;
    }

    .comparison-table {
        min-width: 950px;
    }

    .comparison-table th,
    .comparison-table td {
        text-align: center;
    }

    .comparison-table th:first-child,
    .comparison-table td:first-child {
        text-align: left;
    }

    .comparison-table .group-heading {
        background: #172554;
        color: #fff;
        text-align: center;
    }

    .comparison-table .sub-heading {
        background: #f1f5f9;
    }

    .difference-positive {
        color: #b45309 !important;
        font-weight: 700;
    }

    .difference-negative {
        color: #b91c1c !important;
        font-weight: 700;
    }

    .difference-zero {
        color: #15803d !important;
        font-weight: 700;
    }

    .mobile-comparison {
        display: none;
    }

    .lga-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 16px;
        margin-bottom: 14px;
        background: #fff;
    }

    .lga-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 1px solid #e2e8f0;
    }

    .party-comparison {
        display: grid;
        grid-template-columns: 55px repeat(3, minmax(0, 1fr));
        gap: 8px;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 12px;
    }

    .party-comparison:last-of-type {
        border-bottom: none;
    }

    .party-comparison span {
        overflow-wrap: anywhere;
    }

    .mobile-label {
        color: #64748b;
        font-size: 10px;
        display: block;
        margin-bottom: 4px;
    }

    .mobile-total {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid #e2e8f0;
    }

    .total-box {
        background: #f8fafc;
        border-radius: 7px;
        padding: 11px;
    }

    .total-box strong {
        display: block;
        font-size: 15px;
        margin-top: 4px;
    }

    .difference-box {
        grid-column: 1 / -1;
        padding: 11px;
        border-radius: 7px;
        background: #f8fafc;
    }

    .empty-state {
        padding: 24px 12px;
        text-align: center;
        color: #64748b;
        font-size: 13px;
    }

    .comparison-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 16px;
        font-size: 12px;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .results-card {
            padding: 17px;
            border-radius: 9px;
        }

        .section-heading {
            font-size: 17px;
        }

        .lookup-form {
            flex-direction: column;
            align-items: stretch;
        }

        .lookup-form select,
        .lookup-form button {
            width: 100%;
        }

        .desktop-comparison {
            display: none;
        }

        .mobile-comparison {
            display: block;
        }
    }

    @media (max-width: 380px) {
        .results-card {
            padding: 13px;
        }

        .party-comparison {
            grid-template-columns: 42px repeat(3, minmax(0, 1fr));
            gap: 5px;
            font-size: 11px;
        }
    }
</style>

<div class="results-page">

    <!-- SECTION 1: INDIVIDUAL POLLING UNIT LOOKUP -->

    <div class="results-card">

        <h2 class="section-heading">Individual Polling Unit Results</h2>

        <p class="section-description">
            Choose a polling unit to see how many votes each party received.
        </p>

        <form method="GET" action="{{ url('/results') }}" class="lookup-form">

            <select name="pu_id" required>
                <option value="">-- Select a Polling Unit --</option>

                @foreach($allPollingUnits as $pu)
                    <option value="{{ $pu->uniqueid }}"
                        {{ ($selectedPuId ?? '') == $pu->uniqueid ? 'selected' : '' }}>

                        {{ $pu->polling_unit_name ?: 'PU #' . $pu->polling_unit_number }}
                        (LGA {{ $pu->lga_id }})

                    </option>
                @endforeach
            </select>

            <button type="submit">View Results</button>

        </form>

        @if(isset($selectedPuInfo) && $selectedPuInfo)

            <div class="selected-unit">

                <h3 style="font-size: 15px; font-weight: 700; margin-bottom: 14px;">
                    {{ $selectedPuInfo->polling_unit_name ?: 'PU #' . $selectedPuInfo->polling_unit_number }}
                </h3>

                @if($selectedPuResults->isNotEmpty())

                    <div class="table-wrapper">
                        <table class="results-table">

                            <thead>
                                <tr>
                                    <th>Party</th>
                                    <th>Number of Votes</th>
                                    <th>Entered By</th>
                                    <th>Date Entered</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($selectedPuResults as $result)
                                    <tr>
                                        <td><strong>{{ $result->party_abbreviation }}</strong></td>
                                        <td>{{ number_format($result->party_score) }}</td>
                                        <td>{{ $result->entered_by_user ?: 'N/A' }}</td>
                                        <td>{{ $result->date_entered }}</td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                @else

                    <div class="empty-state">
                        No election results have been recorded for this polling unit.
                    </div>

                @endif

            </div>

        @endif

    </div>


    <!-- SECTION 2: RESULTS COMPARISON -->

    <div class="results-card">

        <div style="margin-bottom: 20px;">

            <span style="font-size: 10px; font-weight: 700; letter-spacing: 1.5px; color: #64748b;">
                PROMPT ENGINEERING
            </span>

            <h2 class="section-heading" style="margin-top: 5px;">
                LGA and Polling Unit Results Comparison
            </h2>

            <p class="section-description" style="margin-bottom: 12px;">
                This section compares the LGA results already on record with the
                votes added together from individual polling units.
            </p>

            <p style="font-size: 12px; color: #64748b; line-height: 1.7;">
                <strong>LGA Result on Record:</strong> The result recorded for the entire local government.<br>
                <strong>Total from Polling Units:</strong> The votes added together from its polling units.<br>
                <strong>Votes Apart:</strong> How many votes higher or lower the polling-unit total is.
            </p>

        </div>


        @php
            $announcedByLga = collect($lgaPivoted)->keyBy('lga_id');
            $calculatedByLga = collect($puAggregated)->keyBy('lga_id');

            $lgaIds = $announcedByLga->keys()
                ->merge($calculatedByLga->keys())
                ->unique()
                ->sort();
        @endphp


        @if($lgaIds->isNotEmpty())

            <!-- DESKTOP COMPARISON TABLE -->

            <div class="desktop-comparison">

                <div class="table-wrapper">

                    <table class="results-table comparison-table">

                        <thead>
                            <tr>
                                <th rowspan="2" class="group-heading">LGA</th>

                                <th colspan="3" class="group-heading">PDP</th>
                                <th colspan="3" class="group-heading">DPP</th>
                                <th colspan="3" class="group-heading">ACN</th>

                                <th rowspan="2" class="group-heading">LGA Result on Record</th>
                                <th rowspan="2" class="group-heading">Total from Polling Units</th>
                                <th rowspan="2" class="group-heading">Total Votes Apart</th>
                            </tr>

                            <tr>
                                @for($i = 0; $i < 3; $i++)
                                    <th class="sub-heading">LGA Record</th>
                                    <th class="sub-heading">PU Total</th>
                                    <th class="sub-heading">Votes Apart</th>
                                @endfor
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($lgaIds as $lgaId)

                                @php
                                    $announced = $announcedByLga->get($lgaId);
                                    $calculated = $calculatedByLga->get($lgaId);

                                    $pdpAnn = $announced['parties']['PDP'] ?? 0;
                                    $pdpCalc = $calculated['parties']['PDP'] ?? 0;

                                    $dppAnn = $announced['parties']['DPP'] ?? 0;
                                    $dppCalc = $calculated['parties']['DPP'] ?? 0;

                                    $acnAnn = $announced['parties']['ACN'] ?? 0;
                                    $acnCalc = $calculated['parties']['ACN'] ?? 0;

                                    $annTotal = $announced['total_votes'] ?? 0;
                                    $calcTotal = $calculated['total_votes'] ?? 0;

                                    $totalDiff = $calcTotal - $annTotal;
                                @endphp

                                <tr>
                                    <td><strong>LGA {{ $lgaId }}</strong></td>

                                    <td>{{ number_format($pdpAnn) }}</td>
                                    <td>{{ number_format($pdpCalc) }}</td>
                                    <td class="{{ $pdpCalc - $pdpAnn == 0 ? 'difference-zero' : ($pdpCalc - $pdpAnn > 0 ? 'difference-positive' : 'difference-negative') }}">
                                        {{ number_format($pdpCalc - $pdpAnn) }}
                                    </td>

                                    <td>{{ number_format($dppAnn) }}</td>
                                    <td>{{ number_format($dppCalc) }}</td>
                                    <td class="{{ $dppCalc - $dppAnn == 0 ? 'difference-zero' : ($dppCalc - $dppAnn > 0 ? 'difference-positive' : 'difference-negative') }}">
                                        {{ number_format($dppCalc - $dppAnn) }}
                                    </td>

                                    <td>{{ number_format($acnAnn) }}</td>
                                    <td>{{ number_format($acnCalc) }}</td>
                                    <td class="{{ $acnCalc - $acnAnn == 0 ? 'difference-zero' : ($acnCalc - $acnAnn > 0 ? 'difference-positive' : 'difference-negative') }}">
                                        {{ number_format($acnCalc - $acnAnn) }}
                                    </td>

                                    <td><strong>{{ number_format($annTotal) }}</strong></td>
                                    <td><strong>{{ number_format($calcTotal) }}</strong></td>

                                    <td class="{{ $totalDiff == 0 ? 'difference-zero' : ($totalDiff > 0 ? 'difference-positive' : 'difference-negative') }}">
                                        <strong>{{ number_format($totalDiff) }}</strong>
                                    </td>
                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>


            <!-- MOBILE COMPARISON CARDS -->

            <div class="mobile-comparison">

                @foreach($lgaIds as $lgaId)

                    @php
                        $announced = $announcedByLga->get($lgaId);
                        $calculated = $calculatedByLga->get($lgaId);

                        $pdpAnn = $announced['parties']['PDP'] ?? 0;
                        $pdpCalc = $calculated['parties']['PDP'] ?? 0;

                        $dppAnn = $announced['parties']['DPP'] ?? 0;
                        $dppCalc = $calculated['parties']['DPP'] ?? 0;

                        $acnAnn = $announced['parties']['ACN'] ?? 0;
                        $acnCalc = $calculated['parties']['ACN'] ?? 0;

                        $annTotal = $announced['total_votes'] ?? 0;
                        $calcTotal = $calculated['total_votes'] ?? 0;

                        $totalDiff = $calcTotal - $annTotal;
                    @endphp

                    <div class="lga-card">

                        <div class="lga-card-header">
                            <strong style="font-size: 15px;">LGA {{ $lgaId }}</strong>

                            <span style="font-size: 11px; color: #64748b;">
                                Votes comparison
                            </span>
                        </div>

                        <div class="party-comparison" style="color: #64748b; font-weight: 700;">
                            <span>Party</span>
                            <span>LGA Record</span>
                            <span>PU Total</span>
                            <span>Votes Apart</span>
                        </div>

                        @foreach([
                            'PDP' => [$pdpAnn, $pdpCalc],
                            'DPP' => [$dppAnn, $dppCalc],
                            'ACN' => [$acnAnn, $acnCalc]
                        ] as $party => $scores)

                            @php
                                $diff = $scores[1] - $scores[0];
                            @endphp

                            <div class="party-comparison">

                                <strong>{{ $party }}</strong>

                                <span>{{ number_format($scores[0]) }}</span>

                                <span>{{ number_format($scores[1]) }}</span>

                                <span class="{{ $diff == 0 ? 'difference-zero' : ($diff > 0 ? 'difference-positive' : 'difference-negative') }}">
                                    {{ number_format($diff) }}
                                </span>

                            </div>

                        @endforeach

                        <div class="mobile-total">

                            <div class="total-box">
                                <span class="mobile-label">LGA Result on Record</span>
                                <strong>{{ number_format($annTotal) }}</strong>
                            </div>

                            <div class="total-box">
                                <span class="mobile-label">Total from Polling Units</span>
                                <strong>{{ number_format($calcTotal) }}</strong>
                            </div>

                            <div class="difference-box">
                                <span class="mobile-label">Total Votes Apart</span>

                                <strong class="{{ $totalDiff == 0 ? 'difference-zero' : ($totalDiff > 0 ? 'difference-positive' : 'difference-negative') }}">
                                    {{ number_format($totalDiff) }}
                                </strong>
                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            <!-- EXPLANATION OF DIFFERENCES -->

            <div class="comparison-legend">
                <span><strong style="color: #15803d;">0</strong> = Both results match</span>
                <span><strong style="color: #b45309;">Positive (+)</strong> = Polling-unit total is higher</span>
                <span><strong style="color: #b91c1c;">Negative (-)</strong> = Polling-unit total is lower</span>
            </div>

            <p style="font-size: 12px; color: #64748b; margin-top: 12px; line-height: 1.6;">
                A difference does not automatically mean something went wrong.
                It simply shows that the LGA result on record and the total
                from polling units are not the same.
            </p>

        @else

            <div class="empty-state">
                No election comparison data is currently available.
            </div>

        @endif

    </div>

</div>

@endsection
