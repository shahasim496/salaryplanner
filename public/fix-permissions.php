<?php
/**
 * Quick Permission Fixer
 * This script attempts to fix common permission issues
 * WARNING: Only run this if you trust the script - it modifies file permissions
 */

$baseDir = dirname(__DIR__);

echo "<h2>Fixing Permissions</h2>";
echo "<pre>";

$fixes = [
    'database' => 0755,
    'storage' => 0755,
    'storage/framework' => 0755,
    'storage/logs' => 0755,
    'bootstrap/cache' => 0755,
];

$fixed = 0;
$errors = 0;

foreach ($fixes as $dir => $perms) {
    $path = $baseDir . '/' . $dir;
    
    if (!file_exists($path)) {
        if (mkdir($path, $perms, true)) {
            echo "✓ Created: {$dir} with permissions " . substr(sprintf('%o', $perms), -4) . "\n";
            $fixed++;
        } else {
            echo "✗ Failed to create: {$dir}\n";
            $errors++;
        }
    } else {
        if (chmod($path, $perms)) {
            echo "✓ Fixed: {$dir} - set to " . substr(sprintf('%o', $perms), -4) . "\n";
            $fixed++;
        } else {
            echo "✗ Failed to fix: {$dir}\n";
            $errors++;
        }
    }
}

// Also fix database.sqlite if it exists
$dbFile = $baseDir . '/database/database.sqlite';
if (file_exists($dbFile)) {
    if (chmod($dbFile, 0666)) {
        echo "✓ Fixed: database.sqlite - set to 0666\n";
        $fixed++;
    } else {
        echo "✗ Failed to fix: database.sqlite\n";
        $errors++;
    }
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "Fixed: {$fixed} items\n";
if ($errors > 0) {
    echo "Errors: {$errors} items (may need manual fix via FTP)\n";
} else {
    echo "All permissions fixed successfully!\n";
    echo "\nYou can now run: /migrate.php\n";
}

echo "</pre>";

