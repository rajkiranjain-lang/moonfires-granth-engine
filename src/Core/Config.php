<?php
/**
 * Configuration Manager
 * Handles all plugin configuration
 */

namespace Moonfires\Granth\Core;

class Config {
    private $config = [];

    public function __construct() {
        $this->load_config();
    }

    /**
     * Load configuration
     */
    private function load_config() {
        $this->config = [
            'db_version' => MGE_VERSION,
            'min_php' => MGE_MINIMUM_PHP_VERSION,
            'min_wp' => MGE_MINIMUM_WP_VERSION,
            'tables' => [
                'granths' => 'mge_granths',
                'chapters' => 'mge_chapters',
                'verses' => 'mge_verses',
                'bookmarks' => 'mge_bookmarks',
                'highlights' => 'mge_highlights',
                'collections' => 'mge_collections',
                'reading_progress' => 'mge_reading_progress',
            ],
            'capabilities' => [
                'manage_granths' => 'manage_granths',
                'view_granths' => 'read',
            ],
        ];
    }

    /**
     * Get configuration value
     */
    public function get($key, $default = null) {
        return $this->config[$key] ?? $default;
    }

    /**
     * Set configuration value
     */
    public function set($key, $value) {
        $this->config[$key] = $value;
    }
}
