<?php
$item_id = get_the_ID();
$section = goody_get_single_home_section('menu_item');
$price = get_post_meta($item_id, 'goody_menu_price', true);
$short_desc = get_post_meta($item_id, 'goody_menu_short_desc', true);
$ingredients = get_post_meta($item_id, 'goody_menu_ingredients', true);
$customize = get_post_meta($item_id, 'goody_menu_customizations', true);
$calories = get_post_meta($item_id, 'goody_menu_calories', true);
$spicy = get_post_meta($item_id, 'goody_menu_spicy_level', true);
$badge = get_post_meta($item_id, 'goody_menu_badge', true);
$available = get_post_meta($item_id, 'goody_menu_available', true);
$vegetarian = get_post_meta($item_id, 'goody_menu_vegetarian', true) === '1';
$gluten_free = get_post_meta($item_id, 'goody_menu_gluten_free', true) === '1';
$order_button_text = goody_get_option('hero_primary_text', __('Order Now', 'goody'));
$direct_order = goody_get_menu_item_direct_order_data($item_id);
$direct_order_form = '';
if (! empty($direct_order['product_id'])) {
    $direct_order_form = goody_render_direct_checkout_provider_form((int) $direct_order['product_id'], (int) $direct_order['qty'], [
        'form_class' => 'goody-direct-order-form--single',
        'button_text' => $order_button_text,
        'provider_label' => __('Delivery provider', 'goody'),
        'placeholder' => __('Choose delivery provider', 'goody'),
    ]);
}
$order_url = goody_get_menu_item_checkout_url($item_id);
if ($order_url === '') {
    $order_url = goody_maybe_get_direct_checkout_url(goody_get_option('custom_order_url', ''));
}
if ($order_url === '') {
    $order_url = goody_maybe_get_direct_checkout_url(goody_get_option('hero_primary_url', ''));
}
$single_provider_links = [];
foreach ([
    'glovo' => __('Glovo', 'goody'),
    'ubereats' => __('UberEats', 'goody'),
    'deliveroo' => __('Deliveroo', 'goody'),
] as $provider_key => $provider_label) {
    $provider_url = goody_get_delivery_link($provider_key);
    if ($provider_url === '') {
        continue;
    }

    $single_provider_links[$provider_key] = [
        'label' => $provider_label,
        'url' => $provider_url,
        'is_external' => goody_is_external_url($provider_url),
    ];
}

$tax_terms = [];
foreach (['menu_category', 'dietary_preference', 'meal_type', 'offer_tag'] as $taxonomy) {
    $terms = get_the_terms($item_id, $taxonomy);
    if (! is_wp_error($terms) && ! empty($terms)) {
        foreach ($terms as $term) {
            $tax_terms[] = $term->name;
        }
    }
}
$tax_terms = array_values(array_unique($tax_terms));
?>
<section class="page-section cpt-single cpt-single--menu-item">
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
                    <div class="cpt-single__placeholder"><?php esc_html_e('Menu Item', 'goody'); ?></div>
                <?php endif; ?>
            </div>

            <div class="cpt-single__content">
                <span class="eyebrow"><?php esc_html_e('Menu Item', 'goody'); ?></span>
                <h1><?php the_title(); ?></h1>

                <?php if ($short_desc || get_the_excerpt()) : ?>
                    <p class="cpt-single__lead"><?php echo esc_html($short_desc ?: get_the_excerpt()); ?></p>
                <?php endif; ?>

                <div class="cpt-single__chips">
                    <?php if ($price !== '') : ?><span class="price"><?php echo goody_format_price($price); ?></span><?php endif; ?>
                    <?php if ($badge) : ?><span class="badge"><?php echo esc_html(ucwords(str_replace('-', ' ', $badge))); ?></span><?php endif; ?>
                    <?php if ($calories) : ?><span><?php echo esc_html($calories); ?> kcal</span><?php endif; ?>
                    <?php if ($spicy) : ?><span><?php echo esc_html(ucwords(str_replace('-', ' ', $spicy))); ?></span><?php endif; ?>
                    <?php if ($vegetarian) : ?><span><?php esc_html_e('Vegetarian', 'goody'); ?></span><?php endif; ?>
                    <?php if ($gluten_free) : ?><span><?php esc_html_e('Gluten Free', 'goody'); ?></span><?php endif; ?>
                    <?php if ($available !== '1') : ?><span class="badge badge--unavailable"><?php esc_html_e('Unavailable', 'goody'); ?></span><?php endif; ?>
                </div>

                <div class="button-group cpt-single__actions">
                    <?php if ($direct_order_form !== '') : ?>
                        <?php echo $direct_order_form; ?>
                    <?php elseif (! empty($single_provider_links)) : ?>
                        <?php $first_provider = reset($single_provider_links); ?>
                        <div class="goody-single-provider-form" data-goody-single-provider-form>
                            <label class="goody-provider-select" for="goody-single-provider-<?php echo esc_attr((string) $item_id); ?>">
                                <span><?php esc_html_e('Delivery provider', 'goody'); ?></span>
                                <select id="goody-single-provider-<?php echo esc_attr((string) $item_id); ?>" data-goody-single-provider-select>
                                    <?php foreach ($single_provider_links as $provider_data) : ?>
                                        <option
                                            value="<?php echo esc_url($provider_data['url']); ?>"
                                            data-external="<?php echo ! empty($provider_data['is_external']) ? '1' : '0'; ?>"
                                        ><?php echo esc_html($provider_data['label']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <a
                                class="button goody-single-provider-form__submit"
                                href="<?php echo esc_url((string) ($first_provider['url'] ?? '')); ?>"
                                <?php if (! empty($first_provider['is_external'])) : ?>target="_blank" rel="noopener noreferrer"<?php endif; ?>
                                data-goody-single-provider-link
                            ><?php echo esc_html($order_button_text); ?></a>
                        </div>
                    <?php elseif ($order_url) : ?>
                        <a class="button" href="<?php echo esc_url($order_url); ?>"><?php echo esc_html($order_button_text); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <div class="cpt-single__grid">
            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Dish Details', 'goody'); ?></h2>
                <ul class="cpt-single__list">
                    <?php if ($ingredients) : ?><li><strong><?php esc_html_e('Ingredients', 'goody'); ?>:</strong> <?php echo esc_html($ingredients); ?></li><?php endif; ?>
                    <?php if ($customize) : ?><li><strong><?php esc_html_e('Customize', 'goody'); ?>:</strong> <?php echo esc_html($customize); ?></li><?php endif; ?>
                    <?php if (! empty($tax_terms)) : ?><li><strong><?php esc_html_e('Categories', 'goody'); ?>:</strong> <?php echo esc_html(implode(', ', $tax_terms)); ?></li><?php endif; ?>
                </ul>
            </div>

            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Description', 'goody'); ?></h2>
                <div class="cpt-single__body">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
</section>
