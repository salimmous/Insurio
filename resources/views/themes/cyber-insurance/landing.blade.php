<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Shield Insurance | Ransomware & Breach Coverage</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Fira Code', monospace; }</style>
</head>
<body class="bg-[#022C22] text-[#34D399] selection:bg-[#34D399] selection:text-[#022C22] min-h-screen">

    <!-- Cyber Header -->
    <header class="border-b border-[#065F46] bg-[#064E3B]/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <span class="font-bold text-sm text-[#ECFDF5] tracking-widest uppercase">
                [CYBER_SHIELD] • {{ $agencyName ?? 'SECURITY_OPS' }}
            </span>
            <button class="bg-[#34D399] text-[#022C22] font-bold text-xs px-5 py-2 rounded-md hover:bg-[#10B981] transition uppercase tracking-wider">
                Run Threat Audit ➔
            </button>
        </div>
    </header>

    <!-- Terminal Hero -->
    <section class="py-24 max-w-5xl mx-auto px-6 space-y-8">
        <div class="border border-[#065F46] bg-[#064E3B]/40 p-8 rounded-xl shadow-2xl space-y-6">
            <div class="flex items-center gap-2 text-xs text-[#10B981]">
                <span class="w-3 h-3 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>SYSTEM_STATUS: THREAT_MONITOR_ACTIVE</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-bold text-[#ECFDF5] leading-tight">
                Assurance Ransomware, Data Breach & Cyber-Extorsion
            </h1>
            <p class="text-xs md:text-sm text-[#A7F3D0] leading-relaxed">
                Protection financière et cellule de gestion de crise 24/7 en cas de piratage, vol de données ou interruption de service informatique.
            </p>
            <div class="pt-2">
                <button class="bg-[#10B981] text-[#022C22] font-bold text-xs uppercase px-8 py-4 rounded-md shadow-lg hover:bg-[#34D399] transition">
                    Audit de Portabilité Cyber ➔
                </button>
            </div>
        </div>

        <!-- Terminal Risk Log Box -->
        <div class="bg-[#021F18] border border-[#065F46] p-6 rounded-xl font-mono text-xs text-emerald-400 space-y-2">
            <div class="text-slate-500">// Real-time Threat Intelligence Feed</div>
            <div>[00:00:01] Vulnerability scan: 0 critical exploits detected.</div>
            <div>[00:00:02] Cyber policy limit: 10,000,000 MAD coverage active.</div>
            <div>[00:00:03] Incident response SLA: &lt; 15 minutes guaranteed.</div>
        </div>
    </section>

    <footer class="border-t border-[#065F46] py-8 text-center text-xs text-[#059669]">
        <span>© {{ date('Y') }} {{ $agencyName ?? 'CYBER_SHIELD' }}. ALL THREATS MONITORED.</span>
    </footer>

</body>
</html>
