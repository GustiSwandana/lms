@php
    $pendingTicketsCount = \Illuminate\Support\Facades\Cache::remember(
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
<header class="flex justify-between items-center w-full px-4 sm:px-6 h-16 border-b border-slate-200 sticky top-0 z-30 bg-white/95 backdrop-blur-md text-slate-800 shadow-xs"
        x-data="{ isNotificationsOpen: false, isHelpOpen: false }">
    <!-- Hamburger Button for Mobile Drawer -->
    <button type="button" @click="sidebarOpen = !sidebarOpen" 
            class="md:hidden min-h-11 min-w-11 inline-flex items-center justify-center p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors mr-2 shrink-0" 
            aria-label="Buka Menu Navigasi">
        <span class="material-symbols-outlined text-[20px]">menu</span>
    </button>

    <!-- Search Bar -->
    <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-4 flex-1 max-w-md">
        <div class="relative w-full">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </span>
            <input name="q" value="{{ request('q') }}"
                   class="w-full bg-slate-50 text-slate-900 pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-500/20 transition-all placeholder:text-slate-400" 
                   placeholder="Cari materi, judul video, atau kurikulum..." type="text"/>
        </div>
    </form>

    <div class="flex items-center gap-2">
        <!-- Support Ticket Quick Link & Badge -->
        <a href="{{ route('admin.support.index') }}" 
           class="min-h-11 min-w-11 inline-flex items-center justify-center p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors relative" 
           title="Tiket Dukungan & Log Layanan">
            <span class="material-symbols-outlined text-[18px]">support_agent</span>
            @if($pendingTicketsCount > 0)
                <span class="absolute top-2 right-2 min-w-[16px] h-[16px] px-1 rounded-full bg-rose-600 text-white text-[9px] font-bold font-mono tabular-nums flex items-center justify-center border-2 border-white">
                    {{ $pendingTicketsCount }}
                </span>
            @endif
        </a>

        <!-- Notifications Dropdown -->
        <div class="relative">
            <button type="button" @click="isNotificationsOpen = !isNotificationsOpen; isHelpOpen = false"
                    class="min-h-11 min-w-11 inline-flex items-center justify-center p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors relative">
                <span class="material-symbols-outlined text-[18px]">notifications</span>
                <span class="absolute top-2.5 right-2.5 w-2 h-2 rounded-full bg-emerald-600"></span>
            </button>

            <div x-show="isNotificationsOpen" 
                 @click.away="isNotificationsOpen = false" 
                 x-cloak
                 class="absolute right-0 mt-2 w-72 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-50 text-xs text-slate-700">
                <div class="px-4 py-2.5 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-900">Notifikasi</span>
                    <span class="text-[10px] text-emerald-800 font-semibold bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">Sistem Siaga</span>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="px-4 py-3 hover:bg-slate-50 transition-colors">
                        <p class="font-semibold text-slate-900 text-[11px]">YouTube Ingestion Siap</p>
                        <p class="text-slate-500 text-[10px] mt-0.5">Layanan oEmbed publik siap mengekstrak metadata video.</p>
                    </div>
                    <div class="px-4 py-3 hover:bg-slate-50 transition-colors">
                        <p class="font-semibold text-slate-900 text-[11px]">Cloud Auto-Save Aktif</p>
                        <p class="text-slate-500 text-[10px] mt-0.5">Perubahan kurikulum dan soal tersimpan otomatis.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Button -->
        <button type="button" @click="isHelpOpen = true; isNotificationsOpen = false"
                class="min-h-11 min-w-11 inline-flex items-center justify-center p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
            <span class="material-symbols-outlined text-[18px]">help_outline</span>
        </button>

        <div class="h-5 w-px bg-slate-200 mx-1"></div>

        <!-- User Role Profile Dropdown & Quick Switcher -->
        <div class="relative" x-data="{ isProfileOpen: false }">
            <button type="button" @click="isProfileOpen = !isProfileOpen"
                    class="flex items-center gap-2 p-1.5 rounded-xl border border-slate-200 hover:bg-slate-100 transition-all min-h-11">
                <img class="w-7 h-7 rounded-lg object-cover border border-slate-200"
                     src="{{ auth()->user()?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}"
                     alt="Avatar"/>
                <div class="hidden md:flex flex-col text-left leading-none pr-1">
                    <span class="text-xs font-bold text-slate-900">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    <span class="text-[10px] text-emerald-700 font-medium mt-0.5">{{ auth()->user()?->role_label ?? 'Admin' }}</span>
                </div>
                <span class="material-symbols-outlined text-[16px] text-slate-400">expand_more</span>
            </button>

            <!-- Dropdown -->
            <div x-show="isProfileOpen" 
                 @click.away="isProfileOpen = false" 
                 x-cloak
                 class="absolute right-0 mt-2 w-64 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-50 text-xs text-slate-700">
                <div class="px-4 py-2.5 border-b border-slate-100">
                    <p class="font-bold text-slate-900 truncate">{{ auth()->user()?->name ?? 'Admin' }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ auth()->user()?->email }}</p>
                    <span class="inline-block mt-1 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-semibold">
                        {{ auth()->user()?->role_label ?? 'Administrator' }}
                    </span>
                </div>

                <div class="px-2 py-1 space-y-0.5">
                    <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                        <span>Lihat Sebagai Siswa</span>
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-slate-900 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">settings</span>
                        <span>Pengaturan LMS</span>
                    </a>
                </div>

                <div class="pt-1 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-left text-rose-600 hover:bg-rose-50 transition-colors rounded-b-xl min-h-11">
                            <span class="material-symbols-outlined text-[16px]">logout</span>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
