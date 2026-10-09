@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 max-w-4xl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Platform LMS</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200">Konfigurasi Sistem</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Kelola preferensi pemutar YouTube, identitas aplikasi, dan aktivasi modul pembelajaran interaktif.</p>
        </div>
    </div>

    <!-- Settings Form -->
    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Card 1: Identitas Platform -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-indigo-600">branding_watermark</span>
                Identitas Platform & Informasi Kontak
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-900">Nama Platform LMS</label>
                    <input type="text" name="site_name" value="{{ $config['site_name'] }}" required
                           class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"/>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-900">Email Dukungan Resmi</label>
                    <input type="email" name="support_email" value="{{ $config['support_email'] }}" required
                           class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"/>
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-xs font-semibold text-slate-900">Tagline / Slogan Aplikasi</label>
                <input type="text" name="site_tagline" value="{{ $config['site_tagline'] }}"
                       class="w-full bg-white border border-slate-200 rounded-lg p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"/>
            </div>
        </div>

        <!-- Card 2: Mesin Integrasi YouTube -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-indigo-600">smart_display</span>
                Konfigurasi Pemutar & Integrasi YouTube
            </h2>

            <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Domain Pemutar YouTube Tanpa Cookie (Nocookie)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menggunakan <code class="font-mono text-indigo-600 font-semibold">youtube-nocookie.com</code> untuk privasi siswa dan kepatuhan GDPR/regulasi data.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">Aktif</span>
            </div>

            <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Penyandian Otomatis HD 1080p & Modest Branding</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menghilangkan logo mengganggu YouTube dan menyetel pemutaran resolusi optimal secara otomatis.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[11px] font-bold">Aktif</span>
            </div>

            <div class="p-4 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold text-slate-900">Mode Ekstraksi Tanpa Kuota API (YouTube oEmbed)</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Menggunakan endpoint publik oEmbed untuk mengekstrak judul & thumbnail tanpa perlu Google API Key.</p>
                </div>
                <span class="px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200 text-[11px] font-bold">Terhubung</span>
            </div>
        </div>

        <!-- Card 3: Fitur Interaksi Belajar Siswa -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-indigo-600">toggle_on</span>
                Saklar Fitur Interaktif Siswa
            </h2>

            <div class="space-y-3">
                <label class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Catatan Pribadi Timestamp</span>
                        <span class="text-[11px] text-slate-500">Memungkinkan siswa menyimpan catatan menit video dan mengulang kembali.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-indigo-600 rounded focus:ring-0">
                </label>

                <label class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Forum Tanya Jawab Komunitas</span>
                        <span class="text-[11px] text-slate-500">Memberikan ruang diskusi interaktif per video materi pembelajaran.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-indigo-600 rounded focus:ring-0">
                </label>

                <label class="flex items-center justify-between p-3.5 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer transition-colors">
                    <div>
                        <span class="text-xs font-bold text-slate-900 block">Aktifkan Kuis Checkpoint Pemahaman</span>
                        <span class="text-[11px] text-slate-500">Evaluasi pemahaman langsung setelah video selesai ditonton.</span>
                    </div>
                    <input type="checkbox" checked class="w-4 h-4 text-indigo-600 rounded focus:ring-0">
                </label>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="px-6 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold transition-colors shadow-xs flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">save</span>
                <span>Simpan Seluruh Pengaturan</span>
            </button>
        </div>
    </form>
</div>
@endsection
