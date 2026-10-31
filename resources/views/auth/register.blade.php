@extends('layouts.app')

@section('content')
<div class="min-h-screen w-full flex flex-col justify-center items-center py-10 px-4 sm:px-6 bg-slate-50 antialiased text-slate-800" x-data="{ showPassword: false, showConfirmPassword: false }">
    <!-- Top Branding & Institutional Header -->
    <div class="text-center mb-6 max-w-lg w-full">
        <a href="{{ route('student.dashboard') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-600 text-white shadow-xs mb-3 group hover:bg-emerald-700 transition-colors">
            <span class="material-symbols-outlined text-[28px]">nature_people</span>
        </a>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
            SIAGA BUMI<span class="text-emerald-600">-SSK</span>
        </h1>
        <p class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider mt-1">
            BKKBN Perwakilan Sulawesi Utara
        </p>
        <p class="text-xs text-slate-600 mt-2 leading-relaxed px-4">
            Daftarkan diri Anda untuk mengakses materi pembelajaran, kuis interaktif, dan sertifikat resmi.
        </p>
    </div>

    <!-- Centered Form Card -->
    <div class="max-w-md w-full bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
        <div class="border-b border-slate-100 pb-4">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">
                Pendaftaran Akun Baru
            </h2>
            <p class="text-xs text-slate-500 mt-0.5">
                Lengkapi formulir di bawah ini untuk membuat akun pembelajar.
            </p>
        </div>

        @if(isset($errors) && $errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form class="space-y-4" action="{{ route('register.post') }}" method="POST">
            @csrf

            <!-- Full Name -->
            <div class="space-y-1.5">
                <label for="name" class="block text-xs font-semibold text-slate-700">
                    Nama Lengkap Siswa
                </label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                    </div>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                           placeholder="Nama lengkap sesuai data sekolah"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all"/>
                </div>
            </div>

            <!-- Email -->
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-slate-700">
                    Alamat Email Aktif
                </label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">alternate_email</span>
                    </div>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           placeholder="nama@email.com atau akun@sekolah.sch.id"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all"/>
                </div>
            </div>

            <!-- School Select -->
            <div class="space-y-1.5">
                <label for="school_id" class="block text-xs font-semibold text-slate-700">
                    Asal Sekolah Mitra SSK
                </label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                    </div>
                    <select id="school_id" name="school_id"
                            class="w-full pl-10 pr-8 py-2.5 bg-slate-50 rounded-xl border border-slate-300 text-xs text-slate-900 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all">
                        <option value="">Pilih Sekolah (Opsional)</option>
                        @foreach(\App\Models\School::all() as $sch)
                            <option value="{{ $sch->id }}" {{ old('school_id') == $sch->id ? 'selected' : '' }}>
                                {{ $sch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-1.5">
                <label for="password" class="block text-xs font-semibold text-slate-700">
                    Kata Sandi (Minimal 8 Karakter)
                </label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">lock</span>
                    </div>
                    <input id="password" name="password" :type="showPassword ? 'text' : 'password'" required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-11 py-2.5 bg-slate-50 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all"/>
                    <button type="button" @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined text-[18px]" x-text="showPassword ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-1.5">
                <label for="password_confirmation" class="block text-xs font-semibold text-slate-700">
                    Konfirmasi Kata Sandi
                </label>
                <div class="relative rounded-xl">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                    </div>
                    <input id="password_confirmation" name="password_confirmation" :type="showConfirmPassword ? 'text' : 'password'" required
                           placeholder="••••••••"
                           class="w-full pl-10 pr-11 py-2.5 bg-slate-50 rounded-xl border border-slate-300 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all"/>
                    <button type="button" @click="showConfirmPassword = !showConfirmPassword"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                        <span class="material-symbols-outlined text-[18px]" x-text="showConfirmPassword ? 'visibility_off' : 'visibility'"></span>
                    </button>
                </div>
            </div>

            <button type="submit" 
                    class="w-full min-h-11 flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold transition-all shadow-xs mt-2">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Daftar Akun Sekarang</span>
            </button>
        </form>

        <div class="text-center pt-2 border-t border-slate-100">
            <p class="text-xs text-slate-600">
                Sudah memiliki akun terdaftar? 
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:underline">
                    Masuk di Sini
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
