@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 max-w-4xl text-left">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Platform LMS</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200">
                    Konfigurasi Sistem
                </span>
            </div>
            <p class="text-xs text-slate-600 mt-1">Kelola preferensi pemutar YouTube, identitas aplikasi, dan aktivasi modul pembelajaran interaktif.</p>
        </div>
    </div>

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Card 1: Identitas Platform -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5 shadow-xs text-left">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-emerald-700">branding_watermark</span>
                <span>Identitas Platform & Informasi Kontak</span>
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Nama Platform LMS</label>
                    <input type="text" name="site_name" value="{{ $config['site_name'] }}" required
                           class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 shadow-2xs"/>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-700">Email Dukungan Resmi</label>
                    <input type="email" name="support_email" value="{{ $config['support_email'] }}" required
                           class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 shadow-2xs"/>
                </div>
            </div>

            <div class="space-y-1.5">
                <label class="text-xs font-semibold text-slate-700">Tagline / Slogan Aplikasi</label>
                <input type="text" name="site_tagline" value="{{ $config['site_tagline'] }}"
                       class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 min-h-11 shadow-2xs"/>
            </div>
        </div>

        <!-- Card 2: Mesin Integrasi YouTube -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs text-left">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-indigo-700">smart_display</span>
                <span>Konfigurasi Pemutar & Integrasi YouTube</span>
            </h2>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4 shadow-2xs">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Domain Pemutar YouTube Tanpa Cookie (Nocookie)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menggunakan <code class="font-mono text-indigo-700 font-semibold">youtube-nocookie.com</code> untuk privasi siswa dan kepatuhan regulasi data.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold shrink-0">Aktif</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4 shadow-2xs">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Penyandian Otomatis HD 1080p & Modest Branding</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menghilangkan logo mengganggu YouTube dan menyetel pemutaran resolusi optimal secara otomatis.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-[11px] font-bold shrink-0">Aktif</span>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4 shadow-2xs">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Mode Ekstraksi Tanpa Kuota API (YouTube oEmbed)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menggunakan endpoint publik oEmbed untuk mengekstrak judul & thumbnail tanpa perlu Google API Key.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-[11px] font-bold shrink-0">Terhubung</span>
            </div>
        </div>

        <!-- Card 3: Fitur Interaksi Belajar Siswa -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 space-y-4 shadow-xs text-left">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-amber-600">toggle_on</span>
                <span>Saklar Fitur Interaktif Siswa</span>
            </h2>

            <div class="space-y-3">
                <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-50 cursor-pointer transition-colors min-h-12 shadow-2xs">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Catatan Pribadi Timestamp</span>
                        <span class="text-[11px] text-slate-500">Memungkinkan siswa menyimpan catatan menit video dan mengulang kembali.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-0">
                </label>

                <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-50 cursor-pointer transition-colors min-h-12 shadow-2xs">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Forum Tanya Jawab Komunitas</span>
                        <span class="text-[11px] text-slate-500">Memberikan ruang diskusi interaktif per video materi pembelajaran.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-0">
                </label>

                <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-50 cursor-pointer transition-colors min-h-12 shadow-2xs">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Kuis Checkpoint Pemahaman</span>
                        <span class="text-[11px] text-slate-500">Evaluasi pemahaman langsung setelah video selesai ditonton.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-0">
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end pt-2">
            <button type="submit" 
                    class="min-h-11 px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-colors flex items-center gap-2 shadow-xs">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>
</div>
@endsection
