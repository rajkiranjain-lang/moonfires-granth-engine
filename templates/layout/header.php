<?php
/**
 * Header Template
 * Main navigation and branding
 */
?>
<!DOCTYPE html>
<html lang="<?php echo get_language_attributes(); ?>">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="<?php echo MGE_PLUGIN_URL; ?>assets/images/favicon.svg">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class('mge-body'); ?>>
    <?php wp_body_open(); ?>

    <!-- Skip to main content link -->
    <a href="#mge-main" class="mge-skip-link"><?php esc_html_e('Skip to main content', 'moonfires-granth'); ?></a>

    <!-- Header Navigation -->
    <header class="mge-header" role="banner">
        <div class="mge-header-container">
            <!-- Logo/Branding -->
            <div class="mge-logo">
                <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
                    <img src="<?php echo MGE_PLUGIN_URL; ?>assets/images/logo.svg" 
                         alt="<?php bloginfo('name'); ?>" 
                         class="mge-logo-image">
                    <span class="mge-site-name"><?php bloginfo('name'); ?></span>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="mge-search-container">
                <form action="<?php echo home_url('/search'); ?>" method="get" class="mge-search-form">
                    <div class="mge-search-input-wrapper">
                        <input type="text" 
                               name="q" 
                               class="mge-search-input" 
                               placeholder="<?php esc_attr_e('Search granths...', 'moonfires-granth'); ?>"
                               aria-label="<?php esc_attr_e('Search', 'moonfires-granth'); ?>">
                        <button type="submit" class="mge-search-button" aria-label="<?php esc_attr_e('Search', 'moonfires-granth'); ?>">
                            <svg class="mge-icon" viewBox="0 0 24 24" width="20" height="20">
                                <circle cx="11" cy="11" r="8" fill="none" stroke="currentColor" stroke-width="2"/>
                                <path d="m21 21-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Main Navigation -->
            <nav class="mge-nav" role="navigation" aria-label="<?php esc_attr_e('Main navigation', 'moonfires-granth'); ?>">
                <ul class="mge-nav-list">
                    <li class="mge-nav-item">
                        <a href="<?php echo home_url(); ?>" class="mge-nav-link">
                            <?php esc_html_e('Home', 'moonfires-granth'); ?>
                        </a>
                    </li>
                    <li class="mge-nav-item">
                        <a href="<?php echo home_url('/browse'); ?>" class="mge-nav-link">
                            <?php esc_html_e('Browse', 'moonfires-granth'); ?>
                        </a>
                    </li>
                    <li class="mge-nav-item">
                        <a href="<?php echo home_url('/categories'); ?>" class="mge-nav-link">
                            <?php esc_html_e('Categories', 'moonfires-granth'); ?>
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Menu -->
            <div class="mge-user-menu">
                <?php if (is_user_logged_in()) : ?>
                    <a href="<?php echo home_url('/library'); ?>" class="mge-btn mge-btn-secondary" title="<?php esc_attr_e('My Library', 'moonfires-granth'); ?>">
                        <svg class="mge-icon" viewBox="0 0 24 24" width="18" height="18">
                            <path d="M4 3h16v18H4z" fill="none" stroke="currentColor" stroke-width="2"/>
                            <path d="M8 7h8M8 11h8" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                        <?php esc_html_e('Library', 'moonfires-granth'); ?>
                    </a>
                    <div class="mge-user-profile">
                        <img src="<?php echo get_avatar_url(get_current_user_id(), 32); ?>" 
                             alt="<?php esc_attr(wp_get_current_user()->display_name); ?>" 
                             class="mge-avatar">
                        <span class="mge-username"><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
                    </div>
                <?php else : ?>
                    <a href="<?php echo wp_login_url(); ?>" class="mge-btn mge-btn-primary">
                        <?php esc_html_e('Sign In', 'moonfires-granth'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <!-- Mobile Menu Toggle -->
            <button class="mge-menu-toggle" aria-label="<?php esc_attr_e('Toggle menu', 'moonfires-granth'); ?>" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </header>

    <!-- Breadcrumbs -->
    <?php if (!is_home()) : ?>
        <nav class="mge-breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'moonfires-granth'); ?>">
            <div class="mge-breadcrumbs-container">
                <a href="<?php echo home_url(); ?>"><?php esc_html_e('Home', 'moonfires-granth'); ?></a>
                <span class="mge-breadcrumb-separator">›</span>
                <span class="mge-breadcrumb-current"><?php the_title(); ?></span>
            </div>
        </nav>
    <?php endif; ?>

    <!-- Main Content -->
    <main id="mge-main" class="mge-main" role="main">
