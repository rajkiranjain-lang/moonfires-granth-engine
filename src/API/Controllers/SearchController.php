<?php
namespace Moonfires\Granth\API\Controllers;

use Moonfires\Granth\Services\SearchService;

class SearchController {
    private $search_service;

    public function __construct() {
        $this->search_service = new SearchService();
    }

    public function search($request) {
        $query = $request->get_param('q');
        
        if (strlen($query) < 2) {
            return rest_ensure_response([
                'success' => false,
                'message' => __('Query too short', 'moonfires-granth'),
            ]);
        }

        $results = $this->search_service->search($query);

        return rest_ensure_response([
            'success' => true,
            'data' => $results,
            'total' => count($results ?? []),
        ]);
    }
}
