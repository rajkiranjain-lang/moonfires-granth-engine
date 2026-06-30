<?php

namespace Moonfires\Granth\Utils;

/**
 * Logger Utility
 * 
 * @package Moonfires\Granth\Utils
 */
class Logger {
    
    /**
     * Log directory
     */
    private static $log_dir = null;
    
    /**
     * Get log directory
     */
    private static function get_log_dir() {
        if (self::$log_dir === null) {
            self::$log_dir = WP_CONTENT_DIR . '/mge-logs';
            
            if (!file_exists(self::$log_dir)) {
                wp_mkdir_p(self::$log_dir);
            }
        }
        
        return self::$log_dir;
    }
    
    /**
     * Log message
     */
    public static function log($message, $level = 'info', $context = []) {
        $log_file = self::get_log_dir() . '/' . date('Y-m-d') . '.log';
        
        $log_message = sprintf(
            "[%s] [%s] %s %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $message,
            !empty($context) ? json_encode($context) : ''
        );
        
        error_log($log_message, 3, $log_file);
    }
    
    /**
     * Log info
     */
    public static function info($message, $context = []) {
        self::log($message, 'info', $context);
    }
    
    /**
     * Log error
     */
    public static function error($message, $context = []) {
        self::log($message, 'error', $context);
    }
    
    /**
     * Log warning
     */
    public static function warning($message, $context = []) {
        self::log($message, 'warning', $context);
    }
    
    /**
     * Log debug
     */
    public static function debug($message, $context = []) {
        if (WP_DEBUG) {
            self::log($message, 'debug', $context);
        }
    }
}
