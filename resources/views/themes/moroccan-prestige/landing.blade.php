<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $agencyName ?? 'Moroccan Prestige' }} | تأمين واستشارات معتمدة</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Noto Kufi Arabic', sans-serif; }</style>
</head>
<body class="bg-amber-50/20 text-slate-900">

    <header class="bg-amber-950 text-amber-100 border-b border-amber-800 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-8 h-20 flex items-center justify-between">
            <span class="font-extrabold text-xl tracking-tight text-amber-400">🇲🇦 {{ $agencyName ?? 'التأمين المغربي الرفيع' }}</span>
            <button class="bg-amber-600 hover:bg-amber-500 text-slate-950 font-black text-xs px-6 py-2.5 rounded-xl">طلب تسعيرة فورية</button>
        </div>
    </header>

    <section class="py-28 max-w-5xl mx-auto px-8 text-center space-y-6">
        <span class="px-4 py-1.5 bg-amber-100 text-amber-900 text-xs font-bold rounded-full">وكالة تأمين واستشارات معتمدة بالمملكة المغربية</span>
        <h1 class="text-4xl md:text-6xl font-black text-amber-950 leading-tight">تأمين شامل وحماية راقية لممتلكاتكم وعائلتكم</h1>
        <p class="text-slate-600 text-base max-w-xl mx-auto">تغطية شاملة للسيارات، المنازل، الصحة والشركات وفق أحدث المعايير التنظيمية بالمغرب.</p>
        <button class="bg-amber-950 text-amber-400 font-bold text-xs px-8 py-4 rounded-xl shadow-lg">احصل على عرض أسعار ➔</button>
    </section>

</body>
</html>
