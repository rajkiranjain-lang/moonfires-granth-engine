<?php

namespace Moonfires\Granth\Utils;

/**
 * Input Sanitizer
 * 
 * @package Moonfires\Granth\Utils
 */
class Sanitizer {
    
    /**
     * Sanitize text
     */
    public static function text($input) {
        return sanitize_text_field($input);
    }
    
    /**
     * Sanitize HTML
     */
    public static function html($input) {
        return wp_kses_post($input);
    }
    
    /**
     * Sanitize email
     */
    public static function email($input) {
        return sanitize_email($input);
    }
    
    /**
     * Sanitize URL
     */
    public static function url($input) {
        return esc_url($input);
    }
    
    /**
     * Sanitize slug
     */
    public static function slug($input) {
        return sanitize_title($input);
    }
    
    /**
     * Sanitize integer
     */
    public static function int($input) {
        return intval($input);
    }
    
    /**
     * Sanitize array
     */
    public static function array($input) {
        if (!is_array($input)) {
            return [];
        }
        
        return array_map(function($item) {
            if (is_array($item)) {
                return self::array($item);
            }
            return self::text($item);
        }, $input);
    }
    
    /**
     * Sanitize verse data
     */
    public static function verse($data) {
        return [
            'granth_id' => self::int($data['granth_id'] ?? 0),
            'chapter_id' => self::int($data['chapter_id'] ?? 0),
            'verse_number' => self::text($data['verse_number'] ?? ''),
            'original_text' => self::html($data['original_text'] ?? ''),
            'translation' => self::html($data['translation'] ?? ''),
            'transliteration' => self::text($data['transliteration'] ?? ''),
            'commentary' => self::html($data['commentary'] ?? ''),
        ];
    }
}
