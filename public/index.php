<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

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

try {
    $response = $app->handleRequest(Request::capture());
    $response->send();
} catch (\Throwable $e) {
    header('HTTP/1.1 500 Internal Server Error');
    header('Content-Type: text/plain; charset=utf-8');
    echo "SIAGA_BUMI_EXCEPTION:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString();
}
