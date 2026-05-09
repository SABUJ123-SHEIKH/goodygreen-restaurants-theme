<?php
$business_hours = goody_get_business_hours();
$social_links = goody_get_social_links();
$contact_title = goody_get_option('contact_section_title', __('Come to visit us', 'goody'));
$maps_api_key = (string) goody_get_option('integrations_maps_api_key', '');
$map_script_embed = (string) goody_get_option('map_script_embed');
$map_script_trimmed = trim($map_script_embed);
$has_google_script_url = (bool) preg_match('#^https?://maps\\.googleapis\\.com/maps/api/js#i', $map_script_trimmed);
$has_custom_map_script = $map_script_trimmed !== '' && ! $has_google_script_url;
$show_native_map = goody_get_option('google_maps_embed') === '' && ($maps_api_key !== '' || $has_google_script_url);
$map_lat = trim((string) goody_get_option('contact_map_lat', ''));
$map_lng = trim((string) goody_get_option('contact_map_lng', ''));
$has_map_coordinates = is_numeric($map_lat) && is_numeric($map_lng);
$map_address = trim((string) goody_get_option('contact_address', ''));
if ($has_map_coordinates) {
    $map_address = (string) $map_lat . ',' . (string) $map_lng;
}
?>
<section id="contact" class="page-section page-section--soft">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php esc_html_e('Contact', 'goody'); ?></span>
            <h2><?php echo esc_html($contact_title); ?></h2>
            <p><?php echo esc_html(goody_get_option('contact_section_text', '')); ?></p>
        </header>

        <div class="split">
            <div class="split__media">
                <div class="card contact-map">
                    <?php if (goody_get_option('google_maps_embed')) : ?>
                        <?php echo do_shortcode(goody_get_option('google_maps_embed')); ?>
                    <?php elseif ($show_native_map) : ?>
                        <div
                            class="goody-map-canvas"
                            data-goody-map
                            data-address="<?php echo esc_attr($map_address); ?>"
                            data-title="<?php echo esc_attr(goody_get_option('restaurant_name', get_bloginfo('name'))); ?>">
                        </div>
                    <?php elseif ($has_custom_map_script) : ?>
                        <?php echo do_shortcode($map_script_embed); ?>
                    <?php else : ?>
                        <div class="map-placeholder">
                            <span><?php echo goody_svg('pin'); ?></span>
                            <p><?php esc_html_e('Add a Google Maps embed from Goody Green settings.', 'goody'); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if ($has_google_script_url) : ?>
                        <div class="map-script-wrap">
                            <script src="<?php echo esc_url($map_script_trimmed); ?>" async defer></script>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="split__content">
                <div class="contact-cards">
                    <div class="card contact-card">
                        <div class="icon"><?php echo goody_svg('phone'); ?></div>
                        <div>
                            <h3><?php esc_html_e('Phone', 'goody'); ?></h3>
                            <p><?php echo esc_html(goody_get_option('contact_phone')); ?></p>
                        </div>
                    </div>
                    <div class="card contact-card">
                        <div class="icon"><?php echo goody_svg('mail'); ?></div>
                        <div>
                            <h3><?php esc_html_e('Email', 'goody'); ?></h3>
                            <p><?php echo esc_html(goody_get_option('contact_email')); ?></p>
                        </div>
                    </div>
                    <div class="card contact-card">
                        <div class="icon"><?php echo goody_svg('pin'); ?></div>
                        <div>
                            <h3><?php esc_html_e('Address', 'goody'); ?></h3>
                            <p><?php echo esc_html(goody_get_option('contact_address')); ?></p>
                        </div>
                    </div>
                </div>

                <?php if (! empty($business_hours)) : ?>
                    <div class="card business-hours">
                        <h3><?php esc_html_e('Business Hours', 'goody'); ?></h3>
                        <ul>
                            <?php foreach ($business_hours as $hours) : ?>
                                <li><strong><?php echo esc_html($hours['day']); ?>:</strong> <?php echo esc_html($hours['open']); ?> - <?php echo esc_html($hours['close']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (! empty($social_links)) : ?>
                    <div class="social-inline">
                        <?php foreach ($social_links as $social) : ?>
                            <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener"><?php echo esc_html($social['label']); ?></a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="contact-form-wrap card">
            <h3><?php esc_html_e('Send Us a Message', 'goody'); ?></h3>
            <?php $contact_message = goody_form_message('contact'); ?>
            <?php if ($contact_message) : ?>
                <p class="form-notice"><?php echo esc_html($contact_message); ?></p>
            <?php endif; ?>
            <?php if (goody_get_option('contact_form_shortcode')) : ?>
                <?php echo do_shortcode(goody_get_option('contact_form_shortcode')); ?>
            <?php else : ?>
                <form class="contact-form-fallback" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="goody_contact_form">
                    <?php wp_nonce_field('goody_contact_submit', 'goody_contact_nonce'); ?>
                    <input type="text" name="goody_contact_name" required placeholder="<?php esc_attr_e('Your name', 'goody'); ?>" aria-label="<?php esc_attr_e('Your name', 'goody'); ?>">
                    <input type="email" name="goody_contact_email" required placeholder="<?php esc_attr_e('Your email', 'goody'); ?>" aria-label="<?php esc_attr_e('Your email', 'goody'); ?>">
                    <input type="text" name="goody_contact_phone" placeholder="<?php esc_attr_e('Phone (optional)', 'goody'); ?>" aria-label="<?php esc_attr_e('Phone', 'goody'); ?>">
                    <textarea rows="4" name="goody_contact_message" required placeholder="<?php esc_attr_e('How can we help?', 'goody'); ?>" aria-label="<?php esc_attr_e('How can we help?', 'goody'); ?>"></textarea>
                    <button type="submit" class="button button--small"><?php esc_html_e('Submit', 'goody'); ?></button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</section>
