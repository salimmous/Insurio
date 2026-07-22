<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Linear Insure | High-Density Underwriting System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#0B0C0E] text-[#F7F8F8] selection:bg-[#5E6AD2] selection:text-white flex min-h-screen">

    <!-- Left Sidebar Navigation for Linear Style -->
    <aside class="w-64 border-r border-[#1F2023] bg-[#0E0F12] p-6 hidden lg:flex flex-col justify-between sticky top-0 h-screen">
        <div class="space-y-8">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#5E6AD2] flex items-center justify-center font-mono font-bold text-white text-xs">⌘</div>
                <span class="font-bold text-sm tracking-tight text-white">{{ $agencyName ?? 'Linear Insure' }}</span>
            </div>
            <nav class="space-y-1 text-xs font-mono text-slate-400">
                <a href="#matrix" class="flex items-center justify-between p-2.5 rounded-md hover:bg-[#1A1B1F] hover:text-white transition">
                    <span>01. Underwriting Matrix</span>
                    <span class="text-[10px] bg-[#1A1B1F] px-1.5 py-0.5 rounded text-slate-500">G</span>
                </a>
                <a href="#telemetry" class="flex items-center justify-between p-2.5 rounded-md hover:bg-[#1A1B1F] hover:text-white transition">
                    <span>02. Risk Telemetry</span>
                    <span class="text-[10px] bg-[#1A1B1F] px-1.5 py-0.5 rounded text-slate-500">T</span>
                </a>
                <a href="#claims" class="flex items-center justify-between p-2.5 rounded-md hover:bg-[#1A1B1F] hover:text-white transition">
                    <span>03. Claims Dispatch</span>
                    <span class="text-[10px] bg-[#1A1B1F] px-1.5 py-0.5 rounded text-slate-500">C</span>
                </a>
            </nav>
        </div>
        <div class="p-3 bg-[#151619] border border-[#26282D] rounded-lg text-[11px] font-mono text-slate-400">
            <span class="text-[#5E6AD2] font-bold">● System Nominal</span>
            <div class="text-[9px] text-slate-500 mt-1">Response P99: 14ms</div>
        </div>
    </aside>

    <!-- Main Content Stream -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Sticky Command Bar -->
        <header class="h-14 border-b border-[#1F2023] bg-[#0E0F12]/80 backdrop-blur-md px-8 flex items-center justify-between sticky top-0 z-40">
            <span class="font-mono text-xs text-slate-400">Press <kbd class="bg-[#1D1E22] px-1.5 py-0.5 rounded text-slate-200 border border-[#2D2E33]">⌘K</kbd> to launch risk simulator</span>
            <button class="bg-[#5E6AD2] hover:bg-[#4E5AC2] text-white text-xs font-mono font-bold px-4 py-2 rounded-md transition shadow-sm">
                Instant Quote ➔
            </button>
        </header>

        <!-- Main Workspace -->
        <main class="p-8 lg:p-12 space-y-16 max-w-6xl">
            <!-- Hero Terminal Box -->
            <section class="border border-[#26282D] bg-[#121316] rounded-xl p-8 lg:p-12 space-y-6">
                <span class="text-xs font-mono text-[#5E6AD2] uppercase tracking-widest block">// Automated Underwriting Engine</span>
                <h1 class="text-3xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
                    Issue insurance policies at the speed of software execution.
                </h1>
                <p class="text-slate-400 text-sm max-w-2xl font-mono leading-relaxed">
                    Zero manual underwriting delays. Fully deterministic risk pricing calculated dynamically across enterprise assets.
                </p>
            </section>

            <!-- Dense Grid System -->
            <section id="matrix" class="grid md:grid-cols-3 gap-6 font-mono text-xs">
                <div class="p-6 border border-[#26282D] bg-[#121316] rounded-lg space-y-3">
                    <span class="text-slate-500">POL-01</span>
                    <h3 class="font-bold text-white text-sm">Commercial Fleet telemetry</h3>
                    <p class="text-slate-400 text-[11px]">Real-time GPS accident detection and instant roadside dispatcher allocation.</p>
                </div>
                <div class="p-6 border border-[#26282D] bg-[#121316] rounded-lg space-y-3">
                    <span class="text-slate-500">POL-02</span>
                    <h3 class="font-bold text-white text-sm">Property & Asset Shield</h3>
                    <p class="text-slate-400 text-[11px]">Automated water leak and fire sensor monitoring connected directly to claims API.</p>
                </div>
                <div class="p-6 border border-[#26282D] bg-[#121316] rounded-lg space-y-3">
                    <span class="text-slate-500">POL-03</span>
                    <h3 class="font-bold text-white text-sm">Executive Liability</h3>
                    <p class="text-slate-400 text-[11px]">Comprehensive D&O and cyber incident coverage for technology holding companies.</p>
                </div>
            </section>
        </main>

        <footer class="mt-auto border-t border-[#1F2023] p-8 text-xs font-mono text-slate-500 flex justify-between">
            <span>© {{ date('Y') }} {{ $agencyName ?? 'Linear Insure' }}. All rights reserved.</span>
            <span>ACAPS License #8849-2</span>
        </footer>
    </div>

</body>
</html>
