@extends('layouts.admin')

@section('admin_content')
<div x-data="{ 
    isAddStudentOpen: false, 
    isImportCsvOpen: false,
    selectedSchool: '{{ $admin->isSchoolAdmin() ? $admin->school_name : ($schools->first() ?? 'other') }}' 
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

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajer Siswa & Mahasiswa</h1>
                <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">Total {{ $students->count() }} Pembelajar</span>
                @if($admin->isSchoolAdmin())
                    <span class="px-2 py-0.5 rounded-full text-[11px] bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                        {{ $admin->school_name }}
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">
                @if($admin->isSchoolAdmin())
                    Kelola dan daftarkan akun siswa yang menempuh pendidikan di <strong>{{ $admin->school_name }}</strong>.
                @else
                    Pantau seluruh siswa terdaftar lintas sekolah, kemajuan modul, dan penerbitan sertifikat kelulusan.
                @endif
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <!-- Search Input -->
            <form method="GET" action="{{ route('admin.students.index') }}" class="flex items-center gap-2">
                <div class="relative w-48 sm:w-64">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                        <span class="material-symbols-outlined text-[18px]">search</span>
                    </span>
                    <input name="q" value="{{ request('q') }}"
                           class="w-full bg-white text-slate-900 pl-9 pr-3 py-2 text-xs rounded-xl border border-slate-200 placeholder:text-slate-400 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 shadow-xs" 
                           placeholder="Cari nama/email..." type="text"/>
                </div>
                @if(request('q'))
                    <a href="{{ route('admin.students.index') }}" class="px-3 py-2 text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors">Reset</a>
                @endif
            </form>

            <a href="{{ route('admin.students.export') }}"
               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors whitespace-nowrap"
               title="Ekspor seluruh rekap data belajar siswa ke format CSV/Excel">
                <span class="material-symbols-outlined text-[16px]">download</span>
                <span>Ekspor</span>
            </a>
            <button type="button"
                    @click="isImportCsvOpen = true"
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-semibold shadow-xs transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">upload_file</span>
                <span>Import CSV</span>
            </button>
            <button type="button"
                    @click="isAddStudentOpen = true"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors whitespace-nowrap">
                <span class="material-symbols-outlined text-[16px]">person_add</span>
                <span>Tambah Siswa</span>
            </button>
        </div>
    </div>

    <!-- Students Data Table -->
    <section class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3 px-6">Informasi Siswa</th>
                        <th class="py-3 px-5">Asal Sekolah</th>
                        <th class="py-3 px-4">Status Akun</th>
                        <th class="py-3 px-4 text-center">Materi Selesai</th>
                        <th class="py-3 px-4 text-center">Kursus Disimpan</th>
                        <th class="py-3 px-5">Tanggal Terdaftar</th>
                        <th class="py-3 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($students as $st)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $st->avatar_url ?? 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80' }}" 
                                         class="w-9 h-9 rounded-full object-cover border border-slate-200" alt="Avatar"/>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-slate-900">{{ $st->name }}</span>
                                        <span class="text-[11px] text-slate-400">{{ $st->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px] font-medium border border-slate-200">
                                    <span class="material-symbols-outlined text-[13px] text-slate-500">school</span>
                                    {{ $st->school_name ?? 'Umum / Pusat' }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-semibold border border-emerald-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Siswa Aktif
                                </span>
                            </td>
                            <td class="py-4 px-4 text-center font-mono font-bold text-indigo-600">
                                {{ $st->lesson_completions_count ?? $st->lessonCompletions->count() }} Video
                            </td>
                            <td class="py-4 px-4 text-center font-mono text-slate-600">
                                {{ $st->bookmarked_courses_count ?? $st->bookmarkedCourses->count() }} Kursus
                            </td>
                            <td class="py-4 px-5 text-slate-400 font-mono text-[11px]">
                                {{ $st->created_at ? $st->created_at->format('d M Y') : '08 Okt 2026' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="inline-flex items-center gap-2 justify-end">
                                    <form action="{{ route('admin.students.reset-password', $st->id) }}" method="POST" 
                                          onsubmit="return confirm('Reset kata sandi siswa \'{{ $st->name }}\' menjadi \'Siswa123!\'?');" class="inline">
                                        @csrf
                                        <button type="submit" 
                                                class="px-2.5 py-1.5 rounded-lg border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-medium transition-colors inline-flex items-center gap-1"
                                                title="Reset Sandi ke Siswa123!">
                                            <span class="material-symbols-outlined text-[14px]">lock_reset</span>
                                            <span>Reset Sandi</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('student.dashboard') }}" target="_blank"
                                       class="px-2.5 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-medium transition-colors">
                                        Lihat Beranda
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <span class="material-symbols-outlined text-[36px] text-slate-300 mb-1">group_off</span>
                                <p class="text-xs font-semibold text-slate-700">Tidak ada data siswa ditemukan</p>
                                <p class="text-[11px] text-slate-400 mt-0.5">Gunakan tombol "Tambah Siswa" di atas untuk mendaftarkan peserta didik.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- ==================== MODAL TAMBAH SISWA ==================== -->
    <div x-show="isAddStudentOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
         @keydown.escape.window="isAddStudentOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isAddStudentOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">person_add</span>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Tambah Siswa Baru</h3>
                        <p class="text-[11px] text-slate-500">
                            @if($admin->isSchoolAdmin())
                                Pendaftaran siswa baru untuk <strong>{{ $admin->school_name }}</strong>
                            @else
                                Pendaftaran akun siswa pada ekosistem multi-sekolah
                            @endif
                        </p>
                    </div>
                </div>
                <button type="button" @click="isAddStudentOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.students.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Siswa</label>
                    <input type="text" name="name" required placeholder="Contoh: Muhammad Farhan"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                </div>

                <!-- Asal Sekolah -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Asal Sekolah</label>
                    @if($admin->isSchoolAdmin())
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-800 font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">school</span>
                            <span>{{ $admin->school_name }}</span>
                            <span class="ml-auto text-[10px] text-slate-500 bg-slate-200/60 px-2 py-0.5 rounded font-mono">Sekolah Anda</span>
                        </div>
                    @else
                        <div class="space-y-2">
                            <select name="school_name" x-model="selectedSchool" required
                                    class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none bg-white">
                                <option value="" disabled>-- Pilih Sekolah Terdaftar --</option>
                                @foreach($schools as $sch)
                                    <option value="{{ $sch }}">{{ $sch }}</option>
                                @endforeach
                                <option value="other">+ Sekolah Lainnya (Ketik Manual)</option>
                            </select>

                            <div x-show="selectedSchool === 'other'" x-cloak>
                                <input type="text" name="custom_school_name" placeholder="Ketik nama sekolah baru..."
                                       :required="selectedSchool === 'other'"
                                       class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none bg-amber-50/50"/>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email Login Siswa</label>
                    <input type="email" name="email" required placeholder="siswa@sekolah.sch.id"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kata Sandi Awal</label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isAddStudentOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs">
                        Simpan & Daftarkan Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL IMPORT CSV SISWA ==================== -->
    <div x-show="isImportCsvOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs"
         @keydown.escape.window="isImportCsvOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isImportCsvOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">upload_file</span>
                    </span>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Impor Massal Siswa via CSV</h3>
                        <p class="text-[11px] text-slate-500">
                            Daftarkan puluhan hingga ratusan siswa sekaligus menggunakan berkas CSV
                        </p>
                    </div>
                </div>
                <button type="button" @click="isImportCsvOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>

            <!-- Download Template Callout -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-3">
                <div class="flex items-center gap-2 text-xs text-slate-800">
                    <span class="material-symbols-outlined text-[20px] text-indigo-600">description</span>
                    <div>
                        <p class="font-semibold">Gunakan Format Kolom Standar</p>
                        <p class="text-[11px] text-slate-500">Kolom: <code class="font-mono text-indigo-700">name,email,password</code></p>
                    </div>
                </div>
                <a href="{{ route('admin.students.import-template') }}" 
                   class="px-3 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-semibold inline-flex items-center gap-1 shadow-xs transition-colors">
                    <span class="material-symbols-outlined text-[14px]">download</span>
                    <span>Unduh Template</span>
                </a>
            </div>

            <form action="{{ route('admin.students.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                @if($admin->isSuperAdmin())
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Target Instansi Sekolah <span class="text-rose-500">*</span></label>
                    <select name="school_name" required
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none bg-white">
                        @foreach($schools as $sch)
                            <option value="{{ $sch }}">{{ $sch }}</option>
                        @endforeach
                    </select>
                </div>
                @else
                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    <span>Seluruh siswa yang diimpor akan otomatis terdaftar di: <strong>{{ $admin->school_name }}</strong></span>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Berkas CSV (.csv) <span class="text-rose-500">*</span></label>
                    <input type="file" name="csv_file" accept=".csv,text/csv" required
                           class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-1 focus:ring-indigo-600 focus:border-indigo-600 focus:outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer"/>
                    <p class="text-[10px] text-slate-400 mt-1">
                        * Baris dengan email yang sudah ada di database akan dilewati secara otomatis (aman dari duplikasi).
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isImportCsvOpen = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs">
                        Mulai Impor Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
