<?php

namespace Moonfires\Granth\Models;

/**
 * Chapter Model
 * 
 * @package Moonfires\Granth\Models
 */
class Chapter {
    
    protected $table = 'mge_chapters';
    
    /**
     * Get chapter by ID
     */
    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_chapters';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Get chapters for granth
     */
    public static function forGranth($granth_id, $per_page = 50, $page = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_chapters';
        
        $offset = ($page - 1) * $per_page;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE granth_id = %d AND parent_chapter_id IS NULL ORDER BY chapter_number ASC LIMIT %d OFFSET %d",
            $granth_id,
            $per_page,
            $offset
        ));
    }
    
    /**
     * Get verses
     */
    public function verses($per_page = 10, $page = 1) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        
        $offset = ($page - 1) * $per_page;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE chapter_id = %d ORDER BY position ASC LIMIT %d OFFSET %d",
            $this->id,
            $per_page,
            $offset
        ));
    }
    
    /**
     * Create
     */
    public static function create($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_chapters';
        
        $wpdb->insert($table, [
            'granth_id' => $data['granth_id'],
            'title' => $data['title'],
            'slug' => sanitize_title($data['title']),
            'chapter_number' => $data['chapter_number'],
            'created_at' => current_time('mysql'),
        ]);
        
        return self::find($wpdb->insert_id);
    }
}
