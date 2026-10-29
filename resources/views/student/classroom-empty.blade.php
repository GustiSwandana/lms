@extends('layouts.app')

@section('content')
<!-- ==================== TOP NAVIGATION BAR ==================== -->
<header class="flex justify-between items-center w-full px-6 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white shadow-xs">
    <!-- Left Section: Back to Dashboard & Breadcrumbs -->
    <div class="flex items-center gap-4">
        <a class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 hover:text-indigo-600 transition-colors group" 
           href="{{ route('student.dashboard') }}" title="Kembali ke Dasbor">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span class="text-xs font-semibold hidden sm:inline">Kembali ke Dasbor</span>
        </a>

        <div class="h-5 w-px bg-slate-200 mx-1 hidden md:block"></div>

        <!-- Compact Breadcrumb Trail -->
        <nav aria-label="Breadcrumb" class="hidden sm:flex items-center gap-1.5 text-slate-500 text-xs font-medium">
            <a class="hover:text-indigo-600 transition-colors" href="{{ route('student.dashboard') }}">Kursus</a>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="hover:text-indigo-600 transition-colors max-w-[200px] truncate">{{ $course->title }}</span>
            <span class="material-symbols-outlined text-[14px] text-slate-400">chevron_right</span>
            <span class="text-rose-600 font-semibold">Belum Ada Materi</span>
        </nav>
    </div>

    <!-- Center/Right Navigation Links & Actions -->
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-semibold transition-all">
            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
            <span class="hidden md:inline">Portal Admin</span>
        </a>

        <div class="h-6 w-px bg-slate-200"></div>

        <!-- Instructor/Student Account Menu -->
        <div class="flex items-center gap-2.5 pl-1">
            <div class="w-8 h-8 rounded-full ring-1 ring-slate-200 overflow-hidden bg-slate-100">
                <img class="w-full h-full object-cover" 
                     src="{{ $student->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" 
                     alt="Foto Siswa"/>
            </div>
            <div class="hidden lg:flex flex-col text-left leading-none">
                <span class="text-xs font-semibold text-slate-900">{{ $student->name }}</span>
                <span class="text-[11px] text-slate-500">Siswa Pro</span>
            </div>
        </div>
    </div>
</header>

<!-- ==================== MAIN EMPTY STATE VIEW ==================== -->
<main class="flex-1 w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10"
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
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-xs mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-3 flex-1">
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                        {{ $course->category->name ?? 'Teknologi' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                        Menunggu Materi
                    </span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                    {{ $course->title }}
                </h1>
                <p class="text-sm text-slate-600 leading-relaxed max-w-3xl">
                    {{ $course->description }}
                </p>
                <div class="flex items-center gap-3 pt-2 text-xs text-slate-500">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-slate-400">person</span>
                        <span>Pengajar: <strong class="text-slate-900 font-semibold">{{ $course->instructor_name ?? 'Tim Fasilitator BKKBN SSK' }}</strong> ({{ $course->instructor_title ?? 'Instruktur' }})</span>
                    </div>
                </div>
            </div>

            <!-- Bookmark Action Button -->
            <div class="flex items-center gap-3 self-start md:self-center shrink-0">
                <button type="button" 
                        @click="toggleCourseBookmark()" 
                        :class="isBookmarked ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-semibold transition-all shadow-xs">
                    <span class="material-symbols-outlined text-[18px]" :style="isBookmarked ? 'font-variation-settings: \'FILL\' 1;' : ''">bookmark</span>
                    <span x-text="isBookmarked ? 'Tersimpan di Penanda' : 'Simpan Kursus'">Simpan Kursus</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Empty Video Notice Box -->
    <div class="bg-white rounded-xl border border-slate-200 p-10 sm:p-14 text-center shadow-xs flex flex-col items-center justify-center">
        <div class="w-16 h-16 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center mb-5 border border-indigo-100">
            <span class="material-symbols-outlined text-[36px]" style="font-variation-settings: 'FILL' 1;">video_library</span>
        </div>

        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-2">
            Materi Video Belum Tersedia
        </h2>
        <p class="text-sm text-slate-500 max-w-md mx-auto leading-relaxed mb-8">
            Kursus ini baru saja didaftarkan dan belum memiliki materi video YouTube yang dipublikasikan oleh pengajar. Silakan kembali lagi nanti atau tambahkan materi melalui portal admin.
        </p>

        <div class="flex flex-wrap items-center justify-center gap-3 w-full max-w-md">
            <a href="{{ route('student.dashboard') }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px]">explore</span>
                <span>Jelajahi Kursus Lain</span>
            </a>

            <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold transition-all">
                <span class="material-symbols-outlined text-[18px]">add_circle</span>
                <span>Tambah Materi di Admin</span>
            </a>
        </div>
    </div>
</main>
@endsection
