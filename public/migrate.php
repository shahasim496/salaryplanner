<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

require __DIR__.'/../vendor/autoload.php';

// Set production environment BEFORE bootstrapping Laravel
// This prevents loading dev dependencies like Pail
putenv('APP_ENV=production');
$_ENV['APP_ENV'] = 'production';
$_SERVER['APP_ENV'] = 'production';

// Clear package discovery cache files that might have dev dependencies registered
$cacheFiles = [
    __DIR__.'/../bootstrap/cache/packages.php',
    __DIR__.'/../bootstrap/cache/services.php',
];
foreach ($cacheFiles as $cacheFile) {
    if (file_exists($cacheFile)) {
        @unlink($cacheFile);
    }
}

// Ensure database directory exists BEFORE bootstrapping Laravel
$dbDir = __DIR__.'/../database';
if (!is_dir($dbDir)) {
    mkdir($dbDir, 0755, true);
}

// Get absolute database path - resolve the directory first
$resolvedDir = realpath($dbDir);
if ($resolvedDir === false) {
    // If directory doesn't exist yet, get absolute path of parent and append database
    $resolvedDir = realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'database';
}
$absoluteDbPath = $resolvedDir . DIRECTORY_SEPARATOR . 'database.sqlite';

// Set environment variable BEFORE bootstrapping Laravel
// This ensures Laravel reads the correct path from the start
putenv('DB_DATABASE=' . $absoluteDbPath);
$_ENV['DB_DATABASE'] = $absoluteDbPath;
$_SERVER['DB_DATABASE'] = $absoluteDbPath;

// Create database file if it doesn't exist
if (!file_exists($absoluteDbPath)) {
    touch($absoluteDbPath);
    chmod($absoluteDbPath, 0666);
}

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Force update config and reconnect database with absolute path
config(['database.connections.sqlite.database' => $absoluteDbPath]);
DB::purge('sqlite');
DB::reconnect('sqlite');

// Run migrate:fresh --seed
Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
echo Artisan::output();
echo "<br><br>Migration and seeding completed!";

