<?php
/**
 * Plugin Name: Moonfires Granth Engine
 * Plugin URI: https://moonfirestech.com/granth-engine
 * Description: Premium digital library platform for Sanatan Dharma scriptures
 * Version: 1.0.0
 * Author: Moonfires Tech
 * Author URI: https://moonfirestech.com
 * License: GPL-3.0
 * Text Domain: moonfires-granth
 * Domain Path: /languages
 * Requires at least: 5.0
 * Requires PHP: 7.4
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define constants
define('MGE_PLUGIN_FILE', __FILE__);
define('MGE_PLUGIN_DIR', dirname(__FILE__));
define('MGE_PLUGIN_URL', plugin_dir_url(__FILE__));
define('MGE_VERSION', '1.0.0');
define('MGE_MINIMUM_WP_VERSION', '5.0');
define('MGE_MINIMUM_PHP_VERSION', '7.4');

// Autoloader
require_once MGE_PLUGIN_DIR . '/vendor/autoload.php';

// Initialize plugin
if (class_exists('Moonfires\\Granth\\Core\\Plugin')) {
    add_action('plugins_loaded', function() {
        \Moonfires\Granth\Core\Plugin::getInstance()->init();
    });
    
    // Activation/Deactivation hooks
    register_activation_hook(__FILE__, ['\\Moonfires\\Granth\\Core\\Plugin', 'activate']);
    register_deactivation_hook(__FILE__, ['\\Moonfires\\Granth\\Core\\Plugin', 'deactivate']);
}
