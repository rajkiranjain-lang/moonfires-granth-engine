<?php

namespace Moonfires\Granth\Core;

use Moonfires\Granth\Core\Loader;
use Moonfires\Granth\Core\Config;
use Moonfires\Granth\Admin\Dashboard;
use Moonfires\Granth\Frontend\Homepage;
use Moonfires\Granth\API\GranthController;

/**
 * Main Plugin Class
 * 
 * @package Moonfires\Granth\Core
 */
class Plugin {
    use Singleton;
    
    /**
     * Plugin version
     */
    private $version = '1.0.0';
    
    /**
     * Loader instance
     */
    private $loader;
    
    /**
     * Config instance
     */
    private $config;
    
    /**
     * Initialize plugin
     */
    public function init() {
        $this->load_dependencies();
        $this->set_locale();
        $this->register_hooks();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
        $this->register_rest_routes();
    }
    
    /**
     * Load dependencies
     */
    private function load_dependencies() {
        require_once MGE_PLUGIN_DIR . '/src/Core/Loader.php';
        require_once MGE_PLUGIN_DIR . '/src/Core/Config.php';
        require_once MGE_PLUGIN_DIR . '/src/Database/Migration.php';
        require_once MGE_PLUGIN_DIR . '/src/Utils/Sanitizer.php';
        require_once MGE_PLUGIN_DIR . '/src/Utils/Logger.php';
        
        $this->loader = new Loader();
        $this->config = new Config();
    }
    
    /**
     * Set locale
     */
    private function set_locale() {
        load_plugin_textdomain(
            'moonfires-granth',
            false,
            MGE_PLUGIN_DIR . '/languages'
        );
    }
    
    /**
     * Register core hooks
     */
    private function register_hooks() {
        $this->loader->add_action('wp_enqueue_scripts', $this, 'enqueue_frontend_assets');
        $this->loader->add_action('admin_enqueue_scripts', $this, 'enqueue_admin_assets');
        $this->loader->add_filter('wp_footer', $this, 'render_modals');
        $this->loader->run();
    }
    
    /**
     * Define admin hooks
     */
    private function define_admin_hooks() {
        if (!class_exists('Moonfires\\Granth\\Admin\\Dashboard')) {
            require_once MGE_PLUGIN_DIR . '/src/Admin/Dashboard.php';
        }
        
        $admin = new Dashboard();
        $this->loader->add_action('admin_menu', $admin, 'add_menu_page');
        $this->loader->add_action('admin_init', $admin, 'register_settings');
    }
    
    /**
     * Define frontend hooks
     */
    private function define_frontend_hooks() {
        if (!class_exists('Moonfires\\Granth\\Frontend\\Homepage')) {
            require_once MGE_PLUGIN_DIR . '/src/Frontend/Homepage.php';
        }
        
        $frontend = new Homepage();
        $this->loader->add_action('wp_enqueue_scripts', $frontend, 'enqueue_styles');
        $this->loader->add_action('wp_footer', $frontend, 'render_templates');
    }
    
    /**
     * Register REST routes
     */
    private function register_rest_routes() {
        add_action('rest_api_init', function() {
            // Register all API controllers
            if (class_exists('Moonfires\\Granth\\API\\GranthController')) {
                $controller = new GranthController();
                $controller->register_routes();
            }
        });
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style(
            'moonfires-granth-frontend',
            MGE_PLUGIN_URL . 'assets/css/style.css',
            [],
            MGE_VERSION
        );
        
        wp_enqueue_script(
            'moonfires-granth-app',
            MGE_PLUGIN_URL . 'assets/js/app.js',
            ['wp-api', 'wp-i18n'],
            MGE_VERSION,
            true
        );
        
        wp_localize_script('moonfires-granth-app', 'MGE', [
            'apiUrl' => rest_url('moonfires/v1/'),
            'nonce' => wp_create_nonce('wp_rest'),
            'version' => MGE_VERSION,
            'config' => $this->config->get_all(),
        ]);
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'granth') === false) {
            return;
        }
        
        wp_enqueue_style(
            'moonfires-granth-admin',
            MGE_PLUGIN_URL . 'assets/css/admin.css',
            [],
            MGE_VERSION
        );
        
        wp_enqueue_script(
            'moonfires-granth-admin',
            MGE_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery', 'wp-api'],
            MGE_VERSION,
            true
        );
    }
    
    /**
     * Render modals
     */
    public function render_modals() {
        if (!is_user_logged_in()) {
            return;
        }
        ?>
        <div id="mge-bookmark-modal" class="mge-modal hidden"></div>
        <div id="mge-collection-modal" class="mge-modal hidden"></div>
        <div id="mge-share-modal" class="mge-modal hidden"></div>
        <?php
    }
    
    /**
     * Activation
     */
    public static function activate() {
        if (!get_option('mge_db_version')) {
            self::setup_database();
            self::setup_roles();
            self::setup_defaults();
            add_option('mge_db_version', MGE_VERSION);
        }
        
        flush_rewrite_rules();
        do_action('mge_activated');
    }
    
    /**
     * Setup database tables
     */
    private static function setup_database() {
        require_once MGE_PLUGIN_DIR . '/src/Database/Migration.php';
        $migration = new \Moonfires\Granth\Database\Migration();
        $migration->up();
    }
    
    /**
     * Setup roles and capabilities
     */
    private static function setup_roles() {
        $capabilities = [
            'read_granth',
            'edit_granths',
            'delete_granths',
            'manage_granth_imports',
        ];
        
        $role = get_role('editor');
        if ($role) {
            foreach ($capabilities as $cap) {
                $role->add_cap($cap);
            }
        }
        
        $admin = get_role('administrator');
        if ($admin) {
            foreach ($capabilities as $cap) {
                $admin->add_cap($cap);
            }
        }
    }
    
    /**
     * Setup default options
     */
    private static function setup_defaults() {
        $defaults = [
            'mge_site_name' => 'Granth Library',
            'mge_site_color' => '#D4A574',
            'mge_items_per_page' => 20,
            'mge_cache_duration' => 3600,
            'mge_enable_community' => 1,
            'mge_enable_audio' => 0,
            'mge_enable_pdf' => 1,
        ];
        
        foreach ($defaults as $key => $value) {
            if (!get_option($key)) {
                add_option($key, $value);
            }
        }
    }
    
    /**
     * Deactivation
     */
    public static function deactivate() {
        flush_rewrite_rules();
        do_action('mge_deactivated');
    }
}
