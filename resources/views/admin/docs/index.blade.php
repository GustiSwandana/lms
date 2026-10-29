@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 max-w-5xl" x-data="{ activeTab: 'manual', activeChapter: 'bab2' }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Panduan Penggunaan Aplikasi & Dokumentasi Sistem</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-200">Edisi 2026</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Buku panduan operasional resmi untuk Siswa, Guru, dan Administrator, dilengkapi Dokumentasi API & Integrasi YouTube serta spesifikasi teknis.</p>
        </div>

        <!-- Tombol Aksi Unduh DOCX -->
        <div class="flex items-center gap-2">
            <a href="{{ asset('docs/Panduan_Penggunaan_EduTube_LMS.docx') }}" 
               download="Panduan_Penggunaan_EduTube_LMS.docx"
               class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs hover:shadow transition-all">
                <span class="material-symbols-outlined text-[18px]">description</span>
                <span>Unduh Buku Panduan (.DOCX)</span>
                <span class="bg-indigo-500/50 text-[10px] px-1.5 py-0.5 rounded font-normal">2.2 MB</span>
            </a>
        </div>
    </div>

    <!-- Navigation Tabs Utama -->
    <div class="flex border-b border-slate-200 gap-6 text-sm">
        <button type="button" 
                @click="activeTab = 'manual'" 
                :class="activeTab === 'manual' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3 font-medium transition-colors'">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                Buku Panduan Pengguna (User Manual)
            </span>
        </button>
        <button type="button" 
                @click="activeTab = 'dev'" 
                :class="activeTab === 'dev' ? 'border-indigo-600 text-indigo-600 font-bold border-b-2 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3 font-medium transition-colors'">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">terminal</span>
                Spesifikasi Teknis & API Developer
            </span>
        </button>
    </div>

    <!-- TAB 1: BUKU PANDUAN PENGGUNA LENGKAP -->
    <div x-show="activeTab === 'manual'" class="space-y-6">
        <!-- Navigasi Bab Cepat -->
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
            <button type="button" @click="activeChapter = 'bab1'" 
                    :class="activeChapter === 'bab1' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 1</div>
                <div class="text-xs font-bold truncate">Pendahuluan</div>
            </button>
            <button type="button" @click="activeChapter = 'bab2'" 
                    :class="activeChapter === 'bab2' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 2</div>
                <div class="text-xs font-bold truncate">Panduan Siswa</div>
            </button>
            <button type="button" @click="activeChapter = 'bab3'" 
                    :class="activeChapter === 'bab3' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 3</div>
                <div class="text-xs font-bold truncate">Admin & Guru</div>
            </button>
            <button type="button" @click="activeChapter = 'bab4'" 
                    :class="activeChapter === 'bab4' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 4</div>
                <div class="text-xs font-bold truncate">Super Admin</div>
            </button>
            <button type="button" @click="activeChapter = 'bab5'" 
                    :class="activeChapter === 'bab5' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 5</div>
                <div class="text-xs font-bold truncate">Syarat Sertifikat</div>
            </button>
            <button type="button" @click="activeChapter = 'bab6'" 
                    :class="activeChapter === 'bab6' ? 'bg-indigo-600 text-white shadow-xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50'"
                    class="p-2.5 rounded-xl text-left transition-all">
                <div class="text-[10px] font-semibold uppercase tracking-wider opacity-75">Bab 6</div>
                <div class="text-xs font-bold truncate">FAQ & Solusi</div>
            </button>
        </div>

        <!-- ==================== BAB 1 ==================== -->
        <div x-show="activeChapter === 'bab1'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wide">Bab 1</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Pendahuluan & Gambaran Umum Sistem</h2>
                <p class="text-xs text-slate-500 mt-0.5">Visi platform, keunggulan kurasi video YouTube bebas iklan, alur kerja, dan pembagian hak akses (RBAC).</p>
            </div>

            <!-- 1.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-indigo-700">1.1 Visi & Latar Belakang EduTube LMS</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    EduTube LMS diciptakan untuk mengubah potensi tak terbatas konten pembelajaran di YouTube menjadi kurikulum akademik bertingkat yang bebas distraksi iklan, bebas rekomendasi video acak, dan terukur secara akademis melalui pos pemeriksaan kuis (*checkpoint*).
                </p>
            </div>

            <!-- 1.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-indigo-700">1.2 Keunggulan Utama Platform</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">bolt</span> Hemat Bandwidth Server
                        </span>
                        <p class="text-slate-600 text-[11px]">Memanfaatkan CDN global YouTube sehingga sekolah tidak terbebani biaya penyimpanan dan streaming video berskala besar.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">block</span> Bebas Distraksi Iklan
                        </span>
                        <p class="text-slate-600 text-[11px]">Pemutar video disematkan dengan parameter ketat yang meniadakan jeda iklan komersial dan rekomendasi video pihak ketiga.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-sky-600">quiz</span> Checkpoint Kuis Bertingkat
                        </span>
                        <p class="text-slate-600 text-[11px]">Materi pelajaran disertai kuis evaluasi pemahaman dengan ambang kelulusan (*passing grade*) dan pembahasan langsung.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                        <span class="font-bold text-slate-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-amber-600">verified</span> Sertifikasi Digital QR Code
                        </span>
                        <p class="text-slate-600 text-[11px]">Sertifikat resmi terbit otomatis jika siswa tuntas 100% video dan lulus seluruh kuis, lengkap dengan verifikasi publik online.</p>
                    </div>
                </div>
            </div>

            <!-- 1.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-indigo-700">1.3 Alur Kerja Pembelajaran Terpadu</h3>
                <ol class="list-decimal list-inside text-xs text-slate-600 space-y-1.5">
                    <li><strong>Konfigurasi Lembaga (Super Admin):</strong> Pendaftaran sekolah mitra dan penetapan kategori ilmu.</li>
                    <li><strong>Penyusunan Kurikulum (Guru):</strong> Membuat kursus, mengimpor video YouTube, menyusun kuis, dan melampirkan berkas PDF/praktikum.</li>
                    <li><strong>Pembelajaran Aktif (Siswa):</strong> Menonton materi, membuat catatan timestamped, berdiskusi, dan mengerjakan kuis evaluasi.</li>
                    <li><strong>Validasi & Kelulusan (Sistem):</strong> Sistem memvalidasi syarat 100% video + kelulusan kuis untuk membuka sertifikat digital.</li>
                </ol>
            </div>

            <!-- 1.4 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-indigo-700">1.4 Matriks Hak Akses & Peran Pengguna (RBAC Matrix)</h3>
                <div class="overflow-x-auto rounded-lg border border-slate-200 text-xs">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-600 uppercase">
                            <tr>
                                <th class="p-3">Peran (Role)</th>
                                <th class="p-3">Cakupan Akses</th>
                                <th class="p-3">Wewenang Utama</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <td class="p-3 font-bold text-indigo-700">Super Admin</td>
                                <td class="p-3 text-slate-600">Seluruh Platform & Multi-Sekolah</td>
                                <td class="p-3 text-slate-600">Kelola sekolah mitra, manajemen user global, master kategori, konfigurasi platform, helpdesk pusat.</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-sky-700">Admin Sekolah / Guru</td>
                                <td class="p-3 text-slate-600">Lembaga Sekolah Masing-Masing</td>
                                <td class="p-3 text-slate-600">Course Builder, impor video YouTube otomatis, manajemen modul & kuis, registrasi siswa via CSV.</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-bold text-emerald-700">Siswa (Student)</td>
                                <td class="p-3 text-slate-600">Ruang Belajar Mandiri</td>
                                <td class="p-3 text-slate-600">Katalog kursus, pemutar video interaktif, catatan timestamped, diskusi, kuis evaluasi, klaim sertifikat.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ==================== BAB 2 ==================== -->
        <div x-show="activeChapter === 'bab2'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-700 uppercase tracking-wide">Bab 2</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Panduan Penggunaan untuk Siswa (Learner Journey)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tata cara login, eksplorasi katalog kursus, ruang belajar classroom, kuis, dan klaim sertifikat digital.</p>
            </div>

            <!-- 2.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.1</span>
                    Akses Masuk (Login) & Pengenalan Dasbor Siswa
                </h3>
                <p class="text-xs text-slate-600">
                    Siswa mengakses URL portal LMS sekolah (contoh: <code class="font-mono text-indigo-600">https://demo.gustiswandana.space/login</code>) menggunakan email dan kata sandi yang telah didaftarkan. Pada dasbor utama, siswa disajikan ringkasan statistik: total kursus diikuti, kursus tuntas, akumulasi menit belajar, dan sertifikat kelulusan.
                </p>
            </div>

            <!-- 2.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.2</span>
                    Eksplorasi Katalog Kursus & Fitur Pencarian Cerdas
                </h3>
                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1">
                    <li><strong>Pencarian Real-Time:</strong> Ketik kata kunci topik, bahasa pemrograman, atau nama pengajar pada kotak pencarian atas.</li>
                    <li><strong>Penyaringan Status:</strong> Gunakan tab "Semua", "Sedang Berjalan", "Selesai", dan "Tersimpan (Bookmark)" untuk mengelompokkan materi.</li>
                    <li><strong>Kartu Kursus:</strong> Memuat nama pengajar, durasi waktu, tingkat kesulitan, progres belajar, serta tombol langsung "Mulai Belajar" atau "Lanjutkan Belajar".</li>
                </ul>
            </div>

            <!-- 2.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.3</span>
                    Ruang Belajar & Pemutar Video Interaktif (Classroom)
                </h3>
                <p class="text-xs text-slate-600">
                    Di ruang belajar, layar dibagi menjadi dua area kerja:
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Area Kiri: Video & Navigasi</strong>
                        <p class="text-slate-600 text-[11px] mt-1">Pemutar video YouTube bebas iklan, kontrol playback (0.5x - 2.0x), tombol "Tandai Selesai", tombol materi sebelumnya/berikutnya, serta hitung mundur 5 detik auto-next materi.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Area Kanan: Silabus & Syarat Sertifikat</strong>
                        <p class="text-slate-600 text-[11px] mt-1">Daftar bab/modul lengkap dengan centang hijau pada materi selesai, serta kartu dinamis syarat penerbitan sertifikat.</p>
                    </div>
                </div>
            </div>

            <!-- 2.4 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.4</span>
                    Fitur Produktivitas: Catatan Berwaktu, Diskusi, & Sumber Daya Modul
                </h3>
                <div class="space-y-2 text-xs text-slate-600">
                    <div class="p-3 bg-indigo-50/50 rounded-lg border border-indigo-100">
                        <strong class="text-indigo-900">Catatan Berwaktu (Timestamped Notes):</strong>
                        <p class="text-[11px] text-slate-600 mt-0.5">Ketik rangkuman materi saat video berjalan. Detik video otomatis tersemat pada catatan. Mengklik tombol waktu di catatan akan langsung memutar video ke detik tersebut.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Forum Diskusi Materi:</strong>
                        <p class="text-[11px] text-slate-600 mt-0.5">Tanyakan kesulitan materi pada tab Diskusi untuk dijawab oleh guru pengampu dan sesama rekan siswa.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Lampiran Sumber Daya (Lesson Resources):</strong>
                        <p class="text-[11px] text-slate-600 mt-0.5">Unduh berkas pendukung praktikum seperti dokumen PDF modul, slide presentasi, atau tautan repositori GitHub.</p>
                    </div>
                </div>
            </div>

            <!-- 2.5 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.5</span>
                    Pengerjaan Kuis Checkpoint & Evaluasi Hasil Ujian
                </h3>
                <ol class="list-decimal list-inside text-xs text-slate-600 space-y-1">
                    <li>Klik tab "Kuis Evaluasi" pada materi yang memiliki penugasan kuis.</li>
                    <li>Jawab butir pertanyaan pilihan ganda yang disajikan.</li>
                    <li>Klik tombol "Kirim Jawaban Kuis". Sistem secara otomatis menghitung skor perolehan Anda.</li>
                    <li>Siswa dinyatakan LULUS bila nilai melebihi ambang batas (*passing grade*, standar 75 atau 80). Bila belum lulus, siswa dapat meninjau pembahasan dan mengulang kuis.</li>
                </ol>
            </div>

            <!-- 2.6 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs">2.6</span>
                    Klaim, Unduh, dan Cetak Sertifikat Digital
                </h3>
                <p class="text-xs text-slate-600">
                    Saat video mencapai 100% dan seluruh kuis lulus, kartu sertifikat di silabus beralih menjadi hijau dengan tombol "Lihat & Cetak Sertifikat". Siswa dapat mencetak sertifikat digital beresolusi tinggi yang dilengkapi Nomor Registrasi Unik dan QR Code verifikasi online.
                </p>
            </div>
        </div>

        <!-- ==================== BAB 3 ==================== -->
        <div x-show="activeChapter === 'bab3'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-sky-50 text-sky-700 uppercase tracking-wide">Bab 3</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Panduan Admin Sekolah & Instruktur (Teacher Journey)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manajemen kursus, Course Builder, impor video YouTube cepat, kuis evaluasi, dan impor siswa massal.</p>
            </div>

            <!-- 3.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.1</span>
                    Dasbor Admin Sekolah & Ringkasan Metrik Pembelajaran
                </h3>
                <p class="text-xs text-slate-600">
                    Menyajikan ringkasan kesehatan sistem sekolah: Total Video Pembelajaran Aktif, Jumlah Kursus Draf & Publikasi, Akumulasi Jam Waktu Tonton Siswa, dan status konektivitas YouTube Service API.
                </p>
            </div>

            <!-- 3.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.2</span>
                    Manajemen Kursus & Pembuatan Kursus Baru
                </h3>
                <p class="text-xs text-slate-600">
                    Buka menu "Manajer Kursus", klik "+ Buat Kursus Baru". Masukkan Judul Kursus, Deskripsi Lengkap, Kategori Keilmuan, Tingkat Kesulitan (Pemula / Menengah / Mahir), dan unggah Thumbnail sampul rasio 16:9 (1280x720).
                </p>
            </div>

            <!-- 3.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.3</span>
                    Menambahkan Materi Video dengan Impor YouTube Cepat
                </h3>
                <div class="p-3 bg-sky-50/50 rounded-lg border border-sky-100 text-xs space-y-1">
                    <p class="font-bold text-slate-900">Langkah Impor:</p>
                    <ol class="list-decimal list-inside text-slate-600 space-y-1 text-[11px]">
                        <li>Pilih Kursus dan Modul target pembelajaran.</li>
                        <li>Tempel tautan YouTube (mendukung format youtube.com, youtu.be, maupun YouTube Shorts).</li>
                        <li>Klik <strong>Ambil Otomatis</strong>: Sistem mengekstrak Video ID, thumbnail resolusi tinggi, durasi, dan judul materi secara instan.</li>
                        <li>Tulis catatan deskripsi materi, lalu pilih status "Publikasikan" atau "Simpan Draf".</li>
                    </ol>
                </div>
            </div>

            <!-- 3.4 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.4</span>
                    Penyusun Kurikulum (Single-Page Course Builder)
                </h3>
                <p class="text-xs text-slate-600">
                    Pada menu Kurikulum (<code class="font-mono text-indigo-600">/admin/curriculum</code>), instruktur dapat menyusun modul pembelajaran bertingkat, mengurutkan pelajaran dari tingkat pengantar hingga proyek akhir, serta meninjau akumulasi durasi waktu belajar per bab.
                </p>
            </div>

            <!-- 3.5 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.5</span>
                    Pembuatan Kuis Evaluasi, Bank Soal, & Batas Nilai Kelulusan
                </h3>
                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1">
                    <li><strong>Passing Grade:</strong> Tentukan batas nilai minimal kelulusan kuis (misal: 75 atau 80).</li>
                    <li><strong>Opsi Acak (Shuffle Questions):</strong> Mengacak urutan nomor soal dan opsi jawaban bagi masing-masing siswa.</li>
                    <li><strong>Bank Soal:</strong> Tambahkan teks pertanyaan, pilihan jawaban A, B, C, D, centang kunci jawaban benar, dan tuliskan teks pembahasan soal.</li>
                </ul>
            </div>

            <!-- 3.6 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.6</span>
                    Manajemen Berkas Sumber Daya & Lampiran Modul (Lesson Resources)
                </h3>
                <p class="text-xs text-slate-600">
                    Sematkan dokumen panduan PDF, repositori starter kit GitHub, atau file latihan praktikum pada masing-masing materi melalui tombol "+ Tambah Lampiran".
                </p>
            </div>

            <!-- 3.7 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.7</span>
                    Administrasi Siswa: Pendaftaran Manual, Impor CSV, & Ekspor Data
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Impor Massal via CSV:</strong>
                        <p class="text-slate-600 text-[11px] mt-1">Unduh template CSV, isi nama, email, dan password siswa satu angkatan, lalu unggah kembali berkas CSV untuk mendaftarkan ratusan siswa dalam hitungan detik.</p>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900">Ekspor Data & Reset Password:</strong>
                        <p class="text-slate-600 text-[11px] mt-1">Ekspor seluruh riwayat siswa ke format lembar kerja Excel/CSV, serta tombol reset kata sandi instan bila siswa lupa password.</p>
                    </div>
                </div>
            </div>

            <!-- 3.8 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-sky-100 text-sky-700 flex items-center justify-center text-xs">3.8</span>
                    Pusat Dukungan Sekolah & Pengajuan Tiket Bantuan (School Support)
                </h3>
                <p class="text-xs text-slate-600">
                    Bila menemukan kendala sistem atau permohonan fitur baru, ajukan tiket bantuan di menu "Dukungan & Log" untuk langsung ditangani oleh Super Administrator pusat.
                </p>
            </div>
        </div>

        <!-- ==================== BAB 4 ==================== -->
        <div x-show="activeChapter === 'bab4'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-700 uppercase tracking-wide">Bab 4</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Panduan untuk Super Administrator (Platform Management)</h2>
                <p class="text-xs text-slate-500 mt-0.5">Multi-sekolah mitra, administrasi pengguna global, master kategori, konfigurasi platform, dan helpdesk pusat.</p>
            </div>

            <!-- 4.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.1</span>
                    Dasbor Pengawasan Global & Metrik Ekosistem Multi-Sekolah
                </h3>
                <p class="text-xs text-slate-600">
                    Menyajikan metrik agregat: Total Sekolah Mitra Terdaftar, Jumlah Pengguna Aktif Seluruh Lembaga, Total Jam Belajar Komulatif, dan Antrean Tiket Bantuan yang menanti tanggapan.
                </p>
            </div>

            <!-- 4.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.2</span>
                    Manajemen Master Sekolah Mitra (Multi-Tenancy)
                </h3>
                <p class="text-xs text-slate-600">
                    Pada menu Master Sekolah (<code class="font-mono text-indigo-600">/admin/schools</code>), Super Admin mendaftarkan institusi sekolah baru, mencatat NPSN, alamat, kontak penanggung jawab, logo lembaga, serta mengatur status keaktifan sekolah (Aktif atau Ditangguhkan).
                </p>
            </div>

            <!-- 4.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.3</span>
                    Manajemen Pengguna Global & Penetapan Hak Akses (User Management)
                </h3>
                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1">
                    <li>Membuat akun Admin Sekolah baru dan menautkannya dengan institusi sekolah masing-masing.</li>
                    <li>Mengatur penugasan hak akses (*Role Assignment*).</li>
                    <li>Mengaktifkan / menonaktifkan status akun pengguna secara global.</li>
                    <li>Mereset kata sandi akun administratif tingkat tinggi bila terjadi kehilangan akses.</li>
                </ul>
            </div>

            <!-- 4.4 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.4</span>
                    Manajemen Master Kategori Kursus
                </h3>
                <p class="text-xs text-slate-600">
                    Mengatur taksonomi keilmuan global (misalnya: Pemrograman Web, Desain UI/UX, Kecerdasan Buatan, Cloud & DevOps). Menetapkan nama kategori, slug URL, warna aksen, dan ikon Material Symbols untuk menjaga konsistensi katalog di seluruh sekolah.
                </p>
            </div>

            <!-- 4.5 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.5</span>
                    Pengaturan Konfigurasi Platform Global
                </h3>
                <p class="text-xs text-slate-600">
                    Pada menu Pengaturan Sistem (<code class="font-mono text-indigo-600">/admin/settings</code>), Super Admin mengontrol parameter aplikasi seperti: Nama Platform, Logo Resmi, Batas Waktu Kedaluwarsa Sesi (*Session Lifetime*), dan Kontak Helpdesk Resmi.
                </p>
            </div>

            <!-- 4.6 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-6 h-6 rounded bg-purple-100 text-purple-700 flex items-center justify-center text-xs">4.6</span>
                    Helpdesk Pusat, Penanganan Tiket, & Diagnostik Sistem Server
                </h3>
                <p class="text-xs text-slate-600">
                    Merespons tiket pengaduan dari seluruh administrator sekolah mitra, serta meninjau laporan diagnostik server (versi PHP, versi framework Laravel, driver database, dan konektivitas YouTube Player API).
                </p>
            </div>
        </div>

        <!-- ==================== BAB 5 ==================== -->
        <div x-show="activeChapter === 'bab5'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-700 uppercase tracking-wide">Bab 5</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Ketentuan Penerbitan Sertifikat Kelulusan</h2>
                <p class="text-xs text-slate-500 mt-0.5">Standar mutu jaminan kompetensi kelulusan siswa dan validitas sertifikasi digital ber-QR Code.</p>
            </div>

            <!-- Callout Peringatan Resmi -->
            <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl space-y-1">
                <div class="flex items-center gap-2 text-amber-900 font-bold text-xs uppercase tracking-wider">
                    <span class="material-symbols-outlined text-[18px] text-amber-600">verified</span>
                    Aturan Resmi Pembukaan Sertifikat Kelulusan
                </div>
                <p class="text-xs text-amber-900 leading-relaxed font-medium">
                    Sertifikat Kelulusan HANYA AKAN TERBUKA dan diterbitkan oleh sistem jika memenuhi <strong>DUA SYARAT MUTLAK</strong> secara bersamaan:
                </p>
                <ol class="list-decimal list-inside text-xs text-amber-800 space-y-0.5 pt-1">
                    <li>Siswa telah menonton dan menyelesaikan <strong>100% video materi</strong> dalam kursus tersebut.</li>
                    <li>Siswa telah mengerjakan dan <strong>DINYATAKAN LULUS</strong> pada seluruh kuis evaluasi materi dan kuis kursus.</li>
                </ol>
            </div>

            <!-- 5.1 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.1 Dua Syarat Mutlak Penerbitan Sertifikat</h3>
                <p class="text-xs text-slate-600">
                    Sistem secara cerdas memeriksa kedua pilar kompetensi: kehadiran/penyelesaian materi video dan pemahaman akademis melalui kuis. Bila terdapat satu kuis saja yang belum lulus atau satu video yang terlewat, sertifikat tetap terkunci.
                </p>
            </div>

            <!-- 5.2 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.2 Checklist Transparan pada Silabus Kelas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                        <strong class="text-slate-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span> Kondisi Terkunci (Locked State)
                        </strong>
                        <p class="text-slate-600 text-[11px] mt-1">Kartu menampilkan status "TERKUNCI" dengan rincian interaktif: sisa video belum selesai (contoh: 4/7 Selesai) dan kuis belum lulus (contoh: 0/1 Kuis Lulus).</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-lg border border-emerald-200">
                        <strong class="text-emerald-900 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-emerald-600">lock_open</span> Kondisi Terbuka (Passed State)
                        </strong>
                        <p class="text-emerald-800 text-[11px] mt-1">Kartu seketika beralih menjadi hijau dengan lencana "LULUS" dan tombol aktif "Lihat & Cetak Sertifikat Kelulusan" disertai modal perayaan kelulusan.</p>
                    </div>
                </div>
            </div>

            <!-- 5.3 -->
            <div class="space-y-2">
                <h3 class="text-sm font-bold text-slate-900">5.3 Standar Keamanan & Verifikasi Sertifikat Resmi</h3>
                <ul class="list-disc list-inside text-xs text-slate-600 space-y-1">
                    <li><strong>Nomor Registrasi Unik:</strong> Kode unik acak permanen (contoh: <code class="font-mono text-indigo-600">CERT-92HP3CFPLY</code>) yang tercatat di database pusat.</li>
                    <li><strong>QR Code Verifikasi Publik:</strong> Dapat dipindai oleh pihak kampus atau recruiter kerja untuk memvalidasi keaslian dokumen secara instan tanpa perlu akun login.</li>
                    <li><strong>Format Cetak Berkualitas Tinggi:</strong> Layout siap cetak dengan tata letak proporsional dan logo sekolah resmi.</li>
                </ul>
            </div>
        </div>

        <!-- ==================== BAB 6 ==================== -->
        <div x-show="activeChapter === 'bab6'" class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-6">
            <div class="border-b border-slate-100 pb-4">
                <span class="px-2.5 py-0.5 rounded text-[11px] font-bold bg-rose-50 text-rose-700 uppercase tracking-wide">Bab 6</span>
                <h2 class="text-lg font-bold text-slate-900 mt-1">Tanya Jawab Umum (FAQ) & Solusi Kendala Teknis</h2>
                <p class="text-xs text-slate-500 mt-0.5">Panduan penanganan masalah umum seputar pemutar video, verifikasi sertifikat, dan akun login.</p>
            </div>

            <div class="space-y-4 text-xs">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">help</span>
                        Q1: Mengapa video YouTube di ruang kelas tidak dapat diputar?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Pastikan video YouTube yang diimpor tidak disetel ke mode "Private" (Pribadi). Video harus berstatus "Public" atau "Unlisted". Selain itu, di YouTube Studio pengunggah video, pastikan opsi <em>Allow Embedding</em> (Izinkan Penyematan) dalam keadaan dicentang.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">help</span>
                        Q2: Progres video saya sudah 100%, mengapa sertifikat belum terbuka?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Periksa kartu "Syarat Sertifikat" di sidebar silabus kanan. Kemungkinan besar kursus tersebut memiliki soal kuis evaluasi materi yang belum dikerjakan atau perolehan nilai Anda masih di bawah batas kelulusan (*passing grade*). Buka tab Kuis dan kerjakan hingga memperoleh status Lulus.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">help</span>
                        Q3: Apakah guru bisa menambahkan kuis checkpoint di tengah kursus yang sedang berjalan?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Ya. Guru dapat menambahkan atau memperbarui soal kuis kapan saja. Sistem secara otomatis memperbarui kalkulasi kelayakan sertifikat bagi seluruh siswa aktif yang belum mengklaim kelulusan.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">help</span>
                        Q4: Bagaimana cara memverifikasi keabsahan sertifikat siswa oleh pihak ketiga?
                    </p>
                    <p class="text-slate-600 leading-relaxed text-[11px]">
                        <strong>Jawab:</strong> Cukup buka kamera ponsel dan pindai QR Code di lembar sertifikat, atau kunjungi tautan publik <code class="font-mono text-indigo-600">https://domain-lms/sertifikat/{kode}</code>. Halaman validasi publik akan menampilkan keaslian sertifikat tanpa memerlukan autentikasi login.
                    </p>
                </div>

                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
                    <p class="font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">help</span>
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
</div>
@endsection
