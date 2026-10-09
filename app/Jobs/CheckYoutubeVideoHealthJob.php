<?php

namespace App\Jobs;

use App\Models\Lesson;
use App\Services\YouTubeService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CheckYoutubeVideoHealthJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $videoUrl,
        public ?int $lessonId = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(YouTubeService $youTubeService): void
    {
        $status = $youTubeService->checkVideoStatus($this->videoUrl);

        if ($this->lessonId) {
            $lesson = Lesson::find($this->lessonId);
            if ($lesson && ! ($status['is_available'] ?? true)) {
                Log::warning("Lesson #{$this->lessonId} contains an unavailable YouTube video: {$this->videoUrl}");
            }
        }

        Log::info("CheckYoutubeVideoHealthJob finished for {$this->videoUrl}: ".json_encode($status));
    }
}
