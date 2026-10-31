@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 max-w-5xl pb-16" x-data="{ activeTab: 'manual', activeChapter: 'bab2' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-3 flex-wrap">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Panduan Penggunaan Aplikasi & Dokumentasi Sistem</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200 font-mono">Edisi 2026</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Buku panduan operasional resmi untuk Siswa, Guru, dan Administrator, dilengkapi Dokumentasi API & Integrasi YouTube serta spesifikasi teknis.</p>
        </div>

        <!-- Tombol Aksi Unduh DOCX -->
        <div class="flex items-center gap-2">
            <a href="{{ asset('docs/Panduan_Penggunaan_SiagaBumi_SSK.docx') }}" 
               download="Panduan_Penggunaan_SiagaBumi_SSK.docx"
               class="min-h-11 inline-flex items-center gap-2.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-semibold shadow-xs transition-all">
                <span class="material-symbols-outlined text-[18px]">description</span>
                <span>Unduh Buku Panduan (.DOCX)</span>
                <span class="bg-white/20 text-[10px] px-1.5 py-0.5 rounded font-mono font-normal">1.4 MB</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs Utama -->
    <div class="flex border-b border-slate-200 gap-6 text-sm">
        <button type="button" 
                @click="activeTab = 'manual'" 
                :class="activeTab === 'manual' ? 'border-emerald-600 text-emerald-700 font-bold border-b-2 pb-3' : 'text-slate-500 hover:text-slate-900 pb-3 font-medium transition-colors'"
                class="min-h-11 flex items-center">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                Buku Panduan Pengguna (User Manual)
            </span>
        </button>
        <button type="button" 
                @click="activeTab = 'dev'" 
                :class="activeTab === 'dev' ? 'border-emerald-600 text-emerald-700 font-bold border-b-2 pb-3' : 'text-slate-500 hover:text-slate-900 pb-3 font-medium transition-colors'"
                class="min-h-11 flex items-center">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">terminal</span>
                Spesifikasi Teknis & API Developer
            </span>
        </button>
    </div>

    <!-- TAB 1: BUKU PANDUAN PENGGUNA LENGKAP -->
    <div x-show="activeTab === 'manual'" class="space-y-6">
        <!-- Navigasi Bab Cepat -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
            <button type="button" @click="activeChapter = 'bab1'" 
                    :class="activeChapter === 'bab1' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 1</div>
                <div class="text-xs font-bold truncate">Pendahuluan</div>
            </button>
            <button type="button" @click="activeChapter = 'bab2'" 
                    :class="activeChapter === 'bab2' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 2</div>
                <div class="text-xs font-bold truncate">Panduan Siswa</div>
            </button>
            <button type="button" @click="activeChapter = 'bab3'" 
                    :class="activeChapter === 'bab3' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 3</div>
                <div class="text-xs font-bold truncate">Admin & Guru</div>
            </button>
            <button type="button" @click="activeChapter = 'bab4'" 
                    :class="activeChapter === 'bab4' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 4</div>
                <div class="text-xs font-bold truncate">Super Admin</div>
            </button>
            <button type="button" @click="activeChapter = 'bab5'" 
                    :class="activeChapter === 'bab5' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 5</div>
                <div class="text-xs font-bold truncate">Syarat Sertifikat</div>
            </button>
            <button type="button" @click="activeChapter = 'bab6'" 
                    :class="activeChapter === 'bab6' ? 'bg-emerald-600 text-white font-bold shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="min-h-11 p-3 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-80">Bab 6</div>
                <div class="text-xs font-bold truncate">FAQ & Solusi</div>
            </button>
        </div>

        <!-- ==================== BAB 1 ==================== -->
        <div x-show="activeChapter === 'bab1'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wide">Bab 1</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Pendahuluan & Gambaran Umum Sistem</h2>
                <p class="text-xs text-slate-500 mt-0.5">Visi platform, keunggulan kurasi video YouTube bebas iklan, alur kerja, dan pembagian hak akses (RBAC).</p>
            </div>

            <!-- 1.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-emerald-700">1.1 Visi & Latar Belakang SIAGA BUMI-SSK</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    SIAGA BUMI-SSK diciptakan untuk mengintegrasikan potensi tak terbatas konten pembelajaran di YouTube ke dalam kurikulum Sekolah Siaga Kependudukan (SSK) binaan BKKBN yang bebas distraksi iklan, bebas rekomendasi video acak, dan terukur secara akademis melalui pos pemeriksaan kuis (*checkpoint*).
                </p>
            </div>

            <!-- 1.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-emerald-700">1.2 Keunggulan Utama Platform</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">bolt</span> Hemat Bandwidth Server
                        </span>
                        <p class="text-slate-600 text-[11px]">Memanfaatkan CDN global YouTube sehingga sekolah tidak terbebani biaya penyimpanan dan streaming video berskala besar.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">block</span> Bebas Distraksi Iklan
                        </span>
                        <p class="text-slate-600 text-[11px]">Pemutar video disematkan dengan parameter ketat yang meniadakan jeda iklan komersial dan rekomendasi video pihak ketiga.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-sky-600">quiz</span> Checkpoint Kuis Bertingkat
                        </span>
                        <p class="text-slate-600 text-[11px]">Materi pelajaran disertai kuis evaluasi pemahaman dengan ambang kelulusan (*passing grade*) dan pembahasan langsung.</p>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">verified</span> Sertifikasi Digital QR Code
                        </span>
                        <p class="text-slate-600 text-[11px]">Sertifikat resmi terbit otomatis jika siswa tuntas 100% video dan lulus seluruh kuis, lengkap dengan verifikasi publik online.</p>
                    </div>
                </div>
            </div>

            <!-- 1.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-emerald-700">1.3 Alur Kerja Pembelajaran Terpadu</h3>
                <ol class="list-decimal list-inside text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <li><strong>Konfigurasi Lembaga (Super Admin):</strong> Pendaftaran sekolah mitra dan penetapan kategori ilmu.</li>
                    <li><strong>Penyusunan Kurikulum (Guru):</strong> Membuat kursus, mengimpor video YouTube, menyusun kuis, dan melampirkan berkas PDF/praktikum.</li>
                    <li><strong>Pembelajaran Aktif (Siswa):</strong> Menonton materi, membuat catatan timestamped, berdiskusi, dan mengerjakan kuis evaluasi.</li>
                    <li><strong>Validasi & Kelulusan (Sistem):</strong> Sistem memvalidasi syarat 100% video + kelulusan kuis untuk membuka sertifikat digital.</li>
                </ol>
            </div>

            <!-- 1.4 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-emerald-700">1.4 Matriks Hak Akses & Peran Pengguna (RBAC Matrix)</h3>
                <div class="overflow-x-auto rounded-xl border border-slate-200 text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase">
                            <tr>
                                <th class="p-3.5">Peran (Role)</th>
                                <th class="p-3.5">Cakupan Akses</th>
                                <th class="p-3.5">Wewenang Utama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3.5 font-bold text-indigo-700">Super Admin</td>
                                <td class="p-3.5 text-slate-900">Seluruh Platform & Multi-Sekolah</td>
                                <td class="p-3.5 text-slate-600">Kelola sekolah mitra, manajemen user global, master kategori, konfigurasi platform, helpdesk pusat.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3.5 font-bold text-sky-700">Admin Sekolah / Guru</td>
                                <td class="p-3.5 text-slate-900">Lembaga Sekolah Masing-Masing</td>
                                <td class="p-3.5 text-slate-600">Course Builder, impor video YouTube otomatis, manajemen modul & kuis, registrasi siswa via CSV.</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="p-3.5 font-bold text-emerald-700">Siswa (Student)</td>
                                <td class="p-3.5 text-slate-900">Ruang Belajar Mandiri</td>
                                <td class="p-3.5 text-slate-600">Katalog kursus, pemutar video interaktif, catatan timestamped, diskusi, kuis evaluasi, klaim sertifikat.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== BAB 2 ==================== -->
        <div x-show="activeChapter === 'bab2'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 uppercase tracking-wide">Bab 2</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Panduan Penggunaan untuk Siswa (Learner Journey)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tata cara login, eksplorasi katalog kursus, ruang belajar classroom, kuis, dan klaim sertifikat digital.</p>
            </div>

            <!-- 2.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-mono font-bold">2.1</span>
                    Akses Masuk (Login) & Pengenalan Dasbor Siswa
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Siswa mengakses URL portal LMS sekolah menggunakan email dan kata sandi yang telah didaftarkan. Pada dasbor utama, siswa disajikan ringkasan: kursus yang sedang dipelajari, progres linear terstruktur, serta akses cepat melanjutkan materi terakhir.
                </p>
            </div>

            <!-- 2.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-mono font-bold">2.2</span>
                    Eksplorasi Katalog Kursus & Pilihan Materi
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Katalog kursus dapat disaring berdasarkan 3 Pilar Kurikulum SSK (Kependudukan, Lingkungan Hidup, PPKS / Kesehatan Reproduksi) serta tingkat kesulitan materi.
                </p>
            </div>

            <!-- 2.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-mono font-bold">2.3</span>
                    Ruang Belajar Interaktif (Video Classroom)
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Tampilan teater video dengan pemutar 16:9 responsif, tab catatan waktu (timestamp notes), materi silabus terstruktur, dan unduhan berkas panduan.
                </p>
            </div>
        </div>

        <!-- ==================== BAB 3 ==================== -->
        <div x-show="activeChapter === 'bab3'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-sky-50 text-sky-700 border border-sky-200 uppercase tracking-wide">Bab 3</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Panduan Penggunaan untuk Guru & Admin Sekolah</h2>
                <p class="text-xs text-slate-500 mt-0.5">Pengelolaan kursus, penyusunan silabus video YouTube, kuis evaluasi, dan manajemen data siswa.</p>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center text-xs font-mono font-bold">3.1</span>
                    Penyusun Kursus Terpadu (Course Builder)
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Melalui Course Builder, guru dapat mengelola informasi kursus, mengimpor video YouTube dengan oEmbed validator, mengatur urutan pelajaran secara visual, dan merancang soal kuis checkpoint.
                </p>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-sky-100 text-sky-800 flex items-center justify-center text-xs font-mono font-bold">3.2</span>
                    Manajemen Siswa & Impor Massal CSV
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Operator sekolah dapat menambahkan siswa satu per satu atau memanfaatkan template CSV untuk mendaftarkan ratusan siswa sekaligus dalam hitungan detik.
                </p>
            </div>
        </div>

        <!-- ==================== BAB 4 ==================== -->
        <div x-show="activeChapter === 'bab4'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200 uppercase tracking-wide">Bab 4</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Panduan Administrasi Pusat (Super Admin)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manajemen sekolah mitra, tata kelola akun pengguna, konfigurasi platform global, dan pemantauan helpdesk.</p>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center text-xs font-mono font-bold">4.1</span>
                    Manajemen Sekolah Binaan BKKBN
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Super Admin mengelola sekolah binaan resmi: SMP Negeri 1 Bitung dan Sekolah Rakyat Menengah Atas 44 Minahasa.
                </p>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center text-xs font-mono font-bold">4.5</span>
                    Pengaturan Konfigurasi Platform Global
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Pada menu Pengaturan Sistem (<code class="font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">/admin/settings</code>), Super Admin mengontrol parameter aplikasi seperti: Nama Platform, Logo Resmi, Batas Waktu Kedaluwarsa Sesi (*Session Lifetime*), dan Kontak Helpdesk Resmi.
                </p>
            </div>

            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-100 text-indigo-800 flex items-center justify-center text-xs font-mono font-bold">4.6</span>
                    Helpdesk Pusat, Penanganan Tiket, & Diagnostik Sistem Server
                </h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Merespons tiket pengaduan dari seluruh administrator sekolah mitra, serta meninjau laporan diagnostik server (versi PHP, versi framework Laravel, driver database, dan konektivitas YouTube Player API).
                </p>
            </div>
        </div>

        <!-- ==================== BAB 5 ==================== -->
        <div x-show="activeChapter === 'bab5'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200 uppercase tracking-wide">Bab 5</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Ketentuan Penerbitan Sertifikat Kelulusan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Standar mutu jaminan kompetensi kelulusan siswa dan validitas sertifikasi digital ber-QR Code.</p>
            </div>

            <!-- Callout Peringatan Resmi -->
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl space-y-1.5">
                <div class="flex items-center gap-2 text-amber-800 font-bold text-xs uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px]">verified</span>
                    Aturan Resmi Pembukaan Sertifikat Kelulusan
                </div>
                <p class="text-xs text-slate-800 leading-relaxed font-medium">
                    Sertifikat Kelulusan HANYA AKAN TERBUKA dan diterbitkan oleh sistem jika memenuhi <strong>DUA SYARAT MUTLAK</strong> secara bersamaan:
                </p>
                <ol class="list-decimal list-inside text-xs text-slate-700 space-y-1 pt-1 leading-relaxed">
                    <li>Siswa telah menonton dan menyelesaikan <strong>100% video materi</strong> dalam kursus tersebut.</li>
                    <li>Siswa telah mengerjakan dan <strong>DINYATAKAN LULUS</strong> pada seluruh kuis evaluasi materi dan kuis kursus.</li>
                </ol>
            </div>

            <!-- 5.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.1 Dua Syarat Mutlak Penerbitan Sertifikat</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Sistem secara cerdas memeriksa kedua pilar kompetensi: kehadiran/penyelesaian materi video dan pemahaman akademis melalui kuis. Bila terdapat satu kuis saja yang belum lulus atau satu video yang terlewat, sertifikat tetap terkunci.
                </p>
            </div>

            <!-- 5.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.2 Checklist Transparan pada Silabus Kelas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                        <strong class="text-slate-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-slate-500">lock</span> Kondisi Terkunci (Locked State)
                        </strong>
                        <p class="text-slate-600 text-[11px] mt-1 leading-relaxed">Kartu menampilkan status "TERKUNCI" dengan rincian interaktif: sisa video belum selesai dan kuis belum lulus.</p>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200">
                        <strong class="text-emerald-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">lock_open</span> Kondisi Terbuka (Passed State)
                        </strong>
                        <p class="text-emerald-700 text-[11px] mt-1 leading-relaxed">Kartu seketika beralih menjadi hijau dengan lencana "LULUS" dan tombol aktif "Lihat & Cetak Sertifikat Kelulusan".</p>
                    </div>
                </div>
            </div>

            <!-- 5.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.3 Standar Keamanan & Verifikasi Sertifikat Resmi</h3>
                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1.5 leading-relaxed">
                    <li><strong>Nomor Registrasi Unik:</strong> Kode unik acak permanen (contoh: <code class="font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">CERT-92HP3CFPLY</code>) yang tercatat di database pusat.</li>
                    <li><strong>QR Code Verifikasi Publik:</strong> Dapat dipindai oleh pihak kampus atau recruiter kerja untuk memvalidasi keaslian dokumen secara instan tanpa perlu akun login.</li>
                    <li><strong>Format Cetak Berkualitas Tinggi:</strong> Layout siap cetak dengan tata letak proporsional dan logo sekolah resmi.</li>
                </ul>
            </div>
        </div>

        <!-- ==================== BAB 6 ==================== -->
        <div x-show="activeChapter === 'bab6'" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 uppercase tracking-wide">Bab 6</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1.5">Tanya Jawab Umum (FAQ) & Solusi Kendala Teknis</h2>
                <p class="text-xs text-slate-500 mt-0.5">Panduan penanganan masalah umum seputar pemutar video, verifikasi sertifikat, dan akun login.</p>
            </div>

            <div class="space-y-4 text-xs">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">help</span>
                        Q1: Mengapa video YouTube di ruang kelas tidak dapat diputar?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Pastikan video YouTube yang diimpor tidak disetel ke mode "Private" (Pribadi). Video harus berstatus "Public" atau "Unlisted". Selain itu, di YouTube Studio pengunggah video, pastikan opsi <em>Allow Embedding</em> (Izinkan Penyematan) dalam keadaan dicentang.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">help</span>
                        Q2: Progres video saya sudah 100%, mengapa sertifikat belum terbuka?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Periksa kartu "Syarat Sertifikat" di sidebar silabus kanan. Kemungkinan besar kursus tersebut memiliki soal kuis evaluasi materi yang belum dikerjakan atau perolehan nilai Anda masih di bawah batas kelulusan (*passing grade*). Buka tab Kuis dan kerjakan hingga memperoleh status Lulus.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">help</span>
                        Q3: Apakah guru bisa menambahkan kuis checkpoint di tengah kursus yang sedang berjalan?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Ya. Guru dapat menambahkan atau memperbarui soal kuis kapan saja. Sistem secara otomatis memperbarui kalkulasi kelayakan sertifikat bagi seluruh siswa aktif yang belum mengklaim kelulusan.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">help</span>
                        Q4: Bagaimana cara memverifikasi keabsahan sertifikat siswa oleh pihak ketiga?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Cukup buka kamera ponsel dan pindai QR Code di lembar sertifikat, atau kunjungi tautan publik <code class="font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">https://domain-lms/sertifikat/{kode}</code>. Halaman validasi publik akan menampilkan keaslian sertifikat tanpa memerlukan autentikasi login.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">help</span>
                        Q5: Bagaimana jika siswa lupa kata sandi akunnya?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Hubungi Admin Sekolah. Admin sekolah dapat membuka menu "Siswa / Mahasiswa" dan mengklik tombol "Reset Password" pada baris nama siswa terkait untuk menetapkan kata sandi baru.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 2: SPESIFIKASI TEKNIS & API DEVELOPER -->
    <div x-show="activeTab === 'dev'" class="space-y-6">
        <!-- Section 1: Algoritma Ekstraksi Video ID -->
        <section class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[20px] text-emerald-600">terminal</span>
                <span>1. Algoritma Ekstraksi YouTube Video ID</span>
            </h2>
            <p class="text-xs text-slate-600 leading-relaxed">
                Sistem LMS SIAGA BUMI-SSK menggunakan pola regex multi-format untuk mengekstrak 11 karakter unik ID video YouTube dari berbagai bentuk tautan URL yang ditempelkan pengguna:
            </p>

            <div class="bg-slate-900 text-emerald-400 p-4 rounded-xl font-mono text-xs overflow-x-auto border border-slate-800">
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
        <section class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[20px] text-emerald-600">play_circle</span>
                <span>2. Arsitektur Iframe Nocookie & Timestamp Seeking</span>
            </h2>
            <p class="text-xs text-slate-600 leading-relaxed">
                Untuk menjaga kepatuhan privasi dan interaktivitas tingkat tinggi, pemutar video disematkan dengan domain <code class="font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 font-semibold">https://www.youtube-nocookie.com/embed/{video_id}?autoplay=1&enablejsapi=1&rel=0</code>.
                Fitur lompatan menit (timestamp seek) pada tab Catatan Siswa bekerja dengan mengirimkan pesan event <code class="font-mono text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200 font-semibold">postMessage</code>:
            </p>

            <div class="bg-slate-900 text-sky-300 p-4 rounded-xl font-mono text-xs overflow-x-auto border border-slate-800">
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
        <section class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 space-y-4">
            <h2 class="text-base font-bold text-slate-900 flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[20px] text-emerald-600">api</span>
                <span>3. Direktori Endpoint REST / AJAX LMS</span>
            </h2>

            <div class="overflow-x-auto rounded-xl border border-slate-200 text-xs">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-bold text-slate-600 uppercase tracking-wider">
                            <th class="py-3 px-4">Metode</th>
                            <th class="py-3 px-4">Path Endpoint</th>
                            <th class="py-3 px-4">Deskripsi & Fungsi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-emerald-700">POST</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/admin/youtube/parse</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Ekstraksi instan ID & thumbnail dari URL YouTube</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-emerald-700">POST</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/admin/youtube/import-playlist</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Sinkronisasi batch playlist YouTube ke dalam kursus</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-emerald-700">POST</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/pelajaran/{id}/toggle-complete</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Tandai selesai atau batal selesai pelajaran siswa</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-emerald-700">POST</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/pelajaran/{id}/notes</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Simpan catatan timestamp siswa ke database</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-rose-700">DELETE</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/notes/{id}</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Hapus catatan pribadi siswa</td>
                        </tr>
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="py-3 px-4 font-bold text-emerald-700">POST</td>
                            <td class="py-3 px-4 text-slate-900 font-semibold">/kursus/{slug}/toggle-bookmark</td>
                            <td class="py-3 px-4 font-sans text-slate-600">Simpan kursus ke penanda (bookmark) siswa</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
