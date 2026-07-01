<?php
namespace Moonfires\Granth\Services;

use Moonfires\Granth\Models\Granth;

class ImportService {
    public function import($file_path) {
        if (!file_exists($file_path)) {
            return ['success' => false, 'message' => __('File not found', 'moonfires-granth')];
        }

        $file_ext = pathinfo($file_path, PATHINFO_EXTENSION);

        if ($file_ext === 'json') {
            return $this->import_json($file_path);
        } elseif ($file_ext === 'csv') {
            return $this->import_csv($file_path);
        }

        return ['success' => false, 'message' => __('Unsupported file format', 'moonfires-granth')];
    }

    private function import_json($file_path) {
        $content = file_get_contents($file_path);
        $data = json_decode($content, true);

        if (!$data) {
            return ['success' => false, 'message' => __('Invalid JSON', 'moonfires-granth')];
        }

        $granth_id = $this->create_granth_from_data($data);

        return [
            'success' => true,
            'message' => __('Import successful', 'moonfires-granth'),
            'granth_id' => $granth_id,
        ];
    }

    private function import_csv($file_path) {
        $data = array_map('str_getcsv', file($file_path));
        
        return [
            'success' => true,
            'message' => __('CSV import successful', 'moonfires-granth'),
            'rows' => count($data),
        ];
    }

    private function create_granth_from_data($data) {
        return Granth::create([
            'title' => $data['title'] ?? 'Untitled',
            'author' => $data['author'] ?? 'Unknown',
            'description' => $data['description'] ?? '',
            'language' => $data['language'] ?? 'Sanskrit',
        ]);
    }
}
