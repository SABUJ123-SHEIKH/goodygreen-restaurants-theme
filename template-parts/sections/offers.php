<?php
$active_offer_ids = goody_get_active_offers(4);
$offers_query = null;
if (! empty($active_offer_ids)) {
    $offers_query = new WP_Query([
        'post_type' => 'offer',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'post__in' => $active_offer_ids,
        'orderby' => 'post__in',
    ]);
} else {
    $offers_query = new WP_Query([
        'post_type' => 'offer',
        'post_status' => 'publish',
        'posts_per_page' => 4,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
}

$glovo_link = goody_get_delivery_link('glovo');
$ubereats_link = goody_get_delivery_link('ubereats');
$deliveroo_link = goody_get_delivery_link('deliveroo');
$offers_title = goody_get_option('offers_section_title', __('Goody in your house', 'goody'));
$order_title = goody_get_option('order_section_title', __('Order & Delivery', 'goody'));
$order_text = goody_get_option('order_section_text', '');
$custom_order_url = goody_maybe_get_direct_checkout_url(goody_get_option('custom_order_url', ''));
$custom_order_is_external = goody_is_external_url($custom_order_url);
?>
<section id="offers" class="page-section offers-zone">
    <div class="container">
        <div class="offers-lead split">
            <div>
                <header class="section-heading">
                    <span class="eyebrow"><?php esc_html_e('Offers', 'goody'); ?></span>
                    <h2><?php echo esc_html($offers_title); ?></h2>
                    <p><?php echo esc_html(goody_get_option('offers_section_text', '')); ?></p>
                </header>
                <div class="delivery-grid">
                    <?php if ($glovo_link) : ?><a class="delivery-pill" href="<?php echo esc_url($glovo_link); ?>" target="_blank" rel="noopener"><span>Glovo</span><strong><?php esc_html_e('Order now', 'goody'); ?></strong></a><?php endif; ?>
                    <?php if ($ubereats_link) : ?><a class="delivery-pill" href="<?php echo esc_url($ubereats_link); ?>" target="_blank" rel="noopener"><span>UberEats</span><strong><?php esc_html_e('Order now', 'goody'); ?></strong></a><?php endif; ?>
                    <?php if ($deliveroo_link) : ?><a class="delivery-pill" href="<?php echo esc_url($deliveroo_link); ?>" target="_blank" rel="noopener"><span>Deliveroo</span><strong><?php esc_html_e('Order now', 'goody'); ?></strong></a><?php endif; ?>
                    <?php if ($custom_order_url) : ?><a class="delivery-pill" href="<?php echo esc_url($custom_order_url); ?>" <?php if ($custom_order_is_external) : ?>target="_blank" rel="noopener"<?php endif; ?>><span><?php echo esc_html(goody_get_option('custom_order_text', __('Direct order', 'goody'))); ?></span><strong><?php esc_html_e('Open', 'goody'); ?></strong></a><?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($offers_query && $offers_query->have_posts()) : ?>
            <div class="archive-grid archive-grid--two offer-grid">
                <?php while ($offers_query->have_posts()) : $offers_query->the_post(); ?>
                    <?php get_template_part('template-parts/cards/content', 'offer'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
