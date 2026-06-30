<?php
/**
 * Homepage Template
 */
get_header('granth');
?>

<div class="mge-hero">
    <div class="mge-hero-content">
        <h1 class="mge-hero-title"><?php bloginfo('name'); ?></h1>
        <p class="mge-hero-subtitle"><?php bloginfo('description'); ?></p>
        
        <!-- Featured Search Bar -->
        <form action="<?php echo home_url('/search'); ?>" method="get" class="mge-hero-search">
            <div class="mge-search-wrapper">
                <input type="text" 
                       name="q" 
                       class="mge-search-input-large" 
                       placeholder="<?php esc_attr_e('Search granths, verses, or authors...', 'moonfires-granth'); ?>"
                       aria-label="<?php esc_attr_e('Search', 'moonfires-granth'); ?>">
                <button type="submit" class="mge-btn mge-btn-primary mge-btn-lg">
                    <?php esc_html_e('Search', 'moonfires-granth'); ?>
                </button>
            </div>
        </form>
    </div>
</div>

<div class="mge-container">
    <!-- Daily Featured Verse -->
    <?php if (!empty($daily_verse)) : ?>
        <section class="mge-section mge-featured-verse" aria-labelledby="daily-verse-title">
            <h2 id="daily-verse-title" class="mge-section-title"><?php esc_html_e('Verse of the Day', 'moonfires-granth'); ?></h2>
            <div class="mge-verse-card">
                <p class="mge-verse-text">"<?php echo esc_html($daily_verse->content); ?>"</p>
                <p class="mge-verse-reference">
                    — <?php echo esc_html($daily_verse->chapter->granth->title); ?> <?php echo esc_html($daily_verse->chapter->title); ?> <?php echo intval($daily_verse->verse_number); ?>
                </p>
                <a href="<?php echo esc_url($daily_verse->get_url()); ?>" class="mge-btn mge-btn-secondary">
                    <?php esc_html_e('Read Full Verse', 'moonfires-granth'); ?>
                </a>
            </div>
        </section>
    <?php endif; ?>

    <!-- Continue Reading -->
    <?php if (!empty($reading_progress) && is_user_logged_in()) : ?>
        <section class="mge-section mge-continue-reading" aria-labelledby="continue-reading-title">
            <h2 id="continue-reading-title" class="mge-section-title"><?php esc_html_e('Continue Reading', 'moonfires-granth'); ?></h2>
            <div class="mge-grid mge-grid-cols-3">
                <?php foreach ($reading_progress as $progress) : ?>
                    <div class="mge-card">
                        <div class="mge-card-image">
                            <img src="<?php echo esc_url($progress->granth->cover_image_url); ?>" 
                                 alt="<?php echo esc_attr($progress->granth->title); ?>">
                        </div>
                        <div class="mge-card-body">
                            <h3 class="mge-card-title"><?php echo esc_html($progress->granth->title); ?></h3>
                            <p class="mge-card-meta"><?php echo esc_html($progress->chapter->title); ?></p>
                            <a href="<?php echo esc_url($progress->get_resume_url()); ?>" class="mge-btn mge-btn-sm mge-btn-secondary">
                                <?php esc_html_e('Resume Reading', 'moonfires-granth'); ?>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Featured Granths -->
    <?php if (!empty($featured)) : ?>
        <section class="mge-section mge-featured" aria-labelledby="featured-title">
            <h2 id="featured-title" class="mge-section-title"><?php esc_html_e('Featured Scriptures', 'moonfires-granth'); ?></h2>
            <div class="mge-grid mge-grid-cols-4">
                <?php foreach ($featured as $granth) : ?>
                    <?php include MGE_PLUGIN_DIR . '/templates/components/granth-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Recently Added -->
    <?php if (!empty($recently_added)) : ?>
        <section class="mge-section mge-recently-added" aria-labelledby="recent-title">
            <div class="mge-section-header">
                <h2 id="recent-title" class="mge-section-title"><?php esc_html_e('Recently Added', 'moonfires-granth'); ?></h2>
                <a href="<?php echo home_url('/browse?sort=newest'); ?>" class="mge-link-more">
                    <?php esc_html_e('View All', 'moonfires-granth'); ?> →
                </a>
            </div>
            <div class="mge-grid mge-grid-cols-4">
                <?php foreach ($recently_added as $granth) : ?>
                    <?php include MGE_PLUGIN_DIR . '/templates/components/granth-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Popular Granths -->
    <?php if (!empty($popular)) : ?>
        <section class="mge-section mge-popular" aria-labelledby="popular-title">
            <div class="mge-section-header">
                <h2 id="popular-title" class="mge-section-title"><?php esc_html_e('Most Popular', 'moonfires-granth'); ?></h2>
                <a href="<?php echo home_url('/browse?sort=popular'); ?>" class="mge-link-more">
                    <?php esc_html_e('View All', 'moonfires-granth'); ?> →
                </a>
            </div>
            <div class="mge-grid mge-grid-cols-4">
                <?php foreach ($popular as $granth) : ?>
                    <?php include MGE_PLUGIN_DIR . '/templates/components/granth-card.php'; ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</div>

<?php get_footer('granth');
