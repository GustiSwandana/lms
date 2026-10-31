# BUKU PANDUAN PENGGUNA (MANUAL BOOK)
# PLATFORM LMS "SIAGA BUMI-SSK"
### Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan
**BKKBN Perwakilan Provinsi Sulawesi Utara**  
*Bekerja sama dengan Universitas Negeri Manado (UNIMA), Universitas Pembangunan Indonesia (UNPI), dan Institut Pemerintahan Dalam Negeri (IPDN) Kampus Sulawesi Utara*

---

## DAFTAR ISI
1. [PENDAHULUAN & GAMBARAN UMUM](#1-pendahuluan--gambaran-umum)
   - 1.1 Mengenai SIAGA BUMI-SSK
   - 1.2 Konsorsium Lembaga Pemrakarsa Sulawesi Utara
   - 1.3 Tiga Pilar Kurikulum Strategis
   - 1.4 Sekolah Mitra Percontohan (*Pilot Schools*)
   - 1.5 Matriks Hak Akses Pengguna (*Role-Based Access Control / RBAC*)
2. [AKSES & AUTENTIKASI PENGGUNA](#2-akses--autentikasi-pengguna)
   - 2.1 Pendaftaran Mandiri Siswa Baru (`/register`)
   - 2.2 Masuk ke Akun Pembelajaran (`/login`)
   - 2.3 Fitur Intip Kata Sandi (*Password Eye Toggle*)
   - 2.4 Daftar Kredensial Pengujian & Akun Resmi
   - 2.5 Lupa Kata Sandi & Prosedur Bantuan Akses
3. [KURIKULUM & SILABUS MATERI RIIL APLIKASI](#3-kurikulum--silabus-materi-riil-aplikasi)
   - 3.1 Peta Kurikulum & Metrik Data Riil
   - 3.2 Kursus 1: Mengenai BKKBN (Pilar Kependudukan)
   - 3.3 Kursus 2: Apa sih SSK? (Pilar Lingkungan)
   - 3.4 Kursus 3: Pencegahan & Penanganan Kekerasan Seksual / PPKS (Pilar Perlindungan Remaja)
   - 3.5 Matriks Evaluasi Kuis Checkpoint & Ambang Kelulusan
4. [PANDUAN SISWA PEMBELAJAR (`siswa`)](#4-panduan-siswa-pembelajar-siswa)
   - 4.1 Dasbor Pembelajaran & Satu Aksi Utama
   - 4.2 Teater Belajar & Pemutar Video HD Bebas Iklan (*Classroom*)
   - 4.3 Live Watch Completion Tracker (Ambang 90% Durasi Video)
   - 4.4 Catatan Waktu Interaktif (*Timestamp Notes*) & Pintasan `Ctrl + Enter`
   - 4.5 Forum Diskusi Materi Antarsiswa & Guru
   - 4.6 Mengerjakan Kuis Checkpoint & Umpan Balik Ramah (Passing Grade 70%)
   - 4.7 Klaim dan Cetak E-Sertifikat Kelulusan
5. [PANDUAN GURU / PENDIDIK (`guru`)](#5-panduan-guru--pendidik-guru)
   - 5.1 Mengakses Ruang Pendidik
   - 5.2 Alur Kerja Unified Course Builder
   - 5.3 Menambahkan Modul & Ingest Video YouTube Otomatis
   - 5.4 Merancang Kuis Checkpoint, Kunci Jawaban & Pembahasan Pedagogis
   - 5.5 Memantau Perkembangan & Nilai Evaluasi Siswa
   - 5.6 Batasan Keamanan Hak Akses Guru
6. [PANDUAN OPERATOR SEKOLAH (`operator_sekolah`)](#6-panduan-operator-sekolah-operator_sekolah)
   - 6.1 Dasbor Sekolah & Statistik Satuan Pendidikan
   - 6.2 Pendaftaran Siswa Baru Secara Satuan
   - 6.3 Pendaftaran Masal Siswa Menggunakan Template CSV
   - 6.4 Mengatur Ulang (*Reset*) Kata Sandi Akun Siswa Menjadi `password`
   - 6.5 Memverifikasi Status Kelulusan Siswa Per Kelas
7. [PANDUAN ADMIN PUSAT (`admin_pusat` / `super_admin`)](#7-panduan-admin-pusat-admin_pusat--super_admin)
   - 7.1 Dasbor Analitik Tingkat Provinsi BKKBN
   - 7.2 Manajemen Master Data Sekolah Mitra
   - 7.3 Manajemen Akun Operator & Pengguna Lintas Sekolah
   - 7.4 Kurasi Visibilitas Akses Mode Galeri untuk Tamu (`is_visible_to_guest`)
   - 7.5 Pemeriksaan Kesehatan Tautan Video YouTube (*Video Health Check*)
   - 7.6 Pengaturan Kebijakan Sistem Global
   - 7.7 Manajemen Tiket Bantuan & Pengaduan Teknis
8. [PANDUAN PENYULUH & TAMU (`penyuluh` - MODE GALERI)](#8-panduan-penyuluh--tamu-penyuluh---mode-galeri)
   - 8.1 Karakteristik Akses Mode Galeri (*View-Only*)
   - 8.2 Sarana Penyuluhan Lapangan PIK-R di Desa/Kelurahan
   - 8.3 Batasan Hak Akses Mode Galeri
9. [SISTEM SERTIFIKASI DIGITAL & VERIFIKASI QR CODE](#9-sistem-sertifikasi-digital--verifikasi-qr-code)
   - 9.1 Tiga Syarat Mutlak Penerbitan Sertifikat
   - 9.2 Komponen Pengamanan Sertifikat Digital
   - 9.3 Laman Verifikasi Publik Ber-QR Code (`/sertifikat/{kode}`)
   - 9.4 Data Sertifikat Riil yang Telah Terbit di Database
10. [PUSAT BANTUAN & TANYA JAWAB (FAQ)](#10-pusat-bantuan--tanya-jawab-faq)

---

## 1. PENDAHULUAN & GAMBARAN UMUM

### 1.1 Mengenai SIAGA BUMI-SSK
**SIAGA BUMI-SSK (Sekolah Siaga Kependudukan)** adalah platform manajemen pembelajaran digital (*Learning Management System / LMS*) yang dirancang khusus untuk mengintegrasikan pendidikan wawasan kependudukan, pelestarian lingkungan hidup, serta pencegahan dan penanganan kekerasan seksual ke dalam jenjang pendidikan sekolah menengah.

Platform ini mengadopsi standar desain modern yang bersih (*Light Mode* berwibawa), tipografi jelas (*Plus Jakarta Sans* untuk antarmuka teks dan *JetBrains Mono / Courier New* untuk durasi stempel waktu), serta tata letak ramah sentuhan (*mobile-first*) dengan tombol empuk minimal 44–48px.

![Beranda & Katalog Kursus SSK](./screenshots/01-beranda-katalog.png)
*Gambar 1.1: Tampilan Beranda Utama SIAGA BUMI-SSK dengan kurasi 3 pilar kurikulum resmi, panduan belajar terarah, serta logo konsorsium BKKBN, UNIMA, UNPI, dan IPDN.*

### 1.2 Konsorsium Lembaga Pemrakarsa Sulawesi Utara
Platform ini diinisiasi oleh **BKKBN Perwakilan Provinsi Sulawesi Utara** bersama konsorsium perguruan tinggi dan lembaga kedinasan:
1. **Universitas Negeri Manado (UNIMA)** — Penyusunan kurikulum pedagogis, metodologi pengajaran muatan lokal, dan evaluasi hasil belajar kependudukan.
2. **Universitas Pembangunan Indonesia (UNPI)** — Pengembangan teknologi, infrastruktur server web, rekayasa perangkat lunak, dan keamanan sistem.
3. **Institut Pemerintahan Dalam Negeri (IPDN) Kampus Sulawesi Utara** — Wawasan kepamongprajaan, ketahanan sosial masyarakat, dan tata kelola kependudukan terpadu.
4. **BKKBN Perwakilan Sulawesi Utara** — Regulasi program Bangga Kencana, penyuluhan Generasi Berencana (GenRe), percepatan penurunan stunting, dan koordinasi Sekolah Siaga Kependudukan.

### 1.3 Tiga Pilar Kurikulum Strategis
Materi pembelajaran dalam SIAGA BUMI-SSK dikelompokkan ke dalam 3 fokus pembelajaran strategis:
- **Pilar Kependudukan (GenRe & BKKBN - Aksen Indigo `#4F46E5`):** Pengenalan peran kelembagaan BKKBN, bonus demografi, program Generasi Berencana (GenRe), 8 fungsi keluarga, dan pendewasaan usia perkawinan (PUP).
- **Pilar Lingkungan (Siaga Bumi - Aksen Emerald `#059669`):** Edukasi krisis iklim global, pelestarian flora-fauna endemik Sulawesi Utara, aksi Siaga Bumi, bank sampah sekolah, serta konservasi sumber daya air Minahasa.
- **Pilar PPKS (Perlindungan Remaja - Aksen Rose Terracotta `#E11D48`):** Pencegahan dan penanganan kekerasan seksual di lingkungan satuan pendidikan, batasan tubuh pribadi remaja (*consent*), kanal aduan ramah anak, dan pendampingan sahabat sebaya.

### 1.4 Sekolah Mitra Percontohan (*Pilot Schools*)
Sesuai arahan kelembagaan, implementasi materi pembelajaran, pendaftaran siswa, dan administrasi operasional dipusatkan pada 2 (dua) sekolah mitra resmi di Sulawesi Utara:
1. **SMP Negeri 1 Bitung**
   - NPSN: `40101511`
   - Alamat: *Jl. Sam Ratulangi No. 12, Pakadoodan, Madidir, Kota Bitung, Sulawesi Utara*
2. **Sekolah Rakyat Menengah Atas 44 Minahasa**
   - NPSN: `40104444`
   - Alamat: *Jl. Raya Tanawangko No. 44, Tombariri, Kabupaten Minahasa, Sulawesi Utara*

### 1.5 Matriks Hak Akses Pengguna (Role-Based Access Control / RBAC)
Sistem menerapkan pengamanan hak akses berbasis 5 peran terpisah secara ketat:

| Modul / Fitur Sistem | Admin Pusat (`admin_pusat`) | Operator Sekolah (`operator_sekolah`) | Guru / Pendidik (`guru`) | Siswa Pembelajar (`siswa`) | Penyuluh / Tamu (`penyuluh`) |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Katalog Kursus Publik** | Ya | Ya | Ya | Ya | Ya (Materi Terpilih) |
| **Ruang Belajar & Pemutar Video** | Ya | Ya | Ya | Ya | Ya (*View-Only*) |
| **Catatan Waktu Pribadi** | Tidak | Tidak | Tidak | Ya | Tidak |
| **Mengerjakan Kuis Checkpoint** | Tidak | Tidak | Tidak | Ya | Tidak |
| **Klaim Sertifikat Digital** | Tidak | Tidak | Tidak | Ya | Tidak |
| **Unified Course Builder** | Ya | Tidak | Ya | Tidak | Tidak |
| **Manajemen Bank Soal & Kuis** | Ya | Tidak | Ya | Tidak | Tidak |
| **Manajemen Siswa & CSV** | Ya (Semua Sekolah) | Ya (Sekolahnya) | Tidak | Tidak | Tidak |
| **Reset Kata Sandi Siswa** | Ya | Ya (Sekolahnya) | Tidak | Tidak | Tidak |
| **Master Data Sekolah Mitra** | Ya | Tidak | Tidak | Tidak | Tidak |
| **Kurasi Visibilitas Tamu** | Ya | Tidak | Tidak | Tidak | Tidak |
| **Setelan Global Platform** | Ya | Tidak | Tidak | Tidak | Tidak |

---

## 2. AKSES & AUTENTIKASI PENGGUNA

### 2.1 Pendaftaran Mandiri Siswa Baru (`/register`)
Bagi siswa yang belum memiliki akun dari sekolah, pendaftaran mandiri dapat dilakukan secara gratis dan langsung aktif:
1. Buka peramban pada ponsel atau komputer dan kunjungi tautan:  
   **`https://demo.gustiswandana.space/register`** (atau klik tombol **Daftar Siswa** di bilah navigasi atas).
2. Lengkapi isian formulir:
   - **Nama Lengkap**: Masukkan nama lengkap sesuai kartu pelajar/rapor (nama ini akan dicetak otomatis pada sertifikat).
   - **Asal Sekolah**: Pilih antara *SMP Negeri 1 Bitung* atau *Sekolah Rakyat Menengah Atas 44 Minahasa*.
   - **Alamat Domisili**: Cantumkan kota/wilayah tempat tinggal di Sulawesi Utara.
   - **Alamat Email**: Email aktif milik siswa.
   - **Nomor HP / WhatsApp**: Nomor aktif untuk keperluan pemberitahuan.
   - **Kata Sandi**: Buat kata sandi minimal 6 karakter.
   - **Konfirmasi Kata Sandi**: Ketik ulang kata sandi yang sama.
3. Klik tombol hijau **Daftar Akun Siswa**. Sistem memverifikasi data dan langsung mengarahkan Anda ke Dasbor Pembelajaran Siswa tanpa perlu menunggu persetujuan manual.

![Pendaftaran Mandiri Siswa Baru](./screenshots/03-pendaftaran-siswa.png)
*Gambar 2.1: Antarmuka formulir registrasi mandiri siswa baru lengkap dengan pemilihan sekolah percontohan resmi di Sulawesi Utara.*

### 2.2 Masuk ke Akun Pembelajaran (`/login`)
1. Buka laman: **`https://demo.gustiswandana.space/login`**.
2. Masukkan alamat email terdaftar dan kata sandi akun Anda.
3. Gunakan fitur *eye toggle* (ikon mata) di sisi kanan kotak isian untuk memeriksa ketepatan pengetikan sandi.
4. *(Opsional)* Centang kotak **Ingat sesi masuk saya** pada perangkat pribadi.
5. Klik tombol hijau **Masuk ke Akun**.
   - Jika berhasil, Anda akan dialihkan otomatis ke antarmuka peran masing-masing (Admin/Guru ke Ruang Pengelola, Siswa ke Ruang Belajar).
   - Jika kredensial keliru, sistem menampilkan pesan santun: *"Email atau kata sandi belum pas. Coba cek lagi, ya."*

![Halaman Masuk Akun Pembelajaran](./screenshots/02-halaman-masuk.png)
*Gambar 2.2: Laman masuk akun pembelajaran dengan kontrol intip kata sandi (eye toggle), tombol sentuh lega 48px, dan lencana panduan 5 peran resmi.*

### 2.3 Fitur Intip Kata Sandi (*Password Eye Toggle*)
Untuk mengantisipasi kesalahan ketik pada layar ponsel:
- Klik ikon mata (`visibility` / `visibility_off`) di sisi kanan kotak kata sandi.
- Teks sandi akan terbaca dalam teks biasa dan dapat disembunyikan kembali dengan satu kali ketukan.

### 2.4 Daftar Kredensial Pengujian & Akun Resmi
Berikut adalah daftar akun yang telah terdaftar aktif di basis data untuk keperluan pengujian dan operasional:

| Peran Akun | Alamat Email | Kata Sandi | Satuan Sekolah / Unit Penugasan |
| :--- | :--- | :--- | :--- |
| **Admin Pusat BKKBN** | `admin@edutube.org` | `password` | BKKBN Perwakilan Prov. Sulawesi Utara |
| **Admin Pusat (Cadangan)** | `adminpusat@siagabumi.id` | `password` | Koordinator Teknis Sistem Informasi |
| **Super Administrator** | `superadmin@edutube.org` | `password` | Administrator Utama Sistem |
| **Operator Sekolah** | `smkn1@edutube.org` | `password` | SMP Negeri 1 Bitung |
| **Operator Sekolah (2)** | `operator.bitung@siagabumi.id` | `password` | SMP Negeri 1 Bitung |
| **Guru / Pendidik** | `guru.minahasa@siagabumi.id` | `password` | SRMA 44 Minahasa (Pengajar SSK) |
| **Guru / Pendidik (2)** | `guru.bitung@sekolah.sch.id` | `password` | SMP Negeri 1 Bitung (Pengajar PPKS) |
| **Siswa Pembelajar** | `student@edutube.org` | `password` | SMP Negeri 1 Bitung (Aktif Belajar) |
| **Siswa Pembelajar (2)** | `budi@edutube.org` | `password` | SMP Negeri 1 Bitung (Lulus Kursus BKKBN) |
| **Penyuluh / Tamu** | `guest@edutube.org` | `password` | Petugas Lapangan BKKBN (Mode Galeri) |

### 2.5 Lupa Kata Sandi & Prosedur Bantuan Akses
- Siswa yang lupa kata sandi dapat melapor ke **Operator Sekolah** masing-masing untuk dilakukan *reset* instan menjadi kata sandi standar `password`.
- Pengguna umum dapat mengirimkan pesan melalui formulir tiket bantuan pada footer aplikasi.

---

## 3. KURIKULUM & SILABUS MATERI RIIL APLIKASI

### 3.1 Peta Kurikulum & Metrik Data Riil
Platform SIAGA BUMI-SSK mengoperasikan **3 kursus resmi**, **6 video pembelajaran HD**, dan **bank soal kuis checkpoint** yang terhubung langsung ke basis data produksi:

| Judul Kursus Resmi | Pilar Kurikulum | Satuan Sekolah Pengampu | Guru Pengampu / Instruktur | Total Modul & Durasi | Akses Mode Galeri Tamu |
| :--- | :--- | :--- | :--- | :---: | :---: |
| **Mengenai BKKBN** | Materi Umum Kependudukan | SMP Negeri 1 Bitung | Drs. Ronald Lumempouw | 2 Video (`20:00`) | Ya (`is_visible_to_guest: true`) |
| **Apa sih SSK?** | Materi Umum Lingkungan | SRMA 44 Minahasa | Ibu Martha Runtuwene, S.Pd | 2 Video (`20:00`) | Tidak (Khusus Siswa & Guru) |
| **Pencegahan & Penanganan Kekerasan Seksual (PPKS)** | Kekerasan Sexual | SMP Negeri 1 Bitung | Dra. Maria Sondakh, M.Si | 2 Video (`19:00`) | Ya (`is_visible_to_guest: true`) |

---

### 3.2 Kursus 1: Mengenai BKKBN (Pilar Kependudukan)
- **ID Kursus**: `5` | **Slug**: `mengenai-bkkbn-peran-kependudukan`
- **Kategori**: Materi Umum Kependudukan
- **Sekolah Pengampu**: SMP Negeri 1 Bitung
- **Instruktur Pengampu**: Drs. Ronald Lumempouw
- **Tingkat**: Pemula (SMP/SMA)
- **Status Akses Tamu**: Tampil di Galeri Publik (`is_visible_to_guest: true`)
- **Deskripsi Kursus**:  
  *Mengenal sejarah kelembagaan, visi transformasi, dan fungsi strategis BKKBN dalam memimpin program Bangga Kencana serta percepatan penurunan stunting menuju Indonesia Emas 2045.*

#### Silabus Pelajaran Video:
| No | Judul Pelajaran Video | Durasi | ID Video YouTube | Ringkasan Pembelajaran |
| :-: | :--- | :-: | :-: | :--- |
| 1 | **1.1: Makna & Filosofi Kelembagaan BKKBN** | `08:00` | `UCGVFxAa7nA` | Memahami transformasi, visi kelembagaan, serta makna logo dan komitmen baru BKKBN dalam melayani keluarga Indonesia yang berkualitas. |
| 2 | **1.2: Program Bangga Kencana & Percepatan Penurunan Stunting** | `12:00` | `EA3wO1h8EX0` | Edukasi strategis mengenai pilar Pembangunan Keluarga, Kependudukan, dan Keluarga Berencana (Bangga Kencana) serta aksi percepatan penurunan stunting. |

#### Butir Soal Kuis Checkpoint Riil di Database:
> **Pertanyaan (Pelajaran 1.1):**  
> *"Apa kepanjangan resmi dan mandat utama dari BKKBN?"*  
>  
> - **Opsi A (Kunci Jawaban Benar):** Badan Kependudukan dan Keluarga Berencana Nasional  
> - **Opsi B:** Badan Koordinasi Kesejahteraan Bersama Nasional  
> - **Opsi C:** Badan Ketahanan Pangan dan Kependudukan Nasional  
> - **Opsi D:** Balai Konseling dan Keluarga Berencana Nasional  
>  
> **Pembahasan Pedagogis:**  
> BKKBN adalah Badan Kependudukan dan Keluarga Berencana Nasional yang bertugas melaksanakan pengendalian penduduk dan penyelenggaraan keluarga berencana.

---

### 3.3 Kursus 2: Apa sih SSK? (Pilar Lingkungan)
- **ID Kursus**: `6` | **Slug**: `apa-sih-ssk-sekolah-siaga-kependudukan`
- **Kategori**: Materi Umum Lingkungan
- **Sekolah Pengampu**: Sekolah Rakyat Menengah Atas 44 Minahasa
- **Instruktur Pengampu**: Ibu Martha Runtuwene, S.Pd
- **Tingkat**: Pemula (SMP/SMA)
- **Status Akses Tamu**: Tertutup untuk publik (`is_visible_to_guest: false`, kurikulum khusus siswa binaan)
- **Deskripsi Kursus**:  
  *Memahami filosofi Sekolah Siaga Kependudukan (SSK), integrasi materi wawasan kependudukan dan kepedulian lingkungan Siaga Bumi ke dalam mata pelajaran sekolah, serta pembentukan Pojok Kependudukan.*

#### Silabus Pelajaran Video:
| No | Judul Pelajaran Video | Durasi | ID Video YouTube | Ringkasan Pembelajaran |
| :-: | :--- | :-: | :-: | :--- |
| 1 | **1.1: Apa Sih Sekolah Siaga Kependudukan (SSK)?** | `09:00` | `6bGlT6CVohg` | Menjelaskan konsep dasar Sekolah Siaga Kependudukan (SSK), tujuan integrasi materi kependudukan ke dalam mata pelajaran sekolah, serta urgensi kepedulian remaja. |
| 2 | **1.2: Panduan Pengenalan & Pojok Kependudukan SSK** | `11:00` | `x8PgjAKr-h8` | Tata cara pembentukan Pojok Kependudukan (Population Corner) di sekolah sebagai sentra literasi, sarana konseling sebaya, dan media integrasi edukasi lingkungan Siaga Bumi. |

#### Butir Soal Kuis Checkpoint Riil di Database:
> **Pertanyaan (Pelajaran 1.1):**  
> *"Apa fungsi utama dari Pojok Kependudukan (Population Corner) di Sekolah Siaga Kependudukan?"*  
>  
> - **Opsi A:** Sebagai ruang pertemuan staf guru saja  
> - **Opsi B (Kunci Jawaban Benar):** Sebagai pusat informasi, literasi, perpustakaan mini, dan diskusi sebaya tentang kependudukan  
> - **Opsi C:** Sebagai tempat penyimpanan alat olahraga sekolah  
> - **Opsi D:** Sebagai ruang bimbingan konseling khusus pelanggaran disiplin  
>  
> **Pembahasan Pedagogis:**  
> Pojok Kependudukan adalah sarana edukasi dan diskusi interaktif bagi siswa mengenai dinamika kependudukan dan isu lingkungan sekitar.

---

### 3.4 Kursus 3: Pencegahan & Penanganan Kekerasan Seksual (PPKS)
- **ID Kursus**: `9` | **Slug**: `pencegahan-dan-penanganan-kekerasan-seksual-ppks`
- **Kategori**: Kekerasan Sexual
- **Sekolah Pengampu**: SMP Negeri 1 Bitung
- **Instruktur Pengampu**: Dra. Maria Sondakh, M.Si
- **Tingkat**: Pemula (SMP/SMA)
- **Status Akses Tamu**: Tampil di Galeri Publik (`is_visible_to_guest: true`)
- **Deskripsi Kursus**:  
  *Pedoman komprehensif bagi pelajar dan pendidik dalam mengidentifikasi, mencegah, serta menangani kekerasan seksual di satuan pendidikan dengan pendekatan ramah anak dan berkeadilan.*

#### Silabus Pelajaran Video:
| No | Judul Pelajaran Video | Durasi | ID Video YouTube | Ringkasan Pembelajaran |
| :-: | :--- | :-: | :-: | :--- |
| 1 | **1.1: Memahami Batasan & Pencegahan Kekerasan Seksual di Sekolah** | `09:00` | `EA3wO1h8EX0` | Mengenali bentuk-bentuk kekerasan seksual fisik dan verbal, membangun kesadaran batasan pribadi remaja (consent), dan menciptakan ruang aman di lingkungan belajar. |
| 2 | **1.2: Peran Satgas PPKS & Mekanisme Pengaduan Ramah Anak** | `10:00` | `UCGVFxAa7nA` | Alur pelaporan yang aman dan terpercaya, perlindungan identitas korban, serta peran konseling sebaya dan guru BK dalam mendampingi peserta didik. |

#### Butir Soal Kuis Checkpoint Riil di Database:
> **Pertanyaan (Pelajaran 1.1):**  
> *"Apa langkah pertama yang tepat dilakukan apabila melihat atau mengalami tindakan kekerasan seksual di sekolah?"*  
>  
> - **Opsi A:** Menyimpan rahasia dan merasa bersalah sendiri  
> - **Opsi B (Kunci Jawaban Benar):** Segera melapor ke Satgas PPKS, guru BK, atau orang dewasa terpercaya yang aman  
> - **Opsi C:** Membalas dengan kekerasan fisik serupa  
> - **Opsi D:** Membagikan kejadian tersebut ke media sosial tanpa perlindungan privasi  
>  
> **Pembahasan Pedagogis:**  
> Melaporkan kepada Satgas PPKS atau guru BK menjamin perlindungan privasi, pendampingan psikologis, dan penanganan kasus secara adil dan aman.

---

### 3.5 Matriks Evaluasi Kuis Checkpoint & Ambang Kelulusan
- **Ambang Menonton Video**: Minimal **90%** dari total durasi video (dihitung otomatis oleh sistem via YouTube API).
- **Ambang Kelulusan Kuis**: Nilai minimal **70%**.
- **Hak Mengulang (Remedial)**: Tanpa batas pengulangan sampai siswa memahami konsep materi secara tuntas.

---

## 4. PANDUAN SISWA PEMBELAJAR (`siswa`)

### 4.1 Dasbor Pembelajaran & Satu Aksi Utama
Setelah masuk ke akun siswa, antarmuka menyajikan informasi secara terfokus tanpa distraksi:
- **Kartu Lanjutkan Belajar:** Menampilkan kursus yang sedang aktif (contoh: *Mengenai BKKBN* atau *Apa sih SSK?*) beserta sisa kemajuan belajar.
- **Satu Aksi Utama Prominen:** Tombol hijau **Lanjutkan Video Terakhir** langsung membawa siswa ke detik terakhir video yang ditonton.
- **Pesan Kemajuan Memotivasi:** Teks linier ramah yang menyampaikan sisa langkah (misal: *"Tinggal 1 materi lagi untuk dapat sertifikat kelulusan!"*).

![Dasbor Siswa Pembelajar](./screenshots/04-dasbor-siswa.png)
*Gambar 4.1: Dasbor Pembelajaran Siswa dengan kartu aksi primer "Lanjutkan Video Terakhir", indikator kelulusan bersahabat, dan filter topik materi.*

### 4.2 Teater Belajar & Pemutar Video HD Bebas Iklan (*Classroom*)
Saat membuka materi di `/belajar/{slug}`:
- Video YouTube HD dimainkan secara terisolasi tanpa rekomendasi video luar.
- Pada layar desktop, panel navigasi berada di samping pemutar; pada layar ponsel cerdas, panel berada tepat di bawah pemutar dengan susunan tab: **Materi**, **Catatan**, dan **Diskusi**.

![Teater Belajar Ruang Kelas Digital](./screenshots/05-ruang-belajar-video.png)
*Gambar 4.2: Ruang Belajar Digital (Classroom) dengan teater video YouTube HD bebas iklan, pelacak otomatis durasi tontonan 90%, dan tab navigasi interaktif.*

### 4.3 Live Watch Completion Tracker (Ambang 90% Durasi Video)
- Sistem memantau perkembangan tontonan siswa setiap 10 detik secara latar belakang.
- **Contoh Kasus:** Pada Pelajaran 1.1 *"Makna & Filosofi Kelembagaan BKKBN"* (durasi 08:00 / 480 detik), siswa wajib menonton minimal hingga menit **`07:12` (432 detik)** agar status pelajaran tercatat **Tuntas**.
- Begitu ambang 90% tercapai, tombol utama **Buka Kuis Checkpoint** di bawah video akan otomatis terbuka.

### 4.4 Catatan Waktu Interaktif (*Timestamp Notes*) & Pintasan `Ctrl + Enter`
Siswa dapat mencatat penjelasan penting guru yang terhubung ke menit video:
1. Klik tab **Catatan** di samping pemutar video.
2. Klik tombol **+ Ambil Menit Saat Ini** (sistem otomatis menangkap waktu pemutaran, misal `03:45`).
3. Tulis rangkuman materi Anda.
4. Tekan pintasan **`Ctrl + Enter`** atau klik tombol **Simpan Catatan**.
5. Catatan tersimpan dengan tombol interaktif **`▶ 03:45 (Putar)`**. Kapan saja tombol tersebut diklik, video seketika melompat dan memutar bagian materi pada detik yang dicatat.

![Pencatatan Waktu Interaktif](./screenshots/06-catatan-waktu.png)
*Gambar 4.3: Panel Catatan Waktu Interaktif dengan tombol ambil menit video otomatis, pintasan simpan cepat, dan tag putar ulang (seekTo).*

### 4.5 Forum Diskusi Materi Antarsiswa & Guru
1. Buka tab **Diskusi**.
2. Tulis pertanyaan mengenai materi yang belum dimengerti, lalu klik **Kirim Pertanyaan**.
3. Guru maupun rekan sekelas dapat memberikan tanggapan atau tanda apresiasi (*Like*).

### 4.6 Mengerjakan Kuis Checkpoint & Umpan Balik Ramah (Passing Grade 70%)
1. Klik tombol **Buka Kuis Checkpoint**.
2. Pilih jawaban dengan menekan kartu pilihan (A, B, C, atau D). Tombol didesain luas (`min-h-14`) agar bebas salah tekan pada layar ponsel.
3. Klik **Kirim Jawaban Kuis**.
4. **Hasil Evaluasi:**
   - **Jika Nilai ≥ 70% (Lulus):** Pesan selamat muncul, materi dinyatakan tuntas, dan progres sertifikat bertambah.
   - **Jika Nilai < 70% (Belum Lulus):** Sistem menyajikan kalimat penyemangat:  
     *"Sedikit lagi lulus passing grade 70%! Kamu bisa tonton ulang materi dan coba kuis lagi tanpa batasan."*  
     Siswa dapat langsung mengklik tombol **Ulangi Kuis** untuk mencoba kembali hingga lulus.

![Evaluasi Kuis Checkpoint Pemahaman](./screenshots/07-kuis-checkpoint.png)
*Gambar 4.4: Tampilan kuis checkpoint dengan kartu opsi sentuh luas, umpan balik ramah jika nilai < 70%, dan tombol instan ulangi kuis.*

### 4.7 Klaim dan Cetak E-Sertifikat Kelulusan
Setelah siswa menyelesaikan seluruh modul dan lulus semua kuis checkpoint dalam kursus:
1. Kotak dialog perayaan kelulusan (*Graduation Celebration*) akan tampil di layar.
2. Klik tombol emas **Lihat & Cetak Sertifikat Kelulusan**.
3. Siswa dapat mengunduh berkas sertifikat atau mencetak langsung melalui peramban (`Ctrl + P`).

---

## 5. PANDUAN GURU / PENDIDIK (`guru`)

### 5.1 Mengakses Ruang Pendidik
Pendidik (seperti Ibu Martha Runtuwene, S.Pd di SRMA 44 Minahasa dan Drs. Ronald Lumempouw di SMPN 1 Bitung) memiliki ruang kerja khusus untuk menyusun materi pembelajaran dan bank soal.

### 5.2 Alur Kerja Unified Course Builder
Untuk menyusun materi kursus:
1. Buka menu **Manajer Kursus** (`/admin/courses`), lalu klik **+ Unified Course Builder**.
2. **Tahap 1: Informasi Dasar Kursus**
   - Tentukan judul kursus (contoh: *Pendidikan Kesehatan Reproduksi Remaja*).
   - Pilih kategori pilar (*Materi Umum Kependudukan*, *Materi Umum Lingkungan*, atau *Kekerasan Sexual*).
   - Tentukan tingkat sekolah (*Tingkat Dasar SMP* atau *Tingkat Menengah SMA*).
   - Masukkan nama instruktur pengampu beserta gelar.
   - Unggah gambar sampul rasio 16:9.

![Manajer Kursus & Kurikulum](./screenshots/10-course-builder.png)
*Gambar 5.1: Antarmuka Manajer Kursus untuk menyusun materi ajar, mengatur silabus bab, dan mengontrol visibilitas publik/tamu.*

### 5.3 Menambahkan Modul & Ingest Video YouTube Otomatis
1. Pada Course Builder, klik **+ Tambah Bab / Modul Baru**.
2. Di dalam modul, klik **+ Tambah Materi Pembelajaran**.
3. Tempelkan tautan URL YouTube (contoh: `https://www.youtube.com/watch?v=6bGlT6CVohg`).
4. Sistem secara otomatis membaca durasi video dalam format menit:detik dan mengambil gambar mini (*thumbnail*) resolusi tinggi.
5. Klik **Simpan Materi**.

### 5.4 Merancang Kuis Checkpoint, Kunci Jawaban & Pembahasan Pedagogis
1. Pada materi yang telah dibuat, klik tombol **Kelola Kuis**.
2. Masukkan butir pertanyaan pilihan ganda.
3. Masukkan 4 opsi pilihan jawaban (A, B, C, D).
4. Tandai bulatan pada opsi yang merupakan kunci jawaban yang benar.
5. Tuliskan **Pembahasan / Ulasan Soal**: Ulasan ini sangat penting untuk memberikan umpan balik edukatif saat siswa menyelesaikan kuis.
6. Klik **Simpan Soal**.

### 5.5 Memantau Perkembangan & Nilai Evaluasi Siswa
Guru dapat melihat daftar siswa yang telah menuntaskan video, riwayat nilai kuis pertama, dan jumlah percobaan kuis untuk keperluan rekap nilai muatan lokal SSK.

### 5.6 Batasan Keamanan Hak Akses Guru
Untuk menjaga integritas data kelembagaan, akun Guru **tidak memiliki akses** ke pengaturan master sekolah, manajemen akun pengguna, dan pengaturan sistem pusat. Seluruh percobaan mengakses modul administratif pusat akan ditolak dengan respons aman *403 Forbidden*.

---

## 6. PANDUAN OPERATOR SEKOLAH (`operator_sekolah`)

### 6.1 Dasbor Sekolah & Statistik Satuan Pendidikan
Operator Sekolah (seperti Meivy Karundeng, S.Kom di SMP Negeri 1 Bitung) memiliki wewenang administratif pada tingkat satuan pendidikan:
- Memantau total siswa terdaftar di sekolah bersangkutan.
- Memantau jumlah siswa yang telah lulus dan mengklaim sertifikat BKKBN.
- Memantau keaktifan belajar harian siswa per kelas.

### 6.2 Pendaftaran Siswa Baru Secara Satuan
1. Buka menu **Manajemen Siswa** (`/admin/students`).
2. Klik tombol hijau **+ Tambah Siswa**.
3. Masukkan data: Nama Lengkap Siswa, NISN, Rombongan Belajar / Kelas, Alamat Email, Nomor WhatsApp, dan Kata Sandi awal (`password`).
4. Klik **Simpan Siswa**.

![Manajemen Data Siswa](./screenshots/11-manajemen-siswa.png)
*Gambar 6.1: Panel Manajemen Siswa Sekolah untuk penambahan akun satuan, impor masal CSV, dan pengaturan ulang (reset) kata sandi.*

### 6.3 Pendaftaran Masal Siswa Menggunakan Template CSV
Untuk mendaftarkan puluhan hingga ratusan siswa baru (contohnya saat tahun ajaran baru):
1. Buka menu **Manajemen Siswa** (`/admin/students`).
2. Klik tombol **Impor Masal via CSV**.
3. Unduh berkas format CSV resmi (`template-siswa.csv`).
4. Buka di Microsoft Excel atau Google Spreadsheet, isi data kolom: `name`, `email`, `nisn`, `phone`, dan `address`.
5. Simpan sebagai format `.csv (Comma delimited)`.
6. Unggah kembali berkas CSV tersebut, lalu klik **Proses Impor CSV**.

### 6.4 Mengatur Ulang (*Reset*) Kata Sandi Akun Siswa Menjadi `password`
Jika siswa lupa kata sandi akunnya:
1. Buka menu **Manajemen Siswa**.
2. Cari nama siswa pada kotak pencarian cepat.
3. Klik tombol **Reset Sandi**. Kata sandi akun siswa otomatis diatur ulang ke sandi standar: **`password`**. Siswa dapat langsung masuk kembali.

### 6.5 Memverifikasi Status Kelulusan Siswa Per Kelas
Operator dapat mencetak rekapitulasi siswa yang telah menyelesaikan kursus SSK per kelas untuk bahan pelaporan ke Kepala Sekolah dan Dinas Pendidikan setempat.

---

## 7. PANDUAN ADMIN PUSAT (`admin_pusat` / `super_admin`)

### 7.1 Dasbor Analitik Tingkat Provinsi BKKBN
Admin Pusat (seperti Dr. Hendra Sompotan, M.Si) mengawasi seluruh operasional SIAGA BUMI-SSK se-Sulawesi Utara:
- Metrik makro siswa aktif se-kabupaten/kota.
- Rekapitulasi sertifikat terverifikasi per sekolah mitra.
- Statistik peminatan topik kurikulum kependudukan, lingkungan, dan PPKS.

![Dasbor Administrator Pusat](./screenshots/09-dasbor-admin.png)
*Gambar 7.1: Dasbor Analitik Administrator Pusat BKKBN Perwakilan Sulawesi Utara untuk pemantauan data makro sekolah dan kurikulum provinsi.*

### 7.2 Manajemen Master Data Sekolah Mitra
Admin Pusat berwenang menambah, mengubah, atau memperbarui informasi sekolah mitra percontohan (*SMP Negeri 1 Bitung* dan *Sekolah Rakyat Menengah Atas 44 Minahasa*) melalui menu `/admin/schools`.

### 7.3 Manajemen Akun Operator & Pengguna Lintas Sekolah
Melalui menu `/admin/users`, Admin Pusat mengelola pembuatan akun operator sekolah, guru pengampu, serta mengaktifkan atau menonaktifkan akun yang melanggar ketentuan.

### 7.4 Kurasi Visibilitas Akses Mode Galeri untuk Tamu (`is_visible_to_guest`)
Pada menu **Manajer Kursus** (`/admin/courses`), Admin Pusat bertindak sebagai kurator konten:
- **Kursus Terpilih (Aktif):** Kursus *Mengenai BKKBN* dan *PPKS* disetel `is_visible_to_guest = true` agar dapat disimak oleh Penyuluh KB lapangan dan tamu umum.
- **Kursus Internal (Terkunci):** Kursus *Apa sih SSK?* disetel `is_visible_to_guest = false` sebagai materi internal siswa binaan SRMA 44 Minahasa.

### 7.5 Pemeriksaan Kesehatan Tautan Video YouTube (*Video Health Check*)
Sistem menyediakan alat otomatis untuk memindai seluruh video kursus guna memastikan tautan YouTube tetap aktif dan tidak dihapus oleh pemilik video di YouTube.

### 7.6 Pengaturan Kebijakan Sistem Global
Admin Pusat mengonfigurasi nama instansi resmi, ambang batas *passing grade* sertifikat (standar: 70%), dan ambang batas durasi tontonan (standar: 90%).

### 7.7 Manajemen Tiket Bantuan & Pengaduan Teknis
Melalui menu `/admin/support`, Admin Pusat menerima dan menanggapi laporan kendala dari operator sekolah, guru, maupun masyarakat umum.

---

## 8. PANDUAN PENYULUH & TAMU (`penyuluh` - MODE GALERI)

### 8.1 Karakteristik Akses Mode Galeri (*View-Only*)
Akun Penyuluh / Tamu (`guest@edutube.org`) difungsikan khusus bagi Petugas Lapangan Keluarga Berencana (PLKB) BKKBN Sulawesi Utara, peninjau Dinas Pendidikan, dan mitra kampus.

### 8.2 Sarana Penyuluhan Lapangan PIK-R di Desa/Kelurahan
- Penyuluh dapat memutar video edukasi resmi di hadapan kelompok Pusat Informasi dan Konseling Remaja (PIK-R) di balai desa tanpa terganggu iklan YouTube.
- Menjadi media peraga penyuluhan mengenai bahaya perkawinan anak, pencegahan stunting, dan wawasan kependudukan.

### 8.3 Batasan Hak Akses Mode Galeri
Untuk menjaga integritas penilaian akademik sekolah:
- Akun tamu tidak dapat menyimpan catatan berwaktu pribadi.
- Akun tamu tidak dapat mengirimkan komentar diskusi.
- Akun tamu tidak dapat mengerjakan kuis checkpoint atau menerbitkan sertifikat siswa.

---

## 9. SISTEM SERTIFIKASI DIGITAL & VERIFIKASI QR CODE

### 9.1 Tiga Syarat Mutlak Penerbitan Sertifikat
Sertifikat kelulusan hanya diterbitkan oleh sistem apabila:
1. Siswa telah menonton **100% video materi** pada kursus terkait (tidak ada modul yang terlewat).
2. Siswa telah lulus **seluruh kuis checkpoint** dengan nilai minimal **70%**.

### 9.2 Komponen Pengamanan Sertifikat Digital
Setiap lembar sertifikat digital dilengkapi pengamanan:
1. **Nomor Registrasi Unik**: Berformat terstandarisasi, contoh: `BKKBN-SULUT/SSK/2026/{KODE}`.
2. **QR Code Kriptografis**: Mengarahkan pemindai langsung ke laman otentikasi server resmi.
3. **Penyematan Lembaga Resmi**: Memuat lambang Garuda/BKKBN dan konsorsium UNIMA, UNPI, serta IPDN.

### 9.3 Laman Verifikasi Publik Ber-QR Code (`/sertifikat/{kode}`)
Siapa pun (orang tua, universitas, maupun sekolah lanjutan) dapat memeriksa keabsahan sertifikat tanpa perlu login:
1. Arahkan kamera ponsel cerdas ke gambar QR Code pada lembar sertifikat.
2. Klik tautan verifikasi yang muncul di layar ponsel.
3. Laman verifikasi publik menyajikan status lencana hijau **"Terverifikasi Asli & Sah"**, nama lengkap siswa, nama sekolah mitra, nilai evaluasi, dan tanggal resmi kelulusan dari basis data server.

![Laman Verifikasi Sertifikat Resmi](./screenshots/08-verifikasi-sertifikat.png)
*Gambar 9.1: Laman Verifikasi Publik Resmi ber-QR Code yang dapat diakses oleh publik dan perguruan tinggi tanpa memerlukan otentikasi login.*

### 9.4 Data Sertifikat Riil yang Telah Terbit di Database
Berikut contoh rekaman sertifikat resmi yang telah aktif dan terverifikasi di basis data sistem:
- **Kode Sertifikat 1**: `CERT-SSK-BUDI-5BAC414B`  
  - Penerima: **Budi Pratama** (SMP Negeri 1 Bitung)  
  - Kursus: *Mengenai BKKBN*  
  - Status: Terverifikasi Sah & Asli
- **Kode Sertifikat 2**: `CERT-SSK-SARAH-A1350F47`  
  - Penerima: **Sarah Connor** (SMP Negeri 1 Bitung)  
  - Kursus: *Pencegahan & Penanganan Kekerasan Seksual (PPKS)*  
  - Status: Terverifikasi Sah & Asli

---

## 10. PUSAT BANTUAN & TANYA JAWAB (FAQ)

### 10.1 Mengapa Tombol Kuis Checkpoint Belum Bisa Dibuka?
**Jawaban:** Tombol kuis baru akan terbuka secara otomatis setelah Anda menonton minimal **90% durasi video materi**. Pastikan video diputar hingga tuntas dan koneksi internet stabil saat memutar video.

### 10.2 Apa yang Harus Dilakukan Jika Nilai Kuis di Bawah 70%?
**Jawaban:** Sistem SIAGA BUMI-SSK tidak membatasi kesempatan mengulang. Klik tombol **Ulangi Kuis**, pelajari kembali pembahasan soal yang ditampilkan di bawah pertanyaan, dan kirimkan kembali jawaban Anda hingga memperoleh nilai 70% atau lebih.

### 10.3 Mengapa Video YouTube Menampilkan Tulisan "Video Unavailable"?
**Jawaban:** Hal ini terjadi jika pemilik video di YouTube mengubah pengaturan video menjadi Privat atau mematikan izin penyematan (*embedding*). Laporkan hal ini kepada Guru atau Admin Pusat melalui tab diskusi materi agar URL video alternatif dapat segera dipasang.

### 10.4 Bagaimana Cara Membetulkan Salah Eja Nama pada Lembar Sertifikat?
**Jawaban:** Nama pada sertifikat dicetak mengacu pada data profil master siswa. Hubungi Operator Sekolah untuk membetulkan ejaan nama pada akun Anda, setelah itu sertifikat otomatis menampilkan nama yang benar.

### 10.5 Apakah Aplikasi Dapat Diakses Melalui Smartphone?
**Jawaban:** Ya, seluruh tampilan SIAGA BUMI-SSK telah dirancang dengan prinsip *Mobile-First*, responsif, dan tombol-tombol sentuh lega (`min-h-11` hingga `min-h-14`) sehingga nyaman digunakan pada perangkat Android maupun iPhone.

---
*Buku Panduan Pengguna Resmi SIAGA BUMI-SSK © 2026 — BKKBN Perwakilan Provinsi Sulawesi Utara*
