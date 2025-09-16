<?php
/**
 * Clear All Website Caches - Emergency Cache Cleaner
 * Run this script to clear all types of caches on the website
 */

// Security check
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__) . '/');
}

// Load WordPress
require_once(ABSPATH . 'wp-config.php');
require_once(ABSPATH . 'wp-includes/wp-db.php');
require_once(ABSPATH . 'wp-includes/functions.php');

echo "<h2>Emergency Cache Cleaner</h2>\n";
echo "<p>Starting cache clearing process...</p>\n";

// 1. Clear WordPress transients
echo "<h3>1. Clearing WordPress Transients</h3>\n";
global $wpdb;
$transient_count = $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_%'");
echo "Cleared {$transient_count} WordPress transients<br>\n";

// 2. Clear LiteSpeed Cache if available
echo "<h3>2. Clearing LiteSpeed Cache</h3>\n";
if (class_exists('LiteSpeed_Cache_Purge')) {
    try {
        LiteSpeed_Cache_Purge::purge_all();
        echo "LiteSpeed Cache cleared successfully<br>\n";
    } catch (Exception $e) {
        echo "LiteSpeed Cache clearing failed: " . $e->getMessage() . "<br>\n";
    }
} else {
    echo "LiteSpeed Cache plugin not detected<br>\n";
}

// 3. Clear object cache
echo "<h3>3. Clearing Object Cache</h3>\n";
if (function_exists('wp_cache_flush')) {
    if (wp_cache_flush()) {
        echo "Object cache cleared successfully<br>\n";
    } else {
        echo "Object cache clearing failed<br>\n";
    }
} else {
    echo "Object cache not available<br>\n";
}

// 4. Clear page cache files if they exist
echo "<h3>4. Clearing Page Cache Files</h3>\n";
$cache_dirs = [
    ABSPATH . 'wp-content/cache/',
    ABSPATH . 'wp-content/uploads/cache/',
    ABSPATH . 'wp-content/litespeed/',
    ABSPATH . 'cache/',
];

foreach ($cache_dirs as $cache_dir) {
    if (is_dir($cache_dir)) {
        $files_cleared = clear_directory_contents($cache_dir);
        echo "Cleared {$files_cleared} files from {$cache_dir}<br>\n";
    }
}

// 5. Clear rewrite rules
echo "<h3>5. Flushing Rewrite Rules</h3>\n";
if (function_exists('flush_rewrite_rules')) {
    flush_rewrite_rules();
    echo "Rewrite rules flushed successfully<br>\n";
}

// 6. Clear opcache if available
echo "<h3>6. Clearing OPcache</h3>\n";
if (function_exists('opcache_reset')) {
    if (opcache_reset()) {
        echo "OPcache cleared successfully<br>\n";
    } else {
        echo "OPcache clearing failed<br>\n";
    }
} else {
    echo "OPcache not available<br>\n";
}

// 7. Send cache-busting headers
echo "<h3>7. Setting Cache-Busting Headers</h3>\n";
if (!headers_sent()) {
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    echo "Cache-busting headers sent<br>\n";
} else {
    echo "Headers already sent - cache-busting headers not set<br>\n";
}

echo "<h2>Cache Clearing Complete!</h2>\n";
echo "<p><strong>All caches have been cleared. Please test your redirects now.</strong></p>\n";
echo "<p>You can delete this file after use: clear-all-caches.php</p>\n";

/**
 * Helper function to clear directory contents
 */
function clear_directory_contents($dir) {
    $files_cleared = 0;
    if (!is_dir($dir)) {
        return $files_cleared;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        $file_path = $dir . '/' . $file;
        if (is_dir($file_path)) {
            $files_cleared += clear_directory_contents($file_path);
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