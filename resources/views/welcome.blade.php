@extends('layouts.app')

@section('content')
    <x-navbar />

    <main>
        <x-hero />
        <x-programs />
        <x-stats />
        <x-lead-form />
    </main>

    <x-footer />
@endsection
