<?php

namespace Moonfires\Granth\Database;

/**
 * Database Migration Handler
 * 
 * @package Moonfires\Granth\Database
 */
class Migration {
    
    /**
     * Run migrations
     */
    public function up() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Create granths table
        $this->create_granths_table($charset_collate);
        
        // Create chapters table
        $this->create_chapters_table($charset_collate);
        
        // Create verses table
        $this->create_verses_table($charset_collate);
        
        // Create authors table
        $this->create_authors_table($charset_collate);
        
        // Create categories table
        $this->create_categories_table($charset_collate);
        
        // Create user tables
        $this->create_user_bookmarks_table($charset_collate);
        $this->create_user_highlights_table($charset_collate);
        $this->create_user_notes_table($charset_collate);
        $this->create_reading_progress_table($charset_collate);
        $this->create_user_collections_table($charset_collate);
        $this->create_user_collection_items_table($charset_collate);
        
        // Create community tables
        $this->create_ratings_table($charset_collate);
        $this->create_comments_table($charset_collate);
        
        // Create analytics tables
        $this->create_import_logs_table($charset_collate);
        $this->create_analytics_daily_table($charset_collate);
        $this->create_search_logs_table($charset_collate);
    }
    
    /**
     * Create granths table
     */
    private function create_granths_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_granths';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description LONGTEXT,
            author_id BIGINT UNSIGNED,
            category_id BIGINT UNSIGNED,
            language VARCHAR(10) NOT NULL DEFAULT 'en',
            tradition VARCHAR(100),
            period VARCHAR(100),
            cover_image_id BIGINT UNSIGNED,
            total_chapters INT DEFAULT 0,
            total_verses BIGINT DEFAULT 0,
            difficulty_level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'intermediate',
            status ENUM('draft', 'published', 'archived') DEFAULT 'published',
            views_count INT DEFAULT 0,
            rating_average DECIMAL(3, 2) DEFAULT 0,
            rating_count INT DEFAULT 0,
            created_by BIGINT UNSIGNED,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            
            INDEX idx_slug (slug),
            INDEX idx_language (language),
            INDEX idx_category_id (category_id),
            INDEX idx_author_id (author_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            FULLTEXT INDEX ft_title_description (title, description)
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create chapters table
     */
    private function create_chapters_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_chapters';
        $granths_table = $wpdb->prefix . 'mge_granths';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            granth_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            slug VARCHAR(255) NOT NULL,
            chapter_number INT NOT NULL,
            parent_chapter_id BIGINT UNSIGNED,
            content LONGTEXT,
            content_html LONGTEXT,
            total_verses INT DEFAULT 0,
            reading_time_minutes INT DEFAULT 0,
            status ENUM('draft', 'published') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_granth_id (granth_id),
            INDEX idx_chapter_number (chapter_number),
            INDEX idx_parent_chapter_id (parent_chapter_id),
            UNIQUE KEY unique_granth_chapter (granth_id, chapter_number),
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create verses table
     */
    private function create_verses_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_verses';
        $granths_table = $wpdb->prefix . 'mge_granths';
        $chapters_table = $wpdb->prefix . 'mge_chapters';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            granth_id BIGINT UNSIGNED NOT NULL,
            chapter_id BIGINT UNSIGNED NOT NULL,
            verse_number VARCHAR(50) NOT NULL,
            original_text LONGTEXT NOT NULL,
            original_script VARCHAR(50),
            translation LONGTEXT,
            translation_language VARCHAR(10),
            transliteration VARCHAR(500),
            commentary LONGTEXT,
            meaning LONGTEXT,
            tags VARCHAR(500),
            position INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_granth_id (granth_id),
            INDEX idx_chapter_id (chapter_id),
            INDEX idx_verse_number (verse_number),
            UNIQUE KEY unique_verse_location (granth_id, chapter_id, verse_number),
            FULLTEXT INDEX ft_verse_content (original_text, translation, transliteration),
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (chapter_id) REFERENCES {$chapters_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create authors table
     */
    private function create_authors_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_authors';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            biography LONGTEXT,
            birth_year INT,
            death_year INT,
            tradition VARCHAR(100),
            photo_id BIGINT UNSIGNED,
            status ENUM('published', 'draft') DEFAULT 'published',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_slug (slug),
            INDEX idx_name (name)
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create categories table
     */
    private function create_categories_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_categories';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(255) NOT NULL,
            slug VARCHAR(255) UNIQUE NOT NULL,
            description TEXT,
            parent_id BIGINT UNSIGNED,
            icon VARCHAR(255),
            color VARCHAR(10),
            position INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_slug (slug),
            INDEX idx_parent_id (parent_id),
            UNIQUE KEY unique_parent_slug (parent_id, slug)
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create user bookmarks table
     */
    private function create_user_bookmarks_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_user_bookmarks';
        $verses_table = $wpdb->prefix . 'mge_verses';
        $granths_table = $wpdb->prefix . 'mge_granths';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            granth_id BIGINT UNSIGNED NOT NULL,
            chapter_id BIGINT UNSIGNED,
            verse_id BIGINT UNSIGNED,
            position_in_text INT,
            note TEXT,
            bookmark_type ENUM('bookmark', 'favorite', 'todo') DEFAULT 'bookmark',
            color VARCHAR(10),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_granth_id (granth_id),
            INDEX idx_verse_id (verse_id),
            UNIQUE KEY unique_bookmark (user_id, verse_id, bookmark_type),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create user highlights table
     */
    private function create_user_highlights_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_user_highlights';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            verse_id BIGINT UNSIGNED NOT NULL,
            start_position INT,
            end_position INT,
            color ENUM('yellow', 'green', 'blue') DEFAULT 'yellow',
            note TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_verse_id (verse_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create user notes table
     */
    private function create_user_notes_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_user_notes';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            verse_id BIGINT UNSIGNED NOT NULL,
            content TEXT NOT NULL,
            is_private BOOLEAN DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_verse_id (verse_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create reading progress table
     */
    private function create_reading_progress_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_reading_progress';
        $granths_table = $wpdb->prefix . 'mge_granths';
        $chapters_table = $wpdb->prefix . 'mge_chapters';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            granth_id BIGINT UNSIGNED NOT NULL,
            current_chapter_id BIGINT UNSIGNED,
            current_verse_id BIGINT UNSIGNED,
            progress_percentage DECIMAL(5, 2) DEFAULT 0,
            last_read_at TIMESTAMP,
            total_reading_time_minutes INT DEFAULT 0,
            status ENUM('not_started', 'reading', 'completed', 'paused') DEFAULT 'not_started',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_granth_id (granth_id),
            UNIQUE KEY unique_user_granth (user_id, granth_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (current_chapter_id) REFERENCES {$chapters_table}(id) ON DELETE SET NULL,
            FOREIGN KEY (current_verse_id) REFERENCES {$verses_table}(id) ON DELETE SET NULL
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create user collections table
     */
    private function create_user_collections_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_user_collections';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            is_public BOOLEAN DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create user collection items table
     */
    private function create_user_collection_items_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_user_collection_items';
        $collections_table = $wpdb->prefix . 'mge_user_collections';
        $granths_table = $wpdb->prefix . 'mge_granths';
        $chapters_table = $wpdb->prefix . 'mge_chapters';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            collection_id BIGINT UNSIGNED NOT NULL,
            granth_id BIGINT UNSIGNED,
            chapter_id BIGINT UNSIGNED,
            verse_id BIGINT UNSIGNED,
            position INT,
            added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_collection_id (collection_id),
            UNIQUE KEY unique_collection_item (collection_id, granth_id, chapter_id, verse_id),
            FOREIGN KEY (collection_id) REFERENCES {$collections_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (chapter_id) REFERENCES {$chapters_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create ratings table
     */
    private function create_ratings_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_ratings';
        $granths_table = $wpdb->prefix . 'mge_granths';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            granth_id BIGINT UNSIGNED,
            verse_id BIGINT UNSIGNED,
            rating INT NOT NULL,
            review TEXT,
            helpful_count INT DEFAULT 0,
            unhelpful_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_user_id (user_id),
            INDEX idx_granth_id (granth_id),
            INDEX idx_verse_id (verse_id),
            INDEX idx_rating (rating),
            UNIQUE KEY unique_user_granth_rating (user_id, granth_id),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create comments table
     */
    private function create_comments_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_comments';
        $verses_table = $wpdb->prefix . 'mge_verses';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            user_id BIGINT UNSIGNED NOT NULL,
            verse_id BIGINT UNSIGNED NOT NULL,
            parent_comment_id BIGINT UNSIGNED,
            content TEXT NOT NULL,
            is_approved BOOLEAN DEFAULT 0,
            likes_count INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL,
            
            INDEX idx_user_id (user_id),
            INDEX idx_verse_id (verse_id),
            INDEX idx_parent_comment_id (parent_comment_id),
            INDEX idx_created_at (created_at),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
            FOREIGN KEY (verse_id) REFERENCES {$verses_table}(id) ON DELETE CASCADE,
            FOREIGN KEY (parent_comment_id) REFERENCES {$table_name}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create import logs table
     */
    private function create_import_logs_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_import_logs';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            filename VARCHAR(255) NOT NULL,
            file_format VARCHAR(50),
            total_records INT,
            imported_records INT,
            failed_records INT,
            error_messages LONGTEXT,
            imported_by BIGINT UNSIGNED,
            status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            completed_at TIMESTAMP NULL,
            
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            FOREIGN KEY (imported_by) REFERENCES {$wpdb->users}(ID) ON DELETE SET NULL
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create analytics daily table
     */
    private function create_analytics_daily_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_analytics_daily';
        $granths_table = $wpdb->prefix . 'mge_granths';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            granth_id BIGINT UNSIGNED,
            date DATE NOT NULL,
            views INT DEFAULT 0,
            readers INT DEFAULT 0,
            average_reading_time INT DEFAULT 0,
            bookmarks_added INT DEFAULT 0,
            highlights_added INT DEFAULT 0,
            ratings INT DEFAULT 0,
            
            INDEX idx_granth_id (granth_id),
            INDEX idx_date (date),
            UNIQUE KEY unique_granth_date (granth_id, date),
            FOREIGN KEY (granth_id) REFERENCES {$granths_table}(id) ON DELETE CASCADE
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Create search logs table
     */
    private function create_search_logs_table($charset_collate) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'mge_search_logs';
        
        $sql = "CREATE TABLE IF NOT EXISTS {$table_name} (
            id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
            query VARCHAR(255) NOT NULL,
            results_count INT,
            user_id BIGINT UNSIGNED,
            filters_applied VARCHAR(500),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            INDEX idx_query (query),
            INDEX idx_user_id (user_id),
            INDEX idx_created_at (created_at),
            FOREIGN KEY (user_id) REFERENCES {$wpdb->users}(ID) ON DELETE SET NULL
        ) {$charset_collate}";
        
        $this->execute_sql($sql);
    }
    
    /**
     * Execute SQL safely
     */
    private function execute_sql($sql) {
        global $wpdb;
        
        // Suppress errors during table creation
        $wpdb->suppress_errors = true;
        $wpdb->query($sql);
        $wpdb->suppress_errors = false;
        
        return true;
    }
}
