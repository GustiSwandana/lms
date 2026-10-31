<!-- ======================================================== -->
<!-- MODAL SYNC PLAYLIST YOUTUBE (LIGHT THEME)               -->
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
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
     x-data="{
        playlistUrl: '',
        courseId: '{{ $courses->first()->id ?? 1 }}',
        moduleTitle: 'Modul Hasil Impor Playlist',
        isSubmitting: false
     }"
     @open-playlist-modal.window="isPlaylistModalOpen = true; if($event.detail && $event.detail.courseId) { courseId = String($event.detail.courseId); }">

    <div @click.away="isPlaylistModalOpen = false" 
         class="relative w-full max-w-2xl bg-white rounded-3xl border border-slate-200 shadow-xl flex flex-col my-auto overflow-hidden text-left">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">sync</span>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Sinkronkan Playlist YouTube</h2>
                </div>
                <p class="text-xs text-slate-500">
                    Impor seluruh video materi dalam satu playlist YouTube secara massal ke dalam kurikulum kursus yang dipilih.
                </p>
            </div>
            <button type="button" @click="isPlaylistModalOpen = false" aria-label="Tutup modal"
                    class="text-slate-400 hover:text-slate-700 min-h-11 min-w-11 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.youtube.import-playlist') }}" method="POST" @submit="isSubmitting = true">
            @csrf
            <div class="p-6 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">
                        Pilih Kursus Target <span class="text-emerald-600">*</span>
                    </label>
                    <select name="course_id" x-model="courseId" required
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11">
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">
                        Nama Bab / Modul Impor <span class="text-emerald-600">*</span>
                    </label>
                    <input type="text" name="module_title" x-model="moduleTitle" required
                           class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">
                        Tautan Playlist YouTube <span class="text-emerald-600">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-600">
                            <span class="material-symbols-outlined text-[18px]">playlist_play</span>
                        </span>
                        <input type="url" name="playlist_url" x-model="playlistUrl" required
                               placeholder="https://www.youtube.com/playlist?list=PL..."
                               class="w-full bg-white border border-slate-300 rounded-xl pl-9 pr-3 py-2.5 text-xs font-mono tabular-nums text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                    </div>
                    <p class="text-[11px] text-slate-500">Mendukung URL dengan parameter <code class="font-mono text-emerald-700 bg-emerald-50 px-1 rounded border border-emerald-200">?list=PL...</code>.</p>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-600 flex items-start gap-2.5">
                    <span class="material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5">info</span>
                    <p>Sistem akan secara berurutan mengekstrak daftar episode video, durasi, dan membuat urutan pembelajaran yang terstruktur di kursus target.</p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                <button type="button" @click="isPlaylistModalOpen = false" 
                        class="min-h-11 px-4 py-2 border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold transition-colors rounded-xl">
                    Batal
                </button>
                <button type="submit" :disabled="isSubmitting"
                        class="min-h-11 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]" :class="{ 'animate-spin': isSubmitting }">sync</span>
                    <span x-text="isSubmitting ? 'Mengimpor...' : 'Mulai Sinkronisasi Playlist'"></span>
                </button>
            </div>
        </form>
    </div>
</div>
