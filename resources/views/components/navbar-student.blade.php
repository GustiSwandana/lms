<header class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-8 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white"
        x-data="{ isNotificationsOpen: false, isHelpOpen: false, isProfileOpen: false, mobileNavOpen: false }">
    <div class="flex items-center gap-4 sm:gap-6 flex-1 max-w-2xl">
        <!-- Mobile Nav Hamburger Button -->
        <button type="button" @click="mobileNavOpen = !mobileNavOpen" 
                class="lg:hidden p-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors"
                aria-label="Toggle Menu">
            <span class="material-symbols-outlined text-[20px]" x-text="mobileNavOpen ? 'close' : 'menu'">menu</span>
        </button>

        <!-- Brand Logo -->
        <a class="flex items-center gap-2.5 shrink-0" href="{{ route('student.dashboard') }}">
            <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-xs">
                <span class="material-symbols-outlined text-[18px]">nature_people</span>
            </div>
            <div class="flex flex-col">
                <span class="text-sm font-bold text-slate-900 tracking-tight leading-none">SIAGA BUMI<span class="text-indigo-600">-SSK</span></span>
                <span class="text-[9px] text-slate-400 font-medium">Sekolah Siaga Kependudukan</span>
            </div>
        </a>

        <!-- Quick Search Bar -->
        <form action="{{ route('student.dashboard') }}" method="GET" class="relative w-full max-w-sm hidden md:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </div>
            <input name="q" value="{{ request('q') }}"
                   class="w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 text-xs pl-8 pr-12 py-1.5 rounded-lg border border-slate-200 focus:outline-none focus:border-indigo-600 focus:bg-white focus:ring-1 focus:ring-indigo-600/20 transition-all" 
                   placeholder="Cari kursus, materi video..." type="text"/>
            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-white text-slate-400 border border-slate-200 rounded">⌘K</kbd>
            </div>
        </form>
    </div>

    <!-- Core Nav Links & Action Cluster -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Core Navigation Links -->
        <nav class="hidden lg:flex items-center gap-6 text-xs font-semibold">
            <a class="{{ request()->routeIs('student.dashboard', 'student.catalog') && !request('filter') ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors" 
               href="{{ route('student.dashboard') }}">Katalog</a>
            <a class="{{ request('filter') === 'ongoing' ? 'text-indigo-600 font-bold' : 'text-slate-600 hover:text-slate-900' }} transition-colors" 
               href="{{ route('student.dashboard', ['filter' => 'ongoing']) }}">Kursus Saya</a>
        </nav>

        <div class="h-4 w-px bg-slate-200 hidden lg:block"></div>

        <!-- Portal Admin Shortcut (for Admins) -->
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 text-xs font-medium transition-all">
            <span class="material-symbols-outlined text-[16px] text-indigo-600">admin_panel_settings</span>
            <span class="hidden md:inline">Portal Admin</span>
        </a>
        @endif

        <!-- Action & Profile Group -->
        <div class="flex items-center gap-2 relative">
            <!-- User Profile Avatar & Dropdown -->
            <div class="relative">
                @if(auth()->check())
                <button type="button" @click="isProfileOpen = !isProfileOpen; isNotificationsOpen = false; isHelpOpen = false"
                        class="cursor-pointer group focus:outline-none flex items-center gap-2 p-1 rounded-lg hover:bg-slate-50 transition-colors">
                    <img alt="Foto Profil" class="w-8 h-8 rounded-full border border-slate-200 object-cover" 
                         src="{{ auth()->user()->avatar_url ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}"/>
                    <div class="hidden md:flex flex-col text-left leading-none pr-1">
                        <span class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-slate-500 font-medium mt-0.5">{{ auth()->user()->role_label }}</span>
                    </div>
                    <span class="material-symbols-outlined text-[14px] text-slate-400">expand_more</span>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="isProfileOpen" 
                     @click.away="isProfileOpen = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-64 bg-white rounded-xl border border-slate-200 shadow-xl py-2 z-50 text-xs">
                    <div class="px-4 py-2 border-b border-slate-100 flex items-center gap-3">
                        <img class="w-8 h-8 rounded-full object-cover border border-slate-200" 
                             src="{{ auth()->user()->avatar_url ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" alt="Profile"/>
                        <div class="truncate">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <span class="px-1.5 py-0.2 rounded-md bg-indigo-50 text-indigo-700 text-[10px] font-semibold">
                                {{ auth()->user()->role_label }}
                            </span>
                            @if(auth()->user()->school_name)
                                <div class="text-[10px] text-slate-500 truncate mt-0.5">🏫 {{ auth()->user()->school_name }}</div>
                            @endif
                        </div>
                    </div>

                    <!-- Role Switcher for Testing/Demo -->
                    <div class="px-3 py-2 border-b border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">
                            Ganti Akun Demo
                        </span>
                        <div class="space-y-0.5">
                            <a href="{{ route('switch-role', 'super_admin') }}" 
                               class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 {{ auth()->user()->isSuperAdmin() ? 'bg-indigo-50 font-bold text-indigo-700' : '' }}">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-purple-600">shield</span>
                                    <span>Admin Pusat</span>
                                </span>
                                @if(auth()->user()->isSuperAdmin()) <span class="text-[10px] text-indigo-600 font-bold">Aktif</span> @endif
                            </a>
                            <a href="{{ route('switch-role', 'school_admin') }}" 
                               class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 {{ auth()->user()->isSchoolAdmin() ? 'bg-indigo-50 font-bold text-indigo-700' : '' }}">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-blue-600">school</span>
                                    <span>Admin Sekolah</span>
                                </span>
                                @if(auth()->user()->isSchoolAdmin()) <span class="text-[10px] text-indigo-600 font-bold">Aktif</span> @endif
                            </a>
                            <a href="{{ route('switch-role', 'student') }}" 
                               class="flex items-center justify-between px-2.5 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 {{ auth()->user()->isStudent() ? 'bg-indigo-50 font-bold text-indigo-700' : '' }}">
                                <span class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-600">person</span>
                                    <span>Siswa</span>
                                </span>
                                @if(auth()->user()->isStudent()) <span class="text-[10px] text-indigo-600 font-bold">Aktif</span> @endif
                            </a>
                        </div>
                    </div>

                    <div class="px-2 py-1 space-y-0.5">
                        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-indigo-600 font-medium transition-colors">
                            <span class="material-symbols-outlined text-[16px]">dashboard</span>
                            <span>Katalog Kursus</span>
                        </a>
                        <a href="{{ route('student.paths') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-indigo-600 font-medium transition-colors">
                            <span class="material-symbols-outlined text-[16px]">timeline</span>
                            <span>Alur Belajar</span>
                        </a>
                        <a href="{{ route('student.analytics') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-indigo-600 font-medium transition-colors">
                            <span class="material-symbols-outlined text-[16px]">analytics</span>
                            <span>Analitik Belajar</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                        <div class="h-px bg-slate-100 my-1"></div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg hover:bg-indigo-50 text-indigo-600 font-semibold transition-colors">
                            <span class="material-symbols-outlined text-[16px]">admin_panel_settings</span>
                            <span>Buka Portal Admin</span>
                        </a>
                        @endif
                    </div>

                    <!-- Logout Button -->
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
                @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold shadow-xs transition-all">
                    <span class="material-symbols-outlined text-[16px]">login</span>
                    <span>Masuk</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Slide-Down Nav Drawer -->
    <div x-show="mobileNavOpen" 
         @click.away="mobileNavOpen = false" 
         x-cloak 
         class="lg:hidden absolute top-16 left-0 right-0 bg-white border-b border-slate-200 shadow-lg p-4 flex flex-col gap-2 z-50">
        <a class="px-3 py-2 rounded-lg text-xs font-semibold {{ request()->routeIs('student.dashboard', 'student.catalog') && !request('filter') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}" 
           href="{{ route('student.dashboard') }}">Katalog Kursus</a>
        <a class="px-3 py-2 rounded-lg text-xs font-semibold {{ request('filter') === 'ongoing' ? 'bg-indigo-50 text-indigo-700' : 'text-slate-600 hover:bg-slate-50' }}" 
           href="{{ route('student.dashboard', ['filter' => 'ongoing']) }}">Kursus Saya</a>
        <a class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50" 
           href="{{ route('student.paths') }}">Alur Belajar</a>
        <a class="px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:bg-slate-50" 
           href="{{ route('student.analytics') }}">Analitik Belajar</a>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a class="px-3 py-2 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 flex items-center justify-between" 
           href="{{ route('admin.dashboard') }}">
           <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[16px]">admin_panel_settings</span> Portal Admin</span>
           <span>&rarr;</span>
        </a>
        @endif
    </div>
</header>
