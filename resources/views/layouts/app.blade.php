<!DOCTYPE html>
<html class="h-full bg-slate-50" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'SIAGA BUMI-SSK — Platform Sekolah Siaga Kependudukan BKKBN' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans, Inter, & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS & Application JS (Vite Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 min-h-full flex flex-col antialiased selection:bg-emerald-600 selection:text-white overflow-x-hidden">

    <!-- Flash Notification Toast -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-white text-slate-800 shadow-lg border border-slate-200 transition-all text-xs font-medium">
            <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-slate-400 hover:text-slate-700 transition-colors ml-2 min-h-11 min-w-11 inline-flex items-center justify-center">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>
