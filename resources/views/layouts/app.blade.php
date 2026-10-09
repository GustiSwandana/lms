<!DOCTYPE html>
<html class="h-full bg-slate-50" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>{{ $title ?? 'EduTube Studio — Platform Pembelajaran Video YouTube' }}</title>

    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin=""/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>

    <!-- Google Material Symbols Outlined -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <!-- Tailwind CSS (Vite Build) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CDN config fallback for full design token compatibility -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        },
                        surface: '#f8fafc',
                    },
                    fontFamily: {
                        headline: ["'Plus Jakarta Sans'", "sans-serif"],
                        body: ["'Plus Jakarta Sans'", "sans-serif"],
                        mono: ["'Inter'", "monospace"],
                    }
                }
            }
        };
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-900 min-h-full flex flex-col antialiased selection:bg-indigo-500 selection:text-white">

    <!-- Flash Notification Toast -->
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
             class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-900 text-white shadow-lg border border-slate-800 transition-all text-xs font-medium">
            <span class="material-symbols-outlined text-[18px] text-emerald-400">check_circle</span>
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-slate-400 hover:text-white transition-colors ml-2">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>
