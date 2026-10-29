<!-- ======================================================== -->
<!-- MODAL OVERLAY & POPUP CONTAINER (Layar 4)               -->
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
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
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

            // Client-side instant extraction
            const id = window.extractYouTubeId ? window.extractYouTubeId(raw) : null;
            if (id) {
                this.videoId = id;
                this.isValid = true;
                this.thumbnailUrl = 'https://img.youtube.com/vi/' + id + '/hqdefault.jpg';
                this.embedUrl = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';

                // Query server for auto-detected title without blocking
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
         class="relative w-full max-w-5xl bg-white rounded-xl border border-slate-200 shadow-2xl flex flex-col my-auto max-h-[942px] overflow-hidden">
        
        <!-- Modal Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-start justify-between bg-white shrink-0">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined text-[18px]">add_to_queue</span>
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Impor Video Pelajaran YouTube</h2>
                </div>
                <p class="text-xs text-slate-500 max-w-2xl">
                    Masukkan URL video YouTube publik atau unlisted untuk mengekstrak ID video, meng-generate thumbnail beresolusi tinggi, dan menyinkronkan materi ke kurikulum.
                </p>
            </div>
            <button type="button" @click="isModalOpen = false" aria-label="Tutup modal" 
                    class="text-slate-400 hover:text-slate-700 hover:bg-slate-100 p-1.5 rounded-lg transition-colors">
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
                                <label class="block text-xs font-semibold text-slate-900">
                                    Pilih Kursus Target <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="course_id" x-model="courseId" required
                                            class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 appearance-none focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600/10 pr-9">
                                        @foreach($modalCourses as $c)
                                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                    <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400 text-[18px]">expand_more</span>
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label class="block text-xs font-semibold text-slate-900">
                                    Modul / Bab Kurikulum <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="module_title" x-model="moduleTitle" required
                                       placeholder="Cth: Modul 4: Server Components"
                                       class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600/10"/>
                            </div>
                        </div>

                        <!-- 2. YouTube Video URL Input Bar -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-semibold text-slate-900">
                                    URL Video YouTube <span class="text-rose-500">*</span>
                                </label>
                                <span x-show="isValid" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="material-symbols-outlined text-[13px]" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    <span>✓ URL Terverifikasi</span>
                                </span>
                                <span x-show="!isValid && url.length > 0" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="material-symbols-outlined text-[13px]">warning</span>
                                    <span>Mengekstrak...</span>
                                </span>
                            </div>
                            
                            <div class="relative flex items-center">
                                <div class="absolute left-3 flex items-center pointer-events-none">
                                    <div class="w-5 h-3.5 bg-rose-600 rounded-xs flex items-center justify-center">
                                        <span class="material-symbols-outlined text-white text-[12px]" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                    </div>
                                </div>
                                <input name="youtube_url" 
                                       x-model="url" 
                                       @input.debounce.300ms="parseUrl()"
                                       placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                                       required
                                       class="w-full bg-white border border-slate-200 rounded-lg pl-10 pr-28 py-2 text-xs text-slate-900 font-mono focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10 transition-colors" 
                                       type="url"/>
                                
                                <button type="button" @click="parseUrl()"
                                        class="absolute right-1.5 px-2.5 py-1 text-xs text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded-md transition-colors flex items-center gap-1 border border-indigo-200">
                                    <span class="material-symbols-outlined text-[14px]" :class="{ 'animate-spin': isLoading }">sync</span>
                                    <span>Ambil Otomatis</span>
                                </button>
                            </div>
                            <p class="text-[11px] text-slate-400 flex items-center gap-1 mt-1">
                                <span class="material-symbols-outlined text-[14px]">info</span>
                                Mendukung format <code class="font-mono text-indigo-600 font-semibold">watch?v=</code>, <code class="font-mono text-indigo-600 font-semibold">youtu.be/</code>, <code class="font-mono text-indigo-600 font-semibold">embed/</code>, dan <code class="font-mono text-indigo-600 font-semibold">shorts/</code>.
                            </p>
                        </div>

                        <!-- 3. Lesson Title Input -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="block text-xs font-semibold text-slate-900">
                                    Judul Pelajaran <span class="text-rose-500">*</span>
                                </label>
                                <span class="text-[11px] text-slate-400">Dapat disesuaikan</span>
                            </div>
                            <input name="title" x-model="title" required
                                   placeholder="Masukkan judul pelajaran lengkap..."
                                   class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/10" 
                                   type="text"/>
                        </div>

                        <!-- 4. Textarea for Lesson Description -->
                        <div class="space-y-1.5">
                            <div class="flex justify-between items-center">
                                <label class="block text-xs font-semibold text-slate-900">
                                    Deskripsi Pelajaran & Catatan Belajar
                                </label>
                                <span class="text-[11px] text-slate-400">Mendukung teks ringkasan</span>
                            </div>
                            <div class="border border-slate-200 rounded-lg overflow-hidden focus-within:border-indigo-600 focus-within:ring-1 focus-within:ring-indigo-600/10 transition-all bg-white">
                                <div class="bg-slate-50 border-b border-slate-200 px-2.5 py-1.5 flex flex-wrap items-center gap-1 text-slate-600">
                                    <button class="p-1 rounded hover:bg-slate-200 text-slate-700" title="Tebal" type="button">
                                        <span class="material-symbols-outlined text-[16px]">format_bold</span>
                                    </button>
                                    <button class="p-1 rounded hover:bg-slate-200 text-slate-700" title="Miring" type="button">
                                        <span class="material-symbols-outlined text-[16px]">format_italic</span>
                                    </button>
                                    <span class="w-[1px] h-4 bg-slate-200 mx-1"></span>
                                    <button class="p-1 rounded hover:bg-slate-200 text-slate-700" title="Sisipkan Tautan" type="button">
                                        <span class="material-symbols-outlined text-[16px]">link</span>
                                    </button>
                                    <button class="p-1 rounded hover:bg-slate-200 text-slate-700" title="Potongan Kode" type="button">
                                        <span class="material-symbols-outlined text-[16px]">code</span>
                                    </button>
                                    <div class="ml-auto flex items-center gap-1.5 text-[11px] text-slate-400">
                                        <span class="material-symbols-outlined text-[14px]">history</span>
                                        <span>Editor BKKBN SSK</span>
                                    </div>
                                </div>
                                <textarea name="description" x-model="description"
                                          placeholder="Tuliskan rangkuman materi, prasyarat, dan catatan belajar..."
                                          class="w-full p-3 bg-white text-xs text-slate-900 border-0 focus:ring-0 focus:outline-none resize-none leading-relaxed" 
                                          rows="4"></textarea>
                            </div>
                        </div>

                        <!-- Status hidden input -->
                        <input type="hidden" name="status" :value="status"/>
                    </div>

                    <!-- RIGHT COLUMN: YouTube Live Metadata Preview Box (5 cols) -->
                    <div class="lg:col-span-5 space-y-4">
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 space-y-3.5">
                            <!-- Header Bar of Preview Box -->
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-indigo-600 text-[18px]">tv</span>
                                    <span class="text-xs font-bold text-slate-900">Pratinjau Langsung YouTube</span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                    <span>Aliran Siap</span>
                                </span>
                            </div>

                            <!-- Video Thumbnail / Player Shell (16:9 ratio) -->
                            <div class="relative aspect-video w-full bg-slate-900 rounded-lg border border-slate-800 overflow-hidden group shadow-inner">
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
                                             class="w-full h-full object-cover opacity-85 group-hover:opacity-95 transition-opacity"/>
                                        
                                        <!-- Play Watermark Overlay Button -->
                                        <div class="absolute inset-0 flex items-center justify-center">
                                            <button type="button" @click="if(videoId) showPlayer = true" 
                                                    class="w-12 h-12 rounded-full bg-indigo-600/85 text-white flex items-center justify-center backdrop-blur-xs shadow-lg group-hover:scale-105 transition-transform border border-white/20">
                                                <span class="material-symbols-outlined text-[26px]" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                                            </button>
                                        </div>

                                        <!-- Corner Badges -->
                                        <div class="absolute top-2.5 left-2.5 flex items-center gap-1 px-2 py-0.5 rounded bg-black/75 backdrop-blur-xs text-white text-[11px] border border-white/10">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            <span>Sinkronisasi YouTube</span>
                                        </div>
                                        <div class="absolute bottom-2.5 right-2.5 px-2 py-0.5 rounded bg-slate-900/80 text-sky-400 text-[11px] font-mono font-semibold border border-sky-400/20">
                                            <span x-text="videoId ? 'ID: ' + videoId : '00:00'"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Detected Metadata Telemetry Grid -->
                            <div class="bg-white border border-slate-200 rounded-lg p-3 space-y-2 text-xs">
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <span class="text-slate-500">Pembuat Channel</span>
                                    <span class="text-slate-900 font-semibold flex items-center gap-1.5">
                                        <span class="w-4 h-4 rounded-full bg-indigo-600 text-white flex items-center justify-center text-[9px] font-bold">E</span>
                                        <span x-text="channelName">BKKBN Official</span>
                                        <span class="material-symbols-outlined text-[14px] text-sky-500" style="font-variation-settings: 'FILL' 1;">verified</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <span class="text-slate-500">Ekstraksi Video ID</span>
                                    <span class="font-mono text-indigo-600 font-semibold" x-text="videoId || 'Belum terdeteksi'"></span>
                                </div>
                                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                                    <span class="text-slate-500">Kualitas Aliran</span>
                                    <span class="text-slate-900 flex items-center gap-1 font-semibold">
                                        <span class="px-1.5 py-0.2 rounded bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200">1080p HD</span>
                                        <span>(60 FPS)</span>
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-slate-500">Teks & Subtitel</span>
                                    <span class="text-emerald-700 font-medium flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">closed_caption</span>
                                        Transkrip Otomatis Siap
                                    </span>
                                </div>
                            </div>

                            <!-- Ingestion Telemetry Note -->
                            <div class="p-2.5 rounded-lg bg-indigo-50/60 border border-indigo-200 text-indigo-900 flex gap-2 items-start text-xs">
                                <span class="material-symbols-outlined text-indigo-600 text-[16px] shrink-0 mt-0.5">neurology</span>
                                <p class="leading-relaxed">
                                    Sistem akan otomatis mengekstrak ID video 11-karakter dan meng-generate URL thumbnail YouTube resolusi tinggi (<code class="font-mono text-[10px]">maxresdefault.jpg</code>) saat disimpan.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Footer Actions -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row items-center justify-between gap-3 shrink-0">
                <!-- Left Side: Draft Action -->
                <button type="submit" @click="status = 'draft'"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors flex items-center justify-center gap-1.5 shadow-xs">
                    <span class="material-symbols-outlined text-[16px]">bookmark_border</span>
                    Simpan sebagai Draf
                </button>

                <!-- Right Side: Cancel & Publish CTA -->
                <div class="w-full sm:w-auto flex items-center justify-end gap-3">
                    <button type="button" @click="isModalOpen = false" 
                            class="px-4 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold transition-colors rounded-lg hover:bg-slate-100">
                        Batal
                    </button>
                    <button type="submit" @click="status = 'published'"
                            :disabled="!isValid"
                            :class="!isValid ? 'opacity-50 cursor-not-allowed bg-slate-300 text-slate-500' : 'bg-indigo-600 hover:bg-indigo-700 text-white'"
                            class="w-full sm:w-auto px-5 py-2.5 rounded-lg text-xs font-semibold transition-all flex items-center justify-center gap-2 shadow-xs">
                        <span class="material-symbols-outlined text-[18px]">cloud_upload</span>
                        Publikasikan Pelajaran
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
