<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\CommentLike;
use App\Models\Course;
use App\Models\CourseBookmark;
use App\Models\Lesson;
use App\Models\LessonComment;
use App\Models\LessonCompletion;
use App\Models\LessonNote;
use App\Models\LessonResource;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with SIAGA BUMI-SSK data:
     * - Only schools originating from Manado, Bitung, & Minahasa (North Sulawesi).
     * - Only 2 learning materials: "Mengenai BKKBN" and "Apa sih SSK?".
     * - Only 3 official categories: "Materi Umum Kependudukan", "Materi Umum Lingkungan", "Kekerasan Sexual".
     */
    public function run(): void
    {
        // 0. Clean up foreign/non-Manado schools, non-SSK categories, and other courses
        School::whereIn('name', ['SMKN 1 Jakarta', 'SMAN 2 Bandung'])->delete();
        Category::whereNotIn('slug', ['materi-umum-kependudukan', 'materi-umum-lingkungan', 'kekerasan-sexual'])->delete();
        Course::whereNotIn('slug', ['mengenai-bkkbn-peran-kependudukan', 'apa-sih-ssk-sekolah-siaga-kependudukan'])->delete();
        Lesson::whereNotIn('youtube_video_id', ['UCGVFxAa7nA', 'EA3wO1h8EX0', '6bGlT6CVohg', 'x8PgjAKr-h8'])->delete();

        // 1. Seed Master Schools (Only Manado area schools)
        $schoolCentral = School::updateOrCreate(
            ['name' => 'BKKBN Perwakilan Sulawesi Utara'],
            [
                'npsn' => '70000001',
                'slug' => 'bkkbn-perwakilan-sulawesi-utara',
                'address' => 'Jl. 17 Agustus No. 69, Wanea, Kota Manado, Sulawesi Utara',
                'is_active' => true,
            ]
        );

        $schoolBitung1 = School::updateOrCreate(
            ['name' => 'SMP Negeri 1 Bitung'],
            [
                'npsn' => '40101511',
                'slug' => 'smp-negeri-1-bitung',
                'address' => 'Jl. Sam Ratulangi No. 12, Pakadoodan, Madidir, Kota Bitung, Sulawesi Utara',
                'is_active' => true,
            ]
        );

        $schoolMinahasa44 = School::updateOrCreate(
            ['name' => 'Sekolah Rakyat Menengah Atas 44 Minahasa'],
            [
                'npsn' => '40104444',
                'slug' => 'sekolah-rakyat-menengah-atas-44-minahasa',
                'address' => 'Jl. Raya Tanawangko No. 44, Tombariri, Kabupaten Minahasa, Sulawesi Utara',
                'is_active' => true,
            ]
        );

        $schoolManado1 = School::updateOrCreate(
            ['name' => 'SMA Negeri 1 Manado'],
            [
                'npsn' => '40102685',
                'slug' => 'sma-negeri-1-manado',
                'address' => 'Jl. Sam Ratulangi No. 106, Wenang Selatan, Kota Manado, Sulawesi Utara',
                'is_active' => true,
            ]
        );

        // =========================================================================
        // 2. RBAC USERS (5 DISTINCT ROLES: Admin Pusat, Operator Sekolah, Guru, Siswa, Penyuluh/Guest)
        // All assigned strictly to Manado area schools
        // =========================================================================

        // Role 1: Admin Pusat (Central Admin / Super Admin)
        $adminPusat = User::updateOrCreate(
            ['email' => 'adminpusat@siagabumi.id'],
            [
                'name' => 'Dr. Hendra Sompotan, M.Si',
                'password' => Hash::make('password'),
                'role' => 'admin_pusat',
                'address' => 'Jl. 17 Agustus No. 69, Wanea, Kota Manado',
                'phone' => '08114321001',
                'school_id' => $schoolCentral->id,
                'school_name' => $schoolCentral->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $admin = User::updateOrCreate(
            ['email' => 'superadmin@edutube.org'],
            [
                'name' => 'Alex Vance, PhD',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'address' => 'Jl. 17 Agustus No. 69, Wanea, Kota Manado',
                'phone' => '08114321000',
                'school_id' => $schoolCentral->id,
                'school_name' => $schoolCentral->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // Role 2: Operator Sekolah (School Operator)
        $operatorBitung = User::updateOrCreate(
            ['email' => 'operator.bitung@siagabumi.id'],
            [
                'name' => 'Meivy Karundeng, S.Kom',
                'password' => Hash::make('password'),
                'role' => 'operator_sekolah',
                'address' => 'Jl. Sam Ratulangi No. 12, Kota Bitung',
                'phone' => '081244001122',
                'school_id' => $schoolBitung1->id,
                'school_name' => $schoolBitung1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $schoolAdmin1 = User::updateOrCreate(
            ['email' => 'smkn1@edutube.org'],
            [
                'name' => 'Bambang S., S.Kom',
                'password' => Hash::make('password'),
                'role' => 'school_admin',
                'address' => 'Jl. Sam Ratulangi No. 12, Kota Bitung',
                'phone' => '081234567890',
                'school_id' => $schoolBitung1->id,
                'school_name' => $schoolBitung1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $schoolAdmin2 = User::updateOrCreate(
            ['email' => 'sman2@edutube.org'],
            [
                'name' => 'Rina Wijaya, M.T',
                'password' => Hash::make('password'),
                'role' => 'school_admin',
                'address' => 'Jl. Raya Tanawangko No. 44, Minahasa',
                'phone' => '081298765432',
                'school_id' => $schoolMinahasa44->id,
                'school_name' => $schoolMinahasa44->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // Role 3: Guru (Teacher) - Has course management but NO user/school management
        $guruMinahasa = User::updateOrCreate(
            ['email' => 'guru.minahasa@siagabumi.id'],
            [
                'name' => 'Ibu Martha Runtuwene, S.Pd',
                'password' => Hash::make('password'),
                'role' => 'guru',
                'address' => 'Jl. Raya Tanawangko No. 44, Tombariri, Minahasa',
                'phone' => '081355667788',
                'school_id' => $schoolMinahasa44->id,
                'school_name' => $schoolMinahasa44->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // Role 4: Siswa (Student / Learner)
        $siswaBitung = User::updateOrCreate(
            ['email' => 'siswa.bitung@siagabumi.id'],
            [
                'name' => 'Reza Mokodongan',
                'password' => Hash::make('password'),
                'role' => 'siswa',
                'address' => 'Jl. Girian Indah No. 5, Kota Bitung',
                'phone' => '085299887766',
                'school_id' => $schoolBitung1->id,
                'school_name' => $schoolBitung1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $student1 = User::updateOrCreate(
            ['email' => 'student@edutube.org'],
            [
                'name' => 'Sarah Connor',
                'password' => Hash::make('password'),
                'role' => 'student',
                'address' => 'Jl. Sam Ratulangi No. 12, Bitung',
                'phone' => '085200112233',
                'school_id' => $schoolBitung1->id,
                'school_name' => $schoolBitung1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $studentElena = User::updateOrCreate(
            ['email' => 'elena@edutube.org'],
            [
                'name' => 'Elena Rostova',
                'password' => Hash::make('password'),
                'role' => 'student',
                'address' => 'Jl. Bitung Barat No. 8, Bitung',
                'phone' => '085211223344',
                'school_id' => $schoolBitung1->id,
                'school_name' => $schoolBitung1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $studentBudi = User::updateOrCreate(
            ['email' => 'budi@edutube.org'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'student',
                'address' => 'Jl. Tanawangko No. 20, Minahasa',
                'phone' => '085233445566',
                'school_id' => $schoolMinahasa44->id,
                'school_name' => $schoolMinahasa44->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // Role 5: Penyuluh / Guest (Counselor or Public Guest - Gallery Mode View-Only)
        $penyuluh = User::updateOrCreate(
            ['email' => 'penyuluh@siagabumi.id'],
            [
                'name' => 'Drs. Ronald Lumempouw (Penyuluh KB)',
                'password' => Hash::make('password'),
                'role' => 'penyuluh',
                'address' => 'Wenang, Kota Manado, Sulawesi Utara',
                'phone' => '08114300998',
                'school_id' => $schoolCentral->id,
                'school_name' => $schoolCentral->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $guestUser = User::updateOrCreate(
            ['email' => 'guest@siagabumi.id'],
            [
                'name' => 'Tamu Pengunjung SSK',
                'password' => Hash::make('password'),
                'role' => 'guest',
                'address' => 'Kota Manado',
                'phone' => '081100223344',
                'school_id' => null,
                'school_name' => 'Masyarakat Umum',
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // =========================================================================
        // 3. LEARNING MATERIAL CATEGORIES (3 EXACT REQUIRED CATEGORIES)
        // =========================================================================
        $catKependudukan = Category::updateOrCreate(
            ['slug' => 'materi-umum-kependudukan'],
            [
                'name' => 'Materi Umum Kependudukan',
                'description' => 'Edukasi dinamika kependudukan, piramida penduduk, bonus demografi, dan keluarga berencana.',
            ]
        );

        $catLingkungan = Category::updateOrCreate(
            ['slug' => 'materi-umum-lingkungan'],
            [
                'name' => 'Materi Umum Lingkungan',
                'description' => 'Kesadaran ekologis, perubahan iklim, pengelolaan sampah, konservasi air dan keanekaragaman hayati.',
            ]
        );

        $catKekerasanSexual = Category::updateOrCreate(
            ['slug' => 'kekerasan-sexual'],
            [
                'name' => 'Kekerasan Sexual',
                'description' => 'Pencegahan dan Penanganan Kekerasan Seksual (PPKS), perlindungan anak, dan hak kesehatan reproduksi.',
            ]
        );

        // =========================================================================
        // 4. LEARNING MATERIALS / COURSES (EXACTLY 2 COURSES)
        // 1. Mengenai BKKBN
        // 2. Apa sih SSK?
        // =========================================================================

        // Course 1: Mengenai BKKBN (School: SMP Negeri 1 Bitung | Visible to Guest: true)
        $courseBkkbn = Course::updateOrCreate(
            ['slug' => 'mengenai-bkkbn-peran-kependudukan'],
            [
                'category_id' => $catKependudukan->id,
                'school_id' => $schoolBitung1->id,
                'created_by' => $adminPusat->id,
                'school_name' => 'SMP Negeri 1 Bitung',
                'title' => 'Mengenai BKKBN',
                'description' => 'Mengenal tugas pokok, fungsi, dan visi strategis BKKBN (Kementerian Kependudukan dan Pembangunan Keluarga) dalam pembinaan ketahanan keluarga, pencegahan stunting, serta pembentukan Generasi Berencana (GenRe) yang tangguh dan berkualitas.',
                'instructor_name' => 'Drs. Ronald Lumempouw',
                'instructor_title' => 'Koordinator Penyuluh KB • Perwakilan BKKBN Sulut',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/UCGVFxAa7nA/maxresdefault.jpg',
                'status' => 'published',
                'is_visible_to_guest' => true,
                'level' => 'pemula',
            ]
        );

        // Course 2: Apa sih SSK? (School: SRMA 44 Minahasa | Visible to Guest: false for Admin curation)
        $courseSsk = Course::updateOrCreate(
            ['slug' => 'apa-sih-ssk-sekolah-siaga-kependudukan'],
            [
                'category_id' => $catLingkungan->id,
                'school_id' => $schoolMinahasa44->id,
                'created_by' => $guruMinahasa->id,
                'school_name' => 'Sekolah Rakyat Menengah Atas 44 Minahasa',
                'title' => 'Apa sih SSK?',
                'description' => 'Panduan lengkap pemahaman Sekolah Siaga Kependudukan (SSK), integrasi materi kependudukan ke dalam kurikulum pembelajaran, pembentukan Pojok Kependudukan (Population Corner), dan aksi peduli lingkungan Siaga Bumi.',
                'instructor_name' => 'Ibu Martha Runtuwene, S.Pd',
                'instructor_title' => 'Pembina Program SSK • Minahasa & Bitung',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/6bGlT6CVohg/maxresdefault.jpg',
                'status' => 'published',
                'is_visible_to_guest' => false,
                'level' => 'pemula',
            ]
        );

        // =========================================================================
        // 5. LESSONS FOR COURSE 1: Mengenai BKKBN
        // Real YouTube Videos: BKKBN Official & Kemendukbangga
        // =========================================================================
        $bkkbnLessons = [
            [
                'module_title' => 'Bab 1: Profil & Tugas Pokok BKKBN',
                'title' => '1.1: Makna & Filosofi Kelembagaan BKKBN',
                'youtube_url' => 'https://www.youtube.com/watch?v=UCGVFxAa7nA',
                'youtube_video_id' => 'UCGVFxAa7nA',
                'duration_seconds' => 480,
                'duration_formatted' => '08:00',
                'sort_order' => 1,
                'description' => 'Memahami transformasi, visi kelembagaan, serta makna logo dan komitmen baru BKKBN dalam melayani keluarga Indonesia yang berkualitas.',
            ],
            [
                'module_title' => 'Bab 1: Profil & Tugas Pokok BKKBN',
                'title' => '1.2: Program Bangga Kencana & Percepatan Penurunan Stunting',
                'youtube_url' => 'https://www.youtube.com/watch?v=EA3wO1h8EX0',
                'youtube_video_id' => 'EA3wO1h8EX0',
                'duration_seconds' => 720,
                'duration_formatted' => '12:00',
                'sort_order' => 2,
                'description' => 'Edukasi strategis mengenai pilar Pembangunan Keluarga, Kependudukan, dan Keluarga Berencana (Bangga Kencana) serta aksi percepatan penurunan stunting.',
            ],
        ];

        foreach ($bkkbnLessons as $lData) {
            Lesson::updateOrCreate(
                ['course_id' => $courseBkkbn->id, 'youtube_video_id' => $lData['youtube_video_id']],
                array_merge($lData, [
                    'thumbnail_url' => "https://img.youtube.com/vi/{$lData['youtube_video_id']}/hqdefault.jpg",
                    'status' => 'published',
                ])
            );
        }

        // =========================================================================
        // 6. LESSONS FOR COURSE 2: Apa sih SSK?
        // Real YouTube Videos: Ditpenduk & BKKBN Official SSK
        // =========================================================================
        $sskLessons = [
            [
                'module_title' => 'Modul 1: Konsep Dasar SSK',
                'title' => '1.1: Apa Sih Sekolah Siaga Kependudukan (SSK)?',
                'youtube_url' => 'https://www.youtube.com/watch?v=6bGlT6CVohg',
                'youtube_video_id' => '6bGlT6CVohg',
                'duration_seconds' => 540,
                'duration_formatted' => '09:00',
                'sort_order' => 1,
                'description' => 'Menjelaskan konsep dasar Sekolah Siaga Kependudukan (SSK), tujuan integrasi materi kependudukan ke dalam mata pelajaran sekolah, serta urgensi kepedulian remaja.',
            ],
            [
                'module_title' => 'Modul 1: Konsep Dasar SSK',
                'title' => '1.2: Panduan Pengenalan & Pojok Kependudukan SSK',
                'youtube_url' => 'https://www.youtube.com/watch?v=x8PgjAKr-h8',
                'youtube_video_id' => 'x8PgjAKr-h8',
                'duration_seconds' => 660,
                'duration_formatted' => '11:00',
                'sort_order' => 2,
                'description' => 'Tata cara pembentukan Pojok Kependudukan (Population Corner) di sekolah sebagai sentra literasi, sarana konseling sebaya, dan media integrasi edukasi lingkungan Siaga Bumi.',
            ],
        ];

        foreach ($sskLessons as $lData) {
            Lesson::updateOrCreate(
                ['course_id' => $courseSsk->id, 'youtube_video_id' => $lData['youtube_video_id']],
                array_merge($lData, [
                    'thumbnail_url' => "https://img.youtube.com/vi/{$lData['youtube_video_id']}/hqdefault.jpg",
                    'status' => 'published',
                ])
            );
        }

        // =========================================================================
        // 7. LESSON RESOURCES (PDF & LINKS)
        // =========================================================================
        $lessonBkkbn1 = Lesson::where('course_id', $courseBkkbn->id)->orderBy('sort_order')->first();
        if ($lessonBkkbn1) {
            LessonResource::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'title' => 'Buku Panduan Program Bangga Kencana BKKBN PDF'],
                [
                    'type' => 'pdf',
                    'url' => 'https://www.bkkbn.go.id/',
                ]
            );

            LessonResource::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'title' => 'Portal Resmi BKKBN Perwakilan Sulawesi Utara'],
                [
                    'type' => 'link',
                    'url' => 'https://sulut.bkkbn.go.id/',
                ]
            );
        }

        $lessonSsk1 = Lesson::where('course_id', $courseSsk->id)->orderBy('sort_order')->first();
        if ($lessonSsk1) {
            LessonResource::updateOrCreate(
                ['lesson_id' => $lessonSsk1->id, 'title' => 'Petunjuk Teknis Sekolah Siaga Kependudukan (SSK) Kemdikbud & BKKBN'],
                [
                    'type' => 'pdf',
                    'url' => 'https://bkkbn.go.id/panduan-ssk',
                ]
            );

            LessonResource::updateOrCreate(
                ['lesson_id' => $lessonSsk1->id, 'title' => 'Panduan Pengelolaan Pojok Kependudukan di Sekolah'],
                [
                    'type' => 'link',
                    'url' => 'https://bkkbn.go.id/pojok-kependudukan',
                ]
            );
        }

        // =========================================================================
        // 8. DISCUSSION COMMENTS & REPLIES
        // =========================================================================
        if ($lessonBkkbn1) {
            $parentComment1 = LessonComment::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'content' => 'Bagaimana peran siswa sekolah menengah dalam mendukung program penurunan stunting BKKBN di lingkungan sekitar?'],
                [
                    'user_id' => $studentElena->id,
                    'user_name' => 'Elena Rostova',
                    'user_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 04:15',
                    'likes_count' => 5,
                    'parent_id' => null,
                ]
            );

            LessonComment::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'parent_id' => $parentComment1->id],
                [
                    'user_id' => $adminPusat->id,
                    'user_name' => 'Dr. Hendra Sompotan, M.Si',
                    'user_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 05:30',
                    'content' => 'Pertanyaan sangat bagus Elena! Siswa dapat menjadi Pendidik Sebaya (Peer Educator) dalam menyebarkan pemahaman gizi seimbang dan pencegahan anemia.',
                    'likes_count' => 3,
                ]
            );

            CommentLike::firstOrCreate([
                'user_id' => $student1->id,
                'lesson_comment_id' => $parentComment1->id,
            ]);
        }

        // =========================================================================
        // 9. STUDENT NOTES
        // =========================================================================
        if ($lessonBkkbn1) {
            LessonNote::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'user_id' => $student1->id, 'timestamp_seconds' => 180],
                ['content' => 'Catatan: Tiga pilar utama BKKBN adalah Kependudukan, Keluarga Berencana, dan Pembangunan Keluarga.']
            );
            LessonNote::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'user_id' => $student1->id, 'timestamp_seconds' => 450],
                ['content' => 'Penting: Program GenRe mengajak remaja merencanakan pendidikan, karir, dan pernikahan secara matang.']
            );
        }

        // =========================================================================
        // 10. INTERACTIVE QUIZZES & ATTEMPTS
        // =========================================================================
        if ($lessonBkkbn1) {
            $qb1 = QuizQuestion::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'question' => 'Apa kepanjangan resmi dan mandat utama dari BKKBN?'],
                [
                    'options' => [
                        'Badan Kependudukan dan Keluarga Berencana Nasional',
                        'Badan Koordinasi Kesejahteraan Bersama Nasional',
                        'Badan Ketahanan Pangan dan Kependudukan Nasional',
                        'Balai Konseling dan Keluarga Berencana Nasional',
                    ],
                    'correct_answer_index' => 0,
                    'explanation' => 'BKKBN adalah Badan Kependudukan dan Keluarga Berencana Nasional yang bertugas melaksanakan pengendalian penduduk dan penyelenggaraan keluarga berencana.',
                ]
            );

            $qb2 = QuizQuestion::updateOrCreate(
                ['lesson_id' => $lessonBkkbn1->id, 'question' => 'Apa tujuan utama dari program Generasi Berencana (GenRe) yang dibina oleh BKKBN?'],
                [
                    'options' => [
                        'Membatasi jumlah anak secara paksa',
                        'Membekali remaja untuk merencanakan masa depan, pendidikan, karir, dan pernikahan matang',
                        'Menghentikan perkawinan bagi seluruh pemuda',
                        'Mengharuskan remaja bekerja sebelum lulus sekolah',
                    ],
                    'correct_answer_index' => 1,
                    'explanation' => 'Program GenRe bertujuan mengembangkan karakter bangsa dengan mengajarkan remaja merencanakan masa depan dan menghindari risiko Triad KRR.',
                ]
            );

            QuizAttempt::updateOrCreate(
                ['user_id' => $student1->id, 'lesson_id' => $lessonBkkbn1->id],
                [
                    'score' => 100,
                    'passed' => true,
                    'answers' => [
                        $qb1->id => 0,
                        $qb2->id => 1,
                    ],
                ]
            );
        }

        if ($lessonSsk1) {
            $qs1 = QuizQuestion::updateOrCreate(
                ['lesson_id' => $lessonSsk1->id, 'question' => 'Apa fungsi utama dari Pojok Kependudukan (Population Corner) di Sekolah Siaga Kependudukan?'],
                [
                    'options' => [
                        'Sebagai ruang pertemuan staf guru saja',
                        'Sebagai pusat informasi, literasi, perpustakaan mini, dan diskusi sebaya tentang kependudukan',
                        'Sebagai tempat penyimpanan alat olahraga sekolah',
                        'Sebagai ruang bimbingan konseling khusus pelanggaran disiplin',
                    ],
                    'correct_answer_index' => 1,
                    'explanation' => 'Pojok Kependudukan adalah sarana edukasi dan diskusi interaktif bagi siswa mengenai dinamika kependudukan dan isu lingkungan sekitar.',
                ]
            );

            QuizAttempt::updateOrCreate(
                ['user_id' => $studentBudi->id, 'lesson_id' => $lessonSsk1->id],
                [
                    'score' => 100,
                    'passed' => true,
                    'answers' => [
                        $qs1->id => 0,
                    ],
                ]
            );

            QuizAttempt::updateOrCreate(
                ['user_id' => $student1->id, 'lesson_id' => $lessonSsk1->id],
                [
                    'score' => 100,
                    'passed' => true,
                    'answers' => [
                        $qs1->id => 0,
                    ],
                ]
            );
        }

        // =========================================================================
        // 11. LESSON COMPLETIONS & CERTIFICATES
        // =========================================================================
        // A. Sarah Connor ($student1) completes lessons in Course 1
        $bkkbnLessonsList = Lesson::where('course_id', $courseBkkbn->id)->take(2)->get();
        foreach ($bkkbnLessonsList as $idx => $lesson) {
            LessonCompletion::updateOrCreate(
                [
                    'user_id' => $student1->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'completed_at' => now()->subHours(24 - ($idx * 4)),
                    'last_watched_second' => min(300 + ($idx * 60), $lesson->duration_seconds ?? 600),
                ]
            );
        }

        // B. Budi Pratama ($studentBudi) completes 100% of Course 2 (Apa sih SSK? - SRMA 44 Minahasa)
        $courseSskLessonsList = Lesson::where('course_id', $courseSsk->id)->get();
        foreach ($courseSskLessonsList as $lesson) {
            LessonCompletion::updateOrCreate(
                [
                    'user_id' => $studentBudi->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'completed_at' => now()->subDays(2),
                    'last_watched_second' => $lesson->duration_seconds ?? 600,
                ]
            );
        }

        Certificate::updateOrCreate(
            [
                'user_id' => $studentBudi->id,
                'course_id' => $courseSsk->id,
            ],
            [
                'certificate_code' => 'CERT-SSK-BUDI-'.strtoupper(substr(md5('budi-ssk'), 0, 8)),
                'issued_at' => now()->subDays(2),
            ]
        );

        // C. Sarah Connor ($student1) also completes 100% of Course 2 (cross-school certificate)
        foreach ($courseSskLessonsList as $lesson) {
            LessonCompletion::updateOrCreate(
                [
                    'user_id' => $student1->id,
                    'lesson_id' => $lesson->id,
                ],
                [
                    'completed_at' => now()->subDay(),
                    'last_watched_second' => $lesson->duration_seconds ?? 600,
                ]
            );
        }

        Certificate::updateOrCreate(
            [
                'user_id' => $student1->id,
                'course_id' => $courseSsk->id,
            ],
            [
                'certificate_code' => 'CERT-SSK-SARAH-'.strtoupper(substr(md5('sarah-ssk'), 0, 8)),
                'issued_at' => now()->subDay(),
            ]
        );

        // =========================================================================
        // 12. COURSE BOOKMARKS
        // =========================================================================
        CourseBookmark::firstOrCreate([
            'user_id' => $student1->id,
            'course_id' => $courseBkkbn->id,
        ]);
        CourseBookmark::firstOrCreate([
            'user_id' => $student1->id,
            'course_id' => $courseSsk->id,
        ]);
        CourseBookmark::firstOrCreate([
            'user_id' => $studentBudi->id,
            'course_id' => $courseSsk->id,
        ]);
    }
}
