<!-- ======================================================== -->
<!-- MODAL EDIT VIDEO PELAJARAN                              -->
<!-- ======================================================== -->
<div x-show="isEditModalOpen" 
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto"
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
         class="relative w-full max-w-3xl bg-white rounded-xl border border-slate-200 shadow-2xl flex flex-col my-auto overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-200 flex items-start justify-between bg-white">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <span class="flex items-center justify-center w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700">
                        <span class="material-symbols-outlined text-[18px]">edit_note</span>
                    </span>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Video Pelajaran</h2>
                </div>
                <p class="text-xs text-slate-500">Perbarui informasi judul, tautan video YouTube, modul, atau status materi pembelajaran.</p>
            </div>
            <button type="button" @click="isEditModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg">
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
                        <label class="block text-xs font-semibold text-slate-900">Kursus</label>
                        <select name="course_id" x-model="courseId" required
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
                            @foreach($courses as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Modul / Bab</label>
                        <input type="text" name="module_title" x-model="moduleTitle" required
                               class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600"/>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">Judul Pelajaran</label>
                    <input type="text" name="title" x-model="title" required
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-semibold text-slate-900 focus:outline-none focus:border-indigo-600"/>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">URL Video YouTube</label>
                    <input type="url" name="youtube_url" x-model="youtubeUrl" required
                           class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs font-mono text-slate-900 focus:outline-none focus:border-indigo-600"/>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-center">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Status Publikasi</label>
                        <select name="status" x-model="status" required
                                class="w-full bg-white border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
                            <option value="published">Terverifikasi & Publik</option>
                            <option value="draft">Draf</option>
                            <option value="unlisted">Unlisted</option>
                        </select>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-slate-900 mb-1">Thumbnail Aktif</span>
                        <img :src="thumbnailUrl" class="w-full h-14 rounded-lg object-cover border border-slate-200" alt="Thumbnail Preview"/>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">Deskripsi Pelajaran</label>
                    <textarea name="description" x-model="description" rows="3"
                              class="w-full bg-white border border-slate-200 rounded-lg p-3 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 leading-relaxed"></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-end gap-3">
                <button type="button" @click="isEditModalOpen = false" 
                        class="px-4 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold rounded-lg hover:bg-slate-100">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-xs flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
