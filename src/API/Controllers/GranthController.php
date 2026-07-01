<?php
namespace Moonfires\Granth\API\Controllers;

use Moonfires\Granth\Models\Granth;

class GranthController {
    public function list($request) {
        $granths = Granth::all();
        
        return rest_ensure_response([
            'success' => true,
            'data' => $granths,
            'total' => count($granths ?? []),
        ]);
    }

    public function get($request) {
        $id = $request['id'];
        $granth = Granth::find($id);

        if (!$granth) {
            return rest_ensure_response([
                'success' => false,
                'message' => __('Granth not found', 'moonfires-granth'),
            ], 404);
        }

        return rest_ensure_response([
            'success' => true,
            'data' => $granth,
        ]);
    }
}
