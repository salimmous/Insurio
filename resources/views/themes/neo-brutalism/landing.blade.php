<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Neo Brutalism Insurance | Bold Hard Edge</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Space Grotesk', sans-serif; }</style>
</head>
<body class="bg-[#FFE66D] text-black p-6 font-bold">

    <!-- Hard Edge Header -->
    <header class="max-w-6xl mx-auto bg-white border-4 border-black p-5 rounded-none shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] flex justify-between items-center">
        <span class="font-black text-2xl tracking-tight uppercase">BRUTAL • INSURE</span>
        <button class="bg-[#FF6B6B] hover:bg-[#ff5252] text-black font-black text-xs uppercase tracking-wider px-6 py-3 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] active:translate-x-1 active:translate-y-1 active:shadow-none transition">
            GET QUOTE NOW! ⚡
        </button>
    </header>

    <!-- Hard Edge Hero -->
    <section class="max-w-6xl mx-auto my-12 bg-white border-4 border-black p-10 rounded-none shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] space-y-6">
        <span class="inline-block bg-[#4ECDC4] text-black px-4 py-1.5 border-2 border-black text-xs font-black uppercase tracking-widest">
            ZERO BS INSURANCE POLICY
        </span>
        <h1 class="text-5xl md:text-7xl font-black uppercase tracking-tight leading-none">
            NO FINE PRINT. NO WEIRD CLAUSES.
        </h1>
        <p class="text-base text-black font-medium max-w-2xl leading-relaxed">
            Straightforward coverage for your car, house, and business with instant payouts without legal jargon.
        </p>
        <button class="bg-[#FFE66D] text-black font-black text-sm uppercase px-8 py-4 border-4 border-black shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:bg-[#ffd000] transition">
            CLAIM YOUR COVERAGE NOW ➔
        </button>
    </section>

    <!-- Hard Edge Cards Grid -->
    <section class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8 my-12">
        <div class="bg-[#4ECDC4] border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-4">
            <h3 class="text-2xl font-black uppercase">AUTO SHIELD</h3>
            <p class="text-xs leading-relaxed font-bold">Collision, towing, and replacement car within 30 minutes.</p>
        </div>
        <div class="bg-[#FF6B6B] border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-4">
            <h3 class="text-2xl font-black uppercase">HOME FORTRESS</h3>
            <p class="text-xs leading-relaxed font-bold">Fire, water damage, and burglary protection with 0 deductible.</p>
        </div>
        <div class="bg-white border-4 border-black p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] space-y-4">
            <h3 class="text-2xl font-black uppercase">HEALTH SQUAD</h3>
            <p class="text-xs leading-relaxed font-bold">Full medical reimbursement for doctors, dental and optic care.</p>
        </div>
    </section>

    <footer class="max-w-6xl mx-auto bg-black text-white p-6 border-4 border-black text-center text-xs font-black tracking-widest uppercase">
        © {{ date('Y') }} {{ $agencyName ?? 'NEO BRUTALISM INSURE' }}. ALL RIGHTS RESERVED.
    </footer>

</body>
</html>
