@extends('layouts.admin')

@section('admin_content')
<div x-data="{
    isAddSchoolOpen: false,
    isEditSchoolOpen: false,
    editSchoolData: {
        id: null,
        name: '',
        npsn: '',
        address: '',
        logo_url: '',
        is_active: true
    },
    openEdit(s) {
        this.editSchoolData = {
            id: s.id,
            name: s.name,
            npsn: s.npsn || '',
            address: s.address || '',
            logo_url: s.logo_url || '',
            is_active: Boolean(s.is_active)
        };
        this.isEditSchoolOpen = true;
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
                <span>Terdapat kendala validasi data:</span>
            </div>
            <ul class="list-disc list-inside text-[11px] pl-6 space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-bold border border-indigo-200">
                    <span class="material-symbols-outlined text-[13px]">shield</span>
                    Eksklusif Admin Pusat
                </span>
                <span class="text-xs text-slate-500">• Master Tenant Scoping</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Master Data Sekolah & Instansi
            </h1>
            <p class="text-xs text-slate-500">
                Kelola master entitas sekolah/madrasah yang terdaftar dalam ekosistem LMS terpadu.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.users.index') }}" 
               class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-[18px] text-slate-500">manage_accounts</span>
                <span>Kelola Operator</span>
            </a>
            <button type="button"
                    @click="isAddSchoolOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-[18px]">add_business</span>
                <span>+ Tambah Instansi Sekolah</span>
            </button>
        </div>
    </div>

    <!-- Telemetry Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Instansi</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalSchools }}</h3>
                <p class="text-[11px] text-indigo-600 mt-0.5 font-medium">Mitra Terdaftar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <span class="material-symbols-outlined text-[24px]">school</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Sekolah Aktif</p>
                <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $activeSchools }}</h3>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Beroperasi Penuh</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <span class="material-symbols-outlined text-[24px]">verified</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Total Siswa Binaan</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $totalStudents }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Pembelajar Aktif</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center border border-slate-200">
                <span class="material-symbols-outlined text-[24px]">groups</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-xs text-slate-500">
            Menampilkan <strong>{{ $schools->count() }}</strong> data instansi sekolah
        </div>

        <form action="{{ route('admin.schools.index') }}" method="GET" class="w-full md:w-80 flex items-center gap-2">
            <div class="relative w-full">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </span>
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Cari nama sekolah, NPSN, kota..."
                       class="w-full pl-9 pr-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 transition-all"/>
            </div>
            @if($search)
                <a href="{{ route('admin.schools.index') }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-xs font-semibold text-slate-600">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Schools Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-6">Instansi Sekolah</th>
                        <th class="py-3.5 px-4">NPSN</th>
                        <th class="py-3.5 px-5">Alamat & Lokasi</th>
                        <th class="py-3.5 px-4 text-center">Operator & Siswa</th>
                        <th class="py-3.5 px-4 text-center">Kursus</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($schools as $school)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Name & Logo -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    @if($school->logo_url)
                                        <img src="{{ $school->logo_url }}" class="w-9 h-9 rounded-lg object-cover border border-slate-200" alt="Logo"/>
                                    @else
                                        <div class="w-9 h-9 rounded-lg bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-xs">
                                            {{ strtoupper(substr($school->name, 0, 2)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h4 class="font-bold text-slate-900 text-sm">{{ $school->name }}</h4>
                                        <span class="text-[11px] text-slate-400 font-mono">
                                            /{{ $school->slug }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <!-- NPSN -->
                            <td class="py-4 px-4 font-mono text-slate-900">
                                {{ $school->npsn ?: '-' }}
                            </td>

                            <!-- Address -->
                            <td class="py-4 px-5 max-w-xs">
                                <p class="text-xs text-slate-600 line-clamp-2">
                                    {{ $school->address ?: 'Alamat belum dilengkapi.' }}
                                </p>
                            </td>

                            <!-- Operators & Students -->
                            <td class="py-4 px-4 text-center">
                                <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span>{{ $school->operators_count }} Operator</span>
                                    <span>•</span>
                                    <span class="font-bold">{{ $school->students_count }} Siswa</span>
                                </div>
                            </td>

                            <!-- Courses Count -->
                            <td class="py-4 px-4 text-center font-semibold text-indigo-600">
                                {{ $school->courses_count }} Kursus
                            </td>

                            <!-- Active Status -->
                            <td class="py-4 px-4">
                                @if($school->is_active)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <!-- Action Buttons -->
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    <!-- Edit Button -->
                                    <button type="button"
                                            @click="openEdit({{ Js::from($school) }})"
                                            class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors"
                                            title="Edit Instansi">
                                        <span class="material-symbols-outlined text-[16px]">edit</span>
                                    </button>

                                    <!-- Toggle Status Form -->
                                    <form action="{{ route('admin.schools.toggle-status', $school->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="p-1.5 rounded-lg border border-slate-200 hover:bg-amber-50 text-amber-700 transition-colors"
                                                title="{{ $school->is_active ? 'Nonaktifkan Instansi' : 'Aktifkan Instansi' }}">
                                            <span class="material-symbols-outlined text-[16px]">
                                                {{ $school->is_active ? 'block' : 'check_circle' }}
                                            </span>
                                        </button>
                                    </form>

                                    <!-- Delete Button (Only if 0 users and 0 courses) -->
                                    @if($school->users_count === 0 && $school->courses_count === 0)
                                        <form action="{{ route('admin.schools.destroy', $school->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus instansi \'{{ $school->name }}\'?');"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-1.5 rounded-lg border border-rose-200 hover:bg-rose-50 text-rose-600 transition-colors"
                                                    title="Hapus Instansi">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </form>
                                    @else
                                        <button type="button"
                                                onclick="alert('Instansi \'{{ $school->name }}\' masih memiliki pengguna atau kursus yang aktif.');"
                                                class="p-1.5 rounded-lg border border-slate-200 bg-slate-50 text-slate-400 cursor-not-allowed"
                                                title="Terkunci: Masih menaungi data pengguna/kursus">
                                            <span class="material-symbols-outlined text-[16px]">lock</span>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-[40px] mb-1">school</span>
                                <p class="text-xs font-semibold text-slate-900">Belum ada instansi sekolah terdaftar</p>
                                <p class="text-[11px] text-slate-500 mt-0.5">Tambahkan instansi sekolah melalui tombol di atas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH SEKOLAH ==================== -->
    <div x-show="isAddSchoolOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="isAddSchoolOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isAddSchoolOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tambah Instansi Sekolah Baru</h3>
                        <p class="text-[11px] text-slate-500">Daftarkan lembaga pendidikan ke dalam sistem LMS</p>
                    </div>
                </div>
                <button type="button" @click="isAddSchoolOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.schools.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Resmi Sekolah / Instansi <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: SMA Negeri 1 Manado"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">Nomor Pokok Sekolah (NPSN)</label>
                        <input type="text" name="npsn" placeholder="Contoh: 20100123"
                               class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">URL Logo Sekolah (Opsional)</label>
                        <input type="url" name="logo_url" placeholder="https://..."
                               class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="2" placeholder="Jl. Budi Utomo No. 7, Jakarta Pusat..."
                              class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isAddSchoolOpen = false"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold">
                        Simpan Instansi Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT SEKOLAH ==================== -->
    <div x-show="isEditSchoolOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="isEditSchoolOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isEditSchoolOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Edit Data Instansi Sekolah</h3>
                        <p class="text-[11px] text-slate-500">Perbarui identitas dan status operasional sekolah</p>
                    </div>
                </div>
                <button type="button" @click="isEditSchoolOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="'/admin/schools/' + editSchoolData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Resmi Sekolah <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" x-model="editSchoolData.name" required
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">NPSN</label>
                        <input type="text" name="npsn" x-model="editSchoolData.npsn"
                               class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">Status Operasional</label>
                        <select name="is_active" x-model="editSchoolData.is_active"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none bg-white">
                            <option :value="true">Aktif Beroperasi</option>
                            <option :value="false">Nonaktif / Ditangguhkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">URL Logo Sekolah</label>
                    <input type="url" name="logo_url" x-model="editSchoolData.logo_url"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Alamat</label>
                    <textarea name="address" x-model="editSchoolData.address" rows="2"
                              class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none leading-relaxed"></textarea>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isEditSchoolOpen = false"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
