<?php
/**
 * Frontend Search Controller
 * Handles search functionality
 */

namespace Moonfires\Granth\Frontend;

use Moonfires\Granth\Services\SearchService;
use Moonfires\Granth\Utils\Sanitizer;

class Search {
    private $search_service;

    public function __construct() {
        $this->search_service = new SearchService();
    }

    /**
     * Render search page
     */
    public function render() {
        $query = isset($_GET['q']) ? Sanitizer::text($_GET['q']) : '';
        $type = isset($_GET['type']) ? Sanitizer::text($_GET['type']) : 'all'; // all, granths, verses, authors
        $language = isset($_GET['lang']) ? Sanitizer::text($_GET['lang']) : '';
        $page = max(1, intval($_GET['paged'] ?? 1));

        $results = [];
        $total = 0;

        if (strlen($query) >= 2) {
            $results = $this->search_service->search(
                $query,
                ['type' => $type, 'language' => $language, 'page' => $page]
            );
            $total = $this->search_service->get_total_results();
        }

        return $this->render_template('pages/search', [
            'query' => $query,
            'type' => $type,
            'language' => $language,
            'results' => $results,
            'total' => $total,
            'page' => $page,
        ]);
    }

    /**
     * Render template
     */
    private function render_template($template, $data = []) {
        extract($data);
        ob_start();
        include MGE_PLUGIN_DIR . "/templates/{$template}.php";
        return ob_get_clean();
    }
}
