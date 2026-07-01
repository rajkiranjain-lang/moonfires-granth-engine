<?php
namespace Moonfires\Granth\Database;

class Schema {
    public function create_tables() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Granths table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_granths (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255),
            description LONGTEXT,
            language VARCHAR(50),
            tradition VARCHAR(100),
            category VARCHAR(100),
            cover_image_url VARCHAR(500),
            featured TINYINT(1) DEFAULT 0,
            views BIGINT(20) DEFAULT 0,
            chapters_count INT DEFAULT 0,
            created_at DATETIME,
            updated_at DATETIME,
            KEY title (title),
            KEY language (language)
        ) $charset_collate;");

        // Chapters table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_chapters (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            granth_id BIGINT(20) UNSIGNED NOT NULL,
            title VARCHAR(255) NOT NULL,
            description LONGTEXT,
            chapter_order INT,
            verses_count INT DEFAULT 0,
            created_at DATETIME,
            updated_at DATETIME,
            KEY granth_id (granth_id)
        ) $charset_collate;");

        // Verses table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_verses (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            chapter_id BIGINT(20) UNSIGNED NOT NULL,
            verse_number INT,
            content LONGTEXT NOT NULL,
            transliteration LONGTEXT,
            translation LONGTEXT,
            commentary LONGTEXT,
            created_at DATETIME,
            updated_at DATETIME,
            KEY chapter_id (chapter_id),
            FULLTEXT INDEX content_ft (content)
        ) $charset_collate;");

        // Bookmarks table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_bookmarks (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            chapter_id BIGINT(20) UNSIGNED NOT NULL,
            note LONGTEXT,
            created_at DATETIME,
            updated_at DATETIME,
            KEY user_id (user_id),
            KEY chapter_id (chapter_id)
        ) $charset_collate;");

        // Highlights table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_highlights (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            verse_id BIGINT(20) UNSIGNED NOT NULL,
            color VARCHAR(20),
            created_at DATETIME,
            updated_at DATETIME,
            KEY user_id (user_id),
            KEY verse_id (verse_id)
        ) $charset_collate;");

        // Reading Progress table
        $wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}mge_reading_progress (
            id BIGINT(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            granth_id BIGINT(20) UNSIGNED NOT NULL,
            chapter_id BIGINT(20) UNSIGNED,
            verse_id BIGINT(20) UNSIGNED,
            created_at DATETIME,
            updated_at DATETIME,
            UNIQUE KEY user_granth (user_id, granth_id),
            KEY user_id (user_id)
        ) $charset_collate;");
    }
}
