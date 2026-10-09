@extends('layouts.student')

@section('student_content')
<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-slate-200 pb-5">
        <div>
            <div class="flex items-center gap-2 mb-1.5">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-xs font-semibold border border-indigo-100">
                    <span class="material-symbols-outlined text-[14px]">route</span>
                    Roadmap Pembelajaran
                </span>
                <span class="text-xs text-slate-400">• Kurikulum Berjenjang</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Alur Belajar Terstruktur</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Ikuti panduan langkah-demi-langkah dari pemula hingga tingkat mahir yang telah dikurasi oleh para pakar industri.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.dashboard') }}" class="px-4 py-2 text-xs font-semibold rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 transition-colors shadow-xs">
                ← Kembali ke Katalog
            </a>
        </div>
    </div>

    <!-- Learning Tracks List -->
    <div class="space-y-6">
        @foreach($paths as $path)
            <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-xs hover:border-slate-300 transition-colors duration-200">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shrink-0 shadow-xs">
                            <span class="material-symbols-outlined text-[26px]">{{ $path['icon'] }}</span>
                        </div>
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 text-xs font-semibold">{{ $path['badge'] }}</span>
                                <span class="text-xs text-slate-400">• Tingkat: <strong class="text-slate-700 font-semibold">{{ $path['level'] }}</strong></span>
                                <span class="text-xs text-slate-400">• Total: <strong class="text-slate-700 font-semibold">{{ $path['total_modules'] }} Modul</strong></span>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900">{{ $path['title'] }}</h2>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">{{ $path['description'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Stepper Roadmap of Courses in this Track -->
                <div class="mt-8 pt-6 border-t border-slate-100">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-indigo-600">timeline</span>
                        Tahapan Kursus dalam Alur Ini
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse($path['courses'] as $course)
                            @php
                                $progress = $course->progressPercentageFor($student);
                            @endphp
                            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col justify-between gap-3 hover:bg-slate-100/70 transition-colors">
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-[11px] text-slate-400">
                                        <span class="font-semibold text-indigo-700">{{ $course->category->name ?? 'Materi' }}</span>
                                        <span>{{ $course->lessons->count() }} Video</span>
                                    </div>
                                    <h4 class="text-xs font-bold text-slate-900 leading-snug">{{ $course->title }}</h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ $course->description }}</p>
                                </div>
                                <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                                    <span class="text-[11px] font-semibold text-slate-600">{{ $progress }}% Selesai</span>
                                    <a href="{{ route('student.classroom', $course->slug) }}" 
                                       class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-semibold transition-colors shadow-xs">
                                        {{ $progress > 0 ? 'Lanjutkan' : 'Mulai Kursus' }}
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
