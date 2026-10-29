@extends('layouts.student')

@section('student_content')
<main class="flex-1 w-full space-y-12 pb-16">
    <!-- =========================================================================
         OPTIONAL: GUEST / PENYULUH GALLERY MODE NOTICE BANNER
         ========================================================================= -->
    @if(auth()->check() && auth()->user()->isGuestRole())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-amber-900">Mode Galeri (Akses Lihat-Saja)</h4>
                    <p class="text-[11px] text-amber-700 mt-0.5">
                        Anda masuk sebagai <strong>{{ auth()->user()->role_label }}</strong>. Katalog menampilkan materi kurasi khusus yang diizinkan oleh Admin Pusat dalam mode pratinjau (read-only).
                    </p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-amber-200 text-amber-900 font-semibold text-[10px] uppercase tracking-wider shrink-0 self-start sm:self-center">
                Penyuluh / Tamu
            </span>
        </div>
    </div>
    @endif

    <!-- =========================================================================
         1. HERO SECTION (MINIMALIST, HIGH-CONTRAST SAAS DESIGN)
         ========================================================================= -->
    <section class="bg-white border-b border-slate-200 py-12 sm:py-16 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Left Hero Copy -->
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-xs font-semibold text-indigo-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600 animate-pulse"></span>
                        <span>SIAGA BUMI-SSK Sulawesi Utara</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-[1.15]">
                        Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 max-w-2xl leading-relaxed">
                        Platform digital integratif untuk membekali generasi muda dengan literasi kependudukan, kesadaran kelestarian lingkungan hidup, pencegahan kekerasan seksual, dan kesiapan masa depan berkarakter tangguh.
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <a href="#katalog-kursus" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs sm:text-sm transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            <span>Jelajahi Kursus SSK</span>
                        </a>
                        
                        @if(auth()->check())
                            <a href="{{ route('student.analytics') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium text-xs sm:text-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-slate-500">analytics</span>
                                <span>Dasbor Analitik</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-medium text-xs sm:text-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px] text-slate-500">login</span>
                                <span>Masuk Akun</span>
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 text-indigo-700 font-medium text-xs sm:text-sm transition-colors">
                                <span class="material-symbols-outlined text-[18px]">person_add</span>
                                <span>Daftar Siswa</span>
                            </a>
                        @endif
                    </div>

                    <!-- 4 Crucial Platform Metrics -->
                    <div class="pt-6 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="space-y-0.5">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['total_courses'] ?? $countAll }}+</div>
                            <div class="text-xs text-slate-500">Kursus Terkurasi</div>
                        </div>
                        <div class="space-y-0.5">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['total_lessons'] ?? 8 }}+</div>
                            <div class="text-xs text-slate-500">Modul Edukasi</div>
                        </div>
                        <div class="space-y-0.5">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900">{{ $stats['total_schools'] ?? 4 }}</div>
                            <div class="text-xs text-slate-500">Sekolah Mitra</div>
                        </div>
                        <div class="space-y-0.5">
                            <div class="text-xl sm:text-2xl font-bold text-slate-900">100%</div>
                            <div class="text-xs text-slate-500">Sertifikat Ber-QR</div>
                        </div>
                    </div>
                </div>

                <!-- Right Visual: Continue Watching or Feature Card -->
                <div class="lg:col-span-5">
                    @if($featuredCourse && $continueLesson)
                        <!-- Smart Continue Watching Card -->
                        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-sm space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Lanjutkan Belajar</span>
                                </span>
                                <span class="text-xs text-slate-400 font-mono">{{ $continueLesson->duration_formatted }}</span>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1">
                                    {{ $featuredCourse->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                    {{ $continueLesson->module_title }}: {{ $continueLesson->title }}
                                </p>
                            </div>

                            <!-- Thumbnail Preview 16:9 -->
                            <div class="relative aspect-video rounded-lg overflow-hidden bg-slate-900 border border-slate-200 group">
                                <img src="{{ $continueLesson->thumbnail_url ?: 'https://img.youtube.com/vi/' . $continueLesson->youtube_video_id . '/maxresdefault.jpg' }}" 
                                     alt="Thumbnail Video" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                                <div class="absolute inset-0 bg-slate-950/20 group-hover:bg-slate-950/30 transition-colors"></div>
                                <a href="{{ route('student.classroom.lesson', [$featuredCourse->slug, $continueLesson->id]) }}" 
                                   class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-11 h-11 rounded-full bg-white text-indigo-600 flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[24px] ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                    </div>
                                </a>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-1.5 pt-1">
                                <div class="flex items-center justify-between text-xs text-slate-600 font-medium">
                                    <span>Progres Modul</span>
                                    <span>{{ $featuredProgress }}%</span>
                                </div>
                                <div class="w-full h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: {{ $featuredProgress }}%;"></div>
                                </div>
                            </div>

                            <a href="{{ route('student.classroom.lesson', [$featuredCourse->slug, $continueLesson->id]) }}" 
                               class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs transition-colors shadow-xs">
                                <span>Putar Materi Selanjutnya</span>
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    @else
                        <!-- Simple Feature Overview Card -->
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[22px]">school</span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Kurikulum Resmi Berbasis Video</h3>
                                    <p class="text-xs text-slate-500">Materi tervalidasi siap dipelajari</p>
                                </div>
                            </div>

                            <div class="space-y-2.5 text-xs text-slate-600">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                    <span>Streaming HD YouTube tanpa iklan</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                    <span>Kuis evaluasi modul dengan passing grade</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                                    <span>Sertifikat kelulusan digital verifikasi QR</span>
                                </div>
                            </div>

                            <a href="#katalog-kursus" class="block text-center py-2 px-4 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 font-medium text-xs transition-colors">
                                Lihat Semua Kursus
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         2. PARTNER & INSTITUTION LOGOS SECTION
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 sm:p-8 shadow-xs">
            <div class="text-center max-w-2xl mx-auto mb-6">
                <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                    Kemitraan Strategis & Kolaborasi Akademik
                </span>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mt-2">
                    Didukung oleh Instansi Pemerintah & Lembaga Perguruan Tinggi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sinergi lintas institusi dalam mengintegrasikan kepedulian lingkungan dan kependudukan di sekolah
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 sm:gap-8 items-center justify-items-center">
                <!-- 1. UNIMA -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition-all group w-full text-center">
                    <img src="{{ asset('images/logos/unima.png') }}" 
                         alt="Logo UNIMA - Universitas Negeri Manado" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain transition-transform group-hover:scale-105"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-800 mt-3 block">UNIMA</span>
                    <span class="text-[10px] text-slate-400 leading-tight">Universitas Negeri Manado</span>
                </div>

                <!-- 2. UNPI -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition-all group w-full text-center">
                    <img src="{{ asset('images/logos/unpi.png') }}" 
                         alt="Logo UNPI - Universitas Pembangunan Indonesia" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain transition-transform group-hover:scale-105"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-800 mt-3 block">UNPI</span>
                    <span class="text-[10px] text-slate-400 leading-tight">Univ. Pembangunan Indonesia</span>
                </div>

                <!-- 3. IPDN -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition-all group w-full text-center">
                    <img src="{{ asset('images/logos/ipdn.png') }}" 
                         alt="Logo IPDN - Institut Pemerintahan Dalam Negeri" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain transition-transform group-hover:scale-105"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-800 mt-3 block">IPDN</span>
                    <span class="text-[10px] text-slate-400 leading-tight">Inst. Pemerintahan Dalam Negeri</span>
                </div>

                <!-- 4. BKKBN -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl hover:bg-slate-50 transition-all group w-full text-center">
                    <img src="{{ asset('images/logos/bkkbn.png') }}" 
                         alt="Logo BKKBN - Badan Kependudukan dan Keluarga Berencana Nasional" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain transition-transform group-hover:scale-105"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-800 mt-3 block">BKKBN</span>
                    <span class="text-[10px] text-slate-400 leading-tight">BKKBN Sulawesi Utara</span>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         3. SSK INFORMATION SECTION (BKKBN & SEKOLAH SIAGA KEPENDUDUKAN)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <span class="text-[11px] font-bold text-indigo-600 tracking-wider uppercase bg-indigo-50 px-2.5 py-0.5 rounded-full border border-indigo-100">
                    Pusat Informasi & Sosialisasi
                </span>
                <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-1.5">
                    Mengenal Program Sekolah Siaga Kependudukan (SSK)
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 max-w-2xl">
                    Sinergi terpadu antara BKKBN dan lembaga pendidikan untuk mempersiapkan generasi terencana, peduli kelestarian bumi, dan tanggap isu kependudukan.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Mengenai BKKBN -->
            <div class="bg-gradient-to-br from-white to-indigo-50/40 rounded-2xl border border-indigo-100 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">groups</span>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-800 text-[11px] font-bold">
                            Lembaga Pembina
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Mengenai BKKBN
                        </h3>
                        <p class="text-xs text-indigo-600 font-semibold mt-0.5">
                            Badan Kependudukan dan Keluarga Berencana Nasional
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        BKKBN adalah lembaga pemerintah non-kementerian yang bertugas melaksanakan pengendalian penduduk dan penyelenggaraan keluarga berencana. Melalui pendekatan holistik, BKKBN membina ketahanan remaja (GenRe - Generasi Berencana), menekan angka stunting, dan mempersiapkan generasi muda menyongsong Indonesia Emas dengan perencanaan hidup yang matang.
                    </p>

                    <div class="space-y-2 pt-1 border-t border-indigo-100/80 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">check_circle</span>
                            <span>Edukasi Pendewasaan Usia Perkawinan (PUP)</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">check_circle</span>
                            <span>Optimalisasi Bonus Demografi & Generasi Berencana</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">check_circle</span>
                            <span>Pemberdayaan Ketahanan Remaja & Keluarga Sehat</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('student.classroom', 'mengenai-bkkbn-peran-kependudukan') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-all group">
                        <span>Pelajari Modul Mengenai BKKBN</span>
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 2: Apa sih SSK? -->
            <div class="bg-gradient-to-br from-white to-emerald-50/40 rounded-2xl border border-emerald-100 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">school</span>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold">
                            Konsep & Implementasi
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Apa sih SSK?
                        </h3>
                        <p class="text-xs text-emerald-700 font-semibold mt-0.5">
                            Sekolah Siaga Kependudukan Terintegrasi
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sekolah Siaga Kependudukan (SSK) adalah sekolah yang mengintegrasikan pendidikan kependudukan, pembangunan keluarga, dan pelestarian lingkungan hidup ke dalam berbagai mata pelajaran serta kegiatan ekstrakurikuler (seperti Pojok Kependudukan). Tujuannya agar peserta didik memiliki sikap, pengetahuan, dan perilaku peduli terhadap dinamika kependudukan serta keberlanjutan bumi.
                    </p>

                    <div class="space-y-2 pt-1 border-t border-emerald-100/80 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Integrasi Materi ke Kurikulum Merdeka & Mata Pelajaran</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Pengembangan Pojok Kependudukan & Diskusi Sebaya</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Aksi Nyata Siaga Bumi: Pelestarian Lingkungan Hidup</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('student.classroom', 'apa-sih-ssk-sekolah-siaga-kependudukan') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-all group">
                        <span>Pelajari Modul Apa sih SSK?</span>
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 3: Pencegahan Kekerasan Seksual (PPKS) -->
            <div class="bg-gradient-to-br from-white to-rose-50/40 rounded-2xl border border-rose-100 p-6 sm:p-7 shadow-xs flex flex-col justify-between space-y-5">
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-rose-600 text-white flex items-center justify-center shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">shield_person</span>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-800 text-[11px] font-bold">
                            Perlindungan Remaja
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Pencegahan Kekerasan Seksual
                        </h3>
                        <p class="text-xs text-rose-700 font-semibold mt-0.5">
                            Kesehatan Reproduksi & Ruang Aman Sekolah
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Edukasi komprehensif bagi pelajar dan remaja mengenai pemahaman batasan pribadi (consent), pencegahan tindak kekerasan seksual di lingkungan pendidikan, perlindungan hak anak, serta mekanisme pelaporan yang aman dan terpercaya bersama Satgas PPKS.
                    </p>

                    <div class="space-y-2 pt-1 border-t border-rose-100/80 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Edukasi Batasan Diri & Sikap Asertif Remaja</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Pembentukan Lingkungan Sekolah Bebas Kekerasan</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Saluran Pengaduan Aman & Pendampingan Korban</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('student.classroom', 'pencegahan-dan-penanganan-kekerasan-seksual-ppks') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-all group">
                        <span>Pelajari Modul PPKS</span>
                        <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         4. CERTIFICATE FEATURE SECTION (SERTIFIKAT KELULUSAN RESMI BER-QR CODE)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 rounded-2xl border border-slate-800 p-8 sm:p-10 text-white relative overflow-hidden shadow-md">
            <!-- Decorative blur shapes -->
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -left-24 w-72 h-72 rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left: Certificate Details & Steps -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs font-semibold text-amber-300">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        <span>Fitur Unggulan Sertifikat Digital</span>
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight leading-tight">
                            Raih Sertifikat Kelulusan Resmi dengan <span class="text-indigo-400">Verifikasi QR Code</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                            Setiap siswa yang menyelesaikan seluruh modul pembelajaran dan lulus evaluasi kuis berhak mendapatkan e-sertifikat resmi berstandar nasional yang dapat diverifikasi keasliannya oleh sekolah, perguruan tinggi, maupun dinas terkait secara publik.
                        </p>
                    </div>

                    <!-- Steps to Earn -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                        <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-700 space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-500/20 text-indigo-400 font-bold text-xs flex items-center justify-center">1</div>
                            <h4 class="text-xs font-bold text-white">Tonton Seluruh Video</h4>
                            <p class="text-[11px] text-slate-400">Selesaikan setiap bab materi kurikulum SSK.</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-700 space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/20 text-emerald-400 font-bold text-xs flex items-center justify-center">2</div>
                            <h4 class="text-xs font-bold text-white">Lulus Kuis Modul</h4>
                            <p class="text-[11px] text-slate-400">Capai skor minimal kelulusan (passing grade 70%).</p>
                        </div>

                        <div class="p-3.5 rounded-xl bg-slate-800/80 border border-slate-700 space-y-1.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-500/20 text-amber-400 font-bold text-xs flex items-center justify-center">3</div>
                            <h4 class="text-xs font-bold text-white">Cetak & Unduh PDF</h4>
                            <p class="text-[11px] text-slate-400">Validasi publik instan via pemindaian QR Code unik.</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual Certificate Mockup Card -->
                <div class="lg:col-span-5">
                    <div class="bg-white rounded-xl p-6 text-slate-900 border-4 border-amber-400/80 shadow-2xl space-y-4 transform hover:scale-[1.02] transition-transform">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white">
                                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 block">Sertifikat Kelulusan Resmi</span>
                                    <span class="text-xs font-extrabold text-slate-900">SIAGA BUMI-SSK</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 text-[10px] font-bold font-mono">TERVERIFIKASI</span>
                        </div>

                        <div class="text-center py-2 space-y-1">
                            <p class="text-[11px] text-slate-500 italic">Diberikan dengan bangga kepada:</p>
                            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Nama Siswa Pembelajar</h3>
                            <div class="h-0.5 w-24 bg-amber-400 mx-auto rounded-full"></div>
                            <p class="text-[11px] text-slate-600 pt-1 leading-snug">
                                Telah menyelesaikan kompetensi program: <br/>
                                <strong class="text-slate-900">Materi Umum Kependudukan & Siaga Bumi</strong>
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-200 text-[10px] text-slate-500">
                            <div>
                                <span class="block text-slate-400">Kode Seri Verifikasi:</span>
                                <span class="font-mono font-bold text-slate-800">SSK-CERT-2026-VAL</span>
                            </div>
                            <!-- Mini QR Representation -->
                            <div class="w-12 h-12 bg-slate-900 rounded-lg p-1.5 flex items-center justify-center text-white">
                                <span class="material-symbols-outlined text-[26px]">qr_code_2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         3. MAIN COURSE CATALOG EXPLORER (SEARCH, FILTERS & CARDS)
         ========================================================================= -->
    <section id="katalog-kursus" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 scroll-mt-20">
        <!-- Section Header with Search Bar -->
        <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                        Katalog Kursus Pembelajaran
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Menampilkan {{ $courses->count() }} kursus yang tersedia</p>
                </div>

                <!-- Global Search Input -->
                <form action="{{ route('student.dashboard') }}#katalog-kursus" method="GET" class="relative w-full sm:w-80">
                    @if(request('filter')) <input type="hidden" name="filter" value="{{ request('filter') }}"/> @endif
                    @if(request('scope')) <input type="hidden" name="scope" value="{{ request('scope') }}"/> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"/> @endif
                    
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[16px]">search</span>
                    </div>
                    <input name="q" value="{{ request('q') }}"
                           class="w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 text-xs pl-8 pr-8 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all" 
                           placeholder="Cari kursus atau pengajar..." type="text"/>
                    @if(request('q'))
                        <a href="{{ route('student.dashboard', request()->except('q')) }}#katalog-kursus" 
                           class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700">
                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Filter Status Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <!-- Status Tabs -->
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-lg overflow-x-auto text-xs">
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'all'])) }}#katalog-kursus" 
                       class="px-3 py-1.5 rounded-md transition-all font-medium whitespace-nowrap {{ $currentFilter === 'all' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua ({{ $countAll }})
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'ongoing'])) }}#katalog-kursus" 
                       class="px-3 py-1.5 rounded-md transition-all font-medium whitespace-nowrap {{ $currentFilter === 'ongoing' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Sedang Berjalan ({{ $countOngoing }})
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'completed'])) }}#katalog-kursus" 
                       class="px-3 py-1.5 rounded-md transition-all font-medium whitespace-nowrap {{ $currentFilter === 'completed' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Selesai ({{ $countCompleted }})
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'saved'])) }}#katalog-kursus" 
                       class="px-3 py-1.5 rounded-md transition-all font-medium whitespace-nowrap {{ $currentFilter === 'saved' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Tersimpan ({{ $countSaved }})
                    </a>
                </div>

                <!-- Multi-School Scope Filter -->
                @if($student->school_name)
                <div class="flex items-center gap-1.5 text-xs">
                    <span class="text-slate-400 font-medium shrink-0">Sekolah:</span>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('scope'), ['scope' => 'all'])) }}#katalog-kursus" 
                       class="px-2.5 py-1 rounded-md border transition-all whitespace-nowrap {{ ($scope ?? 'all') === 'all' ? 'bg-indigo-600 text-white border-indigo-600 font-medium' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        Semua
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('scope'), ['scope' => 'my_school'])) }}#katalog-kursus" 
                       class="px-2.5 py-1 rounded-md border transition-all whitespace-nowrap {{ ($scope ?? 'all') === 'my_school' ? 'bg-emerald-600 text-white border-emerald-600 font-medium' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        {{ $student->school_name }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Categories Horizontal Pills Filter -->
            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs pt-1 border-t border-slate-100">
                <span class="text-slate-400 font-medium shrink-0">Kategori:</span>
                <a href="{{ route('student.dashboard', request()->except('category')) }}#katalog-kursus" 
                   class="px-2.5 py-1 rounded-full border transition-all whitespace-nowrap {{ !request('category') ? 'bg-slate-900 text-white border-slate-900 font-medium' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    @php
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : (is_array($cat) ? ($cat['slug'] ?? '') : (string) $cat);
                        $catName = is_object($cat) ? ($cat->name ?? $catSlug) : (is_array($cat) ? ($cat['name'] ?? $catSlug) : (string) $cat);
                        $catCount = is_object($cat) ? ($cat->courses_count ?? 0) : (is_array($cat) ? ($cat['courses_count'] ?? 0) : 0);
                    @endphp
                    @if($catSlug)
                    <a href="{{ route('student.dashboard', array_merge(request()->all(), ['category' => $catSlug])) }}#katalog-kursus" 
                       class="px-2.5 py-1 rounded-full border transition-all whitespace-nowrap {{ request('category') === $catSlug ? 'bg-indigo-600 text-white border-indigo-600 font-medium' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        {{ $catName }} ({{ $catCount }})
                    </a>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Course Cards 3-Column Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($courses as $course)
                @php
                    $progress = $course->progressPercentageFor($student);
                    $firstLesson = $course->lessons->first();
                    $isBookmarked = $course->isBookmarkedBy($student);
                    $cert = $course->getCertificateFor($student);
                @endphp
                <article class="bg-white border border-slate-200 rounded-xl overflow-hidden flex flex-col hover:border-slate-300 hover:shadow-sm transition-all duration-200"
                         x-data="{ 
                             bookmarked: {{ $isBookmarked ? 'true' : 'false' }},
                             toggleBookmark() {
                                 fetch('{{ route('student.course.bookmark', $course->slug) }}', {
                                     method: 'POST',
                                     headers: {
                                         'Content-Type': 'application/json',
                                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                         'Accept': 'application/json'
                                     }
                                 })
                                 .then(res => res.json())
                                 .then(data => {
                                     this.bookmarked = data.bookmarked;
                                 });
                             }
                         }">
                    <!-- Thumbnail 16:9 Container -->
                    <div class="relative aspect-video bg-slate-900 overflow-hidden">
                        <img alt="Thumbnail {{ $course->title }}" 
                             class="w-full h-full object-cover" 
                             src="{{ $course->thumbnail_url ?: ($firstLesson ? 'https://img.youtube.com/vi/' . $firstLesson->youtube_video_id . '/hqdefault.jpg' : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80') }}"/>
                        
                        <!-- Video Count Badge -->
                        <div class="absolute bottom-2 right-2 px-2 py-0.5 rounded bg-black/75 text-white text-[10px] font-mono flex items-center gap-1">
                            <span class="material-symbols-outlined text-[12px]">video_library</span>
                            <span>{{ $course->lessons->count() }} Video</span>
                        </div>

                        <!-- Source Badge -->
                        <div class="absolute top-2 left-2 flex items-center gap-1 px-2 py-0.5 rounded bg-white/95 text-slate-800 text-[10px] font-semibold shadow-xs">
                            <span class="material-symbols-outlined text-[12px] text-rose-600" style="font-variation-settings: 'FILL' 1;">smart_display</span>
                            <span>YouTube HD</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <!-- Category & School -->
                            <div class="flex items-center justify-between text-xs gap-2">
                                <span class="text-indigo-600 font-semibold text-[11px] truncate">
                                    {{ $course->category->name ?? 'SSK BKKBN' }}
                                </span>
                                @if($course->school_name)
                                    <span class="text-[11px] text-slate-400 truncate">{{ $course->school_name }}</span>
                                @endif
                            </div>

                            <!-- Course Title -->
                            <h3 class="text-sm font-bold text-slate-900 hover:text-indigo-600 transition-colors leading-snug">
                                <a href="{{ route('student.classroom', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>

                            <!-- Description -->
                            <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                {{ $course->description }}
                            </p>

                            <!-- Instructor Info -->
                            <div class="pt-1 text-[11px] text-slate-500">
                                <span>Pengajar: <strong>{{ $course->instructor_name ?? 'Tim Fasilitator BKKBN SSK' }}</strong></span>
                            </div>
                        </div>

                        <!-- Progress Bar & Actions -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1 font-medium">
                                    <span class="{{ $progress === 100 ? 'text-emerald-600 font-semibold' : 'text-slate-600' }}">{{ $progress }}% Selesai</span>
                                    <span class="text-slate-400 text-[11px]">{{ $course->completedLessonsCountFor($student) }}/{{ $course->lessons->count() }} Modul</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $progress === 100 ? 'bg-emerald-500' : 'bg-indigo-600' }} rounded-full transition-all duration-300" style="width: {{ $progress }}%;"></div>
                                </div>
                                @if($progress === 100 && ! $cert)
                                    <div class="mt-1.5 flex items-center gap-1.5 text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200/80">
                                        <span class="material-symbols-outlined text-[14px] text-amber-600">assignment_late</span>
                                        <span>Kuis evaluasi belum tuntas</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a class="flex-1 text-center py-2 px-3 rounded-lg {{ $progress > 0 ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border border-slate-200' }} text-xs font-semibold transition-colors shadow-xs" 
                                   href="{{ route('student.classroom', $course->slug) }}">
                                    {{ $progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
                                </a>

                                @if($cert)
                                    <a href="{{ route('student.certificate', $cert->certificate_code) }}" 
                                       class="py-2 px-2.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold border border-emerald-200 transition-colors flex items-center gap-1 shrink-0" 
                                       title="Lihat Sertifikat">
                                        <span class="material-symbols-outlined text-[16px]">workspace_premium</span>
                                    </a>
                                @endif

                                <button type="button" 
                                        @click="toggleBookmark()" 
                                        :class="bookmarked ? 'bg-indigo-50 text-indigo-600 border-indigo-200' : 'text-slate-400 border-slate-200 hover:text-slate-700 hover:bg-slate-50'"
                                        class="p-2 rounded-lg border transition-colors flex items-center justify-center shrink-0" 
                                        :title="bookmarked ? 'Tersimpan' : 'Simpan Kursus'">
                                    <span class="material-symbols-outlined text-[16px]" :style="bookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-12 text-center bg-white border border-slate-200 rounded-xl p-8 space-y-2">
                    <span class="material-symbols-outlined text-[32px] text-slate-300">search_off</span>
                    <h3 class="text-sm font-semibold text-slate-800">Tidak ada kursus yang sesuai</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Coba gunakan kata kunci pencarian yang berbeda atau reset filter.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('student.dashboard') }}#katalog-kursus" class="px-4 py-2 bg-indigo-600 text-white text-xs font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                            Reset Filter
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- =========================================================================
         4. HOW IT WORKS (3 LANGKAH BELAJAR)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 sm:p-8 space-y-6">
            <div class="text-center max-w-xl mx-auto space-y-1">
                <h2 class="text-base sm:text-lg font-bold text-slate-900">
                    Alur Belajar Terarah
                </h2>
                <p class="text-xs text-slate-500">
                    Mulai dari materi dasar hingga mendapatkan sertifikat kelulusan
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center">
                        1
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Pilih Kursus</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Pilih program studi yang Anda minati melalui katalog pembelajaran terbuka.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 font-bold text-xs flex items-center justify-center">
                        2
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Simak Video & Praktik</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Tonton video YouTube HD tanpa distraksi dan catat poin penting di buku catatan modul.
                    </p>
                </div>

                <div class="bg-white rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs flex items-center justify-center">
                        3
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Kuis & Sertifikat</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Selesaikan kuis evaluasi materi hingga lulus dan cetak sertifikat ber-QR Code.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         5. CLEAN SAAS FOOTER
         ========================================================================= -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-slate-200">
        <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-md bg-indigo-600 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[14px]">nature_people</span>
                </div>
                <span class="font-bold text-slate-900">SIAGA BUMI-SSK</span>
                <span class="text-slate-300">•</span>
                <span>Kolaborasi BKKBN Sulut, UNIMA, UNPI, & IPDN</span>
                <span class="text-slate-300">•</span>
                <span>© {{ date('Y') }} Hak Cipta Dilindungi</span>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <a href="{{ route('student.dashboard') }}" class="hover:text-indigo-600">Katalog SSK</a>
                <a href="{{ route('student.paths') }}" class="hover:text-indigo-600">Alur Belajar</a>
                <a href="{{ route('student.analytics') }}" class="hover:text-indigo-600">Analitik</a>
                <a href="{{ route('login') }}" class="hover:text-indigo-600">Masuk Akun</a>
                <a href="{{ route('register') }}" class="hover:text-indigo-600 font-semibold text-indigo-600">Daftar Siswa</a>
            </div>
        </div>
    </footer>
</main>
@endsection
