<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('student.dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle user login.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            if (! $user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda dinonaktifkan oleh Administrator Pusat. Silakan hubungi pihak sekolah/pusat.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'))->with('success', "Selamat datang kembali, {$user->name} ({$user->role_label})!");
            }

            return redirect()->intended(route('student.dashboard'))->with('success', "Selamat datang kembali, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan kata sandi tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari akun.');
    }

    /**
     * Show user registration form.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('student.dashboard');
        }

        return view('auth.register');
    }

    /**
     * Handle user self-registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'required|string|max:25',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'address.required' => 'Alamat lengkap tempat tinggal wajib diisi.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah terdaftar.',
            'phone.required' => 'Nomor HP aktif wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role' => 'siswa',
            'is_active' => true,
            'avatar_url' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')
            ->with('success', "Selamat datang di SIAGA BUMI-SSK, {$user->name}! Akun Siswa Anda berhasil didaftarkan.");
    }

    /**
     * Quick role switcher for testing and demonstration.
     */
    public function switchRole(Request $request, string $role): RedirectResponse
    {
        if (! app()->environment('local', 'testing') && ! config('app.debug') && ! str_contains(request()->getHost(), 'demo')) {
            abort(403, 'Aksi peralihan peran demo dinonaktifkan pada lingkungan produksi.');
        }

        $targetRole = match ($role) {
            'super_admin', 'superadmin', 'admin_pusat' => 'admin_pusat',
            'school_admin', 'schooladmin', 'operator_sekolah', 'admin_sekolah' => 'operator_sekolah',
            'guru', 'teacher' => 'guru',
            'penyuluh', 'guest' => 'penyuluh',
            default => 'siswa',
        };

        // Find existing user or fallback to compatibility role
        $user = User::where('role', $targetRole)->first();

        if (! $user) {
            if ($targetRole === 'admin_pusat') {
                $user = User::where('role', 'super_admin')->first();
            } elseif ($targetRole === 'operator_sekolah') {
                $user = User::where('role', 'school_admin')->first();
            } elseif ($targetRole === 'siswa') {
                $user = User::where('role', 'student')->first();
            }
        }

        if (! $user) {
            if ($targetRole === 'admin_pusat') {
                $user = User::create([
                    'name' => 'Dr. Hendra Sompotan, M.Si',
                    'email' => 'adminpusat@siagabumi.id',
                    'password' => Hash::make('password'),
                    'role' => 'admin_pusat',
                    'address' => 'Jl. 17 Agustus No. 69, Manado',
                    'phone' => '08114321001',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                ]);
            } elseif ($targetRole === 'operator_sekolah') {
                $user = User::create([
                    'name' => 'Meivy Karundeng, S.Kom',
                    'email' => 'operator.bitung@siagabumi.id',
                    'password' => Hash::make('password'),
                    'role' => 'operator_sekolah',
                    'school_name' => 'SMP Negeri 1 Bitung',
                    'address' => 'Jl. Sam Ratulangi No. 12, Bitung',
                    'phone' => '081244001122',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                ]);
            } elseif ($targetRole === 'guru') {
                $user = User::create([
                    'name' => 'Ibu Martha Runtuwene, S.Pd',
                    'email' => 'guru.minahasa@siagabumi.id',
                    'password' => Hash::make('password'),
                    'role' => 'guru',
                    'school_name' => 'Sekolah Rakyat Menengah Atas 44 Minahasa',
                    'address' => 'Jl. Raya Tanawangko No. 44, Minahasa',
                    'phone' => '081355667788',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=150&auto=format&fit=crop&q=80',
                ]);
            } elseif ($targetRole === 'penyuluh') {
                $user = User::create([
                    'name' => 'Drs. Ronald Lumempouw',
                    'email' => 'penyuluh@siagabumi.id',
                    'password' => Hash::make('password'),
                    'role' => 'penyuluh',
                    'address' => 'Wenang, Kota Manado',
                    'phone' => '08114300998',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&auto=format&fit=crop&q=80',
                ]);
            } else {
                $user = User::create([
                    'name' => 'Reza Mokodongan',
                    'email' => 'siswa.bitung@siagabumi.id',
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                    'school_name' => 'SMP Negeri 1 Bitung',
                    'address' => 'Jl. Girian Indah No. 5, Bitung',
                    'phone' => '085299887766',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&auto=format&fit=crop&q=80',
                ]);
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', "Beralih peran aktif: {$user->name} ({$user->role_label})");
        }

        return redirect()->route('student.dashboard')->with('success', "Beralih peran aktif: {$user->name} ({$user->role_label})");
    }

    /**
     * Show forgot password view.
     */
    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle forgot password submission.
     */
    public function submitForgotPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Alamat email tersebut tidak terdaftar di sistem kami.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Self-service reset password message
        return redirect()->route('login')->with('success', "Permintaan pemulihan untuk akun {$user->name} telah dicatat. Silakan hubungi Operator Sekolah Anda untuk mereset kata sandi atau gunakan layanan bantuan.");
    }

    /**
     * Update current authenticated user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Kata sandi Anda berhasil diperbarui!');
    }
}
