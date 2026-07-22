<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Stripe SaaS Insurance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-slate-900 font-sans">
    @include('themes.stripe-saas.partials.header')
    <main>@yield('content')</main>
    @include('themes.stripe-saas.partials.footer')
</body>
</html>
