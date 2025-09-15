<?php
/**
 * Plugin Name: Safe Country Redirects
 * Description: Safely redirect users from specific countries to home page
 * Version: 1.0.0
 * Author: Claude Code
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class SafeCountryRedirects {

    private $table_name;
    private $plugin_active = false;

    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'safe_country_redirects';

        // Only initialize if no fatal errors
        add_action('plugins_loaded', array($this, 'safe_init'));
    }

    public function safe_init() {
        // Check if we can safely proceed
        if (!$this->can_proceed_safely()) {
            return;
        }

        $this->plugin_active = true;

        // Safe hooks that won't interfere with content loading
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'handle_admin_actions'));

        // Only add redirect check if plugin is enabled in options
        if (get_option('scr_enabled', false)) {
            add_action('wp', array($this, 'safe_redirect_check'), 999); // Late priority
        }

        // Plugin activation/deactivation
        register_activation_hook(__FILE__, array($this, 'activate_plugin'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate_plugin'));
    }

    private function can_proceed_safely() {
        // Check if WordPress is properly loaded
        if (!function_exists('add_action') || !function_exists('wp_remote_get')) {
            return false;
        }

        // Check if database is accessible
        global $wpdb;
        if (!$wpdb || $wpdb->last_error) {
            return false;
        }

        return true;
    }

    public function activate_plugin() {
        if (!$this->can_proceed_safely()) {
            return;
        }

        $this->create_table_safely();
        add_option('scr_enabled', false); // Disabled by default
    }

    public function deactivate_plugin() {
        // Clean up options
        delete_option('scr_enabled');
    }

    private function create_table_safely() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            url varchar(500) NOT NULL,
            country_code varchar(2) NOT NULL DEFAULT 'AZ',
            redirect_to varchar(500) DEFAULT '',
            is_active tinyint(1) DEFAULT 1,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            INDEX country_idx (country_code),
            INDEX active_idx (is_active)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Suppress errors and try to create table
        $wpdb->hide_errors();
        $result = dbDelta($sql);
        $wpdb->show_errors();

        // Log any errors but don't stop execution
        if ($wpdb->last_error) {
            error_log('Safe Country Redirects: Table creation error - ' . $wpdb->last_error);
        }
    }

    public function add_admin_menu() {
        if (!$this->plugin_active) {
            return;
        }

        add_options_page(
            'Safe Country Redirects',
            'Country Redirects',
            'manage_options',
            'safe-country-redirects',
            array($this, 'admin_page')
        );
    }

    public function handle_admin_actions() {
        if (!$this->plugin_active || !current_user_can('manage_options')) {
            return;
        }

        // Handle enable/disable
        if (isset($_POST['scr_toggle']) && wp_verify_nonce($_POST['scr_nonce'], 'scr_toggle')) {
            $enabled = isset($_POST['scr_enabled']) ? true : false;
            update_option('scr_enabled', $enabled);

            $message = $enabled ? 'Country redirects enabled' : 'Country redirects disabled';
            add_action('admin_notices', function() use ($message) {
                echo '<div class="notice notice-success"><p>' . esc_html($message) . '</p></div>';
            });
        }

        // Handle adding new redirect
        if (isset($_POST['add_redirect']) && wp_verify_nonce($_POST['redirect_nonce'], 'add_redirect')) {
            $this->add_redirect_safely();
        }

        // Handle deleting redirect
        if (isset($_GET['delete_redirect']) && wp_verify_nonce($_GET['nonce'], 'delete_redirect')) {
            $this->delete_redirect_safely(intval($_GET['delete_redirect']));
        }
    }

    private function add_redirect_safely() {
        $url = sanitize_text_field($_POST['redirect_url']);
        $country = sanitize_text_field($_POST['country_code']);
        $redirect_to = sanitize_text_field($_POST['redirect_to'] ?: home_url());

        if (empty($url) || empty($country)) {
            return;
        }

        global $wpdb;

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

        if ($result === false) {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-error"><p>Error adding redirect</p></div>';
            });
        } else {
            add_action('admin_notices', function() {
                echo '<div class="notice notice-success"><p>Redirect added successfully</p></div>';
            });
        }
    }

    private function delete_redirect_safely($id) {
        if (!$id) return;

        global $wpdb;

        $wpdb->delete(
            $this->table_name,
            array('id' => $id),
            array('%d')
        );

        add_action('admin_notices', function() {
            echo '<div class="notice notice-success"><p>Redirect deleted</p></div>';
        });
    }

    public function safe_redirect_check() {
        // Only run if plugin is active and enabled
        if (!$this->plugin_active || !get_option('scr_enabled', false)) {
            return;
        }

        // Don't run on admin pages
        if (is_admin()) {
            return;
        }

        // Don't run on AJAX requests
        if (wp_doing_ajax()) {
            return;
        }

        // Get current URL safely
        $current_url = $this->get_current_url();
        if (empty($current_url)) {
            return;
        }

        // Get user country with caching and error handling
        $user_country = $this->get_user_country_cached();
        if (empty($user_country) || $user_country === 'UNKNOWN') {
            return;
        }

        // Check for redirects
        $this->check_redirects($current_url, $user_country);
    }

    private function get_current_url() {
        if (!isset($_SERVER['HTTP_HOST']) || !isset($_SERVER['REQUEST_URI'])) {
            return '';
        }

        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        return $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    private function get_user_country_cached() {
        // Check cache first (1 hour cache)
        $cache_key = 'scr_country_' . $this->get_user_ip_hash();
        $cached_country = get_transient($cache_key);

        if ($cached_country !== false) {
            return $cached_country;
        }

        // Get fresh country data
        $country = $this->detect_user_country();

        // Cache the result
        set_transient($cache_key, $country, HOUR_IN_SECONDS);

        return $country;
    }

    private function get_user_ip_hash() {
        $ip = $this->get_user_ip();
        return md5($ip . 'scr_salt'); // Hash for privacy
    }

    private function get_user_ip() {
        // Check various IP headers safely
        $ip_headers = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR'
        );

        foreach ($ip_headers as $header) {
            if (isset($_SERVER[$header]) && !empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }

        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
    }

    private function detect_user_country() {
        $ip = $this->get_user_ip();

        // Default for localhost
        if (empty($ip) || $ip === '127.0.0.1' || $ip === '::1') {
            return 'US';
        }

        // Try API call with timeout and error handling
        $response = wp_remote_get("http://ip-api.com/json/{$ip}", array(
            'timeout' => 3, // Short timeout
            'sslverify' => false
        ));

        if (is_wp_error($response)) {
            return 'UNKNOWN';
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (isset($data['countryCode']) && !empty($data['countryCode'])) {
            return $data['countryCode'];
        }

        return 'UNKNOWN';
    }

    private function check_redirects($current_url, $user_country) {
        global $wpdb;

        // Get active redirects for this country
        $redirects = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table_name} WHERE country_code = %s AND is_active = 1",
            $user_country
        ));

        if (empty($redirects)) {
            return;
        }

        foreach ($redirects as $redirect) {
            if (strpos($current_url, $redirect->url) !== false) {
                $redirect_to = !empty($redirect->redirect_to) ? $redirect->redirect_to : home_url();

                // Prevent redirect loops
                if ($current_url !== $redirect_to) {
                    wp_redirect($redirect_to, 302);
                    exit;
                }
            }
        }
    }

    public function admin_page() {
        if (!$this->plugin_active) {
            echo '<div class="wrap"><h1>Safe Country Redirects</h1><p>Plugin not active due to safety checks.</p></div>';
            return;
        }

        $enabled = get_option('scr_enabled', false);

        global $wpdb;
        $redirects = $wpdb->get_results("SELECT * FROM {$this->table_name} ORDER BY created_at DESC");

        ?>
        <div class="wrap">
            <h1>Safe Country Redirects</h1>

            <!-- Enable/Disable Plugin -->
            <div class="card">
                <h2>Plugin Status</h2>
                <form method="post">
                    <?php wp_nonce_field('scr_toggle', 'scr_nonce'); ?>
                    <p>
                        <label>
                            <input type="checkbox" name="scr_enabled" value="1" <?php checked($enabled); ?>>
                            Enable country redirects
                        </label>
                    </p>
                    <p class="submit">
                        <input type="submit" name="scr_toggle" class="button-primary" value="Save Settings">
                    </p>
                </form>
                <?php if ($enabled): ?>
                    <p><strong style="color: green;">✓ Redirects are ACTIVE</strong></p>
                <?php else: ?>
                    <p><strong style="color: red;">✗ Redirects are DISABLED</strong></p>
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
                                       placeholder="<?php echo home_url(); ?>/page-name" required>
                                <p class="description">Enter the URL that should be redirected</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="redirect_to">Redirect To</label></th>
                            <td>
                                <input type="url" id="redirect_to" name="redirect_to" class="regular-text"
                                       value="<?php echo home_url(); ?>">
                                <p class="description">Where to redirect (default: home page)</p>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="country_code">Country</label></th>
                            <td>
                                <select id="country_code" name="country_code" required>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="TR">Turkey</option>
                                    <option value="RU">Russia</option>
                                    <option value="IR">Iran</option>
                                    <option value="GE">Georgia</option>
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
                                        <a href="<?php echo wp_nonce_url(admin_url('options-general.php?page=safe-country-redirects&delete_redirect=' . $redirect->id), 'delete_redirect', 'nonce'); ?>"
                                           class="button button-small"
                                           onclick="return confirm('Are you sure?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Safety Information -->
            <div class="card">
                <h2>Safety Features</h2>
                <ul>
                    <li>✓ Plugin can be easily disabled</li>
                    <li>✓ Uses safe WordPress hooks</li>
                    <li>✓ Includes error handling and timeouts</li>
                    <li>✓ Caches country detection for performance</li>
                    <li>✓ Won't interfere with content loading</li>
                    <li>✓ Disabled by default until you enable it</li>
                </ul>
            </div>
        </div>
        <?php
    }
}

// Initialize the plugin safely
new SafeCountryRedirects();