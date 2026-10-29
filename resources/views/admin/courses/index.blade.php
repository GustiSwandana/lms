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
        this.instructorName = 'Dr. Sarah Vance';
        this.instructorTitle = 'Senior Software Architect';
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
}">
    <!-- Flash Success Banner -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs">
            <div class="flex items-center gap-2.5">
                <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            @if(session('new_course_id'))
                <a href="{{ route('admin.curriculum.index', ['course_id' => session('new_course_id')]) }}" 
                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold text-xs transition-colors shrink-0">
                    <span>Mulai Susun Silabus</span>
                    <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                </a>
            @endif
        </div>
    @endif

    <!-- Top Header Section -->
    <section class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2.5">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Manajer Kursus Pembelajaran</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold">
                    Total {{ $courses->count() }} Kursus
                </span>
                @if($admin->isSchoolAdmin())
                    <span class="px-2 py-0.5 rounded-md text-xs bg-slate-100 text-slate-700 font-medium border border-slate-200">
                        {{ $admin->school_name }}
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">Kelola data program studi, kurikulum, kategori, dan profil pengajar kursus.</p>
        </div>

        <div class="flex items-center gap-2.5 flex-wrap">
            @if($admin->isSuperAdmin() && $schools->count() > 0)
                <form method="GET" action="{{ route('admin.courses.index') }}" class="flex items-center">
                    <select name="school" onchange="this.form.submit()"
                            class="bg-slate-50 text-slate-700 text-xs py-2 pl-3 pr-8 rounded-lg border border-slate-200 focus:outline-none font-medium">
                        <option value="">Semua Instansi</option>
                        @foreach($schools as $sch)
                            <option value="{{ $sch }}" {{ ($selectedSchool ?? '') === $sch ? 'selected' : '' }}>{{ $sch }}</option>
                        @endforeach
                    </select>
                </form>
            @endif

            <a href="{{ route('admin.categories.index') }}" 
               class="px-3.5 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-medium flex items-center gap-1.5 transition-colors shadow-xs">
                <span class="material-symbols-outlined text-[16px] text-slate-400">category</span>
                <span>Kategori</span>
            </a>

            <form action="{{ route('admin.courses.builder.create') }}" method="POST">
                @csrf
                <button type="submit" 
                        class="px-3.5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold flex items-center gap-1.5 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">build</span>
                    <span>+ Unified Course Builder</span>
                </button>
            </form>

            <button type="button" @click="openCreate()" 
                    class="px-3.5 py-2 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium flex items-center gap-1.5 transition-colors shadow-xs">
                <span class="material-symbols-outlined text-[16px] text-slate-400">add</span>
                <span>Buat Cepat</span>
            </button>
        </div>
    </section>

    <!-- Courses Table Section -->
    <section class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs mt-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-[11px] font-semibold">
                        <th class="py-3 px-4 uppercase tracking-wider">Thumbnail</th>
                        <th class="py-3 px-4 uppercase tracking-wider">Judul Kursus</th>
                        <th class="py-3 px-4 uppercase tracking-wider">Sekolah</th>
                        <th class="py-3 px-4 uppercase tracking-wider">Kategori</th>
                        <th class="py-3 px-4 uppercase tracking-wider">Pengajar</th>
                        <th class="py-3 px-4 uppercase tracking-wider text-center">Materi</th>
                        <th class="py-3 px-4 uppercase tracking-wider">Status</th>
                        <th class="py-3 px-4 uppercase tracking-wider text-center">Akses Tamu</th>
                        <th class="py-3 px-4 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($courses as $course)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 w-28">
                                <div class="w-20 aspect-video rounded-lg overflow-hidden border border-slate-200 bg-slate-900">
                                    <img src="{{ $course->thumbnail_url ?: 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=300&auto=format&fit=crop&q=80' }}" 
                                         alt="{{ $course->title }}" class="w-full h-full object-cover"/>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <a href="{{ route('student.classroom', $course->slug) }}" class="font-bold text-slate-900 hover:text-indigo-600 block line-clamp-1">
                                    {{ $course->title }}
                                </a>
                                <span class="font-mono text-[10px] text-slate-400">{{ $course->slug }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center gap-1 text-[11px] text-slate-600 font-medium">
                                    <span>{{ $course->school_name ?: 'SIAGA BUMI-SSK' }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-medium text-[11px]">
                                    {{ $course->category->name ?? 'Umum' }}
                                </span>
                            </td>
                            <td class="py-3 px-4">
                                <div class="font-medium text-slate-900">{{ $course->instructor_name }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-[140px]">{{ $course->instructor_title }}</div>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
                                   class="inline-flex items-center gap-1 text-slate-600 hover:text-indigo-600 font-medium"
                                   title="Susun kurikulum">
                                    <span class="font-mono font-bold">{{ $course->lessons_count }}</span>
                                    <span class="text-slate-400 text-[10px]">video</span>
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                @if($course->status === 'published')
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Publik
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[11px] font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span> Draf
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($course->is_visible_to_guest)
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">
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
                                                class="text-[10px] font-semibold text-indigo-600 hover:text-indigo-800 hover:underline mx-auto block"
                                                title="Ubah visibilitas kurasi untuk akun Tamu / Penyuluh">
                                            {{ $course->is_visible_to_guest ? 'Sembunyikan' : 'Tampilkan' }}
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <!-- Shortcut: Single-Page Course Builder -->
                                    <a href="{{ route('admin.courses.builder', $course->id) }}" 
                                        class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white transition-all flex items-center gap-1 shadow-xs" 
                                        title="Buka Course Builder">
                                        <span class="material-symbols-outlined text-[14px]">build</span>
                                        <span>Builder</span>
                                    </a>

                                    <!-- Shortcut: Kurikulum -->
                                    <a href="{{ route('admin.curriculum.index', ['course_id' => $course->id]) }}" 
                                        class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition-all flex items-center gap-1" 
                                        title="Susun Kurikulum">
                                        <span class="material-symbols-outlined text-[14px]">account_tree</span>
                                        <span class="hidden sm:inline">Kurikulum</span>
                                    </a>

                                    <button type="button" @click="openEdit({{ json_encode($course) }})" 
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-slate-100 rounded-lg transition-colors" title="Edit">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    <form action="{{ route('admin.courses.destroy', $course->id) }}" method="POST" 
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kursus ini beserta seluruh video materinya?')">
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
                            <td colspan="9" class="py-12 text-center text-slate-400">
                                Belum ada kursus. Silakan buat kursus baru dengan tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Modal Create / Edit Course -->
    <div x-show="isCourseModalOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 backdrop-blur-xs overflow-y-auto">
        <div @click.away="isCourseModalOpen = false" 
             class="relative w-full max-w-xl bg-white rounded-xl border border-slate-200 shadow-xl flex flex-col my-auto overflow-hidden">
            
            <div class="px-6 py-4 border-b border-slate-100 flex items-start justify-between bg-white">
                <div>
                    <h2 class="text-base font-bold text-slate-900" x-text="modalMode === 'create' ? 'Tambah Kursus Baru' : 'Edit Kursus'"></h2>
                    <p class="text-xs text-slate-500 mt-0.5">Lengkapi informasi dasar silabus dan pengajar.</p>
                </div>
                <button type="button" @click="isCourseModalOpen = false" class="text-slate-400 hover:text-slate-700 p-1 rounded-lg">
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
                        <label class="block font-semibold text-slate-800">Judul Kursus <span class="text-rose-600">*</span></label>
                        <input type="text" name="title" x-model="title" required
                               placeholder="Cth: Dasar Pemrograman Web Modern"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white"/>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-800">Kategori <span class="text-rose-600">*</span></label>
                            <select name="category_id" x-model="categoryId" required
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                                <template x-for="cat in categoriesList" :key="cat.id">
                                    <option :value="cat.id" x-text="cat.name" :selected="cat.id == categoryId"></option>
                                </template>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-800">Status Kursus</label>
                            <select name="status" x-model="status"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white">
                                <option value="published">Publik</option>
                                <option value="draft">Draf</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-800">Nama Pengajar</label>
                            <input type="text" name="instructor_name" x-model="instructorName"
                                   placeholder="Cth: Dr. Sarah Vance"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white"/>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-slate-800">Gelar / Jabatan</label>
                            <input type="text" name="instructor_title" x-model="instructorTitle"
                                   placeholder="Cth: Dosen Teknik Informatika"
                                   class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white"/>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-slate-800">URL Thumbnail Kursus</label>
                        <input type="url" name="thumbnail_url" x-model="thumbnailUrl"
                               placeholder="https://..."
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block font-semibold text-slate-800">Deskripsi Kursus <span class="text-rose-600">*</span></label>
                        <textarea name="description" x-model="description" required rows="3"
                                  placeholder="Jelaskan ringkasan materi dan capaian pembelajaran..."
                                  class="w-full bg-slate-50 border border-slate-200 rounded-lg p-3 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:bg-white leading-relaxed"></textarea>
                    </div>

                    @if($admin->canCurateGuestContent())
                    <div class="p-3 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-between">
                        <div>
                            <label class="block font-semibold text-indigo-950 text-xs">Akses Mode Galeri (Penyuluh & Tamu)</label>
                            <p class="text-[11px] text-indigo-700">Tampilkan kursus ini untuk akun Tamu / Penyuluh dalam mode baca.</p>
                        </div>
                        <input type="checkbox" name="is_visible_to_guest" value="1" x-model="isVisibleToGuest" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-600 border-slate-300"/>
                    </div>
                    @endif
                </div>

                <div class="px-6 py-3.5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isCourseModalOpen = false" class="px-3.5 py-2 text-slate-600 hover:text-slate-900 text-xs font-medium rounded-lg">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg shadow-sm">
                        Simpan Kursus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
