</main>

<?php
$footer_content_source = goody_get_option('footer_content_source', 'theme');
$footer_gutenberg_html = $footer_content_source === 'gutenberg' ? goody_get_footer_gutenberg_content_html() : '';
?>
<?php if ($footer_content_source === 'gutenberg' && $footer_gutenberg_html !== '') : ?>
    <footer class="site-footer site-footer--gutenberg">
        <div class="container">
            <?php echo $footer_gutenberg_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
    </footer>
<?php else : ?>
    <footer class="site-footer">
        <div class="container site-footer__grid">
            <div>
                <h3><?php echo esc_html(goody_get_option('restaurant_name', get_bloginfo('name'))); ?></h3>
                <p><?php echo esc_html(goody_get_option('restaurant_tagline', get_bloginfo('description'))); ?></p>
                <?php $social_links = goody_get_social_links(); ?>
                <?php if (! empty($social_links)) : ?>
                    <div class="social-links">
                        <?php foreach ($social_links as $social) : ?>
                            <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener">
                                <?php if (goody_social_svg($social['label'])) : ?><span class="social-icon"><?php echo goody_social_svg($social['label']); ?></span><?php endif; ?>
                                <span><?php echo esc_html($social['label']); ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <h4><?php echo esc_html(goody_get_option('footer_quick_title', __('Quick Links', 'goody'))); ?></h4>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer_quick',
                    'container' => false,
                    'fallback_cb' => false,
                ]);
                ?>
            </div>

            <div>
                <h4><?php echo esc_html(goody_get_option('footer_legal_title', __('Legal', 'goody'))); ?></h4>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer_legal',
                    'container' => false,
                    'fallback_cb' => false,
                ]);
                ?>
            </div>

            <div>
                <h4><?php esc_html_e('Payment Methods', 'goody'); ?></h4>
                <?php $icons = goody_get_payment_icons(); ?>
                <?php if (! empty($icons)) : ?>
                    <div class="payment-icons">
                        <?php foreach ($icons as $icon_id) : ?>
                            <?php echo wp_get_attachment_image($icon_id, 'thumbnail', false, ['class' => 'payment-icon']); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="container site-footer__bottom">
            <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> <?php echo esc_html(goody_get_option('restaurant_name', get_bloginfo('name'))); ?>. <?php echo esc_html(goody_get_option('footer_copyright', __('All rights reserved.', 'goody'))); ?></p>
            <?php if (goody_get_option('seo_local_text')) : ?>
                <p class="local-seo-text"><?php echo esc_html(goody_get_option('seo_local_text')); ?></p>
            <?php endif; ?>
        </div>
    </footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
