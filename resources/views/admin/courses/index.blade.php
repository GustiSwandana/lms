@extends('layouts.admin')

@section('admin_content')
<div x-data="{
    isCourseModalOpen: false,
    modalMode: 'create',
    courseId: null,
    categoryId: '{{ $categories->first()->id ?? 1 }}',
    title: '',
    description: '',
    instructorName: '',
    instructorTitle: '',
    thumbnailUrl: '',
    status: 'published',
    categoriesList: {{ Js::from($categories) }},
    isQuickCategoryOpen: false,
    newCategoryName: '',
    newCategoryDesc: '',
    isAddingCategory: false,
    quickCategoryError: '',

    async submitQuickCategory() {
        if (!this.newCategoryName.trim()) {
            this.quickCategoryError = 'Nama kategori tidak boleh kosong.';
            return;
        }
        this.isAddingCategory = true;
        this.quickCategoryError = '';
        try {
            const res = await fetch('{{ route('admin.categories.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: this.newCategoryName,
                    description: this.newCategoryDesc
                })
            });
            const data = await res.json();
            if (res.ok && data.success) {
                this.categoriesList.push(data.category);
                this.categoryId = data.category.id;
                this.newCategoryName = '';
                this.newCategoryDesc = '';
                this.isQuickCategoryOpen = false;
            } else {
                this.quickCategoryError = data.message || (data.errors ? Object.values(data.errors).flat().join(', ') : 'Gagal menambahkan kategori');
            }
        } catch (e) {
            this.quickCategoryError = 'Terjadi kesalahan jaringan saat menyimpan kategori.';
        } finally {
            this.isAddingCategory = false;
        }
    },

    openCreate() {
        this.modalMode = 'create';
        this.courseId = null;
        this.title = '';
        this.description = '';
        this.instructorName = 'Tim Fasilitator BKKBN SSK';
        this.instructorTitle = 'Fasilitator Kependudukan & Lingkungan';
        this.thumbnailUrl = '';
        this.status = 'published';
        this.isVisibleToGuest = false;
        this.isQuickCategoryOpen = false;
        this.quickCategoryError = '';
        this.isCourseModalOpen = true;
    },

    openEdit(c) {
        this.modalMode = 'edit';
        this.courseId = c.id;
        this.categoryId = c.category_id;
        this.title = c.title;
        this.description = c.description;
        this.instructorName = c.instructor_name || '';
        this.instructorTitle = c.instructor_title || '';
        this.thumbnailUrl = c.thumbnail_url || '';
        this.status = c.status || 'published';
        this.isVisibleToGuest = Boolean(c.is_visible_to_guest);
        this.isQuickCategoryOpen = false;
        this.quickCategoryError = '';
        this.isCourseModalOpen = true;
    }
}" class="space-y-6 text-left">
    <!-- Flash Success Banner -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-2xs">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @if(session('new_course_id'))
                <a href="{{ route('admin.curriculum.index', ['course_id' => session('new_course_id')]) }}" 
                   class="min-h-9 inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-colors shadow-xs shrink-0">
                    <span>Mulai Susun Silabus</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            @endif
        </div>
    @endif

    <!-- Top Header Section -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Manajer Kursus Pembelajaran</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-emerald-50 text-emerald-800 font-mono tabular-nums font-semibold border border-emerald-200">
                    Total {{ $courses->count() }} Kursus
                </span>
                @if($admin->isSchoolAdmin())
                    <span class="px-2.5 py-0.5 rounded-md text-xs bg-slate-100 text-slate-700 font-medium border border-slate-200 font-mono">
                        {{ $admin->school_name }}
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-600 mt-1">Kelola data program studi, kurikulum, kategori, dan profil pengajar kursus.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            @if($admin->isSuperAdmin() && $schools->count() > 0)
                <form method="GET" action="{{ route('admin.courses.index') }}" class="flex items-center">
                    <select name="school" onchange="this.form.submit()"
                            class="bg-white text-slate-700 text-xs py-2 pl-3 pr-8 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 font-medium shadow-2xs">
                        <option value="">Semua Instansi</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch }}" {{ ($selectedSchool ?? '') === $sch ? 'selected' : '' }}>{{ $sch }}</option>
                        @endforeach
                    </select>
                </form>
            @endif

            <a href="{{ route('admin.categories.index') }}" 
               class="min-h-11 px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs">
                <span class="material-symbols-outlined text-[16px] text-slate-500">category</span>
                <span>Kategori</span>
            </a>

            <form action="{{ route('admin.courses.builder.create') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="min-h-11 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold flex items-center gap-1.5 transition-colors shadow-xs">
                    <span class="material-symbols-outlined text-[18px]">build</span>
                    <span>+ Unified Course Builder</span>
                </button>
            </form>

            <button type="button" @click="openCreate()" 
                    class="min-h-11 px-3.5 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-2xs">
                <span class="material-symbols-outlined text-[16px] text-emerald-700">add</span>
                <span>Buat Cepat</span>
            </button>
        </div>
    </section>

    <!-- Courses Table Section (High Density Light) -->
    <section class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs mt-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] font-mono uppercase tracking-wider">
                        <th class="py-3.5 px-4">Thumbnail</th>
                        <th class="py-3.5 px-4">Judul Kursus</th>
                        <th class="py-3.5 px-4">Sekolah</th>
                        <th class="py-3.5 px-4">Kategori</th>
                        <th class="py-3.5 px-4">Pengajar</th>
                        <th class="py-3.5 px-4 text-center">Materi</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Akses Tamu</th>
                        <th class="py-3.5 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 w-28">
                                <div class="w-20 aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                                    <img src="{{ $course->thumbnail_url ?: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=300&auto=format&fit=crop&q=80' }}" 
                                         alt="{{ $course->title }}" class="w-full h-full object-cover"/>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <a href="{{ route('student.classroom', $course->slug) }}" class="font-bold text-slate-900 hover:text-emerald-700 block line-clamp-1 transition-colors">
                                    {{ $course->title }}
                                </a>
                                <span class="font-mono text-[10px] text-slate-500">{{ $course->slug }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center gap-1 text-[11px] text-slate-700 font-medium">
                                    <span>{{ $course->school_name ?: 'SIAGA BUMI-SSK' }}</span>
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 font-medium text-[11px]">
                                    {{ $course->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-medium text-slate-900">{{ $course->instructor_name }}</div>
                                <div class="text-[11px] text-slate-500 truncate max-w-[140px]">{{ $course->instructor_title }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
                                   class="inline-flex items-center gap-1 text-slate-700 hover:text-emerald-700 font-medium font-mono tabular-nums"
                                   title="Susun kurikulum">
                                    <span class="font-bold">{{ $course->lessons_count }}</span>
                                    <span class="text-slate-500 text-[10px]">video</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4">
                                @if($course->status === 'published')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800 bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-600"></span> Publik
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-amber-800 bg-amber-50 px-2.5 py-0.5 rounded-md border border-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-600"></span> Draf
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($course->is_visible_to_guest)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        <span class="material-symbols-outlined text-[12px] text-emerald-600">visibility</span>
                                        <span>Aktif</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                        <span class="material-symbols-outlined text-[12px] text-slate-400">visibility_off</span>
                                        <span>Terkunci</span>
                                    </span>
                                @endif

                                @if($admin->canCurateGuestContent())
                                    <form action="{{ route('admin.courses.toggle-guest-visibility', $course->id) }}" method="POST" class="mt-1">
                                        @csrf
                                        <button type="submit" 
                                                class="text-[10px] font-semibold text-emerald-700 hover:text-emerald-800 hover:underline mx-auto block"
                                                title="Ubah visibilitas kurasi untuk akun Tamu / Penyuluh">
                                            {{ $course->is_visible_to_guest ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Shortcut: Single-Page Course Builder -->
                                    <a href="{{ route('admin.courses.builder', $course->id) }}" 
                                        class="min-h-9 px-3 py-1 text-xs font-bold rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition-all flex items-center gap-1 shadow-xs" 
                                        title="Buka Course Builder">
                                        <span class="material-symbols-outlined text-[14px]">build</span>
                                        <span>Builder</span>
                                    </a>

                                    <!-- Shortcut: Kurikulum -->
                                    <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
                                        class="min-h-9 px-3 py-1 text-xs font-semibold rounded-lg bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 transition-all flex items-center gap-1 shadow-2xs" 
                                        title="Susun Kurikulum">
                                        <span class="material-symbols-outlined text-[14px]">account_tree</span>
                                        <span class="hidden sm:inline">Kurikulum</span>
                                    </a>

                                    <button type="button" @click="openEdit({{ json_encode($course) }})" 
                                            class="min-h-9 min-w-9 flex items-center justify-center text-slate-500 hover:text-slate-900 hover:bg-slate-50 rounded-lg transition-colors border border-slate-200 shadow-2xs" title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" 
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini beserta seluruh video materinya?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="min-h-9 min-w-9 flex items-center justify-center text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors border border-slate-200 shadow-2xs" title="Hapus">
                                            <span class="material-symbols-outlined text-[16px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-12 text-center text-slate-500">
                                Belum ada kursus. Silakan buat kursus baru dengan tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Create / Edit Course (Light Mode) -->
    <div x-show="isCourseModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
        <div @click.away="isCourseModalOpen = false" 
             class="relative w-full max-w-xl bg-white rounded-3xl border border-slate-200 shadow-2xl flex flex-col my-auto overflow-hidden text-left">
            
            <div class="px-6 py-4 border-b border-slate-200 flex items-start justify-between bg-slate-50/50">
                <div>
                    <h2 class="text-base font-bold text-slate-900" x-text="modalMode === 'create' ? 'Tambah Kursus Baru' : 'Edit Kursus'"></h2>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi dasar silabus dan pengajar.</p>
                </div>
                <button type="button" @click="isCourseModalOpen = false" class="text-slate-400 hover:text-slate-700 min-h-11 min-w-11 flex items-center justify-center rounded-xl">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form :action="modalMode === 'create' ? '{{ route('admin.courses.store') }}' : '/admin/courses/' + courseId" method="POST">
                @csrf
                <template x-if="modalMode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="p-6 space-y-4 text-xs">
                    <div class="space-y-1.5">
                        <label class="block font-semibold text-slate-700">Judul Kursus <span class="text-emerald-600">*</span></label>
                        <input type="text" name="title" x-model="title" required
                               placeholder="Cth: Dasar Integrasi Lingkungan dan Kependudukan"
                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs"/>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-700">Kategori <span class="text-emerald-600">*</span></label>
                            <select name="category_id" x-model="categoryId" required
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs">
                                <template x-for="cat in categoriesList" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name" :selected="cat.id == categoryId"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-700">Status Kursus</label>
                            <select name="status" x-model="status"
                                    class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs">
                                <option value="published">Publik (Aktif)</option>
                                <option value="draft">Draf</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-700">Nama Pengajar</label>
                            <input type="text" name="instructor_name" x-model="instructorName"
                                   placeholder="Cth: Tim Fasilitator SSK"
                                   class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs"/>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-700">Gelar / Jabatan</label>
                            <input type="text" name="instructor_title" x-model="instructorTitle"
                                   placeholder="Cth: Fasilitator BKKBN Sulut"
                                   class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs"/>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-slate-700">URL Thumbnail Kursus</label>
                        <input type="url" name="thumbnail_url" x-model="thumbnailUrl"
                               placeholder="https://..."
                               class="w-full bg-white border border-slate-300 rounded-xl px-3.5 py-2.5 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 shadow-2xs"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-slate-700">Deskripsi Kursus <span class="text-emerald-600">*</span></label>
                        <textarea name="description" x-model="description" required rows="3"
                                  placeholder="Jelaskan ringkasan materi dan capaian pembelajaran..."
                                  class="w-full bg-white border border-slate-300 rounded-xl p-3 text-xs text-slate-900 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-500/20 leading-relaxed shadow-2xs"></textarea>
                    </div>

                    @if($admin->canCurateGuestContent())
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div>
                            <label class="block font-semibold text-slate-800 text-xs">Akses Mode Galeri (Penyuluh & Tamu)</label>
                            <p class="text-[11px] text-slate-500">Tampilkan kursus ini untuk akun Tamu / Penyuluh dalam mode baca.</p>
                        </div>
                        <input type="checkbox" name="is_visible_to_guest" value="1" x-model="isVisibleToGuest" class="w-4 h-4 rounded text-emerald-600 focus:ring-0 border-slate-300 bg-white"/>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isCourseModalOpen = false" class="min-h-11 px-4 py-2 text-slate-600 hover:text-slate-900 text-xs font-semibold rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="min-h-11 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs">
                        Simpan Kursus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
