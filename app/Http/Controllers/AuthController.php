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
     * Quick role switcher for testing and demonstration.
     */
    public function switchRole(Request $request, string $role): RedirectResponse
    {
        if (! app()->environment('local', 'testing') && ! config('app.debug')) {
            abort(403, 'Aksi peralihan peran demo dinonaktifkan pada lingkungan produksi.');
        }

        $targetRole = match ($role) {
            'super_admin', 'superadmin', 'admin_pusat' => 'super_admin',
            'school_admin', 'schooladmin', 'admin_sekolah' => 'school_admin',
            default => 'student',
        };

        // Find existing user or seed a demo user
        $user = User::where('role', $targetRole)->first();

        if (! $user) {
            if ($targetRole === 'super_admin') {
                $user = User::create([
                    'name' => 'Alex Vance, PhD',
                    'email' => 'superadmin@edutube.org',
                    'password' => Hash::make('password'),
                    'role' => 'super_admin',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&auto=format&fit=crop&q=80',
                ]);
            } elseif ($targetRole === 'school_admin') {
                $user = User::create([
                    'name' => 'Bambang S., S.Kom',
                    'email' => 'smkn1@edutube.org',
                    'password' => Hash::make('password'),
                    'role' => 'school_admin',
                    'school_name' => 'SMKN 1 Jakarta',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
                ]);
            } else {
                $user = User::create([
                    'name' => 'Sarah Connor',
                    'email' => 'student@edutube.org',
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'school_name' => 'SMKN 1 Jakarta',
                    'is_active' => true,
                    'avatar_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80',
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
