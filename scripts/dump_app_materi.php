<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\School;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$schools = School::all(['id', 'name', 'npsn', 'slug', 'address'])->toArray();
$categories = Category::all(['id', 'name', 'slug', 'description'])->toArray();
$courses = Course::with(['category', 'lessons.quizzes'])->get();

$data = [
    'schools' => $schools,
    'categories' => $categories,
    'courses' => [],
];

foreach ($courses as $c) {
    $cData = [
        'id' => $c->id,
        'title' => $c->title,
        'slug' => $c->slug,
        'school_name' => $c->school_name,
        'instructor_name' => $c->instructor_name,
        'category' => $c->category?->name,
        'level' => $c->level,
        'is_visible_to_guest' => (bool) $c->is_visible_to_guest,
        'lessons' => [],
    ];
    foreach ($c->lessons as $l) {
        $cData['lessons'][] = [
            'id' => $l->id,
            'module_title' => $l->module_title,
            'title' => $l->title,
            'duration_formatted' => $l->duration_formatted,
            'duration_seconds' => $l->duration_seconds,
            'youtube_url' => $l->youtube_url,
            'youtube_video_id' => $l->youtube_video_id,
            'description' => $l->description,
            'quizzes_count' => $l->quizzes->count(),
            'sample_quiz' => $l->quizzes->first() ? [
                'question' => $l->quizzes->first()->question,
                'options' => $l->quizzes->first()->options,
                'correct' => $l->quizzes->first()->correct_answer_index,
                'explanation' => $l->quizzes->first()->explanation,
            ] : null,
        ];
    }
    $data['courses'][] = $cData;
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
