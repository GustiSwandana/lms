@extends('layouts.admin')

@section('admin_content')
<div class="space-y-8 max-w-4xl">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dokumentasi API & Integrasi YouTube</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200">Panduan Developer</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Spesifikasi teknis arsitektur integrasi tautan YouTube, algoritma parsing regex, dan daftar endpoint internal LMS.</p>
        </div>
    </div>

    <!-- Section 1: Algoritma Ekstraksi Video ID -->
    <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-indigo-600">terminal</span>
            1. Algoritma Ekstraksi YouTube Video ID
        </h2>
        <p class="text-xs text-slate-600 leading-relaxed">
            Sistem LMS EduTube menggunakan pola regex multi-format untuk mengekstrak 11 karakter unik ID video YouTube dari berbagai bentuk tautan URL yang ditempelkan pengguna:
        </p>

        <div class="bg-slate-900 text-emerald-400 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner">
<code># Format URL yang didukung:
1. https://www.youtube.com/watch?v=wm5gMKuwSYk
2. https://youtu.be/wm5gMKuwSYk
3. https://www.youtube.com/embed/wm5gMKuwSYk
4. https://www.youtube.com/shorts/wm5gMKuwSYk
5. https://www.youtube.com/watch?v=wm5gMKuwSYk&t=120s
6. wm5gMKuwSYk (Direct 11-char ID)</code>
        </div>
    </section>

    <!-- Section 2: Integrasi Pemutar & Iframe postMessage -->
    <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-indigo-600">play_circle</span>
            2. Arsitektur Iframe Nocookie & Timestamp Seeking
        </h2>
        <p class="text-xs text-slate-600 leading-relaxed">
            Untuk menjaga kepatuhan privasi dan interaktivitas tingkat tinggi, pemutar video disematkan dengan domain <code class="font-mono text-indigo-600 font-semibold">https://www.youtube-nocookie.com/embed/{video_id}?autoplay=1&enablejsapi=1&rel=0</code>.
            Fitur lompatan menit (timestamp seek) pada tab Catatan Siswa bekerja dengan mengirimkan pesan event <code class="font-mono text-indigo-600 font-semibold">postMessage</code>:
        </p>

        <div class="bg-slate-900 text-sky-300 p-4 rounded-xl font-mono text-xs overflow-x-auto shadow-inner">
<code>function seekTo(seconds) {
    const iframe = document.getElementById('youtube-player');
    if (iframe && iframe.contentWindow) {
        iframe.contentWindow.postMessage(JSON.stringify({
            event: 'command',
            func: 'seekTo',
            args: [Number(seconds) || 0, true]
        }), '*');
    }
}</code>
        </div>
    </section>

    <!-- Section 3: REST & AJAX Endpoints Directory -->
    <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
        <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="material-symbols-outlined text-[20px] text-indigo-600">api</span>
            3. Direktori Endpoint REST / AJAX LMS
        </h2>

        <div class="overflow-x-auto rounded-xl border border-slate-200">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-4">Metode</th>
                        <th class="py-3 px-4">Path Endpoint</th>
                        <th class="py-3 px-4">Deskripsi & Fungsi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-indigo-600">POST</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/admin/youtube/parse</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Ekstraksi instan ID & thumbnail dari URL YouTube</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-indigo-600">POST</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/admin/youtube/import-playlist</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Sinkronisasi batch playlist YouTube ke dalam kursus</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-indigo-600">POST</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/pelajaran/{id}/toggle-complete</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Tandai selesai atau batal selesai pelajaran siswa</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-indigo-600">POST</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/pelajaran/{id}/notes</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Simpan catatan timestamp siswa ke database</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-rose-600">DELETE</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/notes/{id}</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Hapus catatan pribadi siswa</td>
                    </tr>
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-bold text-indigo-600">POST</td>
                        <td class="py-3 px-4 text-slate-900 font-semibold">/kursus/{slug}/toggle-bookmark</td>
                        <td class="py-3 px-4 font-sans text-slate-600">Simpan kursus ke penanda (bookmark) siswa</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
