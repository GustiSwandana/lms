@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 text-slate-800">
    <div class="text-center mb-6 max-w-md w-full">
        <a href="{{ route('login') }}" class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-600 text-white shadow-xs mb-3 group hover:bg-emerald-700 transition-colors">
            <span class="material-symbols-outlined text-[26px]">lock_reset</span>
        </a>
        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">
            Lupa Kata Sandi?
        </h1>
        <p class="text-xs text-slate-500 mt-1">
            Masukkan alamat email yang terdaftar pada akun pembelajaran Anda.
        </p>
    </div>

    <div class="max-w-md w-full bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-sm space-y-5">
        @if(session('success'))
            <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-rose-600 shrink-0">error</span>
                <div>{{ $errors->first() }}</div>
            </div>
        @endif

        <form class="space-y-4" action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="space-y-1.5">
                <label for="email" class="block text-xs font-semibold text-slate-700">
                    Alamat Email Akun
                </label>
                <div class="relative rounded-xl">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">mail</span>
                    </span>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           required 
                           placeholder="nama@sekolah.sch.id"
                           value="{{ old('email') }}"
                           class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 transition-all"/>
                </div>
            </div>

            <div class="pt-1">
                <button type="submit" 
                        class="w-full min-h-11 flex justify-center items-center gap-2 py-2.5 px-4 rounded-xl text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 transition-all shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">send</span>
                    <span>Kirim Tautan Reset Kata Sandi</span>
                </button>
            </div>
        </form>

        <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 flex items-start gap-2.5">
            <span class="material-symbols-outlined text-[18px] text-amber-600 shrink-0 mt-0.5">contact_support</span>
            <div>
                <p class="font-bold text-slate-900">Bantuan Langsung Operator:</p>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Siswa juga dapat meminta reset kata sandi secara instan kepada Operator Sekolah melalui panel Manajer Siswa.
                </p>
            </div>
        </div>

        <div class="text-center pt-2 border-t border-slate-100">
            <a href="{{ route('login') }}" class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 hover:underline inline-flex items-center gap-1 min-h-11">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Halaman Masuk</span>
            </a>
        </div>
    </div>
</div>
@endsection
