<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolController extends Controller
{
    /**
     * Display a listing of schools (Super Admin exclusive).
     */
    public function index(Request $request): View
    {
        $admin = auth()->user();
        $query = School::withCount(['users', 'students', 'operators', 'courses']);

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $schools = $query->latest()->get();

        $totalSchools = School::count();
        $activeSchools = School::where('is_active', true)->count();
        $totalStudents = School::withCount('students')->get()->sum('students_count');

        return view('admin.schools.index', [
            'admin' => $admin,
            'schools' => $schools,
            'totalSchools' => $totalSchools,
            'activeSchools' => $activeSchools,
            'totalStudents' => $totalStudents,
            'search' => $request->query('q', ''),
        ]);
    }

    /**
     * Store a newly created school in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name',
            'npsn' => 'nullable|string|max:50|unique:schools,npsn',
            'address' => 'nullable|string|max:500',
            'logo_url' => 'nullable|url|max:255',
        ]);

        $validated['is_active'] = true;

        $school = School::create($validated);

        return redirect()->route('admin.schools.index')
            ->with('success', "Master instansi '{$school->name}' berhasil ditambahkan!");
    }

    /**
     * Update the specified school in storage.
     */
    public function update(Request $request, School $school): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:schools,name,'.$school->id,
            'npsn' => 'nullable|string|max:50|unique:schools,npsn,'.$school->id,
            'address' => 'nullable|string|max:500',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'required|boolean',
        ]);

        $oldName = $school->name;
        $school->update($validated);

        // Synchronize school_name string on users & courses if name changed
        if ($oldName !== $school->name) {
            $school->users()->update(['school_name' => $school->name]);
            $school->courses()->update(['school_name' => $school->name]);
        }

        return redirect()->route('admin.schools.index')
            ->with('success', "Data instansi '{$school->name}' berhasil diperbarui!");
    }

    /**
     * Toggle school active status.
     */
    public function toggleStatus(School $school): RedirectResponse
    {
        $school->is_active = ! $school->is_active;
        $school->save();

        $status = $school->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.schools.index')
            ->with('success', "Instansi '{$school->name}' berhasil {$status}.");
    }

    /**
     * Remove the specified school from storage.
     */
    public function destroy(School $school): RedirectResponse
    {
        $usersCount = $school->users()->count();
        $coursesCount = $school->courses()->count();

        if ($usersCount > 0 || $coursesCount > 0) {
            return redirect()->route('admin.schools.index')
                ->with('error', "Instansi '{$school->name}' tidak dapat dihapus karena masih menaungi {$usersCount} pengguna dan {$coursesCount} kursus.");
        }

        $schoolName = $school->name;
        $school->delete();

        return redirect()->route('admin.schools.index')
            ->with('success', "Instansi '{$schoolName}' berhasil dihapus.");
    }
}
