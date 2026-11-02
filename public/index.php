<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure .env exists by inheriting from parent if missing
if (!file_exists(__DIR__.'/../.env') && file_exists(__DIR__.'/../../.env')) {
    @copy(__DIR__.'/../../.env', __DIR__.'/../.env');
}

// Maintenance / deploy cache purge trigger
if (isset($_GET['purge_views'])) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (array_merge(glob(__DIR__.'/../storage/framework/views/*.php') ?: [], glob(__DIR__.'/../../storage/framework/views/*.php') ?: []) as $f) {
        @unlink($f);
    }
    foreach (array_merge(glob(__DIR__.'/../bootstrap/cache/*.php') ?: [], glob(__DIR__.'/../../bootstrap/cache/*.php') ?: []) as $f) {
        @unlink($f);
    }
    echo "PURGED_SUCCESS";
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../../vendor/autoload.php')) {
    require __DIR__.'/../../vendor/autoload.php';
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Autoloader vendor/autoload.php not found.';
    exit(1);
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
if (file_exists(__DIR__.'/../bootstrap/app.php')) {
    $app = require_once __DIR__.'/../bootstrap/app.php';
} elseif (file_exists(__DIR__.'/../../bootstrap/app.php')) {
    $app = require_once __DIR__.'/../../bootstrap/app.php';
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Bootstrap file bootstrap/app.php not found.';
    exit(1);
}

$app->handleRequest(Request::capture());
