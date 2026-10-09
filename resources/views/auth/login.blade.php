@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row bg-slate-50 antialiased" x-data="{ showPassword: false }">
    <!-- ==========================================
         LEFT HERO PANEL (SHOWCASE & BRANDING)
         ========================================== -->
    <div class="relative hidden lg:flex lg:w-1/2 xl:w-7/12 flex-col justify-between p-12 overflow-hidden bg-slate-900 text-white border-r border-slate-800">
        <!-- Ambient Decorative Glow & Grid Overlay -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>

        <!-- Top Header & Brand -->
        <div class="relative z-10 flex items-center justify-between">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">smart_display</span>
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white block leading-tight">
                        EduTube <span class="text-indigo-400">Studio</span>
                    </span>
                    <span class="text-[10px] font-mono tracking-wider text-slate-400 uppercase">
                        Sistem Manajemen Pembelajaran Digital
                    </span>
                </div>
            </a>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs text-slate-300 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Production v2.5</span>
            </div>
        </div>

        <!-- Hero Center Content -->
        <div class="relative z-10 my-auto py-12 max-w-xl space-y-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs font-semibold text-indigo-300">
                    <span class="material-symbols-outlined text-[16px] text-amber-400">verified</span>
                    <span>Platform Multi-Sekolah Terintegrasi</span>
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight">
                    Masa Depan Pembelajaran Video yang <span class="text-indigo-400">Terarah, Cerdas & Bersertifikat</span>
                </h1>
                <p class="text-sm xl:text-base text-slate-300 leading-relaxed font-normal">
                    Streaming materi edukasi berkualitas tinggi dari YouTube tanpa gangguan iklan, dilengkapi pencatatan progres per detik, diskusi interaktif, kuis terintegrasi, dan sertifikat kelulusan resmi ber-QR Code.
                </p>
            </div>

            <!-- 3 Feature Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">movie</span>
                    </div>
                    <div class="font-bold text-xs text-white">Streaming HD Bebas Iklan</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Kurikulum modul terstruktur berbasis YouTube.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                    </div>
                    <div class="font-bold text-xs text-white">Sertifikat Resmi Ber-QR</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Validasi keaslian publik dengan kode seri unik.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-purple-500/20 text-purple-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">apartment</span>
                    </div>
                    <div class="font-bold text-xs text-white">Multi-Tenancy Sekolah</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Isolasi data siswa & operator per sekolah.</div>
                </div>
            </div>

            <!-- Student Quote Badge -->
            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700 flex items-center gap-3.5">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&auto=format&fit=crop&q=80" 
                     alt="Siswa" class="w-9 h-9 rounded-full object-cover border border-slate-600 shrink-0"/>
                <div class="text-xs">
                    <p class="text-slate-200 italic font-medium leading-relaxed">
                        "Belajar modul teknologi jadi sangat fokus, sistem kuis dan pencatatan waktunya sangat membantu persiapan ujian kejuruan."
                    </p>
                    <div class="text-[11px] text-indigo-300 font-semibold mt-1">
                        Sarah Connor • Siswa SMKN 1 Jakarta
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Meta & Security Badge -->
        <div class="relative z-10 pt-6 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-emerald-400">lock</span>
                <span>Koneksi Aman Terenkripsi SSL/TLS & Proteksi CSRF</span>
            </div>
            <span>© {{ date('Y') }} EduTube LMS Indonesia</span>
        </div>
    </div>

    <!-- ==========================================
         RIGHT AUTH FORM PANEL
         ========================================== -->
    <div class="w-full lg:w-1/2 xl:w-5/12 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-12">
        <div class="max-w-md w-full mx-auto space-y-6">
            <!-- Mobile Brand Header -->
            <div class="lg:hidden flex items-center justify-between pb-4 border-b border-slate-200">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                        <span class="material-symbols-outlined text-[18px]">smart_display</span>
                    </div>
                    <div>
                        <span class="font-bold text-base text-slate-900 block leading-none">EduTube <span class="text-indigo-600">Studio</span></span>
                        <span class="text-[10px] text-slate-400 font-mono">LMS Multi-Role</span>
                    </div>
                </a>
                <a href="{{ route('student.dashboard') }}" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">home</span>
                    <span>Beranda</span>
                </a>
            </div>

            <!-- Form Intro Header -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                        Portal Masuk Akun
                    </span>
                    <a href="{{ route('student.dashboard') }}" class="hidden lg:inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Kembali ke Beranda</span>
                    </a>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Selamat Datang Kembali
                </h2>
                <p class="text-xs text-slate-500">
                    Masukkan kredensial akun Anda untuk mengakses materi pembelajaran dan dasbor admin.
                </p>
            </div>

            <!-- Session & Error Messages -->
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-xs">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5 shadow-xs">
                    <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5 shadow-xs">
                    <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <!-- Main Login Form -->
            <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- Email Input -->
                <div class="space-y-1.5">
                    <label for="email" class="block text-xs font-semibold text-slate-700">
                        Alamat Email Resmi
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">alternate_email</span>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               autocomplete="email" 
                               required 
                               value="{{ old('email') }}"
                               placeholder="nama@sekolah.sch.id atau admin@edutube.org"
                               class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                    </div>
                </div>

                <!-- Password Input with Show/Hide Toggle -->
                <div class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Kata Sandi
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-medium text-indigo-600 hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">lock</span>
                        </div>
                        <input id="password" 
                               name="password" 
                               :type="showPassword ? 'text' : 'password'" 
                               autocomplete="current-password" 
                               required 
                               placeholder="••••••••••••"
                               class="w-full pl-10 pr-11 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none"
                                :title="showPassword ? 'Sembunyikan sandi' : 'Tampilkan sandi'">
                            <span class="material-symbols-outlined text-[18px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                        </button>
                    </div>
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600 select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-600 border-slate-200"/>
                        <span>Ingat sesi saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button type="submit" 
                            class="w-full flex justify-center items-center gap-2 py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none shadow-xs transition-all active:scale-[0.99] group">
                        <span>Masuk ke Akun Pembelajaran</span>
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </form>

            <!-- Student Account Registration Info Notice -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-start gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-indigo-600 shrink-0 mt-0.5">school</span>
                <div class="leading-relaxed">
                    <span class="font-semibold text-slate-900">Pendaftaran Akun Siswa Baru:</span>
                    <p class="text-[11px] text-slate-500 mt-0.5">
                        Pembuatan akun siswa dikelola secara terpusat oleh <strong>Operator Sekolah</strong> masing-masing. Silakan hubungi bagian kurikulum atau tata usaha sekolah Anda jika belum menerima akun.
                    </p>
                </div>
            </div>

            <!-- ==========================================
                 1-CLICK QUICK DEMO SWITCHER CARDS
                 ========================================== -->
            <div class="pt-4 border-t border-slate-200 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-amber-500">bolt</span>
                        1-Click Demo Login (Uji Peran)
                    </span>
                    <span class="text-[10px] text-slate-500 font-mono bg-slate-100 px-2 py-0.5 rounded-full font-medium">
                        Sandi: password
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-2">
                    <!-- Super Admin Preset Card -->
                    <a href="{{ route('switch-role', 'super_admin') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[16px]">shield</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Admin Pusat (Super Admin)</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Semua Akses</span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">superadmin@edutube.org</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all">login</span>
                    </a>

                    <!-- School Admin Preset Card -->
                    <a href="{{ route('switch-role', 'school_admin') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[16px]">account_balance</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Operator Sekolah (School Admin)</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-slate-100 text-slate-700 font-semibold border border-slate-200">SMKN 1 Jakarta</span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">smkn1@edutube.org</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-indigo-600 group-hover:translate-x-0.5 transition-all">login</span>
                    </a>

                    <!-- Student Preset Card -->
                    <a href="{{ route('switch-role', 'student') }}" 
                       class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[16px]">school</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Siswa Pembelajar (Student)</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100">Sarah Connor</span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-mono">student@edutube.org</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-emerald-600 group-hover:translate-x-0.5 transition-all">login</span>
                    </a>
                </div>
            </div>

            <!-- Footer Quick Links -->
            <div class="pt-1 text-center text-xs text-slate-400">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1 text-indigo-600 font-semibold hover:underline">
                    <span class="material-symbols-outlined text-[15px]">explore</span>
                    <span>Jelajahi Katalog Kursus Tanpa Masuk</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
