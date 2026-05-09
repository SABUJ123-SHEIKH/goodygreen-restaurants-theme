<?php
$hero_image_id = absint(goody_get_option('hero_image'));
$hero_image = $hero_image_id > 0 ? goody_get_image_url($hero_image_id, 'goody-hero') : '';
$hero_image_mobile = $hero_image_id > 0 ? goody_get_image_url($hero_image_id, 'goody-hero-mobile') : '';
$background_type = goody_get_option('hero_background_type', 'image');
$video_file = wp_get_attachment_url(absint(goody_get_option('hero_video_file')));
$video_external = goody_get_hero_video_embed_url(goody_get_option('hero_video_url'));
$show_video = $background_type === 'video' && ($video_file || $video_external);
$hero_classes = ['hero'];
if ($show_video || $hero_image_id > 0) {
    $hero_classes[] = 'hero--has-media';
}
if ($show_video) {
    $hero_classes[] = 'hero--has-video';
}
$hero_style = '';
if ($hero_image) {
    $hero_style = 'style="--hero-bg-image:url(' . esc_url($hero_image) . ');';
    if ($hero_image_mobile) {
        $hero_style .= '--hero-bg-image-mobile:url(' . esc_url($hero_image_mobile) . ');';
    }
    $hero_style .= '"';
}
$hero_primary_url = goody_maybe_get_direct_checkout_url(goody_get_option('hero_primary_url', ''));
$hero_secondary_url = goody_get_option('hero_secondary_url', '');
$hero_reservation_url = goody_get_reservation_url();
$hero_tagline = trim((string) goody_get_option('hero_concept_tagline', __('The best brunch in town', 'goody')));
$hero_heading_raw = trim((string) goody_get_option('hero_heading', __('The best Brunch from Barcelona', 'goody')));
$hero_highlight_text = trim((string) goody_get_option('hero_highlight_text', 'Brunch'));
$hero_subheading = trim((string) goody_get_option('hero_subheading', ''));
$hero_primary_text = trim((string) goody_get_option('hero_primary_text', __('Order now', 'goody')));
$hero_secondary_text = trim((string) goody_get_option('hero_secondary_text', __('Explore menu', 'goody')));
$hero_reservation_text = trim((string) goody_get_option('reservation_button_text', ''));
if ($hero_reservation_url) {
    if ($hero_reservation_text === '') {
        $hero_reservation_text = __('Reserve now', 'goody');
    }
} else {
    $hero_reservation_url = $hero_secondary_url;
    $hero_reservation_text = $hero_secondary_text !== '' ? $hero_secondary_text : __('Explore menu', 'goody');
}

$heading_top = '';
$heading_focus = '';
$heading_bottom = '';

$line_candidates = preg_split('/\r\n|\r|\n|\|/', $hero_heading_raw);
$line_candidates = array_values(array_filter(array_map('trim', is_array($line_candidates) ? $line_candidates : [])));
if (count($line_candidates) >= 3) {
    $heading_top = trim((string) $line_candidates[0], " \t\n\r\0\x0B,");
    $heading_focus = trim((string) $line_candidates[1], " \t\n\r\0\x0B,");
    $heading_bottom = trim((string) implode(' ', array_slice($line_candidates, 2)), " \t\n\r\0\x0B,");
} else {
    $focus_candidate = $hero_highlight_text;
    if ($focus_candidate === '' && preg_match('/\bBrunch\b/i', $hero_heading_raw, $default_focus_match)) {
        $focus_candidate = $default_focus_match[0];
    }

    if ($focus_candidate !== '' && preg_match('/' . preg_quote($focus_candidate, '/') . '/i', $hero_heading_raw, $focus_match, PREG_OFFSET_CAPTURE)) {
        $matched_text = $focus_match[0][0];
        $match_offset = (int) $focus_match[0][1];
        $heading_top = trim((string) substr($hero_heading_raw, 0, $match_offset), " \t\n\r\0\x0B,");
        $heading_focus = trim((string) $matched_text, " \t\n\r\0\x0B,");
        $heading_bottom = trim((string) substr($hero_heading_raw, $match_offset + strlen($matched_text)), " \t\n\r\0\x0B,");
    }
}

if ($heading_focus === '' && $hero_heading_raw !== '') {
    $heading_top = '';
    $heading_focus = '';
    $heading_bottom = '';
}

$spotlight_query = new WP_Query([
    'post_type' => 'menu_item',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'meta_key' => 'goody_menu_sort_order',
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
]);

$spotlight_image = '';
$spotlight_image_mobile = '';
$spotlight_image_id = 0;
$spotlight_title = __('Chef selection', 'goody');
$spotlight_price = '';
$spotlight_link = '';
if ($spotlight_query->have_posts()) {
    $spotlight_query->the_post();
    $spotlight_image_id = get_post_thumbnail_id(get_the_ID());
    $spotlight_image = get_the_post_thumbnail_url(get_the_ID(), 'goody-card');
    $spotlight_image_mobile = get_the_post_thumbnail_url(get_the_ID(), 'goody-card-mobile');
    $spotlight_title = get_the_title();
    $spotlight_link = get_permalink();
    $price = get_post_meta(get_the_ID(), 'goody_menu_price', true);
    if ($price !== '') {
        $spotlight_price = goody_format_price($price);
    }
    wp_reset_postdata();
}

$google_reviews = goody_get_google_reviews_data();
$google_rating = (float) ($google_reviews['rating'] ?? 0);
$google_count = (int) ($google_reviews['user_ratings_total'] ?? 0);
$display_rating = $google_rating > 0 ? $google_rating : goody_get_average_rating();
$google_source_label = trim((string) ($google_reviews['source_label'] ?? __('Google', 'goody')));

$hero_review_meta = __('Guest reviews', 'goody');
if ($google_count > 0) {
    $hero_review_meta = sprintf(_n('%d Google review', '%d Google reviews', $google_count, 'goody'), $google_count);
}

$hero_review_stat_label = $hero_review_meta;
if ($google_count > 0) {
    $hero_review_stat_label = sprintf(
        '+%1$d %2$s',
        $google_count,
        trim($google_source_label . ' ' . _n('review', 'reviews', $google_count, 'goody'))
    );
}

$today_hours = goody_get_today_business_hours();

$delivery_labels = [];
foreach ([
    'glovo' => 'Glovo',
    'ubereats' => 'Uber Eats',
    'deliveroo' => 'Deliveroo',
] as $provider => $provider_label) {
    if (goody_get_delivery_link($provider)) {
        $delivery_labels[] = $provider_label;
    }
}

$custom_order_url = goody_maybe_get_direct_checkout_url(goody_get_option('custom_order_url', ''));
if ($custom_order_url && empty($delivery_labels)) {
    $custom_delivery_label = trim((string) goody_get_option('custom_order_text', __('Direct order', 'goody')));
    if ($custom_delivery_label !== '') {
        $delivery_labels[] = $custom_delivery_label;
    }
}

$hero_stats = [];
if ($display_rating > 0) {
    $hero_stats[] = [
        'icon' => 'star',
        'value' => number_format($display_rating, 1) . ' / 5',
        'label' => $hero_review_stat_label,
    ];
}

if (is_array($today_hours) && ! empty($today_hours['open']) && ! empty($today_hours['close'])) {
    $hero_stats[] = [
        'icon' => 'clock',
        'value' => __('Open today', 'goody'),
        'label' => $today_hours['open'] . ' - ' . $today_hours['close'],
    ];
}

if (! empty($delivery_labels)) {
    $hero_stats[] = [
        'icon' => 'delivery',
        'value' => __('Delivery', 'goody'),
        'label' => implode(' • ', array_slice($delivery_labels, 0, 2)),
    ];
} elseif ($custom_order_url || $hero_primary_url) {
    $order_stat_value = $hero_primary_text !== '' ? $hero_primary_text : __('Order now', 'goody');
    $order_stat_label = '';

    if ($custom_order_url) {
        $order_stat_label = trim((string) goody_get_option('custom_order_text', ''));
        if ($order_stat_label === '' || strcasecmp($order_stat_label, $order_stat_value) === 0) {
            $order_stat_label = __('Direct order available', 'goody');
        }
    } elseif (strpos($hero_primary_url, '#menu') === 0) {
        $order_stat_label = __('Jump to the menu section', 'goody');
    } elseif (strpos($hero_primary_url, '#order') === 0) {
        $order_stat_label = __('Jump to the order section', 'goody');
    } else {
        $order_stat_label = __('Quick access from the hero CTA', 'goody');
    }

    $hero_stats[] = [
        'icon' => 'delivery',
        'value' => $order_stat_value,
        'label' => $order_stat_label,
    ];
} elseif ($hero_reservation_url) {
    $hero_stats[] = [
        'icon' => 'calendar',
        'value' => $hero_reservation_text !== '' ? $hero_reservation_text : __('Reserve now', 'goody'),
        'label' => __('Book your table today', 'goody'),
    ];
}
?>
<section class="<?php echo esc_attr(implode(' ', $hero_classes)); ?>" <?php echo $hero_style; ?>>
    <?php if ($hero_image_id > 0 && ! $show_video) : ?>
        <div class="hero__bg-wrap" aria-hidden="true">
            <picture>
                <?php if ($hero_image_mobile) : ?>
                    <source media="(max-width: 860px)" srcset="<?php echo esc_url($hero_image_mobile); ?>">
                <?php endif; ?>
                <?php if ($hero_image) : ?>
                    <source media="(min-width: 861px)" srcset="<?php echo esc_url($hero_image); ?>">
                <?php endif; ?>
                <?php
                echo wp_get_attachment_image($hero_image_id, 'goody-hero', false, [
                    'class' => 'hero__bg-image',
                    'loading' => 'eager',
                    'fetchpriority' => 'high',
                    'decoding' => 'async',
                    'sizes' => '100vw',
                    'alt' => '',
                ]);
                ?>
            </picture>
        </div>
    <?php endif; ?>

    <?php if ($show_video) : ?>
        <div class="hero__video-wrap" aria-hidden="true">
            <?php if ($video_file) : ?>
                <video class="hero__video" autoplay muted loop playsinline poster="<?php echo esc_url($hero_image); ?>">
                    <source src="<?php echo esc_url($video_file); ?>" type="video/mp4">
                </video>
            <?php elseif ($video_external) : ?>
                <iframe class="hero__iframe" src="about:blank" data-goody-deferred-src="<?php echo esc_url($video_external); ?>" allow="autoplay; fullscreen; picture-in-picture" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" title="<?php esc_attr_e('Hero video', 'goody'); ?>"></iframe>
                <script>
                    (function () {
                        var boot = function () {
                            var iframe = document.querySelector('.hero__iframe[data-goody-deferred-src]');
                            if (!iframe || iframe.getAttribute('src') !== 'about:blank') {
                                return;
                            }

                            var setSource = function () {
                                var deferredSrc = iframe.getAttribute('data-goody-deferred-src');
                                if (!deferredSrc || iframe.getAttribute('src') !== 'about:blank') {
                                    return;
                                }
                                iframe.setAttribute('src', deferredSrc);
                            };

                            if (window.requestIdleCallback) {
                                window.requestIdleCallback(setSource, { timeout: 1600 });
                                return;
                            }

                            window.setTimeout(setSource, 650);
                        };

                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', boot, { once: true });
                        } else {
                            boot();
                        }
                    }());
                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="hero__overlay"></div>

    <div class="container hero__inner">
        <div class="hero__content">
            <?php if ($hero_tagline !== '') : ?>
                <span class="eyebrow hero__eyebrow">
                    <span class="hero__eyebrow-line" aria-hidden="true"></span>
                    <?php echo esc_html($hero_tagline); ?>
                </span>
            <?php endif; ?>

            <h1 class="hero__headline">
                <?php if ($heading_focus !== '' || $heading_top !== '' || $heading_bottom !== '') : ?>
                    <?php if ($heading_top !== '') : ?><span class="hero__headline-line"><?php echo esc_html($heading_top); ?></span><?php endif; ?>
                    <?php if ($heading_focus !== '') : ?><span class="hero__headline-line hero__headline-line--accent"><?php echo esc_html($heading_focus); ?></span><?php endif; ?>
                    <?php if ($heading_bottom !== '') : ?><span class="hero__headline-line"><?php echo esc_html($heading_bottom); ?></span><?php endif; ?>
                <?php else : ?>
                    <span class="hero__headline-line"><?php echo esc_html($hero_heading_raw); ?></span>
                <?php endif; ?>
            </h1>

            <?php if ($hero_subheading !== '') : ?>
                <p><?php echo esc_html($hero_subheading); ?></p>
            <?php endif; ?>

            <div class="button-group hero__actions">
                <?php if ($hero_primary_url) : ?>
                    <a class="button button--hero" href="<?php echo esc_url($hero_primary_url); ?>">
                        <span class="button__icon" aria-hidden="true"><?php echo goody_svg('search'); ?></span>
                        <span><?php echo esc_html($hero_primary_text ?: __('Order now', 'goody')); ?></span>
                    </a>
                <?php endif; ?>

                <?php if ($hero_reservation_url) : ?>
                    <a class="button button--ghost button--hero-secondary" href="<?php echo esc_url($hero_reservation_url); ?>">
                        <span class="button__icon" aria-hidden="true"><?php echo goody_svg('calendar'); ?></span>
                        <span><?php echo esc_html($hero_reservation_text); ?></span>
                    </a>
                <?php endif; ?>
            </div>

            <?php if (! empty($hero_stats)) : ?>
                <div class="hero-stats">
                    <?php foreach ($hero_stats as $hero_stat) : ?>
                        <article class="hero-stat hero-stat--<?php echo esc_attr(sanitize_html_class($hero_stat['icon'])); ?>">
                            <span class="hero-stat__icon" aria-hidden="true"><?php echo goody_svg($hero_stat['icon']); ?></span>
                            <div class="hero-stat__content">
                                <strong class="hero-stat__value"><?php echo esc_html($hero_stat['value']); ?></strong>
                                <span class="hero-stat__label"><?php echo esc_html($hero_stat['label']); ?></span>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="hero__visual-wrap">
            <div class="hero__visual card">
                <div class="hero__visual-stage" <?php if ($spotlight_image) : ?>style="--hero-spotlight-image:url('<?php echo esc_url($spotlight_image); ?>');<?php if ($spotlight_image_mobile) : ?>--hero-spotlight-image-mobile:url('<?php echo esc_url($spotlight_image_mobile); ?>');<?php endif; ?>"<?php endif; ?>>
                    <?php if ($spotlight_image_id > 0) : ?>
                        <div class="hero__visual-bg" aria-hidden="true">
                            <?php
                            echo wp_get_attachment_image($spotlight_image_id, 'goody-card', false, [
                                'class' => 'hero__visual-bg-image',
                                'loading' => 'lazy',
                                'fetchpriority' => 'low',
                                'decoding' => 'async',
                                'sizes' => '(max-width: 860px) 72vw, 420px',
                                'alt' => '',
                            ]);
                            ?>
                        </div>
                    <?php endif; ?>
                    <div class="hero__visual-symbol" aria-hidden="true">&#127859;</div>

                    <?php if ($display_rating > 0 || $google_count > 0) : ?>
                        <div class="hero__review-chip">
                            <div class="hero__review-stars" aria-hidden="true">
                                <?php for ($i = 0; $i < 5; $i++) : ?>
                                    <?php echo goody_svg('star'); ?>
                                <?php endfor; ?>
                            </div>
                            <strong><?php echo esc_html(number_format($display_rating > 0 ? $display_rating : 5, 1)); ?> / 5</strong>
                            <small><?php echo esc_html($hero_review_meta); ?></small>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($spotlight_title || $spotlight_price) : ?>
                    <div class="hero__visual-note">
                        <span><?php esc_html_e('Chef selection', 'goody'); ?></span>
                        <?php if ($spotlight_link) : ?>
                            <strong><a href="<?php echo esc_url($spotlight_link); ?>"><?php echo esc_html($spotlight_title); ?></a></strong>
                        <?php else : ?>
                            <strong><?php echo esc_html($spotlight_title); ?></strong>
                        <?php endif; ?>

                        <?php if ($spotlight_price) : ?>
                            <small><?php echo esc_html($spotlight_price); ?></small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</section>
