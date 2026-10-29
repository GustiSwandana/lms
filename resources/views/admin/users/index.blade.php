@extends('layouts.admin')

@section('admin_content')
<div x-data="{
    isAddAdminOpen: false,
    isAddStudentOpen: false,
    isEditUserOpen: false,
    editUserData: {
        id: null,
        name: '',
        email: '',
        role: 'school_admin',
        school_name: '',
        is_active: true
    },
    openEditModal(user) {
        this.editUserData = {
            id: user.id,
            name: user.name,
            email: user.email,
            role: user.role,
            school_name: user.school_name || '',
            is_active: Boolean(user.is_active)
        };
        this.isEditUserOpen = true;
    }
}" class="space-y-6">

    <!-- Page Title & Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-slate-200">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[11px] font-bold border border-indigo-200">
                    <span class="material-symbols-outlined text-[13px]">shield</span>
                    Eksklusif Admin Pusat
                </span>
                <span class="text-xs text-slate-500">• Manajemen Otorisasi RBAC</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                Manajemen Pengguna & Admin Sekolah
            </h1>
            <p class="text-xs text-slate-500">
                Kelola akun Administrator Sekolah, Siswa terdaftar, serta otoritas aktivasi akun dalam ekosistem LMS.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button"
                    @click="isAddStudentOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-[18px]">group_add</span>
                <span>Tambah Siswa</span>
            </button>
            <button type="button"
                    @click="isAddAdminOpen = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-colors">
                <span class="material-symbols-outlined text-[18px]">person_add</span>
                <span>Tambah Operator Sekolah</span>
            </button>
        </div>
    </div>

    <!-- Telemetry Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Admin Sekolah</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $countSchoolAdmin }}</h3>
                <p class="text-[11px] text-indigo-600 mt-0.5 font-medium">Pengelola Kurikulum</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <span class="material-symbols-outlined text-[24px]">school</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Siswa Terdaftar</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $countStudent }}</h3>
                <p class="text-[11px] text-emerald-600 mt-0.5 font-medium">Pengguna Belajar</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <span class="material-symbols-outlined text-[24px]">group</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Admin Pusat</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ $countSuperAdmin }}</h3>
                <p class="text-[11px] text-indigo-600 mt-0.5 font-medium">Hak Akses Penuh</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center border border-slate-200">
                <span class="material-symbols-outlined text-[24px]">admin_panel_settings</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-medium text-slate-500">Akun Aktif</p>
                <h3 class="text-2xl font-bold text-emerald-700 mt-1">{{ $countActive }}/{{ $totalUsers }}</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Status Aktif Beroperasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                <span class="material-symbols-outlined text-[24px]">verified_user</span>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Role Filter Tabs -->
        <div class="flex items-center gap-1.5 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
            <a href="{{ route('admin.users.index', ['q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap {{ empty($currentRole) ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                Semua ({{ $totalUsers }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'school_admin', 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap {{ $currentRole === 'school_admin' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                Admin Sekolah ({{ $countSchoolAdmin }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'student', 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap {{ $currentRole === 'student' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                Siswa ({{ $countStudent }})
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'super_admin', 'q' => $search]) }}"
               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap {{ $currentRole === 'super_admin' ? 'bg-indigo-600 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50' }}">
                Admin Pusat ({{ $countSuperAdmin }})
            </a>
        </div>

        <!-- Search Input -->
        <form action="{{ route('admin.users.index') }}" method="GET" class="w-full md:w-72">
            @if($currentRole)
                <input type="hidden" name="role" value="{{ $currentRole }}">
            @endif
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <span class="material-symbols-outlined text-[18px]">search</span>
                </span>
                <input type="text"
                       name="q"
                       value="{{ $search }}"
                       placeholder="Cari nama, email, sekolah..."
                       class="w-full pl-9 pr-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 transition-all"/>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-5">Pengguna</th>
                        <th class="py-3.5 px-4">Role RBAC</th>
                        <th class="py-3.5 px-4">Instansi / Sekolah</th>
                        <th class="py-3.5 px-4">Status Akun</th>
                        <th class="py-3.5 px-4">Terdaftar</th>
                        <th class="py-3.5 px-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <!-- User Name & Email -->
                        <td class="py-3.5 px-5">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url ?: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}" 
                                     alt="Avatar" 
                                     class="w-9 h-9 rounded-full object-cover border border-slate-200 shadow-xs"/>
                                <div>
                                    <div class="font-bold text-slate-900 flex items-center gap-1.5">
                                        <span>{{ $user->name }}</span>
                                        @if(auth()->id() === $user->id)
                                            <span class="text-[10px] px-1.5 py-0.2 rounded bg-amber-50 text-amber-700 font-bold border border-amber-200">Anda</span>
                                        @endif
                                    </div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>

                        <!-- Role Badge -->
                        <td class="py-3.5 px-4">
                            @if($user->isSuperAdmin())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                    <span class="material-symbols-outlined text-[13px]">shield</span>
                                    Admin Pusat
                                </span>
                            @elseif($user->isSchoolAdmin())
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                    <span class="material-symbols-outlined text-[13px]">school</span>
                                    Admin Sekolah
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="material-symbols-outlined text-[13px]">person</span>
                                    Siswa
                                </span>
                            @endif
                        </td>

                        <!-- School Affiliation -->
                        <td class="py-3.5 px-4">
                            <span class="text-xs text-slate-900 font-medium">
                                {{ $user->school_name ?: 'Platform Pusat' }}
                            </span>
                        </td>

                        <!-- Active Status -->
                        <td class="py-3.5 px-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span>
                                    Dinonaktifkan
                                </span>
                            @endif
                        </td>

                        <!-- Registered Date -->
                        <td class="py-3.5 px-4 text-slate-400 text-[11px]">
                            {{ $user->created_at?->format('d M Y') ?? '-' }}
                        </td>

                        <!-- Actions -->
                        <td class="py-3.5 px-5 text-right">
                            <div class="inline-flex items-center gap-1.5 justify-end">
                                <!-- Edit Button -->
                                <button type="button"
                                        @click="openEditModal({{ Js::from($user) }})"
                                        class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 text-slate-700 transition-colors"
                                        title="Edit Pengguna">
                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                </button>

                                <!-- Reset Password Form -->
                                <form action="{{ route('admin.users.reset-password', $user->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Reset kata sandi pengguna {{ $user->name }} menjadi Siswa123!?')">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 rounded-lg border border-amber-200 bg-amber-50 hover:bg-amber-100 text-amber-800 transition-colors"
                                            title="Reset Kata Sandi ke Siswa123!">
                                        <span class="material-symbols-outlined text-[16px]">lock_reset</span>
                                    </button>
                                </form>

                                <!-- Toggle Active/Suspend Form -->
                                @if(auth()->id() !== $user->id)
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="p-1.5 rounded-lg border border-slate-200 hover:bg-amber-50 text-amber-700 transition-colors"
                                            title="{{ $user->is_active ? 'Nonaktifkan / Blokir Akun' : 'Aktifkan Akun' }}">
                                        <span class="material-symbols-outlined text-[16px]">
                                            {{ $user->is_active ? 'block' : 'lock_open' }}
                                        </span>
                                    </button>
                                </form>

                                <!-- Delete Form -->
                                <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                      method="POST" 
                                      class="inline"
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ $user->name }}? Tindakan ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 rounded-lg border border-rose-200 hover:bg-rose-50 text-rose-600 transition-colors"
                                            title="Hapus Akun">
                                        <span class="material-symbols-outlined text-[16px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400">
                            <span class="material-symbols-outlined text-[40px] mb-1">person_search</span>
                            <p class="text-sm font-semibold text-slate-900">Tidak ada pengguna ditemukan</p>
                            <p class="text-xs mt-0.5">Coba sesuaikan kata kunci pencarian atau tab filter Anda.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="p-4 border-t border-slate-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>

    <!-- ==================== MODAL TAMBAH ADMIN SEKOLAH ==================== -->
    <div x-show="isAddAdminOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="isAddAdminOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isAddAdminOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tambah Operator Sekolah Baru</h3>
                        <p class="text-[11px] text-slate-500">Akun ini memiliki wewenang mengelola konten kurikulum dan mendaftarkan siswa sekolah</p>
                    </div>
                </div>
                <button type="button" @click="isAddAdminOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.users.store-school-admin') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Lengkap Pengelola / Operator</label>
                    <input type="text" name="name" required placeholder="Contoh: Bambang S., S.Kom"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Instansi / Sekolah</label>
                    <input type="text" name="school_name" required placeholder="Contoh: SMP Negeri 1 Bitung"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Alamat Email Login</label>
                    <input type="email" name="email" required placeholder="bambang@smkn1jakarta.sch.id"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Kata Sandi Akun</label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isAddAdminOpen = false"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold">
                        Simpan Akun Operator Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL TAMBAH SISWA ==================== -->
    <div x-show="isAddStudentOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="isAddStudentOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isAddStudentOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Tambah Siswa Baru</h3>
                        <p class="text-[11px] text-slate-500">Daftarkan siswa baru ke dalam ekosistem LMS sekolah</p>
                    </div>
                </div>
                <button type="button" @click="isAddStudentOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form action="{{ route('admin.users.store-student') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Lengkap Siswa</label>
                    <input type="text" name="name" required placeholder="Contoh: Ahmad Rizki Pratama"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div x-data="{ addStudentSchool: '{{ $schools->first() ?? 'other' }}' }">
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Asal Sekolah / Instansi</label>
                    <div class="space-y-2">
                        <select name="school_name" x-model="addStudentSchool" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none bg-white">
                            <option value="" disabled>-- Pilih Sekolah Terdaftar --</option>
                            @foreach($schools as $sch)
                                <option value="{{ $sch }}">{{ $sch }}</option>
                            @endforeach
                            <option value="other">+ Sekolah Lainnya (Ketik Manual)</option>
                        </select>

                        <div x-show="addStudentSchool === 'other'" x-cloak>
                            <input type="text" name="custom_school_name" placeholder="Ketik nama sekolah baru..."
                                   :required="addStudentSchool === 'other'"
                                   class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none bg-amber-50/50"/>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Alamat Email Siswa</label>
                    <input type="email" name="email" required placeholder="siswa@sekolah.sch.id"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Kata Sandi Awal</label>
                    <input type="password" name="password" required minlength="6" placeholder="Minimal 6 karakter"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isAddStudentOpen = false"
                            class="px-4 py-2 rounded-lg border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs">
                        Simpan Akun Siswa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ==================== MODAL EDIT PENGGUNA ==================== -->
    <div x-show="isEditUserOpen" 
         x-cloak 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs"
         @keydown.escape.window="isEditUserOpen = false">
        <div class="bg-white rounded-xl border border-slate-200 max-w-lg w-full p-6 shadow-xl space-y-4"
             @click.away="isEditUserOpen = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">edit</span>
                    </span>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Perbarui Data Pengguna</h3>
                        <p class="text-[11px] text-slate-500">Ubah informasi profil, peran RBAC, dan status aktivasi</p>
                    </div>
                </div>
                <button type="button" @click="isEditUserOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>

            <form :action="'/admin/users/' + editUserData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Pengguna</label>
                    <input type="text" name="name" x-model="editUserData.name" required
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Alamat Email</label>
                    <input type="email" name="email" x-model="editUserData.email" required
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">Peran (Role RBAC)</label>
                        <select name="role" x-model="editUserData.role"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none bg-white">
                            <option value="super_admin">Admin Pusat</option>
                            <option value="school_admin">Admin Sekolah</option>
                            <option value="student">Siswa</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-900 mb-1">Status Aktivasi</label>
                        <select name="is_active" x-model="editUserData.is_active"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none bg-white">
                            <option :value="1">Aktif</option>
                            <option :value="0">Dinonaktifkan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Nama Instansi / Asal Sekolah</label>
                    <input type="text" name="school_name" x-model="editUserData.school_name" placeholder="Opsional (misal: SMA Negeri 1 Manado)"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-900 mb-1">Ubah Kata Sandi (Kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" minlength="6" placeholder="Kosongkan jika tetap"
                           class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:border-indigo-600 focus:outline-none"/>
                </div>

                <div class="pt-3 border-t border-slate-200 flex items-center justify-end gap-2.5">
                    <button type="button" @click="isEditUserOpen = false"
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
