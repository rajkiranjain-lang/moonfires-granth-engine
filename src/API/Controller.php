<?php

namespace Moonfires\Granth\API;

use Moonfires\Granth\Utils\Sanitizer;
use Moonfires\Granth\Utils\Logger;

/**
 * Base API Controller
 * 
 * @package Moonfires\Granth\API
 */
class Controller {
    
    /**
     * API namespace
     */
    protected $namespace = 'moonfires/v1';
    
    /**
     * Send success response
     */
    protected function success($data = [], $message = 'Success', $status = 200) {
        return new \WP_REST_Response([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'meta' => [
                'timestamp' => current_time('c'),
                'version' => MGE_VERSION,
            ],
        ], $status);
    }
    
    /**
     * Send error response
     */
    protected function error($message = 'Error', $code = 'ERROR', $status = 400, $details = []) {
        return new \WP_REST_Response([
            'success' => false,
            'data' => null,
            'message' => $message,
            'error' => [
                'code' => $code,
                'details' => $details,
            ],
            'meta' => [
                'timestamp' => current_time('c'),
                'version' => MGE_VERSION,
            ],
        ], $status);
    }
    
    /**
     * Check if user can read
     */
    protected function can_read($user_id = null) {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user_id = $user_id ?? get_current_user_id();
        return user_can($user_id, 'read_granth');
    }
    
    /**
     * Check if user can edit
     */
    protected function can_edit($user_id = null) {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user_id = $user_id ?? get_current_user_id();
        return user_can($user_id, 'edit_granths');
    }
    
    /**
     * Check if user can manage
     */
    protected function can_manage() {
        return is_user_logged_in() && current_user_can('manage_options');
    }
    
    /**
     * Verify nonce
     */
    protected function verify_nonce($request, $action = 'wp_rest') {
        $nonce = $request->get_header('X-WP-Nonce');
        return wp_verify_nonce($nonce, $action);
    }
}
