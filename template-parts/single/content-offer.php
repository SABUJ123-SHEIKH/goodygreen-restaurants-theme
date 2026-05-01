<?php
$offer_id = get_the_ID();
$section = goody_get_single_home_section('offer');
$discount = get_post_meta($offer_id, 'goody_offer_discount_label', true);
$type = get_post_meta($offer_id, 'goody_offer_type', true);
$start = get_post_meta($offer_id, 'goody_offer_start_date', true);
$end = get_post_meta($offer_id, 'goody_offer_end_date', true);
$active = get_post_meta($offer_id, 'goody_offer_active', true);
$linked_ids = array_values(array_filter(array_map('absint', (array) get_post_meta($offer_id, 'goody_offer_linked_menu_items', true))));
$custom_order_url = goody_maybe_get_direct_checkout_url(goody_get_option('custom_order_url', ''));
$custom_order_is_external = goody_is_external_url($custom_order_url);
?>
<section class="page-section cpt-single cpt-single--offer">
    <div class="container">
        <nav class="cpt-single__crumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'goody'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'goody'); ?></a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php echo esc_html($section['label']); ?></a>
            <span>/</span>
            <strong><?php the_title(); ?></strong>
        </nav>

        <article <?php post_class('card cpt-single__hero'); ?>>
            <div class="cpt-single__media">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php else : ?>
                    <div class="cpt-single__placeholder"><?php esc_html_e('Offer', 'goody'); ?></div>
                <?php endif; ?>
            </div>

            <div class="cpt-single__content">
                <span class="eyebrow"><?php esc_html_e('Offer', 'goody'); ?></span>
                <h1><?php the_title(); ?></h1>
                <?php if (get_the_excerpt()) : ?><p class="cpt-single__lead"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>

                <div class="cpt-single__chips">
                    <?php if ($discount) : ?><span class="badge"><?php echo esc_html($discount); ?></span><?php endif; ?>
                    <?php if ($type) : ?><span><?php echo esc_html(ucfirst($type)); ?></span><?php endif; ?>
                    <?php if ($start) : ?><span><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($start))); ?></span><?php endif; ?>
                    <?php if ($end) : ?><span><?php esc_html_e('Until', 'goody'); ?> <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end))); ?></span><?php endif; ?>
                    <span><?php echo $active === '1' ? esc_html__('Active', 'goody') : esc_html__('Inactive', 'goody'); ?></span>
                </div>

                <div class="button-group cpt-single__actions">
                    <a class="button button--outline" href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php esc_html_e('Back to Offers', 'goody'); ?></a>
                    <?php if ($custom_order_url) : ?>
                        <a class="button" href="<?php echo esc_url($custom_order_url); ?>" <?php if ($custom_order_is_external) : ?>target="_blank" rel="noopener"<?php endif; ?>><?php echo esc_html(goody_get_option('custom_order_text', __('Order now', 'goody'))); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <div class="cpt-single__grid">
            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Offer Details', 'goody'); ?></h2>
                <div class="cpt-single__body">
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Linked Menu Items', 'goody'); ?></h2>
                <?php if (! empty($linked_ids)) : ?>
                    <ul class="cpt-single__linked">
                        <?php foreach ($linked_ids as $linked_id) : ?>
                            <?php if (get_post_status($linked_id) !== 'publish') { continue; } ?>
                            <li><a href="<?php echo esc_url(get_permalink($linked_id)); ?>"><?php echo esc_html(get_the_title($linked_id)); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p><?php esc_html_e('No linked menu items for this offer yet.', 'goody'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
