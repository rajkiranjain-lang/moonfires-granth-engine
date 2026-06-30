<?php

namespace Moonfires\Granth\API;

use Moonfires\Granth\Models\Granth;
use Moonfires\Granth\Models\Chapter;
use Moonfires\Granth\Models\Verse;
use Moonfires\Granth\Utils\Logger;

/**
 * Granth API Controller
 * 
 * @package Moonfires\Granth\API
 */
class GranthController extends Controller {
    
    /**
     * Register routes
     */
    public function register_routes() {
        // List granths
        register_rest_route($this->namespace, '/granths', [
            'methods' => 'GET',
            'callback' => [$this, 'list_granths'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Get single granth
        register_rest_route($this->namespace, '/granths/(?P<id>\d+)', [
            'methods' => 'GET',
            'callback' => [$this, 'get_granth'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Create granth
        register_rest_route($this->namespace, '/granths', [
            'methods' => 'POST',
            'callback' => [$this, 'create_granth'],
            'permission_callback' => [$this, 'can_edit'],
        ]);
        
        // Update granth
        register_rest_route($this->namespace, '/granths/(?P<id>\d+)', [
            'methods' => 'PUT',
            'callback' => [$this, 'update_granth'],
            'permission_callback' => [$this, 'can_edit'],
        ]);
        
        // Delete granth
        register_rest_route($this->namespace, '/granths/(?P<id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'delete_granth'],
            'permission_callback' => [$this, 'can_edit'],
        ]);
        
        // List chapters
        register_rest_route($this->namespace, '/granths/(?P<granth_id>\d+)/chapters', [
            'methods' => 'GET',
            'callback' => [$this, 'list_chapters'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // List verses
        register_rest_route($this->namespace, '/granths/(?P<granth_id>\d+)/chapters/(?P<chapter_id>\d+)/verses', [
            'methods' => 'GET',
            'callback' => [$this, 'list_verses'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Featured granths
        register_rest_route($this->namespace, '/granths/featured', [
            'methods' => 'GET',
            'callback' => [$this, 'featured_granths'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Popular granths
        register_rest_route($this->namespace, '/granths/popular', [
            'methods' => 'GET',
            'callback' => [$this, 'popular_granths'],
            'permission_callback' => [$this, 'can_read'],
        ]);
    }
    
    /**
     * List granths
     */
    public function list_granths($request) {
        try {
            $per_page = intval($request->get_param('per_page') ?? 20);
            $page = intval($request->get_param('page') ?? 1);
            $language = $request->get_param('language');
            $category = $request->get_param('category');
            
            // Validate pagination
            if ($per_page > 100) {
                $per_page = 100;
            }
            if ($page < 1) {
                $page = 1;
            }
            
            $granths = Granth::paginate($per_page, $page);
            $total = Granth::count();
            
            return $this->success([
                'granths' => $granths,
                'pagination' => [
                    'per_page' => $per_page,
                    'page' => $page,
                    'total' => $total,
                    'pages' => ceil($total / $per_page),
                ],
            ], 'Granths retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error listing granths', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve granths', 'LIST_ERROR', 500);
        }
    }
    
    /**
     * Get single granth
     */
    public function get_granth($request) {
        try {
            $id = intval($request->get_param('id'));
            $granth = Granth::find($id);
            
            if (!$granth) {
                return $this->error('Granth not found', 'NOT_FOUND', 404);
            }
            
            // Add chapters
            $granth->chapters = $granth->chapters();
            
            return $this->success($granth, 'Granth retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error getting granth', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve granth', 'GET_ERROR', 500);
        }
    }
    
    /**
     * Create granth
     */
    public function create_granth($request) {
        try {
            if (!$this->can_edit()) {
                return $this->error('Unauthorized', 'UNAUTHORIZED', 403);
            }
            
            $title = $request->get_param('title');
            $description = $request->get_param('description');
            
            if (!$title) {
                return $this->error('Title is required', 'VALIDATION_ERROR', 422);
            }
            
            $granth = Granth::create([
                'title' => $title,
                'description' => $description ?? '',
                'author_id' => $request->get_param('author_id'),
                'category_id' => $request->get_param('category_id'),
                'language' => $request->get_param('language') ?? 'en',
            ]);
            
            Logger::info('Granth created', ['granth_id' => $granth->id]);
            
            return $this->success($granth, 'Granth created successfully', 201);
        } catch (\Exception $e) {
            Logger::error('Error creating granth', ['error' => $e->getMessage()]);
            return $this->error('Failed to create granth', 'CREATE_ERROR', 500);
        }
    }
    
    /**
     * Update granth
     */
    public function update_granth($request) {
        try {
            if (!$this->can_edit()) {
                return $this->error('Unauthorized', 'UNAUTHORIZED', 403);
            }
            
            $id = intval($request->get_param('id'));
            $granth = Granth::find($id);
            
            if (!$granth) {
                return $this->error('Granth not found', 'NOT_FOUND', 404);
            }
            
            $data = [];
            if ($request->get_param('title')) {
                $data['title'] = $request->get_param('title');
            }
            if ($request->get_param('description')) {
                $data['description'] = $request->get_param('description');
            }
            if ($request->get_param('category_id')) {
                $data['category_id'] = $request->get_param('category_id');
            }
            
            $updated = $granth->update($data);
            
            Logger::info('Granth updated', ['granth_id' => $id]);
            
            return $this->success($updated, 'Granth updated successfully');
        } catch (\Exception $e) {
            Logger::error('Error updating granth', ['error' => $e->getMessage()]);
            return $this->error('Failed to update granth', 'UPDATE_ERROR', 500);
        }
    }
    
    /**
     * Delete granth
     */
    public function delete_granth($request) {
        try {
            if (!$this->can_edit()) {
                return $this->error('Unauthorized', 'UNAUTHORIZED', 403);
            }
            
            $id = intval($request->get_param('id'));
            $granth = Granth::find($id);
            
            if (!$granth) {
                return $this->error('Granth not found', 'NOT_FOUND', 404);
            }
            
            $granth->delete();
            
            Logger::info('Granth deleted', ['granth_id' => $id]);
            
            return $this->success([], 'Granth deleted successfully');
        } catch (\Exception $e) {
            Logger::error('Error deleting granth', ['error' => $e->getMessage()]);
            return $this->error('Failed to delete granth', 'DELETE_ERROR', 500);
        }
    }
    
    /**
     * List chapters
     */
    public function list_chapters($request) {
        try {
            $granth_id = intval($request->get_param('granth_id'));
            $granth = Granth::find($granth_id);
            
            if (!$granth) {
                return $this->error('Granth not found', 'NOT_FOUND', 404);
            }
            
            $chapters = $granth->chapters();
            
            return $this->success($chapters, 'Chapters retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error listing chapters', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve chapters', 'LIST_ERROR', 500);
        }
    }
    
    /**
     * List verses
     */
    public function list_verses($request) {
        try {
            $chapter_id = intval($request->get_param('chapter_id'));
            $per_page = intval($request->get_param('per_page') ?? 10);
            $page = intval($request->get_param('page') ?? 1);
            
            $chapter = Chapter::find($chapter_id);
            if (!$chapter) {
                return $this->error('Chapter not found', 'NOT_FOUND', 404);
            }
            
            $verses = Verse::byChapter($chapter_id, $per_page, $page);
            
            return $this->success($verses, 'Verses retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error listing verses', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve verses', 'LIST_ERROR', 500);
        }
    }
    
    /**
     * Featured granths
     */
    public function featured_granths($request) {
        try {
            $limit = intval($request->get_param('limit') ?? 5);
            $granths = Granth::featured($limit);
            
            return $this->success($granths, 'Featured granths retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error getting featured granths', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve featured granths', 'LIST_ERROR', 500);
        }
    }
    
    /**
     * Popular granths
     */
    public function popular_granths($request) {
        try {
            $limit = intval($request->get_param('limit') ?? 10);
            $granths = Granth::popular($limit);
            
            return $this->success($granths, 'Popular granths retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error getting popular granths', ['error' => $e->getMessage()]);
            return $this->error('Failed to retrieve popular granths', 'LIST_ERROR', 500);
        }
    }
}
