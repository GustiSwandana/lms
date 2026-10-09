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
<header class="flex justify-between items-center w-full px-4 sm:px-6 h-16 border-b border-slate-200 sticky top-0 z-30 bg-white"
        x-data="{ isNotificationsOpen: false, isHelpOpen: false }">
    <!-- Hamburger Button for Mobile Drawer -->
    <button type="button" @click="sidebarOpen = !sidebarOpen" 
            class="md:hidden p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors mr-2 shrink-0" 
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
                   class="w-full bg-slate-50 text-slate-900 pl-9 pr-4 py-1.5 text-xs rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 focus:bg-white focus:ring-1 focus:ring-indigo-600/20 transition-all placeholder:text-slate-400" 
                   placeholder="Cari materi, judul video, atau kurikulum..." type="text"/>
        </div>
    </form>

    <div class="flex items-center gap-1.5 sm:gap-2">
        <!-- Support Ticket Quick Link & Badge -->
        <a href="{{ route('admin.support.index') }}" 
           class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors relative flex items-center" 
           title="Tiket Dukungan & Log Layanan">
            <span class="material-symbols-outlined text-[18px]">support_agent</span>
            @if($pendingTicketsCount > 0)
                <span class="absolute top-1 right-1 min-w-[16px] h-[16px] px-1 rounded-full bg-rose-600 text-white text-[9px] font-bold flex items-center justify-center border-2 border-white">
                    {{ $pendingTicketsCount }}
                </span>
            @endif
        </a>

        <!-- Notifications Dropdown -->
        <div class="relative">
            <button type="button" @click="isNotificationsOpen = !isNotificationsOpen; isHelpOpen = false"
                    class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors relative">
                <span class="material-symbols-outlined text-[18px]">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
            </button>

            <div x-show="isNotificationsOpen" 
                 @click.away="isNotificationsOpen = false"
                 x-cloak
                 class="absolute right-0 mt-2 w-72 bg-white rounded-xl border border-slate-200 shadow-lg py-2 z-50 text-xs">
                <div class="px-4 py-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="font-bold text-slate-900">Notifikasi</span>
                    <span class="text-[10px] text-emerald-600 font-semibold bg-emerald-50 px-1.5 py-0.5 rounded">Sistem Aktif</span>
                </div>
                <div class="divide-y divide-slate-100">
                    <div class="px-4 py-2.5 hover:bg-slate-50 transition-colors">
                        <p class="font-semibold text-slate-800 text-[11px]">YouTube Ingestion Siap</p>
                        <p class="text-slate-500 text-[10px] mt-0.5">Layanan oEmbed publik siap mengekstrak metadata video.</p>
                    </div>
                    <div class="px-4 py-2.5 hover:bg-slate-50 transition-colors">
                        <p class="font-semibold text-slate-800 text-[11px]">Cloud Auto-Save Aktif</p>
                        <p class="text-slate-500 text-[10px] mt-0.5">Perubahan kurikulum dan soal tersimpan otomatis.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Help Button -->
        <button type="button" @click="isHelpOpen = true; isNotificationsOpen = false"
                class="p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-[18px]">help_outline</span>
        </button>

        <div class="h-5 w-px bg-slate-200 mx-1"></div>

        <!-- User Role Profile Dropdown & Quick Switcher -->
        <div class="relative" x-data="{ isProfileOpen: false }">
            <button type="button" @click="isProfileOpen = !isProfileOpen"
                    class="flex items-center gap-2 p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-all">
                <img class="w-7 h-7 rounded-full object-cover border border-slate-200"
                     src="{{ auth()->user()?->avatar_url ?? 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80' }}"
                     alt="Avatar"/>
                <div class="hidden md:flex flex-col text-left leading-none pr-1">
                    <span class="text-xs font-bold text-slate-900">{{ auth()->user()?->name ?? 'Admin' }}</span>
                    <span class="text-[10px] font-semibold text-indigo-600 mt-0.5">
                        {{ auth()->user()?->role_label ?? 'Admin' }}
                    </span>
                </div>
                <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="isProfileOpen"
                 @click.away="isProfileOpen = false"
                 x-cloak
                 class="absolute right-0 mt-2 w-64 bg-white rounded-xl border border-slate-200 shadow-xl py-2 z-50 text-xs">
                <!-- User Info -->
                <div class="px-4 py-2 border-b border-slate-100">
                    <div class="font-bold text-slate-900">{{ auth()->user()?->name ?? 'Admin' }}</div>
                    <div class="text-[11px] text-slate-500 font-mono">{{ auth()->user()?->email }}</div>
                    @if(auth()->user()?->school_name)
                        <div class="text-[10px] font-medium text-indigo-600 mt-1">🏫 {{ auth()->user()->school_name }}</div>
                    @endif
                </div>

                <!-- Quick Role Switcher for Demo -->
                <div class="px-3 py-2 border-b border-slate-100">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1.5">
                        Ganti Akun Demo (Uji RBAC)
                    </span>
                    <div class="space-y-1">
                        <a href="{{ route('switch-role', 'super_admin') }}" 
                           class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 {{ auth()->user()?->isSuperAdmin() ? 'bg-indigo-50 font-bold text-indigo-700' : '' }}">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-purple-600">shield</span>
                                <span>Admin Pusat</span>
                            </span>
                            @if(auth()->user()?->isSuperAdmin()) <span class="text-[10px] text-indigo-600 font-bold">Aktif</span> @endif
                        </a>
                        <a href="{{ route('switch-role', 'school_admin') }}" 
                           class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 {{ auth()->user()?->isSchoolAdmin() ? 'bg-indigo-50 font-bold text-indigo-700' : '' }}">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-blue-600">school</span>
                                <span>Admin Sekolah</span>
                            </span>
                            @if(auth()->user()?->isSchoolAdmin()) <span class="text-[10px] text-indigo-600 font-bold">Aktif</span> @endif
                        </a>
                        <a href="{{ route('switch-role', 'student') }}" 
                           class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700">
                            <span class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px] text-emerald-600">person</span>
                                <span>Siswa</span>
                            </span>
                        </a>
                    </div>
                </div>

                <!-- Navigation Links -->
                <div class="py-1">
                    <a href="{{ route('student.dashboard') }}" 
                       class="flex items-center gap-2 px-4 py-2 hover:bg-slate-50 text-slate-700 transition-colors">
                        <span class="material-symbols-outlined text-[16px] text-indigo-600">visibility</span>
                        <span>Beralih ke Tampilan Siswa</span>
                    </a>
                </div>

                <!-- Logout Form -->
                <div class="pt-1 border-t border-slate-100">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center gap-2 px-4 py-2 text-left text-rose-600 hover:bg-rose-50 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">logout</span>
                            <span>Keluar dari Akun</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Modal Dialog -->
    <div x-show="isHelpOpen" 
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-xs">
        <div @click.away="isHelpOpen = false" 
             class="bg-white rounded-xl border border-slate-200 shadow-xl max-w-md w-full p-6 space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">help_center</span>
                    </span>
                    <h3 class="text-sm font-bold text-slate-900">Panduan Portal Admin LMS</h3>
                </div>
                <button type="button" @click="isHelpOpen = false" class="text-slate-400 hover:text-slate-700">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
            <div class="space-y-3 text-xs text-slate-600">
                <div class="p-3 bg-slate-50 rounded-lg border border-slate-200 space-y-1">
                    <p class="font-bold text-slate-900">Cara Mengimpor Materi Video:</p>
                    <ul class="list-disc list-inside space-y-1 text-[11px]">
                        <li>Klik <strong>+ Tambah Video Baru</strong> atau gunakan Quick Ingestion bar di atas tabel.</li>
                        <li>Tempel URL YouTube. Sistem otomatis mengekstrak thumbnail resolusi tinggi dan judul materi.</li>
                    </ul>
                </div>
            </div>
            <div class="pt-3 border-t border-slate-100 flex justify-end">
                <button type="button" @click="isHelpOpen = false" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
</header>
