<?php

namespace Moonfires\Granth\Models;

/**
 * Granth Model
 * 
 * @package Moonfires\Granth\Models
 */
class Granth {
    
    protected $table = 'mge_granths';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'author_id',
        'category_id',
        'language',
        'tradition',
        'period',
        'cover_image_id',
        'difficulty_level',
    ];
    
    /**
     * Get all granths with pagination
     */
    public static function paginate($per_page = 20, $page = 1) {
        global $wpdb;
        
        $offset = ($page - 1) * $per_page;
        $table = $wpdb->prefix . 'mge_granths';
        
        $query = $wpdb->prepare(
            "SELECT * FROM {$table} WHERE status = 'published' ORDER BY created_at DESC LIMIT %d OFFSET %d",
            $per_page,
            $offset
        );
        
        return $wpdb->get_results($query);
    }
    
    /**
     * Get total count
     */
    public static function count() {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        return (int) $wpdb->get_var("SELECT COUNT(*) FROM {$table} WHERE status = 'published'");
    }
    
    /**
     * Get by ID
     */
    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE id = %d",
            $id
        ));
    }
    
    /**
     * Get by slug
     */
    public static function findBySlug($slug) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE slug = %s AND status = 'published'",
            $slug
        ));
    }
    
    /**
     * Get featured granths
     */
    public static function featured($limit = 5) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE status = 'published' ORDER BY rating_average DESC, views_count DESC LIMIT %d",
            $limit
        ));
    }
    
    /**
     * Get popular granths
     */
    public static function popular($limit = 10) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE status = 'published' ORDER BY views_count DESC LIMIT %d",
            $limit
        ));
    }
    
    /**
     * Get chapters
     */
    public function chapters() {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_chapters';
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$table} WHERE granth_id = %d ORDER BY chapter_number ASC",
            $this->id
        ));
    }
    
    /**
     * Create
     */
    public static function create($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        $wpdb->insert($table, [
            'title' => $data['title'],
            'slug' => sanitize_title($data['title']),
            'description' => $data['description'] ?? '',
            'author_id' => $data['author_id'] ?? null,
            'category_id' => $data['category_id'] ?? null,
            'language' => $data['language'] ?? 'en',
            'created_at' => current_time('mysql'),
        ]);
        
        return self::find($wpdb->insert_id);
    }
    
    /**
     * Update
     */
    public function update($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        $wpdb->update(
            $table,
            array_merge($data, ['updated_at' => current_time('mysql')]),
            ['id' => $this->id]
        );
        
        return self::find($this->id);
    }
    
    /**
     * Delete
     */
    public function delete() {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        return $wpdb->delete($table, ['id' => $this->id]);
    }
}
