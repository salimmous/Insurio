<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Console Super Admin - Insurio')</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#F8FAFC] text-slate-800" x-data="{ sidebarOpen: false }">
        <div class="flex h-screen overflow-hidden">

            <!-- UNIFIED SIDEBAR PARTIAL -->
            @include('layouts.partials.platform-sidebar')

            <!-- MAIN CONTENT AREA & HEADER -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- UNIFIED HEADER PARTIAL -->
                @include('layouts.partials.platform-header')

                <!-- Page Content container -->
                <main class="flex-1 overflow-y-auto bg-[#F8FAFC] p-6 space-y-6">
                    @yield('content')
                    {{ $slot ?? '' }}
                </main>
            </div>

        </div>
    </body>
</html>
