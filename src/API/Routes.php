<?php
namespace Moonfires\Granth\API;

use Moonfires\Granth\API\Controllers\GranthController;
use Moonfires\Granth\API\Controllers\SearchController;
use Moonfires\Granth\API\Controllers\UserController;

class Routes {
    public function register() {
        $this->register_granth_routes();
        $this->register_search_routes();
        $this->register_user_routes();
    }

    private function register_granth_routes() {
        $controller = new GranthController();

        register_rest_route('moonfires/v1', '/granths', [
            'methods' => 'GET',
            'callback' => [$controller, 'list'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('moonfires/v1', '/granths/(?P<id>\\d+)', [
            'methods' => 'GET',
            'callback' => [$controller, 'get'],
            'permission_callback' => '__return_true',
        ]);
    }

    private function register_search_routes() {
        $controller = new SearchController();

        register_rest_route('moonfires/v1', '/search', [
            'methods' => 'GET',
            'callback' => [$controller, 'search'],
            'permission_callback' => '__return_true',
        ]);
    }

    private function register_user_routes() {
        $controller = new UserController();

        register_rest_route('moonfires/v1', '/user/bookmarks', [
            'methods' => 'GET',
            'callback' => [$controller, 'get_bookmarks'],
            'permission_callback' => 'is_user_logged_in',
        ]);

        register_rest_route('moonfires/v1', '/user/bookmarks', [
            'methods' => 'POST',
            'callback' => [$controller, 'add_bookmark'],
            'permission_callback' => 'is_user_logged_in',
        ]);
    }
}
