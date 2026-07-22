<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التكافل الإسلامي | Takaful Islamic Insurance</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Noto+Kufi+Arabic:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Noto Kufi Arabic', sans-serif; }</style>
</head>
<body class="bg-[#FAFDFB] text-[#064E3B] selection:bg-[#047857] selection:text-white">

    <header class="bg-white border-b border-emerald-100 sticky top-0 z-50 shadow-xs">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <span class="font-extrabold text-2xl text-[#047857] tracking-tight">🇲🇦 {{ $agencyName ?? 'التكافل الإسلامي المغربي' }}</span>
            <button class="bg-[#047857] text-white font-bold text-xs px-6 py-3 rounded-2xl shadow-md hover:bg-[#035e44] transition">
                حساب اشتراك التكافل ➔
            </button>
        </div>
    </header>

    <section class="py-24 max-w-5xl mx-auto px-8 text-center space-y-6">
        <span class="inline-block px-4 py-1.5 bg-emerald-100 text-[#047857] text-xs font-bold rounded-full">
            تأمين تكافلي متوافق مع أحكام الشريعة الإسلامية ومصادق عليه من المجلس العلمي الأعلى
        </span>
        <h1 class="text-4xl md:text-6xl font-black text-[#064E3B] leading-tight font-['Amiri']">
            التكافل التضامني: حماية وأمان وفق ضوابط المعاملات الإسلامية
        </h1>
        <p class="text-emerald-800 text-base max-w-xl mx-auto font-medium">
            صندوق تكافلي غير ربحي يقوم على أساس التبرع والتعاون بين المشاركين مع توزيع الفائض التكافلي.
        </p>
        <button class="bg-[#B45309] hover:bg-[#92400e] text-white font-bold text-xs uppercase tracking-wider px-8 py-4 rounded-2xl shadow-lg transition">
            الانضمام لصندوق التكافل ➔
        </button>
    </section>

    <footer class="py-12 bg-[#064E3B] text-emerald-100 text-xs text-center border-t border-emerald-900">
        <span>© {{ date('Y') }} {{ $agencyName ?? 'التكافل الإسلامي المغربي' }}. جميع الحقوق محفوظة.</span>
    </footer>

</body>
</html>
