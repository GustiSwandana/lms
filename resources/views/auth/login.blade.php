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
                    <span>SIAGA BUMI-SSK Sulawesi Utara</span>
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight">
                    Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
                </h1>
                <p class="text-sm xl:text-base text-slate-300 leading-relaxed font-normal">
                    Platform pembelajaran terintegrasi untuk pembentukan generasi berwawasan kependudukan, peduli kelestarian lingkungan hidup, dan berkarakter kuat melalui kurikulum digital interaktif.
                </p>
            </div>

            <!-- 3 Feature Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">family_restroom</span>
                    </div>
                    <div class="font-bold text-xs text-white">Edukasi Kependudukan</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Materi komprehensif SSK dan BKKBN.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
                    </div>
                    <div class="font-bold text-xs text-white">Sertifikat Resmi Ber-QR</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Validasi keaslian publik dengan kode seri unik.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">forest</span>
                    </div>
                    <div class="font-bold text-xs text-white">Kelestarian Lingkungan</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Aksi nyata pelestarian alam Siaga Bumi.</div>
                </div>
            </div>

            <!-- Student Quote Badge -->
            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700 flex items-center gap-3.5">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=120&auto=format&fit=crop&q=80" 
                     alt="Siswa" class="w-9 h-9 rounded-full object-cover border border-slate-600 shrink-0"/>
                <div class="text-xs">
                    <p class="text-slate-200 italic font-medium leading-relaxed">
                        "Belajar modul kependudukan dan lingkungan hidup jadi sangat menyenangkan, kuisnya interaktif dan sertifikatnya resmi."
                    </p>
                    <div class="text-[11px] text-indigo-300 font-semibold mt-1">
                        Sarah Connor • Siswa SMP Negeri 1 Bitung
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
            <span>© {{ date('Y') }} SIAGA BUMI-SSK Indonesia</span>
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
                        <span class="material-symbols-outlined text-[18px]">nature_people</span>
                    </div>
                    <div>
                        <span class="font-bold text-base text-slate-900 block leading-none">SIAGA BUMI<span class="text-indigo-600">-SSK</span></span>
                        <span class="text-[10px] text-slate-400 font-mono">LMS Kependudukan & Lingkungan</span>
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
                    Masukkan kredensial akun Anda untuk mengakses materi pembelajaran, sertifikat, dan panel manajemen.
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
                        Alamat Email
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
                               placeholder="nama@email.com atau akun@sekolah.sch.id"
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

            <!-- Registration CTA -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-center text-slate-600">
                Belum memiliki akun pembelajar?
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline ml-1">
                    Daftar Sekarang
                </a>
            </div>

            <!-- ==========================================
                 1-CLICK QUICK DEMO SWITCHER CARDS (5 ROLES)
                 ========================================== -->
            <div class="pt-3 border-t border-slate-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-amber-500">bolt</span>
                        1-Click Demo Login (5 Peran)
                    </span>
                    <span class="text-[10px] text-slate-500 font-mono bg-slate-100 px-2 py-0.5 rounded-full font-medium">
                        Sandi: password
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-1.5">
                    <!-- 1. Admin Pusat -->
                    <a href="{{ route('switch-role', 'admin_pusat') }}" 
                       class="flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[15px]">admin_panel_settings</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Admin Pusat</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Super Admin</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">admin.pusat@ssk.kemdikbud.go.id</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[15px] text-slate-400 group-hover:text-indigo-600 transition-all">login</span>
                    </a>

                    <!-- 2. Operator Sekolah -->
                    <a href="{{ route('switch-role', 'operator_sekolah') }}" 
                       class="flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-700 border border-sky-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[15px]">apartment</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Operator Sekolah</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-sky-50 text-sky-700 font-semibold border border-sky-100">SMPN 1 Bitung</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">operator.smpn1bitung@sekolah.sch.id</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[15px] text-slate-400 group-hover:text-sky-600 transition-all">login</span>
                    </a>

                    <!-- 3. Guru -->
                    <a href="{{ route('switch-role', 'guru') }}" 
                       class="flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-700 border border-purple-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[15px]">co_present</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Guru / Pendidik</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-purple-50 text-purple-700 font-semibold border border-purple-100">Kreator Modul</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">guru.bitung@sekolah.sch.id</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[15px] text-slate-400 group-hover:text-purple-600 transition-all">login</span>
                    </a>

                    <!-- 4. Siswa -->
                    <a href="{{ route('switch-role', 'siswa') }}" 
                       class="flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[15px]">school</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Siswa Pembelajar</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100">Sertifikat & Kuis</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">siswa.bitung@sekolah.sch.id</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[15px] text-slate-400 group-hover:text-emerald-600 transition-all">login</span>
                    </a>

                    <!-- 5. Penyuluh / Guest -->
                    <a href="{{ route('switch-role', 'penyuluh') }}" 
                       class="flex items-center justify-between p-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 hover:border-slate-300 transition-all shadow-xs group">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-700 border border-amber-100 flex items-center justify-center font-bold text-xs">
                                <span class="material-symbols-outlined text-[15px]">visibility</span>
                            </div>
                            <div class="text-left">
                                <div class="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
                                    <span>Penyuluh / Tamu</span>
                                    <span class="px-1.5 py-0.2 rounded text-[9px] bg-amber-50 text-amber-700 font-semibold border border-amber-100">Mode Galeri</span>
                                </div>
                                <div class="text-[10px] text-slate-400 font-mono">penyuluh.sulut@bkkbn.go.id</div>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-[15px] text-slate-400 group-hover:text-amber-600 transition-all">login</span>
                    </a>
                </div>
            </div>

            <!-- Footer Quick Links -->
            <div class="pt-1 text-center text-xs text-slate-400">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1 text-indigo-600 font-semibold hover:underline">
                    <span class="material-symbols-outlined text-[15px]">explore</span>
                    <span>Jelajahi Katalog Kursus SSK Tanpa Masuk</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
