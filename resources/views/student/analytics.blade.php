@extends('layouts.student')

@section('student_content')
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                    <span class="material-symbols-outlined text-[14px]">analytics</span>
                    Statistik & Telemetri Belajar
                </span>
                <span class="text-xs text-slate-400">• Data Real-Time</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Analitik Perkembangan Siswa</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Lacak jam tonton materi YouTube, pencapaian modul, dan konsistensi belajar harian Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-xs">
                ← Kembali ke Katalog
            </a>
        </div>
    </div>

    <!-- 4 KPI Metrics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Jam Belajar</span>
                <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center border border-indigo-100">
                    <span class="material-symbols-outlined text-[18px]">pace</span>
                </span>
            </div>
            <div>
                <span class="text-3xl font-extrabold text-slate-900 font-mono">{{ $totalHoursWatched }}</span>
                <span class="text-xs text-slate-400 ml-1">Jam Tonton</span>
            </div>
            <p class="text-[11px] text-slate-500">Berdasarkan materi YouTube yang telah ditandai tuntas.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Video Diselesaikan</span>
                <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                </span>
            </div>
            <div>
                <span class="text-3xl font-extrabold text-emerald-700 font-mono">{{ $totalCompletedLessons }}</span>
                <span class="text-xs text-slate-400 ml-1">Pelajaran</span>
            </div>
            <p class="text-[11px] text-slate-500">Dari seluruh silabus kurikulum aktif.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Kursus Aktif & Tuntas</span>
                <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                </span>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-extrabold text-slate-900 font-mono">{{ $inProgressCoursesCount }}</span>
                <span class="text-xs text-slate-400">Berjalan</span>
                <span class="text-xs text-slate-300">/</span>
                <span class="text-xl font-bold text-emerald-700 font-mono">{{ $completedCoursesCount }}</span>
                <span class="text-xs text-slate-400">Tuntas</span>
            </div>
            <p class="text-[11px] text-slate-500">{{ $bookmarkedCoursesCount }} kursus disimpan di penanda.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-col justify-between space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Interaksi Belajar</span>
                <span class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-100">
                    <span class="material-symbols-outlined text-[18px]">forum</span>
                </span>
            </div>
            <div class="flex items-baseline gap-3">
                <div>
                    <span class="text-2xl font-bold text-slate-900 font-mono">{{ $notesCount }}</span>
                    <span class="text-[11px] text-slate-400 block">Catatan</span>
                </div>
                <div class="h-6 w-px bg-slate-200"></div>
                <div>
                    <span class="text-2xl font-bold text-slate-900 font-mono">{{ $commentsCount }}</span>
                    <span class="text-[11px] text-slate-400 block">Diskusi</span>
                </div>
            </div>
            <p class="text-[11px] text-slate-500">Catatan timestamp & tanya jawab komunitas.</p>
        </div>
    </div>

    <!-- Category Progress Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Col 7: Category Proficiency -->
        <section class="lg:col-span-7 bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Penguasaan Materi Berdasarkan Bidang</h2>
                <span class="text-xs text-slate-400">Persentase Modul</span>
            </div>

            <div class="space-y-4">
                @foreach($categoryStats as $stat)
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-semibold text-slate-900">{{ $stat['name'] }}</span>
                            <span class="font-mono text-indigo-700 font-semibold">{{ $stat['percentage'] }}% ({{ $stat['completed_lessons'] }}/{{ $stat['total_lessons'] }} materi)</span>
                        </div>
                        <div class="w-full h-2 rounded-full bg-slate-100 overflow-hidden">
                            <div class="h-full bg-indigo-600 rounded-full transition-all duration-300" style="width: {{ $stat['percentage'] }}%;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- Col 5: Recent Activity Feed & Earned Certificates -->
        <section class="lg:col-span-5 flex flex-col gap-6">
            <!-- Earned Certificates Box -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">workspace_premium</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Sertifikat Kelulusan Resmi</h2>
                    </div>
                    <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100">
                        {{ $certificates->count() }} Terbit
                    </span>
                </div>

                <div class="space-y-2.5">
                    @forelse($certificates as $cert)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                            <div class="flex flex-col truncate">
                                <span class="text-xs font-semibold text-slate-900 truncate">{{ $cert->course->title }}</span>
                                <span class="text-[10px] text-slate-500 font-mono">Kode: {{ $cert->certificate_code }}</span>
                            </div>
                            <a href="{{ route('student.certificate', $cert->certificate_code) }}" 
                               class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shrink-0 transition-colors flex items-center gap-1 shadow-xs">
                                <span class="material-symbols-outlined text-[14px]">print</span>
                                <span>Lihat</span>
                            </a>
                        </div>
                    @empty
                        <div class="py-5 text-center text-xs text-slate-400 bg-slate-50 rounded-xl border border-dashed border-slate-200">
                            Belum ada sertifikat terbit. Selesaikan 100% materi salah satu kursus untuk meraih sertifikat kelulusan otomatis!
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quiz Telemetry Box -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600">quiz</span>
                        <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Statistik Kuis Checkpoint</h2>
                    </div>
                    <span class="text-xs font-mono font-semibold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-100">
                        Rata-rata: {{ $averageQuizScore }}%
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 text-center">
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-2xl font-bold font-mono text-indigo-700">{{ $quizAttemptsCount }}</span>
                        <span class="text-[11px] text-slate-500 block mt-0.5">Percobaan Kuis</span>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                        <span class="text-2xl font-bold font-mono text-emerald-700">{{ $averageQuizScore }}%</span>
                        <span class="text-[11px] text-slate-500 block mt-0.5">Rata-rata Nilai</span>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Feed -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Aktivitas Penyelesaian Terkini</h2>
                    <span class="text-xs text-slate-400">Riwayat Log</span>
                </div>

                <div class="space-y-3">
                    @forelse($recentCompletions as $comp)
                        <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                            <div class="w-7 h-7 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5">
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
                            </div>
                            <div class="flex flex-col truncate flex-1">
                                <span class="text-xs font-semibold text-slate-900 truncate">{{ $comp->lesson->title ?? 'Pelajaran' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $comp->completed_at ? \Carbon\Carbon::parse($comp->completed_at)->diffForHumans() : 'Baru saja' }}</span>
                            </div>
                            @if($comp->lesson && $comp->lesson->course)
                                <a href="{{ route('student.classroom.lesson', [$comp->lesson->course->slug, $comp->lesson->id]) }}" 
                                   class="text-xs text-indigo-600 hover:text-indigo-800 font-medium shrink-0">Review →</a>
                            @endif
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-slate-400">
                            Belum ada pelajaran yang diselesaikan. Buka salah satu kursus dan mulai belajar!
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
