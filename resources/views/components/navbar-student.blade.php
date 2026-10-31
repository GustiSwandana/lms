<header class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-8 h-16 border-b border-slate-200 sticky top-0 z-50 bg-white/95 backdrop-blur-md text-slate-800 shadow-xs"
        x-data="{ isNotificationsOpen: false, isHelpOpen: false, isProfileOpen: false, mobileNavOpen: false }">
    <div class="flex items-center gap-4 sm:gap-6 flex-1 max-w-2xl">
        <!-- Mobile Nav Hamburger Button -->
        <button type="button" @click="mobileNavOpen = !mobileNavOpen" 
                class="md:hidden min-h-11 min-w-11 inline-flex items-center justify-center p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors"
                aria-label="Toggle Menu">
            <span class="material-symbols-outlined text-[22px]" x-text="mobileNavOpen ? 'close' : 'menu'">menu</span>
        </button>

        <!-- Brand Identity -->
        <a class="flex items-center gap-3 shrink-0 group focus:outline-none" href="{{ route('student.dashboard') }}">
            <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white border border-emerald-700/20 shadow-xs group-hover:bg-emerald-700 transition-all">
                <span class="material-symbols-outlined text-[20px]">nature_people</span>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold tracking-tight text-slate-900 leading-none">SIAGA BUMI<span class="text-emerald-600">-SSK</span></span>
                    @if(auth()->check() && auth()->user()->school_name)
                        <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800">
                            {{ auth()->user()->school_name }}
                        </span>
                    @endif
                </div>
                <span class="text-[10px] text-slate-500 font-medium tracking-wide">BKKBN Sulawesi Utara</span>
            </div>
        </a>

        <!-- Quick Search Bar (Desktop) -->
        <form action="{{ route('student.dashboard') }}" method="GET" class="relative w-full max-w-sm hidden lg:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                <span class="material-symbols-outlined text-[16px]">search</span>
            </div>
            <input name="q" value="{{ request('q') }}"
                   class="w-full bg-slate-50 text-slate-900 placeholder:text-slate-400 text-xs pl-8 pr-12 py-2 rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-600 focus:bg-white focus:ring-1 focus:ring-emerald-500/20 transition-all" 
                   placeholder="Cari materi kependudukan, lingkungan..." type="text"/>
            <div class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                <kbd class="px-1.5 py-0.5 text-[10px] font-mono bg-slate-100 text-slate-500 border border-slate-200 rounded">⌘K</kbd>
            </div>
        </form>
    </div>

    <!-- Core Nav Links & Action Cluster -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Desktop Core Navigation Links -->
        <nav class="hidden md:flex items-center gap-6 text-xs font-semibold">
            <a class="{{ request()->routeIs('student.dashboard') && !request('filter') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-0.5' : 'text-slate-600 hover:text-slate-900' }} transition-colors min-h-11 inline-flex items-center" 
               href="{{ route('student.dashboard') }}">Beranda</a>
            <a class="{{ request('filter') === 'ongoing' ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-0.5' : 'text-slate-600 hover:text-slate-900' }} transition-colors min-h-11 inline-flex items-center" 
               href="{{ route('student.dashboard', ['filter' => 'ongoing']) }}">Materi</a>
            <a class="{{ request()->routeIs('student.paths') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-0.5' : 'text-slate-600 hover:text-slate-900' }} transition-colors min-h-11 inline-flex items-center" 
               href="{{ route('student.paths') }}">Catatan Saya</a>
            <a class="{{ request()->routeIs('student.analytics') ? 'text-emerald-700 font-bold border-b-2 border-emerald-600 pb-0.5' : 'text-slate-600 hover:text-slate-900' }} transition-colors min-h-11 inline-flex items-center" 
               href="{{ route('student.analytics') }}">Sertifikat</a>
        </nav>

        <div class="h-5 w-px bg-slate-200 hidden md:block"></div>

        <!-- Portal Admin Shortcut (for Admins) -->
        @if(auth()->check() && auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}" 
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl border border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold transition-all min-h-11">
            <span class="material-symbols-outlined text-[18px]">admin_panel_settings</span>
            <span class="hidden sm:inline">Portal Admin</span>
        </a>
        @endif

        <!-- Action & Profile Group -->
        <div class="flex items-center gap-2 relative">
            <div class="relative">
                @if(auth()->check())
                <button type="button" @click="isProfileOpen = !isProfileOpen"
                        class="cursor-pointer group focus:outline-none flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors min-h-11">
                    <img alt="Foto Profil" class="w-8 h-8 rounded-lg border border-slate-200 object-cover" 
                         src="{{ auth()->user()->avatar_url ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}"/>
                    <div class="hidden sm:flex flex-col text-left leading-none pr-1">
                        <span class="text-xs font-bold text-slate-900">{{ auth()->user()->name }}</span>
                        <span class="text-[10px] text-emerald-700 font-medium mt-0.5">{{ auth()->user()->role_label }}</span>
                    </div>
                    <span class="material-symbols-outlined text-[16px] text-slate-400 group-hover:text-slate-700 transition-colors">expand_more</span>
                </button>

                <!-- Profile Dropdown Menu -->
                <div x-show="isProfileOpen" 
                     @click.away="isProfileOpen = false"
                     x-cloak
                     class="absolute right-0 mt-2 w-72 bg-white rounded-2xl border border-slate-200 shadow-xl py-2 z-50 text-xs text-slate-700">
                    <div class="px-4 py-3 border-b border-slate-100 flex items-center gap-3">
                        <img class="w-10 h-10 rounded-xl object-cover border border-slate-200" 
                             src="{{ auth()->user()->avatar_url ?: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' }}" alt="Profile"/>
                        <div class="truncate">
                            <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->name }}</p>
                            <span class="inline-block mt-0.5 px-2 py-0.5 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] font-semibold">
                                {{ auth()->user()->role_label }}
                            </span>
                            @if(auth()->user()->school_name)
                                <div class="text-[10px] text-slate-500 truncate mt-1">🏫 {{ auth()->user()->school_name }}</div>
                            @endif
                        </div>
                    </div>


                    <!-- Links -->
                    <div class="px-2 py-1 space-y-0.5">
                        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-slate-900 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">dashboard</span>
                            <span>Katalog Kursus</span>
                        </a>
                        <a href="{{ route('student.analytics') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg hover:bg-slate-50 text-slate-700 hover:text-slate-900 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">analytics</span>
                            <span>Analitik Belajar</span>
                        </a>

                        @if(auth()->user()->isAdmin())
                        <div class="h-px bg-slate-100 my-1"></div>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 font-semibold transition-colors">
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
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-left text-rose-600 hover:bg-rose-50 transition-colors rounded-b-xl min-h-11">
                                <span class="material-symbols-outlined text-[16px]">logout</span>
                                <span>Keluar dari Akun</span>
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold shadow-xs transition-all min-h-11">
                    <span class="material-symbols-outlined text-[18px]">login</span>
                    <span>Masuk</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Mobile Slide-Down Drawer (< md) -->
    <div x-show="mobileNavOpen" 
         @click.away="mobileNavOpen = false" 
         x-cloak 
         class="md:hidden absolute top-16 left-0 right-0 bg-white border-b border-slate-200 shadow-xl p-4 flex flex-col gap-2 z-50">
        <a class="px-4 py-3 rounded-xl text-xs font-semibold min-h-11 flex items-center {{ request()->routeIs('student.dashboard', 'student.catalog') && !request('filter') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'text-slate-700 hover:bg-slate-50' }}" 
           href="{{ route('student.dashboard') }}">Katalog Materi</a>
        <a class="px-4 py-3 rounded-xl text-xs font-semibold min-h-11 flex items-center {{ request('filter') === 'ongoing' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'text-slate-700 hover:bg-slate-50' }}" 
           href="{{ route('student.dashboard', ['filter' => 'ongoing']) }}">Materi Saya</a>
        <a class="px-4 py-3 rounded-xl text-xs font-semibold min-h-11 flex items-center text-slate-700 hover:bg-slate-50" 
           href="{{ route('student.paths') }}">Alur Belajar</a>
        <a class="px-4 py-3 rounded-xl text-xs font-semibold min-h-11 flex items-center text-slate-700 hover:bg-slate-50" 
           href="{{ route('student.analytics') }}">Analitik Belajar</a>
        @if(auth()->check() && auth()->user()->isAdmin())
        <a class="px-4 py-3 rounded-xl text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between min-h-11" 
           href="{{ route('admin.dashboard') }}">
           <span class="flex items-center gap-2"><span class="material-symbols-outlined text-[18px]">admin_panel_settings</span> Portal Admin</span>
           <span>&rarr;</span>
        </a>
        @endif
    </div>
</header>

<!-- Floating Bottom Navigation Dock on Mobile (< md) -->
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 px-4 py-1.5 flex items-center justify-around text-[10px] font-medium text-slate-600 shadow-md">
    <a href="{{ route('student.dashboard') }}" 
       class="flex flex-col items-center justify-center min-h-12 min-w-12 px-2 rounded-xl transition-colors {{ request()->routeIs('student.dashboard') && !request('filter') ? 'text-emerald-700 font-bold' : 'hover:text-slate-900' }}">
        <span class="material-symbols-outlined text-[20px]">home</span>
        <span class="mt-0.5">Beranda</span>
    </a>
    <a href="{{ route('student.dashboard', ['filter' => 'ongoing']) }}" 
       class="flex flex-col items-center justify-center min-h-12 min-w-12 px-2 rounded-xl transition-colors {{ request('filter') === 'ongoing' ? 'text-emerald-700 font-bold' : 'hover:text-slate-900' }}">
        <span class="material-symbols-outlined text-[20px]">menu_book</span>
        <span class="mt-0.5">Materi</span>
    </a>
    <a href="{{ route('student.paths') }}" 
       class="flex flex-col items-center justify-center min-h-12 min-w-12 px-2 rounded-xl transition-colors {{ request()->routeIs('student.paths') ? 'text-emerald-700 font-bold' : 'hover:text-slate-900' }}">
        <span class="material-symbols-outlined text-[20px]">edit_note</span>
        <span class="mt-0.5">Catatan</span>
    </a>
    <a href="{{ route('student.analytics') }}" 
       class="flex flex-col items-center justify-center min-h-12 min-w-12 px-2 rounded-xl transition-colors {{ request()->routeIs('student.analytics') ? 'text-emerald-700 font-bold' : 'hover:text-slate-900' }}">
        <span class="material-symbols-outlined text-[20px]">workspace_premium</span>
        <span class="mt-0.5">Sertifikat</span>
    </a>
</nav>
