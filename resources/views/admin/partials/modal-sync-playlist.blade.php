<!-- ======================================================== -->
<!-- MODAL SYNC PLAYLIST YOUTUBE                             -->
<!-- ======================================================== -->
@php
    $courses = $courses ?? \App\Models\Course::all();
@endphp
<div x-show="isPlaylistModalOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
     x-data="{
        playlistUrl: '',
        courseId: '{{ $courses->first()->id ?? 1 }}',
        moduleTitle: 'Modul Hasil Impor Playlist',
        isSubmitting: false
     }"
     @open-playlist-modal.window="isPlaylistModalOpen = true; if($event.detail && $event.detail.courseId) { courseId = String($event.detail.courseId); }">

    <div @click.away="isPlaylistModalOpen = false" 
         class="relative w-full max-w-2xl bg-white rounded-xl border border-slate-200 shadow-2xl flex flex-col my-auto overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-start justify-between bg-white">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined text-[18px]">sync</span>
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Sinkronkan Playlist YouTube</h2>
                </div>
                <p class="text-xs text-slate-500">
                    Impor seluruh video materi dalam satu playlist YouTube secara massal ke dalam kurikulum kursus yang dipilih.
                </p>
            </div>
            <button type="button" @click="isPlaylistModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.youtube.import-playlist') }}" method="POST" @submit="isSubmitting = true">
            @csrf
            <div class="p-6 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">
                        Pilih Kursus Target <span class="text-rose-500">*</span>
                    </label>
                    <select name="course_id" x-model="courseId" required
                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">
                        Nama Bab / Modul Impor <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="module_title" x-model="moduleTitle" required
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"/>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">
                        Tautan Playlist YouTube <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-rose-600">
                            <span class="material-symbols-outlined text-[18px]">playlist_play</span>
                        </span>
                        <input type="url" name="playlist_url" x-model="playlistUrl" required
                               placeholder="https://www.youtube.com/playlist?list=PL..."
                               class="w-full bg-white border border-slate-200 rounded-lg pl-9 pr-3 py-2 text-xs font-mono text-slate-900 focus:outline-none focus:border-indigo-600"/>
                    </div>
                    <p class="text-[11px] text-slate-400">Mendukung URL dengan parameter <code class="font-mono text-indigo-600 font-semibold">?list=PL...</code>.</p>
                </div>

                <div class="p-3 rounded-lg bg-indigo-50/60 border border-indigo-200 text-xs text-indigo-900 flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-indigo-600 text-[18px] shrink-0 mt-0.5">info</span>
                    <p>Sistem akan secara berurutan mengekstrak daftar episode video, durasi, dan membuat urutan pembelajaran yang terstruktur di kursus target.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" @click="isPlaylistModalOpen = false" 
                        class="px-4 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold rounded-lg hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" :disabled="isSubmitting"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]" :class="{ 'animate-spin': isSubmitting }">sync</span>
                    <span x-text="isSubmitting ? 'Mengimpor...' : 'Mulai Sinkronisasi Playlist'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
