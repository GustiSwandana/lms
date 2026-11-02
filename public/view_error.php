<?php

if (($_GET['k'] ?? '') !== 'check-log-2026') {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

// Find log file
$candidates = [
    __DIR__ . '/../storage/logs/laravel.log',
    __DIR__ . '/../../storage/logs/laravel.log',
    '/home/u299024447/domains/gustiswandana.space/public_html/lms/storage/logs/laravel.log',
    '/home/u299024447/domains/gustiswandana.space/public_html/lms/public/storage/logs/laravel.log',
];

echo "=== CHECKING LOG FILES ===\n";
foreach ($candidates as $cand) {
    if (file_exists($cand)) {
        echo "Found log at: $cand (size: " . filesize($cand) . " bytes)\n";
        echo "=== LAST 15000 BYTES ===\n";
        $content = file_get_contents($cand);
        echo substr($content, -15000) . "\n";
        break;
    }
}

// Clear compiled views
echo "\n=== CLEARING COMPILED BLADE VIEWS ===\n";
$viewDirs = [
    __DIR__ . '/../storage/framework/views',
    __DIR__ . '/../../storage/framework/views',
    '/home/u299024447/domains/gustiswandana.space/public_html/lms/storage/framework/views',
];

foreach ($viewDirs as $vdir) {
    if (is_dir($vdir)) {
        $files = glob($vdir . '/*.php');
        echo "In $vdir: found " . count($files) . " compiled view files.\n";
        foreach ($files as $f) {
            @unlink($f);
        }
        echo "Cleared compiled views in $vdir.\n";
    }
}

// Clear bootstrap cache
echo "\n=== CLEARING BOOTSTRAP CACHE ===\n";
$bootDirs = [
    __DIR__ . '/../bootstrap/cache',
    __DIR__ . '/../../bootstrap/cache',
    '/home/u299024447/domains/gustiswandana.space/public_html/lms/bootstrap/cache',
];

foreach ($bootDirs as $bdir) {
    if (is_dir($bdir)) {
        foreach (['config.php', 'routes-v7.php', 'services.php', 'packages.php'] as $bf) {
            $fpath = $bdir . '/' . $bf;
            if (file_exists($fpath)) {
                @unlink($fpath);
                echo "Removed $fpath\n";
            }
        }
    }
}

echo "\nDone!\n";
