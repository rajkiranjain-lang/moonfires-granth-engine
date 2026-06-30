<?php
/**
 * Browse Granths Page Template
 */
get_header('granth');
?>

<div class="mge-browse-page">
    <div class="mge-browse-container">
        <!-- Sidebar Filters -->
        <aside class="mge-browse-sidebar">
            <h2><?php esc_html_e('Filters', 'moonfires-granth'); ?></h2>
            <form action="<?php echo home_url('/browse'); ?>" method="get" class="mge-filters-form">
                <button type="submit" class="mge-btn mge-btn-primary mge-btn-block">
                    <?php esc_html_e('Apply Filters', 'moonfires-granth'); ?>
                </button>
            </form>
        </aside>

        <!-- Main Content -->
        <main class="mge-browse-content">
            <h1><?php esc_html_e('Browse Scriptures', 'moonfires-granth'); ?></h1>
        </main>
    </div>
</div>

<?php get_footer('granth');
