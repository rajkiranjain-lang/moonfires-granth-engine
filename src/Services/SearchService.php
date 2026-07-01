<?php
namespace Moonfires\Granth\Services;

use Moonfires\Granth\Models\Granth;

class SearchService {
    public function search($query, $options = []) {
        $type = $options['type'] ?? 'all';
        $results = [];

        if ($type === 'all' || $type === 'granths') {
            $results = array_merge($results, $this->search_granths($query));
        }

        if ($type === 'all' || $type === 'verses') {
            $results = array_merge($results, $this->search_verses($query));
        }

        return $results;
    }

    private function search_granths($query) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_granths';
        $query = '%' . $wpdb->esc_like($query) . '%';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE title LIKE %s OR author LIKE %s LIMIT 20",
            $query,
            $query
        ));

        return $results ?? [];
    }

    private function search_verses($query) {
        global $wpdb;
        $table = $wpdb->prefix . 'mge_verses';
        $query = '%' . $wpdb->esc_like($query) . '%';

        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE content LIKE %s LIMIT 20",
            $query
        ));

        return $results ?? [];
    }
}
