<?php

namespace Moonfires\Granth\Models;

/**
 * Verse Model
 * 
 * @package Moonfires\Granth\Models
 */
class Verse {
    
    protected $table = 'mge_verses';
    
    /**
     * Get verse by ID
     */
    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Get verses by chapter
     */
    public static function byChapter($chapter_id, $per_page = 10, $page = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        
        $offset = ($page - 1) * $per_page;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE chapter_id = %d ORDER BY position ASC LIMIT %d OFFSET %d",
            $chapter_id,
            $per_page,
            $offset
        ));
    }
    
    /**
     * Search verses
     */
    public static function search($query, $per_page = 20, $page = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        
        $offset = ($page - 1) * $per_page;
        $search_term = '%' . $wpdb->esc_like($query) . '%';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE original_text LIKE %s OR translation LIKE %s OR transliteration LIKE %s LIMIT %d OFFSET %d",
            $search_term,
            $search_term,
            $search_term,
            $per_page,
            $offset
        ));
    }
    
    /**
     * Create
     */
    public static function create($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        
        $wpdb->insert($table, [
            'granth_id' => $data['granth_id'],
            'chapter_id' => $data['chapter_id'],
            'verse_number' => $data['verse_number'],
            'original_text' => $data['original_text'],
            'translation' => $data['translation'] ?? '',
            'position' => $data['position'] ?? 0,
            'created_at' => current_time('mysql'),
        ]);
        
        return self::find($wpdb->insert_id);
    }
}
