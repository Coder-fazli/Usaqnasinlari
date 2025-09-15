<?php
/**
 * Plugin Name: Ultra Safe Country Redirects
 * Description: Ultra-safe country redirect system with extensive error handling
 * Version: 2.0.0
 * Author: Claude Code - Improved
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class UltraSafeCountryRedirects {

    private $table_name;
    private $plugin_ready = false;
    private $debug_mode = false;

    public function __construct() {
        // Only initialize if absolutely safe
        add_action('init', array($this, 'ultra_safe_init'), 999); // Very late priority
    }

    public function ultra_safe_init() {
        // Multiple safety checks before doing anything
        if (!$this->is_environment_safe()) {
            return;
        }

        global $wpdb;
        $this->table_name = $wpdb->prefix . 'ultra_safe_redirects';
        $this->plugin_ready = true;

        // Only add hooks if everything is safe
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_admin_actions'));

        // CRITICAL: Only add redirect check if explicitly enabled AND environment is safe
        // Note: We attach the hook even in admin for testing, but the redirect check itself will handle safety
        if (get_option('uscr_enabled', false) && $this->is_environment_safe()) {
            add_action('wp', array($this, 'ultra_safe_redirect_check'), 9999); // Lowest priority
        }

        register_activation_hook(__FILE__, array($this, 'safe_activate'));
        register_deactivation_hook(__FILE__, array($this, 'safe_deactivate'));
    }

    private function is_environment_safe() {
        // Check 1: WordPress is fully loaded
        if (!function_exists('wp_remote_get') || !function_exists('get_transient')) {
            return false;
        }

        // Check 2: Database is accessible
        global $wpdb;
        if (!$wpdb || !is_object($wpdb)) {
            return false;
        }

        // Check 3: No critical WordPress errors
        if (defined('WP_DEBUG') && WP_DEBUG && error_get_last()) {
            return false;
        }

        // Check 4: Basic WordPress functions work
        try {
            get_option('admin_email');
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    private function is_redirect_safe_to_run() {
        // Must be explicitly enabled
        if (!get_option('uscr_enabled', false)) {
            return false;
        }

        // Must not be admin area
        if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
            return false;
        }

        // Must not be during WordPress maintenance
        if (defined('WP_MAINTENANCE_MODE') || wp_maintenance()) {
            return false;
        }

        // Environment must still be safe
        if (!$this->is_environment_safe()) {
            return false;
        }

        return true;
    }

    public function safe_activate() {
        if (!$this->is_environment_safe()) {
            return;
        }

        $this->create_table_ultra_safe();

        // Start disabled for maximum safety
        add_option('uscr_enabled', false);
        add_option('uscr_debug', false);
    }

    public function safe_deactivate() {
        // Clean up safely
        delete_option('uscr_enabled');
        delete_option('uscr_debug');
    }

    private function create_table_ultra_safe() {
        global $wpdb;

        try {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                url varchar(500) NOT NULL,
                country_code varchar(2) NOT NULL DEFAULT 'AZ',
                redirect_to varchar(500) DEFAULT '',
                is_active tinyint(1) DEFAULT 1,
                created_at datetime DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (id)
            ) $charset_collate;";

            require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

            // Try to create table with dbDelta first
            $wpdb->hide_errors();
            $result = dbDelta($sql);
            $wpdb->show_errors();

            // Check if table was created
            if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                // Try direct query if dbDelta failed
                $direct_result = $wpdb->query($sql);

                // Still no table? Try simple version
                if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                    $simple_sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
                        id int(11) NOT NULL AUTO_INCREMENT,
                        url text NOT NULL,
                        country_code varchar(5) NOT NULL DEFAULT 'AZ',
                        redirect_to text,
                        is_active int(1) DEFAULT 1,
                        created_at datetime DEFAULT CURRENT_TIMESTAMP,
                        PRIMARY KEY (id)
                    )";
                    $wpdb->query($simple_sql);
                }
            }

            // Final check and log
            if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name) {
                error_log('Ultra Safe Redirects: Table created successfully');
            } else {
                error_log('Ultra Safe Redirects: Failed to create table. Last error: ' . $wpdb->last_error);
            }

        } catch (Exception $e) {
            error_log('Ultra Safe Redirects: Exception creating table: ' . $e->getMessage());
        }
    }

    public function add_admin_menu() {
        if (!$this->plugin_ready) {
            return;
        }

        add_options_page(
            'Ultra Safe Country Redirects',
            'Country Redirects',
            'manage_options',
            'ultra-safe-redirects',
            array($this, 'admin_page')
        );
    }

    public function handle_admin_actions() {
        if (!$this->plugin_ready || !current_user_can('manage_options')) {
            return;
        }

        // Handle enable/disable
        if (isset($_POST['uscr_toggle']) && wp_verify_nonce($_POST['uscr_nonce'], 'uscr_toggle')) {
            $enabled = isset($_POST['uscr_enabled']) ? true : false;
            update_option('uscr_enabled', $enabled);

            $message = $enabled ? 'Country redirects ENABLED' : 'Country redirects DISABLED';
            add_action('admin_notices', function() use ($message) {
                echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
            });
        }

        // Handle adding redirect
        if (isset($_POST['add_redirect']) && wp_verify_nonce($_POST['redirect_nonce'], 'add_redirect')) {
            $this->add_redirect_ultra_safe();
        }

        // Handle deleting redirect
        if (isset($_GET['delete_redirect']) && wp_verify_nonce($_GET['nonce'], 'delete_redirect')) {
            $this->delete_redirect_ultra_safe(intval($_GET['delete_redirect']));
        }

        // Handle debug request
        if (isset($_GET['debug_database']) && wp_verify_nonce($_GET['nonce'], 'debug_database')) {
            $this->show_debug_info();
        }

        // Handle redirect test request
        if (isset($_GET['test_redirect']) && wp_verify_nonce($_GET['nonce'], 'test_redirect')) {
            $this->test_redirect_logic();
        }
    }

    private function add_redirect_ultra_safe() {
        try {
            $url = sanitize_text_field($_POST['redirect_url']);
            $country = sanitize_text_field($_POST['country_code']);
            $redirect_to = sanitize_text_field($_POST['redirect_to'] ?: home_url());

            if (empty($url) || empty($country)) {
                throw new Exception('URL and country are required');
            }

            global $wpdb;

            // Check if table exists first
            if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                // Try to create the table
                $this->create_table_ultra_safe();

                // Check again
                if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                    throw new Exception('Database table does not exist and could not be created');
                }
            }

            // Test database connection and table access
            $test_query = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
            if ($test_query === null && $wpdb->last_error) {
                throw new Exception('Database connection error: ' . $wpdb->last_error);
            }

            // Validate data before insert
            if (strlen($url) > 500) {
                throw new Exception('URL too long (max 500 characters)');
            }
            if (strlen($country) > 2) {
                throw new Exception('Country code too long (max 2 characters)');
            }
            if (strlen($redirect_to) > 500) {
                throw new Exception('Redirect URL too long (max 500 characters)');
            }

            // Clear any previous errors
            $wpdb->flush();

            $result = $wpdb->insert(
                $this->table_name,
                array(
                    'url' => $url,
                    'country_code' => $country,
                    'redirect_to' => $redirect_to,
                    'is_active' => 1
                ),
                array('%s', '%s', '%s', '%d')
            );

            // Detailed error reporting
            if ($result === false) {
                $error_details = array();

                if ($wpdb->last_error) {
                    $error_details[] = 'MySQL Error: ' . $wpdb->last_error;
                }

                if ($wpdb->last_query) {
                    $error_details[] = 'Last Query: ' . $wpdb->last_query;
                }

                // Check table structure
                $table_info = $wpdb->get_results("DESCRIBE {$this->table_name}");
                if (empty($table_info)) {
                    $error_details[] = 'Table structure issue';
                } else {
                    $columns = array();
                    foreach ($table_info as $column) {
                        $columns[] = $column->Field;
                    }
                    $error_details[] = 'Table columns: ' . implode(', ', $columns);
                }

                $error_msg = !empty($error_details) ? implode(' | ', $error_details) : 'Unknown database insert error';
                throw new Exception('Database insert failed: ' . $error_msg);
            }

            // Verify the insert worked
            $inserted_id = $wpdb->insert_id;
            if (!$inserted_id) {
                throw new Exception('Insert appeared successful but no ID returned');
            }

            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Redirect added successfully</p></div>';
            });

        } catch (Exception $e) {
            add_action('admin_notices', function() use ($e) {
                echo '<div class="notice notice-error"><p>Error: ' . esc_html($e->getMessage()) . '</p></div>';
            });
        }
    }

    private function delete_redirect_ultra_safe($id) {
        try {
            if (!$id) {
                throw new Exception('Invalid ID');
            }

            global $wpdb;
            $wpdb->delete($this->table_name, array('id' => $id), array('%d'));

            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Redirect deleted</p></div>';
            });

        } catch (Exception $e) {
            add_action('admin_notices', function() use ($e) {
                echo '<div class="notice notice-error"><p>Error: ' . esc_html($e->getMessage()) . '</p></div>';
            });
        }
    }

    public function ultra_safe_redirect_check() {
        // Triple-check safety before doing anything
        if (!$this->is_redirect_safe_to_run()) {
            return;
        }

        try {
            // Get current URL with extensive validation
            $current_url = $this->get_ultra_safe_url();
            if (empty($current_url)) {
                return;
            }

            // Get user country with caching and error handling
            $user_country = $this->get_user_country_ultra_safe();
            if (empty($user_country) || $user_country === 'UNKNOWN') {
                return;
            }

            // Check for redirects with error handling
            $this->check_redirects_ultra_safe($current_url, $user_country);

        } catch (Exception $e) {
            // Never break the website - just log the error
            if (get_option('uscr_debug', false)) {
                error_log('Ultra Safe Redirects: ' . $e->getMessage());
            }
            return;
        }
    }

    private function get_ultra_safe_url() {
        try {
            if (!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['REQUEST_URI'])) {
                return '';
            }

            $host = sanitize_text_field($_SERVER['HTTP_HOST']);
            $uri = sanitize_text_field($_SERVER['REQUEST_URI']);

            if (empty($host) || empty($uri)) {
                return '';
            }

            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            return $protocol . '://' . $host . $uri;

        } catch (Exception $e) {
            return '';
        }
    }

    private function get_user_country_ultra_safe() {
        try {
            // Use cache first
            $cache_key = 'uscr_country_' . $this->get_user_ip_hash();
            $cached = get_transient($cache_key);

            if ($cached !== false) {
                return $cached;
            }

            // Get fresh data with timeout
            $country = $this->detect_user_country_ultra_safe();

            // Cache for 1 hour
            set_transient($cache_key, $country, HOUR_IN_SECONDS);

            return $country;

        } catch (Exception $e) {
            return 'UNKNOWN';
        }
    }

    private function get_user_ip_hash() {
        try {
            $ip = '127.0.0.1'; // Default

            if (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = sanitize_text_field($_SERVER['REMOTE_ADDR']);
            }

            return md5($ip . 'uscr_salt');

        } catch (Exception $e) {
            return md5('default_uscr_salt');
        }
    }

    private function detect_user_country_ultra_safe() {
        try {
            $ip = isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '127.0.0.1';

            // Default for localhost
            if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1') {
                return 'US';
            }

            // Ultra-safe API call with short timeout
            $response = wp_remote_get("http://ip-api.com/json/{$ip}", array(
                'timeout' => 2, // Very short timeout
                'sslverify' => false,
                'user-agent' => 'WordPress/Ultra-Safe-Redirects'
            ));

            if (is_wp_error($response)) {
                return 'UNKNOWN';
            }

            $body = wp_remote_retrieve_body($response);
            if (empty($body)) {
                return 'UNKNOWN';
            }

            $data = json_decode($body, true);
            if (!is_array($data) || !isset($data['countryCode'])) {
                return 'UNKNOWN';
            }

            return sanitize_text_field($data['countryCode']);

        } catch (Exception $e) {
            return 'UNKNOWN';
        }
    }

    private function check_redirects_ultra_safe($current_url, $user_country) {
        try {
            global $wpdb;

            // Check if table exists first
            if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") != $this->table_name) {
                return; // No table, no redirects
            }

            // Safe database query
            $redirects = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE country_code = %s AND is_active = 1 LIMIT 10",
                $user_country
            ));

            if (empty($redirects) || !is_array($redirects)) {
                return; // No redirects configured
            }

            foreach ($redirects as $redirect) {
                if (!is_object($redirect) || empty($redirect->url)) {
                    continue;
                }

                // CRITICAL FIX: Use exact match or specific path match, not strpos
                $redirect_url = trim($redirect->url);
                $current_path = parse_url($current_url, PHP_URL_PATH);
                $redirect_path = parse_url($redirect_url, PHP_URL_PATH);

                // Only redirect if it's an EXACT match or specific page match
                if ($current_url === $redirect_url ||
                    ($current_path && $redirect_path && $current_path === $redirect_path) ||
                    ($redirect_path && substr($current_url, -strlen($redirect_path)) === $redirect_path)) {

                    $redirect_to = !empty($redirect->redirect_to) ? $redirect->redirect_to : home_url();

                    // Prevent redirect loops
                    if ($current_url === $redirect_to || strpos($redirect_to, $current_path) !== false) {
                        continue;
                    }

                    // Ultra-safe redirect
                    if (filter_var($redirect_to, FILTER_VALIDATE_URL)) {
                        wp_redirect($redirect_to, 302);
                        exit;
                    }
                }
            }

        } catch (Exception $e) {
            // Never break the website
            if (get_option('uscr_debug', false)) {
                error_log('Ultra Safe Redirects: Redirect check failed - ' . $e->getMessage());
            }
            return;
        }
    }

    private function show_debug_info() {
        global $wpdb;

        $debug_output = array();

        // Test database connection
        $debug_output[] = "=== DATABASE DEBUG INFORMATION ===";
        $debug_output[] = "Time: " . date('Y-m-d H:i:s');

        // Connection test
        if ($wpdb->db_connect(false)) {
            $debug_output[] = "✅ Database connection: SUCCESS";
        } else {
            $debug_output[] = "❌ Database connection: FAILED";
            $debug_output[] = "Error: " . $wpdb->last_error;
        }

        // Table check
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name;
        $debug_output[] = "Table name: " . $this->table_name;

        if ($table_exists) {
            $debug_output[] = "✅ Table exists: YES";

            // Table structure
            $structure = $wpdb->get_results("DESCRIBE {$this->table_name}");
            $debug_output[] = "Table structure:";
            foreach ($structure as $column) {
                $debug_output[] = "  - {$column->Field}: {$column->Type}";
            }

            // Record count
            $count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
            $debug_output[] = "Record count: " . $count;

            // Test insert
            $debug_output[] = "\n=== INSERT TEST ===";
            $wpdb->flush();

            $test_result = $wpdb->insert(
                $this->table_name,
                array(
                    'url' => 'http://debug-test.com/test',
                    'country_code' => 'AZ',
                    'redirect_to' => 'http://debug-test.com',
                    'is_active' => 1
                ),
                array('%s', '%s', '%s', '%d')
            );

            if ($test_result !== false) {
                $debug_output[] = "✅ Insert test: SUCCESS";
                $debug_output[] = "Inserted ID: " . $wpdb->insert_id;

                // Clean up
                $wpdb->delete($this->table_name, array('url' => 'http://debug-test.com/test'), array('%s'));
                $debug_output[] = "✅ Test data cleaned up";
            } else {
                $debug_output[] = "❌ Insert test: FAILED";
                $debug_output[] = "MySQL Error: " . $wpdb->last_error;
                $debug_output[] = "Last Query: " . $wpdb->last_query;
            }

        } else {
            $debug_output[] = "❌ Table exists: NO";

            // Try to create table
            $debug_output[] = "\n=== TABLE CREATION TEST ===";
            $this->create_table_ultra_safe();

            $table_created = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name;
            if ($table_created) {
                $debug_output[] = "✅ Table creation: SUCCESS";
            } else {
                $debug_output[] = "❌ Table creation: FAILED";
                $debug_output[] = "Error: " . $wpdb->last_error;
            }
        }

        // System info
        $debug_output[] = "\n=== SYSTEM INFORMATION ===";
        $debug_output[] = "WordPress version: " . get_bloginfo('version');
        $debug_output[] = "PHP version: " . PHP_VERSION;
        $debug_output[] = "MySQL version: " . $wpdb->get_var("SELECT VERSION()");
        $debug_output[] = "Database name: " . DB_NAME;
        $debug_output[] = "Table prefix: " . $wpdb->prefix;

        if ($wpdb->last_error) {
            $debug_output[] = "Last database error: " . $wpdb->last_error;
        }

        // Display debug output
        add_action('admin_notices', function() use ($debug_output) {
            echo '<div class="notice notice-info"><h3>Database Debug Results</h3><pre style="background: #f0f0f0; padding: 10px; overflow: auto; max-height: 400px;">' . esc_html(implode("\n", $debug_output)) . '</pre></div>';
        });
    }

    private function test_redirect_logic() {
        $test_output = array();

        $test_output[] = "=== REDIRECT LOGIC TEST ===";
        $test_output[] = "Time: " . date('Y-m-d H:i:s');

        // Check if redirects are enabled
        $enabled = get_option('uscr_enabled', false);
        $test_output[] = "Plugin enabled: " . ($enabled ? "YES" : "NO");

        if (!$enabled) {
            $test_output[] = "❌ REDIRECTS ARE DISABLED - This is why redirects don't work!";
        }

        // Test safety checks
        $test_output[] = "\n=== SAFETY CHECKS ===";
        $test_output[] = "Environment safe: " . ($this->is_environment_safe() ? "YES" : "NO");
        $test_output[] = "Redirect safe to run: " . ($this->is_redirect_safe_to_run() ? "YES" : "NO");

        // Test current page detection
        $test_output[] = "\n=== CURRENT PAGE DETECTION ===";
        $current_url = $this->get_ultra_safe_url();
        $test_output[] = "Current URL detected: " . ($current_url ?: "FAILED");

        // Test country detection
        $test_output[] = "\n=== COUNTRY DETECTION ===";
        $user_country = $this->get_user_country_ultra_safe();
        $test_output[] = "User country detected: " . $user_country;

        // Test IP detection
        $test_output[] = "User IP (for testing): " . (isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'Not detected');

        // Check database redirects
        $test_output[] = "\n=== DATABASE REDIRECTS ===";
        global $wpdb;
        $redirects = $wpdb->get_results("SELECT * FROM {$this->table_name} WHERE is_active = 1");
        $test_output[] = "Active redirects in database: " . count($redirects);

        foreach ($redirects as $redirect) {
            $test_output[] = "  - {$redirect->url} → {$redirect->redirect_to} (Country: {$redirect->country_code})";
        }

        // Test hooks
        $test_output[] = "\n=== WORDPRESS HOOKS ===";
        $test_output[] = "wp hook priority: " . (has_action('wp', array($this, 'ultra_safe_redirect_check')) !== false ? "ATTACHED" : "NOT ATTACHED");

        // Show what would happen for Azerbaijan user
        if (count($redirects) > 0 && $current_url) {
            $test_output[] = "\n=== SIMULATION FOR AZERBAIJAN USER ===";
            foreach ($redirects as $redirect) {
                $redirect_url = trim($redirect->url);
                $current_path = parse_url($current_url, PHP_URL_PATH);
                $redirect_path = parse_url($redirect_url, PHP_URL_PATH);

                $would_redirect = (
                    $current_url === $redirect_url ||
                    ($current_path && $redirect_path && $current_path === $redirect_path) ||
                    ($redirect_path && substr($current_url, -strlen($redirect_path)) === $redirect_path)
                );

                $test_output[] = "URL: {$redirect_url}";
                $test_output[] = "  Current URL: {$current_url}";
                $test_output[] = "  Would redirect: " . ($would_redirect ? "YES" : "NO");
            }
        }

        // Display test output
        add_action('admin_notices', function() use ($test_output) {
            echo '<div class="notice notice-info"><h3>Redirect Logic Test Results</h3><pre style="background: #f0f0f0; padding: 10px; overflow: auto; max-height: 400px;">' . esc_html(implode("\n", $test_output)) . '</pre></div>';
        });
    }

    public function admin_page() {
        if (!$this->plugin_ready) {
            echo '<div class="wrap"><h1>Ultra Safe Country Redirects</h1><p>Plugin not ready - environment safety check failed.</p></div>';
            return;
        }

        $enabled = get_option('uscr_enabled', false);

        try {
            global $wpdb;
            $redirects = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC LIMIT 20");
        } catch (Exception $e) {
            $redirects = array();
        }

        ?>
        <div class="wrap">
            <h1>Ultra Safe Country Redirects v2.0</h1>

            <!-- Safety Status -->
            <div class="card" style="background: <?php echo $enabled ? '#ffebee' : '#e8f5e8'; ?>;">
                <h2>🛡️ Safety Status</h2>
                <form method="post">
                    <?php wp_nonce_field('uscr_toggle', 'uscr_nonce'); ?>
                    <p>
                        <label>
                            <input type="checkbox" name="uscr_enabled" value="1" <?php checked($enabled); ?>>
                            <strong>Enable country redirects</strong>
                        </label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="uscr_toggle" class="button-primary" value="Save Settings">
                    </p>
                </form>

                <?php if ($enabled): ?>
                    <p><strong style="color: #d32f2f;">⚠️ REDIRECTS ARE ACTIVE</strong></p>
                    <p>Redirects are currently running. Disable immediately if you notice any issues.</p>
                <?php else: ?>
                    <p><strong style="color: #2e7d32;">✓ REDIRECTS ARE SAFELY DISABLED</strong></p>
                    <p>Your website is completely safe. Enable only when ready to test.</p>
                <?php endif; ?>
            </div>

            <!-- Add New Redirect -->
            <div class="card">
                <h2>Add New Redirect</h2>
                <form method="post">
                    <?php wp_nonce_field('add_redirect', 'redirect_nonce'); ?>
                    <table class="form-table">
                        <tr>
                            <th><label for="redirect_url">URL to Redirect From</label></th>
                            <td>
                                <input type="url" id="redirect_url" name="redirect_url" class="regular-text"
                                       placeholder="<?php echo esc_url(home_url()); ?>/page-name" required>
                                <p class="description">Full URL of the page to redirect</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="redirect_to">Redirect To</label></th>
                            <td>
                                <input type="url" id="redirect_to" name="redirect_to" class="regular-text"
                                       value="<?php echo esc_url(home_url()); ?>">
                                <p class="description">Where to redirect (default: home page)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="country_code">Country</label></th>
                            <td>
                                <select id="country_code" name="country_code" required>
                                    <option value="AZ">Azerbaijan (AZ)</option>
                                    <option value="TR">Turkey (TR)</option>
                                    <option value="RU">Russia (RU)</option>
                                    <option value="IR">Iran (IR)</option>
                                    <option value="GE">Georgia (GE)</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" name="add_redirect" class="button-primary" value="Add Redirect">
                    </p>
                </form>
            </div>

            <!-- Current Redirects -->
            <div class="card">
                <h2>Current Redirects</h2>
                <?php if (empty($redirects)): ?>
                    <p>No redirects configured.</p>
                <?php else: ?>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                            <tr>
                                <th>From URL</th>
                                <th>To URL</th>
                                <th>Country</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($redirects as $redirect): ?>
                                <tr>
                                    <td><?php echo esc_html($redirect->url); ?></td>
                                    <td><?php echo esc_html($redirect->redirect_to ?: home_url()); ?></td>
                                    <td><?php echo esc_html($redirect->country_code); ?></td>
                                    <td>
                                        <?php if ($redirect->is_active): ?>
                                            <span style="color: green;">Active</span>
                                        <?php else: ?>
                                            <span style="color: red;">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($redirect->created_at)); ?></td>
                                    <td>
                                        <a href="<?php echo wp_nonce_url(admin_url('options-general.php?page=ultra-safe-redirects&delete_redirect=' . $redirect->id), 'delete_redirect', 'nonce'); ?>"
                                           class="button button-small"
                                           onclick="return confirm('Delete this redirect?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Ultra Safety Features -->
            <div class="card" style="background: #f0f8ff;">
                <h2>🛡️ Ultra Safety Features</h2>
                <ul style="list-style: none;">
                    <li>✅ <strong>Environment Safety Checks:</strong> Plugin only runs if WordPress is 100% stable</li>
                    <li>✅ <strong>Disabled by Default:</strong> Never runs until you explicitly enable it</li>
                    <li>✅ <strong>Error Isolation:</strong> Any error immediately stops redirect checks</li>
                    <li>✅ <strong>Minimal Performance Impact:</strong> Uses caching and short timeouts</li>
                    <li>✅ <strong>Easy Disable:</strong> Can be instantly turned off if any issues occur</li>
                    <li>✅ <strong>No Website Interference:</strong> Uses lowest priority hooks</li>
                    <li>✅ <strong>Database Protection:</strong> All queries are safe and limited</li>
                    <li>✅ <strong>Redirect Loop Prevention:</strong> Prevents infinite redirects</li>
                </ul>

                <h3>How to Use Safely:</h3>
                <ol>
                    <li><strong>Test First:</strong> Add one redirect rule and test it</li>
                    <li><strong>Monitor:</strong> Watch for any issues after enabling</li>
                    <li><strong>Disable Quickly:</strong> If anything goes wrong, just uncheck "Enable"</li>
                    <li><strong>Example:</strong> Add <code><?php echo esc_url(home_url()); ?>/test</code> for Azerbaijan users</li>
                </ol>
            </div>

            <!-- Debug Information -->
            <div class="card" style="background: #fffbf0;">
                <h2>🔧 Debug Information</h2>
                <?php
                global $wpdb;
                $table_exists = $wpdb->get_var("SHOW TABLES LIKE '{$this->table_name}'") == $this->table_name;
                $db_connection = $wpdb->db_connect(false);
                $table_structure = array();
                $record_count = 0;

                if ($table_exists) {
                    $structure_result = $wpdb->get_results("DESCRIBE {$this->table_name}");
                    if ($structure_result) {
                        foreach ($structure_result as $column) {
                            $table_structure[] = "{$column->Field} ({$column->Type})";
                        }
                    }
                    $record_count = $wpdb->get_var("SELECT COUNT(*) FROM {$this->table_name}");
                }
                ?>
                <ul style="list-style: none;">
                    <li><?php echo $table_exists ? '✅' : '❌'; ?> <strong>Database Table:</strong> <?php echo $table_exists ? 'Exists' : 'Missing'; ?></li>
                    <li><?php echo $db_connection ? '✅' : '❌'; ?> <strong>Database Connection:</strong> <?php echo $db_connection ? 'Connected' : 'Failed'; ?></li>
                    <li>📊 <strong>Table Name:</strong> <code><?php echo esc_html($this->table_name); ?></code></li>
                    <li>📈 <strong>Records Count:</strong> <?php echo $table_exists ? $record_count : 'N/A'; ?></li>
                    <li>🌐 <strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></li>
                    <li>🔢 <strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                    <li>🏠 <strong>Home URL:</strong> <code><?php echo esc_url(home_url()); ?></code></li>
                    <?php if ($wpdb->last_error): ?>
                    <li>⚠️ <strong>Last Database Error:</strong> <code><?php echo esc_html($wpdb->last_error); ?></code></li>
                    <?php endif; ?>
                </ul>

                <?php if ($table_exists && !empty($table_structure)): ?>
                <h4>Table Structure:</h4>
                <ul style="font-family: monospace; font-size: 12px;">
                    <?php foreach ($table_structure as $column): ?>
                    <li><?php echo esc_html($column); ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if (!$table_exists): ?>
                <p style="color: #d32f2f;"><strong>⚠️ Issue Detected:</strong> Database table is missing. This will cause "Error adding redirect". The plugin will attempt to create it when you add your first redirect.</p>
                <?php endif; ?>

                <?php if (!$db_connection): ?>
                <p style="color: #d32f2f;"><strong>⚠️ Issue Detected:</strong> Database connection failed. This will prevent the plugin from working.</p>
                <?php endif; ?>

                <h4>Test Database Operations:</h4>
                <p><em>Try adding a redirect to see detailed error information if something fails.</em></p>

                <p>
                    <a href="<?php echo wp_nonce_url(admin_url('options-general.php?page=ultra-safe-redirects&debug_database=1'), 'debug_database', 'nonce'); ?>"
                       class="button button-secondary">🔧 Run Database Debug Test</a>

                    <a href="<?php echo wp_nonce_url(admin_url('options-general.php?page=ultra-safe-redirects&test_redirect=1'), 'test_redirect', 'nonce'); ?>"
                       class="button button-secondary">🌍 Test Redirect Logic</a>
                </p>
                <p><small>Database test checks the database. Redirect test checks if the redirect logic is working.</small></p>

                <div style="margin-top: 15px; padding: 10px; background: #e7f3ff; border-left: 4px solid #2196F3;">
                    <h4>🧪 Test Your Redirects</h4>
                    <p>To test if redirects actually work, visit one of your protected URLs:</p>
                    <?php
                    global $wpdb;
                    $test_redirects = $wpdb->get_results("SELECT * FROM {$this->table_name} WHERE is_active = 1 LIMIT 3");
                    if ($test_redirects): ?>
                        <ul>
                            <?php foreach ($test_redirects as $redirect): ?>
                                <li>
                                    <strong>Test URL:</strong>
                                    <a href="<?php echo esc_url($redirect->url); ?>" target="_blank"><?php echo esc_html($redirect->url); ?></a>
                                    <br><small>Should redirect to: <?php echo esc_html($redirect->redirect_to ?: home_url()); ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <p><small><strong>Note:</strong> Redirects only work on the frontend (not in admin area). Open these links in a new tab to test.</small></p>
                    <?php else: ?>
                        <p>No redirects configured yet. Add one above to test.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }
}

// Initialize with ultra-safe practices
new UltraSafeCountryRedirects();