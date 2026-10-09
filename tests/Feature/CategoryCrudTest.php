<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test 1: Super Admin and School Admin can view categories index.
     */
    public function test_admin_can_view_categories_index(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $schoolAdmin = User::where('role', 'school_admin')->first();

        $responseSuper = $this->actingAs($superAdmin)->get(route('admin.categories.index'));
        $responseSuper->assertStatus(200);
        $responseSuper->assertSee('Manajemen Kategori Kursus');

        $responseSchool = $this->actingAs($schoolAdmin)->get(route('admin.categories.index'));
        $responseSchool->assertStatus(200);
        $responseSchool->assertSee('Manajemen Kategori Kursus');
    }

    /**
     * Test 2: Admin can create a category via standard form.
     */
    public function test_admin_can_create_category_via_form(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();

        $response = $this->actingAs($superAdmin)->post(route('admin.categories.store'), [
            'name' => 'Kecerdasan Buatan & AI',
            'description' => 'Materi seputar Machine Learning dan Deep Learning.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Kecerdasan Buatan & AI',
            'slug' => 'kecerdasan-buatan-ai',
            'description' => 'Materi seputar Machine Learning dan Deep Learning.',
        ]);
    }

    /**
     * Test 3: Admin can create category via AJAX (Quick-Add in course modal).
     */
    public function test_admin_can_create_category_via_ajax_quick_add(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();

        $response = $this->actingAs($schoolAdmin)->postJson(route('admin.categories.store'), [
            'name' => 'Internet of Things & Robotika',
            'description' => 'Perangkat keras cerdas dan sensor.',
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'success' => true,
        ]);
        $response->assertJsonStructure([
            'success',
            'category' => ['id', 'name', 'slug'],
            'message',
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Internet of Things & Robotika',
            'slug' => 'internet-of-things-robotika',
        ]);
    }

    /**
     * Test 4: Category name must be unique.
     */
    public function test_category_name_must_be_unique(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $existing = Category::first();

        $response = $this->actingAs($superAdmin)->post(route('admin.categories.store'), [
            'name' => $existing->name,
            'description' => 'Duplikasi nama',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /**
     * Test 5: Admin can update existing category.
     */
    public function test_admin_can_update_category(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $category = Category::create([
            'name' => 'Desain Antarmuka',
            'slug' => 'desain-antarmuka',
            'description' => 'Deskripsi lama',
        ]);

        $response = $this->actingAs($superAdmin)->put(route('admin.categories.update', $category->id), [
            'name' => 'UI/UX Design & Prototyping',
            'description' => 'Deskripsi diperbarui dengan materi Figma.',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'UI/UX Design & Prototyping',
            'slug' => 'uiux-design-prototyping',
            'description' => 'Deskripsi diperbarui dengan materi Figma.',
        ]);
    }

    /**
     * Test 6: Category with active courses cannot be deleted.
     */
    public function test_category_with_courses_cannot_be_deleted(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $categoryWithCourses = Category::has('courses')->first();

        $response = $this->actingAs($superAdmin)->delete(route('admin.categories.destroy', $categoryWithCourses->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('categories', [
            'id' => $categoryWithCourses->id,
        ]);
    }

    /**
     * Test 7: Category without courses can be successfully deleted.
     */
    public function test_category_without_courses_can_be_deleted(): void
    {
        $superAdmin = User::where('role', 'super_admin')->first();
        $emptyCategory = Category::create([
            'name' => 'Kategori Sementara',
            'slug' => 'kategori-sementara',
        ]);

        $response = $this->actingAs($superAdmin)->delete(route('admin.categories.destroy', $emptyCategory->id));

        $response->assertRedirect(route('admin.categories.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', [
            'id' => $emptyCategory->id,
        ]);
    }

    /**
     * Test 8: Newly created category can be immediately used to create a course.
     */
    public function test_newly_created_category_can_be_assigned_to_course(): void
    {
        $schoolAdmin = User::where('role', 'school_admin')->first();

        $cat = Category::create([
            'name' => 'Cybersecurity & Ethical Hacking',
            'slug' => 'cybersecurity-ethical-hacking',
        ]);

        $response = $this->actingAs($schoolAdmin)->post(route('admin.courses.store'), [
            'category_id' => $cat->id,
            'title' => 'Dasar-Dasar Keamanan Siber',
            'description' => 'Belajar dasar ethical hacking dan proteksi server.',
            'instructor_name' => 'Budi Santoso',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $this->assertDatabaseHas('courses', [
            'title' => 'Dasar-Dasar Keamanan Siber',
            'category_id' => $cat->id,
            'school_name' => $schoolAdmin->school_name,
        ]);
    }
}
