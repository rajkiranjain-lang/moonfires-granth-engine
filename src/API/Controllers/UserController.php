<?php
namespace Moonfires\Granth\API\Controllers;

use Moonfires\Granth\Models\Bookmark;

class UserController {
    public function get_bookmarks($request) {
        $user_id = get_current_user_id();
        $bookmarks = Bookmark::for_user($user_id);

        return rest_ensure_response([
            'success' => true,
            'data' => $bookmarks,
            'total' => count($bookmarks ?? []),
        ]);
    }

    public function add_bookmark($request) {
        $user_id = get_current_user_id();
        $params = $request->get_json_params();
        $chapter_id = $params['chapter_id'] ?? null;
        $note = sanitize_text_field($params['note'] ?? '');

        if (!$chapter_id) {
            return rest_ensure_response([
                'success' => false,
                'message' => __('Chapter ID required', 'moonfires-granth'),
            ], 400);
        }

        global $wpdb;
        $wpdb->insert($wpdb->prefix . 'mge_bookmarks', [
            'user_id' => $user_id,
            'chapter_id' => $chapter_id,
            'note' => $note,
            'created_at' => current_time('mysql'),
        ]);

        return rest_ensure_response([
            'success' => true,
            'message' => __('Bookmark added', 'moonfires-granth'),
        ]);
    }
}
