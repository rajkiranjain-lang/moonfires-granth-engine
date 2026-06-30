<?php
/**
 * Full Screen Reader Template
 * Distraction-free reading interface
 */
get_header('granth-minimal');
?>

<div class="mge-reader-container" data-granth-id="<?php echo intval($granth->id); ?>" data-chapter-id="<?php echo intval($chapter->id); ?>">
    
    <!-- Reader Toolbar -->
    <div class="mge-reader-toolbar" role="toolbar" aria-label="<?php esc_attr_e('Reading tools', 'moonfires-granth'); ?>">
        <div class="mge-toolbar-left">
            <button class="mge-btn-icon" id="mge-menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'moonfires-granth'); ?>" title="<?php esc_attr_e('Menu', 'moonfires-granth'); ?>">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </button>
            <h1 class="mge-reader-title"><?php echo esc_html($granth->title); ?></h1>
        </div>

        <div class="mge-toolbar-center">
            <!-- Reading Progress -->
            <div class="mge-reading-progress-bar">
                <div class="mge-progress" id="mge-scroll-progress" style="width: 0%"></div>
            </div>
        </div>

        <div class="mge-toolbar-right">
            <!-- Dark Mode Toggle -->
            <button class="mge-btn-icon" id="mge-theme-toggle" aria-label="<?php esc_attr_e('Toggle theme', 'moonfires-granth'); ?>" title="<?php esc_attr_e('Dark Mode', 'moonfires-granth'); ?>">
                <svg class="mge-icon-light" viewBox="0 0 24 24" width="20" height="20">
                    <circle cx="12" cy="12" r="5" fill="currentColor"/>
                    <g stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="3"/>
                        <line x1="12" y1="21" x2="12" y2="23"/>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    </g>
                </svg>
                <svg class="mge-icon-dark" viewBox="0 0 24 24" width="20" height="20" style="display:none;">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" fill="currentColor"/>
                </svg>
            </button>

            <!-- Settings -->
            <button class="mge-btn-icon" id="mge-settings-toggle" aria-label="<?php esc_attr_e('Reading settings', 'moonfires-granth'); ?>" title="<?php esc_attr_e('Settings', 'moonfires-granth'); ?>">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="19" cy="12" r="1.5" fill="currentColor"/>
                    <circle cx="5" cy="12" r="1.5" fill="currentColor"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Reader Content -->
    <div class="mge-reader-content" id="mge-reader-content">
        <div class="mge-verses-container">
            <?php foreach ($verses as $verse) : ?>
                <div class="mge-verse" data-verse-id="<?php echo intval($verse->id); ?>">
                    <div class="mge-verse-number"><?php echo intval($verse->verse_number); ?></div>
                    <div class="mge-verse-content">
                        <p class="mge-verse-text"><?php echo wp_kses_post($verse->content); ?></p>
                        <?php if (!empty($verse->transliteration)) : ?>
                            <p class="mge-verse-transliteration"><?php echo esc_html($verse->transliteration); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($verse->translation)) : ?>
                            <p class="mge-verse-translation"><?php echo wp_kses_post($verse->translation); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="mge-verse-actions">
                        <button class="mge-btn-icon mge-bookmark-btn" data-verse-id="<?php echo intval($verse->id); ?>" aria-label="<?php esc_attr_e('Bookmark verse', 'moonfires-granth'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18">
                                <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button class="mge-btn-icon mge-highlight-btn" data-verse-id="<?php echo intval($verse->id); ?>" aria-label="<?php esc_attr_e('Highlight verse', 'moonfires-granth'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" fill="currentColor"/>
                            </svg>
                        </button>
                        <button class="mge-btn-icon mge-share-btn" data-verse-id="<?php echo intval($verse->id); ?>" aria-label="<?php esc_attr_e('Share verse', 'moonfires-granth'); ?>">
                            <svg viewBox="0 0 24 24" width="18" height="18">
                                <circle cx="18" cy="5" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
                                <circle cx="6" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
                                <circle cx="18" cy="19" r="3" fill="none" stroke="currentColor" stroke-width="2"/>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49" stroke="currentColor" stroke-width="2"/>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Settings Panel -->
    <div class="mge-settings-panel" id="mge-settings-panel" aria-hidden="true">
        <div class="mge-settings-section">
            <label class="mge-settings-label"><?php esc_html_e('Font Size', 'moonfires-granth'); ?></label>
            <div class="mge-font-size-controls">
                <button class="mge-btn-sm" data-font-size="small">A</button>
                <button class="mge-btn-sm" data-font-size="medium">A</button>
                <button class="mge-btn-sm" data-font-size="large">A</button>
            </div>
        </div>

        <div class="mge-settings-section">
            <label class="mge-settings-label"><?php esc_html_e('Line Height', 'moonfires-granth'); ?></label>
            <div class="mge-line-height-controls">
                <button class="mge-btn-sm" data-line-height="1.5">1.5</button>
                <button class="mge-btn-sm" data-line-height="1.8">1.8</button>
                <button class="mge-btn-sm" data-line-height="2">2.0</button>
            </div>
        </div>

        <div class="mge-settings-section">
            <label class="mge-settings-label"><?php esc_html_e('Reading Width', 'moonfires-granth'); ?></label>
            <div class="mge-width-controls">
                <button class="mge-btn-sm" data-width="narrow"><?php esc_html_e('Narrow', 'moonfires-granth'); ?></button>
                <button class="mge-btn-sm" data-width="normal"><?php esc_html_e('Normal', 'moonfires-granth'); ?></button>
                <button class="mge-btn-sm" data-width="wide"><?php esc_html_e('Wide', 'moonfires-granth'); ?></button>
            </div>
        </div>
    </div>

    <!-- Chapter Navigation -->
    <div class="mge-chapter-nav">
        <button class="mge-btn mge-btn-outline" id="mge-prev-chapter" aria-label="<?php esc_attr_e('Previous chapter', 'moonfires-granth'); ?>">
            ← <?php esc_html_e('Previous', 'moonfires-granth'); ?>
        </button>
        <button class="mge-btn mge-btn-outline" id="mge-next-chapter" aria-label="<?php esc_attr_e('Next chapter', 'moonfires-granth'); ?>">
            <?php esc_html_e('Next', 'moonfires-granth'); ?> →
        </button>
    </div>
</div>

<?php get_footer('granth-minimal');
