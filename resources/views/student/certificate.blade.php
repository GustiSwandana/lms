<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Kelulusan - {{ $certificate->user->name }} - {{ $certificate->course->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800;900&family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        @media print {
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .certificate-wrapper {
                box-shadow: none !important;
                border: 8px solid #062E1E !important;
                width: 100% !important;
                height: 100vh !important;
                margin: 0 !important;
                page-break-inside: avoid;
            }
            @page {
                size: A4 landscape;
                margin: 10mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 min-h-screen font-['Plus_Jakarta_Sans',sans-serif] flex flex-col justify-between p-4 sm:p-8"
      @if($isDownloadMode) onload="window.print()" @endif>

    <!-- Top Action Bar (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200 text-slate-900 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="min-h-11 min-w-11 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 flex items-center justify-center shadow-2xs transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div class="text-left">
                <h1 class="text-sm font-bold text-slate-900">Verifikasi Sertifikat Digital</h1>
                <p class="text-xs text-slate-500">Nomor Registrasi: <span class="font-mono tabular-nums text-amber-800 bg-amber-50 px-2 py-0.5 rounded border border-amber-200 font-bold tracking-wider">{{ $certificate->certificate_code }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" 
                    onclick="navigator.clipboard.writeText('{{ $verificationUrl }}'); alert('Tautan verifikasi sertifikat berhasil disalin ke clipboard!');"
                    class="min-h-11 px-4 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-colors text-slate-700 shadow-2xs">
                <span class="material-symbols-outlined text-[16px]">share</span>
                <span>Salin Tautan</span>
            </button>
            <button type="button" 
                    onclick="window.print()"
                    class="min-h-11 px-5 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold flex items-center gap-1.5 shadow-xs transition-all">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- Verified Badge Callout (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto mb-4 bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-2.5 rounded-xl text-xs flex items-center justify-between shadow-2xs">
        <div class="flex items-center gap-2 text-left">
            <span class="material-symbols-outlined text-[20px] text-emerald-600 shrink-0">verified</span>
            <span class="font-semibold text-emerald-800">Sertifikat ini sah dan terdaftar dalam sistem sertifikasi resmi Sekolah Siaga Kependudukan (SSK) BKKBN Sulawesi Utara.</span>
        </div>
        <span class="font-mono tabular-nums text-[11px] text-emerald-800 font-bold shrink-0">STATUS: TERVERIFIKASI</span>
    </div>

    <!-- Certificate Card Sheet (A4 Landscape aspect) -->
    <div class="certificate-wrapper max-w-5xl w-full mx-auto bg-[#FCFBFA] rounded-2xl shadow-2xl border-[12px] border-[#062E1E] p-8 sm:p-14 relative overflow-hidden my-auto flex flex-col justify-between">
        
        <!-- Ornate Corners & Background Watermark -->
        <div class="absolute inset-2 border-2 border-amber-500/50 pointer-events-none rounded-lg"></div>
        <div class="absolute inset-3.5 border border-amber-500/25 pointer-events-none rounded-md"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header: Logos & Serial -->
        <div class="relative z-10 flex items-start justify-between border-b-2 border-amber-500/30 pb-5">
            <div class="flex items-center gap-3.5 text-left">
                <div class="w-14 h-14 rounded-2xl bg-[#062E1E] text-white flex items-center justify-center font-black text-2xl shadow-md border-2 border-amber-400 shrink-0">
                    <span class="material-symbols-outlined text-[32px] text-amber-300">school</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[#062E1E] font-['Cinzel',serif]">SIAGA BUMI-SSK</h2>
                    <p class="text-[11px] tracking-wider uppercase text-slate-500 font-bold">
                        {{ $certificate->user->school_name ?: ($certificate->course->school_name ?: 'Sekolah Siaga Kependudukan — BKKBN Sulut') }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="text-[10px] uppercase tracking-widest text-slate-500 font-bold block">NOMOR SERTIFIKAT</span>
                <span class="font-mono tabular-nums font-bold text-sm text-[#062E1E] bg-amber-100 px-3 py-1 rounded-md border border-amber-300/80 inline-block mt-0.5">
                    {{ $certificate->certificate_code }}
                </span>
            </div>
        </div>

        <!-- Certificate Body -->
        <div class="relative z-10 text-center py-7 space-y-3.5">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-[0.25em] font-bold text-amber-700">SERTIFIKAT KELULUSAN RESMI</p>
                <h3 class="text-2xl sm:text-4xl font-extrabold text-[#062E1E] font-['Cinzel',serif] tracking-wide">
                    CERTIFICATE OF COMPLETION
                </h3>
            </div>

            <p class="text-xs text-slate-500 italic font-['Playfair_Display',serif] text-sm">
                Sertifikat ini diberikan sebagai pengakuan kelulusan kepada:
            </p>

            <!-- Student Name -->
            <div class="py-1">
                <h2 class="text-2xl sm:text-4xl font-bold text-slate-900 font-['Playfair_Display',serif] tracking-normal border-b-2 border-amber-500/40 inline-block px-8 pb-1">
                    {{ $certificate->user->name }}
                </h2>
                <p class="text-xs text-slate-600 mt-1.5 font-bold">
                    {{ $certificate->user->school_name ?: 'Peserta Didik Sekolah Siaga Kependudukan' }}
                </p>
            </div>

            <!-- Course Details -->
            <div class="max-w-2xl mx-auto space-y-1">
                <p class="text-xs text-slate-600">
                    Telah berhasil menyelesaikan seluruh rangkaian materi video, modul integrasi lingkungan dan kependudukan, serta evaluasi kuis checkpoint pada program kursus:
                </p>
                <h4 class="text-base sm:text-lg font-bold text-[#062E1E]">
                    "{{ $certificate->course->title }}"
                </h4>
                <p class="text-[11px] text-slate-500">
                    Pilar Kurikulum: <strong>{{ $certificate->course->category->name ?? 'Materi Umum Lingkungan' }}</strong> • Total <span class="font-mono tabular-nums font-semibold">{{ $certificate->course->lessons->count() }}</span> Modul Pembelajaran
                </p>
            </div>
        </div>

        <!-- Footer: Signatures, Seal & QR Code -->
        <div class="relative z-10 pt-5 border-t-2 border-amber-500/30 grid grid-cols-3 items-end gap-4 text-center">
            
            <!-- Left: Issue Date & Instructor -->
            <div class="text-left space-y-1">
                <p class="text-[11px] text-slate-500">Diterbitkan pada:</p>
                <p class="text-xs font-bold text-slate-900 font-mono tabular-nums">{{ $certificate->issued_at->translatedFormat('d F Y') }}</p>
                <div class="pt-3">
                    <p class="text-xs font-bold text-[#062E1E]">{{ $certificate->course->instructor_name ?: 'Tim Fasilitator SSK BKKBN' }}</p>
                    <p class="text-[10px] text-slate-500">{{ $certificate->course->instructor_title ?: 'Fasilitator Utama Program' }}</p>
                </div>
            </div>

            <!-- Center: Official Stamp Seal -->
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full border-2 border-dashed border-amber-600 bg-amber-50 flex items-center justify-center shadow-inner relative">
                    <div class="w-16 h-16 rounded-full border border-amber-600 flex flex-col items-center justify-center text-[9px] font-bold text-amber-900 tracking-tighter uppercase leading-tight">
                        <span class="material-symbols-outlined text-[16px] text-amber-700">verified</span>
                        <span>BKKBN</span>
                        <span>VALID</span>
                    </div>
                </div>
                <span class="text-[9px] uppercase tracking-wider text-amber-900 font-bold mt-1">SEGEL RESMI SSK SULUT</span>
            </div>

            <!-- Right: Dynamic QR Code for Third-Party Verification -->
            <div class="flex flex-col items-end text-right">
                <div class="p-2 bg-white rounded-xl border border-slate-200 shadow-xs inline-block">
                    <!-- Dynamic QR Code SVG Generator -->
                    <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" fill="white"/>
                        <!-- Position Marker Top Left -->
                        <rect x="10" y="10" width="26" height="26" fill="#062E1E"/>
                        <rect x="15" y="15" width="16" height="16" fill="white"/>
                        <rect x="19" y="19" width="8" height="8" fill="#062E1E"/>
                        <!-- Position Marker Top Right -->
                        <rect x="64" y="10" width="26" height="26" fill="#062E1E"/>
                        <rect x="69" y="15" width="16" height="16" fill="white"/>
                        <rect x="73" y="19" width="8" height="8" fill="#062E1E"/>
                        <!-- Position Marker Bottom Left -->
                        <rect x="10" y="64" width="26" height="26" fill="#062E1E"/>
                        <rect x="15" y="69" width="16" height="16" fill="white"/>
                        <rect x="19" y="73" width="8" height="8" fill="#062E1E"/>
                        <!-- Data Pattern Pixels -->
                        <rect x="42" y="12" width="6" height="6" fill="#062E1E"/>
                        <rect x="52" y="18" width="6" height="6" fill="#062E1E"/>
                        <rect x="42" y="28" width="6" height="6" fill="#062E1E"/>
                        <rect x="14" y="44" width="6" height="6" fill="#062E1E"/>
                        <rect x="26" y="48" width="6" height="6" fill="#062E1E"/>
                        <rect x="46" y="44" width="8" height="8" fill="#062E1E"/>
                        <rect x="62" y="44" width="6" height="6" fill="#062E1E"/>
                        <rect x="78" y="48" width="6" height="6" fill="#062E1E"/>
                        <rect x="44" y="62" width="6" height="6" fill="#062E1E"/>
                        <rect x="56" y="68" width="6" height="6" fill="#062E1E"/>
                        <rect x="44" y="78" width="8" height="8" fill="#062E1E"/>
                        <rect x="68" y="68" width="6" height="6" fill="#062E1E"/>
                        <rect x="80" y="76" width="6" height="6" fill="#062E1E"/>
                    </svg>
                </div>
                <span class="text-[9px] text-slate-500 mt-1 font-mono">Pindai verifikasi</span>
                <span class="text-[8px] text-slate-400 font-mono truncate max-w-[150px]">{{ $verificationUrl }}</span>
            </div>

        </div>

    </div>

    <!-- Bottom Notice (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto text-center mt-6 text-slate-500 text-xs">
        &copy; {{ date('Y') }} SIAGA BUMI-SSK • Platform Sekolah Siaga Kependudukan BKKBN Sulawesi Utara
    </div>

</body>
</html>