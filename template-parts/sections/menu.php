<?php
$categories = get_terms(['taxonomy' => 'menu_category', 'hide_empty' => true]);
$dietaries = get_terms(['taxonomy' => 'dietary_preference', 'hide_empty' => true]);
$meal_types = get_terms(['taxonomy' => 'meal_type', 'hide_empty' => true]);
$offer_tags = get_terms(['taxonomy' => 'offer_tag', 'hide_empty' => true]);

$menu_query = new WP_Query(goody_build_menu_query_args());
$menu_title = goody_get_option('menu_section_title', __('A menu for every moment', 'goody'));
$menu_text = goody_get_option('menu_section_text', '');
$today_hours = goody_get_today_business_hours();
?>
<section id="menu" class="page-section">
    <div class="container">
        <header class="section-heading menu-heading">
            <div class="menu-heading__copy">
                <span class="eyebrow"><?php esc_html_e('Dynamic Menu', 'goody'); ?></span>
                <h2><?php echo esc_html($menu_title); ?></h2>
                <?php if ($menu_text !== '') : ?>
                    <p><?php echo esc_html($menu_text); ?></p>
                <?php endif; ?>
            </div>

            <?php if (! empty($today_hours['open']) && ! empty($today_hours['close'])) : ?>
                <div class="menu-heading__status" aria-label="<?php esc_attr_e('Kitchen hours today', 'goody'); ?>">
                    <span class="menu-heading__status-dot" aria-hidden="true"></span>
                    <span class="menu-heading__status-label"><?php esc_html_e('Kitchen open', 'goody'); ?></span>
                    <strong><?php echo esc_html($today_hours['open'] . ' - ' . $today_hours['close']); ?></strong>
                </div>
            <?php endif; ?>
        </header>

        <form class="menu-filters" method="post" action="#" onsubmit="return false;">
            <div class="menu-filters__tabs" role="group" aria-label="<?php esc_attr_e('Menu category filter', 'goody'); ?>">
                <input type="hidden" name="category" value="">
                <button type="button" class="menu-filter-chip menu-filter-chip--toggle" data-filter-advanced-toggle aria-expanded="false" aria-controls="menu-advanced-filters">
                    <span class="menu-filter-chip__content">
                        <span class="menu-filter-chip__icon menu-filter-chip__icon--toggle" aria-hidden="true"><?php echo goody_svg('filter'); ?></span>
                        <span class="menu-filter-chip__label"><?php esc_html_e('Filters', 'goody'); ?></span>
                    </span>
                </button>
                <button type="button" class="menu-filter-chip is-active" data-filter-chip data-filter-target="category" data-filter-value="" aria-pressed="true">
                    <span class="menu-filter-chip__content">
                        <span class="menu-filter-chip__label"><?php esc_html_e('All', 'goody'); ?></span>
                    </span>
                </button>
                <?php if (! is_wp_error($categories)) : ?>
                    <?php foreach ($categories as $term) : ?>
                        <?php $category_icon_id = goody_get_menu_category_icon_id($term->term_id); ?>
                        <button type="button" class="menu-filter-chip" data-filter-chip data-filter-target="category" data-filter-value="<?php echo esc_attr($term->slug); ?>" aria-pressed="false">
                            <span class="menu-filter-chip__content">
                                <?php if ($category_icon_id) : ?>
                                    <span class="menu-filter-chip__icon" aria-hidden="true">
                                        <?php
                                        echo wp_get_attachment_image($category_icon_id, 'goody-chip', false, [
                                            'class' => 'menu-filter-chip__icon-image',
                                            'alt' => '',
                                            'loading' => 'lazy',
                                            'decoding' => 'async',
                                            'sizes' => '20px',
                                        ]);
                                        ?>
                                    </span>
                                <?php endif; ?>
                                <span class="menu-filter-chip__label"><?php echo esc_html($term->name); ?></span>
                            </span>
                        </button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="menu-filters__advanced" id="menu-advanced-filters" hidden>
                <label class="menu-filters__field">
                    <span><?php esc_html_e('Dietary', 'goody'); ?></span>
                    <select name="dietary">
                        <option value=""><?php esc_html_e('All dietary', 'goody'); ?></option>
                        <?php if (! is_wp_error($dietaries)) : ?>
                            <?php foreach ($dietaries as $term) : ?>
                                <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </label>

                <label class="menu-filters__field">
                    <span><?php esc_html_e('Meal Type', 'goody'); ?></span>
                    <select name="meal_type">
                        <option value=""><?php esc_html_e('All meal types', 'goody'); ?></option>
                        <?php if (! is_wp_error($meal_types)) : ?>
                            <?php foreach ($meal_types as $term) : ?>
                                <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </label>

                <label class="menu-filters__field">
                    <span><?php esc_html_e('Special Offers', 'goody'); ?></span>
                    <select name="offer">
                        <option value=""><?php esc_html_e('All offers', 'goody'); ?></option>
                        <option value="special"><?php esc_html_e('Active offer dishes', 'goody'); ?></option>
                        <?php if (! is_wp_error($offer_tags)) : ?>
                            <?php foreach ($offer_tags as $term) : ?>
                                <option value="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </label>

                <label class="menu-filters__field menu-filters__field--search">
                    <span><?php esc_html_e('Search', 'goody'); ?></span>
                    <input type="search" name="q" placeholder="<?php esc_attr_e('Dish or ingredient...', 'goody'); ?>">
                </label>

                <label class="menu-filters__check">
                    <input type="checkbox" name="new_only" value="1">
                    <span><?php esc_html_e('New dishes only', 'goody'); ?></span>
                </label>
            </div>

            <div class="menu-filters__footer">
                <p class="menu-count" data-menu-count><?php echo esc_html($menu_query->found_posts); ?> <?php esc_html_e('dishes', 'goody'); ?></p>
                <button type="button" class="button button--outline button--small" data-filter-reset hidden><?php esc_html_e('Reset filters', 'goody'); ?></button>
            </div>
        </form>

        <div data-menu-results>
            <?php echo goody_render_menu_items_markup($menu_query); ?>
        </div>

        <?php
        echo goody_render_direct_order_modal([
            'id' => 'goody-menu-order-modal',
            'title' => __('Choose your delivery provider', 'goody'),
            'eyebrow' => __('Direct checkout', 'goody'),
            'button_text' => __('Checkout', 'goody'),
        ]);
        ?>
    </div>
</section>
