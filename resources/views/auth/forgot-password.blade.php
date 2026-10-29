@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Logo & Branding -->
        <a href="{{ route('login') }}" class="inline-flex items-center gap-2.5 mb-4 group">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-xs group-hover:scale-105 transition-transform">
                <span class="material-symbols-outlined text-[24px]">lock_reset</span>
            </div>
            <div class="text-left">
                <span class="font-extrabold text-xl tracking-tight text-slate-900 block leading-none">SIAGA BUMI<span class="text-indigo-600">-SSK</span></span>
                <span class="text-[11px] font-mono text-slate-500 tracking-wider uppercase">Sekolah Siaga Kependudukan Sulawesi Utara</span>
            </div>
        </a>
        <h2 class="text-2xl font-bold tracking-tight text-slate-900">
            Lupa Kata Sandi?
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Masukkan alamat email yang terdaftar pada akun pembelajaran Anda
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 sm:px-10 shadow-xs border border-slate-200 rounded-xl space-y-4">
            
            @if(session('success'))
                <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px] text-rose-600">error</span>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            <form class="space-y-4" action="{{ route('password.email') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-900 mb-1.5">
                        Alamat Email Akun
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                        </span>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               required 
                               placeholder="nama@sekolah.sch.id"
                               value="{{ old('email') }}"
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-lg border border-slate-200 text-xs text-slate-900 placeholder-slate-400 focus:outline-none focus:border-indigo-600 transition-all"/>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                            class="w-full flex justify-center items-center gap-2 py-2.5 px-4 rounded-lg shadow-xs text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition-all">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                        <span>Kirim Permintaan Reset</span>
                    </button>
                </div>
            </form>

            <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2">
                <span class="material-symbols-outlined text-[18px] text-amber-600 shrink-0 mt-0.5">contact_support</span>
                <div>
                    <p class="font-bold">Bantuan Langsung Operator:</p>
                    <p class="text-[11px] text-amber-900/80 mt-0.5">
                        Siswa juga dapat meminta reset kata sandi secara instan kepada Operator Sekolah melalui panel Manajer Siswa.
                    </p>
                </div>
            </div>

            <div class="text-center pt-2">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-indigo-600 hover:underline inline-flex items-center gap-1">
                    <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
