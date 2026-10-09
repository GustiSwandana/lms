@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6" x-data="{
    isEditModuleOpen: false,
    selectedLessonId: null,
    selectedLessonTitle: '',
    currentModuleTitle: '',
    sortOrder: 1,

    // Quiz Modal State
    isQuizModalOpen: false,
    quizLessonId: null,
    quizLessonTitle: '',
    quizQuestions: [],
    newQuizQuestion: '',
    newQuizOption0: '',
    newQuizOption1: '',
    newQuizOption2: '',
    newQuizOption3: '',
    newQuizCorrectIndex: 0,
    newQuizExplanation: '',

    // Resources Modal State
    isResourceModalOpen: false,
    resourceLessonId: null,
    resourceLessonTitle: '',
    lessonResources: [],
    newResourceTitle: '',
    newResourceType: 'pdf',
    newResourceUrl: '',

    openEditModule(les) {
        this.selectedLessonId = les.id;
        this.selectedLessonTitle = les.title;
        this.currentModuleTitle = les.module_title || 'Modul 1';
        this.sortOrder = les.sort_order || 1;
        this.isEditModuleOpen = true;
    },

    openQuizModal(les) {
        this.quizLessonId = les.id;
        this.quizLessonTitle = les.title;
        this.quizQuestions = les.quizzes || [];
        this.newQuizQuestion = '';
        this.newQuizOption0 = '';
        this.newQuizOption1 = '';
        this.newQuizOption2 = '';
        this.newQuizOption3 = '';
        this.newQuizCorrectIndex = 0;
        this.newQuizExplanation = '';
        this.isQuizModalOpen = true;
    },

    openResourceModal(les) {
        this.resourceLessonId = les.id;
        this.resourceLessonTitle = les.title;
        this.lessonResources = les.resources || [];
        this.newResourceTitle = '';
        this.newResourceType = 'pdf';
        this.newResourceUrl = '';
        this.isResourceModalOpen = true;
    }
}">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Penyusun Kurikulum & Modul</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Struktur Silabus & Interaktif</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">Kelompokkan video YouTube ke dalam modul, kelola soal kuis checkpoint evaluasi, dan sediakan berkas materi.</p>
        </div>

        <!-- Course Selector Dropdown -->
        <form method="GET" action="{{ route('admin.curriculum.index') }}" class="flex items-center gap-2 self-start md:self-auto">
            <label class="text-xs font-semibold text-slate-700 shrink-0">Pilih Kursus:</label>
            <select name="course_id" onchange="this.form.submit()"
                    class="bg-white border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 max-w-xs truncate shadow-xs">
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ $selectedCourse && $selectedCourse->id === $c->id ? 'selected' : '' }}>
                        {{ $c->title }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    @if($selectedCourse)
        <!-- Course Telemetry Banner -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold border border-indigo-100">
                    <span class="material-symbols-outlined text-[22px]">account_tree</span>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-base font-bold text-slate-900">{{ $selectedCourse->title }}</h2>
                        <span class="px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            {{ $selectedCourse->school_name ?: 'EduTube Pusat' }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $selectedCourse->category->name ?? 'Kategori' }} • {{ $selectedCourse->lessons->count() }} Total Video Pelajaran</p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <button type="button" @click="$dispatch('open-import-modal', { courseId: {{ $selectedCourse->id }} })" 
                        class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-xs">
                    <span class="material-symbols-outlined text-[16px]">add_circle</span>
                    <span>+ Tambah Video ke Kursus</span>
                </button>
                <button type="button" @click="$dispatch('open-playlist-modal', { courseId: {{ $selectedCourse->id }} })" 
                        class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold flex items-center gap-1.5 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">sync</span>
                    <span>Sinkronkan Playlist YouTube</span>
                </button>
            </div>
        </div>

        <!-- Modules Breakdown List -->
        <div class="space-y-4">
            @forelse($modules as $moduleName => $lessonsInModule)
                <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                    <!-- Module Header -->
                    <div class="p-4 bg-slate-50/75 border-b border-slate-200 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <span class="w-6 h-6 rounded-full bg-indigo-600 text-white flex items-center justify-center font-mono text-xs font-bold">
                                {{ $loop->iteration }}
                            </span>
                            <h3 class="text-sm font-bold text-slate-900">{{ $moduleName }}</h3>
                            <span class="text-xs text-slate-500">({{ $lessonsInModule->count() }} Pelajaran)</span>
                        </div>
                    </div>

                    <!-- Lessons in Module Table -->
                    <div class="divide-y divide-slate-100">
                        @foreach($lessonsInModule as $les)
                            <div class="p-3.5 px-5 flex flex-col md:flex-row md:items-center justify-between gap-3 hover:bg-slate-50/60 transition-colors">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="font-mono text-xs text-slate-400 w-5 text-right font-semibold">{{ $les->sort_order }}</span>
                                    <img src="{{ $les->thumbnail_url ?: 'https://img.youtube.com/vi/' . $les->youtube_video_id . '/hqdefault.jpg' }}" 
                                         class="w-12 h-8 rounded-lg object-cover border border-slate-200 shrink-0" alt="Thumb"/>
                                    <div class="flex flex-col truncate">
                                        <span class="text-xs font-semibold text-slate-900 truncate">{{ $les->title }}</span>
                                        <div class="flex items-center gap-2 text-[11px] text-slate-400 font-mono mt-0.5">
                                            <span>{{ $les->duration_formatted }}</span>
                                            <span>•</span>
                                            <span>Kuis: <strong class="text-indigo-600 font-bold">{{ $les->quizzes->count() }}</strong></span>
                                            <span>•</span>
                                            <span>Sumber Daya: <strong class="text-emerald-600 font-bold">{{ $les->resources->count() }}</strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0 self-end md:self-auto">
                                    <!-- Quiz Manager Button -->
                                    <button type="button" @click="openQuizModal({{ json_encode($les) }})"
                                            class="px-2.5 py-1.5 rounded-lg border border-indigo-200 hover:bg-indigo-50 bg-indigo-50/50 text-indigo-700 text-xs font-medium transition-colors flex items-center gap-1"
                                            title="Kelola Soal Kuis Checkpoint">
                                        <span class="material-symbols-outlined text-[15px]">quiz</span>
                                        <span>Kuis ({{ $les->quizzes->count() }})</span>
                                    </button>

                                    <!-- Resources Manager Button -->
                                    <button type="button" @click="openResourceModal({{ json_encode($les) }})"
                                            class="px-2.5 py-1.5 rounded-lg border border-emerald-200 hover:bg-emerald-50 bg-emerald-50/50 text-emerald-700 text-xs font-medium transition-colors flex items-center gap-1"
                                            title="Kelola Berkas & Tautan Sumber Daya">
                                        <span class="material-symbols-outlined text-[15px]">folder_open</span>
                                        <span>Berkas ({{ $les->resources->count() }})</span>
                                    </button>

                                    <!-- Edit Module Title Button -->
                                    <button type="button" @click="openEditModule({{ json_encode($les) }})"
                                            class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 bg-white text-slate-600 hover:text-slate-900 text-xs font-medium transition-colors flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">edit</span>
                                        Ubah Bab
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="p-10 text-center bg-white rounded-xl border border-slate-200 shadow-xs space-y-4">
                    <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 mx-auto flex items-center justify-center">
                        <span class="material-symbols-outlined text-[32px]">video_library</span>
                    </div>
                    <div class="space-y-1">
                        <h3 class="text-base font-bold text-slate-900">Kurikulum Belum Memiliki Video Materi</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto">
                            Kursus <strong>{{ $selectedCourse->title }}</strong> siap disusun. Tambahkan video materi pertama atau impor seluruh modul langsung dari playlist YouTube resmi.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
                        <button type="button" @click="$dispatch('open-import-modal', { courseId: {{ $selectedCourse->id }} })" 
                                class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold flex items-center gap-2 transition-all shadow-xs">
                            <span class="material-symbols-outlined text-[18px]">add_circle</span>
                            <span>+ Tambah Video YouTube Pertama</span>
                        </button>
                        <button type="button" @click="$dispatch('open-playlist-modal', { courseId: {{ $selectedCourse->id }} })" 
                                class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 text-xs font-semibold flex items-center gap-2 transition-all">
                            <span class="material-symbols-outlined text-[18px]">sync</span>
                            <span>Sinkronkan Playlist YouTube Massal</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    @else
        <div class="p-12 text-center bg-white rounded-xl border border-slate-200 shadow-xs space-y-4">
            <div class="w-14 h-14 rounded-xl bg-amber-50 text-amber-600 mx-auto flex items-center justify-center">
                <span class="material-symbols-outlined text-[32px]">school</span>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-slate-900">Belum Ada Kursus Terdaftar</h3>
                <p class="text-xs text-slate-500">Buat program kursus baru terlebih dahulu untuk mulai menyusun kurikulum dan materi video.</p>
            </div>
            <a href="{{ route('admin.courses.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-all shadow-xs">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>Buat Kursus Baru Sekarang</span>
            </a>
        </div>
    @endif

    <!-- ================= MODAL: EDIT MODULE ASSIGNMENT ================= -->
    <div x-show="isEditModuleOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div @click.away="isEditModuleOpen = false"
             class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-lg w-full p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Ubah Penempatan Modul & Urutan</h3>
                <button type="button" @click="isEditModuleOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.curriculum.update-module') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="lesson_id" :value="selectedLessonId">

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Judul Pelajaran</label>
                    <p class="text-xs text-slate-800 font-medium p-2.5 bg-slate-50 rounded-xl border border-slate-200" x-text="selectedLessonTitle"></p>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Nama Modul / Bab</label>
                    <input type="text" name="module_title" x-model="currentModuleTitle" required
                           class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"/>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Nomor Urutan (Sort Order)</label>
                    <input type="number" name="sort_order" x-model="sortOrder" min="1" required
                           class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"/>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="button" @click="isEditModuleOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: KELOLA KUIS CHECKPOINT ================= -->
    <div x-show="isQuizModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div @click.away="isQuizModalOpen = false"
             class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-2xl w-full p-6 space-y-5 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kelola Kuis Checkpoint</h3>
                    <p class="text-xs text-slate-500 truncate max-w-md" x-text="quizLessonTitle"></p>
                </div>
                <button type="button" @click="isQuizModalOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <!-- Existing Questions List -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Daftar Soal Kuis Saat Ini</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <template x-for="(q, idx) in quizQuestions" :key="q.id">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-start justify-between gap-3 text-xs">
                            <div class="space-y-1 flex-1">
                                <div class="font-semibold text-slate-900" x-text="(idx + 1) + '. ' + q.question"></div>
                                <div class="text-[11px] text-slate-500">
                                    Jawaban Benar: <strong class="text-emerald-700 font-bold" x-text="q.options[q.correct_answer_index]"></strong>
                                </div>
                            </div>
                            <form :action="'/admin/curriculum/quizzes/' + q.id" method="POST" onsubmit="return confirm('Hapus soal kuis ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition-colors" title="Hapus Soal">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </template>
                    <div x-show="quizQuestions.length === 0" class="py-4 text-center text-xs text-slate-400">
                        Belum ada soal kuis checkpoint untuk materi ini.
                    </div>
                </div>
            </div>

            <!-- Form: Add New Quiz Question -->
            <form :action="'/admin/curriculum/lessons/' + quizLessonId + '/quizzes'" method="POST" class="space-y-3 pt-3 border-t border-slate-200">
                @csrf
                <h4 class="text-xs font-bold text-indigo-700 uppercase tracking-wider">+ Tambah Soal Kuis Baru</h4>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Pertanyaan</label>
                    <textarea name="question" x-model="newQuizQuestion" required rows="2"
                              placeholder="Tuliskan pertanyaan pengujian konsep di video..."
                              class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Pilihan A (Index 0)</label>
                        <input type="text" name="options[]" x-model="newQuizOption0" required placeholder="Opsi A"
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Pilihan B (Index 1)</label>
                        <input type="text" name="options[]" x-model="newQuizOption1" required placeholder="Opsi B"
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Pilihan C (Index 2)</label>
                        <input type="text" name="options[]" x-model="newQuizOption2" required placeholder="Opsi C"
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-semibold text-slate-700">Pilihan D (Index 3)</label>
                        <input type="text" name="options[]" x-model="newQuizOption3" required placeholder="Opsi D"
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Passing Grade (%)</label>
                        <input type="number" name="passing_grade" min="10" max="100" value="75"
                               class="w-full bg-white border border-slate-200 rounded-xl p-2 text-xs font-semibold text-slate-900 focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Jawaban yang Benar</label>
                        <select name="correct_answer_index" x-model="newQuizCorrectIndex"
                                class="w-full bg-white border border-slate-200 rounded-xl p-2 text-xs font-semibold text-emerald-700 focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none">
                            <option value="0">Pilihan A (Index 0)</option>
                            <option value="1">Pilihan B (Index 1)</option>
                            <option value="2">Pilihan C (Index 2)</option>
                            <option value="3">Pilihan D (Index 3)</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Penjelasan (Opsional)</label>
                        <input type="text" name="explanation" x-model="newQuizExplanation"
                               placeholder="Mengapa jawaban ini benar..."
                               class="w-full bg-white border border-slate-200 rounded-xl p-2 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="button" @click="isQuizModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs">Simpan Soal Kuis</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: KELOLA SUMBER DAYA MATERI ================= -->
    <div x-show="isResourceModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div @click.away="isResourceModalOpen = false"
             class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-xl w-full p-6 space-y-5">
            <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Kelola Sumber Daya Materi (PDF / GitHub / ZIP)</h3>
                    <p class="text-xs text-slate-500 truncate max-w-md" x-text="resourceLessonTitle"></p>
                </div>
                <button type="button" @click="isResourceModalOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <!-- Existing Resources List -->
            <div class="space-y-3">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Berkas Sumber Daya Saat Ini</h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <template x-for="r in lessonResources" :key="r.id">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2 truncate flex-1">
                                <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold uppercase border border-indigo-100" x-text="r.type"></span>
                                <span class="font-medium text-slate-900 truncate" x-text="r.title"></span>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a :href="r.url" target="_blank" class="text-indigo-600 hover:text-indigo-800 text-xs flex items-center gap-0.5">
                                    <span>Buka</span>
                                    <span class="material-symbols-outlined text-[13px]">open_in_new</span>
                                </a>
                                <form :action="'/admin/curriculum/resources/' + r.id" method="POST" onsubmit="return confirm('Hapus sumber daya ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1 text-slate-400 hover:text-rose-600 rounded transition-colors" title="Hapus Berkas">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <div x-show="lessonResources.length === 0" class="py-4 text-center text-xs text-slate-400">
                        Belum ada sumber daya terhubung ke materi ini.
                    </div>
                </div>
            </div>

            <!-- Form: Add New Resource -->
            <form :action="'/admin/curriculum/lessons/' + resourceLessonId + '/resources'" method="POST" class="space-y-3 pt-3 border-t border-slate-200">
                @csrf
                <h4 class="text-xs font-bold text-emerald-700 uppercase tracking-wider">+ Tambah Sumber Daya Baru</h4>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-700">Judul Berkas / Tautan</label>
                    <input type="text" name="title" x-model="newResourceTitle" required placeholder="Contoh: Modul Panduan Praktikum.pdf atau Repositori Starter"
                           class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"/>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">Jenis Sumber Daya</label>
                        <select name="type" x-model="newResourceType"
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs font-medium text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                            <option value="pdf">Dokumen PDF (.pdf)</option>
                            <option value="github">Kode Sumber GitHub</option>
                            <option value="zip">Arsip ZIP (.zip)</option>
                            <option value="link">Tautan Referensi Eksternal</option>
                        </select>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-700">URL Tautan / Unduhan</label>
                        <input type="url" name="url" x-model="newResourceUrl" required placeholder="https://..."
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs font-mono text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600"/>
                    </div>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2">
                    <button type="button" @click="isResourceModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50 border border-slate-200 rounded-xl">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-xl shadow-xs">Simpan Sumber Daya</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
