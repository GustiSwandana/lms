<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Display User Management for Super Admin (Admin Pusat).
     */
    public function index(Request $request): View
    {
        $admin = auth()->user() ?? User::where('role', 'super_admin')->first();

        $query = User::query();

        // Filter by role
        $roleFilter = $request->query('role');
        if ($roleFilter && in_array($roleFilter, ['super_admin', 'school_admin', 'student'])) {
            $query->where('role', $roleFilter);
        }

        // Search by keyword
        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $countSuperAdmin = User::where('role', 'super_admin')->count();
        $countSchoolAdmin = User::where('role', 'school_admin')->count();
        $countStudent = User::where('role', 'student')->count();
        $countActive = User::where('is_active', true)->count();
        $totalUsers = User::count();

        return view('admin.users.index', [
            'admin' => $admin,
            'users' => $users,
            'currentRole' => $roleFilter,
            'search' => $request->query('q', ''),
            'countSuperAdmin' => $countSuperAdmin,
            'countSchoolAdmin' => $countSchoolAdmin,
            'countStudent' => $countStudent,
            'countActive' => $countActive,
            'totalUsers' => $totalUsers,
            'schools' => User::getRegisteredSchools(),
        ]);
    }

    /**
     * Store new School Admin account.
     */
    public function storeSchoolAdmin(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'school_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'avatar_url' => 'nullable|url',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'school_name' => $validated['school_name'],
            'password' => Hash::make($validated['password']),
            'role' => 'school_admin',
            'is_active' => true,
            'avatar_url' => ($validated['avatar_url'] ?? null) ?: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&auto=format&fit=crop&q=80',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Akun Admin Sekolah untuk {$user->name} ({$user->school_name}) berhasil dibuat!",
                'user' => $user,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Akun Admin Sekolah untuk {$user->name} ({$user->school_name}) berhasil dibuat!");
    }

    /**
     * Store new Student account from Admin Panel.
     */
    public function storeStudent(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->input('school_name') === 'other' && $request->filled('custom_school_name')) {
            $request->merge(['school_name' => $request->input('custom_school_name')]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'school_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'avatar_url' => 'nullable|url',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'school_name' => $validated['school_name'],
            'password' => Hash::make($validated['password']),
            'role' => 'student',
            'is_active' => true,
            'avatar_url' => ($validated['avatar_url'] ?? null) ?: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Akun Siswa untuk {$user->name} ({$user->school_name}) berhasil ditambahkan!",
                'user' => $user,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Akun Siswa untuk {$user->name} ({$user->school_name}) berhasil ditambahkan!");
    }

    /**
     * Update existing user.
     */
    public function update(Request $request, User $user): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|max:255|unique:users,email,{$user->id}",
            'role' => 'required|in:super_admin,school_admin,student',
            'school_name' => 'nullable|string|max:255',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'school_name' => $validated['school_name'],
            'is_active' => $validated['is_active'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Data pengguna {$user->name} berhasil diperbarui!",
                'user' => $user,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    /**
     * Toggle active / suspend status of user.
     */
    public function toggleStatus(User $user): RedirectResponse|JsonResponse
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['status' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        $user->is_active = ! $user->is_active;
        $user->save();

        $statusMsg = $user->is_active ? 'diaktifkan kembali' : 'dinonaktifkan/diblokir';

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_active' => $user->is_active,
                'message' => "Akun {$user->name} berhasil {$statusMsg}.",
            ]);
        }

        return back()->with('success', "Akun {$user->name} berhasil {$statusMsg}.");
    }

    /**
     * Delete user account.
     */
    public function destroy(User $user): RedirectResponse|JsonResponse
    {
        if (auth()->id() === $user->id) {
            return back()->withErrors(['delete' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $userName = $user->name;
        $user->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Pengguna {$userName} berhasil dihapus dari sistem.",
            ]);
        }

        return back()->with('success', "Pengguna {$userName} berhasil dihapus dari sistem.");
    }

    /**
     * Reset password for any user account (Super Admin exclusive).
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $newPassword = $request->input('new_password') ?: 'Siswa123!';
        $user->password = Hash::make($newPassword);
        $user->save();

        return back()->with('success', "Kata sandi untuk {$user->name} ({$user->role_label}) berhasil direset menjadi '{$newPassword}'.");
    }

    /**
     * Export all users to CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $query = User::with('school');

        if ($request->filled('role')) {
            $query->where('role', $request->query('role'));
        }

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('school_name', 'like', "%{$search}%");
            });
        }

        $filename = 'data_pengguna_siagabumi_ssk_'.now()->format('Y-m-d').'.csv';

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Nama Lengkap',
                'Email',
                'Peran (Role)',
                'Label Peran',
                'Status Akun',
                'Satuan Sekolah',
                'NPSN',
                'NISN',
                'Nomor Telepon/WA',
                'Alamat Domisili',
                'Waktu Pendaftaran',
            ]);

            $query->chunk(100, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->role_label,
                        $user->is_active ? 'Aktif' : 'Nonaktif',
                        $user->school_name ?? ($user->school?->name ?? 'Pusat BKKBN / Lintas Sekolah'),
                        $user->school?->npsn ?? '-',
                        $user->nisn ?? '-',
                        $user->phone ?? '-',
                        $user->address ?? '-',
                        $user->created_at?->format('Y-m-d H:i:s') ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
