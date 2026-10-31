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

<aside class="fixed left-0 top-0 h-screen w-64 flex flex-col justify-between p-4 border-r border-slate-200 z-50 bg-white text-slate-800 transition-transform duration-300 md:translate-x-0 overflow-y-auto shadow-xs"
       :class="sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full md:translate-x-0'">
    <!-- Top Brand & Core Navigation Group -->
    <div class="flex flex-col gap-4">
        <!-- Admin Header Brand with Mobile Close Button -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center gap-3 px-1">
                <div class="h-9 w-9 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-bold shadow-xs">
                    <span class="material-symbols-outlined text-[20px]" style="font-variation-settings: 'FILL' 1;">nature_people</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-sm font-bold text-slate-900 tracking-tight leading-none">SIAGA BUMI<span class="text-emerald-600">-SSK</span></span>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="text-[10px] text-slate-500 font-medium">Portal Kelola</span>
                        <span class="px-1.5 py-0.2 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-semibold">
                            {{ auth()->user()?->role_label ?? 'Admin' }}
                        </span>
                    </div>
                </div>
            </div>
            <button type="button" @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-700 p-2 rounded-xl hover:bg-slate-100 min-h-11 min-w-11 inline-flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Quick Action CTA inside SideNavBar -->
        <button type="button" @click="$dispatch('open-import-modal')" 
                class="w-full min-h-11 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs transition-colors duration-150 flex items-center justify-center gap-2 shadow-xs">
            <span class="material-symbols-outlined text-[18px]">add_circle</span>
            <span>+ Tambah Video Baru</span>
        </button>

        <!-- Navigation Tabs -->
        <nav class="flex flex-col space-y-1">
            <!-- Dashboard / Video Pembelajaran -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.dashboard') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">dashboard</span>
                <span>Video Pembelajaran</span>
                <span class="ml-auto bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-mono tabular-nums">{{ \App\Models\Lesson::count() }}</span>
            </a>

            <!-- Course Manager -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.courses.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.courses.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.courses.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.courses.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">video_library</span>
                <span>Manajer Kursus</span>
                <span class="ml-auto bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-mono tabular-nums">{{ \App\Models\Course::count() }}</span>
            </a>

            <!-- Category Manager -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.categories.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.categories.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.categories.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.categories.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">category</span>
                <span>Kategori Kursus</span>
                <span class="ml-auto bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-mono tabular-nums">{{ \App\Models\Category::count() }}</span>
            </a>

            <!-- Curriculum Builder -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.curriculum.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.curriculum.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.curriculum.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.curriculum.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">account_tree</span>
                <span>Penyusun Kurikulum</span>
            </a>

            <!-- Students (Hidden from Guru, accessible by School Operator & Central Admin) -->
            @if(!auth()->user()?->isGuru())
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.students.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.students.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.students.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.students.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">group</span>
                <span>Siswa / Mahasiswa</span>
                <span class="ml-auto bg-slate-100 border border-slate-200 text-slate-600 px-2 py-0.5 rounded-md text-[10px] font-mono tabular-nums">{{ \App\Models\User::whereIn('role', ['student', 'siswa'])->count() }}</span>
            </a>
            @endif

            @if(auth()->user()?->canManageSettings())
            <div class="pt-3 pb-1 px-3 text-[10px] font-mono uppercase tracking-wider text-slate-400">Pusat BKKBN</div>

            <!-- User Management -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.users.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.users.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.users.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">manage_accounts</span>
                <span>Manajemen Pengguna</span>
            </a>

            <!-- Master Schools Management -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.schools.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.schools.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.schools.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.schools.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">domain</span>
                <span>Master Sekolah</span>
            </a>

            <!-- Settings -->
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-colors text-xs font-medium min-h-11 {{ request()->routeIs('admin.settings.*') ? 'bg-emerald-50 text-emerald-800 font-semibold border-l-2 border-emerald-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
               href="{{ route('admin.settings.index') }}">
                <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.settings.*') ? 'text-emerald-600' : 'text-slate-400' }}" style="{{ request()->routeIs('admin.settings.*') ? 'font-variation-settings: \'FILL\' 1;' : '' }}">settings</span>
                <span>Pengaturan Sistem</span>
            </a>
            @endif
        </nav>
    </div>

    <!-- Bottom Anchor: Footer Tabs + Admin Profile Snippet -->
    <div class="flex flex-col gap-2 pt-3 border-t border-slate-200">
        <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-xs min-h-11 {{ request()->routeIs('admin.docs.*') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
           href="{{ route('admin.docs.index') }}">
            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.docs.*') ? 'text-emerald-600' : 'text-slate-400' }}">menu_book</span>
            <span>Dokumentasi API</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 rounded-xl transition-colors text-xs min-h-11 {{ request()->routeIs('admin.support.*') ? 'bg-emerald-50 text-emerald-800 font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}" 
           href="{{ route('admin.support.index') }}">
            <span class="material-symbols-outlined text-[18px] {{ request()->routeIs('admin.support.*') ? 'text-emerald-600' : 'text-slate-400' }}">support_agent</span>
            <span>Dukungan & Log</span>
            @if($pendingSupportBadgeCount > 0)
                <span class="ml-auto bg-rose-600 text-white px-2 py-0.5 rounded-full text-[10px] font-bold font-mono tabular-nums">{{ $pendingSupportBadgeCount }}</span>
            @endif
        </a>

        <!-- Admin User Snippet with Role Identity -->
        <div class="mt-2 p-2.5 rounded-xl bg-slate-50 flex items-center justify-between border border-slate-200">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <img class="h-8 w-8 rounded-lg border border-slate-200 object-cover shrink-0" 
                     src="{{ auth()->user()?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80' }}"
                     alt="Foto Profil Admin"/>
                <div class="truncate">
                    <p class="text-xs text-slate-900 font-semibold truncate leading-tight">{{ auth()->user()?->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-slate-500 truncate">
                        {{ auth()->user()?->role_label ?? 'Admin' }}
                        @if(auth()->user()?->school_name)
                            <span class="text-emerald-700 font-medium">• {{ auth()->user()->school_name }}</span>
                        @endif
                    </p>
                </div>
            </div>
            <a href="{{ route('student.dashboard') }}" title="Lihat Sebagai Siswa" class="text-slate-400 hover:text-emerald-700 p-2 rounded-lg hover:bg-slate-200 transition-colors min-h-11 min-w-11 inline-flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
            </a>
        </div>
    </div>
</aside>
