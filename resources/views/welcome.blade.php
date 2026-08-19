@extends('layouts.app')

@section('content')
    <x-navbar />

    <main>
        <x-hero />
        <x-features />
        <x-stats />
        <x-testimonials />
        <x-faq />
        <x-cta />
    </main>

    <x-footer />
@endsection
