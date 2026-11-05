@extends('layouts.dashboard')

@section('content')
    <h2 class="text-lg font-bold mb-2">LGA Results</h2>
    @include('components.results', ['rows' => $lgaRows])

    <h2 class="text-lg font-bold mt-6 mb-2">Polling Unit Results</h2>
    @include('components.results', ['rows' => $puRows])
@endsection