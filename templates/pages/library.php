<?php
/**
 * User Library Page Template
 */
get_header('granth');
?>

<div class="mge-container">
    <div class="mge-library-page">
        <h1><?php esc_html_e('My Library', 'moonfires-granth'); ?></h1>

        <!-- Library Tabs -->
        <div class="mge-library-tabs" role="tablist">
            <button role="tab" class="mge-tab-button active" data-library-tab="reading-history">
                <?php esc_html_e('Reading History', 'moonfires-granth'); ?>
            </button>
            <button role="tab" class="mge-tab-button" data-library-tab="bookmarks">
                <?php esc_html_e('Bookmarks', 'moonfires-granth'); ?>
            </button>
            <button role="tab" class="mge-tab-button" data-library-tab="highlights">
                <?php esc_html_e('Highlights', 'moonfires-granth'); ?>
            </button>
            <button role="tab" class="mge-tab-button" data-library-tab="collections">
                <?php esc_html_e('Collections', 'moonfires-granth'); ?>
            </button>
        </div>
    </div>
</div>

<?php get_footer('granth');
