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
     * Seed the application's database with realistic EduTube LMS data across multiple schools.
     */
    public function run(): void
    {
        // 0. Seed Master Schools (Tenants)
        $schoolCentral = School::updateOrCreate(
            ['name' => 'Kementerian / Pusat EduTube'],
            [
                'npsn' => '10000000',
                'slug' => 'kementerian-pusat-edutube',
                'address' => 'Gedung D Kemendikbudristek, Senayan, Jakarta Pusat',
                'is_active' => true,
            ]
        );

        $schoolSmkn1 = School::updateOrCreate(
            ['name' => 'SMKN 1 Jakarta'],
            [
                'npsn' => '20100001',
                'slug' => 'smkn-1-jakarta',
                'address' => 'Jl. Budi Utomo No. 7, Pasar Baru, Sawah Besar, Jakarta Pusat',
                'is_active' => true,
            ]
        );

        $schoolSman2 = School::updateOrCreate(
            ['name' => 'SMAN 2 Bandung'],
            [
                'npsn' => '20200002',
                'slug' => 'sman-2-bandung',
                'address' => 'Jl. Cihampelas No. 173, Cipaganti, Coblong, Kota Bandung',
                'is_active' => true,
            ]
        );

        // 1. RBAC Users (Super Admin, School Admins, and Students)
        $admin = User::updateOrCreate(
            ['email' => 'superadmin@edutube.org'],
            [
                'name' => 'Alex Vance, PhD',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'school_id' => $schoolCentral->id,
                'school_name' => $schoolCentral->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // School 1: SMKN 1 Jakarta
        $schoolAdmin1 = User::updateOrCreate(
            ['email' => 'smkn1@edutube.org'],
            [
                'name' => 'Bambang S., S.Kom',
                'password' => Hash::make('password'),
                'role' => 'school_admin',
                'school_id' => $schoolSmkn1->id,
                'school_name' => $schoolSmkn1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $student1 = User::updateOrCreate(
            ['email' => 'student@edutube.org'],
            [
                'name' => 'Sarah Connor',
                'password' => Hash::make('password'),
                'role' => 'student',
                'school_id' => $schoolSmkn1->id,
                'school_name' => $schoolSmkn1->name,
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
                'school_id' => $schoolSmkn1->id,
                'school_name' => $schoolSmkn1->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // School 2: SMAN 2 Bandung
        $schoolAdmin2 = User::updateOrCreate(
            ['email' => 'sman2@edutube.org'],
            [
                'name' => 'Rina Wijaya, M.T',
                'password' => Hash::make('password'),
                'role' => 'school_admin',
                'school_id' => $schoolSman2->id,
                'school_name' => $schoolSman2->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
            ]
        );

        $studentBudi = User::updateOrCreate(
            ['email' => 'budi@edutube.org'],
            [
                'name' => 'Budi Pratama',
                'password' => Hash::make('password'),
                'role' => 'student',
                'school_id' => $schoolSman2->id,
                'school_name' => $schoolSman2->name,
                'is_active' => true,
                'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
            ]
        );

        // 2. Categories
        $frontend = Category::firstOrCreate(
            ['slug' => 'frontend'],
            ['name' => 'Frontend', 'description' => 'Rekayasa antarmuka web modern, SSR, dan framework reaktif']
        );

        $backend = Category::firstOrCreate(
            ['slug' => 'backend'],
            ['name' => 'Backend & Arsitektur', 'description' => 'Perancangan API skalabel, transaksi database, dan mikroservis']
        );

        $devops = Category::firstOrCreate(
            ['slug' => 'devops'],
            ['name' => 'Cloud & DevOps', 'description' => 'Kontainerisasi Docker, Kubernetes, dan otomatisasi pipeline CI/CD']
        );

        $algo = Category::firstOrCreate(
            ['slug' => 'algoritma'],
            ['name' => 'Algoritma & Sains Komputer', 'description' => 'Struktur data kompleks, optimasi algoritma, dan problem solving']
        );

        // 3. Courses Scoped to Schools
        // Course 1: SMKN 1 Jakarta
        $course1 = Course::firstOrCreate(
            ['slug' => 'rekayasa-web-fullstack-nextjs-typescript'],
            [
                'category_id' => $frontend->id,
                'created_by' => $schoolAdmin1->id,
                'school_name' => 'SMKN 1 Jakarta',
                'title' => 'Rekayasa Web Full-Stack dengan Next.js & TypeScript',
                'description' => 'Pelajari arsitektur Next.js App Router, React 19 Server Components, Streaming SSR, dan optimasi Server Actions dengan performa enterprise.',
                'instructor_name' => 'Bambang S., S.Kom',
                'instructor_title' => 'Ketua Jurusan RPL • SMKN 1 Jakarta',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/wm5gMKuwSYk/maxresdefault.jpg',
                'status' => 'published',
            ]
        );

        // Course 2: SMAN 2 Bandung
        $course2 = Course::firstOrCreate(
            ['slug' => 'perancangan-sistem-modern-skala-besar'],
            [
                'category_id' => $backend->id,
                'created_by' => $schoolAdmin2->id,
                'school_name' => 'SMAN 2 Bandung',
                'title' => 'Perancangan Sistem Modern dalam Skala Besar',
                'description' => 'Membangun sistem terdistribusi tangguh: load balancer, caching layer (Redis), partitioning database, dan message broker Kafka.',
                'instructor_name' => 'Rina Wijaya, M.T',
                'instructor_title' => 'Kepala Lab TI • SMAN 2 Bandung',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/bMknfKXIFA8/maxresdefault.jpg',
                'status' => 'published',
            ]
        );

        // Course 3: SMKN 1 Jakarta
        $course3 = Course::firstOrCreate(
            ['slug' => 'struktur-data-algoritma-tingkat-lanjut'],
            [
                'category_id' => $algo->id,
                'created_by' => $schoolAdmin1->id,
                'school_name' => 'SMKN 1 Jakarta',
                'title' => 'Struktur Data & Algoritma Tingkat Lanjut',
                'description' => 'Kuasai pohon biner pencarian berimbang (AVL/Red-Black), algoritma graf (Dijkstra, Tarjan), dan pemrograman dinamis.',
                'instructor_name' => 'Bambang S., S.Kom',
                'instructor_title' => 'Instruktur Utama • SMKN 1 Jakarta',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/SqcY0GlETPk/maxresdefault.jpg',
                'status' => 'published',
            ]
        );

        // Course 4: SMAN 2 Bandung
        $course4 = Course::firstOrCreate(
            ['slug' => 'dasar-dasar-kontainerisasi-docker-kubernetes'],
            [
                'category_id' => $devops->id,
                'created_by' => $schoolAdmin2->id,
                'school_name' => 'SMAN 2 Bandung',
                'title' => 'Dasar-Dasar Kontainerisasi Docker & Kubernetes',
                'description' => 'Bangun Dockerfile yang aman, multi-stage builds, orkestrasi container dengan Docker Compose, dan deployment pod Kubernetes.',
                'instructor_name' => 'Rina Wijaya, M.T',
                'instructor_title' => 'DevOps & Cloud Mentor • SMAN 2 Bandung',
                'instructor_avatar' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
                'thumbnail_url' => 'https://img.youtube.com/vi/fqMOX6JJhGo/maxresdefault.jpg',
                'status' => 'published',
            ]
        );

        // 4. Lessons for Course 1 (Rekayasa Web Full-Stack)
        $c1Lessons = [
            [
                'module_title' => 'Modul 3: Client vs Server Hydration',
                'title' => '3.1: Fondasi Hidrasi & Paradigma React Modern',
                'youtube_url' => 'https://www.youtube.com/watch?v=bMknfKXIFA8',
                'youtube_video_id' => 'bMknfKXIFA8',
                'duration_seconds' => 840,
                'duration_formatted' => '14:00',
                'sort_order' => 1,
                'description' => 'Memahami siklus hidup komponen dari server streaming hingga ke DOM klien, serta mencegah hydration mismatch.',
            ],
            [
                'module_title' => 'Modul 3: Client vs Server Hydration',
                'title' => '3.2: Arsitektur Boundary Suspense di Sisi Klien',
                'youtube_url' => 'https://www.youtube.com/watch?v=SqcY0GlETPk',
                'youtube_video_id' => 'SqcY0GlETPk',
                'duration_seconds' => 1120,
                'duration_formatted' => '18:40',
                'sort_order' => 2,
                'description' => 'Membagi bundle JavaScript dan menyajikan fallback skeleton yang mulus menggunakan Suspense.',
            ],
            [
                'module_title' => 'Modul 4: Server Components & Streaming SSR',
                'title' => '4.1: Pengenalan React Server Components (RSC)',
                'youtube_url' => 'https://www.youtube.com/watch?v=wm5gMKuwSYk',
                'youtube_video_id' => 'wm5gMKuwSYk',
                'duration_seconds' => 860,
                'duration_formatted' => '14:20',
                'sort_order' => 3,
                'description' => 'Eksplorasi eksekusi kode di server tanpa menyertakan dependensi berat ke client bundle browser.',
            ],
            [
                'module_title' => 'Modul 4: Server Components & Streaming SSR',
                'title' => '4.2: Pola Pengambilan Data Efisien dengan React Cache',
                'youtube_url' => 'https://www.youtube.com/watch?v=bMknfKXIFA8',
                'youtube_video_id' => 'bMknfKXIFA8',
                'duration_seconds' => 1265,
                'duration_formatted' => '21:05',
                'sort_order' => 4,
                'description' => 'Mencegah duplikasi query database (waterfall) dengan request deduplication bawaan React.',
            ],
            [
                'module_title' => 'Modul 4: Server Components & Streaming SSR',
                'title' => '4.3: Membangun Server Actions yang Tangguh dengan Optimistic UI Updates',
                'youtube_url' => 'https://www.youtube.com/watch?v=wm5gMKuwSYk',
                'youtube_video_id' => 'wm5gMKuwSYk',
                'duration_seconds' => 1690,
                'duration_formatted' => '28:10',
                'sort_order' => 5,
                'description' => 'Dalam sesi ini, kita mempelajari cara memadukan hook useOptimistic dengan Server Actions asinkron. Anda akan belajar menangani fluktuasi latensi jaringan dengan baik, melakukan rollback pada mutasi yang dibatalkan akibat kegagalan koneksi, serta menjamin responsivitas pengalaman pengguna (UX) yang tinggi tanpa mengorbankan integritas transaksi database.',
            ],
            [
                'module_title' => 'Modul 4: Server Components & Streaming SSR',
                'title' => '4.4: Streaming UI dengan Next.js loading.js & Suspense',
                'youtube_url' => 'https://www.youtube.com/watch?v=SqcY0GlETPk',
                'youtube_video_id' => 'SqcY0GlETPk',
                'duration_seconds' => 1180,
                'duration_formatted' => '19:40',
                'sort_order' => 6,
                'description' => 'Mengirim bagian halaman secara bertahap via HTTP chunked transfer encoding untuk First Contentful Paint secepat kilat.',
            ],
        ];

        foreach ($c1Lessons as $lData) {
            Lesson::firstOrCreate(
                ['course_id' => $course1->id, 'title' => $lData['title']],
                array_merge($lData, [
                    'thumbnail_url' => "https://img.youtube.com/vi/{$lData['youtube_video_id']}/hqdefault.jpg",
                    'status' => 'published',
                ])
            );
        }

        // Lessons for Course 4 (Docker & DevOps - SMAN 2 Bandung)
        $c4Lessons = [
            [
                'module_title' => 'Modul 1: Pengenalan Container',
                'title' => 'Pengantar Docker Containers & Images Dasar',
                'youtube_url' => 'https://www.youtube.com/watch?v=fqMOX6JJhGo',
                'youtube_video_id' => 'fqMOX6JJhGo',
                'duration_seconds' => 1122,
                'duration_formatted' => '18:42',
                'sort_order' => 1,
                'description' => 'Memahami arsitektur daemon Docker, perbedaan container vs virtual machine, dan dasar-dasar CLI.',
            ],
            [
                'module_title' => 'Modul 2: Multi-stage Dockerfile',
                'title' => 'Optimalisasi Ukuran Image dengan Multi-Stage Build',
                'youtube_url' => 'https://www.youtube.com/watch?v=3c-iBn73dDE',
                'youtube_video_id' => '3c-iBn73dDE',
                'duration_seconds' => 1455,
                'duration_formatted' => '24:15',
                'sort_order' => 2,
                'description' => 'Memangkas ukuran image dari 1GB menjadi 50MB menggunakan Alpine Linux dan pemisahan compiler.',
            ],
        ];

        foreach ($c4Lessons as $lData) {
            Lesson::firstOrCreate(
                ['course_id' => $course4->id, 'title' => $lData['title']],
                array_merge($lData, [
                    'thumbnail_url' => "https://img.youtube.com/vi/{$lData['youtube_video_id']}/hqdefault.jpg",
                    'status' => 'published',
                ])
            );
        }

        // 5. Seed Resources for Lesson 4.3 & Docker Lesson
        $lesson43 = Lesson::where('title', 'like', '%4.3%')->first() ?? Lesson::first();
        if ($lesson43) {
            LessonResource::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'title' => 'Panduan Optimistic UI & Server Actions PDF'],
                [
                    'type' => 'pdf',
                    'url' => 'https://nextjs.org/docs/app/building-your-application/data-fetching/server-actions-and-mutations',
                ]
            );

            LessonResource::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'title' => 'Repository Contoh Next.js React 19 Starter'],
                [
                    'type' => 'github',
                    'url' => 'https://github.com/vercel/next.js',
                ]
            );

            LessonResource::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'title' => 'Dokumentasi Resmi React useOptimistic Hook'],
                [
                    'type' => 'link',
                    'url' => 'https://react.dev/reference/react/useOptimistic',
                ]
            );
        }

        $dockerLesson = Lesson::where('course_id', $course4->id)->first();
        if ($dockerLesson) {
            LessonResource::firstOrCreate(
                ['lesson_id' => $dockerLesson->id, 'title' => 'Cheatsheet Docker CLI & Compose'],
                [
                    'type' => 'pdf',
                    'url' => 'https://docs.docker.com/get-started/',
                ]
            );
            LessonResource::firstOrCreate(
                ['lesson_id' => $dockerLesson->id, 'title' => 'Template Dockerfile Multi-Stage'],
                [
                    'type' => 'github',
                    'url' => 'https://github.com/docker/awesome-compose',
                ]
            );
        }

        // 6. Seed Discussion Comments & Threaded Replies on Lesson 4.3
        if ($lesson43) {
            $parentComment1 = LessonComment::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'content' => 'Apakah antrean server action menjamin urutan eksekusi jika klien mengirimkan dua pembaruan optimistik berturut-turut pada koneksi lambat 3G?'],
                [
                    'user_id' => $studentElena->id,
                    'user_name' => 'Elena Rostova',
                    'user_avatar' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 14:12',
                    'likes_count' => 5,
                    'parent_id' => null,
                ]
            );

            // Nested reply
            LessonComment::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'parent_id' => $parentComment1->id],
                [
                    'user_id' => $schoolAdmin1->id,
                    'user_name' => 'Bambang S., S.Kom',
                    'user_avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 15:00',
                    'content' => 'Pertanyaan bagus Elena! Server Actions mengeksekusi secara FIFO per klien, namun di sisi UI kita menyarankan memakai nonce kriptografis.',
                    'likes_count' => 3,
                ]
            );

            // Seed likes
            CommentLike::firstOrCreate([
                'user_id' => $student1->id,
                'lesson_comment_id' => $parentComment1->id,
            ]);

            // Second discussion thread
            $parentComment2 = LessonComment::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'content' => 'Bagaimana jika koneksi terputus total saat Server Action sedang berlangsung?'],
                [
                    'user_id' => $student1->id,
                    'user_name' => 'Sarah Connor',
                    'user_avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 16:30',
                    'likes_count' => 4,
                    'parent_id' => null,
                ]
            );

            LessonComment::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'parent_id' => $parentComment2->id],
                [
                    'user_id' => $admin->id,
                    'user_name' => 'Alex Vance, PhD',
                    'user_avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
                    'timestamp_str' => 'pada 17:15',
                    'content' => 'React akan otomatis memicu error boundary atau mengembalikan status nilai ke rollback state yang sudah didefinisikan.',
                    'likes_count' => 2,
                ]
            );
        }

        // 7. Seed Student Timestamped Notes
        if ($lesson43) {
            LessonNote::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'user_id' => $student1->id, 'timestamp_seconds' => 260],
                ['content' => 'Catatan penting: useOptimistic membutuhkan React 19 atau Next.js App Router aktif.']
            );
            LessonNote::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'user_id' => $student1->id, 'timestamp_seconds' => 852],
                ['content' => 'Bagian kritis: Rollback otomatis saat dispatch action mengalami rejection di sisi server.']
            );

            // 8. Seed Interactive Quizzes
            $q1 = QuizQuestion::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'question' => 'Apa fungsi utama dari hook useOptimistic di React?'],
                [
                    'options' => [
                        'Memperbarui UI seketika sebelum respon server tiba',
                        'Menyimpan data ke local storage browser',
                        'Melakukan caching otomatis query database',
                        'Mengenkripsi request HTTP',
                    ],
                    'correct_answer_index' => 0,
                    'explanation' => 'useOptimistic memungkinkan aplikasi langsung menampilkan state optimistik di antarmuka pengguna sambil menunggu mutasi asinkron selesai di server.',
                ]
            );

            $q2 = QuizQuestion::firstOrCreate(
                ['lesson_id' => $lesson43->id, 'question' => 'Bagaimana cara Next.js me-revalidasi cache data setelah Server Action berhasil dieksekusi?'],
                [
                    'options' => [
                        'Menggunakan method reload() pada browser',
                        'Memanggil fungsi revalidatePath() atau revalidateTag()',
                        'Mengirim sinyal WebSocket manual',
                        'Menghapus cookies sesi pengguna',
                    ],
                    'correct_answer_index' => 1,
                    'explanation' => 'Next.js menyediakan revalidatePath dan revalidateTag untuk membatalkan cache data pada path atau tag tertentu dari server.',
                ]
            );

            // Seed Quiz Attempt for Sarah Connor ($student1)
            QuizAttempt::firstOrCreate(
                ['user_id' => $student1->id, 'lesson_id' => $lesson43->id],
                [
                    'score' => 100,
                    'passed' => true,
                    'answers' => [
                        $q1->id => 0,
                        $q2->id => 1,
                    ],
                ]
            );
        }

        // Quiz for Docker Lesson
        if ($dockerLesson) {
            $dq1 = QuizQuestion::firstOrCreate(
                ['lesson_id' => $dockerLesson->id, 'question' => 'Apa perbedaan utama antara Docker Container dan Virtual Machine?'],
                [
                    'options' => [
                        'Container berbagi kernel sistem operasi host sedangkan VM memiliki guest OS sendiri',
                        'Container hanya bisa dijalankan di Linux server',
                        'Virtual Machine menggunakan memory jauh lebih sedikit',
                        'Container tidak membutuhkan Docker daemon',
                    ],
                    'correct_answer_index' => 0,
                    'explanation' => 'Container memanfaatkan isolasi cgroups dan namespaces pada kernel host yang sama tanpa perlu menjalankan guest OS penuh.',
                ]
            );

            // Seed Quiz Attempt for Budi Pratama ($studentBudi)
            QuizAttempt::firstOrCreate(
                ['user_id' => $studentBudi->id, 'lesson_id' => $dockerLesson->id],
                [
                    'score' => 100,
                    'passed' => true,
                    'answers' => [
                        $dq1->id => 0,
                    ],
                ]
            );
        }

        // 9. Seed Completions with last_watched_second & Certificates
        // A. Sarah Connor completes first 3 lessons of Course 1 (50% progress)
        $c1LessonsList = Lesson::where('course_id', $course1->id)->take(3)->get();
        foreach ($c1LessonsList as $idx => $lesson) {
            LessonCompletion::firstOrCreate(
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

        // B. Budi Pratama completes 100% of Course 4 (Docker & DevOps - SMAN 2 Bandung)
        $course4LessonsList = Lesson::where('course_id', $course4->id)->get();
        foreach ($course4LessonsList as $lesson) {
            LessonCompletion::firstOrCreate(
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

        // Generate Certificate for Budi Pratama
        Certificate::firstOrCreate(
            [
                'user_id' => $studentBudi->id,
                'course_id' => $course4->id,
            ],
            [
                'certificate_code' => 'CERT-SMAN2-BUDI-'.strtoupper(substr(md5('budi-docker'), 0, 8)),
                'issued_at' => now()->subDays(2),
            ]
        );

        // C. Sarah Connor ALSO completes 100% of Course 4 (for testing cross-school certificate)
        foreach ($course4LessonsList as $lesson) {
            LessonCompletion::firstOrCreate(
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

        Certificate::firstOrCreate(
            [
                'user_id' => $student1->id,
                'course_id' => $course4->id,
            ],
            [
                'certificate_code' => 'CERT-SMKN1-SARAH-'.strtoupper(substr(md5('sarah-docker'), 0, 8)),
                'issued_at' => now()->subDay(),
            ]
        );

        // 10. Seed Bookmarks
        CourseBookmark::firstOrCreate([
            'user_id' => $student1->id,
            'course_id' => $course1->id,
        ]);
        CourseBookmark::firstOrCreate([
            'user_id' => $student1->id,
            'course_id' => $course4->id,
        ]);
        CourseBookmark::firstOrCreate([
            'user_id' => $studentBudi->id,
            'course_id' => $course4->id,
        ]);
    }
}
