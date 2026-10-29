# BUKU PANDUAN PENGGUNA (MANUAL BOOK)
# PLATFORM LMS "SIAGA BUMI-SSK"
### Integrasi Lingkungan dan Kependudukan Dalam Program Sekolah Siaga Kependudukan

---

## DAFTAR ISI
1. [PENDAHULUAN & GAMBARAN UMUM](#1-pendahuluan--gambaran-umum)
2. [PANDUAN MASUK & PENDAFTARAN AKUN](#2-panduan-masuk--pendaftaran-akun)
3. [PANDUAN UNTUK ADMIN PUSAT (CENTRAL ADMIN)](#3-panduan-untuk-admin-pusat-admin_pusat)
4. [PANDUAN UNTUK OPERATOR SEKOLAH (SCHOOL OPERATOR)](#4-panduan-untuk-operator-sekolah-operator_sekolah)
5. [PANDUAN UNTUK GURU / PENDIDIK (TEACHER)](#5-panduan-untuk-guru-guru)
6. [PANDUAN UNTUK SISWA / PEMBELAJAR (STUDENT)](#6-panduan-untuk-siswa-siswa)
7. [PANDUAN UNTUK PENYULUH / TAMU (GUEST - MODE GALERI)](#7-panduan-untuk-penyuluh--tamu-penyuluh)
8. [SISTEM SERTIFIKASI & VERIFIKASI QR CODE](#8-sistem-sertifikasi--verifikasi-qr-code)
9. [PUSAT BANTUAN & TANYA JAWAB (FAQ)](#9-pusat-bantuan--tanya-jawab-faq)

---

## 1. PENDAHULUAN & GAMBARAN UMUM

### 1.1 Mengenai SIAGA BUMI-SSK
**SIAGA BUMI-SSK (Sekolah Siaga Kependudukan)** adalah platform manajemen pembelajaran digital (*Learning Management System / LMS*) yang dirancang khusus untuk mengintegrasikan pendidikan wawasan kependudukan, kesadaran kelestarian lingkungan hidup, serta pencegahan dan penanganan kekerasan seksual ke dalam ekosistem pendidikan sekolah menengah di Indonesia.

Program ini diinisiasi dengan komitmen kolaboratif bersama **BKKBN Perwakilan Sulawesi Utara** dan didukung oleh konsorsium lembaga perguruan tinggi terkemuka:
1. **UNIMA** — Universitas Negeri Manado
2. **UNPI** — Universitas Pembangunan Indonesia
3. **IPDN** — Institut Pemerintahan Dalam Negeri
4. **BKKBN** — Badan Kependudukan dan Keluarga Berencana Nasional

### 1.2 Kategori Pembelajaran Utama
Kurikulum dalam SIAGA BUMI-SSK dikelompokkan ke dalam 3 fokus pembelajaran strategis:
- **`Materi Umum Kependudukan`**: Pengenalan konsep kependudukan, program Generasi Berencana (GenRe), pendewasaan usia perkawinan, bonus demografi, dan peran strategis BKKBN.
- **`Materi Umum Lingkungan`**: Penanaman nilai cinta alam, konservasi keanekaragaman hayati Minahasa & Sulawesi Utara, aksi Siaga Bumi, serta pengelolaan sampah sekolah.
- **`Kekerasan Sexual`**: Edukasi preventif, perlindungan hak anak dan remaja, pengenalan batasan privasi tubuh, serta tata cara pelaporan yang aman.

---

## 2. PANDUAN MASUK & PENDAFTARAN AKUN

### 2.1 Pendaftaran Mandiri Siswa Baru
Calon siswa dapat mendaftar mandiri melalui tautan **Daftar Sekarang** di beranda atau laman login (`/register`).
1. Buka peramban (*browser*) dan arahkan ke alamat web: `http://localhost/register` atau klik tombol **Daftar Siswa**.
2. Lengkapi formulir registrasi dengan data valid:
   - **Nama Lengkap**: Masukkan nama lengkap sesuai identitas rapor / kartu pelajar.
   - **Alamat Lengkap / Domisili**: Cantumkan alamat tinggal atau kota asal (contoh: *Bitung, Manado, atau Minahasa*).
   - **Alamat Email**: Email aktif yang belum pernah terdaftar.
   - **No. Handphone / WhatsApp**: Nomor ponsel aktif untuk keperluan verifikasi.
   - **Kata Sandi**: Minimal 6 karakter alfanumerik.
   - **Konfirmasi Kata Sandi**: Ulangi kata sandi yang sama.
3. Klik tombol **Daftar Akun Siswa**.
4. Sistem akan otomatis membuat akun dengan peran **Siswa (`siswa`)** dan mengarahkan Anda langsung ke Dasbor Pembelajaran.

### 2.2 Masuk ke Akun (Login)
1. Akses halaman login di `http://localhost/login`.
2. Masukkan alamat email dan kata sandi Anda.
3. *(Opsional)* Centang kotak **Ingat sesi saya di perangkat ini** untuk tetap masuk pada perangkat pribadi Anda.
4. Klik tombol **Masuk ke Akun Pembelajaran**.

### 2.3 Fitur 1-Click Demo Login (Uji Peran)
Bagi tim evaluator, pengembang, maupun pengawas sistem, disediakan panel cepat **1-Click Demo Login** di bagian bawah form login untuk berpindah peran seketika tanpa perlu mengetik kata sandi:
- **Admin Pusat**: `admin.pusat@ssk.kemdikbud.go.id` (Akses Super Admin penuh).
- **Operator Sekolah**: `operator.smpn1bitung@sekolah.sch.id` (Akses data SMPN 1 Bitung).
- **Guru / Pendidik**: `guru.bitung@sekolah.sch.id` (Akses pembuat kurikulum & kuis).
- **Siswa Pembelajar**: `siswa.bitung@sekolah.sch.id` (Akses kelas & sertifikat).
- **Penyuluh / Tamu**: `penyuluh.sulut@bkkbn.go.id` (Akses galeri kurasi lihat-saja).
*(Kata sandi bawaan seluruh akun demo adalah: `password`)*.

---

## 3. PANDUAN UNTUK ADMIN PUSAT (`admin_pusat`)

Peran **Admin Pusat** memiliki wewenang tata kelola tertinggi di seluruh sistem SIAGA BUMI-SSK.

### 3.1 Manajemen Kursus & Fitur Kurasi Tamu (`is_visible_to_guest`)
Admin Pusat bertindak sebagai kurator konten untuk publik dan instansi luar.
1. Masuk sebagai Admin Pusat dan pilih menu **Manajer Kursus** di bilah sisi (*sidebar*).
2. Pada tabel kursus, terdapat kolom khusus **Akses Tamu**:
   - Jika berstatus **Aktif (Tampil)**: Kursus tersebut dapat dilihat oleh peran **Penyuluh / Tamu** di beranda mode galeri.
   - Jika berstatus **Terkunci**: Kursus tersebut hanya dapat diakses oleh siswa internal terdaftar.
3. Untuk mengubah status visibilitas materi secara instan, klik tombol **Tampilkan** atau **Sembunyikan** di bawah lencana status pada tabel kursus.
4. Anda juga dapat mengatur opsi ini saat membuat atau menyunting kursus melalui kotak centang **Akses Mode Galeri (Penyuluh & Tamu)**.

### 3.2 Pengelolaan Master Data Sekolah Mitra
1. Buka menu **Pusat > Master Sekolah** (`/admin/schools`).
2. Klik **+ Tambah Sekolah Baru**.
3. Masukkan rincian sekolah (seperti *SMP Negeri 1 Bitung* atau *Sekolah Rakyat Menengah Atas 44 Minahasa*), NPSN, kota, dan status aktif.
4. Klik **Simpan Sekolah**.

### 3.3 Pengelolaan Operator Sekolah & Pengguna
1. Buka menu **Pusat > Manajemen Pengguna** (`/admin/users`).
2. Untuk mendaftarkan operator sekolah baru, klik tab **Tambah Operator Sekolah**.
3. Pilih instansi sekolah yang bersangkutan dan isi kredensial akun operator.

### 3.4 Pengaturan Platform Global & Tiket Dukungan
- Menu **Pengaturan Sistem** (`/admin/settings`): Mengatur nama instansi, ambang batas kuis kelulusan bawaan, dan kebijakan unggahan.
- Menu **Dukungan & Log** (`/admin/support`): Membaca dan membalas tiket kendala teknis yang dikirimkan oleh pihak sekolah.

---

## 4. PANDUAN UNTUK OPERATOR SEKOLAH (`operator_sekolah`)

Peran **Operator Sekolah** bertanggung jawab atas administrasi siswa dan pembelajaran di satuan pendidikannya.

### 4.1 Mendaftarkan Siswa Baru
1. Buka menu **Siswa / Mahasiswa** di bilah sisi (`/admin/students`).
2. Terdapat dua pilihan pendaftaran:
   - **Pendaftaran Satuan**: Klik tombol **+ Tambah Siswa**, lalu lengkapi Nama, Alamat, Email, No. HP, NISN, dan Kelas.
   - **Impor Masal via CSV**: Klik **Impor CSV**, unduh berkas template `.csv` yang telah disediakan, isi data seluruh siswa kelas, lalu unggah kembali berkas tersebut.

### 4.2 Mengatur Ulang Kata Sandi Siswa (Reset Password)
Jika siswa lupa kata sandi akunnya:
1. Cari nama atau email siswa pada tabel daftar siswa.
2. Klik tombol **Reset Sandi** di kolom aksi.
3. Kata sandi siswa akan diatur ulang ke sandi bawaan sekolah, dan siswa dapat segera masuk kembali untuk menggantinya di menu profil.

---

## 5. PANDUAN UNTUK GURU / PENDIDIK (`guru`)

Peran **Guru** memiliki akses penuh ke fitur edukasi (*Course Manager, Curriculum Builder, Quiz Creator, dan Ingest YouTube*), namun **secara ketat dilindungi** dari menu pengaturan pengguna dan sistem.

### 5.1 Menyusun Kursus dengan Unified Course Builder
1. Buka menu **Manajer Kursus** lalu klik **+ Unified Course Builder**.
2. **Tab 1 - Info & Pengaturan**:
   - Tentukan judul kursus yang menarik dan deskriptif.
   - Pilih salah satu kategori pembelajaran resmi (*Materi Umum Kependudukan, Materi Umum Lingkungan, atau Kekerasan Sexual*).
   - Tentukan nama pengajar dan gelar akademik.
   - Unggah gambar sampul (*thumbnail*) rasio 16:9 atau tempel tautan gambar web.
3. **Tab 2 - Kurikulum & Materi Video**:
   - Buat judul modul (contoh: *Bab 1: Hakikat Kependudukan & Bonus Demografi*).
   - Tempelkan URL video edukasi dari YouTube (contoh: `https://www.youtube.com/watch?v=...`).
   - Sistem akan secara otomatis mengekstrak durasi detik, thumbnail HD, dan judul video tanpa iklan luar.
4. **Tab 3 - Evaluasi & Kuis**:
   - Tentukan passing grade kelulusan kuis (rekomendasi: 70% atau 75%).
   - Masukkan butir-butir pertanyaan pilihan ganda beserta 4 opsi jawaban.
   - Tandai jawaban yang benar dan sertakan penjelasan/pembahasan soal.
5. Klik **Terbitkan Kursus** agar materi dapat mulai dipelajari oleh siswa.

### 5.2 Perlindungan Hak Akses Guru (Keamanan RBAC)
Akun Guru tidak memiliki akses ke data manajemen siswa, master sekolah, dan setelan pusat. Jika URL administratif dibuka secara sengaja, sistem akan secara otomatis menolak dengan status aman **403 Forbidden**.

---

## 6. PANDUAN UNTUK SISWA / PEMBELAJAR (`siswa`)

### 6.1 Memulai Pembelajaran di Kelas Digital
1. Setelah masuk ke akun, buka menu **Katalog Kursus** di beranda.
2. Pilih materi pembelajaran yang tersedia: **Mengenai BKKBN** atau **Apa sih SSK?**.
3. Klik kartu kursus untuk membuka **Ruang Belajar Digital (Classroom)**.

### 6.2 Menyimak Video & Membuat Catatan Interaktif
- Video YouTube diputar dengan tampilan fokus bebas iklan distraksi.
- **Buku Catatan Waktu**: Saat video mencapai detik penting, ketik catatan pada tab **Catatan Modul** di samping video. Catatan akan tersimpan dengan stempel waktu detik yang dapat diklik untuk melompat kembali ke bagian video tersebut.
- **Forum Diskusi**: Gunakan kolom komentar di bawah video untuk bertanya kepada guru atau berdiskusi dengan sesama siswa.

### 6.3 Mengerjakan Kuis Kelulusan
1. Selesaikan menonton seluruh video materi hingga progres mencapai 100%.
2. Klik tombol **Mulai Evaluasi Kuis**.
3. Jawab seluruh pertanyaan pilihan ganda dengan teliti.
4. Setelah kuis dikirim, sistem akan langsung mengoreksi dan menampilkan perolehan nilai Anda secara transparan beserta pembahasannya.

### 6.4 Menerima E-Sertifikat Kelulusan
- Jika nilai Anda memenuhi standar kelulusan (*passing grade*), sistem secara otomatis menerbitkan **Sertifikat Kelulusan Resmi Ber-QR Code**.
- Klik tombol **Lihat E-Sertifikat** untuk membuka sertifikat digital.
- Anda dapat mengunduh berkas sertifikat dalam format PDF beresolusi tinggi atau mencetaknya untuk portofolio akademik.

---

## 7. PANDUAN UNTUK PENYULUH / TAMU (`penyuluh`)

Peran **Penyuluh / Tamu** difungsikan untuk mitra instansi, penyuluh kependudukan BKKBN di lapangan, maupun peninjau publik.

### 7.1 Karakteristik Mode Galeri (View-Only)
- Akun ini berada dalam **Mode Galeri (Lihat-Saja)**.
- Hanya materi kursus yang telah disetujui dan ditandai **`is_visible_to_guest = true`** oleh Admin Pusat yang akan tampil di katalog.
- Penyuluh dapat membaca ringkasan materi, melihat susunan silabus kurikulum, serta menyimak paparan video edukasi untuk bahan penyuluhan di lapangan.

### 7.2 Pembatasan Hak Akses
Guna menjamin integritas data evaluasi siswa:
- Akun Penyuluh/Tamu **tidak dapat** menyimpan catatan pribadi di modul.
- Akun Penyuluh/Tamu **tidak dapat** mengirim komentar forum.
- Akun Penyuluh/Tamu **tidak dapat** mengerjakan kuis atau memicu penerbitan sertifikat kelulusan siswa.
- Seluruh upaya manipulasi data akan ditolak oleh sistem dengan pesan ramah panduan mode galeri.

---

## 8. SISTEM SERTIFIKASI & VERIFIKASI QR CODE

Setiap sertifikat kelulusan yang diterbitkan oleh platform SIAGA BUMI-SSK dilengkapi dengan fitur keamanan dan keaslian:

1. **Nomor Seri Unik**: Kode unik acak berstandar nasional (contoh: `CERT-AB72X9KL4M`).
2. **QR Code Dinamis**: Kode matriks 2D yang tercetak pada sertifikat dapat dipindai menggunakan kamera ponsel atau pemindai QR standar tanpa memerlukan login.
3. **Halaman Verifikasi Publik (`/sertifikat/{kode}`)**:
   - Menampilkan nama penerima sertifikat.
   - Nama program studi / kompetensi kependudukan yang diselesaikan.
   - Instansi sekolah penyelenggara.
   - Tanggal resmi penerbitan dan segel validasi digital.

---

## 9. PUSAT BANTUAN & TANYA JAWAB (FAQ)

**T: Bagaimana jika nama di sertifikat salah eja?**  
J: Nama pada sertifikat mengacu pada data akun siswa. Siswa dapat memperbarui profil atau meminta Operator Sekolah untuk membetulkan nama pada data master siswa.

**T: Mengapa kursus yang saya buat tidak muncul di akun Penyuluh / Tamu?**  
J: Pastikan akun Admin Pusat telah mengaktifkan opsi **Akses Mode Galeri** pada kursus tersebut melalui menu *Manajer Kursus*.

**T: Video YouTube menampilkan tulisan "Video Tidak Tersedia"?**  
J: Pastikan video di YouTube disetel sebagai *Publik* atau *Tidak Publik (Unlisted)*, bukan *Pribadi (Private)*. Gunakan fitur *Cek Kesehatan Video* di dasbor admin untuk memverifikasi tautan video Anda.

---
*Dokumen ini diterbitkan secara resmi untuk pengguna aplikasi LMS SIAGA BUMI-SSK. Hak Cipta dilindungi undang-undang.*
