<?php
namespace Moonfires\Granth\Models;

class Granth {
    protected $table = 'mge_granths';
    protected $id;
    protected $title;
    protected $author;
    protected $description;
    protected $language;
    protected $tradition;
    protected $category;
    protected $cover_image_url;
    protected $featured = false;
    protected $views = 0;
    protected $chapters_count = 0;
    protected $created_at;
    protected $updated_at;

    public static function all() {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        return $wpdb->get_results("SELECT * FROM $table");
    }

    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }

    public static function create($data) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        
        $wpdb->insert($table, [
            'title' => $data['title'],
            'author' => $data['author'],
            'description' => $data['description'] ?? '',
            'language' => $data['language'],
            'created_at' => current_time('mysql'),
        ]);

        return $wpdb->insert_id;
    }
}
