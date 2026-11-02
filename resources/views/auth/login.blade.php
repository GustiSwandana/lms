@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex flex-col justify-center items-center py-10 px-4 sm:px-6 bg-slate-50 antialiased text-slate-800" x-data="{ showPassword: false }">
    <!-- Top Branding & Institutional Header -->
    <div class="text-center mb-6 max-w-lg w-full">
        <!-- Sponsor & Partner Logos Strip (All 7 Institutional Logos) -->
        <div class="inline-flex flex-wrap items-center justify-center gap-2.5 sm:gap-3.5 px-4 py-2 rounded-2xl bg-white border border-slate-200 shadow-2xs mb-4">
            <img src="{{ asset('images/logos/lpdp.png') }}" alt="LPDP" class="h-5 sm:h-6 w-auto object-contain" title="Lembaga Pengelola Dana Pendidikan"/>
            <img src="{{ asset('images/logos/risbang.png') }}" alt="Ditjen Risbang" class="h-5 sm:h-6 w-auto object-contain" title="Direktorat Jenderal Riset dan Pengembangan"/>
            <img src="{{ asset('images/logos/hiliriset.png') }}" alt="Hiliriset" class="h-5 sm:h-6 w-auto object-contain" title="Program Hilirisasi Riset"/>
            <div class="h-4 w-px bg-slate-200 hidden sm:block"></div>
            <img src="{{ asset('images/logos/unima.png') }}" alt="UNIMA" class="h-5 sm:h-6 w-auto object-contain" title="Universitas Negeri Manado"/>
            <img src="{{ asset('images/logos/unpi.png') }}" alt="UNPI" class="h-5 sm:h-6 w-auto object-contain" title="Universitas Pembangunan Indonesia"/>
            <img src="{{ asset('images/logos/ipdn.png') }}" alt="IPDN" class="h-5 sm:h-6 w-auto object-contain" title="Institut Pemerintahan Dalam Negeri"/>
            <img src="{{ asset('images/logos/bkkbn.png') }}" alt="BKKBN" class="h-5 sm:h-6 w-auto object-contain" title="BKKBN Sulawesi Utara"/>
        </div>

        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
            SIAGA BUMI<span class="text-emerald-600">-SSK</span>
        </h1>
        <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mt-1">
            Program Hiliriset LPDP & Ditjen Risbang • BKKBN Sulut
        </p>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed px-4">
            Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
        </p>
    </div>

    <!-- Centered Form Card -->
    <div class="max-w-md w-full bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <!-- Form Header -->
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                Masuk ke Akun Pembelajaran
            </h2>
            <p class="text-xs text-slate-500 mt-1">
                Yuk, masukkan email dan kata sandimu untuk lanjut belajar hari ini.
            </p>
        </div>

        <!-- Session & Validation Alert Blocks -->
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">info</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <!-- Login Form -->
        <form class="space-y-4" action="{{ route('login.post') }}" method="POST">
            @csrf

            <!-- Email Input -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-slate-700">
                    Alamat Email
                </label>
                <div class="relative rounded-xl">
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
                           class="w-full pl-10 pr-4 py-3 bg-white rounded-xl border border-slate-300 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 min-h-12 transition-all"/>
                </div>
            </div>

            <!-- Password Input -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-semibold text-slate-700">
                        Kata Sandi
                    </label>
                    <a href="{{ route('password.request') }}" class="text-xs font-medium text-emerald-700 hover:text-emerald-800 hover:underline">
                        Lupa kata sandi?
                    </a>
                </div>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                    </div>
                    <input id="password" 
                           name="password" 
                           :type="showPassword ? 'text' : 'password'" 
                           autocomplete="current-password" 
                           required 
                           placeholder="Masukkan kata sandi..."
                           class="w-full pl-10 pr-12 py-3 bg-white rounded-xl border border-slate-300 text-sm text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-500/20 min-h-12 transition-all"/>
                    <button type="button" 
                            @click="showPassword = !showPassword" 
                            aria-label="Lihat kata sandi"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center justify-center text-slate-400 hover:text-slate-700 min-h-12 w-12 transition-colors">
                        <span class="material-symbols-outlined text-[20px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"/>
                    <span class="text-xs text-slate-600">Ingat sesi masuk saya</span>
                </label>
            </div>

            <!-- Submit Button (Min 48px Touch Target) -->
            <button type="submit" 
                    class="w-full min-h-12 flex items-center justify-center gap-2 py-3.5 px-5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px]">login</span>
                <span>Masuk ke Akun</span>
            </button>
        </form>

        <!-- Hak Akses Terintegrasi Platform -->
        <div class="pt-4 border-t border-slate-100 text-center space-y-2.5">
            <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">
                Hak Akses Terintegrasi Sistem:
            </span>
            <div class="flex flex-wrap items-center justify-center gap-1.5">
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                    Admin Pusat
                </span>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                    Operator Sekolah
                </span>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                    Guru / Pendidik
                </span>
                <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                    Siswa Pembelajar
                </span>
                <span class="px-2.5 py-1 rounded-full bg-amber-50 text-amber-800 text-xs font-medium border border-amber-200">
                    Penyuluh / Tamu
                </span>
            </div>
        </div>

        <!-- Footer Link -->
        <div class="text-center pt-2">
            <p class="text-xs text-slate-600">
                Belum memiliki akun pembelajar? 
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:underline">
                    Daftar Akun Baru
                </a>
            </p>
            <p class="text-[10px] text-slate-400 mt-3 text-center">
                Didanai oleh LPDP & Ditjen Risbang (Program Hiliriset) • Konsorsium UNIMA, UNPI, IPDN & BKKBN Sulut
            </p>
        </div>
    </div>
</div>
@endsection
