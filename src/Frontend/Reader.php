<?php
/**
 * Frontend Reader Controller
 * Handles scripture reading interface
 */

namespace Moonfires\Granth\Frontend;

use Moonfires\Granth\Models\Granth;
use Moonfires\Granth\Models\Chapter;
use Moonfires\Granth\Models\Verse;
use Moonfires\Granth\Models\ReadingProgress;
use Moonfires\Granth\Models\Bookmark;
use Moonfires\Granth\Utils\Sanitizer;

class Reader {
    private $granth;
    private $chapter;
    private $current_user_id;

    public function __construct() {
        $this->current_user_id = get_current_user_id();
    }

    /**
     * Render reader interface
     */
    public function render($granth_id, $chapter_id = null, $verse_id = null) {
        // Validate and load granth
        $this->granth = Granth::findOrFail($granth_id);
        
        // Get reading mode (chapter, verse, continuous, parallel)
        $reading_mode = get_user_meta($this->current_user_id, 'mge_reading_mode', true) ?: 'verse';
        
        // Load chapter
        if ($chapter_id) {
            $this->chapter = Chapter::where('granth_id', $granth_id)
                ->where('id', $chapter_id)
                ->firstOrFail();
        } else {
            $this->chapter = Chapter::where('granth_id', $granth_id)
                ->orderBy('order', 'asc')
                ->first();
        }

        // Get verses with pagination/continuation
        $verses = $this->get_verses($verse_id, $reading_mode);

        // Track reading progress
        $this->track_reading($verse_id ?? $verses->first()->id);

        // Get user bookmarks and highlights
        $bookmarks = Bookmark::where('user_id', $this->current_user_id)
            ->where('chapter_id', $this->chapter->id)
            ->get();

        // Get related granths
        $related = $this->granth->get_related_granths(5);

        return $this->render_template('reader/full-screen', [
            'granth' => $this->granth,
            'chapter' => $this->chapter,
            'verses' => $verses,
            'bookmarks' => $bookmarks,
            'related' => $related,
            'reading_mode' => $reading_mode,
        ]);
    }

    /**
     * Get verses based on reading mode
     */
    private function get_verses($starting_verse_id = null, $mode = 'verse') {
        $query = Verse::where('chapter_id', $this->chapter->id)
            ->orderBy('verse_number', 'asc');

        if ($starting_verse_id && $mode === 'verse') {
            $query->where('id', '>=', $starting_verse_id);
        }

        return $query->paginate($mode === 'continuous' ? 100 : 10);
    }

    /**
     * Track user reading progress
     */
    private function track_reading($verse_id) {
        if (!$this->current_user_id) {
            return;
        }

        ReadingProgress::updateOrCreate(
            [
                'user_id' => $this->current_user_id,
                'granth_id' => $this->granth->id,
            ],
            [
                'chapter_id' => $this->chapter->id,
                'verse_id' => $verse_id,
                'updated_at' => current_time('mysql'),
            ]
        );
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
