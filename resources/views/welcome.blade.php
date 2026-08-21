@extends('layouts.app')

@section('content')
    <x-navbar />

    <main>
        <x-hero />
        <x-insight-growth />
        <x-programs />
        <x-stats />
        <x-lead-form />
    </main>

    <x-footer />
@endsection
