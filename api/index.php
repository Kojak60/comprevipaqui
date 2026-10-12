<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Vercel serverless: force writable storage / cache paths to /tmp
$tmpDirs = ['framework/cache', 'framework/sessions', 'framework/views', 'logs', 'app/public'];
foreach ($tmpDirs as $d) {
    $p = '/tmp/storage_' . str_replace('/', '_', $d);
    if (!is_dir($p)) {
        @mkdir($p, 0777, true);
    }
}

// Maintenance mode check (core path)
if (file_exists($maintenance = __DIR__ . '/../core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Composer autoloader (core is one level above api/)
require __DIR__ . '/../core/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__ . '/../core/bootstrap/app.php')
    ->handleRequest(Request::capture());
