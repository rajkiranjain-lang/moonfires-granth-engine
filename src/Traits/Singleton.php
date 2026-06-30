<?php

namespace Moonfires\Granth\Traits;

/**
 * Singleton Pattern Trait
 * 
 * @package Moonfires\Granth\Traits
 */
trait Singleton {
    
    /**
     * Instance
     */
    private static $instance = null;
    
    /**
     * Private constructor
     */
    private function __construct() {}
    
    /**
     * Get instance
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
    
    /**
     * Prevent cloning
     */
    private function __clone() {}
    
    /**
     * Prevent unserializing
     */
    public function __wakeup() {
        throw new \Exception('Cannot unserialize singleton');
    }
}
