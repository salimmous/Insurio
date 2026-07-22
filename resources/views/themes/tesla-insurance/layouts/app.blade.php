<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tesla Insurance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('views/themes/tesla-insurance/theme.css') }}">
</head>
<body class="bg-white text-slate-900 font-sans tracking-tight">
    @include('themes.tesla-insurance.partials.header')
    
    <main>
        @yield('content')
    </main>

    @include('themes.tesla-insurance.partials.footer')
    <script src="{{ asset('views/themes/tesla-insurance/theme.js') }}"></script>
</body>
</html>
