<?php
/**
 * Footer Template
 */
?>
    </main>

    <!-- Footer -->
    <footer class="mge-footer" role="contentinfo">
        <div class="mge-footer-container">
            <div class="mge-footer-content">
                <div class="mge-footer-section">
                    <h3 class="mge-footer-heading"><?php esc_html_e('About', 'moonfires-granth'); ?></h3>
                    <p class="mge-footer-text">
                        <?php esc_html_e('Moonfires Granth Engine - Premium digital library for Sanatan Dharma scriptures', 'moonfires-granth'); ?>
                    </p>
                </div>

                <div class="mge-footer-section">
                    <h3 class="mge-footer-heading"><?php esc_html_e('Quick Links', 'moonfires-granth'); ?></h3>
                    <ul class="mge-footer-links">
                        <li><a href="<?php echo home_url('/browse'); ?>"><?php esc_html_e('Browse Granths', 'moonfires-granth'); ?></a></li>
                        <li><a href="<?php echo home_url('/about'); ?>"><?php esc_html_e('About Us', 'moonfires-granth'); ?></a></li>
                        <li><a href="<?php echo home_url('/contact'); ?>"><?php esc_html_e('Contact', 'moonfires-granth'); ?></a></li>
                    </ul>
                </div>

                <div class="mge-footer-section">
                    <h3 class="mge-footer-heading"><?php esc_html_e('Resources', 'moonfires-granth'); ?></h3>
                    <ul class="mge-footer-links">
                        <li><a href="<?php echo home_url('/privacy'); ?>"><?php esc_html_e('Privacy Policy', 'moonfires-granth'); ?></a></li>
                        <li><a href="<?php echo home_url('/terms'); ?>"><?php esc_html_e('Terms of Service', 'moonfires-granth'); ?></a></li>
                        <li><a href="<?php echo home_url('/accessibility'); ?>"><?php esc_html_e('Accessibility', 'moonfires-granth'); ?></a></li>
                    </ul>
                </div>
            </div>

            <div class="mge-footer-bottom">
                <p class="mge-copyright">
                    &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. 
                    <?php esc_html_e('All rights reserved.', 'moonfires-granth'); ?>
                </p>
                <p class="mge-footer-tagline">
                    <?php esc_html_e('May this engine illuminate the path of knowledge and devotion.', 'moonfires-granth'); ?>
                </p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
