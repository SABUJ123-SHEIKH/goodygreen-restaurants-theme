<?php

function goody_register_post_types() {
    $shared_supports = ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'];

    register_post_type('menu_item', [
        'labels' => [
            'name' => __('Menu Items', 'goody'),
            'singular_name' => __('Menu Item', 'goody'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-food',
        'supports' => $shared_supports,
        'rewrite' => ['slug' => 'menu'],
        'has_archive' => true,
    ]);

    register_post_type('offer', [
        'labels' => [
            'name' => __('Offers', 'goody'),
            'singular_name' => __('Offer', 'goody'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-megaphone',
        'supports' => $shared_supports,
        'has_archive' => true,
    ]);

    register_post_type('testimonial', [
        'labels' => [
            'name' => __('Testimonials', 'goody'),
            'singular_name' => __('Testimonial', 'goody'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-testimonial',
        'supports' => ['title', 'editor', 'thumbnail', 'revisions'],
        'has_archive' => true,
    ]);

    register_post_type('event', [
        'labels' => [
            'name' => __('Events', 'goody'),
            'singular_name' => __('Event', 'goody'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-calendar-alt',
        'supports' => $shared_supports,
        'has_archive' => true,
    ]);

    register_post_type('team_member', [
        'labels' => [
            'name' => __('Team Members', 'goody'),
            'singular_name' => __('Team Member', 'goody'),
        ],
        'public' => true,
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-groups',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
        'has_archive' => false,
    ]);

    register_post_type('goody_message', [
        'labels' => [
            'name' => __('Contact Messages', 'goody'),
            'singular_name' => __('Contact Message', 'goody'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'goody-theme',
        'menu_icon' => 'dashicons-email-alt',
        'supports' => ['title', 'editor', 'custom-fields'],
    ]);

    register_taxonomy('menu_category', ['menu_item'], [
        'labels' => [
            'name' => __('Menu Categories', 'goody'),
            'singular_name' => __('Menu Category', 'goody'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'menu-category'],
    ]);

    register_taxonomy('dietary_preference', ['menu_item'], [
        'labels' => [
            'name' => __('Dietary Preferences', 'goody'),
            'singular_name' => __('Dietary Preference', 'goody'),
        ],
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'dietary-preference'],
    ]);

    register_taxonomy('meal_type', ['menu_item'], [
        'labels' => [
            'name' => __('Meal Types', 'goody'),
            'singular_name' => __('Meal Type', 'goody'),
        ],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'meal-type'],
    ]);

    register_taxonomy('offer_tag', ['menu_item'], [
        'labels' => [
            'name' => __('Offer Tags', 'goody'),
            'singular_name' => __('Offer Tag', 'goody'),
        ],
        'hierarchical' => false,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'offer-tag'],
    ]);
}
add_action('init', 'goody_register_post_types');

function goody_get_menu_category_icon_id($term_id) {
    return absint(get_term_meta($term_id, 'goody_menu_category_icon_id', true));
}

function goody_render_menu_category_icon_add_field($taxonomy = '') {
    wp_nonce_field('goody_save_menu_category_icon', 'goody_menu_category_icon_nonce');
    ?>
    <div class="form-field term-goody-menu-category-icon-wrap">
        <label for="goody_menu_category_icon_id"><?php esc_html_e('Category Icon', 'goody'); ?></label>
        <input type="hidden" id="goody_menu_category_icon_id" name="goody_menu_category_icon_id" value="">
        <div class="goody-term-media-actions">
            <button type="button" class="button goody-media-upload" data-target="goody_menu_category_icon_id" data-library-type="image" data-frame-title="<?php esc_attr_e('Select Category Icon', 'goody'); ?>" data-button-text="<?php esc_attr_e('Use Icon', 'goody'); ?>">
                <?php esc_html_e('Upload / Select Icon', 'goody'); ?>
            </button>
            <button type="button" class="button button-secondary goody-media-clear" data-target="goody_menu_category_icon_id">
                <?php esc_html_e('Clear', 'goody'); ?>
            </button>
        </div>
        <div class="goody-media-preview goody-term-media-preview"></div>
        <p class="description"><?php esc_html_e('Upload a small icon image for the menu filter tab.', 'goody'); ?></p>
    </div>
    <?php
}
add_action('menu_category_add_form_fields', 'goody_render_menu_category_icon_add_field');

function goody_render_menu_category_icon_edit_field($term, $taxonomy = '') {
    $icon_id = goody_get_menu_category_icon_id($term->term_id);
    $icon_html = $icon_id ? wp_get_attachment_image($icon_id, 'thumbnail', false, ['alt' => '', 'loading' => 'lazy']) : '';
    wp_nonce_field('goody_save_menu_category_icon', 'goody_menu_category_icon_nonce');
    ?>
    <tr class="form-field term-goody-menu-category-icon-wrap">
        <th scope="row"><label for="goody_menu_category_icon_id"><?php esc_html_e('Category Icon', 'goody'); ?></label></th>
        <td>
            <input type="hidden" id="goody_menu_category_icon_id" name="goody_menu_category_icon_id" value="<?php echo esc_attr($icon_id); ?>">
            <div class="goody-term-media-actions">
                <button type="button" class="button goody-media-upload" data-target="goody_menu_category_icon_id" data-library-type="image" data-frame-title="<?php esc_attr_e('Select Category Icon', 'goody'); ?>" data-button-text="<?php esc_attr_e('Use Icon', 'goody'); ?>">
                    <?php esc_html_e('Upload / Select Icon', 'goody'); ?>
                </button>
                <button type="button" class="button button-secondary goody-media-clear" data-target="goody_menu_category_icon_id">
                    <?php esc_html_e('Clear', 'goody'); ?>
                </button>
            </div>
            <div class="goody-media-preview goody-term-media-preview"><?php echo $icon_html; ?></div>
            <p class="description"><?php esc_html_e('This icon will show inside the menu filter category chip on the homepage.', 'goody'); ?></p>
        </td>
    </tr>
    <?php
}
add_action('menu_category_edit_form_fields', 'goody_render_menu_category_icon_edit_field');

function goody_save_menu_category_icon($term_id) {
    if (! isset($_POST['goody_menu_category_icon_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_menu_category_icon_nonce'])), 'goody_save_menu_category_icon')) {
        return;
    }

    if (! current_user_can('manage_categories')) {
        return;
    }

    $icon_id = isset($_POST['goody_menu_category_icon_id']) ? absint(wp_unslash($_POST['goody_menu_category_icon_id'])) : 0;

    if ($icon_id > 0) {
        update_term_meta($term_id, 'goody_menu_category_icon_id', $icon_id);
        return;
    }

    delete_term_meta($term_id, 'goody_menu_category_icon_id');
}
add_action('created_menu_category', 'goody_save_menu_category_icon');
add_action('edited_menu_category', 'goody_save_menu_category_icon');

function goody_flush_rewrite_on_switch() {
    goody_register_post_types();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'goody_flush_rewrite_on_switch');

function goody_extend_search_query($query) {
    if (is_admin() || ! $query->is_main_query() || ! $query->is_search()) {
        return;
    }

    $query->set('post_type', ['post', 'page', 'menu_item', 'offer', 'event']);
}
add_action('pre_get_posts', 'goody_extend_search_query');
