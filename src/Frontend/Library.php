<?php
/**
 * Frontend Library Controller
 * Handles user personal library
 */

namespace Moonfires\Granth\Frontend;

use Moonfires\Granth\Models\Bookmark;
use Moonfires\Granth\Models\Highlight;
use Moonfires\Granth\Models\Collection;
use Moonfires\Granth\Models\ReadingProgress;

class Library {
    private $user_id;

    public function __construct() {
        $this->user_id = get_current_user_id();
        
        if (!$this->user_id) {
            wp_safe_remote_redirect(wp_login_url());
            exit;
        }
    }

    /**
     * Render user library
     */
    public function render($tab = 'bookmarks') {
        $data = [
            'tab' => $tab,
            'user_id' => $this->user_id,
        ];

        switch ($tab) {
            case 'bookmarks':
                $data['bookmarks'] = Bookmark::where('user_id', $this->user_id)
                    ->with(['chapter', 'chapter.granth'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                break;

            case 'highlights':
                $data['highlights'] = Highlight::where('user_id', $this->user_id)
                    ->with(['verse', 'verse.chapter'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                break;

            case 'collections':
                $data['collections'] = Collection::where('user_id', $this->user_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
                break;

            case 'reading-history':
            default:
                $data['reading_progress'] = ReadingProgress::where('user_id', $this->user_id)
                    ->with(['granth', 'chapter'])
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
        }

        return $this->render_template('pages/library', $data);
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
