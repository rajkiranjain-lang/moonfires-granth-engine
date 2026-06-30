<?php

namespace Moonfires\Granth\Models;

/**
 * Bookmark Model
 * 
 * @package Moonfires\Granth\Models
 */
class Bookmark {
    
    protected $table = 'mge_user_bookmarks';
    
    /**
     * Get user bookmarks
     */
    public static function userBookmarks($user_id, $per_page = 50, $page = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_user_bookmarks';
        
        $offset = ($page - 1) * $per_page;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE user_id = %d ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $user_id,
            $per_page,
            $offset
        ));
    }
    
    /**
     * Create bookmark
     */
    public static function create($user_id, $verse_id, $data = []) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_user_bookmarks';
        
        $wpdb->insert($table, [
            'user_id' => $user_id,
            'verse_id' => $verse_id,
            'note' => $data['note'] ?? '',
            'color' => $data['color'] ?? 'yellow',
            'created_at' => current_time('mysql'),
        ]);
        
        return $wpdb->insert_id;
    }
    
    /**
     * Delete bookmark
     */
    public static function delete($user_id, $verse_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_user_bookmarks';
        
        return $wpdb->delete($table, [
            'user_id' => $user_id,
            'verse_id' => $verse_id,
        ]);
    }
}
