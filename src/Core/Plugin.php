<?php
/**
 * Main Plugin Class
 * Core initialization and setup
 */

namespace Moonfires\Granth\Core;

use Moonfires\Granth\Traits\Singleton;
use Moonfires\Granth\Database\Schema;
use Moonfires\Granth\Admin\Dashboard;
use Moonfires\Granth\API\Routes;

class Plugin {
    use Singleton;

    private $loader;
    private $config;
    private $version = '1.0.0';

    /**
     * Initialize plugin
     */
    public function init() {
        // Load config
        $this->config = new Config();
        $this->loader = new Loader();

        // Load text domain
        $this->load_textdomain();

        // Register hooks
        $this->register_hooks();

        // Define admin hooks
        if (is_admin()) {
            $this->define_admin_hooks();
        }

        // Define frontend hooks
        if (!is_admin()) {
            $this->define_frontend_hooks();
        }

        // Register REST routes
        $this->register_rest_routes();

        // Enqueue scripts and styles
        $this->enqueue_assets();
    }

    /**
     * Load plugin text domain
     */
    private function load_textdomain() {
        load_plugin_textdomain(
            'moonfires-granth',
            false,
            dirname(plugin_basename(MGE_PLUGIN_FILE)) . '/languages'
        );
    }

    /**
     * Register core hooks
     */
    private function register_hooks() {
        // Plugin loaded
        do_action('mge_loaded');
    }

    /**
     * Define admin hooks
     */
    private function define_admin_hooks() {
        $admin = new Dashboard();
        
        add_action('admin_menu', [$admin, 'register_menu']);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_styles']);
        add_action('admin_enqueue_scripts', [$admin, 'enqueue_scripts']);
    }

    /**
     * Define frontend hooks
     */
    private function define_frontend_hooks() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_scripts']);
        add_filter('template_include', [$this, 'load_frontend_template']);
    }

    /**
     * Register REST API routes
     */
    private function register_rest_routes() {
        add_action('rest_api_init', function() {
            $routes = new Routes();
            $routes->register();
        });
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_scripts() {
        // Main stylesheet
        wp_enqueue_style(
            'mge-style',
            MGE_PLUGIN_URL . 'assets/css/style.css',
            [],
            $this->version
        );

        // Reader stylesheet
        wp_enqueue_style(
            'mge-reader',
            MGE_PLUGIN_URL . 'assets/css/reader.css',
            ['mge-style'],
            $this->version
        );

        // Accessibility stylesheet
        wp_enqueue_style(
            'mge-accessibility',
            MGE_PLUGIN_URL . 'assets/css/accessibility.css',
            ['mge-style'],
            $this->version
        );

        // Responsive stylesheet
        wp_enqueue_style(
            'mge-responsive',
            MGE_PLUGIN_URL . 'assets/css/responsive.css',
            ['mge-style'],
            $this->version
        );

        // Main app script
        wp_enqueue_script(
            'mge-app',
            MGE_PLUGIN_URL . 'assets/js/app.js',
            [],
            $this->version,
            true
        );

        // Reader script
        wp_enqueue_script(
            'mge-reader',
            MGE_PLUGIN_URL . 'assets/js/reader.js',
            ['mge-app'],
            $this->version,
            true
        );

        // Localize script
        wp_localize_script('mge-app', 'mgeData', [
            'nonce' => wp_create_nonce('mge-nonce'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'restUrl' => rest_url('moonfires/v1'),
            'version' => $this->version,
        ]);
    }

    /**
     * Enqueue assets
     */
    private function enqueue_assets() {
        // This can be customized for different contexts
    }

    /**
     * Load frontend template
     */
    public function load_frontend_template($template) {
        // Frontend template loading logic
        return $template;
    }

    /**
     * Plugin activation
     */
    public static function activate() {
        // Create tables
        $schema = new Schema();
        $schema->create_tables();

        // Set default options
        add_option('mge_db_version', MGE_VERSION);
        add_option('mge_setup_complete', false);

        // Flush rewrites
        flush_rewrite_rules();

        // Do activation action
        do_action('mge_activated');
    }

    /**
     * Plugin deactivation
     */
    public static function deactivate() {
        // Cleanup
        flush_rewrite_rules();
        
        // Do deactivation action
        do_action('mge_deactivated');
    }
}
