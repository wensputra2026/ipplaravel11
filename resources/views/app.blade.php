<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title inertia>{{ config('app.name', 'E-IPP') }}</title>

    @php
        $schoolLogo = \App\Models\AppSetting::get('school_logo', 'logo_1767853884.png');
    @endphp
    <link rel="icon" href="{{ asset('assets/dist/img/' . $schoolLogo) }}">

    <!-- Offline Assets -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/bootstrap-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/outfit.css') }}">

    @viteReactRefresh
    @vite(['resources/js/app.jsx'])
    @inertiaHead
</head>
<body class="font-sans antialiased" style="background-color: #f8fafc; color: #0f172a; margin: 0; font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    @inertia
    <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
