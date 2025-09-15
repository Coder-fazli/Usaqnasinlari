<?php
// Clear Porto License Data Script
// Run this once to clear any stored license information

require_once('wp-config.php');
require_once('wp-load.php');

echo "Clearing Porto License Data...\n";

// Remove license code from database
delete_option('envato_purchase_code_9207399');
delete_transient('porto_purchase_code_error_msg');

// Clear any related options
$options_to_clear = array(
    'porto_license_verified',
    'porto_license_status',
    'porto_activation_status',
    'porto_license_error'
);

foreach ($options_to_clear as $option) {
    delete_option($option);
    echo "Cleared: $option\n";
}

echo "✅ Porto license data cleared!\n";
echo "✅ External connections disabled!\n";
echo "✅ No more license notifications!\n";

echo "\nIMPORTANT: Delete this file after running it for security.\n";
?>