<?php

namespace Moonfires\Granth\Core;

/**
 * Configuration Manager
 * 
 * @package Moonfires\Granth\Core
 */
class Config {
    
    /**
     * Get all configuration
     */
    public function get_all() {
        return [
            'site_name' => $this->get('mge_site_name'),
            'site_color' => $this->get('mge_site_color'),
            'items_per_page' => (int) $this->get('mge_items_per_page', 20),
            'cache_duration' => (int) $this->get('mge_cache_duration', 3600),
            'enable_community' => (bool) $this->get('mge_enable_community', 1),
            'enable_audio' => (bool) $this->get('mge_enable_audio', 0),
            'enable_pdf' => (bool) $this->get('mge_enable_pdf', 1),
        ];
    }
    
    /**
     * Get configuration value
     */
    public function get($key, $default = '') {
        return get_option($key, $default);
    }
    
    /**
     * Set configuration value
     */
    public function set($key, $value) {
        return update_option($key, $value);
    }
    
    /**
     * Delete configuration value
     */
    public function delete($key) {
        return delete_option($key);
    }
}
