<?php

function goody_add_meta_boxes() {
    add_meta_box('goody_menu_item_meta', __('Menu Item Details', 'goody'), 'goody_render_menu_meta_box', 'menu_item', 'normal', 'high');
    add_meta_box('goody_offer_meta', __('Offer Details', 'goody'), 'goody_render_offer_meta_box', 'offer', 'normal', 'high');
    add_meta_box('goody_testimonial_meta', __('Testimonial Details', 'goody'), 'goody_render_testimonial_meta_box', 'testimonial', 'normal', 'high');
    add_meta_box('goody_event_meta', __('Event Details', 'goody'), 'goody_render_event_meta_box', 'event', 'normal', 'high');
    add_meta_box('goody_team_meta', __('Team Member Details', 'goody'), 'goody_render_team_meta_box', 'team_member', 'normal', 'high');
}
add_action('add_meta_boxes', 'goody_add_meta_boxes');

function goody_render_meta_field($args) {
    $key = $args['key'];
    $label = $args['label'];
    $type = $args['type'] ?? 'text';
    $value = $args['value'] ?? '';
    $options = $args['options'] ?? [];
    $description = $args['description'] ?? '';

    echo '<p class="goody-field goody-field--' . esc_attr($type) . '">';
    echo '<label for="' . esc_attr($key) . '"><strong>' . esc_html($label) . '</strong></label><br>';

    if ($type === 'textarea') {
        echo '<textarea style="width:100%;min-height:80px;" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">' . esc_textarea($value) . '</textarea>';
    } elseif ($type === 'checkbox') {
        echo '<label><input type="checkbox" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="1" ' . checked($value, '1', false) . '> ' . esc_html__('Enable', 'goody') . '</label>';
    } elseif ($type === 'select') {
        echo '<select style="width:100%;" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
        foreach ($options as $option_value => $option_label) {
            echo '<option value="' . esc_attr($option_value) . '" ' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select>';
    } elseif ($type === 'multiselect') {
        $values = is_array($value) ? $value : [];
        echo '<select style="width:100%;min-height:160px;" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '[]" multiple>';
        foreach ($options as $option_value => $option_label) {
            echo '<option value="' . esc_attr($option_value) . '" ' . selected(in_array((string) $option_value, array_map('strval', $values), true), true, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select>';
    } elseif ($type === 'image') {
        $image_url = $value ? wp_get_attachment_image_url((int) $value, 'medium') : '';
        echo '<input type="hidden" class="goody-media-field" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr($value) . '">';
        echo '<button type="button" class="button goody-media-upload" data-target="' . esc_attr($key) . '">' . esc_html__('Upload Image', 'goody') . '</button>';
        echo '<div class="goody-media-preview" style="margin-top:8px;">';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:160px;height:auto;">';
        }
        echo '</div>';
    } else {
        $input_type = in_array($type, ['number', 'date', 'url', 'time'], true) ? $type : 'text';
        echo '<input style="width:100%;" type="' . esc_attr($input_type) . '" id="' . esc_attr($key) . '" name="' . esc_attr($key) . '" value="' . esc_attr((string) $value) . '">';
    }

    if ($description) {
        echo '<small style="display:block;color:#555;margin-top:4px;">' . esc_html($description) . '</small>';
    }

    echo '</p>';
}

function goody_render_menu_meta_box($post) {
    wp_nonce_field('goody_save_meta_boxes', 'goody_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_menu_price',
        'label' => __('Price', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_price', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_short_desc',
        'label' => __('Short Description', 'goody'),
        'type' => 'textarea',
        'value' => get_post_meta($post->ID, 'goody_menu_short_desc', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_ingredients',
        'label' => __('Ingredients', 'goody'),
        'type' => 'textarea',
        'value' => get_post_meta($post->ID, 'goody_menu_ingredients', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_calories',
        'label' => __('Calories (optional)', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_menu_calories', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_spicy_level',
        'label' => __('Spicy Level', 'goody'),
        'type' => 'select',
        'value' => get_post_meta($post->ID, 'goody_menu_spicy_level', true),
        'options' => [
            '' => __('None', 'goody'),
            'mild' => __('Mild', 'goody'),
            'medium' => __('Medium', 'goody'),
            'hot' => __('Hot', 'goody'),
            'extra-hot' => __('Extra Hot', 'goody'),
        ],
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_badge',
        'label' => __('Badge', 'goody'),
        'type' => 'select',
        'value' => get_post_meta($post->ID, 'goody_menu_badge', true),
        'options' => [
            '' => __('None', 'goody'),
            'new' => __('New', 'goody'),
            'popular' => __('Popular', 'goody'),
            'chef-special' => __('Chef Special', 'goody'),
        ],
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_vegetarian',
        'label' => __('Vegetarian', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_vegetarian', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_gluten_free',
        'label' => __('Gluten Free', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_gluten_free', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_available',
        'label' => __('Available', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_available', true) ?: '1',
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_customizations',
        'label' => __('Customization Options', 'goody'),
        'type' => 'textarea',
        'value' => get_post_meta($post->ID, 'goody_menu_customizations', true),
        'description' => __('Example: Add cheese (+2), switch to almond milk (+1)', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_sort_order',
        'label' => __('Sort Order', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_sort_order', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_is_new',
        'label' => __('Mark as New Dish', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_is_new', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_wc_product_id',
        'label' => __('WooCommerce Product ID (Direct Checkout)', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_wc_product_id', true),
        'description' => __('Set Woo product ID to make Order Now go directly to checkout with this item.', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_wc_qty',
        'label' => __('WooCommerce Quantity', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_wc_qty', true) ?: '1',
        'description' => __('Default quantity used for direct checkout.', 'goody'),
    ]);
}

function goody_render_offer_meta_box($post) {
    wp_nonce_field('goody_save_meta_boxes', 'goody_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_offer_discount_label',
        'label' => __('Discount Label', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_offer_discount_label', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_offer_type',
        'label' => __('Offer Type', 'goody'),
        'type' => 'select',
        'value' => get_post_meta($post->ID, 'goody_offer_type', true),
        'options' => [
            'daily' => __('Daily', 'goody'),
            'weekly' => __('Weekly', 'goody'),
        ],
    ]);

    goody_render_meta_field([
        'key' => 'goody_offer_start_date',
        'label' => __('Start Date', 'goody'),
        'type' => 'date',
        'value' => get_post_meta($post->ID, 'goody_offer_start_date', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_offer_end_date',
        'label' => __('End Date', 'goody'),
        'type' => 'date',
        'value' => get_post_meta($post->ID, 'goody_offer_end_date', true),
    ]);

    $menu_items = get_posts([
        'post_type' => 'menu_item',
        'numberposts' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    $options = [];
    foreach ($menu_items as $item) {
        $options[$item->ID] = $item->post_title;
    }

    goody_render_meta_field([
        'key' => 'goody_offer_linked_menu_items',
        'label' => __('Linked Menu Items', 'goody'),
        'type' => 'multiselect',
        'value' => (array) get_post_meta($post->ID, 'goody_offer_linked_menu_items', true),
        'options' => $options,
    ]);

    goody_render_meta_field([
        'key' => 'goody_offer_active',
        'label' => __('Active Status', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_offer_active', true) ?: '1',
    ]);
}

function goody_render_testimonial_meta_box($post) {
    wp_nonce_field('goody_save_meta_boxes', 'goody_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_testimonial_customer_name',
        'label' => __('Customer Name', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_testimonial_customer_name', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_rating',
        'label' => __('Rating (1-5)', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_testimonial_rating', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_food_rating',
        'label' => __('Food Rating (1-5)', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_testimonial_food_rating', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_ambiance_rating',
        'label' => __('Ambiance Rating (1-5)', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_testimonial_ambiance_rating', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_service_rating',
        'label' => __('Service Rating (1-5)', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_testimonial_service_rating', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_source_label',
        'label' => __('Source Label', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_testimonial_source_label', true),
        'description' => __('Example: Google, Yelp, Direct', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_testimonial_customer_image',
        'label' => __('Customer Image (optional)', 'goody'),
        'type' => 'image',
        'value' => get_post_meta($post->ID, 'goody_testimonial_customer_image', true),
    ]);
}

function goody_render_event_meta_box($post) {
    wp_nonce_field('goody_save_meta_boxes', 'goody_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_event_date',
        'label' => __('Event Date', 'goody'),
        'type' => 'date',
        'value' => get_post_meta($post->ID, 'goody_event_date', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_event_time',
        'label' => __('Event Time', 'goody'),
        'type' => 'time',
        'value' => get_post_meta($post->ID, 'goody_event_time', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_event_cta_label',
        'label' => __('CTA Label', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_event_cta_label', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_event_cta_url',
        'label' => __('CTA URL', 'goody'),
        'type' => 'url',
        'value' => get_post_meta($post->ID, 'goody_event_cta_url', true),
    ]);
}

function goody_render_team_meta_box($post) {
    wp_nonce_field('goody_save_meta_boxes', 'goody_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_team_role',
        'label' => __('Role', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_team_role', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_team_short_bio',
        'label' => __('Short Bio', 'goody'),
        'type' => 'textarea',
        'value' => get_post_meta($post->ID, 'goody_team_short_bio', true),
    ]);
}

function goody_save_meta_boxes($post_id) {
    if (! isset($_POST['goody_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_meta_nonce'])), 'goody_save_meta_boxes')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    $text_fields = [
        'goody_menu_calories',
        'goody_menu_spicy_level',
        'goody_menu_badge',
        'goody_menu_short_desc',
        'goody_menu_ingredients',
        'goody_menu_customizations',
        'goody_offer_discount_label',
        'goody_offer_type',
        'goody_testimonial_customer_name',
        'goody_testimonial_source_label',
        'goody_event_cta_label',
        'goody_team_role',
        'goody_team_short_bio',
    ];

    foreach ($text_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_textarea_field(wp_unslash($_POST[$field])));
        }
    }

    $number_fields = [
        'goody_menu_price',
        'goody_menu_sort_order',
        'goody_testimonial_rating',
        'goody_testimonial_food_rating',
        'goody_testimonial_ambiance_rating',
        'goody_testimonial_service_rating',
    ];
    foreach ($number_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, (string) floatval($_POST[$field]));
        }
    }

    $int_fields = [
        'goody_menu_wc_product_id',
        'goody_menu_wc_qty',
    ];
    foreach ($int_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, (string) absint($_POST[$field]));
        }
    }

    $date_fields = ['goody_offer_start_date', 'goody_offer_end_date', 'goody_event_date'];
    foreach ($date_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, sanitize_text_field(wp_unslash($_POST[$field])));
        }
    }

    if (isset($_POST['goody_event_time'])) {
        update_post_meta($post_id, 'goody_event_time', sanitize_text_field(wp_unslash($_POST['goody_event_time'])));
    }

    $url_fields = ['goody_event_cta_url'];
    foreach ($url_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, esc_url_raw(wp_unslash($_POST[$field])));
        }
    }

    $image_fields = ['goody_testimonial_customer_image'];
    foreach ($image_fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, $field, absint($_POST[$field]));
        }
    }

    $checkbox_fields = [
        'goody_menu_vegetarian',
        'goody_menu_gluten_free',
        'goody_menu_available',
        'goody_menu_is_new',
        'goody_offer_active',
    ];

    foreach ($checkbox_fields as $field) {
        update_post_meta($post_id, $field, isset($_POST[$field]) ? '1' : '0');
    }

    if (isset($_POST['goody_offer_linked_menu_items']) && is_array($_POST['goody_offer_linked_menu_items'])) {
        $linked = array_map('absint', wp_unslash($_POST['goody_offer_linked_menu_items']));
        update_post_meta($post_id, 'goody_offer_linked_menu_items', array_values(array_filter($linked)));
    } else {
        delete_post_meta($post_id, 'goody_offer_linked_menu_items');
    }
}
add_action('save_post', 'goody_save_meta_boxes');
