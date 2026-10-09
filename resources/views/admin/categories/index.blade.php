@extends('layouts.admin')

@section('admin_content')
<div x-data="{
    isAddCategoryOpen: false,
    isEditCategoryOpen: false,
    editCategoryData: {
        id: null,
        name: '',
        description: ''
    },
    openEdit(cat) {
        this.editCategoryData = {
            id: cat.id,
            name: cat.name,
            description: cat.description || ''
        };
        this.isEditCategoryOpen = true;
    }
}" class="space-y-6">

    <!-- Flash Message Notification -->
    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-emerald-600">check_circle</span>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-rose-600">error</span>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" @click="$el.parentElement.remove()" class="text-rose-600 hover:text-rose-900">
                <span class="material-symbols-outlined text-[16px]">close</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs shadow-xs space-y-1">
            <div class="flex items-center gap-2 font-bold">
                <span class="material-symbols-outlined text-[18px] text-amber-600">warning</span>
                <span>Terjadi kendala validasi data:</span>
            </div>
            <ul class="list-disc list-inside text-[11px] pl-6 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Kategori Kursus</h1>
                <span class="px-2 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Total {{ $totalCategories }} Kategori</span>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Kelola taksonomi program studi, bidang kejuruan, dan spesialisasi materi kursus pada ekosistem pembelajaran.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button"
                    @click="isAddCategoryOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-[18px]">add</span>
                <span>+ Tambah Kategori Baru</span>
            </button>
        </div>
    </div>

    <!-- Essential Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Kategori</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalCategories }}</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Klasifikasi Tersedia</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                <span class="material-symbols-outlined text-[22px]">category</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Kategori Terpakai</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $usedCategories }}</h3>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Memiliki Kursus Aktif</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <span class="material-symbols-outlined text-[22px]">school</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Kategori Kosong</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $emptyCategories }}</h3>
                <p class="text-[11px] text-slate-400 mt-0.5">Belum Ada Kursus</p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100">
                <span class="material-symbols-outlined text-[22px]">folder_open</span>
            </div>
        </div>
    </div>

    <!-- Search Toolbar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan <strong class="text-slate-800">{{ $categories->count() }}</strong> data kategori
        </div>

        <form action="{{ route('admin.categories.index') }}" method="GET" class="w-full md:w-80 flex items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </span>
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Cari nama atau deskripsi kategori..."
                       class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-900 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
            </div>
            @if($search)
                <a href="{{ route('admin.categories.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-700 transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Nama Kategori & Slug</th>
                        <th class="py-3.5 px-5">Deskripsi</th>
                        <th class="py-3.5 px-4 text-center">Jumlah Kursus</th>
                        <th class="py-3.5 px-4">Dibuat Pada</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <!-- Name & Slug -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs border border-indigo-100 shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">folder</span>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-slate-900 text-sm">{{ $cat->name }}</h4>
                                        <span class="text-[11px] text-slate-400 font-mono">
                                            /{{ $cat->slug }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- Description -->
                            <td class="py-4 px-5 max-w-xs">
                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                                    {{ $cat->description ?: 'Tidak ada deskripsi tambahan.' }}
                                </p>
                            </td>

                            <!-- Courses Count -->
                            <td class="py-4 px-4 text-center">
                                @if($cat->courses_count > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <span class="material-symbols-outlined text-[13px]">video_library</span>
                                        {{ $cat->courses_count }} Kursus
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500">
                                        0 Kursus
                                    </span>
                                @endif
                            </td>

                            <!-- Created At -->
                            <td class="py-4 px-4 text-slate-400 text-[11px]">
                                {{ $cat->created_at ? $cat->created_at->format('d M Y') : '-' }}
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            @click="openEdit({{ Js::from($cat) }})"
                                            class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-600 hover:text-indigo-600 transition-colors"
                                            title="Edit Kategori">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    <!-- Delete Button -->
                                    @if($cat->courses_count === 0)
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $cat->name }}\'?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 rounded-lg border border-slate-200 hover:bg-rose-50 text-slate-600 hover:text-rose-600 transition-colors"
                                                    title="Hapus Kategori">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                                onclick="alert('Kategori \'{{ $cat->name }}\' tidak dapat dihapus karena masih digunakan oleh {{ $cat->courses_count }} kursus.');"
                                                class="p-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-300 cursor-not-allowed"
                                                title="Terkunci: Masih digunakan oleh kursus">
                                            <span class="material-symbols-outlined text-[16px]">lock</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-[36px] text-slate-300 mb-1">category</span>
                                <p class="text-xs font-semibold text-slate-700">Tidak ada kategori ditemukan</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Silakan buat kategori baru melalui tombol di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH KATEGORI ==================== -->
    <div x-show="isAddCategoryOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
         @keydown.escape.window="isAddCategoryOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-md w-full p-6 shadow-xl space-y-4"
             @click.away="isAddCategoryOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Tambah Kategori Baru</h3>
                        <p class="text-[11px] text-slate-500">Buat klasifikasi materi kursus pembelajaran</p>
                    </div>
                </div>
                <button type="button" @click="isAddCategoryOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: Kecerdasan Buatan & AI"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                    <p class="text-[10px] text-slate-400 mt-1">Slug URL akan dibuat secara otomatis dari nama kategori.</p>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan cakupan topik atau sasaran keahlian dari kategori ini..."
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isAddCategoryOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs">
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT KATEGORI ==================== -->
    <div x-show="isEditCategoryOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
         @keydown.escape.window="isEditCategoryOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-md w-full p-6 shadow-xl space-y-4"
             @click.away="isEditCategoryOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Edit Kategori</h3>
                        <p class="text-[11px] text-slate-500">Perbarui nama dan deskripsi kategori kursus</p>
                    </div>
                </div>
                <button type="button" @click="isEditCategoryOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form :action="'/admin/categories/' + editCategoryData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editCategoryData.name" required
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" x-model="editCategoryData.description" rows="3"
                              class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isEditCategoryOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs">
                        Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
