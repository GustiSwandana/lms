<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of course categories.
     */
    public function index(Request $request): View
    {
        $admin = auth()->user();
        $query = Category::withCount('courses');

        if ($request->filled('q')) {
            $search = $request->query('q');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->get();

        $totalCategories = Category::count();
        $usedCategories = Category::has('courses')->count();
        $emptyCategories = $totalCategories - $usedCategories;

        return view('admin.categories.index', [
            'admin' => $admin,
            'categories' => $categories,
            'totalCategories' => $totalCategories,
            'usedCategories' => $usedCategories,
            'emptyCategories' => $emptyCategories,
            'search' => $request->query('q', ''),
        ]);
    }

    /**
     * Store a newly created category in storage.
     * Supports both standard form redirects and JSON responses for quick-add in course modals.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->exists()) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'category' => $category,
                'message' => "Kategori '{$category->name}' berhasil ditambahkan!",
            ], 201);
        }

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil ditambahkan!");
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,'.$category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        $baseSlug = Str::slug($validated['name']);
        $slug = $baseSlug;
        $counter = 1;

        while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        $category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$category->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified category from storage.
     * Protects categories that currently have associated courses.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $coursesCount = $category->courses()->count();

        if ($coursesCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', "Kategori '{$category->name}' tidak dapat dihapus karena masih digunakan oleh {$coursesCount} kursus. Pindahkan atau hapus kursus terlebih dahulu.");
        }

        $categoryName = $category->name;
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', "Kategori '{$categoryName}' berhasil dihapus.");
    }
}
