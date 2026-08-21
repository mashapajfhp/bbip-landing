<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="BBIP Peak Performing Platform — Academic Coaching, Remedial Academic Coaching, Self Empowerment and Leadership Coaching.">
    <meta name="theme-color" content="#071a45">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="BBIP Peak Performing Platform">
    <meta property="og:description" content="Academic Coaching, Remedial Academic Coaching, Self Empowerment and Leadership Coaching.">
    <meta property="og:url" content="{{ url('/') }}">

    <title>@yield('title', 'BBIP Peak Performing Platform')</title>

    @vite(['resources/js/app.js'])
</head>
<body class="antialiased">
    @yield('content')

    <x-floating-whatsapp />
</body>
</html>
