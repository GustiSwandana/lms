<?php

if (($_GET['token'] ?? '') !== 'siagabumi-sync-2026') {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$results = [];

// 1. Git pull parent directory if possible
$parentDir = realpath(__DIR__ . '/..');
$results['parent_dir'] = $parentDir;

if ($parentDir && is_dir($parentDir . '/.git')) {
    $output = [];
    $retval = 0;
    exec("cd " . escapeshellarg($parentDir) . " && git fetch origin main && git reset --hard origin/main 2>&1", $output, $retval);
    $results['git_pull'] = [
        'exit_code' => $retval,
        'output' => $output
    ];
} else {
    $results['git_pull'] = 'No .git directory found in parent';
}

// 2. Clear Laravel caches
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();

    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    $results['laravel_cache'] = 'Successfully cleared config, view, and app cache';
} catch (\Throwable $e) {
    $results['laravel_cache_error'] = [
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ];
}

// 3. Get last 30 lines of laravel.log
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $results['recent_logs'] = array_slice($lines, -30);
} else {
    $results['recent_logs'] = 'No log file found';
}

// 4. Ensure .htaccess has DirectoryIndex
$htaccessPath = __DIR__ . '/.htaccess';
if (file_exists($htaccessPath)) {
    $htaccess = file_get_contents($htaccessPath);
    if (!str_contains($htaccess, 'DirectoryIndex')) {
        $htaccess = "DirectoryIndex index.php index.html\n\n" . $htaccess;
        file_put_contents($htaccessPath, $htaccess);
        $results['htaccess'] = 'Added DirectoryIndex to .htaccess';
    } else {
        $results['htaccess'] = 'DirectoryIndex already present in .htaccess';
    }
}

// Self delete
@unlink(__FILE__);
$results['self_deleted'] = true;

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
