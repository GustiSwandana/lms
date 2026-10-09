<?php

namespace App\Jobs;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\YouTubeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncYoutubePlaylistJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $playlistUrl,
        public int $courseId,
        public int $userId
    ) {}

    /**
     * Execute the job.
     */
    public function handle(YouTubeService $youTubeService): void
    {
        $course = Course::find($this->courseId);
        if (! $course) {
            Log::warning("SyncYoutubePlaylistJob aborted: Course #{$this->courseId} not found.");

            return;
        }

        $playlistData = $youTubeService->fetchPlaylistItems($this->playlistUrl);
        if (empty($playlistData)) {
            Log::info("SyncYoutubePlaylistJob: No items found or invalid playlist: {$this->playlistUrl}");

            return;
        }

        $currentSort = Lesson::where('course_id', $this->courseId)->max('sort_order') ?? 0;

        foreach ($playlistData as $item) {
            $currentSort++;
            Lesson::create([
                'course_id' => $this->courseId,
                'title' => $item['title'] ?? "Pelajaran #{$currentSort}",
                'video_url' => $item['video_url'] ?? '',
                'duration_seconds' => $item['duration_seconds'] ?? 600,
                'sort_order' => $currentSort,
                'is_free_preview' => $currentSort === 1,
            ]);
        }

        Log::info('SyncYoutubePlaylistJob: Successfully imported '.count($playlistData)." lessons to Course #{$this->courseId}.");
    }
}
