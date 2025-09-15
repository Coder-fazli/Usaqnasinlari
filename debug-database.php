<?php
// WordPress Database Debug Script
// Place this file in the root directory and visit it in your browser

// Load WordPress
require_once('wp-config.php');
require_once('wp-includes/wp-db.php');

// Initialize database connection
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

echo "<h1>Database Debug Information</h1>";

// Test basic connection
echo "<h2>1. Database Connection Test</h2>";
if ($wpdb->db_connect(false)) {
    echo "✅ Database connection: <strong>SUCCESS</strong><br>";
} else {
    echo "❌ Database connection: <strong>FAILED</strong><br>";
    echo "Error: " . $wpdb->last_error . "<br>";
    exit;
}

// Check table existence
$table_name = $wpdb->prefix . 'ultra_safe_redirects';
echo "<h2>2. Table Check</h2>";
echo "Table name: <code>$table_name</code><br>";

$table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
if ($table_exists) {
    echo "✅ Table exists: <strong>YES</strong><br>";

    // Show table structure
    echo "<h3>Table Structure:</h3>";
    $structure = $wpdb->get_results("DESCRIBE $table_name");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>";
    foreach ($structure as $column) {
        echo "<tr>";
        echo "<td>{$column->Field}</td>";
        echo "<td>{$column->Type}</td>";
        echo "<td>{$column->Null}</td>";
        echo "<td>{$column->Key}</td>";
        echo "<td>{$column->Default}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Count records
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    echo "<br>Record count: <strong>$count</strong><br>";

} else {
    echo "❌ Table exists: <strong>NO</strong><br>";

    // Try to create the table
    echo "<h3>Attempting to create table...</h3>";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        url varchar(500) NOT NULL,
        country_code varchar(2) NOT NULL DEFAULT 'AZ',
        redirect_to varchar(500) DEFAULT '',
        is_active tinyint(1) DEFAULT 1,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id)
    ) $charset_collate;";

    $result = $wpdb->query($sql);
    if ($result !== false) {
        echo "✅ Table creation: <strong>SUCCESS</strong><br>";
    } else {
        echo "❌ Table creation: <strong>FAILED</strong><br>";
        echo "Error: " . $wpdb->last_error . "<br>";
    }
}

// Test insert operation
echo "<h2>3. Insert Test</h2>";
if ($table_exists || $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {

    // Clear any previous errors
    $wpdb->flush();

    $test_data = array(
        'url' => 'http://test.com/debug-test',
        'country_code' => 'AZ',
        'redirect_to' => 'http://test.com',
        'is_active' => 1
    );

    echo "Attempting to insert test data...<br>";
    $result = $wpdb->insert($table_name, $test_data, array('%s', '%s', '%s', '%d'));

    if ($result !== false) {
        echo "✅ Insert test: <strong>SUCCESS</strong><br>";
        echo "Inserted ID: " . $wpdb->insert_id . "<br>";

        // Clean up test data
        $wpdb->delete($table_name, array('url' => 'http://test.com/debug-test'), array('%s'));
        echo "✅ Test data cleaned up<br>";

    } else {
        echo "❌ Insert test: <strong>FAILED</strong><br>";
        echo "MySQL Error: " . $wpdb->last_error . "<br>";
        echo "Last Query: " . $wpdb->last_query . "<br>";
    }
} else {
    echo "⚠️ Cannot test insert - table does not exist<br>";
}

// Database info
echo "<h2>4. Database Information</h2>";
echo "WordPress version: " . get_bloginfo('version') . "<br>";
echo "PHP version: " . PHP_VERSION . "<br>";
echo "MySQL version: " . $wpdb->get_var("SELECT VERSION()") . "<br>";
echo "Database name: " . DB_NAME . "<br>";
echo "Database host: " . DB_HOST . "<br>";
echo "Table prefix: " . $wpdb->prefix . "<br>";

// Check for any WordPress database errors
if ($wpdb->last_error) {
    echo "<h2>5. WordPress Database Errors</h2>";
    echo "Last error: " . $wpdb->last_error . "<br>";
}

echo "<br><strong>Debug complete!</strong>";
?>