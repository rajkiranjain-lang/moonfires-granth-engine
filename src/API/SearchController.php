<?php

namespace Moonfires\Granth\API;

use Moonfires\Granth\Models\Verse;
use Moonfires\Granth\Utils\Logger;

/**
 * Search API Controller
 * 
 * @package Moonfires\Granth\API
 */
class SearchController extends Controller {
    
    /**
     * Register routes
     */
    public function register_routes() {
        register_rest_route($this->namespace, '/search', [
            'methods' => 'GET',
            'callback' => [$this, 'search'],
            'permission_callback' => [$this, 'can_read'],
        ]);
    }
    
    /**
     * Search verses and granths
     */
    public function search($request) {
        try {
            $query = $request->get_param('q');
            
            if (!$query || strlen($query) < 2) {
                return $this->error('Query too short', 'INVALID_QUERY', 422);
            }
            
            $per_page = intval($request->get_param('per_page') ?? 20);
            $page = intval($request->get_param('page') ?? 1);
            
            // Search verses
            $verses = Verse::search($query, $per_page, $page);
            
            Logger::info('Search performed', ['query' => $query]);
            
            return $this->success([
                'verses' => $verses,
                'pagination' => [
                    'per_page' => $per_page,
                    'page' => $page,
                ],
            ], 'Search results retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error searching', ['error' => $e->getMessage()]);
            return $this->error('Search failed', 'SEARCH_ERROR', 500);
        }
    }
}
