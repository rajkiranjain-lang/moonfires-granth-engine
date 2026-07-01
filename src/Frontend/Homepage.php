<?php
/**
 * Frontend Homepage Controller
 * Handles the main library homepage display
 */

namespace Moonfires\Granth\Frontend;

use Moonfires\Granth\Models\Granth;
use Moonfires\Granth\Models\ReadingProgress;
use Moonfires\Granth\Services\SearchService;
use Moonfires\Granth\Utils\Cache;

class Homepage {
    private $search_service;
    private $cache;

    public function __construct() {
        $this->search_service = new SearchService();
        $this->cache = new Cache();
    }

    /**
     * Render homepage
     */
    public function render() {
        // Get featured granths
        $featured = $this->cache->remember('granths_featured', 60, function() {
            return Granth::where('featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        });

        // Get recently added
        $recently_added = $this->cache->remember('granths_recent', 30, function() {
            return Granth::orderBy('created_at', 'desc')
                ->limit(8)
                ->get();
        });

        // Get popular granths
        $popular = $this->cache->remember('granths_popular', 60, function() {
            return Granth::orderBy('views', 'desc')
                ->limit(8)
                ->get();
        });

        // Get daily featured verse
        $daily_verse = $this->get_daily_verse();

        // User reading progress if logged in
        $reading_progress = [];
        if (is_user_logged_in()) {
            $reading_progress = ReadingProgress::where('user_id', get_current_user_id())
                ->orderBy('updated_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Render template
        return $this->render_template('pages/homepage', [
            'featured' => $featured,
            'recently_added' => $recently_added,
            'popular' => $popular,
            'daily_verse' => $daily_verse,
            'reading_progress' => $reading_progress,
        ]);
    }

    /**
     * Get daily featured verse
     */
    private function get_daily_verse() {
        $day = date('z'); // Day of year
        $total_verses = wp_cache_get('total_verses_count');
        
        if (!$total_verses) {
            global $wpdb;
            $total_verses = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}mge_verses");
            wp_cache_set('total_verses_count', $total_verses, '', 86400);
        }

        $verse_id = ($day % max($total_verses, 1)) + 1;
        
        return $this->cache->remember('daily_verse_' . $day, 86400, function() use ($verse_id) {
            return Verse::with(['chapter', 'chapter.granth'])->find($verse_id);
        });
    }

    /**
     * Render template with data
     */
    private function render_template($template, $data = []) {
        extract($data);
        ob_start();
        include MGE_PLUGIN_DIR . "/templates/{$template}.php";
        return ob_get_clean();
    }
}
