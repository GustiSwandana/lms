@extends('layouts.student')

@section('student_content')
<main class="flex-1 w-full space-y-10 sm:space-y-12 pb-16 bg-slate-50">
    <!-- =========================================================================
         OPTIONAL: GUEST / PENYULUH GALLERY MODE NOTICE BANNER
         ========================================================================= -->
    @if(auth()->check() && auth()->user()->isGuestRole())
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 border border-amber-200">
                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-amber-900">Mode Galeri (Akses Lihat-Saja)</h4>
                    <p class="text-[11px] text-amber-700 mt-0.5">
                        Anda masuk sebagai <strong>{{ auth()->user()->role_label }}</strong>. Menampilkan materi kurasi resmi BKKBN dalam mode pratinjau edukasi publik.
                    </p>
                </div>
            </div>
            <span class="px-3 py-1 rounded-full bg-amber-100 border border-amber-200 text-amber-800 font-semibold text-[10px] font-mono uppercase tracking-wider shrink-0 self-start sm:self-center">
                Penyuluh / Tamu
            </span>
        </div>
    </div>
    @endif

    <!-- =========================================================================
         1. HERO SECTION (TACTILE ECO-EDITORIAL LIGHT MODE)
         ========================================================================= -->
    <section class="border-b border-slate-200 py-10 sm:py-14 px-4 sm:px-6 lg:px-8 bg-gradient-to-b from-white to-slate-50">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Left Hero Editorial Copy -->
                <div class="lg:col-span-7 space-y-5 text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
                        <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                        <span>SIAGA BUMI-SSK Sulawesi Utara</span>
                    </div>

                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-slate-900 leading-[1.18]">
                        Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
                    </h1>

                    <p class="text-sm sm:text-base text-slate-600 max-w-2xl leading-relaxed">
                        Platform pembelajaran terstruktur bagi peserta didik di Sulawesi Utara untuk memperdalam kesadaran dinamika kependudukan, kepedulian ekologis Siaga Bumi, dan pencegahan kekerasan seksual (PPKS).
                    </p>

                    <!-- CTAs -->
                    <div class="flex flex-wrap items-center gap-3 pt-1">
                        <a href="#katalog-kursus" 
                           class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm transition-all shadow-xs min-h-11">
                            <span class="material-symbols-outlined text-[18px]">explore</span>
                            <span>Pilih Materi Hari Ini</span>
                        </a>
                        
                        @if(auth()->check())
                            <a href="{{ route('student.analytics') }}" 
                               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition-all shadow-xs min-h-11">
                                <span class="material-symbols-outlined text-[18px] text-emerald-600">analytics</span>
                                <span>Catatan & Nilai Saya</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-semibold text-xs sm:text-sm transition-all shadow-xs min-h-11">
                                <span class="material-symbols-outlined text-[18px] text-emerald-600">login</span>
                                <span>Masuk Akun</span>
                            </a>
                            <a href="{{ route('register') }}" 
                               class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-800 font-semibold text-xs sm:text-sm transition-all min-h-11">
                                <span class="material-symbols-outlined text-[18px]">person_add</span>
                                <span>Daftar Siswa</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Right Visual: Resume Learning Card -->
                <div class="lg:col-span-5">
                    @if($featuredCourse && $continueLesson)
                        <!-- Active Course Progress Card -->
                        <div class="bg-white rounded-2xl p-5 sm:p-6 space-y-4 shadow-sm border border-emerald-200">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 text-xs font-semibold border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Lanjut Belajar</span>
                                </span>
                                <span class="text-xs font-mono tabular-nums text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200">
                                    {{ $continueLesson->duration_formatted }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-base font-bold text-slate-900 line-clamp-1">
                                    {{ $featuredCourse->title }}
                                </h3>
                                <p class="text-xs text-slate-500 mt-1 line-clamp-1">
                                    {{ $continueLesson->module_title }}: {{ $continueLesson->title }}
                                </p>
                            </div>

                            <!-- 16:9 Thumbnail Theatre Preview -->
                            <div class="relative aspect-video rounded-xl overflow-hidden bg-slate-900 border border-slate-200 group">
                                <img src="{{ $continueLesson->thumbnail_url ?: 'https://img.youtube.com/vi/' . $continueLesson->youtube_video_id . '/maxresdefault.jpg' }}" 
                                     alt="Thumbnail Video" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"/>
                                <div class="absolute inset-0 bg-black/25 group-hover:bg-black/10 transition-colors"></div>
                                <a href="{{ route('student.classroom.lesson', [$featuredCourse->slug, $continueLesson->id]) }}" 
                                   class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                        <span class="material-symbols-outlined text-[26px] ml-0.5" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                    </div>
                                </a>
                            </div>

                            <!-- Linear Progress Bar -->
                            <div class="space-y-1.5 pt-1">
                                <div class="flex items-center justify-between text-xs text-slate-700 font-medium">
                                    <span>
                                        @if($featuredProgress >= 100)
                                            Hebat, materi kursus ini sudah selesai!
                                        @elseif($featuredProgress > 0)
                                            Tinggal sedikit lagi untuk dapat sertifikat!
                                        @else
                                            Mulai tonton materi pertamamu hari ini
                                        @endif
                                    </span>
                                    <span class="font-mono tabular-nums text-emerald-700 font-bold">{{ $featuredProgress }}%</span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full bg-emerald-600 rounded-full transition-all duration-300" style="width: {{ $featuredProgress }}%;"></div>
                                </div>
                            </div>

                            <a href="{{ route('student.classroom.lesson', [$featuredCourse->slug, $continueLesson->id]) }}" 
                               class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm transition-colors shadow-xs min-h-12">
                                <span>Lanjutkan Video Terakhir</span>
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                    @else
                        <!-- Institutional Overview Card -->
                        <div class="bg-white rounded-2xl p-6 sm:p-7 space-y-4 border border-slate-200 shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[24px]">school</span>
                                </div>
                                <div>
                                    <h3 class="text-sm font-bold text-slate-900">Kurikulum Resmi Berbasis Video</h3>
                                    <p class="text-xs text-slate-500">Pembelajaran modular siap dipelajari</p>
                                </div>
                            </div>

                            <div class="space-y-3 text-xs text-slate-600 pt-1">
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Pemutaran YouTube HD tanpa iklan komersial</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Kuis checkpoint interaktif dengan passing grade 70%</span>
                                </div>
                                <div class="flex items-center gap-2.5">
                                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                                    <span>Sertifikat kelulusan resmi dengan verifikasi QR code</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <a href="#katalog-kursus" class="block text-center py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 font-semibold text-xs transition-colors min-h-11 leading-[2.4]">
                                    Jelajahi Seluruh Kursus
                                </a>
                            </div>
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
        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200 shadow-sm">
            <div class="text-left max-w-2xl mb-6">
                <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                    Kemitraan Strategis & Kolaborasi Akademik
                </span>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mt-2">
                    Didukung oleh Instansi Pemerintah & Lembaga Perguruan Tinggi
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Sinergi lintas institusi dalam mengintegrasikan kepedulian lingkungan dan kependudukan di sekolah
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 items-center">
                <!-- 1. UNIMA -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50/70 border border-slate-200 hover:border-slate-300 transition-all text-center">
                    <img src="{{ asset('images/logos/unima.png') }}" 
                         alt="Logo UNIMA - Universitas Negeri Manado" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-900 mt-3 block">UNIMA</span>
                    <span class="text-[10px] text-slate-500 leading-tight">Universitas Negeri Manado</span>
                </div>

                <!-- 2. UNPI -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50/70 border border-slate-200 hover:border-slate-300 transition-all text-center">
                    <img src="{{ asset('images/logos/unpi.png') }}" 
                         alt="Logo UNPI - Universitas Pembangunan Indonesia" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-900 mt-3 block">UNPI</span>
                    <span class="text-[10px] text-slate-500 leading-tight">Univ. Pembangunan Indonesia</span>
                </div>

                <!-- 3. IPDN -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50/70 border border-slate-200 hover:border-slate-300 transition-all text-center">
                    <img src="{{ asset('images/logos/ipdn.png') }}" 
                         alt="Logo IPDN - Institut Pemerintahan Dalam Negeri" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-900 mt-3 block">IPDN</span>
                    <span class="text-[10px] text-slate-500 leading-tight">Inst. Pemerintahan Dalam Negeri</span>
                </div>

                <!-- 4. BKKBN -->
                <div class="flex flex-col items-center justify-center p-4 rounded-xl bg-slate-50/70 border border-slate-200 hover:border-slate-300 transition-all text-center">
                    <img src="{{ asset('images/logos/bkkbn.png') }}" 
                         alt="Logo BKKBN - Badan Kependudukan dan Keluarga Berencana Nasional" 
                         class="h-16 sm:h-20 max-w-[130px] object-contain"
                         loading="lazy"/>
                    <span class="text-xs font-bold text-slate-900 mt-3 block">BKKBN</span>
                    <span class="text-[10px] text-slate-500 leading-tight">BKKBN Sulawesi Utara</span>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         3. SSK INFORMATION SECTION (3 TACTILE PILLAR CARDS)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div class="text-left">
            <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                Pusat Informasi & Sosialisasi
            </span>
            <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-2">
                Mengenal Program Sekolah Siaga Kependudukan (SSK)
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
                Sinergi terpadu antara BKKBN dan lembaga pendidikan untuk mempersiapkan generasi terencana, peduli kelestarian bumi, dan tanggap isu kependudukan.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1: Mengenai BKKBN (Pillar Kependudukan: Indigo) -->
            <div class="bg-white rounded-2xl border border-indigo-200 p-6 sm:p-7 flex flex-col justify-between space-y-5 shadow-xs hover:border-indigo-400 hover:shadow-sm transition-all">
                <div class="space-y-4 text-left">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center border border-indigo-200">
                            <span class="material-symbols-outlined text-[26px]">groups</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-md bg-indigo-50 border border-indigo-200 text-indigo-800 text-[10px] font-mono uppercase font-semibold">
                            Lembaga Pembina
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Mengenai BKKBN
                        </h3>
                        <p class="text-xs text-indigo-700 font-medium mt-0.5">
                            Badan Kependudukan dan Keluarga Berencana Nasional
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        BKKBN adalah lembaga pemerintah non-kementerian yang bertugas melaksanakan pengendalian penduduk dan penyelenggaraan keluarga berencana. Melalui pendekatan holistik, BKKBN membina ketahanan remaja (GenRe - Generasi Berencana), menekan angka stunting, dan mempersiapkan generasi muda menyongsong Indonesia Emas dengan perencanaan hidup yang matang.
                    </p>

                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
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
                       class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs shadow-xs transition-all min-h-11">
                        <span>Pelajari Modul Mengenai BKKBN</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 2: Apa sih SSK? (Pillar Lingkungan: Emerald) -->
            <div class="bg-white rounded-2xl border border-emerald-200 p-6 sm:p-7 flex flex-col justify-between space-y-5 shadow-xs hover:border-emerald-400 hover:shadow-sm transition-all">
                <div class="space-y-4 text-left">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-200">
                            <span class="material-symbols-outlined text-[26px]">school</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-mono uppercase font-semibold">
                            Konsep & Aksi
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Apa sih SSK?
                        </h3>
                        <p class="text-xs text-emerald-700 font-medium mt-0.5">
                            Sekolah Siaga Kependudukan Terintegrasi
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Sekolah Siaga Kependudukan (SSK) adalah sekolah yang mengintegrasikan pendidikan kependudukan, pembangunan keluarga, dan pelestarian lingkungan hidup ke dalam berbagai mata pelajaran serta kegiatan ekstrakurikuler (seperti Pojok Kependudukan). Tujuannya agar peserta didik memiliki sikap, pengetahuan, dan perilaku peduli terhadap dinamika kependudukan serta keberlanjutan bumi.
                    </p>

                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Integrasi Materi ke Kurikulum Merdeka & Mapel</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Pojok Kependudukan & Edukasi Konseling Sebaya</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                            <span>Aksi Nyata Siaga Bumi: Pelestarian Lingkungan Hidup</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('student.classroom', 'apa-sih-ssk-sekolah-siaga-kependudukan') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition-all min-h-11">
                        <span>Pelajari Modul Apa sih SSK?</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>

            <!-- Card 3: Pencegahan Kekerasan Seksual (Pillar PPKS: Rose) -->
            <div class="bg-white rounded-2xl border border-rose-200 p-6 sm:p-7 flex flex-col justify-between space-y-5 shadow-xs hover:border-rose-400 hover:shadow-sm transition-all">
                <div class="space-y-4 text-left">
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-700 flex items-center justify-center border border-rose-200">
                            <span class="material-symbols-outlined text-[26px]">shield_person</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-[10px] font-mono uppercase font-semibold">
                            Perlindungan Remaja
                        </span>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-slate-900">
                            Pencegahan Kekerasan Seksual
                        </h3>
                        <p class="text-xs text-rose-700 font-medium mt-0.5">
                            Kesehatan Reproduksi & Ruang Aman Sekolah
                        </p>
                    </div>

                    <p class="text-xs text-slate-600 leading-relaxed">
                        Edukasi komprehensif bagi pelajar dan remaja mengenai pemahaman batasan pribadi (consent), pencegahan tindak kekerasan seksual di lingkungan pendidikan, perlindungan hak anak, serta mekanisme pelaporan yang aman dan terpercaya bersama Satgas PPKS.
                    </p>

                    <div class="space-y-2 pt-2 border-t border-slate-100 text-xs text-slate-600">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Edukasi Batasan Diri & Sikap Asertif Remaja</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Lingkungan Sekolah Bebas Kekerasan Seksual</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-rose-600">check_circle</span>
                            <span>Saluran Pengaduan Aman & Pendampingan Korban</span>
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <a href="{{ route('student.classroom', 'pencegahan-dan-penanganan-kekerasan-seksual-ppks') }}" 
                       class="inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-semibold text-xs shadow-xs transition-all min-h-11">
                        <span>Pelajari Modul PPKS</span>
                        <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         4. CERTIFICATE FEATURE SECTION (BKKBN OFFICIAL GOLD STANDARD)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-6 sm:p-10 border border-amber-200 relative overflow-hidden shadow-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center text-left">
                <!-- Left: Certificate Details & Steps -->
                <div class="lg:col-span-7 space-y-5">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-xs font-semibold text-amber-800">
                        <span class="material-symbols-outlined text-[16px]">verified</span>
                        <span>Fitur Unggulan Sertifikat Digital</span>
                    </div>

                    <div class="space-y-2">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                            Raih Sertifikat Kelulusan Resmi dengan <span class="text-amber-700">Verifikasi QR Code</span>
                        </h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                            Setiap siswa yang menyelesaikan seluruh modul pembelajaran dan lulus evaluasi kuis berhak mendapatkan e-sertifikat resmi berstandar nasional yang dapat diverifikasi keasliannya secara publik.
                        </p>
                    </div>

                    <!-- Steps to Earn -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-mono font-bold text-xs flex items-center justify-center border border-emerald-200">01</div>
                            <h4 class="text-xs font-bold text-slate-900">Tonton Seluruh Video</h4>
                            <p class="text-[11px] text-slate-500">Selesaikan setiap bab materi kurikulum SSK.</p>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 font-mono font-bold text-xs flex items-center justify-center border border-emerald-200">02</div>
                            <h4 class="text-xs font-bold text-slate-900">Lulus Kuis Modul</h4>
                            <p class="text-[11px] text-slate-500">Capai skor minimal kelulusan (passing grade 70%).</p>
                        </div>

                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 font-mono font-bold text-xs flex items-center justify-center border border-amber-200">03</div>
                            <h4 class="text-xs font-bold text-slate-900">Cetak & Unduh PDF</h4>
                            <p class="text-[11px] text-slate-500">Validasi publik instan via pemindaian QR Code unik.</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Visual Certificate Mockup Card -->
                <div class="lg:col-span-5">
                    <div class="bg-slate-50 rounded-2xl p-6 text-slate-900 border-2 border-amber-300 shadow-sm space-y-4">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center border border-amber-200">
                                    <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-800 block">Sertifikat Kelulusan Resmi</span>
                                    <span class="text-xs font-extrabold text-slate-900">SIAGA BUMI-SSK</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-mono tabular-nums font-semibold">TERVERIFIKASI</span>
                        </div>

                        <div class="text-center py-2 space-y-1">
                            <p class="text-[11px] text-slate-500 italic">Diberikan dengan bangga kepada:</p>
                            <h3 class="text-base font-extrabold text-slate-900 tracking-tight">Siswa Pembelajar Berprestasi</h3>
                            <div class="h-0.5 w-24 bg-amber-400 mx-auto rounded-full mt-1"></div>
                            <p class="text-[11px] text-slate-600 pt-2 leading-relaxed">
                                Telah menyelesaikan kompetensi resmi program:<br/>
                                <strong class="text-slate-900">Materi Umum Kependudukan & Siaga Bumi</strong>
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-3 border-t border-slate-200 text-[10px]">
                            <div class="text-left">
                                <span class="block text-slate-500">Kode Seri Verifikasi:</span>
                                <span class="font-mono tabular-nums font-bold text-amber-800">SSK-CERT-2026-VAL</span>
                            </div>
                            <div class="w-12 h-12 bg-white rounded-lg p-1 flex items-center justify-center text-slate-900 border border-slate-200 shadow-xs">
                                <span class="material-symbols-outlined text-[28px]">qr_code_2</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         5. MAIN COURSE CATALOG EXPLORER (SEARCH, FILTERS & CARDS)
         ========================================================================= -->
    <section id="katalog-kursus" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 scroll-mt-20">
        <!-- Section Header with Search Bar -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 space-y-4 border border-slate-200 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-left">
                <div>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                        Katalog Kursus Pembelajaran
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Menampilkan <span class="font-mono tabular-nums font-semibold text-emerald-700">{{ $courses->count() }}</span> modul kurikulum terakreditasi</p>
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
                           class="w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 text-xs pl-8 pr-8 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:bg-white focus:border-emerald-600 transition-all min-h-11" 
                           placeholder="Cari kursus atau fasilitator..." type="text"/>
                    @if(request('q'))
                        <a href="{{ route('student.dashboard', request()->except('q')) }}#katalog-kursus" 
                           class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-slate-400 hover:text-slate-700 min-h-11 min-w-11 justify-center">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                        </a>
                    @endif
                </form>
            </div>

            <!-- Filter Status Toolbar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-3 border-t border-slate-100">
                <!-- Status Tabs -->
                <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl border border-slate-200 overflow-x-auto text-xs">
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'all'])) }}#katalog-kursus" 
                       class="px-3.5 py-2 rounded-lg transition-all font-medium whitespace-nowrap min-h-11 inline-flex items-center {{ $currentFilter === 'all' ? 'bg-white text-emerald-800 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Semua (<span class="font-mono tabular-nums">{{ $countAll }}</span>)
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'ongoing'])) }}#katalog-kursus" 
                       class="px-3.5 py-2 rounded-lg transition-all font-medium whitespace-nowrap min-h-11 inline-flex items-center {{ $currentFilter === 'ongoing' ? 'bg-white text-emerald-800 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Sedang Berjalan (<span class="font-mono tabular-nums">{{ $countOngoing }}</span>)
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'completed'])) }}#katalog-kursus" 
                       class="px-3.5 py-2 rounded-lg transition-all font-medium whitespace-nowrap min-h-11 inline-flex items-center {{ $currentFilter === 'completed' ? 'bg-white text-emerald-800 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Selesai (<span class="font-mono tabular-nums">{{ $countCompleted }}</span>)
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('filter'), ['filter' => 'saved'])) }}#katalog-kursus" 
                       class="px-3.5 py-2 rounded-lg transition-all font-medium whitespace-nowrap min-h-11 inline-flex items-center {{ $currentFilter === 'saved' ? 'bg-white text-emerald-800 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                        Tersimpan (<span class="font-mono tabular-nums">{{ $countSaved }}</span>)
                    </a>
                </div>

                <!-- Multi-School Scope Filter -->
                @if($student->school_name)
                <div class="flex items-center gap-1.5 text-xs">
                    <span class="text-slate-500 font-medium shrink-0">Sekolah:</span>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('scope'), ['scope' => 'all'])) }}#katalog-kursus" 
                       class="px-3 py-2 rounded-xl border transition-all whitespace-nowrap min-h-11 inline-flex items-center {{ ($scope ?? 'all') === 'all' ? 'bg-emerald-600 text-white border-emerald-600 font-semibold shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                        Semua
                    </a>
                    <a href="{{ route('student.dashboard', array_merge(request()->except('scope'), ['scope' => 'my_school'])) }}#katalog-kursus" 
                       class="px-3 py-2 rounded-xl border transition-all whitespace-nowrap min-h-11 inline-flex items-center {{ ($scope ?? 'all') === 'my_school' ? 'bg-emerald-600 text-white border-emerald-600 font-semibold shadow-xs' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50' }}">
                        {{ $student->school_name }}
                    </a>
                </div>
                @endif
            </div>

            <!-- Categories Horizontal Pills Filter -->
            <div class="flex items-center gap-2 overflow-x-auto pb-1 text-xs pt-3 border-t border-slate-100">
                <span class="text-slate-500 font-medium shrink-0">Pilar:</span>
                <a href="{{ route('student.dashboard', request()->except('category')) }}#katalog-kursus" 
                   class="px-3 py-1.5 rounded-xl border transition-all whitespace-nowrap min-h-11 inline-flex items-center {{ !request('category') ? 'bg-emerald-50 text-emerald-800 border-emerald-300 font-semibold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                    Semua
                </a>
                @foreach($categories as $cat)
                    @php
                        $catSlug = is_object($cat) ? ($cat->slug ?? '') : (is_array($cat) ? ($cat['slug'] ?? '') : (string) $cat);
                        $catName = is_object($cat) ? ($cat->name ?? $catSlug) : (is_array($cat) ? ($cat['name'] ?? $catSlug) : (string) $cat);
                        $catCount = is_object($cat) ? ($cat->courses_count ?? 0) : (is_array($cat) ? ($cat['courses_count'] ?? 0) : 0);
                        
                        $pillarBadge = match($catSlug) {
                            'materi-umum-kependudukan' => 'border-indigo-300 text-indigo-700 bg-indigo-50',
                            'materi-umum-lingkungan' => 'border-emerald-300 text-emerald-700 bg-emerald-50',
                            'kekerasan-sexual' => 'border-rose-300 text-rose-700 bg-rose-50',
                            default => 'border-slate-200 text-slate-600 bg-white'
                        };
                    @endphp
                    @if($catSlug)
                    <a href="{{ route('student.dashboard', array_merge(request()->all(), ['category' => $catSlug])) }}#katalog-kursus" 
                       class="px-3 py-1.5 rounded-xl border transition-all whitespace-nowrap min-h-11 inline-flex items-center {{ request('category') === $catSlug ? $pillarBadge . ' font-bold' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
                        {{ $catName }} (<span class="font-mono tabular-nums">{{ $catCount }}</span>)
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
                    
                    $catSlug = $course->category?->slug ?? '';
                    $pillarBorder = match($catSlug) {
                        'materi-umum-kependudukan' => 'hover:border-indigo-400',
                        'materi-umum-lingkungan' => 'hover:border-emerald-400',
                        'kekerasan-sexual' => 'hover:border-rose-400',
                        default => 'hover:border-slate-300'
                    };
                    $pillarPill = match($catSlug) {
                        'materi-umum-kependudukan' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'materi-umum-lingkungan' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'kekerasan-sexual' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200'
                    };
                @endphp
                <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden flex flex-col {{ $pillarBorder }} hover:shadow-md transition-all duration-200 text-left shadow-xs"
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
                        <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded-md bg-black/75 backdrop-blur-xs text-white text-[10px] font-mono tabular-nums flex items-center gap-1 border border-white/10">
                            <span class="material-symbols-outlined text-[13px]">video_library</span>
                            <span>{{ $course->lessons->count() }} Video</span>
                        </div>

                        <!-- Source Badge -->
                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white/95 text-slate-800 text-[10px] font-semibold border border-slate-200 shadow-xs">
                            <span class="material-symbols-outlined text-[13px] text-rose-600" style="font-variation-settings: 'FILL' 1;">smart_display</span>
                            <span>YouTube HD</span>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2.5">
                            <!-- Category Pillar & School Tag -->
                            <div class="flex items-center justify-between text-xs gap-2">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold border {{ $pillarPill }} truncate">
                                    {{ $course->category->name ?? 'SSK BKKBN' }}
                                </span>
                                @if($course->school_name)
                                    <span class="text-[11px] text-slate-500 truncate">{{ $course->school_name }}</span>
                                @endif
                            </div>

                            <!-- Course Title -->
                            <h3 class="text-sm font-bold text-slate-900 hover:text-emerald-700 transition-colors leading-snug">
                                <a href="{{ route('student.classroom', $course->slug) }}">
                                    {{ $course->title }}
                                </a>
                            </h3>

                            <!-- Description -->
                            <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                                {{ $course->description }}
                            </p>

                            <!-- Instructor Info -->
                            <div class="pt-1 text-[11px] text-slate-500">
                                <span>Pengajar: <strong class="text-slate-800">{{ $course->instructor_name ?? 'Tim Fasilitator BKKBN SSK' }}</strong></span>
                            </div>
                        </div>

                        <!-- Progress Bar & Actions -->
                        <div class="space-y-3 pt-3 border-t border-slate-100">
                            <div>
                                <div class="flex items-center justify-between text-xs mb-1.5 font-medium">
                                    <span class="font-mono tabular-nums {{ $progress === 100 ? 'text-emerald-700 font-bold' : 'text-slate-700' }}">{{ $progress }}% Selesai</span>
                                    <span class="text-slate-500 text-[11px] font-mono tabular-nums">{{ $course->completedLessonsCountFor($student) }}/{{ $course->lessons->count() }} Modul</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $progress === 100 ? 'bg-emerald-600' : 'bg-emerald-500' }} rounded-full transition-all duration-300" style="width: {{ $progress }}%;"></div>
                                </div>
                                @if($progress === 100 && ! $cert)
                                    <div class="mt-2 flex items-center gap-1.5 text-[11px] font-medium text-amber-800 bg-amber-50 px-2.5 py-1 rounded-lg border border-amber-200">
                                        <span class="material-symbols-outlined text-[15px] text-amber-600">assignment_late</span>
                                        <span>Kuis evaluasi belum tuntas</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 pt-1">
                                <a class="flex-1 text-center py-2.5 px-3 rounded-xl {{ $progress > 0 ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200' }} text-xs font-semibold transition-colors shadow-xs min-h-11 flex items-center justify-center" 
                                   href="{{ route('student.classroom', $course->slug) }}">
                                    {{ $progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}
                                </a>

                                @if($cert)
                                    <a href="{{ route('student.certificate', $cert->certificate_code) }}" 
                                       class="min-h-11 min-w-11 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-semibold border border-amber-200 transition-colors flex items-center justify-center shrink-0" 
                                       title="Lihat Sertifikat">
                                        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                                    </a>
                                @endif

                                <button type="button" 
                                        @click="toggleBookmark()" 
                                        :class="bookmarked ? 'bg-emerald-50 text-emerald-800 border-emerald-300' : 'text-slate-400 border-slate-200 hover:text-slate-700 hover:bg-slate-50'"
                                        class="min-h-11 min-w-11 rounded-xl border transition-colors flex items-center justify-center shrink-0" 
                                        :title="bookmarked ? 'Tersimpan' : 'Simpan Kursus'">
                                    <span class="material-symbols-outlined text-[18px]" :style="bookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-12 text-center bg-white border border-slate-200 rounded-2xl p-8 space-y-3 shadow-xs">
                    <span class="material-symbols-outlined text-[36px] text-slate-400">search_off</span>
                    <h3 class="text-sm font-semibold text-slate-800">Tidak ada materi kursus yang cocok</h3>
                    <p class="text-xs text-slate-500 max-w-sm mx-auto">
                        Coba gunakan kata kunci pencarian yang berbeda atau reset filter pilar.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('student.dashboard') }}#katalog-kursus" class="px-5 py-2.5 bg-emerald-600 text-white text-xs font-semibold rounded-xl hover:bg-emerald-700 transition-colors inline-block min-h-11 leading-[2.4]">
                            Reset Filter
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </section>

    <!-- =========================================================================
         6. HOW IT WORKS (3 LANGKAH BELAJAR)
         ========================================================================= -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl p-6 sm:p-8 space-y-6 text-left border border-slate-200 shadow-sm">
            <div class="space-y-1">
                <span class="text-[10px] font-mono uppercase tracking-wider text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 font-semibold">
                    Panduan Siswa
                </span>
                <h2 class="text-base sm:text-lg font-bold text-slate-900 mt-1">
                    Alur Belajar Terarah
                </h2>
                <p class="text-xs text-slate-500">
                    Mulai dari materi dasar kependudukan hingga mengklaim sertifikat kelulusan resmi
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 font-mono font-bold text-xs flex items-center justify-center border border-emerald-200">
                        01
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Pilih Kursus SSK</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Pilih topik materi kependudukan, lingkungan hidup, atau PPKS melalui katalog pembelajaran.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 font-mono font-bold text-xs flex items-center justify-center border border-emerald-200">
                        02
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Simak Video & Catat Waktu</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Tonton video YouTube HD tanpa distraksi dan rekam catatan timestamp penting saat menyimak.
                    </p>
                </div>

                <div class="bg-slate-50 rounded-xl p-5 border border-slate-200 space-y-2">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 font-mono font-bold text-xs flex items-center justify-center border border-amber-200">
                        03
                    </div>
                    <h3 class="text-xs font-bold text-slate-900">Kuis & Klaim Sertifikat</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Selesaikan kuis evaluasi materi hingga lulus dan dapatkan e-sertifikat resmi ber-QR Code.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         7. INSTITUTIONAL FOOTER
         ========================================================================= -->
    <footer class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 border-t border-slate-200">
        <div class="flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500 gap-4">
            <div class="flex flex-wrap items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center text-white">
                    <span class="material-symbols-outlined text-[16px]">nature_people</span>
                </div>
                <span class="font-bold text-slate-900">SIAGA BUMI-SSK</span>
                <span class="text-slate-300">•</span>
                <span>Kolaborasi BKKBN Sulut, UNIMA, UNPI, & IPDN</span>
                <span class="text-slate-300">•</span>
                <span class="font-mono tabular-nums">© {{ date('Y') }} Hak Cipta Dilindungi</span>
            </div>

            <div class="flex items-center gap-4 text-xs font-medium">
                <a href="{{ route('student.dashboard') }}" class="hover:text-emerald-700 min-h-11 inline-flex items-center">Katalog SSK</a>
                <a href="{{ route('student.paths') }}" class="hover:text-emerald-700 min-h-11 inline-flex items-center">Alur Belajar</a>
                <a href="{{ route('student.analytics') }}" class="hover:text-emerald-700 min-h-11 inline-flex items-center">Analitik</a>
                <a href="{{ route('login') }}" class="hover:text-emerald-700 min-h-11 inline-flex items-center">Masuk Akun</a>
                <a href="{{ route('register') }}" class="hover:text-emerald-700 font-semibold text-emerald-700 min-h-11 inline-flex items-center">Daftar Siswa</a>
            </div>
        </div>
    </footer>
</main>
@endsection
