@extends('layouts.dashboard')

@section('content')
<div style="background: #ffffff; padding: 24px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 2rem;">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 12px;">
        <div>
            <h1 style="font-size: 1.25rem; font-weight: bold; color: #0f172a; margin: 0;">Submit Polling Unit Results</h1>
            <p style="font-size: 0.875rem; color: #64748b; margin-top: 4px;">Record party vote counts for a specific polling unit.</p>
        </div>
        <a href="{{ url('/results') }}" style="padding: 8px 14px; background-color: #64748b; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
            ← Back to Dashboard
        </a>
    </div>

    @if(session('success'))
        <div style="background-color: #f0fdf4; border: 1px solid #86efac; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; color: #166534;">
            ✅ {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ url('/api/save-pu-results') }}">
        @csrf

        <!-- Target Polling Unit -->
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155;">Select Polling Unit:</label>
            <select name="polling_unit_uniqueid" required style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.95rem;">
                <option value="">-- Choose Target Polling Unit --</option>
                @foreach($pollingUnits as $pu)
                    <option value="{{ $pu->uniqueid }}">
                        {{ $pu->polling_unit_name ?: 'PU #' . $pu->polling_unit_number }} (LGA {{ $pu->lga_id }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Recorded By Officer Name -->
        <div style="margin-bottom: 24px;">
            <label style="display: block; font-weight: 600; margin-bottom: 6px; color: #334155;">Entered By (Officer Name):</label>
            <input type="text" name="entered_by_user" required placeholder="e.g. Officer Jane Doe" style="width: 100%; padding: 10px 12px; border-radius: 6px; border: 1px solid #cbd5e1; font-size: 0.95rem;">
        </div>

        <h2 style="font-size: 1.1rem; font-weight: bold; margin-bottom: 12px; color: #0f172a;">Party Scores Breakdown</h2>
        
        <!-- Party Score Inputs -->
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 28px;">
            @foreach($parties as $party)
                <div style="background: #f8fafc; padding: 12px; border-radius: 6px; border: 1px solid #e2e8f0;">
                    <label style="display: block; font-weight: 700; margin-bottom: 6px; color: #1e293b;">{{ $party }}</label>
                    <input type="number" name="party_scores[{{ $party }}]" min="0" placeholder="0" style="width: 100%; padding: 8px 10px; border-radius: 4px; border: 1px solid #cbd5e1; font-size: 0.95rem;">
                </div>
            @endforeach
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ url('/results') }}" style="padding: 10px 18px; background-color: #94a3b8; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600;">Cancel</a>
            <button type="submit" style="padding: 10px 24px; background-color: #16a34a; color: #ffffff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">
                Save Results
            </button>
        </div>
    </form>
</div>
@endsection