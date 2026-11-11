<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Auto-provision .env on deployment if missing
$envPath = __DIR__.'/../.env';
if (! file_exists($envPath)) {
    if (file_exists(__DIR__.'/../../.env')) {
        @copy(__DIR__.'/../../.env', $envPath);
    } else {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $isSiaga = str_contains($host, 'siagabumi');
        $dbName = $isSiaga ? 'u109219415_lms' : 'u299024447_lms';
        $dbUser = $isSiaga ? 'u109219415_lms' : 'u299024447_lmsuser';
        $dbPass = $isSiaga ? 'SiagaBumi_2026!Db#Kependudukan' : 'SiagaBumi_LMS_2026!';
        $appUrl = $isSiaga ? 'https://siagabumissk.com' : 'https://demo.gustiswandana.space';

        $defaultEnv = <<<ENV
APP_NAME="SIAGA BUMI-SSK"
APP_ENV=production
APP_KEY=base64:u87i0IMtka4nYRXc2YbI2FLwa372fS+UlPzRhZieWVQ=
APP_DEBUG=false
APP_URL={$appUrl}

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE={$dbName}
DB_USERNAME={$dbUser}
DB_PASSWORD="{$dbPass}"

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
ENV;
        @file_put_contents($envPath, $defaultEnv);
    }
}

// Maintenance / deploy cache purge trigger
if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'purge_views')) {
    if (function_exists('opcache_reset')) {
        @opcache_reset();
    }
    foreach (array_merge(glob(__DIR__.'/../storage/framework/views/*.php') ?: [], glob(__DIR__.'/../../storage/framework/views/*.php') ?: []) as $f) {
        @unlink($f);
    }
    foreach (array_merge(glob(__DIR__.'/../bootstrap/cache/*.php') ?: [], glob(__DIR__.'/../../bootstrap/cache/*.php') ?: []) as $f) {
        @unlink($f);
    }
    echo 'PURGED_SUCCESS';
    exit;
}

// Production MySQL Database Switch / Diagnostic Handler
if (str_contains($_SERVER['REQUEST_URI'] ?? '', 'sync_production_db')) {
    header('Content-Type: application/json');
    $host = $_SERVER['HTTP_HOST'] ?? '';
    $isSiaga = str_contains($host, 'siagabumi');
    $dbName = $isSiaga ? 'u109219415_lms' : 'u299024447_lms';
    $dbUser = $isSiaga ? 'u109219415_lms' : 'u299024447_lmsuser';
    $dbPass = $isSiaga ? 'SiagaBumi_2026!Db#Kependudukan' : 'SiagaBumi_LMS_2026!';
    $appUrl = $isSiaga ? 'https://siagabumissk.com' : 'https://demo.gustiswandana.space';

    $candidateHosts = ['127.0.0.1', 'localhost', $isSiaga ? 'srv1155.hstgr.io' : 'srv1762.hstgr.io'];
    $status = [];
    $bestHost = '127.0.0.1';
    foreach ($candidateHosts as $h) {
        try {
            $testPdo = new PDO("mysql:host=$h;port=3306;dbname=$dbName", $dbUser, $dbPass, [
                PDO::ATTR_TIMEOUT => 3,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $count = $testPdo->query('SELECT count(*) FROM courses')->fetchColumn();
            $status[$h] = "CONNECTED ($count courses)";
            $bestHost = $h;
            break;
        } catch (Throwable $e) {
            $status[$h] = $e->getMessage();
        }
    }

    $envPaths = [__DIR__.'/.env', __DIR__.'/../.env', __DIR__.'/../../.env'];
    $updated = [];
    foreach ($envPaths as $p) {
        $c = file_exists($p) ? file_get_contents($p) : '';
        if (empty($c)) {
            $c = "APP_NAME=\"SIAGA BUMI-SSK\"\nAPP_ENV=production\nAPP_KEY=base64:u87i0IMtka4nYRXc2YbI2FLwa372fS+UlPzRhZieWVQ=\nAPP_DEBUG=false\nAPP_URL=$appUrl\nDB_CONNECTION=mysql\nDB_HOST=$bestHost\nDB_PORT=3306\nDB_DATABASE=$dbName\nDB_USERNAME=$dbUser\nDB_PASSWORD=\"$dbPass\"\nSESSION_DRIVER=database\nCACHE_STORE=database\nQUEUE_CONNECTION=database\n";
        } else {
            $c = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=mysql', $c);
            $c = preg_replace('/^DB_HOST=.*$/m', "DB_HOST=$bestHost", $c);
            $c = preg_replace('/^DB_PORT=.*$/m', 'DB_PORT=3306', $c);
            $c = preg_replace('/^DB_DATABASE=.*$/m', "DB_DATABASE=$dbName", $c);
            $c = preg_replace('/^DB_USERNAME=.*$/m', "DB_USERNAME=$dbUser", $c);
            $c = preg_replace('/^DB_PASSWORD=.*$/m', "DB_PASSWORD=\"$dbPass\"", $c);
            $c = preg_replace('/^APP_URL=.*$/m', "APP_URL=$appUrl", $c);
        }
        file_put_contents($p, $c);
        $updated[] = $p;
    }

    foreach (array_merge(glob(__DIR__.'/../bootstrap/cache/*.php') ?: [], glob(__DIR__.'/../../bootstrap/cache/*.php') ?: []) as $f) {
        @unlink($f);
    }

    echo json_encode(['db_status' => $status, 'best_host' => $bestHost, 'updated_env_files' => $updated]);
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
