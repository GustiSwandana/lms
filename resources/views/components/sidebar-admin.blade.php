@php
    $pendingSupportBadgeCount = \Illuminate\Support\Facades\Cache::remember(
        'support_unread_badge_'.(auth()->user()?->isSuperAdmin() ? 'super' : ('school_'.(auth()->user()?->school_id ?? auth()->id()))),
        300,
        function () {
            $user = auth()->user();
            if (! $user) return 0;
            if ($user->isSuperAdmin()) {
                return \App\Models\SupportTicket::where('status', 'menunggu')->count();
            }
            return \App\Models\SupportTicket::where(function ($q) use ($user) {
                if ($user->school_id) {
                    $q->where('school_id', $user->school_id);
                } else {
                    $q->where('user_id', $user->id);
                }
            })->whereIn('status', ['menunggu', 'diproses'])->count();
        }
    );
@endphp

<!-- Mobile Backdrop Overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false" 
     x-cloak 
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 md:hidden transition-opacity"></div>

<aside class="fixed left-0 top-0 h-screen w-64 flex flex-col justify-between p-4 border-r border-slate-200 z-50 bg-white transition-transform duration-300 md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0 shadow-xl' : '-translate-x-full md:translate-x-0'">
    <!-- Top Brand & Core Navigation Group -->
    <div class="flex flex-col gap-4">
        <!-- Admin Header Brand with Mobile Close Button -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-3 px-1">
                <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold shadow-sm">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">school</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-slate-900 tracking-tight">EduTube LMS</span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[11px] text-slate-500 font-medium">Portal Admin</span>
                        <span class="px-1.5 py-0.2 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-semibold">
                            {{ auth()->user()?->role_label ?? 'Admin' }}
                        </span>
                    </div>
                </div>
            </div>
            <button type="button" @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-100">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Quick Action CTA inside SideNavBar -->
        <button type="button" @click="$dispatch('open-import-modal')" 
                class="w-full py-2.5 px-3 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-xs transition-colors duration-150 flex items-center justify-center gap-2 shadow-sm">
            <span class="material-symbols-outlined text-[16px]">add_circle</span>
            <span>+ Tambah Video Baru</span>
        </button>

        <!-- Navigation Tabs -->
        <nav class="flex flex-col space-y-1">
            <!-- Dashboard / Video Pembelajaran -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.dashboard') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">dashboard</span>
                <span>Video Pembelajaran</span>
                <span class="ml-auto bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] font-medium">{{ \App\Models\Lesson::count() }}</span>
            </a>

            <!-- Course Manager -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.courses.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.courses.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.courses.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.courses.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">video_library</span>
                <span>Manajer Kursus</span>
                <span class="ml-auto bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] font-medium">{{ \App\Models\Course::count() }}</span>
            </a>

            <!-- Category Manager -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.categories.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.categories.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.categories.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">category</span>
                <span>Kategori Kursus</span>
                <span class="ml-auto bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] font-medium">{{ \App\Models\Category::count() }}</span>
            </a>

            <!-- Curriculum Builder -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.curriculum.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.curriculum.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.curriculum.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.curriculum.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">account_tree</span>
                <span>Penyusun Kurikulum</span>
            </a>

            <!-- Students -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.students.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.students.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.students.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.students.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">group</span>
                <span>Siswa / Mahasiswa</span>
                <span class="ml-auto bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-[10px] font-medium">{{ \App\Models\User::where('role', 'student')->count() }}</span>
            </a>

            @if(auth()->user()?->isSuperAdmin())
            <div class="pt-2 pb-1 px-3 text-[10px] font-semibold uppercase tracking-wider text-slate-400">Pusat</div>

            <!-- User Management -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.users.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.users.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.users.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">manage_accounts</span>
                <span>Manajemen Pengguna</span>
            </a>

            <!-- Master Schools Management -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.schools.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.schools.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.schools.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.schools.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">domain</span>
                <span>Master Sekolah</span>
            </a>

            <!-- Settings -->
            <a class="flex items-center gap-3 px-3 py-2 rounded-lg transition-colors text-xs font-medium {{ request()->routeIs('admin.settings.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.settings.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.settings.*') ? 'text-indigo-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.settings.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">settings</span>
                <span>Pengaturan Sistem</span>
            </a>
            @endif
        </nav>
    </div>

    <!-- Bottom Anchor: Footer Tabs + Admin Profile Snippet -->
    <div class="flex flex-col gap-1.5 pt-3 border-t border-slate-100">
        <a class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors text-xs {{ request()->routeIs('admin.docs.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
           href="{{ route('admin.docs.index') }}">
            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.docs.*') ? 'text-indigo-600' : 'text-slate-400' }}">menu_book</span>
            <span>Dokumentasi API</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-colors text-xs {{ request()->routeIs('admin.support.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
           href="{{ route('admin.support.index') }}">
            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.support.*') ? 'text-indigo-600' : 'text-slate-400' }}">support_agent</span>
            <span>Dukungan & Log</span>
            @if($pendingSupportBadgeCount > 0)
                <span class="ml-auto bg-rose-600 text-white px-2 py-0.5 rounded-full text-[10px] font-bold">{{ $pendingSupportBadgeCount }}</span>
            @endif
        </a>

        <!-- Admin User Snippet with Role Identity -->
        <div class="mt-2 p-2.5 rounded-xl bg-slate-50 flex items-center justify-between border border-slate-200">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <img class="h-8 w-8 rounded-full border border-slate-200 object-cover shrink-0" 
                     src="{{ auth()->user()?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}"
                     alt="Foto Profil Admin"/>
                <div class="truncate">
                    <p class="text-xs text-slate-900 font-semibold truncate leading-tight">{{ auth()->user()?->name ?? 'Admin' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">
                        {{ auth()->user()?->role_label ?? 'Admin' }}
                        @if(auth()->user()?->school_name)
                            <span class="text-slate-400 font-normal">• {{ auth()->user()->school_name }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('student.dashboard') }}" title="Lihat Sebagai Siswa" class="text-slate-400 hover:text-indigo-600 p-1.5 rounded-lg hover:bg-white transition-colors">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
            </a>
        </div>
    </div>
</aside>
