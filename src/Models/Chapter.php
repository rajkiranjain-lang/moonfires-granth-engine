<?php
namespace Moonfires\Granth\Models;

class Chapter {
    protected $table = 'mge_chapters';
    protected $id;
    protected $granth_id;
    protected $title;
    protected $description;
    protected $order;
    protected $verses_count = 0;
    protected $created_at;
    protected $updated_at;

    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_chapters';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }
}
