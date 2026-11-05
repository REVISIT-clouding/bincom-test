@extends('layouts.dashboard')

@section('content')
    @include('components.polling-units-table', ['rows' => $rows])
@endsection