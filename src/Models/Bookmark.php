<?php
namespace Moonfires\Granth\Models;

class Bookmark {
    protected $table = 'mge_bookmarks';
    protected $id;
    protected $user_id;
    protected $chapter_id;
    protected $note;
    protected $created_at;
    protected $updated_at;

    public static function for_user($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_bookmarks';
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC",
            $user_id
        ));
    }
}
