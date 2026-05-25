<?php
$item_id = get_the_ID();
$price = get_post_meta($item_id, 'goody_menu_price', true);
$short_desc = get_post_meta($item_id, 'goody_menu_short_desc', true);
$ingredients = get_post_meta($item_id, 'goody_menu_ingredients', true);
$calories = get_post_meta($item_id, 'goody_menu_calories', true);
$spicy = get_post_meta($item_id, 'goody_menu_spicy_level', true);
$badge = get_post_meta($item_id, 'goody_menu_badge', true);
$available = get_post_meta($item_id, 'goody_menu_available', true);
if ($available === '') {
    $available = '1';
}
$customize = get_post_meta($item_id, 'goody_menu_customizations', true);
$vegetarian = get_post_meta($item_id, 'goody_menu_vegetarian', true) === '1';
$gluten_free = get_post_meta($item_id, 'goody_menu_gluten_free', true) === '1';
$meal_terms = get_the_terms($item_id, 'meal_type');
$category_terms = get_the_terms($item_id, 'menu_category');
$dietary_terms = get_the_terms($item_id, 'dietary_preference');
$order_now_text = goody_get_option('custom_order_text', __('Order now', 'goody'));
$direct_order = goody_get_menu_item_direct_order_data($item_id);
$direct_order_product_id = ! empty($direct_order['product_id']) ? (int) $direct_order['product_id'] : 0;
$direct_order_qty = ! empty($direct_order['qty']) ? (int) $direct_order['qty'] : 1;
$direct_order_image = has_post_thumbnail($item_id) ? (string) get_the_post_thumbnail_url($item_id, 'goody-card') : '';
$order_now_url = goody_get_menu_item_checkout_url($item_id);
if ($order_now_url === '') {
    $order_now_url = goody_maybe_get_direct_checkout_url(goody_get_option('custom_order_url', ''));
}
if ($order_now_url === '') {
    $order_now_url = '#offers';
}
$is_external_order_url = goody_is_external_url($order_now_url);
$badge_label = $badge ? ucwords(str_replace('-', ' ', $badge)) : '';

if ($badge_label === '') {
    if ($vegetarian) {
        $badge_label = __('Vegetarian', 'goody');
    } elseif ($gluten_free) {
        $badge_label = __('Gluten Free', 'goody');
    } elseif (! is_wp_error($meal_terms) && ! empty($meal_terms)) {
        $badge_label = $meal_terms[0]->name;
    } elseif (! is_wp_error($dietary_terms) && ! empty($dietary_terms)) {
        $badge_label = $dietary_terms[0]->name;
    } elseif (! is_wp_error($category_terms) && ! empty($category_terms)) {
        $badge_label = $category_terms[0]->name;
    }
}

if ($available !== '1') {
    $badge_label = __('Unavailable', 'goody');
}

$info_lines = [];
if ($ingredients) {
    $info_lines[] = sprintf(
        /* translators: %s is a list of ingredients. */
        __('Ingredients: %s', 'goody'),
        $ingredients
    );
}
if ($customize) {
    $info_lines[] = sprintf(
        /* translators: %s is a customization summary. */
        __('Customize: %s', 'goody'),
        $customize
    );
}

$meta_items = [];
if ($calories) {
    $meta_items[] = $calories . ' kcal';
}
if ($spicy) {
    $meta_items[] = ucwords(str_replace('-', ' ', $spicy));
}
if ($vegetarian) {
    $meta_items[] = __('Vegetarian', 'goody');
}
if ($gluten_free) {
    $meta_items[] = __('Gluten Free', 'goody');
}

$details_popup_lines = [];
$details_popup_intro = trim((string) ($short_desc ?: get_the_excerpt()));
if ($details_popup_intro !== '') {
    $details_popup_lines[] = wp_trim_words(wp_strip_all_tags($details_popup_intro), 18, '...');
}
foreach ($info_lines as $info_line) {
    $info_line = trim((string) $info_line);
    if ($info_line !== '') {
        $details_popup_lines[] = $info_line;
    }
}
if (! empty($meta_items)) {
    $details_popup_lines[] = implode(' | ', array_values(array_filter(array_map('sanitize_text_field', $meta_items))));
}
$details_popup_lines = array_slice(array_values(array_unique($details_popup_lines)), 0, 3);
$category_ids_attr = '';
if (! is_wp_error($category_terms) && ! empty($category_terms)) {
    $category_ids_attr = implode(',', array_map('absint', wp_list_pluck($category_terms, 'term_id')));
}
$reservation_context = is_array($GLOBALS['goody_reservation_menu_context'] ?? null) ? $GLOBALS['goody_reservation_menu_context'] : [];
$reservation_enabled = ! empty($reservation_context['enabled']);
$reservation_item = ($reservation_enabled && ! empty($reservation_context['items'][$item_id]) && is_array($reservation_context['items'][$item_id]))
    ? $reservation_context['items'][$item_id]
    : null;
if (is_array($reservation_item)) {
    $available = ! empty($reservation_item['available']) ? '1' : '0';
    $price = (string) ($reservation_item['price'] ?? $price);
}
$reservation_is_disabled = $available !== '1';
$reservation_qty_display = 1;
$reservation_qty_label = __('Quantity', 'goody');
if (is_array($reservation_item)) {
    $reservation_qty_display = (float) ($reservation_item['min_qty'] ?? 1);
    if ($reservation_qty_display <= 0) {
        $reservation_qty_display = 1;
    }
    $reservation_qty_label = (($reservation_item['unit_type'] ?? '') === 'kg') ? __('KG', 'goody') : __('Quantity', 'goody');
}
$card_classes = 'card menu-card' . ($reservation_is_disabled ? ' is-disabled' : '');
if ($reservation_enabled) {
    $card_classes .= ' goody-booking-card';
}
?>
<article <?php post_class($card_classes); ?> data-available="<?php echo esc_attr($available); ?>" data-menu-item-id="<?php echo esc_attr((string) $item_id); ?>" data-category-ids="<?php echo esc_attr($category_ids_attr); ?>">
    <?php if ($reservation_enabled) : ?>
        <span class="goody-booking-card__check" aria-hidden="true">✓</span>
    <?php endif; ?>
    <div class="menu-card__visual">
        <?php if ($badge_label !== '') : ?>
            <span class="badge menu-card__flag"><?php echo esc_html($badge_label); ?></span>
        <?php endif; ?>

        <?php if (has_post_thumbnail()) : ?>
            <a class="menu-card__image" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('goody-card', ['sizes' => '(max-width: 767px) 46vw, 240px']); ?></a>
        <?php else : ?>
            <a class="menu-card__image menu-card__image--placeholder" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                <span><?php esc_html_e('Menu Item', 'goody'); ?></span>
            </a>
        <?php endif; ?>
    </div>

    <div class="menu-card__content">
        <div class="menu-card__body">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html($short_desc ?: get_the_excerpt()); ?></p>
        </div>

        <div class="menu-card__footer">
            <div class="menu-card__pricing">
                <?php if ($price !== '') : ?><span class="price"><?php echo goody_format_price($price); ?></span><?php endif; ?>
                <?php foreach ($info_lines as $info_line) : ?>
                    <p class="menu-card__info"><?php echo esc_html($info_line); ?></p>
                <?php endforeach; ?>
            </div>

            <div class="menu-card__actions">
                <?php if ($reservation_enabled && is_array($reservation_item)) : ?>
                    <input
                        type="hidden"
                        value="<?php echo esc_attr((string) $reservation_qty_display); ?>"
                        data-qty-for="<?php echo esc_attr((string) $item_id); ?>"
                    >
                    <button
                        type="button"
                        class="goody-item-select button menu-card__order"
                        data-select-item="<?php echo esc_attr((string) $item_id); ?>"
                        <?php echo $reservation_is_disabled ? 'disabled' : ''; ?>
                        aria-label="<?php echo esc_attr($reservation_is_disabled ? __('Unavailable', 'goody') : __('Add to order', 'goody')); ?>"
                        title="<?php echo esc_attr($reservation_is_disabled ? __('Unavailable', 'goody') : __('Add to order', 'goody')); ?>"
                    >
                        <span class="menu-card__order-icon" aria-hidden="true">+</span>
                        <span class="screen-reader-text"><?php echo esc_html($reservation_is_disabled ? __('Unavailable', 'goody') : __('Add to order', 'goody')); ?></span>
                    </button>
                <?php elseif ($available === '1') : ?>
                    <?php if ($direct_order_product_id > 0) : ?>
                        <button
                            type="button"
                            class="button menu-card__order"
                            data-goody-direct-order-open
                            data-goody-direct-order-target="goody-menu-order-modal"
                            data-product-id="<?php echo esc_attr((string) $direct_order_product_id); ?>"
                            data-quantity="<?php echo esc_attr((string) $direct_order_qty); ?>"
                            data-title="<?php echo esc_attr(get_the_title($item_id)); ?>"
                            data-price="<?php echo esc_attr($price !== '' ? goody_format_price($price) : ''); ?>"
                            data-image="<?php echo esc_url($direct_order_image); ?>"
                            aria-label="<?php echo esc_attr($order_now_text ?: __('Order now', 'goody')); ?>"
                            title="<?php echo esc_attr($order_now_text ?: __('Order now', 'goody')); ?>"
                        >
                            <span class="menu-card__order-icon" aria-hidden="true">+</span>
                            <span class="screen-reader-text"><?php echo esc_html($order_now_text ?: __('Order now', 'goody')); ?></span>
                        </button>
                    <?php else : ?>
                        <a class="button menu-card__order" href="<?php echo esc_url($order_now_url); ?>" aria-label="<?php echo esc_attr($order_now_text ?: __('Order now', 'goody')); ?>" title="<?php echo esc_attr($order_now_text ?: __('Order now', 'goody')); ?>" <?php if ($is_external_order_url) : ?>target="_blank" rel="noopener"<?php endif; ?>>
                            <span class="menu-card__order-icon" aria-hidden="true">+</span>
                            <span class="screen-reader-text"><?php echo esc_html($order_now_text ?: __('Order now', 'goody')); ?></span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
                <a class="text-link menu-card__details" href="<?php the_permalink(); ?>">
                    <span class="menu-card__details-label">
                        <?php esc_html_e('View dish', 'goody'); ?>
                        <?php echo goody_svg('arrow'); ?>
                    </span>
                    <?php if (! empty($details_popup_lines)) : ?>
                        <span class="menu-card__details-popup" role="tooltip">
                            <span class="menu-card__details-popup-title"><?php the_title(); ?></span>
                            <?php foreach ($details_popup_lines as $details_popup_line) : ?>
                                <span class="menu-card__details-popup-line"><?php echo esc_html($details_popup_line); ?></span>
                            <?php endforeach; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <?php if ($reservation_enabled && is_array($reservation_item) && ! empty($reservation_item['addons']) && is_array($reservation_item['addons'])) : ?>
            <div class="goody-booking-card__addons">
                <?php foreach ($reservation_item['addons'] as $addon) : ?>
                    <label>
                        <input type="checkbox" value="<?php echo esc_attr((string) ($addon['key'] ?? '')); ?>" data-addon-for="<?php echo esc_attr((string) $item_id); ?>">
                        <span><?php echo esc_html((string) ($addon['name'] ?? '')); ?></span>
                        <small><?php echo goody_reservation_price_html((float) ($addon['price'] ?? 0)); ?></small>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (! empty($meta_items)) : ?>
            <div class="menu-card__meta">
                <?php foreach ($meta_items as $meta_item) : ?>
                    <span><?php echo esc_html($meta_item); ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</article>
