<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class YouTubeService
{
    /**
     * Regex pattern to capture YouTube video IDs (11 chars) from multiple URL variations.
     */
    protected const YOUTUBE_REGEX = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=|shorts/)|youtu\.be/)([^"&?/\s]{11})%i';

    /**
     * Regex pattern to capture YouTube playlist IDs.
     */
    protected const PLAYLIST_REGEX = '/[?&]list=([a-zA-Z0-9_-]+)/i';

    /**
     * Extract playlist ID from URL.
     */
    public static function extractPlaylistId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (preg_match(self::PLAYLIST_REGEX, trim($url), $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Generate structured batch lesson records for a simulated playlist import.
     *
     * @return array<int, array{title: string, youtube_url: string, youtube_video_id: string, duration_seconds: int, duration_formatted: string, description: string}>
     */
    public static function getPlaylistLessonsMock(string $playlistId): array
    {
        return [
            [
                'title' => 'Pengenalan & Penyiapan Proyek (Episode 1)',
                'youtube_url' => 'https://www.youtube.com/watch?v=wm5gMKuwSYk',
                'youtube_video_id' => 'wm5gMKuwSYk',
                'duration_seconds' => 720,
                'duration_formatted' => '12:00',
                'description' => 'Memulai inisialisasi lingkungan kerja, dependensi paket, dan struktur berkas awal.',
            ],
            [
                'title' => 'Arsitektur Komponen & Alur Data (Episode 2)',
                'youtube_url' => 'https://www.youtube.com/watch?v=bMknfKXIFA8',
                'youtube_video_id' => 'bMknfKXIFA8',
                'duration_seconds' => 1020,
                'duration_formatted' => '17:00',
                'description' => 'Membangun komponen reusable dan mengelola state aplikasi secara efisien.',
            ],
            [
                'title' => 'Integrasi API & Penanganan Asinkron (Episode 3)',
                'youtube_url' => 'https://www.youtube.com/watch?v=fq7pMv4bNq0',
                'youtube_video_id' => 'fq7pMv4bNq0',
                'duration_seconds' => 1350,
                'duration_formatted' => '22:30',
                'description' => 'Menghubungkan klien dengan endpoint backend serta teknik caching data.',
            ],
            [
                'title' => 'Pengujian, Optimasi & Produksi (Episode 4)',
                'youtube_url' => 'https://www.youtube.com/watch?v=zgT35aY2s_A',
                'youtube_video_id' => 'zgT35aY2s_A',
                'duration_seconds' => 1140,
                'duration_formatted' => '19:00',
                'description' => 'Audit performa Lighthouse, optimasi bundle, dan persiapan deployment.',
            ],
        ];
    }

    /**
     * Extract the 11-character YouTube video ID from a URL or raw ID.
     */
    public static function extractVideoId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $url = trim($url);

        // If user already pasted just the 11-character ID
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return $url;
        }

        if (preg_match(self::YOUTUBE_REGEX, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get the YouTube thumbnail URL (maxresdefault or hqdefault).
     */
    public static function getThumbnailUrl(?string $videoId, string $quality = 'maxresdefault'): string
    {
        if (empty($videoId)) {
            return asset('images/default-thumbnail.jpg');
        }

        return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
    }

    /**
     * Get high quality thumbnail with hqdefault fallback.
     */
    public static function getHqThumbnailUrl(?string $videoId): string
    {
        return self::getThumbnailUrl($videoId, 'hqdefault');
    }

    /**
     * Get embeddable URL for YouTube iframe.
     */
    public static function getEmbedUrl(?string $videoId, bool $autoplay = false): string
    {
        if (empty($videoId)) {
            return '';
        }

        $params = [
            'rel' => 0,
            'modestbranding' => 1,
            'enablejsapi' => 1,
            'autoplay' => $autoplay ? 1 : 0,
        ];

        return "https://www.youtube-nocookie.com/embed/{$videoId}?".http_build_query($params);
    }

    /**
     * Format duration seconds to readable string (e.g., "18:42" or "1:15:30").
     */
    public static function formatDuration(int $seconds): string
    {
        if ($seconds <= 0) {
            return '00:00';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }

        return sprintf('%02d:%02d', $minutes, $secs);
    }

    /**
     * Fetch public oEmbed data for YouTube URL without needing an API key.
     *
     * @return array{
     *     video_id: string|null,
     *     title: string|null,
     *     author_name: string|null,
     *     thumbnail_url: string|null,
     *     html: string|null
     * }
     */
    public static function fetchMetadata(string $url): array
    {
        $videoId = self::extractVideoId($url);

        $defaultData = [
            'video_id' => $videoId,
            'title' => null,
            'author_name' => 'EduTube Official',
            'thumbnail_url' => $videoId ? self::getThumbnailUrl($videoId) : null,
            'html' => null,
        ];

        if (! $videoId) {
            return $defaultData;
        }

        try {
            $oembedUrl = 'https://www.youtube.com/oembed?url='.urlencode("https://www.youtube.com/watch?v={$videoId}").'&format=json';
            $response = Http::timeout(3)->get($oembedUrl);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'video_id' => $videoId,
                    'title' => $data['title'] ?? null,
                    'author_name' => $data['author_name'] ?? 'EduTube Official',
                    'thumbnail_url' => $data['thumbnail_url'] ?? self::getThumbnailUrl($videoId),
                    'html' => $data['html'] ?? null,
                ];
            }
            // Silently fall back to default metadata if offline or timeout
        } catch (Throwable $e) {
        }

        return $defaultData;
    }

    /**
     * Check if a YouTube video is alive, public, or private/deleted via oEmbed.
     *
     * @return array{
     *     healthy: bool,
     *     status: string,
     *     video_id: string|null,
     *     title?: string,
     *     author?: string,
     *     message: string
     * }
     */
    public static function checkHealth(?string $urlOrVideoId): array
    {
        $videoId = self::extractVideoId($urlOrVideoId);
        if (! $videoId) {
            return [
                'healthy' => false,
                'status' => 'invalid_url',
                'video_id' => null,
                'message' => 'Format URL atau ID YouTube tidak valid.',
            ];
        }

        try {
            $oembedUrl = 'https://www.youtube.com/oembed?url='.urlencode("https://www.youtube.com/watch?v={$videoId}").'&format=json';
            $response = Http::timeout(4)->get($oembedUrl);

            if ($response->successful()) {
                return [
                    'healthy' => true,
                    'status' => 'available',
                    'video_id' => $videoId,
                    'title' => $response->json('title'),
                    'author' => $response->json('author_name'),
                    'message' => 'Video aktif dan dapat diputar secara publik.',
                ];
            }

            if (in_array($response->status(), [404, 401, 403], true)) {
                return [
                    'healthy' => false,
                    'status' => 'unavailable',
                    'video_id' => $videoId,
                    'message' => 'Video telah dihapus atau diset Privat oleh pemilik channel.',
                ];
            }
        } catch (Throwable $e) {
            // Network fallback
        }

        return [
            'healthy' => true,
            'status' => 'unknown',
            'video_id' => $videoId,
            'message' => 'Pemeriksaan status sementara dilewati (koneksi offline/timeout).',
        ];
    }
}
