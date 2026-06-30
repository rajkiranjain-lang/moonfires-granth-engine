<?php
/**
 * Search Results Page Template
 */
get_header('granth');
?>

<div class="mge-container">
    <div class="mge-search-page">
        <!-- Search Form -->
        <div class="mge-search-form-container">
            <form action="<?php echo home_url('/search'); ?>" method="get" class="mge-search-form-page">
                <div class="mge-search-wrapper">
                    <input type="text" 
                           name="q" 
                           class="mge-search-input-large" 
                           value="<?php echo esc_attr($query); ?>"
                           placeholder="<?php esc_attr_e('Search granths, verses, or authors...', 'moonfires-granth'); ?>">
                    <button type="submit" class="mge-btn mge-btn-primary">
                        <?php esc_html_e('Search', 'moonfires-granth'); ?>
                    </button>
                </div>
                
                <!-- Filters -->
                <div class="mge-search-filters">
                    <div class="mge-filter-group">
                        <label><?php esc_html_e('Type', 'moonfires-granth'); ?></label>
                        <select name="type" class="mge-input">
                            <option value="all" <?php selected($type, 'all'); ?>><?php esc_html_e('All', 'moonfires-granth'); ?></option>
                            <option value="granths" <?php selected($type, 'granths'); ?>><?php esc_html_e('Granths', 'moonfires-granth'); ?></option>
                            <option value="verses" <?php selected($type, 'verses'); ?>><?php esc_html_e('Verses', 'moonfires-granth'); ?></option>
                            <option value="authors" <?php selected($type, 'authors'); ?>><?php esc_html_e('Authors', 'moonfires-granth'); ?></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <!-- Results Summary -->
        <?php if (strlen($query) >= 2) : ?>
            <div class="mge-search-summary">
                <p><?php printf(esc_html__('Found %1$d result(s) for "%2$s"', 'moonfires-granth'), intval($total), esc_html($query)); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer('granth');
