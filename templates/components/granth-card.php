<?php
/**
 * Granth Card Component
 * Displays a book/scripture card
 */
?>
<div class="mge-card mge-granth-card">
    <div class="mge-card-image-wrapper">
        <img src="<?php echo esc_url($granth->cover_image_url); ?>" 
             alt="<?php echo esc_attr($granth->title); }}" 
             class="mge-card-image"
             loading="lazy">
        <?php if ($granth->featured) : ?>
            <span class="mge-badge mge-badge-featured"><?php esc_html_e('Featured', 'moonfires-granth'); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="mge-card-body">
        <h3 class="mge-card-title">
            <a href="<?php echo esc_url($granth->get_url()); ?>">
                <?php echo esc_html($granth->title); ?>
            </a>
        </h3>
        
        <p class="mge-card-author"><?php echo esc_html($granth->author); ?></p>
        
        <p class="mge-card-description"><?php echo wp_kses_post(wp_trim_words($granth->description, 15)); ?></p>
        
        <div class="mge-card-meta">
            <span class="mge-badge mge-badge-sm"><?php echo esc_html($granth->language); ?></span>
            <span class="mge-meta-item"><?php echo intval($granth->chapters_count); ?> <?php esc_html_e('chapters', 'moonfires-granth'); ?></span>
        </div>
        
        <div class="mge-card-footer">
            <a href="<?php echo esc_url($granth->get_url()); ?>" class="mge-btn mge-btn-sm mge-btn-primary">
                <?php esc_html_e('Read Now', 'moonfires-granth'); ?>
            </a>
            <button class="mge-btn-icon mge-favorite-btn" data-granth-id="<?php echo intval($granth->id); ?>" aria-label="<?php esc_attr_e('Add to favorites', 'moonfires-granth'); ?>">
                <svg viewBox="0 0 24 24" width="20" height="20">
                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>
