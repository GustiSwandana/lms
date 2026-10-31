<!-- ======================================================== -->
<!-- MODAL OVERLAY & POPUP CONTAINER (LIGHT THEME)            -->
<!-- ======================================================== -->
@php
    $modalCourses = $courses ?? \App\Models\Course::all();
@endphp
<div x-show="isModalOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
     x-data="{
        url: '',
        videoId: '',
        isValid: false,
        title: '',
        description: '',
        courseId: '{{ $modalCourses->first()->id ?? 1 }}',
        moduleTitle: 'Modul 1: Pengantar & Dasar',
        status: 'published',
        thumbnailUrl: '',
        embedUrl: '',
        channelName: 'BKKBN Official',
        durationText: '24 menit 18 detik',
        qualityText: '1080p HD (60 FPS)',
        showPlayer: false,
        isLoading: false,

        initModal(initialUrl = '') {
            if (initialUrl) {
                this.url = initialUrl;
                this.parseUrl();
            }
        },

        parseUrl() {
            const raw = this.url.trim();
            if (!raw) {
                this.isValid = false;
                this.videoId = '';
                this.thumbnailUrl = '';
                this.showPlayer = false;
                return;
            }

            const id = window.extractYouTubeId ? window.extractYouTubeId(raw) : null;
            if (id) {
                this.videoId = id;
                this.isValid = true;
                this.thumbnailUrl = 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
                this.embedUrl = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';

                this.isLoading = true;
                fetch('{{ route('admin.youtube.parse') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ url: raw })
                })
                .then(res => res.json())
                .then(data => {
                    this.isLoading = false;
                    if (data.valid) {
                        if (!this.title || this.title === 'Judul Pelajaran Terdeteksi Otomatis') {
                            this.title = data.title;
                        }
                        this.channelName = data.author_name || 'BKKBN Official';
                        this.thumbnailUrl = data.thumbnail_url || this.thumbnailUrl;
                    }
                })
                .catch(() => { this.isLoading = false; });
            } else {
                this.isValid = false;
                this.videoId = '';
                this.thumbnailUrl = '';
            }
        }
     }"
     @open-import-modal.window="isModalOpen = true; if($event.detail) { if($event.detail.url) { url = $event.detail.url; parseUrl(); } if($event.detail.courseId) { courseId = String($event.detail.courseId); } if($event.detail.moduleTitle) { moduleTitle = $event.detail.moduleTitle; } }">

    <!-- Modal Card -->
    <div @click.away="isModalOpen = false" 
         class="relative w-full max-w-5xl bg-white rounded-3xl border border-slate-200 shadow-xl flex flex-col my-auto max-h-[92vh] overflow-hidden text-left">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50 shrink-0">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">add_to_queue</span>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Impor Video Pelajaran YouTube</h2>
                </div>
                <p class="text-xs text-slate-500 max-w-2xl">
                    Masukkan URL video YouTube publik atau unlisted untuk mengekstrak ID video, meng-generate thumbnail beresolusi tinggi, dan menyinkronkan materi ke kurikulum SSK.
                </p>
            </div>
            <button type="button" @click="isModalOpen = false" aria-label="Tutup modal" 
                    class="text-slate-400 hover:text-slate-700 min-h-11 min-w-11 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form for Submission -->
        <form action="{{ route('admin.lessons.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            <!-- Modal Body (Two-Column Layout: Form Fields vs Live Preview) -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                    
                    <!-- LEFT / MAIN COLUMN: Form Controls (7 cols) -->
                    <div class="lg:col-span-7 space-y-4">
                        
                        <!-- 1. Course & Category Selector (2-Column Grid) -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700">
                                    Pilih Kursus Target <span class="text-emerald-600">*</span>
                                </label>
                                <select name="course_id" x-model="courseId" required
                                        class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11">
                                    @foreach($modalCourses as $c)
                                        <option value="{{ $c->id }}">{{ $c->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-700">
                                    Modul / Bab Kurikulum <span class="text-emerald-600">*</span>
                                </label>
                                <input type="text" name="module_title" x-model="moduleTitle" required
                                       placeholder="Cth: Modul 1: Wawasan SSK"
                                       class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                            </div>
                        </div>

                        <!-- 2. YouTube Video URL Input Bar -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-semibold text-slate-700">
                                    URL Video YouTube <span class="text-emerald-600">*</span>
                                </label>
                                <span x-show="isValid" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    <span>✓ URL Terverifikasi</span>
                                </span>
                                <span x-show="!isValid && url.length > 0" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="material-symbols-outlined text-[13px]">warning</span>
                                    <span>Mengekstrak...</span>
                                </span>
                            </div>
                            
                            <div class="relative flex items-center">
                                <input name="youtube_url" 
                                       x-model="url" 
                                       @input.debounce.300ms="parseUrl()"
                                       placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                                       required
                                       class="w-full bg-white border border-slate-300 rounded-xl pl-3.5 pr-28 py-2.5 text-xs text-slate-900 font-mono tabular-nums focus:outline-none focus:border-emerald-600 min-h-11 transition-colors" 
                                       type="url"/>
                                
                                <button type="button" @click="parseUrl()"
                                        class="absolute right-1.5 px-3 py-1.5 text-xs text-emerald-700 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors flex items-center gap-1 border border-emerald-200 font-medium">
                                    <span class="material-symbols-outlined text-[14px]" :class="{ 'animate-spin': isLoading }">sync</span>
                                    <span>Ambil</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-500 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[14px] text-emerald-600">info</span>
                                Mendukung format <code class="font-mono text-emerald-700 bg-emerald-50 px-1 rounded border border-emerald-200">watch?v=</code>, <code class="font-mono text-emerald-700 bg-emerald-50 px-1 rounded border border-emerald-200">youtu.be/</code>, <code class="font-mono text-emerald-700 bg-emerald-50 px-1 rounded border border-emerald-200">embed/</code>, dan <code class="font-mono text-emerald-700 bg-emerald-50 px-1 rounded border border-emerald-200">shorts/</code>.
                            </p>
                        </div>

                        <!-- 3. Lesson Title Input -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">
                                Judul Pelajaran <span class="text-emerald-600">*</span>
                            </label>
                            <input name="title" x-model="title" required
                                   placeholder="Masukkan judul pelajaran lengkap..."
                                   class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11" 
                                   type="text"/>
                        </div>

                        <!-- 4. Textarea for Lesson Description -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700">
                                Deskripsi Pelajaran & Catatan Belajar
                            </label>
                            <textarea name="description" x-model="description"
                                      placeholder="Tuliskan rangkuman materi, prasyarat, dan catatan belajar..."
                                      class="w-full p-3.5 bg-white text-xs text-slate-900 border border-slate-300 rounded-xl focus:border-emerald-600 focus:outline-none resize-none leading-relaxed" 
                                      rows="3"></textarea>
                        </div>

                        <!-- Status hidden input -->
                        <input type="hidden" name="status" :value="status"/>
                    </div>

                    <!-- RIGHT COLUMN: YouTube Live Metadata Preview Box (5 cols) -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 space-y-3.5">
                            <!-- Header Bar of Preview Box -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-emerald-600 text-[18px]">tv</span>
                                    <span class="text-xs font-bold text-slate-900">Pratinjau YouTube</span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span>Aliran Siap</span>
                                </span>
                            </div>

                            <!-- Video Thumbnail / Player Shell (16:9 ratio) -->
                            <div class="relative aspect-video w-full bg-slate-900 rounded-xl border border-slate-800 overflow-hidden group">
                                <template x-if="showPlayer && videoId">
                                    <iframe class="w-full h-full absolute inset-0 z-10 border-0" 
                                            :src="embedUrl" 
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                            allowfullscreen>
                                    </iframe>
                                </template>

                                <template x-if="!showPlayer">
                                    <div class="w-full h-full relative">
                                        <img :src="thumbnailUrl || 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80'" 
                                             alt="Thumbnail Live Preview"
                                             class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity"/>
                                        
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <button type="button" @click="if(videoId) showPlayer = true" 
                                                    class="min-h-12 min-w-12 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-105">
                                                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                            </button>
                                        </div>

                                        <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-slate-900/80 text-emerald-400 text-[11px] font-mono tabular-nums font-semibold border border-slate-700">
                                            <span x-text="videoId ? 'ID: ' + videoId : '00:00'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Detected Metadata Telemetry Grid -->
                            <div class="bg-white border border-slate-200 rounded-xl p-3 space-y-2 text-xs">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <span class="text-slate-500">Kanal Pengunggah</span>
                                    <span class="text-slate-900 font-semibold flex items-center gap-1.5">
                                        <span x-text="channelName">BKKBN Official</span>
                                        <span class="material-symbols-outlined text-[14px] text-emerald-600" style="font-variation-settings: 'FILL' 1;">verified</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <span class="text-slate-500">Video ID</span>
                                    <span class="font-mono tabular-nums text-emerald-700 font-semibold" x-text="videoId || 'Belum terdeteksi'"></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Kualitas</span>
                                    <span class="text-slate-900 flex items-center gap-1 font-semibold font-mono tabular-nums">
                                        1080p HD (60 FPS)
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                <button type="submit" @click="status = 'draft'"
                        class="w-full sm:w-auto min-h-11 px-4 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-100 transition-colors flex items-center justify-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">bookmark_border</span>
                    Simpan sebagai Draf
                </button>

                <div class="w-full sm:w-auto flex items-center justify-end gap-3">
                    <button type="button" @click="isModalOpen = false" 
                            class="min-h-11 px-4 py-2 border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold transition-colors rounded-xl">
                        Batal
                    </button>
                    <button type="submit" @click="status = 'published'"
                            :disabled="!isValid"
                            :class="!isValid ? 'opacity-40 cursor-not-allowed bg-slate-200 text-slate-500' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs'"
                            class="w-full sm:w-auto min-h-11 px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">cloud_upload</span>
                        Publikasikan Pelajaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
