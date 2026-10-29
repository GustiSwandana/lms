@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex flex-col lg:flex-row bg-slate-50 antialiased" x-data="{ showPassword: false, showConfirmPassword: false }">
    <!-- ==========================================
         LEFT HERO PANEL (SHOWCASE & BRANDING)
         ========================================== -->
    <div class="relative hidden lg:flex lg:w-1/2 xl:w-7/12 flex-col justify-between p-12 overflow-hidden bg-slate-900 text-white border-r border-slate-800">
        <!-- Ambient Decorative Glow & Grid Overlay -->
        <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

        <!-- Top Header & Brand -->
        <div class="relative z-10 flex items-center justify-between">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                    <span class="material-symbols-outlined text-[24px]">nature_people</span>
                </div>
                <div>
                    <span class="text-lg font-bold tracking-tight text-white block leading-tight">
                        SIAGA BUMI<span class="text-indigo-400">-SSK</span>
                    </span>
                    <span class="text-[10px] font-mono tracking-wider text-slate-400 uppercase">
                        Sekolah Siaga Kependudukan Sulawesi Utara
                    </span>
                </div>
            </a>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs text-slate-300 font-medium">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Pendaftaran Akun Terbuka</span>
            </div>
        </div>

        <!-- Hero Center Content -->
        <div class="relative z-10 my-auto py-12 max-w-xl space-y-6">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-800 border border-slate-700 text-xs font-semibold text-indigo-300">
                    <span class="material-symbols-outlined text-[16px] text-amber-400">verified</span>
                    <span>Bergabung Sebagai Siswa / Pembelajar Mandiri</span>
                </div>
                <h1 class="text-3xl xl:text-4xl font-extrabold text-white leading-tight tracking-tight">
                    Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
                </h1>
                <p class="text-sm xl:text-base text-slate-300 leading-relaxed font-normal">
                    Daftarkan diri Anda untuk mengakses seluruh kurikulum pembelajaran Sekolah Siaga Kependudukan (SSK), berdiskusi dalam komunitas, mengikuti evaluasi kuis, serta meraih sertifikat resmi ber-QR Code.
                </p>
            </div>

            <!-- 3 Benefit Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-indigo-500/20 text-indigo-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">menu_book</span>
                    </div>
                    <div class="font-bold text-xs text-white">Materi Lengkap</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Akses modul kependudukan dan aksi peduli bumi.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                    <div class="font-bold text-xs text-white">E-Sertifikat Resmi</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Diterbitkan otomatis dan dapat diverifikasi instansi.</div>
                </div>

                <div class="p-4 rounded-xl bg-slate-800/80 border border-slate-700/80">
                    <div class="w-8 h-8 rounded-lg bg-teal-500/20 text-teal-400 flex items-center justify-center mb-2.5">
                        <span class="material-symbols-outlined text-[18px]">quiz</span>
                    </div>
                    <div class="font-bold text-xs text-white">Kuis Interaktif</div>
                    <div class="text-[11px] text-slate-400 mt-1 leading-snug">Uji pemahaman topik secara langsung dan terukur.</div>
                </div>
            </div>

            <!-- Partner Endorsement -->
            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700 flex items-center gap-3.5">
                <div class="w-9 h-9 rounded-full bg-indigo-500/20 text-indigo-300 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[20px]">handshake</span>
                </div>
                <div class="text-xs">
                    <p class="text-slate-200 font-medium leading-relaxed">
                        Kolaborasi BKKBN Perwakilan Sulawesi Utara, UNIMA, UNPI, dan IPDN dalam pembinaan generasi emas berwawasan lingkungan & kependudukan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Bottom Meta & Security Badge -->
        <div class="relative z-10 pt-6 border-t border-slate-800 flex items-center justify-between text-xs text-slate-400">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px] text-emerald-400">lock</span>
                <span>Data Pribadi Terlindungi & Terenkripsi Aman</span>
            </div>
            <span>© {{ date('Y') }} SIAGA BUMI-SSK</span>
        </div>
    </div>

    <!-- ==========================================
         RIGHT AUTH FORM PANEL (REGISTRATION)
         ========================================== -->
    <div class="w-full lg:w-1/2 xl:w-5/12 flex flex-col justify-center px-6 sm:px-12 lg:px-16 py-10">
        <div class="max-w-md w-full mx-auto space-y-5">
            <!-- Mobile Brand Header -->
            <div class="lg:hidden flex items-center justify-between pb-3 border-b border-slate-200">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                        <span class="material-symbols-outlined text-[18px]">nature_people</span>
                    </div>
                    <div>
                        <span class="font-bold text-base text-slate-900 block leading-none">SIAGA BUMI<span class="text-indigo-600">-SSK</span></span>
                        <span class="text-[10px] text-slate-400 font-mono">Pendaftaran Siswa Baru</span>
                    </div>
                </a>
                <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:underline flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">login</span>
                    <span>Masuk</span>
                </a>
            </div>

            <!-- Form Intro Header -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                        Registrasi Siswa / Akun Baru
                    </span>
                    <a href="{{ route('login') }}" class="hidden lg:inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-indigo-600 transition-colors">
                        <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                        <span>Sudah ada akun? Masuk</span>
                    </a>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                    Buat Akun Pembelajar
                </h2>
                <p class="text-xs text-slate-500">
                    Lengkapi identitas berikut untuk memulai perjalanan belajar Anda di platform SIAGA BUMI-SSK.
                </p>
            </div>

            <!-- Error Messages -->
            @if(isset($errors) && $errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5 shadow-xs">
                    <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <!-- Registration Form -->
            <form class="space-y-3.5" action="{{ route('register.post') }}" method="POST">
                @csrf

                <!-- 1. Nama Lengkap (Required) -->
                <div class="space-y-1">
                    <label for="name" class="block text-xs font-semibold text-slate-700">
                        Nama Lengkap <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">person</span>
                        </div>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               required 
                               value="{{ old('name') }}"
                               placeholder="Nama lengkap sesuai identitas / rapor"
                               class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                    </div>
                </div>

                <!-- 2. Alamat (Required) -->
                <div class="space-y-1">
                    <label for="address" class="block text-xs font-semibold text-slate-700">
                        Alamat Lengkap / Domisili <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative rounded-xl shadow-xs">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                        </div>
                        <input id="address" 
                               name="address" 
                               type="text" 
                               required 
                               value="{{ old('address') }}"
                               placeholder="Kota/Kabupaten, Kecamatan, atau nama jalan"
                               class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <!-- 3. Email (Required, Valid, Unique) -->
                    <div class="space-y-1">
                        <label for="email" class="block text-xs font-semibold text-slate-700">
                            Alamat Email <span class="text-rose-500">*</span>
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
                                   placeholder="nama@email.com"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                        </div>
                    </div>

                    <!-- 4. No. HP (Required, String/Phone format) -->
                    <div class="space-y-1">
                        <label for="phone" class="block text-xs font-semibold text-slate-700">
                            No. Handphone / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">phone</span>
                            </div>
                            <input id="phone" 
                                   name="phone" 
                                   type="tel" 
                                   required 
                                   value="{{ old('phone') }}"
                                   placeholder="08123456789"
                                   class="w-full pl-10 pr-4 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                        </div>
                    </div>
                </div>

                <!-- 5 & 6. Kata Sandi & Konfirmasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label for="password" class="block text-xs font-semibold text-slate-700">
                            Kata Sandi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">lock</span>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'" 
                                   required 
                                   placeholder="Minimal 6 karakter"
                                   class="w-full pl-10 pr-10 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                            <button type="button" 
                                    @click="showPassword = !showPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none">
                                <span class="material-symbols-outlined text-[18px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">
                            Konfirmasi Sandi <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative rounded-xl shadow-xs">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                            </div>
                            <input id="password_confirmation" 
                                   name="password_confirmation" 
                                   :type="showConfirmPassword ? 'text' : 'password'" 
                                   required 
                                   placeholder="Ulangi kata sandi"
                                   class="w-full pl-10 pr-10 py-2.5 bg-white rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                            <button type="button" 
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-700 focus:outline-none">
                                <span class="material-symbols-outlined text-[18px]" x-text="showConfirmPassword ? 'visibility_off' : 'visibility'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" 
                            class="w-full flex justify-center items-center gap-2 py-2.5 px-4 rounded-xl text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none shadow-xs transition-all active:scale-[0.99] group">
                        <span>Daftar Akun Siswa</span>
                        <span class="material-symbols-outlined text-[18px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            </form>

            <!-- Back to Login Notice -->
            <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-center text-slate-600">
                Sudah memiliki akun terdaftar?
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-700 hover:underline ml-1">
                    Masuk ke Akun
                </a>
            </div>

            <!-- Footer Quick Links -->
            <div class="pt-1 text-center text-xs text-slate-400">
                <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-1 text-slate-500 hover:text-indigo-600 font-medium">
                    <span class="material-symbols-outlined text-[15px]">explore</span>
                    <span>Jelajahi Beranda SIAGA BUMI-SSK</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
