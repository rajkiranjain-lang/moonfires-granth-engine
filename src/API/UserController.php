<?php

namespace Moonfires\Granth\API;

use Moonfires\Granth\Models\Bookmark;
use Moonfires\Granth\Utils\Logger;

/**
 * User API Controller
 * 
 * @package Moonfires\Granth\API
 */
class UserController extends Controller {
    
    /**
     * Register routes
     */
    public function register_routes() {
        // Get user library
        register_rest_route($this->namespace, '/user/library', [
            'methods' => 'GET',
            'callback' => [$this, 'get_library'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Add bookmark
        register_rest_route($this->namespace, '/user/bookmarks', [
            'methods' => 'POST',
            'callback' => [$this, 'add_bookmark'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Remove bookmark
        register_rest_route($this->namespace, '/user/bookmarks/(?P<verse_id>\d+)', [
            'methods' => 'DELETE',
            'callback' => [$this, 'remove_bookmark'],
            'permission_callback' => [$this, 'can_read'],
        ]);
        
        // Get user bookmarks
        register_rest_route($this->namespace, '/user/bookmarks', [
            'methods' => 'GET',
            'callback' => [$this, 'get_bookmarks'],
            'permission_callback' => [$this, 'can_read'],
        ]);
    }
    
    /**
     * Get user library
     */
    public function get_library($request) {
        try {
            $user_id = get_current_user_id();
            
            if (!$user_id) {
                return $this->error('Not authenticated', 'NOT_AUTHENTICATED', 401);
            }
            
            $bookmarks = Bookmark::userBookmarks($user_id, 50, 1);
            
            return $this->success([
                'bookmarks' => $bookmarks,
            ], 'User library retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error getting user library', ['error' => $e->getMessage()]);
            return $this->error('Failed to get library', 'GET_ERROR', 500);
        }
    }
    
    /**
     * Add bookmark
     */
    public function add_bookmark($request) {
        try {
            $user_id = get_current_user_id();
            
            if (!$user_id) {
                return $this->error('Not authenticated', 'NOT_AUTHENTICATED', 401);
            }
            
            $verse_id = $request->get_param('verse_id');
            $note = $request->get_param('note');
            $color = $request->get_param('color') ?? 'yellow';
            
            if (!$verse_id) {
                return $this->error('Verse ID is required', 'VALIDATION_ERROR', 422);
            }
            
            $bookmark_id = Bookmark::create($user_id, $verse_id, [
                'note' => $note,
                'color' => $color,
            ]);
            
            Logger::info('Bookmark added', ['user_id' => $user_id, 'verse_id' => $verse_id]);
            
            return $this->success([
                'bookmark_id' => $bookmark_id,
            ], 'Bookmark added successfully', 201);
        } catch (\Exception $e) {
            Logger::error('Error adding bookmark', ['error' => $e->getMessage()]);
            return $this->error('Failed to add bookmark', 'CREATE_ERROR', 500);
        }
    }
    
    /**
     * Remove bookmark
     */
    public function remove_bookmark($request) {
        try {
            $user_id = get_current_user_id();
            
            if (!$user_id) {
                return $this->error('Not authenticated', 'NOT_AUTHENTICATED', 401);
            }
            
            $verse_id = intval($request->get_param('verse_id'));
            
            Bookmark::delete($user_id, $verse_id);
            
            Logger::info('Bookmark removed', ['user_id' => $user_id, 'verse_id' => $verse_id]);
            
            return $this->success([], 'Bookmark removed successfully');
        } catch (\Exception $e) {
            Logger::error('Error removing bookmark', ['error' => $e->getMessage()]);
            return $this->error('Failed to remove bookmark', 'DELETE_ERROR', 500);
        }
    }
    
    /**
     * Get user bookmarks
     */
    public function get_bookmarks($request) {
        try {
            $user_id = get_current_user_id();
            
            if (!$user_id) {
                return $this->error('Not authenticated', 'NOT_AUTHENTICATED', 401);
            }
            
            $per_page = intval($request->get_param('per_page') ?? 50);
            $page = intval($request->get_param('page') ?? 1);
            
            $bookmarks = Bookmark::userBookmarks($user_id, $per_page, $page);
            
            return $this->success($bookmarks, 'Bookmarks retrieved successfully');
        } catch (\Exception $e) {
            Logger::error('Error getting bookmarks', ['error' => $e->getMessage()]);
            return $this->error('Failed to get bookmarks', 'GET_ERROR', 500);
        }
    }
}
