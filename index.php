<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure .env exists by inheriting from parent if missing
if (!file_exists(__DIR__.'/.env') && file_exists(__DIR__.'/../.env')) {
    @copy(__DIR__.'/../.env', __DIR__.'/.env');
}

// Maintenance / deploy cache purge trigger
if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'purge_views')) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (array_merge(glob(__DIR__.'/storage/framework/views/*.php') ?: [], glob(__DIR__.'/../storage/framework/views/*.php') ?: []) as $f) {
        @unlink($f);
    }
    foreach (array_merge(glob(__DIR__.'/bootstrap/cache/*.php') ?: [], glob(__DIR__.'/../bootstrap/cache/*.php') ?: []) as $f) {
        @unlink($f);
    }
    echo "PURGED_SUCCESS";
    exit;
}

// Production MySQL Database Switch Handler
if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'sync_production_db')) {
    header('Content-Type: application/json');
    $status = [];
    $bestHost = '127.0.0.1';
    foreach (['127.0.0.1', 'localhost', 'srv1762.hstgr.io'] as $host) {
        try {
            $testPdo = new PDO("mysql:host=$host;port=3306;dbname=u299024447_lms", "u299024447_lmsuser", "SiagaBumi_LMS_2026!");
            $count = $testPdo->query("SELECT count(*) FROM courses")->fetchColumn();
            $status[$host] = "CONNECTED ($count courses)";
            $bestHost = $host;
            break;
        } catch (\Throwable $e) {
            $status[$host] = $e->getMessage();
        }
    }

    $envPaths = [__DIR__.'/.env', __DIR__.'/../.env', __DIR__.'/../../.env'];
    $updated = [];
    foreach ($envPaths as $p) {
        if (file_exists($p)) {
            $c = file_get_contents($p);
            $c = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=mysql', $c);
            $c = preg_replace('/^DB_HOST=.*$/m', "DB_HOST=$bestHost", $c);
            $c = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT=3306', $c);
            $c = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=u299024447_lms', $c);
            $c = preg_replace('/^DB_USERNAME=.*$/m', 'DB_USERNAME=u299024447_lmsuser', $c);
            $c = preg_replace('/^DB_PASSWORD=.*$/m', 'DB_PASSWORD=SiagaBumi_LMS_2026!', $c);
            file_put_contents($p, $c);
            $updated[] = $p;
        }
    }

    foreach (array_merge(glob(__DIR__.'/bootstrap/cache/*.php') ?: [], glob(__DIR__.'/../bootstrap/cache/*.php') ?: []) as $f) {
        @unlink($f);
    }

    echo json_encode(['db_status' => $status, 'best_host' => $bestHost, 'updated_env_files' => $updated]);
    exit;
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
} elseif (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
} elseif (file_exists(__DIR__.'/../vendor/autoload.php')) {
    require __DIR__.'/../vendor/autoload.php';
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Autoloader vendor/autoload.php not found.';
    exit(1);
}

// Bootstrap Laravel and handle the request...
/** @var Application $app */
if (file_exists(__DIR__.'/bootstrap/app.php')) {
    $app = require_once __DIR__.'/bootstrap/app.php';
} elseif (file_exists(__DIR__.'/../bootstrap/app.php')) {
    $app = require_once __DIR__.'/../bootstrap/app.php';
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo 'Bootstrap file bootstrap/app.php not found.';
    exit(1);
}

$app->handleRequest(Request::capture());
