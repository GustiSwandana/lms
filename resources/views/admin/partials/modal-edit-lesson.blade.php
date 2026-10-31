<!-- ======================================================== -->
<!-- MODAL EDIT VIDEO PELAJARAN (LIGHT THEME)                 -->
<!-- ======================================================== -->
<div x-show="isEditModalOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
     x-data="{
        lessonId: null,
        courseId: '',
        moduleTitle: '',
        title: '',
        youtubeUrl: '',
        description: '',
        status: 'published',
        thumbnailUrl: '',

        loadLesson(data) {
            this.lessonId = data.id;
            this.courseId = data.course_id;
            this.moduleTitle = data.module_title || '';
            this.title = data.title;
            this.youtubeUrl = data.youtube_url;
            this.description = data.description || '';
            this.status = data.status || 'published';
            this.thumbnailUrl = data.thumbnail_url || ('https://img.youtube.com/vi/' + data.youtube_video_id + '/hqdefault.jpg');
        }
     }"
     @open-edit-lesson-modal.window="isEditModalOpen = true; loadLesson($event.detail.lesson)">

    <div @click.away="isEditModalOpen = false" 
         class="relative w-full max-w-3xl bg-white rounded-3xl border border-slate-200 shadow-xl flex flex-col my-auto overflow-hidden text-left">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex items-start justify-between bg-slate-50/50">
            <div class="space-y-1">
                <div class="flex items-center gap-2.5">
                    <span class="flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                    </span>
                    <h2 class="text-lg font-bold text-slate-900 tracking-tight">Edit Video Pelajaran</h2>
                </div>
                <p class="text-xs text-slate-500">Perbarui informasi judul, tautan video YouTube, modul, atau status materi pembelajaran.</p>
            </div>
            <button type="button" @click="isEditModalOpen = false" aria-label="Tutup modal"
                    class="text-slate-400 hover:text-slate-700 min-h-11 min-w-11 flex items-center justify-center rounded-xl hover:bg-slate-100 transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Form -->
        <form :action="'/admin/lessons/' + lessonId" method="POST">
            @csrf
            @method('PUT')

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-700">Kursus</label>
                        <select name="course_id" x-model="courseId" required
                                class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11">
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-700">Modul / Bab</label>
                        <input type="text" name="module_title" x-model="moduleTitle" required
                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">Judul Pelajaran</label>
                    <input type="text" name="title" x-model="title" required
                           class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-semibold text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">URL Video YouTube</label>
                    <input type="url" name="youtube_url" x-model="youtubeUrl" required
                           class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs font-mono tabular-nums text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11"/>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">Status Publikasi</label>
                    <select name="status" x-model="status" required
                            class="w-full bg-white border border-slate-300 rounded-xl px-3 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 min-h-11">
                        <option value="published">Publik (Aktif)</option>
                        <option value="draft">Draf</option>
                        <option value="unlisted">Unlisted</option>
                    </select>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-700">Deskripsi Pelajaran</label>
                    <textarea name="description" x-model="description" rows="3"
                              class="w-full p-3.5 bg-white text-xs text-slate-900 border border-slate-300 rounded-xl focus:border-emerald-600 focus:outline-none resize-none leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-end gap-3">
                <button type="button" @click="isEditModalOpen = false" 
                        class="min-h-11 px-4 py-2 border border-slate-200 bg-white text-slate-700 hover:bg-slate-100 text-xs font-semibold transition-colors rounded-xl">
                    Batal
                </button>
                <button type="submit" 
                        class="min-h-11 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
