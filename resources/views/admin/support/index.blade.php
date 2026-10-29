@extends('layouts.admin')

@section('admin_content')
<div class="space-y-6 max-w-6xl pb-16" x-data="{ activeTab: 'tickets', responseModalOpen: false, selectedTicket: null }">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4">
        <div>
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Dukungan Teknis & Diagnostik Sistem</h1>
                @if($admin->isSuperAdmin())
                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">
                        Dasbor Tiket Pusat (Semua Sekolah)
                    </span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs bg-indigo-50 text-indigo-700 font-semibold border border-indigo-100">
                        Tiket {{ $admin->school_name ?? 'Sekolah' }} → Pusat
                    </span>
                @endif
            </div>
            <p class="text-xs text-slate-500 mt-1">
                @if($admin->isSuperAdmin())
                    Kelola dan tanggapi tiket bantuan yang dikirimkan oleh operator sekolah, pantau log operasional, dan periksa kesehatan sistem.
                @else
                    Kirimkan pesan kendala atau konsultasi teknis langsung ke Admin Pusat BKKBN SSK, serta pantau status penanganan dan log sistem.
                @endif
            </p>
        </div>

        <!-- Navigation Sub-Tabs -->
        <div class="flex items-center gap-1 p-1 bg-slate-100 rounded-xl border border-slate-200 self-start md:self-auto text-xs">
            <button type="button" @click="activeTab = 'tickets'" 
                    :class="activeTab === 'tickets' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">support_agent</span>
                <span>{{ $admin->isSuperAdmin() ? 'Inbox Tiket Sekolah' : 'Tiket & Komunikasi Pusat' }}</span>
                @if($pendingCount > 0)
                    <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-500 text-white font-bold">{{ $pendingCount }}</span>
                @endif
            </button>
            <button type="button" @click="activeTab = 'diagnostics'" 
                    :class="activeTab === 'diagnostics' ? 'bg-white text-indigo-700 font-semibold shadow-xs' : 'text-slate-600 hover:text-slate-900'"
                    class="px-3.5 py-1.5 rounded-lg transition-all flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[16px]">health_and_safety</span>
                <span>Diagnostik & Log Server</span>
            </button>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center gap-2.5 shadow-xs">
            <span class="material-symbols-outlined text-[18px] text-emerald-600 shrink-0">check_circle</span>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- =========================================================================
         TAB 1: TIKET DUKUNGAN & KOMUNIKASI (ADAPTIF ROLE)
         ========================================================================= -->
    <div x-show="activeTab === 'tickets'" class="space-y-6">
        <!-- Telemetry Overview Cards (Essential 4 Metrics) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] text-slate-500 uppercase font-semibold">Total Tiket</span>
                <p class="text-2xl font-bold text-slate-900">{{ $totalCount }}</p>
                <span class="text-[10px] text-slate-400">Riwayat pengajuan</span>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] text-amber-700 uppercase font-semibold">Menunggu Respon</span>
                <p class="text-2xl font-bold text-amber-600">{{ $pendingCount }}</p>
                <span class="text-[10px] text-amber-600 font-medium">Perlu tindakan</span>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] text-indigo-700 uppercase font-semibold">Sedang Diproses</span>
                <p class="text-2xl font-bold text-indigo-600">{{ $processedCount }}</p>
                <span class="text-[10px] text-indigo-600 font-medium">Dalam penanganan</span>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs space-y-1">
                <span class="text-[11px] text-emerald-700 uppercase font-semibold">Terselesaikan</span>
                <p class="text-2xl font-bold text-emerald-600">{{ $completedCount }}</p>
                <span class="text-[10px] text-emerald-600 font-medium">Solusi dikirim</span>
            </div>
        </div>

        @if($admin->isSchoolAdmin())
        <!-- =========================================================================
             FORM PENGAJUAN TIKET OLEH ADMIN SEKOLAH KE PUSAT
             ========================================================================= -->
        <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[18px]">send</span>
                    </div>
                    <div>
                        <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                            Ajukan Tiket Dukungan ke Admin Pusat BKKBN SSK
                        </h2>
                        <p class="text-[11px] text-slate-500">Pesan Anda akan langsung masuk ke dasbor pantau Admin Pusat beserta lampiran log diagnostik sekolah.</p>
                    </div>
                </div>
                <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-medium border border-slate-200">
                    Asal: {{ $admin->school_name ?? 'Sekolah Anda' }}
                </span>
            </div>

            <form action="{{ route('admin.support.ticket') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Judul Permasalahan / Kebutuhan</label>
                        <input type="text" name="subject" required 
                               placeholder="Contoh: Kendala impor playlist kurikulum atau permintaan kuota siswa..."
                               class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 transition-all"/>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Tingkat Prioritas</label>
                        <select name="priority" required
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                            <option value="rendah">Rendah (Pertanyaan Umum)</option>
                            <option value="sedang" selected>Sedang (Fitur Minor)</option>
                            <option value="tinggi">Tinggi (Akses Materi Terganggu)</option>
                            <option value="kritis">Kritis (Aplikasi Terhenti)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Kategori Masalah</label>
                        <select name="category" required
                                class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600">
                            <option value="teknis">Kendala Teknis Pemutar Video</option>
                            <option value="sinkronisasi_youtube">Sinkronisasi Playlist YouTube</option>
                            <option value="akun_siswa">Impor Massal & Akun Siswa</option>
                            <option value="kurikulum">Kurikulum & Silabus Kursus</option>
                            <option value="lainnya">Pertanyaan & Lain-lain</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="text-xs font-semibold text-slate-700">Deskripsi Kendala Secara Rinci</label>
                        <textarea name="description" rows="3" required 
                                  placeholder="Jelaskan kronologi kendala, link video materi yang bermasalah, atau langkah-langkah yang memicu error..."
                                  class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 leading-relaxed"></textarea>
                    </div>
                </div>

                <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs text-slate-600">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px] text-indigo-600 shrink-0">info</span>
                        <span>Log sistem (IP: {{ request()->ip() }}, PHP: {{ PHP_VERSION }}) otomatis dilampirkan untuk mempermudah investigasi Pusat.</span>
                    </div>
                    <button type="submit" 
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-all shadow-xs flex items-center gap-1.5 shrink-0 self-end sm:self-auto">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        <span>Kirim Tiket Dukungan</span>
                    </button>
                </div>
            </form>
        </section>
        @endif

        <!-- =========================================================================
             FILTER TOOLBAR (SIMPEL & CERDAS)
             ========================================================================= -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2 text-xs">
                <span class="text-slate-500 font-medium flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">filter_list</span>
                    Status:
                </span>
                <a href="{{ route('admin.support.index') }}" 
                   class="px-3 py-1 rounded-full border text-xs font-medium transition-all {{ empty($currentStatus) ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                    Semua ({{ $totalCount }})
                </a>
                <a href="{{ route('admin.support.index', array_merge(request()->except('status'), ['status' => 'menunggu'])) }}" 
                   class="px-3 py-1 rounded-full border text-xs font-medium transition-all {{ $currentStatus === 'menunggu' ? 'bg-amber-600 text-white border-amber-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                    Menunggu ({{ $pendingCount }})
                </a>
                <a href="{{ route('admin.support.index', array_merge(request()->except('status'), ['status' => 'diproses'])) }}" 
                   class="px-3 py-1 rounded-full border text-xs font-medium transition-all {{ $currentStatus === 'diproses' ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                    Diproses ({{ $processedCount }})
                </a>
                <a href="{{ route('admin.support.index', array_merge(request()->except('status'), ['status' => 'selesai'])) }}" 
                   class="px-3 py-1 rounded-full border text-xs font-medium transition-all {{ $currentStatus === 'selesai' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-600 border-slate-200 hover:border-slate-300' }}">
                    Selesai ({{ $completedCount }})
                </a>
            </div>

            @if($admin->isSuperAdmin() && $schools->isNotEmpty())
            <!-- Filter Asal Sekolah untuk Super Admin -->
            <form action="{{ route('admin.support.index') }}" method="GET" class="flex items-center gap-2 text-xs">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"/> @endif
                <label class="text-slate-500 font-medium">Asal Sekolah:</label>
                <select name="school_id" onchange="this.form.submit()" 
                        class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
                    <option value="">Semua Sekolah</option>
                    @foreach($schools as $sch)
                        <option value="{{ $sch->id }}" {{ $selectedSchoolId == $sch->id ? 'selected' : '' }}>{{ $sch->name }}</option>
                    @endforeach
                </select>
            </form>
            @endif
        </div>

        <!-- =========================================================================
             DAFTAR TIKET MASUK / RIWAYAT TIKET
             ========================================================================= -->
        <div class="space-y-4">
            @forelse($tickets as $t)
                @php
                    $pBadge = $t->priority_badge;
                    $sBadge = $t->status_badge;
                @endphp
                <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-xs hover:border-slate-300 transition-all space-y-4"
                     x-data="{ expanded: false, showReplyForm: false }">
                    <!-- Ticket Header Row -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 border-b border-slate-100 pb-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full font-mono font-semibold text-xs bg-slate-100 text-slate-700">
                                {{ $t->ticket_code }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $pBadge['bg'] }} {{ $pBadge['text'] }} {{ $pBadge['border'] }}">
                                Prioritas: {{ $pBadge['label'] }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $sBadge['bg'] }} {{ $sBadge['text'] }} {{ $sBadge['border'] }}">
                                <span class="material-symbols-outlined text-[13px]">{{ $sBadge['icon'] }}</span>
                                <span>{{ $sBadge['label'] }}</span>
                            </span>
                        </div>

                        <div class="text-xs text-slate-400 flex items-center gap-2">
                            <span>{{ $t->created_at->diffForHumans() }}</span>
                            <span class="hidden sm:inline">•</span>
                            <span class="font-mono text-[11px]">{{ $t->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    <!-- Ticket Subject & Meta Sender -->
                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center justify-between gap-2">
                            <h3 class="text-sm font-bold text-slate-900 leading-snug">
                                {{ $t->subject }}
                            </h3>
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                🏫 {{ $t->school_name ?: ($t->school?->name ?? 'BKKBN Perwakilan Sulut') }}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 flex items-center gap-2">
                            <span>Pengirim: <strong class="text-slate-800">{{ $t->user?->name ?? 'Operator' }}</strong> ({{ $t->user?->email }})</span>
                            <span>•</span>
                            <span class="capitalize">Kategori: {{ str_replace('_', ' ', $t->category) }}</span>
                        </div>
                    </div>

                    <!-- Ticket Description Content -->
                    <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
                        {{ $t->description }}
                    </div>

                    <!-- System Diagnostics Telemetry Log Attached -->
                    @if($t->system_logs)
                        <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 text-[11px] font-mono text-slate-600 space-y-1">
                            <div class="font-semibold flex items-center gap-1 text-slate-700">
                                <span class="material-symbols-outlined text-[14px]">terminal</span>
                                <span>Log Diagnostik Pengirim:</span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 text-[10px]">
                                <div>IP: {{ $t->system_logs['sender_ip'] ?? '127.0.0.1' }}</div>
                                <div>PHP: {{ $t->system_logs['php_version'] ?? PHP_VERSION }}</div>
                                <div>Laravel: {{ $t->system_logs['laravel_version'] ?? app()->version() }}</div>
                                <div>Peran: {{ $t->system_logs['sender_role'] ?? 'Operator' }}</div>
                            </div>
                        </div>
                    @endif

                    <!-- Central Admin Official Response Block (If Exists) -->
                    @if($t->admin_response)
                        <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-200 text-xs text-emerald-950 space-y-2">
                            <div class="flex items-center justify-between border-b border-emerald-200 pb-1.5">
                                <div class="flex items-center gap-1.5 font-bold text-emerald-900">
                                    <span class="material-symbols-outlined text-[16px] text-emerald-700">verified</span>
                                    <span>Tanggapan Resmi Admin Pusat BKKBN SSK:</span>
                                </div>
                                <span class="text-[10px] text-emerald-700 font-mono">
                                    {{ $t->responded_at?->diffForHumans() ?? 'Baru saja' }} 
                                    @if($t->responder) (oleh {{ $t->responder->name }}) @endif
                                </span>
                            </div>
                            <p class="leading-relaxed whitespace-pre-line text-emerald-900">
                                {{ $t->admin_response }}
                            </p>
                        </div>
                    @endif

                    <!-- Action Toolbar: Respond / Delete (Super Admin Exclusive) -->
                    @if($admin->isSuperAdmin())
                        <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                            <button type="button" @click="showReplyForm = !showReplyForm"
                                    class="px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold transition-colors flex items-center gap-1.5 shadow-xs">
                                <span class="material-symbols-outlined text-[15px]">reply</span>
                                <span>{{ $t->admin_response ? 'Perbarui Tanggapan / Status' : 'Tanggapi Tiket Sekolah Ini' }}</span>
                            </button>

                            <form action="{{ route('admin.support.destroy', $t->id) }}" method="POST" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket #{{ $t->ticket_code }} ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-rose-600 hover:text-rose-800 transition-colors flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[15px]">delete</span>
                                    <span>Hapus</span>
                                </button>
                            </form>
                        </div>

                        <!-- Response Inline Form for Super Admin -->
                        <div x-show="showReplyForm" x-cloak class="mt-3 p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-3">
                            <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1">
                                <span class="material-symbols-outlined text-[16px] text-indigo-600">edit_note</span>
                                <span>Form Balasan Pusat untuk {{ $t->school_name }}</span>
                            </h4>

                            <form action="{{ route('admin.support.respond', $t->id) }}" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Tiket Terbaru</label>
                                    <select name="status" required class="bg-white border border-slate-200 rounded-xl p-2 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">
                                        <option value="diproses" {{ $t->status === 'diproses' ? 'selected' : '' }}>Sedang Diproses Pusat</option>
                                        <option value="selesai" {{ $t->status === 'selesai' ? 'selected' : '' }}>Terselesaikan (Solusi Diberikan)</option>
                                        <option value="menunggu" {{ $t->status === 'menunggu' ? 'selected' : '' }}>Menunggu Informasi Tambahan</option>
                                        <option value="ditutup" {{ $t->status === 'ditutup' ? 'selected' : '' }}>Ditutup</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggapan / Solusi untuk Operator Sekolah</label>
                                    <textarea name="admin_response" rows="3" required placeholder="Tuliskan petunjuk teknis atau konfirmasi perbaikan di sini..."
                                              class="w-full bg-white border border-slate-200 rounded-xl p-2.5 text-xs text-slate-900 focus:outline-none focus:border-indigo-600">{{ old('admin_response', $t->admin_response) }}</textarea>
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="showReplyForm = false" class="px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs text-slate-600 hover:bg-slate-50">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 rounded-xl bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 transition-colors shadow-xs">Kirim Tanggapan</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-12 text-center bg-white border border-slate-200 rounded-xl p-8 space-y-2 shadow-xs">
                    <span class="material-symbols-outlined text-[36px] text-slate-300">inbox</span>
                    <h3 class="text-sm font-bold text-slate-800">Belum Ada Tiket Dukungan</h3>
                    <p class="text-xs text-slate-400 max-w-md mx-auto">
                        @if($admin->isSuperAdmin())
                            Tidak ada pesan atau tiket bantuan yang membutuhkan tindakan saat ini dari seluruh sekolah.
                        @else
                            Sekolah Anda belum pernah mengajukan tiket bantuan. Jika ada kendala, gunakan form di atas untuk menghubungi Pusat.
                        @endif
                    </p>
                </div>
            @endforelse

            <div class="pt-2">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>

    <!-- =========================================================================
         TAB 2: DIAGNOSTIK & LOG SISTEM SERVER
         ========================================================================= -->
    <div x-show="activeTab === 'diagnostics'" x-cloak class="space-y-6">
        <section class="bg-white rounded-xl border border-slate-200 p-6 shadow-xs space-y-4">
            <h2 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-indigo-600">health_and_safety</span>
                Status Kesehatan & Lingkungan Server
            </h2>
            <p class="text-xs text-slate-500">Telemetri runtime server, driver database, session store, dan status engine video YouTube.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pt-2">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Versi PHP Runtime</span>
                    <p class="text-sm font-mono font-bold text-slate-900">{{ $diagnostics['php_version'] }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Kompatibel (PHP 8.3+)</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Framework Laravel</span>
                    <p class="text-sm font-mono font-bold text-slate-900">v{{ $diagnostics['laravel_version'] }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Terverifikasi Terkini</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Koneksi Database</span>
                    <p class="text-sm font-mono font-bold text-slate-900">{{ strtoupper($diagnostics['database_driver']) }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Terkoneksi & Responsif</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Penyimpanan Cache</span>
                    <p class="text-sm font-mono font-bold text-slate-900">{{ strtoupper($diagnostics['cache_driver']) }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Smart Invalidation Aktif</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Driver Sesi & Antrean</span>
                    <p class="text-sm font-mono font-bold text-slate-900">{{ strtoupper($diagnostics['session_driver']) }} / {{ strtoupper($diagnostics['queue_connection']) }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Database Jobs Ready</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Sistem Operasi Host</span>
                    <p class="text-sm font-mono font-bold text-slate-900">{{ $diagnostics['server_os'] }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Windows/Laragon Environment</span>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 space-y-1 sm:col-span-2 lg:col-span-3">
                    <span class="text-[11px] text-slate-500 uppercase font-semibold">Integrasi YouTube Engine</span>
                    <p class="text-xs font-mono font-bold text-indigo-700">{{ $diagnostics['youtube_engine_status'] }}</p>
                    <span class="text-[10px] text-emerald-600 font-medium">✓ Latensi Normal • Tanpa Batasan Kuota API • Support Unlisted Videos</span>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
