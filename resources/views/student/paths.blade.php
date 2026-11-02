@extends('layouts.student')

@section('student_content')
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-100">
                    <span class="material-symbols-outlined text-[14px]">route</span>
                    Roadmap Pembelajaran
                </span>
                <span class="text-xs text-slate-400">• Kurikulum Berjenjang SSK</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Alur Belajar Terstruktur</h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">Panduan langkah-demi-langkah materi Sekolah Siaga Kependudukan (SSK) dan BKKBN dari pengenalan dasar hingga advokasi yang dikurasi oleh tim ahli konsorsium.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="min-h-10 inline-flex items-center gap-1.5 px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-2xs">
                <span class="material-symbols-outlined text-[15px]">arrow_back</span>
                <span>Kembali ke Katalog</span>
            </a>
        </div>
    </div>

    <!-- Learning Tracks List -->
    <div class="space-y-6">
        @foreach($paths as $path)
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-2xs hover:border-slate-300 transition-colors duration-200">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">{{ $path['icon'] }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100 text-xs font-semibold">{{ $path['badge'] }}</span>
                                <span class="text-xs text-slate-400">• Tingkat: <strong class="text-slate-700 font-semibold">{{ $path['level'] }}</strong></span>
                                <span class="text-xs text-slate-400">• Total: <strong class="text-slate-700 font-semibold">{{ $path['total_modules'] }} Modul</strong></span>
                            </div>
                            <h2 class="text-lg sm:text-xl font-bold text-slate-900">{{ $path['title'] }}</h2>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">{{ $path['description'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stepper Roadmap of Courses in this Track -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-emerald-600">timeline</span>
                        Tahapan Kursus dalam Alur Ini
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($path['courses'] as $course)
                            @php
                                $progress = $course->progressPercentageFor($student);
                            @endphp
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between gap-3 hover:bg-slate-100/70 hover:border-slate-300 transition-all">
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400 gap-2">
                                        <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold border border-emerald-100 truncate">{{ $course->category->name ?? 'Materi SSK' }}</span>
                                        <span class="shrink-0 flex items-center gap-1 text-slate-500 font-medium">
                                            <span class="material-symbols-outlined text-[14px]">videocam</span>
                                            {{ $course->lessons->count() }} Modul
                                        </span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">{{ $course->title }}</h4>
                                    @if($course->instructor_name)
                                        <p class="text-[11px] text-slate-500 flex items-center gap-1 truncate">
                                            <span class="material-symbols-outlined text-[13px] text-slate-400">person</span>
                                            <span>{{ $course->instructor_name }}</span>
                                        </p>
                                    @endif
                                    <p class="text-[11px] text-slate-600 line-clamp-2 leading-relaxed">{{ $course->description }}</p>
                                </div>
                                <div class="pt-3 border-t border-slate-200/80 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-16 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                            <div class="h-full bg-emerald-600 rounded-full" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-semibold text-slate-600 font-mono">{{ $progress }}%</span>
                                    </div>
                                    <a href="{{ route('student.classroom', $course->slug) }}" 
                                       class="min-h-9 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-semibold transition-colors shadow-2xs inline-flex items-center gap-1">
                                        <span>{{ $progress > 0 ? 'Lanjutkan Belajar' : 'Mulai Belajar' }}</span>
                                        <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-xs text-slate-400 italic py-2">
                                Kursus untuk modul lanjutan alur ini sedang dalam proses kurasi instruktur.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</main>
@endsection
