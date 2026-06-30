# 📊 Database Schema

**Complete data model for Moonfires Granth Engine**

---

## Core Tables

### granths
```sql
CREATE TABLE wp_mge_granths (
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
);
```

### chapters
```sql
CREATE TABLE wp_mge_chapters (
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
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE
);
```

### verses
```sql
CREATE TABLE wp_mge_verses (
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
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES wp_mge_chapters(id) ON DELETE CASCADE
);
```

### authors
```sql
CREATE TABLE wp_mge_authors (
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
);
```

### categories
```sql
CREATE TABLE wp_mge_categories (
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
);
```

---

## User-Related Tables

### user_bookmarks
```sql
CREATE TABLE wp_mge_user_bookmarks (
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
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE
);
```

### user_highlights
```sql
CREATE TABLE wp_mge_user_highlights (
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
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE
);
```

### user_notes
```sql
CREATE TABLE wp_mge_user_notes (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    verse_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    is_private BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    INDEX idx_verse_id (verse_id),
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE
);
```

### reading_progress
```sql
CREATE TABLE wp_mge_reading_progress (
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
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE,
    FOREIGN KEY (current_chapter_id) REFERENCES wp_mge_chapters(id) ON DELETE SET NULL,
    FOREIGN KEY (current_verse_id) REFERENCES wp_mge_verses(id) ON DELETE SET NULL
);
```

### user_collections
```sql
CREATE TABLE wp_mge_user_collections (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    is_public BOOLEAN DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_user_id (user_id),
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE
);
```

### user_collection_items
```sql
CREATE TABLE wp_mge_user_collection_items (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    collection_id BIGINT UNSIGNED NOT NULL,
    granth_id BIGINT UNSIGNED,
    chapter_id BIGINT UNSIGNED,
    verse_id BIGINT UNSIGNED,
    position INT,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_collection_id (collection_id),
    UNIQUE KEY unique_collection_item (collection_id, granth_id, chapter_id, verse_id),
    FOREIGN KEY (collection_id) REFERENCES wp_mge_user_collections(id) ON DELETE CASCADE,
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE,
    FOREIGN KEY (chapter_id) REFERENCES wp_mge_chapters(id) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE
);
```

---

## Community Tables

### ratings
```sql
CREATE TABLE wp_mge_ratings (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT UNSIGNED NOT NULL,
    granth_id BIGINT UNSIGNED,
    verse_id BIGINT UNSIGNED,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
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
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE
);
```

### comments
```sql
CREATE TABLE wp_mge_comments (
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
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE CASCADE,
    FOREIGN KEY (verse_id) REFERENCES wp_mge_verses(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_comment_id) REFERENCES wp_mge_comments(id) ON DELETE CASCADE
);
```

---

## Import & Analytics Tables

### import_logs
```sql
CREATE TABLE wp_mge_import_logs (
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
    FOREIGN KEY (imported_by) REFERENCES wp_users(ID) ON DELETE SET NULL
);
```

### analytics_daily
```sql
CREATE TABLE wp_mge_analytics_daily (
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
    FOREIGN KEY (granth_id) REFERENCES wp_mge_granths(id) ON DELETE CASCADE
);
```

### search_logs
```sql
CREATE TABLE wp_mge_search_logs (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    query VARCHAR(255) NOT NULL,
    results_count INT,
    user_id BIGINT UNSIGNED,
    filters_applied VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_query (query),
    INDEX idx_user_id (user_id),
    INDEX idx_created_at (created_at),
    FOREIGN KEY (user_id) REFERENCES wp_users(ID) ON DELETE SET NULL
);
```

---

## Settings Table

### options
Using WordPress wp_options table for configuration:
```php
update_option('mge_library_name', 'My Granth Library');
update_option('mge_site_color', '#D4A574');
update_option('mge_enable_community', 1);
update_option('mge_cache_duration', 3600);
```

---

## Relationships Diagram

```
Granths
  ├── many Chapters
  │    ├── many Verses
  │    │    ├── many Highlights (User)
  │    │    ├── many Notes (User)
  │    │    ├── many Bookmarks (User)
  │    │    ├── many Ratings
  │    │    └── many Comments
  │    └── one ReadingProgress (per User)
  ├── one Author
  ├── one Category
  └── many Ratings (Granth level)

Users
  ├── many Bookmarks
  ├── many Highlights
  ├── many Notes
  ├── many ReadingProgress
  ├── many Collections
  ├── many Ratings
  └── many Comments

Collections
  └── many CollectionItems
       ├── Granth
       ├── Chapter
       └── Verse
```

---

## Performance Considerations

### Indexes
- All foreign keys indexed
- Frequently filtered columns indexed
- FULLTEXT indexes for search
- Composite indexes for common queries

### Query Optimization
- Pagination used everywhere (20-50 items)
- Eager loading with relationships
- Select only needed columns
- Cache search results

### Archival Strategy
- soft_delete using deleted_at timestamp
- Keep analytics historical data
- Regular database cleanup

---

This schema supports:
✅ Unlimited Granths & Content
✅ Multi-language Support
✅ Rich User Interactions
✅ Performance at Scale
✅ Future Analytics
