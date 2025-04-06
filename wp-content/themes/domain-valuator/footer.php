    <footer id="colophon" class="site-footer">
        <div class="container">
            <div class="footer-widgets">
                <?php if (is_active_sidebar('footer-1')): ?>
                    <div class="footer-widget-area">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="site-info">
                <?php
                /* translators: %s: WordPress. */
                printf(esc_html__('Proudly powered by %s', 'domain-valuator'), 'WordPress');
                ?>
                <span class="sep"> | </span>
                <?php
                /* translators: %1$s: Theme name, %2$s: Theme author. */
                printf(esc_html__('Theme: %1$s by %2$s.', 'domain-valuator'), 'Domain Valuator', '<a href="https://domainvaluator.com">Domain Valuator Team</a>');
                ?>
            </div><!-- .site-info -->
            
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => 'nav',
                    'container_class' => 'footer-navigation',
                    'depth'          => 1,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </div><!-- .container -->
    </footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html> 