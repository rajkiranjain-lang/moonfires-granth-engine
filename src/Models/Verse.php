<?php
namespace Moonfires\Granth\Models;

class Verse {
    protected $table = 'mge_verses';
    protected $id;
    protected $chapter_id;
    protected $verse_number;
    protected $content;
    protected $transliteration;
    protected $translation;
    protected $commentary;
    protected $created_at;
    protected $updated_at;

    public static function find($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $id));
    }
}
