# ALUR PENGGUNA & FLOWCHART SISTEM SIAGA BUMI-SSK
## (Sekolah Siaga Kependudukan & Lingkungan Hidup)

Dokumen ini memuat diagram alur komprehensif (*Mermaid.js Flowcharts*) dan arsitektur akses berbasis peran (*Role-Based Access Control / RBAC*) untuk aplikasi **SIAGA BUMI-SSK LMS**.

---

## 1. Arsitektur Matriks Hak Akses (5 Peran Pengguna)

| Modul / Fitur | Admin Pusat (`admin_pusat`) | Operator Sekolah (`operator_sekolah`) | Guru (`guru`) | Siswa (`siswa`) | Penyuluh / Tamu (`penyuluh`) |
| :--- | :---: | :---: | :---: | :---: | :---: |
| **Katalog Materi Terbuka** | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Materi Terkurasi Saja |
| **Pendaftaran Mandiri (Register)** | — | — | — | ✅ Mandiri | — |
| **Pemutar Video & Materi (Classroom)** | ✅ Penuh | ✅ Penuh | ✅ Penuh | ✅ Penuh | 👁️ Pratinjau (Lihat-Saja) |
| **Catatan Pribadi & Diskusi** | ✅ Akses | ✅ Akses | ✅ Akses | ✅ Akses | ❌ Dibatasi (Read-Only) |
| **Kuis Evaluasi & Progres** | ✅ Akses | ✅ Akses | ✅ Akses | ✅ Mengerjakan | ❌ Dibatasi (Read-Only) |
| **Penerbitan Sertifikat Ber-QR** | ✅ Monitor | ✅ Monitor | ✅ Monitor | ✅ Unduh & Verifikasi | ❌ Dibatasi |
| **Course Builder & Ingest YouTube** | ✅ Semua Instansi | ✅ Instansi Sendiri | ✅ Instansi Sendiri | ❌ Dilarang | ❌ Dilarang |
| **Kurasi Visibilitas Tamu (`is_visible_to_guest`)**| ✅ Penuh (Eksklusif)| ❌ Dilarang | ❌ Dilarang | ❌ Dilarang | ❌ Dilarang |
| **Manajemen Siswa (CRUD / Import CSV)** | ✅ Semua Instansi | ✅ Instansi Sendiri | ❌ Dilarang | ❌ Dilarang | ❌ Dilarang |
| **Master Sekolah & Akun Operator** | ✅ Penuh (Eksklusif)| ❌ Dilarang | ❌ Dilarang | ❌ Dilarang | ❌ Dilarang |
| **Pengaturan Global & Log Tiket** | ✅ Penuh (Eksklusif)| ❌ Tiket Mandiri | ❌ Tiket Mandiri | ❌ Dilarang | ❌ Dilarang |

---

## 2. Flowchart End-to-End per Peran Pengguna

### A. Flowchart: Admin Pusat (`admin_pusat`)
Admin Pusat memegang kendali tata kelola tertinggi platform, mengelola sekolah mitra (seperti SMP Negeri 1 Bitung dan SMA 44 Minahasa), memvalidasi akun operator, dan secara khusus mengurasi materi publik untuk peran Penyuluh/Tamu.

```mermaid
flowchart TD
    StartAdmin([Mulai: Login Admin Pusat]) --> AuthCheck{Kredensial Valid & Role Admin Pusat?}
    AuthCheck -- Tidak --> LoginFail[Tampilkan Pesan Kesalahan] --> StartAdmin
    AuthCheck -- Ya --> AdminDash[Dasbor Eksekutif Admin Pusat]

    AdminDash --> ChoiceMenu{Pilih Menu Utama}

    %% Menu 1: Kurasi Tamu & Kursus
    ChoiceMenu --> ManageCourses[Manajer Kursus Global]
    ManageCourses --> ToggleGuest[Kurasi Materi Penyuluh / Tamu]
    ToggleGuest --> ToggleAction[Klik Toggle 'is_visible_to_guest']
    ToggleAction --> SaveGuestStatus[Perbarui Status di Database: Aktif/Terkunci]
    SaveGuestStatus --> ManageCourses

    ManageCourses --> CourseBuilder[Unified Course Builder]
    CourseBuilder --> IngestYT[Ingest Playlist / Video YouTube]
    IngestYT --> SaveModule[Simpan Silabus & Kuis]

    %% Menu 2: Master Sekolah & Operator
    ChoiceMenu --> ManageSchools[Master Data Sekolah]
    ManageSchools --> AddSchool[Tambah Sekolah Mitra Baru]
    AddSchool --> FormSchool[Input Nama, NPSN, Kota, Jenjang]
    FormSchool --> SaveSchool[(Database Sekolah)]

    ChoiceMenu --> ManageUsers[Manajemen Pengguna]
    ManageUsers --> CreateOperator[Buat Akun Operator Sekolah]
    CreateOperator --> SaveOperator[(Database Pengguna)]

    %% Menu 3: Pengaturan & Dukungan
    ChoiceMenu --> PlatformSettings[Pengaturan Sistem Global]
    PlatformSettings --> UpdateConfig[Simpan Konfigurasi & Kebijakan]

    ChoiceMenu --> SupportCenter[Pusat Layanan & Tiket Dukungan]
    SupportCenter --> RespondTicket[Balas / Selesaikan Tiket Kendala Sekolah]

    SaveModule --> EndAdmin([Selesai / Logout])
    SaveSchool --> EndAdmin
    SaveOperator --> EndAdmin
    UpdateConfig --> EndAdmin
    RespondTicket --> EndAdmin
```

---

### B. Flowchart: Operator Sekolah (`operator_sekolah`)
Operator Sekolah mengelola operasional digital di tingkat satuan pendidikan, mendaftarkan dan memantau siswa, mereset kata sandi siswa, serta memonitor kurikulum sekolahnya.

```mermaid
flowchart TD
    StartOp([Mulai: Login Operator Sekolah]) --> AuthOp{Kredensial Valid?}
    AuthOp -- Tidak --> FailOp[Peringatan Akun Tidak Cocok] --> StartOp
    AuthOp -- Ya --> OpDash[Dasbor Operator Sekolah: Berdasarkan Sekolah Terdaftar]

    OpDash --> OpActions{Pilih Aksi Manajemen}

    %% Manajemen Siswa
    OpActions --> StudentMgmt[Manajemen Siswa Sekolah]
    StudentMgmt --> StudentChoice{Metode Pendaftaran}
    StudentChoice -- Manual Satuan --> FormSingleStudent[Input Nama, Email, Alamat, No. HP, NISN]
    StudentChoice -- Masal CSV --> UploadCsv[Unggah Template CSV Siswa]
    FormSingleStudent --> SaveStudent[(Database Siswa)]
    UploadCsv --> BatchImport[Validasi & Batch Insert Akun Siswa]
    BatchImport --> SaveStudent
    StudentMgmt --> ResetPwd[Reset Kata Sandi Siswa ke Default]

    %% Manajemen Kursus Sekolah
    OpActions --> SchoolCourses[Kursus Internal Sekolah]
    SchoolCourses --> BuilderOp[Susun Silabus & Tambah Video YouTube]
    BuilderOp --> PublishOp[Publikasikan Kursus untuk Siswa]

    %% Dukungan
    OpActions --> HelpDesk[Kirim Tiket Kendala ke Admin Pusat]

    SaveStudent --> EndOp([Selesai / Logout])
    PublishOp --> EndOp
    HelpDesk --> EndOp
```

---

### C. Flowchart: Guru (`guru` / Pendidik)
Guru berfokus murni pada perancangan kurikulum, pembuatan modul, pengunggahan materi video YouTube, pembuatan bank soal kuis, dan diskusi kelas. Guru **dibatasi secara ketat** dari menu manajemen pengguna dan setelan sistem.

```mermaid
flowchart TD
    StartGuru([Mulai: Login Guru]) --> AuthGuru{Autentikasi Berhasil?}
    AuthGuru -- Tidak --> GuruFail[Gagal Masuk] --> StartGuru
    AuthGuru -- Ya --> GuruDash[Dasbor Pembelajaran Guru]

    GuruDash --> SecurityCheck{Coba Akses Menu Manajemen Siswa / Sistem?}
    SecurityCheck -- Ya --> BlockAccess[403 Forbidden: Akses Ditolak Sistem] --> GuruDash
    SecurityCheck -- Tidak --> GuruActions{Pilih Aktivitas Instruksional}

    %% Aktivitas Pembuatan Kursus
    GuruActions --> OpenBuilder[Unified Course Builder]
    OpenBuilder --> StepInfo[1. Isi Informasi, Tingkat Kesulitan, Pengajar]
    StepInfo --> StepCurriculum[2. Susun Modul & Ingest Video YouTube]
    StepCurriculum --> ParseYT[Sistem Parsing Durasi & Resolusi Otomatis]
    ParseYT --> StepQuiz[3. Buat Kuis Evaluasi, Opsi Jawaban & Pembahasan]
    StepQuiz --> SetPassing[Tentukan Standar Kelulusan / Passing Grade]
    SetPassing --> ValidatePublish{Validasi Persyaratan Terbit}
    ValidatePublish -- Belum Lengkap --> WarningIncomplete[Perbaiki Modul / Soal Kuis] --> StepCurriculum
    ValidatePublish -- Lengkap --> PublishCourse[Terbitkan Kursus untuk Siswa]

    %% Bimbingan & Diskusi
    GuruActions --> ForumDiscussion[Pantau Forum Diskusi Materi]
    ForumDiscussion --> ReplyStudent[Beri Masukan & Jawaban Diskusi]

    PublishCourse --> EndGuru([Selesai / Logout])
    ReplyStudent --> EndGuru
```

---

### D. Flowchart: Siswa (`siswa` / Pembelajar)
Siswa dapat mendaftar secara mandiri melalui form registrasi baru (dengan nama lengkap, alamat, email, dan nomor HP), menjelajahi materi SSK, menonton video terstruktur, membuat catatan, mengerjakan kuis, dan mengunduh sertifikat kelulusan ber-QR Code.

```mermaid
flowchart TD
    StartSiswa([Calon Siswa / Siswa Terdaftar]) --> HasAccount{Sudah Punya Akun?}

    %% Jalur Registrasi Mandiri
    HasAccount -- Belum --> RegPage[Buka Halaman /register]
    RegPage --> InputForm[Isi: Nama Lengkap, Alamat, Email, No. HP, Kata Sandi]
    InputForm --> SubmitReg[Kirim Form Registrasi]
    SubmitReg --> ValidateReg{Validasi Input Berhasil?}
    ValidateReg -- Gagal --> ShowRegError[Tampilkan Error Form] --> InputForm
    ValidateReg -- Berhasil --> AutoLogin[Akun Terdaftar & Otomatis Masuk]

    %% Jalur Login
    HasAccount -- Sudah --> LoginPage[Buka Halaman /login]
    LoginPage --> AuthSiswa{Kredensial Sesuai?}
    AuthSiswa -- Gagal --> LoginError[Email/Sandi Salah] --> LoginPage
    AuthSiswa -- Sukses --> SiswaDash[Dasbor Pembelajar Siswa]
    AutoLogin --> SiswaDash

    %% Aktivitas Belajar
    SiswaDash --> ExploreCatalog[Jelajahi Katalog Kursus SSK & Siaga Bumi]
    ExploreCatalog --> SelectCourse[Pilih Kursus Kependudukan / Lingkungan]
    SelectCourse --> Classroom[Masuk Ruang Kelas Digital]

    Classroom --> StreamVideo[Tonton Video YouTube Bebas Distraksi]
    StreamVideo --> TakeNotes[Tulis Catatan Sesuai Detik Video Penting]
    StreamVideo --> AskComment[Tanya / Diskusi di Kolom Komentar Modul]
    StreamVideo --> TrackProgress[Pencatatan Progres Menit Belajar Otomatis]

    TrackProgress --> FinishAllVideos{Semua Video Bab Telah Tuntas?}
    FinishAllVideos -- Belum --> NextVideo[Lanjutkan ke Modul Berikutnya] --> StreamVideo
    FinishAllVideos -- Ya --> TakeQuiz[Kerjakan Evaluasi Kuis Terintegrasi]

    TakeQuiz --> CheckScore{Skor >= Passing Grade 70%?}
    CheckScore -- Belum Lulus --> RetakeQuiz[Pelajari Ulang Video & Remedial Kuis] --> TakeQuiz
    CheckScore -- Lulus --> IssueCert[Sistem Terbitkan E-Sertifikat Resmi Ber-QR Code]

    IssueCert --> ViewCert[Buka Halaman Validasi Sertifikat /sertifikat/KODE]
    ViewCert --> DownloadPdf[Unduh PDF / Cetak Sertifikat Bersegel]
    DownloadPdf --> EndSiswa([Selesai Belajar])
```

---

### E. Flowchart: Penyuluh / Tamu (`penyuluh` / `guest`)
Peran Penyuluh dan Tamu difungsikan dalam **Mode Galeri (Lihat-Saja)** untuk meninjau materi sosialisasi BKKBN dan SSK yang telah dikurasi oleh Admin Pusat tanpa dapat mengubah data progres atau kuis.

```mermaid
flowchart TD
    StartGuest([Penyuluh BKKBN / Tamu Publik]) --> LoginGuest[Masuk Akun Penyuluh / Akses Mode Galeri]
    LoginGuest --> CheckCuratedCatalog[Buka Katalog Pembelajaran Galeri]

    CheckCuratedCatalog --> FilterVisible[Sistem Hanya Menampilkan Kursus dengan is_visible_to_guest = true]
    FilterVisible --> BrowseCourses[Jelajahi Modul BKKBN, SSK & Siaga Bumi Pilihan]

    BrowseCourses --> ClickCourse{Pilih Kursus Terpilih}
    ClickCourse --> CheckVisibility{Apakah Kursus Diizinkan Admin Pusat?}
    CheckVisibility -- Tidak --> BlockGuest[403 Forbidden: Materi Khusus Siswa Internal] --> BrowseCourses
    CheckVisibility -- Ya --> OpenPreviewClass[Buka Ruang Kelas dalam Mode Galeri Preview]

    OpenPreviewClass --> ReadVideo[Tonton Video Paparan Edukasi]
    OpenPreviewClass --> ReadOverview[Baca Silabus & Capaian Pembelajaran]

    %% Batasan Read-Only
    OpenPreviewClass --> AttemptAction{Coba Aksi Interaktif?}
    AttemptAction -- Kirim Komentar --> Restrict1[403: Mode Galeri Bersifat Lihat-Saja] --> OpenPreviewClass
    AttemptAction -- Buat Catatan --> Restrict2[403: Mode Galeri Bersifat Lihat-Saja] --> OpenPreviewClass
    AttemptAction -- Mengerjakan Kuis --> Restrict3[403: Evaluasi Khusus Siswa Terdaftar] --> OpenPreviewClass
    AttemptAction -- Selesai Menonton --> EndGuest([Selesai Tinjauan])
```

---

## 3. Ringkasan Integritas Keamanan & Middleware

1. **`RoleMiddleware`**: Memeriksa token sesi terhadap hierarki peran pengguna (`admin_pusat`, `operator_sekolah`, `guru`, `siswa`, `penyuluh`).
2. **Scoping Multi-Tenancy**: Operator Sekolah dan Guru dibatasi hanya pada lingkup data sekolah mereka masing-masing (`school_name`).
3. **Guard Mode Galeri**: Seluruh endpoint mutasi (`storeNote`, `storeComment`, `toggleBookmark`, `toggleCompletion`, `saveProgress`, `submitQuiz`) mengeksekusi `abort(403)` jika diakses oleh akun `isGuestRole()`.
4. **Validasi QR Code Publik**: Halaman `/sertifikat/{code}` terbuka untuk pemindaian instan oleh masyarakat atau instansi guna memverifikasi keabsahan tanda kelulusan.
