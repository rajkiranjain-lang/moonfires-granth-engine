<?php
/**
 * Admin Dashboard
 * Main admin interface
 */

namespace Moonfires\Granth\Admin;

class Dashboard {
    /**
     * Register admin menu
     */
    public function register_menu() {
        // Main menu
        add_menu_page(
            __('Granth Engine', 'moonfires-granth'),
            __('Granth Engine', 'moonfires-granth'),
            'manage_options',
            'mge-dashboard',
            [$this, 'render_dashboard'],
            MGE_PLUGIN_URL . 'assets/images/menu-icon.svg',
            30
        );

        // Dashboard submenu
        add_submenu_page(
            'mge-dashboard',
            __('Dashboard', 'moonfires-granth'),
            __('Dashboard', 'moonfires-granth'),
            'manage_options',
            'mge-dashboard',
            [$this, 'render_dashboard']
        );

        // Granths submenu
        add_submenu_page(
            'mge-dashboard',
            __('Manage Granths', 'moonfires-granth'),
            __('Manage Granths', 'moonfires-granth'),
            'manage_options',
            'mge-granths',
            [$this, 'render_granths']
        );

        // Import submenu
        add_submenu_page(
            'mge-dashboard',
            __('Import', 'moonfires-granth'),
            __('Import', 'moonfires-granth'),
            'manage_options',
            'mge-import',
            [$this, 'render_import']
        );

        // Settings submenu
        add_submenu_page(
            'mge-dashboard',
            __('Settings', 'moonfires-granth'),
            __('Settings', 'moonfires-granth'),
            'manage_options',
            'mge-settings',
            [$this, 'render_settings']
        );
    }

    /**
     * Render dashboard
     */
    public function render_dashboard() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Granth Engine Dashboard', 'moonfires-granth'); ?></h1>
            
            <div class="mge-dashboard-grid">
                <div class="mge-dashboard-card">
                    <h3><?php esc_html_e('Total Granths', 'moonfires-granth'); ?></h3>
                    <p class="mge-stat">0</p>
                </div>
                
                <div class="mge-dashboard-card">
                    <h3><?php esc_html_e('Total Verses', 'moonfires-granth'); ?></h3>
                    <p class="mge-stat">0</p>
                </div>
                
                <div class="mge-dashboard-card">
                    <h3><?php esc_html_e('Active Users', 'moonfires-granth'); ?></h3>
                    <p class="mge-stat">0</p>
                </div>
                
                <div class="mge-dashboard-card">
                    <h3><?php esc_html_e('Total Bookmarks', 'moonfires-granth'); ?></h3>
                    <p class="mge-stat">0</p>
                </div>
            </div>
            
            <div class="mge-dashboard-actions">
                <a href="<?php echo admin_url('admin.php?page=mge-import'); ?>" class="button button-primary">
                    <?php esc_html_e('Import Granths', 'moonfires-granth'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=mge-settings'); ?>" class="button">
                    <?php esc_html_e('Settings', 'moonfires-granth'); ?>
                </a>
            </div>
        </div>
        <?php
    }

    /**
     * Render granths page
     */
    public function render_granths() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Manage Granths', 'moonfires-granth'); ?></h1>
            <p><?php esc_html_e('Manage all granths in your library.', 'moonfires-granth'); ?></p>
        </div>
        <?php
    }

    /**
     * Render import page
     */
    public function render_import() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Import Granths', 'moonfires-granth'); ?></h1>
            
            <form method="post" enctype="multipart/form-data">
                <?php wp_nonce_field('mge_import_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th><label for="import_file"><?php esc_html_e('Select File', 'moonfires-granth'); ?></label></th>
                        <td>
                            <input type="file" id="import_file" name="import_file" accept=".json,.csv" required>
                            <p class="description"><?php esc_html_e('Upload JSON or CSV file', 'moonfires-granth'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Import', 'moonfires-granth')); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render settings page
     */
    public function render_settings() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Settings', 'moonfires-granth'); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields('mge_settings'); ?>
                <?php do_settings_sections('mge_settings'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_styles() {
        wp_enqueue_style(
            'mge-admin',
            MGE_PLUGIN_URL . 'assets/css/admin.css',
            [],
            MGE_VERSION
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_scripts() {
        wp_enqueue_script(
            'mge-admin',
            MGE_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            MGE_VERSION
        );
    }
}
