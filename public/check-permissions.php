<?php
/**
 * Permission Checker for Salary Planner
 * Run this script to check if all required directories have proper permissions
 */

$baseDir = dirname(__DIR__);
$checks = [
    'database' => [
        'path' => $baseDir . '/database',
        'required' => true,
        'writable' => true,
        'permission' => '755 or 777'
    ],
    'database.sqlite' => [
        'path' => $baseDir . '/database/database.sqlite',
        'required' => false, // Will be created if doesn't exist
        'writable' => true,
        'permission' => '666 or 777'
    ],
    'storage' => [
        'path' => $baseDir . '/storage',
        'required' => true,
        'writable' => true,
        'permission' => '755 or 777'
    ],
    'storage/framework' => [
        'path' => $baseDir . '/storage/framework',
        'required' => true,
        'writable' => true,
        'permission' => '755 or 777'
    ],
    'storage/logs' => [
        'path' => $baseDir . '/storage/logs',
        'required' => true,
        'writable' => true,
        'permission' => '755 or 777'
    ],
    'bootstrap/cache' => [
        'path' => $baseDir . '/bootstrap/cache',
        'required' => true,
        'writable' => true,
        'permission' => '755 or 777'
    ],
];

echo "<h2>Permission Check Report</h2>";
echo "<pre>";
echo "Base Directory: " . $baseDir . "\n";
echo "Current User: " . get_current_user() . "\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";
echo str_repeat("=", 80) . "\n\n";

$allGood = true;

foreach ($checks as $name => $config) {
    $path = $config['path'];
    $exists = file_exists($path);
    $isDir = is_dir($path);
    $isFile = is_file($path);
    $readable = $exists ? is_readable($path) : false;
    $writable = $exists ? is_writable($path) : false;
    
    if ($exists) {
        $perms = substr(sprintf('%o', fileperms($path)), -4);
    } else {
        $perms = 'N/A';
    }
    
    echo "Checking: {$name}\n";
    echo "  Path: {$path}\n";
    echo "  Exists: " . ($exists ? "Yes" : "No") . "\n";
    
    if ($exists) {
        echo "  Type: " . ($isDir ? "Directory" : "File") . "\n";
        echo "  Permissions: {$perms}\n";
        echo "  Readable: " . ($readable ? "Yes ✓" : "No ✗") . "\n";
        echo "  Writable: " . ($writable ? "Yes ✓" : "No ✗") . "\n";
        
        if ($config['required'] && !$exists) {
            echo "  STATUS: ✗ REQUIRED BUT MISSING\n";
            $allGood = false;
        } elseif ($config['writable'] && !$writable) {
            echo "  STATUS: ✗ NOT WRITABLE (Required: {$config['permission']})\n";
            $allGood = false;
        } elseif (!$readable) {
            echo "  STATUS: ✗ NOT READABLE\n";
            $allGood = false;
        } else {
            echo "  STATUS: ✓ OK\n";
        }
    } else {
        if ($config['required']) {
            echo "  STATUS: ✗ REQUIRED BUT MISSING\n";
            $allGood = false;
        } else {
            echo "  STATUS: ⚠ Optional (will be created if needed)\n";
        }
    }
    
    echo "\n";
}

echo str_repeat("=", 80) . "\n";
if ($allGood) {
    echo "\n✓ All required permissions are correct!\n";
    echo "\nYou can now run the migration at: /migrate.php\n";
} else {
    echo "\n✗ Some permissions need to be fixed.\n";
    echo "\nTo fix permissions via FTP/File Manager:\n";
    echo "1. Right-click on the directory/file\n";
    echo "2. Select 'Change Permissions' or 'CHMOD'\n";
    echo "3. Set the recommended permissions\n";
    echo "4. Make sure 'Recursive' is checked for directories\n";
    echo "\nCommon fixes:\n";
    echo "- If 'database' directory is missing or not writable: Set to 755 or 777\n";
    echo "- If 'storage' directories are not writable: Set to 755 or 777 recursively\n";
    echo "- If 'bootstrap/cache' is not writable: Set to 755 or 777\n";
}
echo "\n";
echo "Note: If paths contain spaces, that's fine - PHP handles them correctly.\n";
echo "</pre>";

