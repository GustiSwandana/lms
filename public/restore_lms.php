<?php

// Security verification
if (($_GET['token'] ?? '') !== 'siagabumi-cleanup-2026') {
    http_response_code(403);
    echo "Forbidden";
    exit;
}

$publicDir = __DIR__;
$results = [];

// 1. Delete rogue index.html
$indexHtml = $publicDir . '/index.html';
if (file_exists($indexHtml)) {
    unlink($indexHtml);
    $results[] = "Deleted: $indexHtml";
} else {
    $results[] = "Not found: $indexHtml";
}

// 2. Restore proper Laravel .htaccess
$laravelHtaccess = <<<'HTACCESS'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Handle X-XSRF-Token Header
    RewriteCond %{HTTP:x-xsrf-token} .
    RewriteRule .* - [E=HTTP_X_XSRF_TOKEN:%{HTTP:X-XSRF-Token}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
HTACCESS;

file_put_contents($publicDir . '/.htaccess', $laravelHtaccess);
$results[] = "Restored: .htaccess";

// 3. Delete rogue files from the React Tour & Travel deployment if present
$rogueFiles = [
    $publicDir . '/icons.svg',
    $publicDir . '/favicon.svg',
    $publicDir . '/assets/index-DuGyADZR.js',
    $publicDir . '/assets/index-IZodcj7-.css',
];

foreach ($rogueFiles as $rf) {
    if (file_exists($rf)) {
        unlink($rf);
        $results[] = "Deleted rogue file: $rf";
    }
}

// If assets folder is empty, remove it (Laravel Vite uses public/build/assets)
if (is_dir($publicDir . '/assets')) {
    $remaining = array_diff(scandir($publicDir . '/assets'), ['.', '..']);
    if (empty($remaining)) {
        rmdir($publicDir . '/assets');
        $results[] = "Removed empty rogue directory: assets";
    }
}

// 4. Test Laravel bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\Illuminate\Support\Facades\Artisan::call('config:clear');
\Illuminate\Support\Facades\Artisan::call('view:clear');
\Illuminate\Support\Facades\Artisan::call('cache:clear');
$results[] = "Laravel caches cleared successfully.";

// Self delete
unlink(__FILE__);
$results[] = "Cleanup script self-deleted.";

header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'actions' => $results], JSON_PRETTY_PRINT);
