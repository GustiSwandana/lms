@extends('layouts.admin')

@section('admin_content')
<!-- ================= PAGE TITLE & ACTIONS ================= -->
<section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <div class="flex items-center gap-2.5">
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pustaka Video Pembelajaran</h1>
            <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold">
                Total {{ $totalLessons }}
            </span>
        </div>
        <p class="text-xs text-slate-500 mt-1">
            Kelola, verifikasi, dan atur video materi pembelajaran berbasis YouTube di seluruh modul kursus aktif.
        </p>
    </div>

    <!-- Primary Action Buttons -->
    <div class="flex items-center gap-2.5 flex-wrap">
        <button type="button" @click="$dispatch('open-playlist-modal')" 
                class="px-3.5 py-2 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 text-xs font-medium flex items-center gap-2 transition-colors shadow-xs">
            <span class="material-symbols-outlined text-[16px] text-slate-500">sync</span>
            <span>Sinkronkan Playlist</span>
        </button>

        <button type="button" @click="$dispatch('open-import-modal')" 
                class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold flex items-center gap-2 transition-colors shadow-sm">
            <span class="material-symbols-outlined text-[16px]">add</span>
            <span>+ Tambah Video Pembelajaran Baru</span>
        </button>
    </div>
</section>

<!-- ================= 4 ESSENTIAL METRICS CARDS ================= -->
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <!-- Card 1: Total Lessons -->
    <div class="p-5 rounded-xl bg-white border border-slate-200 flex flex-col justify-between shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">Total Video Pelajaran</span>
            <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">smart_display</span>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <span class="text-2xl font-bold text-slate-900">{{ $totalLessons }}</span>
            <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-medium flex items-center gap-0.5">
                <span class="material-symbols-outlined text-[12px]">trending_up</span> Aktif
            </span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400">Di seluruh kurikulum terstruktur</div>
    </div>

    <!-- Card 2: Active Courses -->
    <div class="p-5 rounded-xl bg-white border border-slate-200 flex flex-col justify-between shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">Kursus Aktif</span>
            <div class="h-8 w-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">menu_book</span>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <span class="text-2xl font-bold text-slate-900">{{ $totalCourses }}</span>
            <span class="text-[11px] text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md font-medium">
                Siap Belajar
            </span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400">Cakupan video 100% terverifikasi</div>
    </div>

    <!-- Card 3: Total Watch Hours -->
    <div class="p-5 rounded-xl bg-white border border-slate-200 flex flex-col justify-between shadow-xs">
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">Total Waktu Belajar</span>
            <div class="h-8 w-8 rounded-lg bg-violet-50 text-violet-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">schedule</span>
            </div>
        </div>
        <div class="mt-4 flex items-baseline justify-between">
            <span class="text-2xl font-bold text-slate-900">{{ $totalWatchHours }} <span class="text-xs font-normal text-slate-500">jam</span></span>
            <span class="text-[11px] text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-md font-medium">
                Akumulasi
            </span>
        </div>
        <div class="mt-2 text-[11px] text-slate-400">Total jam pemutaran oleh siswa</div>
    </div>

    <!-- Card 4: YouTube Sync Status -->
    <div class="p-5 rounded-xl bg-white border border-slate-200 flex flex-col justify-between shadow-xs"
         x-data="{ 
             checking: false, 
             checkResult: null,
             checkHealth(url) {
                 this.checking = true;
                 fetch('{{ route('admin.youtube.check-health') }}', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                         'Accept': 'application/json'
                     },
                     body: JSON.stringify({ video_url: url || 'https://www.youtube.com/watch?v=wm5gMKuwSYk' })
                 })
                 .then(res => res.json())
                 .then(data => {
                     this.checking = false;
                     this.checkResult = data;
                 })
                 .catch(() => {
                     this.checking = false;
                 });
             }
         }">
        <div class="flex items-center justify-between">
            <span class="text-xs text-slate-500 font-medium">Validasi Tautan YouTube</span>
            <div class="h-8 w-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">verified</span>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <div class="flex items-center gap-1.5">
                <span class="h-2 w-2 rounded-full" :class="checkResult ? (checkResult.healthy ? 'bg-emerald-500' : 'bg-rose-500') : 'bg-emerald-500'"></span>
                <span class="text-xs font-semibold text-slate-800" x-text="checkResult ? checkResult.message : 'oEmbed v3 Normal'"></span>
            </div>
            <button type="button" @click="checkHealth()" :disabled="checking"
                    class="px-2.5 py-1 rounded-md bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-medium transition-all disabled:opacity-50">
                <span x-text="checking ? 'Memeriksa...' : 'Cek Status'"></span>
            </button>
        </div>
        <div class="mt-2 text-[11px] text-slate-400">Verifikasi status pemutaran oEmbed</div>
    </div>
</section>

<!-- ================= YOUTUBE URL QUICK INGESTION BAR ================= -->
<section class="bg-white border border-slate-200 p-4 rounded-xl shadow-xs" x-data="{ quickUrl: '' }">
    <div class="flex flex-col sm:flex-row items-center gap-3">
        <div class="flex items-center gap-2 self-start sm:self-center text-slate-700 font-semibold text-xs whitespace-nowrap">
            <span class="material-symbols-outlined text-rose-500 text-[18px]">smart_display</span>
            <span>Unggah Cepat:</span>
        </div>
        <div class="relative flex-1 w-full">
            <input x-model="quickUrl"
                   class="w-full bg-slate-50 text-slate-900 font-mono text-xs pl-3 pr-24 py-2 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 focus:bg-white transition-all placeholder:text-slate-400" 
                   placeholder="Tempel tautan video YouTube (cth: https://www.youtube.com/watch?v=...)" 
                   type="text"/>
            <span x-show="quickUrl.length > 5" class="absolute inset-y-0 right-2 flex items-center">
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-semibold rounded">Terdeteksi</span>
            </span>
        </div>
        <button type="button" @click="if(quickUrl) $dispatch('open-import-modal', { url: quickUrl })"
                class="w-full sm:w-auto px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg transition-colors flex items-center justify-center gap-1.5 shadow-xs whitespace-nowrap">
            <span class="material-symbols-outlined text-[16px]">bolt</span>
            <span>Urai Otomatis</span>
        </button>
    </div>
</section>

<!-- ================= FILTER & SEARCH TOOLBAR ================= -->
<section class="p-3 bg-white rounded-xl border border-slate-200 flex flex-col md:flex-row gap-3 items-stretch md:items-center justify-between shadow-xs">
    <!-- Search Field -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="relative flex-1 min-w-[240px]">
        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
            <span class="material-symbols-outlined text-[16px]">search</span>
        </span>
        <input name="q" value="{{ $searchQuery }}"
               class="w-full bg-slate-50 text-slate-900 pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 focus:bg-white placeholder:text-slate-400 transition-all" 
               placeholder="Cari berdasarkan judul materi atau ID video..." type="text"/>
    </form>

    <!-- Dropdowns Cluster -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
        <!-- Super Admin School Filter -->
        @if($admin->isSuperAdmin() && $schools->count() > 0)
            <select name="school" onchange="this.form.submit()" 
                    class="bg-slate-50 text-slate-700 text-xs py-1.5 pl-2.5 pr-8 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 font-medium">
                <option value="">Semua Instansi</option>
                @foreach($schools as $sch)
                    <option value="{{ $sch }}" {{ ($selectedSchool ?? '') === $sch ? 'selected' : '' }}>{{ $sch }}</option>
                @endforeach
            </select>
        @endif

        <!-- Category Filter -->
        <select name="category_id" onchange="this.form.submit()" 
                class="bg-slate-50 text-slate-700 text-xs py-1.5 pl-2.5 pr-8 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>

        <!-- Status Filter -->
        <select name="status" onchange="this.form.submit()" 
                class="bg-slate-50 text-slate-700 text-xs py-1.5 pl-2.5 pr-8 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600">
            <option value="">Semua Status</option>
            <option value="published" {{ $selectedStatus === 'published' ? 'selected' : '' }}>Terverifikasi & Publik</option>
            <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draf</option>
            <option value="unlisted" {{ $selectedStatus === 'unlisted' ? 'selected' : '' }}>Unlisted</option>
        </select>

        <!-- Reset Filter Button -->
        <a href="{{ route('admin.dashboard') }}" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors" title="Reset Filter">
            <span class="material-symbols-outlined text-[18px]">refresh</span>
        </a>
    </form>
</section>

<!-- ================= DATA TABLE SECTION ================= -->
<section class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs">
            <!-- Table Header -->
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[11px] font-semibold">
                    <th class="py-3 px-4 w-10 text-center">#</th>
                    <th class="py-3 px-4 uppercase tracking-wider">Thumbnail</th>
                    <th class="py-3 px-4 uppercase tracking-wider">Judul & Modul</th>
                    <th class="py-3 px-4 uppercase tracking-wider">Kursus</th>
                    <th class="py-3 px-4 uppercase tracking-wider">Status & Sumber</th>
                    <th class="py-3 px-4 uppercase tracking-wider">Durasi</th>
                    <th class="py-3 px-4 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-slate-100 text-slate-700">
                @forelse($lessons as $lesson)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-mono text-slate-400 text-[11px]">
                            {{ $loop->iteration }}
                        </td>
                        <td class="py-3 px-4 w-32">
                            <div class="relative w-24 aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-900">
                                <img class="w-full h-full object-cover" 
                                     src="{{ $lesson->thumbnail_url ?: 'https://img.youtube.com/vi/' . $lesson->youtube_video_id . '/hqdefault.jpg' }}" 
                                     alt="Thumbnail {{ $lesson->title }}"/>
                                <span class="absolute bottom-1 right-1 px-1 py-0.2 bg-black/75 text-white rounded text-[9px] font-mono font-medium">
                                    {{ $lesson->duration_formatted }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('student.classroom.lesson', [$lesson->course->slug, $lesson->id]) }}" 
                               class="font-semibold text-slate-900 hover:text-indigo-600 transition-colors line-clamp-1">
                                {{ $lesson->title }}
                            </a>
                            <div class="text-[11px] text-slate-400 flex items-center gap-1.5 mt-0.5">
                                <span>{{ $lesson->module_title ?: 'Modul Umum' }}</span>
                                <span>•</span>
                                <span>Pelajaran #{{ $lesson->sort_order }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-block px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px] max-w-[180px] truncate">
                                {{ $lesson->course->title }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex flex-col gap-1 items-start">
                                @if($lesson->status === 'published')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Publik
                                    </span>
                                @elseif($lesson->status === 'draft')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draf
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                        Unlisted
                                    </span>
                                @endif
                                <a class="font-mono text-[10px] text-slate-400 hover:text-indigo-600" 
                                   href="{{ $lesson->youtube_url }}" target="_blank">
                                    ID: {{ $lesson->youtube_video_id }}
                                </a>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-mono font-semibold text-slate-800">{{ $lesson->duration_formatted }}</span>
                        </td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" @click="$dispatch('open-edit-lesson-modal', { lesson: {{ json_encode($lesson) }} })"
                                        class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors" title="Edit video">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>

                                <a href="{{ route('student.classroom.lesson', [$lesson->course->slug, $lesson->id]) }}" 
                                   class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors" title="Pratinjau Ruang Belajar">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                </a>

                                <form action="{{ route('admin.lessons.destroy', $lesson->id) }}" method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi video ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-[32px] mb-1 text-slate-300">video_library</span>
                            <p class="text-xs font-semibold text-slate-700">Tidak ada video materi yang cocok.</p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Silakan reset filter atau tambahkan video baru.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    @if($lessons->hasPages())
        <div class="px-6 py-3.5 border-t border-slate-200 bg-slate-50">
            {{ $lessons->links() }}
        </div>
    @endif
</section>
@endsection
