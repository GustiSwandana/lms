@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 pb-20" 
     x-data="{
        activeTab: 'info',
        saveStatus: 'idle', // 'idle' | 'saving' | 'saved' | 'error'
        saveStatusTimer: null,
        
        course: {{ Js::from($course) }},
        categories: {{ Js::from($categories) }},
        quiz: {{ Js::from($quiz) }},
        quizQuestions: {{ Js::from($quiz->questions ?? []) }},
        lessons: {{ Js::from($course->lessons ?? []) }},

        // YouTube Smart Ingestion
        newYoutubeUrl: '',
        newModuleTitle: 'Modul 1: Pendahuluan',
        newLessonTitle: '',
        isIngesting: false,
        ingestError: '',

        // Edit Lesson Modal State
        isEditLessonOpen: false,
        editingLesson: null,

        // Quiz Question Form State
        newQText: '',
        newQOptions: ['', '', '', ''],
        newQCorrectIndex: 0,
        newQExplanation: '',
        isSavingQuestion: false,

        // Publish Validation Modal
        isPublishModalOpen: false,
        publishErrors: [],
        isPublishing: false,

        // Toast State
        toast: { show: false, message: '', type: 'success' },
        showToast(msg, type = 'success') {
            this.toast.message = msg;
            this.toast.type = type;
            this.toast.show = true;
            setTimeout(() => { this.toast.show = false; }, 3500);
        },

        setSaveState(state) {
            this.saveStatus = state;
            if (this.saveStatusTimer) clearTimeout(this.saveStatusTimer);
            if (state === 'saved') {
                this.saveStatusTimer = setTimeout(() => {
                    this.saveStatus = 'idle';
                }, 2200);
            }
        },

        // ==========================================
        // TAB 1: AUTO-SAVE COURSE INFO (AJAX PATCH)
        // ==========================================
        async saveInfo() {
            this.setSaveState('saving');
            try {
                const payload = {
                    title: this.course.title,
                    category_id: this.course.category_id,
                    level: this.course.level,
                    instructor_name: this.course.instructor_name,
                    instructor_title: this.course.instructor_title,
                    description: this.course.description,
                    thumbnail_url: this.course.thumbnail_url,
                    is_visible_to_guest: Boolean(this.course.is_visible_to_guest),
                };

                const res = await fetch('{{ route('admin.courses.builder.info', $course->id) }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.setSaveState('saved');
                    if (data.course) {
                        this.course.slug = data.course.slug;
                    }
                } else {
                    this.setSaveState('error');
                    this.showToast(data.message || 'Gagal menyimpan perubahan.', 'error');
                }
            } catch (err) {
                this.setSaveState('error');
            }
        },

        async uploadThumbnail(event) {
            const file = event.target.files[0];
            if (!file) return;

            this.setSaveState('saving');
            const formData = new FormData();
            formData.append('thumbnail_file', file);

            try {
                const res = await fetch('{{ route('admin.courses.builder.info', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'X-HTTP-Method-Override': 'PATCH',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.course.thumbnail_url = data.course.thumbnail_url;
                    this.setSaveState('saved');
                    this.showToast('Thumbnail berhasil diperbarui!');
                } else {
                    this.setSaveState('error');
                    this.showToast('Gagal mengunggah thumbnail.', 'error');
                }
            } catch (e) {
                this.setSaveState('error');
            }
        },

        // ==========================================
        // TAB 2: SMART YOUTUBE INGESTION & LESSONS
        // ==========================================
        async smartIngest() {
            const url = this.newYoutubeUrl.trim();
            if (!url) {
                this.ingestError = 'Silakan masukkan URL YouTube terlebih dahulu.';
                return;
            }

            this.isIngesting = true;
            this.ingestError = '';

            try {
                const res = await fetch('{{ route('admin.courses.builder.lessons.store', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        youtube_url: url,
                        module_title: this.newModuleTitle.trim() || 'Modul 1: Pendahuluan',
                        title: this.newLessonTitle.trim() || null
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.lessons.push(data.lesson);
                    this.newYoutubeUrl = '';
                    this.newLessonTitle = '';
                    this.showToast(data.message || 'Materi video berhasil ditambahkan!');
                } else {
                    this.ingestError = data.message || 'Tautan YouTube tidak valid.';
                    this.showToast(this.ingestError, 'error');
                }
            } catch (e) {
                this.ingestError = 'Terjadi kendala jaringan saat menyinkronkan video.';
                this.showToast(this.ingestError, 'error');
            } finally {
                this.isIngesting = false;
            }
        },

        moveLesson(index, direction) {
            const targetIdx = index + direction;
            if (targetIdx < 0 || targetIdx >= this.lessons.length) return;

            const temp = this.lessons[index];
            this.lessons[index] = this.lessons[targetIdx];
            this.lessons[targetIdx] = temp;

            // Re-assign sort order
            this.lessons.forEach((les, idx) => {
                les.sort_order = idx + 1;
            });

            this.syncLessonsOrder();
        },

        draggedIdx: null,
        onDragStart(idx) {
            this.draggedIdx = idx;
        },
        onDrop(targetIdx) {
            if (this.draggedIdx === null || this.draggedIdx === targetIdx) return;
            const item = this.lessons.splice(this.draggedIdx, 1)[0];
            this.lessons.splice(targetIdx, 0, item);
            this.draggedIdx = null;

            this.lessons.forEach((les, idx) => {
                les.sort_order = idx + 1;
            });
            this.syncLessonsOrder();
        },

        async syncLessonsOrder() {
            this.setSaveState('saving');
            try {
                const payload = {
                    lessons: this.lessons.map(l => ({
                        id: l.id,
                        sort_order: l.sort_order,
                        module_title: l.module_title
                    }))
                };

                const res = await fetch('{{ route('admin.courses.builder.lessons.reorder', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (res.ok) {
                    this.setSaveState('saved');
                } else {
                    this.setSaveState('error');
                }
            } catch (e) {
                this.setSaveState('error');
            }
        },

        async deleteLesson(lessonId) {
            if (!confirm('Hapus video pelajaran ini dari kurikulum?')) return;

            try {
                const res = await fetch('/admin/courses/{{ $course->id }}/builder/lessons/' + lessonId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.lessons = this.lessons.filter(l => l.id !== lessonId);
                    this.showToast(data.message || 'Materi berhasil dihapus.');
                }
            } catch (e) {
                this.showToast('Gagal menghapus materi.', 'error');
            }
        },

        // ==========================================
        // TAB 3: QUIZ BUILDER & SETTINGS (AJAX)
        // ==========================================
        async saveQuizSettings() {
            this.setSaveState('saving');
            try {
                const res = await fetch('{{ route('admin.courses.builder.quiz.save', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        title: this.quiz.title,
                        passing_grade: parseInt(this.quiz.passing_grade) || 75,
                        time_limit_minutes: this.quiz.time_limit_minutes ? parseInt(this.quiz.time_limit_minutes) : null,
                        shuffle_questions: Boolean(this.quiz.shuffle_questions)
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.setSaveState('saved');
                    this.showToast('Pengaturan kuis evaluasi berhasil disimpan.');
                } else {
                    this.setSaveState('error');
                }
            } catch (e) {
                this.setSaveState('error');
            }
        },

        async addQuestion() {
            const qText = this.newQText.trim();
            const opts = this.newQOptions.map(o => o.trim()).filter(o => o.length > 0);

            if (!qText) {
                this.showToast('Harap tuliskan butir pertanyaan.', 'error');
                return;
            }
            if (opts.length < 2) {
                this.showToast('Harap isi minimal 2 pilihan jawaban.', 'error');
                return;
            }

            this.isSavingQuestion = true;
            try {
                const res = await fetch('{{ route('admin.courses.builder.quiz.questions.store', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        quiz_id: this.quiz.id,
                        question: qText,
                        options: opts,
                        correct_answer_index: parseInt(this.newQCorrectIndex) || 0,
                        explanation: this.newQExplanation.trim() || null
                    })
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.quizQuestions.push(data.question);
                    this.newQText = '';
                    this.newQOptions = ['', '', '', ''];
                    this.newQCorrectIndex = 0;
                    this.newQExplanation = '';
                    this.showToast('Pertanyaan kuis berhasil ditambahkan!');
                } else {
                    this.showToast(data.message || 'Gagal menyimpan pertanyaan.', 'error');
                }
            } catch (e) {
                this.showToast('Gagal menghubungi server.', 'error');
            } finally {
                this.isSavingQuestion = false;
            }
        },

        async deleteQuestion(qId) {
            if (!confirm('Hapus butir soal kuis ini?')) return;

            try {
                const res = await fetch('/admin/courses/{{ $course->id }}/builder/quiz/questions/' + qId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.quizQuestions = this.quizQuestions.filter(q => q.id !== qId);
                    this.showToast('Soal kuis berhasil dihapus.');
                }
            } catch (e) {
                this.showToast('Gagal menghapus soal.', 'error');
            }
        },

        // ==========================================
        // PUBLISH & UNPUBLISH FLOW WITH VALIDATION
        // ==========================================
        async publishCourse() {
            this.isPublishing = true;
            try {
                const res = await fetch('{{ route('admin.courses.builder.publish', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.course.status = 'published';
                    this.showToast(data.message || 'Kursus berhasil dipublikasikan!');
                } else {
                    this.publishErrors = data.errors || [data.message || 'Validasi penerbitan gagal.'];
                    this.isPublishModalOpen = true;
                }
            } catch (e) {
                this.showToast('Terjadi kesalahan jaringan saat mempublikasikan kursus.', 'error');
            } finally {
                this.isPublishing = false;
            }
        },

        async unpublishCourse() {
            if (!confirm('Tarik kursus kembali ke status Draf? Siswa tidak akan dapat mengaksesnya sementara waktu.')) return;

            try {
                const res = await fetch('{{ route('admin.courses.builder.unpublish', $course->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await res.json();
                if (res.ok && data.success) {
                    this.course.status = 'draft';
                    this.showToast(data.message || 'Kursus beralih ke status Draf.');
                }
            } catch (e) {
                this.showToast('Gagal mengubah status kursus.', 'error');
            }
        }
     }">

    <!-- =========================================================================
         STICKY HEADER: SAAS UNIFIED BUILDER COMMAND BAR
         ========================================================================= -->
    <header class="sticky top-0 z-30 bg-white/95 backdrop-blur-md border border-slate-200 rounded-xl p-4 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <!-- Left: Course Title, School Pill & Auto-Save Badge -->
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.courses.index') }}" 
               class="p-2 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors shrink-0" 
               title="Kembali ke Daftar Kursus">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>

            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap">
                    <h1 class="text-base sm:text-lg font-bold text-slate-900 truncate max-w-sm sm:max-w-md lg:max-w-xl" 
                        x-text="course.title || 'Kursus Baru Tanpa Judul'"></h1>
                    
                    <!-- Status Badge -->
                    <template x-if="course.status === 'published'">
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Terbit (Publik)
                        </span>
                    </template>
                    <template x-if="course.status !== 'published'">
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2.5 py-0.5 rounded-full">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                            Draf
                        </span>
                    </template>
                </div>

                <!-- Auto-Save Feedback Indicator -->
                <div class="flex items-center gap-2 text-[11px] mt-0.5">
                    <span class="text-slate-400" x-text="course.school_name || 'EduTube'"></span>
                    <span class="text-slate-300">•</span>
                    
                    <!-- Save Indicator Pill -->
                    <span x-show="saveStatus === 'saving'" class="text-indigo-600 flex items-center gap-1 font-medium">
                        <svg class="animate-spin h-3 w-3 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                        <span>Menyimpan otomatis...</span>
                    </span>

                    <span x-show="saveStatus === 'saved'" class="text-emerald-700 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        <span>Semua perubahan tersimpan</span>
                    </span>

                    <span x-show="saveStatus === 'error'" class="text-rose-600 flex items-center gap-1 font-medium">
                        <span class="material-symbols-outlined text-[14px]">error</span>
                        <span>Gagal menyimpan</span>
                    </span>

                    <span x-show="saveStatus === 'idle'" class="text-slate-400">
                        Cloud Auto-Save Aktif
                    </span>
                </div>
            </div>
        </div>

        <!-- Right: Preview, Quick Actions & Primary Sticky Publish Button -->
        <div class="flex items-center gap-2 shrink-0 self-end md:self-auto">
            <!-- Student Preview Button -->
            <a :href="'/belajar/' + course.slug" target="_blank"
               class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium flex items-center gap-1.5 transition-all shadow-xs"
               title="Lihat Pratinjau Tampilan Siswa">
                <span class="material-symbols-outlined text-[16px] text-slate-400">visibility</span>
                <span class="hidden sm:inline">Pratinjau Siswa</span>
            </a>

            <!-- Unpublish Button (if published) -->
            <button type="button" x-show="course.status === 'published'" @click="unpublishCourse()"
                    class="px-3.5 py-2 rounded-lg border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-medium transition-all shadow-xs flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">archive</span>
                <span>Tarik ke Draf</span>
            </button>

            <!-- Primary Publish Button (Sticky Action) -->
            <button type="button" x-show="course.status !== 'published'" @click="publishCourse()" :disabled="isPublishing"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">publish</span>
                <span x-text="isPublishing ? 'Memvalidasi...' : 'Terbitkan Kursus'"></span>
            </button>
        </div>
    </header>

    <!-- =========================================================================
         UNIFIED TAB NAVIGATION
         ========================================================================= -->
    <nav class="flex items-center border-b border-slate-200 space-x-2 sm:space-x-4 text-xs font-semibold overflow-x-auto">
        <button type="button" @click="activeTab = 'info'"
                :class="activeTab === 'info' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'"
                class="pb-3 px-3 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px]">tune</span>
            <span>1. Info & Pengaturan</span>
        </button>

        <button type="button" @click="activeTab = 'curriculum'"
                :class="activeTab === 'curriculum' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'"
                class="pb-3 px-3 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px]">video_library</span>
            <span>2. Kurikulum & Materi</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-mono" x-text="lessons.length"></span>
        </button>

        <button type="button" @click="activeTab = 'quiz'"
                :class="activeTab === 'quiz' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300'"
                class="pb-3 px-3 border-b-2 transition-all flex items-center gap-2 whitespace-nowrap">
            <span class="material-symbols-outlined text-[18px]">quiz</span>
            <span>3. Evaluasi & Kuis</span>
            <span class="px-2 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 font-mono" x-text="quizQuestions.length"></span>
        </button>
    </nav>

    <!-- =========================================================================
         TAB 1: INFO KURSUS (AUTO-SAVE)
         ========================================================================= -->
    <section x-show="activeTab === 'info'" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Panel (8 cols): Metadata & Form -->
            <div class="lg:col-span-8 bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-5">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Detail Informasi Kursus</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Setiap perubahan tersimpan otomatis ke cloud.</p>
                </div>                <!-- Course Title -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">Judul Program Kursus <span class="text-rose-500">*</span></label>
                    <input type="text" x-model="course.title" @input.debounce.750ms="saveInfo()"
                           placeholder="Contoh: Pemrograman Modern Laravel 12 & Alpine.js"
                           class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3.5 py-2 text-xs text-slate-900 focus:outline-none focus:ring-1 focus:ring-indigo-600/20 transition-all font-semibold"/>
                </div>

                <!-- Category & Difficulty Level -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Kategori Kursus <span class="text-rose-500">*</span></label>
                        <select x-model="course.category_id" @change="saveInfo()"
                                class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none">
                            <template x-for="cat in categories" :key="cat.id">
                                <option :value="cat.id" x-text="cat.name" :selected="course.category_id === cat.id"></option>
                            </template>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Tingkat Kesulitan</label>
                        <select x-model="course.level" @change="saveInfo()"
                                class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none">
                            <option value="Semua Tingkat">Semua Tingkat</option>
                            <option value="Pemula">Pemula (Dasar)</option>
                            <option value="Menengah">Menengah</option>
                            <option value="Lanjutan">Lanjutan (Mahir)</option>
                        </select>
                    </div>
                </div>

                <!-- Instructor Info -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Nama Pengajar / Instruktur</label>
                        <input type="text" x-model="course.instructor_name" @input.debounce.750ms="saveInfo()"
                               placeholder="Nama Guru atau Mentor"
                               class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3.5 py-2 text-xs text-slate-900 focus:outline-none"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-900">Gelar / Jabatan Instruktur</label>
                        <input type="text" x-model="course.instructor_title" @input.debounce.750ms="saveInfo()"
                               placeholder="Contoh: Guru Rekayasa Perangkat Lunak"
                               class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3.5 py-2 text-xs text-slate-900 focus:outline-none"/>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-semibold text-slate-900">Deskripsi & Silabus Kursus <span class="text-rose-500">*</span></label>
                    <textarea x-model="course.description" @input.debounce.750ms="saveInfo()" rows="4"
                              placeholder="Jelaskan kompetensi apa yang akan dipelajari siswa dalam kursus ini..."
                              class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg p-3 text-xs text-slate-900 focus:outline-none leading-relaxed"></textarea>
                </div>

                @if(auth()->user()?->canCurateGuestContent())
                <!-- Guest / Penyuluh Access Curation (Admin Pusat Only) -->
                <div class="p-3.5 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-600 text-white flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-[18px]">visibility</span>
                        </div>
                        <div>
                            <label for="guest_toggle" class="text-xs font-bold text-indigo-950 block cursor-pointer">
                                Akses Mode Galeri (Penyuluh & Tamu)
                            </label>
                            <p class="text-[11px] text-indigo-700 mt-0.5">
                                Aktifkan agar kursus ini dapat diakses dan dijelajahi oleh akun Penyuluh / Tamu dalam mode lihat-saja.
                            </p>
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                        <input id="guest_toggle" type="checkbox" x-model="course.is_visible_to_guest" @change="saveInfo()" class="sr-only peer">
                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                </div>
                @endif
            </div>

            <!-- Right Panel (4 cols): Thumbnail & Media -->
            <div class="lg:col-span-4 bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Thumbnail Cover</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Gambar rasio 16:9 untuk kartu katalog.</p>
                </div>

                <!-- Image Live Preview -->
                <div class="aspect-video w-full rounded-lg bg-slate-900 overflow-hidden border border-slate-200 relative group">
                    <img :src="course.thumbnail_url || 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&auto=format&fit=crop&q=80'" 
                         class="w-full h-full object-cover" alt="Thumbnail Preview"/>
                </div>

                <!-- Upload File or URL -->
                <div class="space-y-3">
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-800">Unggah Gambar</label>
                        <input type="file" accept="image/*" @change="uploadThumbnail($event)"
                               class="w-full text-xs text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-medium file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-800">Atau Tautan URL Thumbnail</label>
                        <input type="text" x-model="course.thumbnail_url" @input.debounce.750ms="saveInfo()"
                               placeholder="https://..."
                               class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3 py-1.5 text-xs text-slate-900 focus:outline-none"/>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         TAB 2: KURIKULUM & SMART YOUTUBE INGESTION (AJAX)
         ========================================================================= -->
    <section x-show="activeTab === 'curriculum'" class="space-y-6">
        <!-- Smart YouTube Ingestion Card -->
        <div class="bg-slate-900 rounded-xl p-5 sm:p-6 text-white shadow-xs space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-white/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px] text-indigo-400">smart_display</span>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-white tracking-tight">Smart YouTube Ingestion (Asinkron)</h2>
                    <p class="text-xs text-slate-400">Tempelkan tautan video YouTube, durasi dan judul diekstrak otomatis.</p>
                </div>
            </div>

            <form @submit.prevent="smartIngest()" class="grid grid-cols-1 md:grid-cols-12 gap-2.5 pt-1">
                <div class="md:col-span-4">
                    <input type="text" x-model="newModuleTitle"
                           placeholder="Nama Bab / Modul (contoh: Modul 1: Dasar)"
                           class="w-full bg-white/10 border border-white/20 text-white placeholder-slate-400 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-indigo-400"/>
                </div>

                <div class="md:col-span-6">
                    <input type="text" x-model="newYoutubeUrl" @keydown.enter.prevent="smartIngest()"
                           placeholder="Tempelkan URL YouTube (cth: https://www.youtube.com/watch?v=...)"
                           class="w-full bg-white text-slate-900 placeholder:text-slate-400 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-500 font-medium"/>
                </div>

                <div class="md:col-span-2">
                    <button type="submit" :disabled="isIngesting"
                            class="w-full h-full py-2 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-xs transition-colors flex items-center justify-center gap-1.5 disabled:opacity-50">
                        <span class="material-symbols-outlined text-[16px]">add_circle</span>
                        <span x-text="isIngesting ? 'Memproses...' : '+ Tambah Video'"></span>
                    </button>
                </div>
            </form>

            <div x-show="ingestError" class="text-rose-300 text-xs font-semibold flex items-center gap-1.5 pt-1">
                <span class="material-symbols-outlined text-[16px]">error</span>
                <span x-text="ingestError"></span>
            </div>
        </div>

        <!-- Lessons Silabus Listing & Drag-and-Drop Reorder -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Silabus Materi Pelajaran</h3>
                    <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-semibold" x-text="lessons.length + ' Video'"></span>
                </div>
                <p class="text-xs text-slate-400 hidden sm:block">Gunakan panah atau geser kartu untuk mengatur urutan materi.</p>
            </div>

            <!-- Lessons List -->
            <div class="space-y-2.5">
                <template x-for="(les, idx) in lessons" :key="les.id">
                    <div draggable="true"
                         @dragstart="onDragStart(idx)"
                         @dragover.prevent
                         @drop="onDrop(idx)"
                         class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-3 rounded-lg border border-slate-200 hover:border-slate-300 bg-slate-50/60 hover:bg-white transition-all group">
                        
                        <!-- Left: Drag Handle, Number, Thumb, Title -->
                        <div class="flex items-center gap-3 min-w-0">
                            <!-- Drag Handle & Sort Order Number -->
                            <div class="flex items-center gap-1 text-slate-400 cursor-grab active:cursor-grabbing">
                                <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-slate-600">drag_indicator</span>
                                <span class="font-mono text-xs font-bold w-4 text-center" x-text="idx + 1"></span>
                            </div>

                            <!-- Thumbnail -->
                            <img :src="les.thumbnail_url || ('https://img.youtube.com/vi/' + les.youtube_video_id + '/hqdefault.jpg')" 
                                 class="w-14 h-9 rounded-md object-cover border border-slate-200 shrink-0 bg-slate-900" alt="Thumb"/>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="px-1.5 py-0.2 rounded text-[10px] bg-slate-100 text-slate-700 font-medium" x-text="les.module_title || 'Modul Umum'"></span>
                                    <span class="text-[11px] font-mono text-slate-400" x-text="les.duration_formatted || '10:00'"></span>
                                </div>
                                <h4 class="text-xs font-semibold text-slate-900 truncate max-w-sm sm:max-w-md lg:max-w-xl mt-0.5" x-text="les.title"></h4>
                            </div>
                        </div>

                        <!-- Right: Reorder Up/Down & Delete Action -->
                        <div class="flex items-center gap-1 shrink-0 self-end sm:self-auto">
                            <!-- Move Up -->
                            <button type="button" @click="moveLesson(idx, -1)" :disabled="idx === 0"
                                    class="p-1 rounded hover:bg-slate-200 text-slate-400 hover:text-slate-700 disabled:opacity-25 transition-colors" title="Geser ke Atas">
                                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_up</span>
                            </button>

                            <!-- Move Down -->
                            <button type="button" @click="moveLesson(idx, 1)" :disabled="idx === lessons.length - 1"
                                    class="p-1 rounded hover:bg-slate-200 text-slate-400 hover:text-slate-700 disabled:opacity-25 transition-colors" title="Geser ke Bawah">
                                <span class="material-symbols-outlined text-[18px]">keyboard_arrow_down</span>
                            </button>

                            <!-- Delete -->
                            <button type="button" @click="deleteLesson(les.id)"
                                    class="p-1 rounded hover:bg-rose-50 text-slate-400 hover:text-rose-600 transition-colors" title="Hapus Video">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </div>
                </template>

                <div x-show="lessons.length === 0" class="py-10 text-center text-slate-400 border border-dashed border-slate-200 rounded-xl space-y-1">
                    <span class="material-symbols-outlined text-[32px] text-slate-300">video_library</span>
                    <p class="text-xs font-semibold text-slate-700">Belum ada video materi di kursus ini.</p>
                    <p class="text-[11px] text-slate-400">Tempelkan tautan video YouTube di atas untuk menambahkan materi pertama.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         TAB 3: EVALUASI & KUIS (AJAX)
         ========================================================================= -->
    <section x-show="activeTab === 'quiz'" class="space-y-6">
        <!-- Quiz Master Configuration Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pengaturan Evaluasi Kuis</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Tentukan standar kelulusan dan batasan pengerjaan kuis.</p>
                </div>

                <button type="button" @click="saveQuizSettings()"
                        class="px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-xs flex items-center gap-1.5 self-start sm:self-auto">
                    <span class="material-symbols-outlined text-[16px]">save</span>
                    <span>Simpan Pengaturan Kuis</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="space-y-1.5">
                    <label class="block font-semibold text-slate-800">Passing Grade Kelulusan (%)</label>
                    <input type="number" x-model="quiz.passing_grade" min="10" max="100"
                           class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3 py-2 text-xs font-bold text-indigo-700"/>
                    <p class="text-[11px] text-slate-400">Nilai minimal untuk klaim sertifikat.</p>
                </div>

                <div class="space-y-1.5">
                    <label class="block font-semibold text-slate-800">Batas Waktu (Menit)</label>
                    <input type="number" x-model="quiz.time_limit_minutes" min="1" max="180" placeholder="Kosongkan jika tanpa timer"
                           class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg px-3 py-2 text-xs text-slate-900"/>
                    <p class="text-[11px] text-slate-400">Timer hitung mundur pengerjaan (opsional).</p>
                </div>

                <div class="space-y-1.5 flex flex-col justify-center">
                    <label class="block font-semibold text-slate-800">Acak Urutan Soal (Shuffle)</label>
                    <label class="inline-flex items-center gap-2 cursor-pointer pt-1">
                        <input type="checkbox" x-model="quiz.shuffle_questions" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 h-4 w-4"/>
                        <span class="text-xs text-slate-700">Acak urutan soal untuk setiap siswa</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Add Question Form Card -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">+ Tambah Butir Soal Pilihan Ganda</h3>
                <p class="text-xs text-slate-500 mt-0.5">Masukkan pertanyaan, 4 pilihan opsi, dan kunci jawaban yang benar.</p>
            </div>

            <form @submit.prevent="addQuestion()" class="space-y-4 text-xs">
                <div class="space-y-1.5">
                    <label class="font-semibold text-slate-800">Pertanyaan Kuis <span class="text-rose-500">*</span></label>
                    <textarea x-model="newQText" required rows="2" placeholder="Tuliskan pertanyaan materi..."
                              class="w-full bg-slate-50 focus:bg-white border border-slate-200 focus:border-indigo-600 rounded-lg p-3 text-xs text-slate-900 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium text-slate-700">Pilihan A (Index 0)</label>
                        <input type="text" x-model="newQOptions[0]" required placeholder="Opsi A"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-900"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium text-slate-700">Pilihan B (Index 1)</label>
                        <input type="text" x-model="newQOptions[1]" required placeholder="Opsi B"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-900"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium text-slate-700">Pilihan C (Index 2)</label>
                        <input type="text" x-model="newQOptions[2]" placeholder="Opsi C"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-900"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-medium text-slate-700">Pilihan D (Index 3)</label>
                        <input type="text" x-model="newQOptions[3]" placeholder="Opsi D"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-900"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                    <div class="space-y-1.5">
                        <label class="font-semibold text-slate-800">Kunci Jawaban yang Benar</label>
                        <select x-model="newQCorrectIndex"
                                class="w-full bg-emerald-50 border border-emerald-200 rounded-lg p-2 text-xs font-semibold text-emerald-900 focus:outline-none">
                            <option value="0">Pilihan A (Index 0)</option>
                            <option value="1">Pilihan B (Index 1)</option>
                            <option value="2">Pilihan C (Index 2)</option>
                            <option value="3">Pilihan D (Index 3)</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="font-semibold text-slate-800">Pembahasan Jawaban (Opsional)</label>
                        <input type="text" x-model="newQExplanation" placeholder="Penjelasan singkat jawaban..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg p-2 text-xs text-slate-900"/>
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" :disabled="isSavingQuestion"
                            class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-xs flex items-center gap-1.5 disabled:opacity-50">
                        <span class="material-symbols-outlined text-[16px]">add_task</span>
                        <span x-text="isSavingQuestion ? 'Menyimpan...' : 'Simpan Butir Soal'"></span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Questions List -->
        <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Daftar Soal Tersimpan</h3>
                <span class="px-2 py-0.5 rounded-full text-xs bg-slate-100 text-slate-700 font-semibold" x-text="quizQuestions.length + ' Soal'"></span>
            </div>

            <div class="space-y-3">
                <template x-for="(q, idx) in quizQuestions" :key="q.id">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3 text-xs">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-2.5">
                                <span class="w-5 h-5 rounded-md bg-indigo-600 text-white flex items-center justify-center font-mono text-[11px] font-bold shrink-0 mt-0.5" x-text="idx + 1"></span>
                                <h4 class="font-semibold text-slate-900" x-text="q.question"></h4>
                            </div>
                            <button type="button" @click="deleteQuestion(q.id)"
                                    class="p-1 rounded text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Hapus Soal">
                                <span class="material-symbols-outlined text-[16px]">delete</span>
                            </button>
                        </div>

                        <!-- Options grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 pl-7">
                            <template x-for="(opt, optIdx) in q.options" :key="optIdx">
                                <div class="px-3 py-1.5 rounded-md border text-xs flex items-center justify-between"
                                     :class="optIdx === q.correct_answer_index ? 'bg-emerald-50 border-emerald-300 text-emerald-900 font-semibold' : 'bg-white border-slate-200 text-slate-700'">
                                    <span x-text="opt"></span>
                                    <span x-show="optIdx === q.correct_answer_index" class="material-symbols-outlined text-[14px] text-emerald-600">check_circle</span>
                                </div>
                            </template>
                        </div>

                        <div x-show="q.explanation" class="text-[11px] text-slate-500 bg-white p-2 rounded-md border border-slate-200 pl-7">
                            <strong class="text-slate-800">Pembahasan:</strong> <span x-text="q.explanation"></span>
                        </div>
                    </div>
                </template>

                <div x-show="quizQuestions.length === 0" class="py-8 text-center text-slate-400 border border-dashed border-slate-200 rounded-xl">
                    <p class="text-xs font-medium">Belum ada butir pertanyaan untuk kuis ini.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =========================================================================
         PUBLISH VALIDATION MODAL (INTERACTIVE CHECKS)
         ========================================================================= -->
    <div x-show="isPublishModalOpen" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div @click.away="isPublishModalOpen = false"
             class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-200">
                    <span class="material-symbols-outlined text-[22px]">warning</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Syarat Publikasi Belum Terpenuhi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Harap lengkapi item berikut sebelum menerbitkan kursus:</p>
                </div>
            </div>

            <div class="space-y-2 py-1">
                <template x-for="err in publishErrors" :key="err">
                    <div class="p-2.5 rounded-lg bg-rose-50 border border-rose-200 text-rose-900 text-xs flex items-start gap-2">
                        <span class="material-symbols-outlined text-[15px] text-rose-600 shrink-0 mt-0.5">cancel</span>
                        <span x-text="err"></span>
                    </div>
                </template>
            </div>

            <div class="flex justify-end pt-2">
                <button type="button" @click="isPublishModalOpen = false"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors shadow-xs">
                    Saya Mengerti, Perbaiki Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- =========================================================================
         TOAST NOTIFICATION COMPONENT
         ========================================================================= -->
    <div x-show="toast.show" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-3"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-3"
         class="fixed bottom-6 right-6 z-50 max-w-sm w-full p-3.5 rounded-xl shadow-lg flex items-center gap-3 text-xs font-medium"
         :class="toast.type === 'error' ? 'bg-rose-900 text-white border border-rose-800' : 'bg-slate-900 text-white border border-slate-800'">
        <span class="material-symbols-outlined text-[18px]" 
              :class="toast.type === 'error' ? 'text-rose-400' : 'text-emerald-400'"
              x-text="toast.type === 'error' ? 'error' : 'check_circle'"></span>
        <span class="flex-1" x-text="toast.message"></span>
        <button type="button" @click="toast.show = false" class="text-slate-400 hover:text-white">
            <span class="material-symbols-outlined text-[16px]">close</span>
        </button>
    </div>
</div>
@endsection
