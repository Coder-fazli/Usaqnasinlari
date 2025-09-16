<?php
/**
 * Simple Cache Clearing Script
 * Access this via browser: http://yourdomain.com/simple-cache-clear.php
 */

// Basic security
if (isset($_GET['action']) && $_GET['action'] === 'clear') {

    echo "<h2>Cache Clearing Started...</h2>";

    // Clear any cache directories that might exist
    $cache_dirs = [
        __DIR__ . '/wp-content/cache/',
        __DIR__ . '/wp-content/uploads/cache/',
        __DIR__ . '/wp-content/litespeed/',
        __DIR__ . '/cache/',
    ];

    $total_cleared = 0;
    foreach ($cache_dirs as $cache_dir) {
        if (is_dir($cache_dir)) {
            $files_cleared = clear_directory($cache_dir);
            echo "Cleared {$files_cleared} files from {$cache_dir}<br>";
            $total_cleared += $files_cleared;
        }
    }

    // Send cache-busting headers
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "<h3>Cache clearing completed!</h3>";
    echo "Total files cleared: {$total_cleared}<br>";
    echo "Cache-busting headers sent.<br>";
    echo "<p><strong>Now test your redirects!</strong></p>";

} else {
    echo '<h2>Cache Cleaner</h2>';
    echo '<p><a href="?action=clear" style="background: #0073aa; color: white; padding: 10px 20px; text-decoration: none;">Clear All Caches</a></p>';
}

function clear_directory($dir) {
    $files_cleared = 0;
    if (!is_dir($dir)) {
        return $files_cleared;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $file_path = $dir . '/' . $file;
        if (is_dir($file_path)) {
            $files_cleared += clear_directory($file_path);
            @rmdir($file_path);
        } else {
            if (@unlink($file_path)) {
                $files_cleared++;
            }
        }
    }
    return $files_cleared;
}
?>