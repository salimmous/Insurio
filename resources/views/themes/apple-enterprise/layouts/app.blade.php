<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Apple Enterprise Insurance</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
</head>
<body class="bg-white text-slate-900 font-['Inter'] tracking-tight">
    @include('themes.apple-enterprise.partials.header')
    <main>@yield('content')</main>
    @include('themes.apple-enterprise.partials.footer')
</body>
</html>
