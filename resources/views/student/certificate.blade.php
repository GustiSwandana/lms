<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sertifikat Kelulusan - {{ $certificate->user->name }} - {{ $certificate->course->title }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,400;1,600&display=swap" rel="stylesheet">
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
                border: 8px solid #00236f !important;
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
<body class="bg-[#0b1329] text-[#131b2e] min-h-screen font-['Plus_Jakarta_Sans',sans-serif] flex flex-col justify-between p-4 sm:p-8"
      @if($isDownloadMode) onload="window.print()" @endif>

    <!-- Top Action Bar (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/20 text-white">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="p-2 rounded-xl bg-white/10 hover:bg-white/20 transition-colors">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-sm font-bold">Verifikasi Sertifikat Digital</h1>
                <p class="text-xs text-white/70">Nomor Registrasi: <span class="font-mono text-amber-300 font-bold">{{ $certificate->certificate_code }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-2.5">
            <button type="button" 
                    onclick="navigator.clipboard.writeText('{{ $verificationUrl }}'); alert('Tautan verifikasi sertifikat berhasil disalin ke clipboard!');"
                    class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                <span class="material-symbols-outlined text-[16px]">share</span>
                <span>Salin Tautan</span>
            </button>
            <button type="button" 
                    onclick="window.print()"
                    class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold flex items-center gap-1.5 shadow-lg shadow-amber-500/20 transition-all">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak / Unduh PDF</span>
            </button>
        </div>
    </div>

    <!-- Verified Badge Callout (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto mb-4 bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 px-4 py-2.5 rounded-xl text-xs flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-emerald-400">verified</span>
            <span class="font-semibold">Sertifikat ini sah dan terdaftar dalam sistem sertifikasi resmi Sekolah Siaga Kependudukan (SSK) BKKBN Sulawesi Utara.</span>
        </div>
        <span class="font-mono text-[11px] text-emerald-300">STATUS: TERVERIFIKASI</span>
    </div>

    <!-- Certificate Card Sheet (A4 Landscape aspect) -->
    <div class="certificate-wrapper max-w-5xl w-full mx-auto bg-[#fcfbfa] rounded-2xl shadow-2xl border-[12px] border-[#00236f] p-8 sm:p-14 relative overflow-hidden my-auto flex flex-col justify-between">
        
        <!-- Ornate Corners & Background Watermark -->
        <div class="absolute inset-2 border-2 border-amber-500/60 pointer-events-none rounded-lg"></div>
        <div class="absolute inset-3 border border-amber-500/30 pointer-events-none rounded-md"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-blue-600/5 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Header: Logos & Serial -->
        <div class="relative z-10 flex items-start justify-between border-b-2 border-amber-500/30 pb-6">
            <div class="flex items-center gap-3.5">
                <div class="w-14 h-14 rounded-2xl bg-[#00236f] text-white flex items-center justify-center font-black text-2xl shadow-md border-2 border-amber-400">
                    <span class="material-symbols-outlined text-[32px]">school</span>
                </div>
                <div>
                    <h2 class="text-xl font-bold tracking-tight text-[#00236f] font-['Cinzel',serif]">SIAGA BUMI-SSK</h2>
                    <p class="text-[11px] tracking-wider uppercase text-[#757682] font-semibold">
                        {{ $certificate->user->school_name ?: ($certificate->course->school_name ?: 'Platform Pembelajaran Vokasi') }}
                    </p>
                </div>
            </div>

            <div class="text-right">
                <span class="text-[10px] uppercase tracking-widest text-[#757682] font-semibold block">NOMOR SERTIFIKAT</span>
                <span class="font-mono font-bold text-sm text-[#00236f] bg-amber-100/70 px-2.5 py-0.5 rounded border border-amber-300">
                    {{ $certificate->certificate_code }}
                </span>
            </div>
        </div>

        <!-- Certificate Body -->
        <div class="relative z-10 text-center py-8 space-y-4">
            <div class="space-y-1">
                <p class="text-xs uppercase tracking-[0.3em] font-semibold text-amber-700">SERTIFIKAT KELULUSAN RESMI</p>
                <h3 class="text-3xl sm:text-4xl font-extrabold text-[#00236f] font-['Cinzel',serif] tracking-wide">
                    CERTIFICATE OF COMPLETION
                </h3>
            </div>

            <p class="text-xs text-[#757682] italic font-['Playfair_Display',serif] text-sm">
                Sertifikat ini diberikan sebagai pengakuan kelulusan kepada:
            </p>

            <!-- Student Name -->
            <div class="py-2">
                <h2 class="text-3xl sm:text-4xl font-bold text-[#131b2e] font-['Playfair_Display',serif] tracking-normal border-b-2 border-amber-500/40 inline-block px-8 pb-1">
                    {{ $certificate->user->name }}
                </h2>
                <p class="text-xs text-[#757682] mt-1 font-semibold">
                    {{ $certificate->user->school_name ?: 'Peserta Didik Terverifikasi' }}
                </p>
            </div>

            <!-- Course Details -->
            <div class="max-w-2xl mx-auto space-y-1">
                <p class="text-xs text-[#444651]">
                    Telah berhasil menyelesaikan seluruh rangkaian materi, studi kasus, penugasan kurikulum, dan evaluasi kelulusan pada program kursus:
                </p>
                <h4 class="text-lg sm:text-xl font-bold text-[#00236f]">
                    "{{ $certificate->course->title }}"
                </h4>
                <p class="text-[11px] text-[#757682]">
                    Kategori: <strong>{{ $certificate->course->category->name ?? 'Teknologi' }}</strong> • Total {{ $certificate->course->lessons->count() }} Modul Pelajaran
                </p>
            </div>
        </div>

        <!-- Footer: Signatures, Seal & QR Code -->
        <div class="relative z-10 pt-6 border-t-2 border-amber-500/30 grid grid-cols-3 items-end gap-4 text-center">
            
            <!-- Left: Issue Date & Instructor -->
            <div class="text-left space-y-1">
                <p class="text-[11px] text-[#757682]">Diterbitkan pada:</p>
                <p class="text-xs font-bold text-[#131b2e]">{{ $certificate->issued_at->translatedFormat('d F Y') }}</p>
                <div class="pt-4">
                    <p class="text-xs font-bold text-[#00236f]">{{ $certificate->course->instructor_name ?: 'Tim Kurikulum SSK BKKBN' }}</p>
                    <p class="text-[10px] text-[#757682]">{{ $certificate->course->instructor_title ?: 'Instruktur Utama Program' }}</p>
                </div>
            </div>

            <!-- Center: Official Stamp Seal -->
            <div class="flex flex-col items-center justify-center">
                <div class="w-20 h-20 rounded-full border-2 border-dashed border-amber-600 bg-amber-50 flex items-center justify-center shadow-inner relative">
                    <div class="w-16 h-16 rounded-full border border-amber-600 flex flex-col items-center justify-center text-[9px] font-bold text-amber-900 tracking-tighter uppercase leading-tight">
                        <span class="material-symbols-outlined text-[16px] text-amber-700">verified</span>
                        <span>SEAL</span>
                        <span>VALID</span>
                    </div>
                </div>
                <span class="text-[9px] uppercase tracking-wider text-amber-800 font-bold mt-1">SEGEL KEASLIAN RESMI</span>
            </div>

            <!-- Right: Dynamic QR Code for Third-Party Verification -->
            <div class="flex flex-col items-end text-right">
                <div class="p-2 bg-white rounded-xl border border-slate-200 shadow-xs inline-block">
                    <!-- Dynamic QR Code SVG Generator -->
                    <svg class="w-16 h-16" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" fill="white"/>
                        <!-- Position Marker Top Left -->
                        <rect x="10" y="10" width="26" height="26" fill="#00236f"/>
                        <rect x="15" y="15" width="16" height="16" fill="white"/>
                        <rect x="19" y="19" width="8" height="8" fill="#00236f"/>
                        <!-- Position Marker Top Right -->
                        <rect x="64" y="10" width="26" height="26" fill="#00236f"/>
                        <rect x="69" y="15" width="16" height="16" fill="white"/>
                        <rect x="73" y="19" width="8" height="8" fill="#00236f"/>
                        <!-- Position Marker Bottom Left -->
                        <rect x="10" y="64" width="26" height="26" fill="#00236f"/>
                        <rect x="15" y="69" width="16" height="16" fill="white"/>
                        <rect x="19" y="73" width="8" height="8" fill="#00236f"/>
                        <!-- Data Pattern Pixels -->
                        <rect x="42" y="12" width="6" height="6" fill="#00236f"/>
                        <rect x="52" y="18" width="6" height="6" fill="#00236f"/>
                        <rect x="42" y="28" width="6" height="6" fill="#00236f"/>
                        <rect x="14" y="44" width="6" height="6" fill="#00236f"/>
                        <rect x="26" y="48" width="6" height="6" fill="#00236f"/>
                        <rect x="46" y="44" width="8" height="8" fill="#00236f"/>
                        <rect x="62" y="44" width="6" height="6" fill="#00236f"/>
                        <rect x="78" y="48" width="6" height="6" fill="#00236f"/>
                        <rect x="44" y="62" width="6" height="6" fill="#00236f"/>
                        <rect x="56" y="68" width="6" height="6" fill="#00236f"/>
                        <rect x="44" y="78" width="8" height="8" fill="#00236f"/>
                        <rect x="68" y="68" width="6" height="6" fill="#00236f"/>
                        <rect x="80" y="76" width="6" height="6" fill="#00236f"/>
                    </svg>
                </div>
                <span class="text-[9px] text-[#757682] mt-1 font-mono">Pindai untuk validasi</span>
                <span class="text-[8px] text-[#757682] font-mono truncate max-w-[150px]">{{ $verificationUrl }}</span>
            </div>

        </div>

    </div>

    <!-- Bottom Notice (No Print) -->
    <div class="no-print max-w-5xl w-full mx-auto text-center mt-6 text-white/50 text-xs">
        &copy; {{ date('Y') }} SIAGA BUMI-SSK • Platform Sekolah Siaga Kependudukan BKKBN Sulawesi Utara
    </div>

</body>
</html>
