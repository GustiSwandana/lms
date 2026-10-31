@extends('layouts.app')

@section('content')
<!-- ==================== TOP NAVIGATION BAR ==================== -->
<header class="flex justify-between items-center w-full px-4 sm:px-6 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white/95 backdrop-blur-md">
    <!-- Left Section: Back to Dashboard & Breadcrumbs -->
    <div class="flex items-center gap-3 sm:gap-4 min-w-0">
        <a class="inline-flex items-center justify-center gap-2 min-h-11 px-3.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs transition-colors" 
           href="{{ route('student.dashboard') }}" title="Kembali ke Dasbor">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="text-xs font-semibold hidden sm:inline">Dasbor</span>
        </a>

        <div class="h-5 w-px bg-slate-200 mx-0.5 hidden md:block"></div>

        <!-- Compact Breadcrumb Trail -->
        <nav aria-label="Breadcrumb" class="hidden sm:flex items-center gap-1.5 text-slate-500 text-xs font-medium truncate">
            <a class="hover:text-emerald-700 transition-colors" href="{{ route('student.dashboard') }}">Kursus</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="text-slate-700 truncate max-w-[200px]">{{ $course->title }}</span>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="text-amber-800 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded text-[11px] font-bold">Belum Ada Materi</span>
        </nav>
    </div>

    <!-- Center/Right Navigation Links & Actions -->
    <div class="flex items-center gap-2.5 sm:gap-3 shrink-0">
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center justify-center gap-1.5 min-h-11 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 hover:text-slate-900 shadow-2xs text-xs font-semibold transition-all">
            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
            <span class="hidden md:inline">Portal Admin</span>
        </a>

        <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

        <!-- Instructor/Student Account Menu -->
        <div class="flex items-center gap-2.5 pl-1">
            <div class="w-9 h-9 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-100 shrink-0">
                <img class="w-full h-full object-cover" 
                     src="{{ $student->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" 
                     alt="Foto Siswa"/>
            </div>
            <div class="hidden lg:flex flex-col text-left leading-tight">
                <span class="text-xs font-semibold text-slate-900">{{ $student->name }}</span>
                <span class="text-[11px] text-slate-500 font-mono">{{ $student->school_name ?: 'Siswa SSK' }}</span>
            </div>
        </div>
    </div>
</header>

<!-- ==================== MAIN EMPTY STATE VIEW ==================== -->
<main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8"
      x-data="{
          isBookmarked: {{ $isBookmarked ? 'true' : 'false' }},
          toggleCourseBookmark() {
              fetch('{{ route('student.course.bookmark', $course->slug) }}', {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                      'Accept': 'application/json'
                  }
              })
              .then(res => res.json())
              .then(data => {
                  this.isBookmarked = data.bookmarked;
              });
          }
      }">
    
    <!-- Course Meta Summary Banner -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-xs mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 flex-1 text-left">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                        {{ $course->category->name ?? 'SSK BKKBN' }}
                    </span>
                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                        Menunggu Materi
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                    {{ $course->title }}
                </h1>
                <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
                    {{ $course->description }}
                </p>
                <div class="flex items-center gap-3 pt-1 text-xs text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-emerald-700">person</span>
                        <span>Pengajar: <strong class="text-slate-800 font-semibold">{{ $course->instructor_name ?? 'Tim Fasilitator BKKBN SSK' }}</strong> ({{ $course->instructor_title ?? 'Instruktur' }})</span>
                    </div>
                </div>
            </div>

            <!-- Bookmark Action Button -->
            <div class="flex items-center gap-3 self-start md:self-center shrink-0">
                <button type="button" 
                        @click="toggleCourseBookmark()" 
                        :class="isBookmarked ? 'bg-indigo-50 border-indigo-300 text-indigo-700' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50 shadow-2xs'"
                        class="inline-flex items-center justify-center gap-2 min-h-11 px-4 py-2.5 rounded-xl border text-xs font-semibold transition-all">
                    <span class="material-symbols-outlined text-[18px]" :style="isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                    <span x-text="isBookmarked ? 'Tersimpan di Penanda' : 'Simpan Kursus'">Simpan Kursus</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Empty Video Notice Box -->
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-12 text-left shadow-xs flex flex-col md:flex-row items-start md:items-center gap-6">
        <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-200">
            <span class="material-symbols-outlined text-[32px]">video_library</span>
        </div>

        <div class="flex-1 space-y-2">
            <h2 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                Materi Video Belum Tersedia
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-xl">
                Kursus ini baru saja didaftarkan dan belum memiliki video materi yang dipublikasikan oleh tim instruktur. Silakan kembali lagi nanti atau tambahkan materi melalui portal admin kurikulum.
            </p>
        </div>

        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 w-full md:w-auto">
            <a href="{{ route('student.dashboard') }}" 
               class="inline-flex items-center justify-center gap-2 min-h-11 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px]">explore</span>
                <span>Jelajahi Kursus Lain</span>
            </a>

            <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
               class="inline-flex items-center justify-center gap-2 min-h-11 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-200 text-xs font-semibold shadow-2xs transition-all">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Materi di Admin</span>
            </a>
        </div>
    </div>
</main>
@endsection
