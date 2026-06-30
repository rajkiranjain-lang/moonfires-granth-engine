<?php
/**
 * Frontend Browse Controller
 * Handles browsing and filtering granths
 */

namespace Moonfires\Granth\Frontend;

use Moonfires\Granth\Models\Granth;
use Moonfires\Granth\Utils\Sanitizer;

class Browse {
    /**
     * Render browse page
     */
    public function render() {
        $category = isset($_GET['category']) ? Sanitizer::text($_GET['category']) : '';
        $language = isset($_GET['language']) ? Sanitizer::text($_GET['language']) : '';
        $tradition = isset($_GET['tradition']) ? Sanitizer::text($_GET['tradition']) : '';
        $sort = isset($_GET['sort']) ? Sanitizer::text($_GET['sort']) : 'popular'; // popular, newest, alphabetical
        $page = max(1, intval($_GET['paged'] ?? 1));

        // Build query
        $query = Granth::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($language) {
            $query->where('language', $language);
        }

        if ($tradition) {
            $query->where('tradition', $tradition);
        }

        // Apply sorting
        switch ($sort) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'alphabetical':
                $query->orderBy('title', 'asc');
                break;
            case 'popular':
            default:
                $query->orderBy('views', 'desc');
        }

        $granths = $query->paginate(20, ['*'], 'paged', $page);

        // Get available filters
        $categories = Granth::distinct('category')->pluck('category');
        $languages = Granth::distinct('language')->pluck('language');
        $traditions = Granth::distinct('tradition')->pluck('tradition');

        return $this->render_template('pages/browse', [
            'granths' => $granths,
            'categories' => $categories,
            'languages' => $languages,
            'traditions' => $traditions,
            'selected_category' => $category,
            'selected_language' => $language,
            'selected_tradition' => $tradition,
            'sort' => $sort,
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
