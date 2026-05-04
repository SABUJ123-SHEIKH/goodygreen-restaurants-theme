<?php

function goody_get_reservation_statuses() {
    return [
        'pending-payment' => __('Pending payment', 'goody'),
        'confirmed' => __('Confirmed', 'goody'),
        'preparing' => __('Preparing', 'goody'),
        'ready' => __('Ready', 'goody'),
        'completed' => __('Completed', 'goody'),
        'cancelled' => __('Cancelled', 'goody'),
    ];
}

function goody_get_reservation_status_label($status) {
    $statuses = goody_get_reservation_statuses();
    return $statuses[$status] ?? __('Pending payment', 'goody');
}

function goody_get_reservation_order_types() {
    $defaults = [
        'dine_in' => __('Dine In', 'goody'),
        'pickup' => __('Pickup', 'goody'),
        'delivery' => __('Delivery', 'goody'),
    ];

    $labels = [];
    foreach ($defaults as $type_key => $default_label) {
        $option_key = 'reservation_order_type_label_' . $type_key;
        $custom_label = trim((string) goody_get_option($option_key, ''));
        $labels[$type_key] = $custom_label !== '' ? $custom_label : $default_label;
    }

    return $labels;
}

function goody_get_enabled_reservation_order_types() {
    $all_types = goody_get_reservation_order_types();
    $enabled = [];

    foreach ($all_types as $type_key => $type_label) {
        $option_key = 'reservation_enable_' . $type_key;
        if (goody_get_option($option_key, '1') === '1') {
            $enabled[$type_key] = $type_label;
        }
    }

    return ! empty($enabled) ? $enabled : $all_types;
}

function goody_get_reservation_payment_modes() {
    $deposit = goody_get_reservation_deposit_percentage();

    return [
        'full' => __('Full payment', 'goody'),
        'advance' => sprintf(
            /* translators: %d is a percentage value. */
            __('%d%% advance payment', 'goody'),
            $deposit
        ),
        'cash' => __('Cash on pickup/delivery', 'goody'),
    ];
}

function goody_get_enabled_reservation_payment_modes() {
    $all_modes = goody_get_reservation_payment_modes();
    $enabled = [];
    $map = [
        'full' => 'reservation_enable_full_payment',
        'advance' => 'reservation_enable_advance_payment',
        'cash' => 'reservation_enable_cash_payment',
    ];

    foreach ($all_modes as $mode_key => $mode_label) {
        $option_key = $map[$mode_key] ?? '';
        if ($option_key === '' || goody_get_option($option_key, '1') === '1') {
            $enabled[$mode_key] = $mode_label;
        }
    }

    $can_create_online_order = class_exists('WooCommerce') && goody_get_option('reservation_auto_create_wc_order', '1') === '1';
    if (! $can_create_online_order) {
        if (isset($enabled['cash'])) {
            return ['cash' => $enabled['cash']];
        }

        return ['full' => $all_modes['full']];
    }

    return ! empty($enabled) ? $enabled : ['full' => $all_modes['full']];
}

function goody_get_reservation_deposit_percentage() {
    $percentage = absint(goody_get_option('reservation_deposit_percentage', '50'));
    if ($percentage < 10) {
        $percentage = 50;
    }
    if ($percentage > 100) {
        $percentage = 100;
    }

    return $percentage;
}

function goody_get_reservation_advance_days() {
    $days = absint(goody_get_option('reservation_advance_days', '30'));
    return $days > 0 ? $days : 30;
}

function goody_get_reservation_cutoff_minutes() {
    return max(0, absint(goody_get_option('reservation_cutoff_minutes', '120')));
}

function goody_get_reservation_free_delivery_threshold() {
    return (float) goody_get_option('reservation_free_delivery_threshold', '0');
}

function goody_get_reservation_max_bookings_per_day() {
    return max(0, absint(goody_get_option('reservation_max_bookings_per_day', '0')));
}

function goody_get_reservation_disabled_dates() {
    $raw = (string) goody_get_option('reservation_disabled_dates', '');
    $lines = preg_split('/\r\n|\r|\n/', $raw);
    $dates = [];

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line !== '') {
            $dates[] = sanitize_text_field($line);
        }
    }

    return array_values(array_unique($dates));
}

function goody_get_reservation_status_lookup_page_url() {
    $page_url = goody_normalize_url_input(goody_get_option('reservation_status_page_url', ''));
    if ($page_url !== '') {
        return $page_url;
    }

    $pages = get_pages([
        'post_status' => ['publish', 'private'],
        'number' => 100,
    ]);

    foreach ($pages as $page) {
        if (! $page instanceof WP_Post) {
            continue;
        }

        if (has_shortcode((string) $page->post_content, 'reservation_order_status')) {
            return get_permalink($page);
        }
    }

    return home_url('/');
}

function goody_reservation_price_html($amount) {
    $amount = (float) $amount;

    if (function_exists('wc_price')) {
        return wp_kses_post(wc_price($amount));
    }

    return esc_html('$' . number_format($amount, 2));
}

function goody_reservation_price_plain($amount) {
    $amount = (float) $amount;

    if (function_exists('get_woocommerce_currency_symbol')) {
        return sanitize_text_field((string) get_woocommerce_currency_symbol()) . number_format($amount, 2);
    }

    return '$' . number_format($amount, 2);
}

function goody_validate_bangladeshi_phone($phone) {
    $raw = preg_replace('/\D+/', '', (string) $phone);
    $normalized = $raw;

    if (strpos($raw, '880') === 0 && strlen($raw) === 13) {
        $normalized = '0' . substr($raw, 3);
    }

    $valid = (bool) preg_match('/^01[3-9][0-9]{8}$/', $normalized);

    return [
        'valid' => $valid,
        'normalized' => $normalized,
    ];
}

function goody_get_reservation_reference($reservation_id) {
    $reservation_id = absint($reservation_id);
    if ($reservation_id < 1) {
        return '';
    }

    return 'GGR-' . str_pad((string) $reservation_id, 5, '0', STR_PAD_LEFT);
}

function goody_is_bengali_context() {
    $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
    if (strpos(strtolower((string) $locale), 'bn') === 0) {
        return true;
    }

    if (function_exists('pll_current_language')) {
        $lang = (string) pll_current_language('slug');
        if (strpos(strtolower($lang), 'bn') === 0) {
            return true;
        }
    }

    return false;
}

function goody_get_menu_item_display_name($menu_item_id) {
    $default_name = get_the_title($menu_item_id);
    $bangla_name = sanitize_text_field((string) get_post_meta($menu_item_id, 'goody_menu_name_bn', true));

    if ($bangla_name !== '' && goody_is_bengali_context()) {
        return $bangla_name;
    }

    return $default_name;
}

function goody_get_reservation_step_titles() {
    return [
        1 => goody_get_option('reservation_step_title_1', __('Date', 'goody')),
        2 => goody_get_option('reservation_step_title_2', __('Menu', 'goody')),
        3 => goody_get_option('reservation_step_title_3', __('Time', 'goody')),
        4 => goody_get_option('reservation_step_title_4', __('Order Type', 'goody')),
        5 => goody_get_option('reservation_step_title_5', __('Information', 'goody')),
        6 => goody_get_option('reservation_step_title_6', __('Summary', 'goody')),
    ];
}

function goody_get_reservation_customer_field_settings() {
    return [
        'name' => [
            'required' => goody_get_option('reservation_customer_name_required', '1') === '1',
            'enabled' => true,
        ],
        'phone' => [
            'required' => goody_get_option('reservation_customer_phone_required', '1') === '1',
            'enabled' => true,
        ],
        'guests' => [
            'required' => goody_get_option('reservation_customer_guests_required', '1') === '1',
            'enabled' => true,
        ],
        'address' => [
            'required' => goody_get_option('reservation_customer_address_required', '1') === '1',
            'enabled' => goody_get_option('reservation_customer_address_enabled', '1') === '1',
        ],
        'note' => [
            'required' => goody_get_option('reservation_customer_note_required', '0') === '1',
            'enabled' => goody_get_option('reservation_customer_note_enabled', '1') === '1',
        ],
    ];
}

function goody_register_reservation_post_types() {
    register_post_type('goody_booking_day', [
        'labels' => [
            'name' => __('Booking Dates', 'goody'),
            'singular_name' => __('Booking Date', 'goody'),
            'add_new_item' => __('Add Booking Date', 'goody'),
            'edit_item' => __('Edit Booking Date', 'goody'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'goody-theme',
        'menu_icon' => 'dashicons-calendar',
        'supports' => ['title', 'revisions'],
    ]);

    register_post_type('goody_delivery_zone', [
        'labels' => [
            'name' => __('Delivery Zones', 'goody'),
            'singular_name' => __('Delivery Zone', 'goody'),
            'add_new_item' => __('Add Delivery Zone', 'goody'),
            'edit_item' => __('Edit Delivery Zone', 'goody'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'goody-theme',
        'menu_icon' => 'dashicons-location',
        'supports' => ['title', 'revisions'],
    ]);

    register_post_type('goody_reservation', [
        'labels' => [
            'name' => __('Reservations', 'goody'),
            'singular_name' => __('Reservation', 'goody'),
            'add_new_item' => __('Add Reservation', 'goody'),
            'edit_item' => __('Edit Reservation', 'goody'),
        ],
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => 'goody-theme',
        'menu_icon' => 'dashicons-clipboard',
        'supports' => ['title', 'editor', 'revisions'],
    ]);
}
add_action('init', 'goody_register_reservation_post_types');

function goody_add_reservation_meta_boxes() {
    add_meta_box('goody-menu-reservation-meta', __('Reservation & Pre-Order Settings', 'goody'), 'goody_render_menu_reservation_meta_box', 'menu_item', 'normal', 'default');
    add_meta_box('goody-booking-day-meta', __('Booking Date Details', 'goody'), 'goody_render_booking_day_meta_box', 'goody_booking_day', 'normal', 'high');
    add_meta_box('goody-delivery-zone-meta', __('Delivery Zone Details', 'goody'), 'goody_render_delivery_zone_meta_box', 'goody_delivery_zone', 'normal', 'high');
    add_meta_box('goody-reservation-meta', __('Reservation Details', 'goody'), 'goody_render_reservation_meta_box', 'goody_reservation', 'normal', 'high');

    if (class_exists('WooCommerce')) {
        add_meta_box('goody-reservation-order-meta', __('Reservation Details', 'goody'), 'goody_render_woocommerce_reservation_meta_box', 'shop_order', 'side', 'default');
    }
}
add_action('add_meta_boxes', 'goody_add_reservation_meta_boxes', 20);

function goody_render_menu_reservation_meta_box($post) {
    wp_nonce_field('goody_save_reservation_meta_boxes', 'goody_reservation_meta_nonce');

    goody_render_meta_field([
        'key' => 'goody_menu_name_bn',
        'label' => __('Bengali Name', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_menu_name_bn', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_featured',
        'label' => __('Featured Item', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_featured', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_unit_type',
        'label' => __('Unit Type', 'goody'),
        'type' => 'select',
        'value' => get_post_meta($post->ID, 'goody_menu_unit_type', true) ?: 'item',
        'options' => [
            'item' => __('Per item', 'goody'),
            'person' => __('Per person', 'goody'),
            'kg' => __('Per KG', 'goody'),
            'whole' => __('Whole piece', 'goody'),
        ],
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_unit_label',
        'label' => __('Unit Label', 'goody'),
        'type' => 'text',
        'value' => get_post_meta($post->ID, 'goody_menu_unit_label', true),
        'description' => __('Examples: plate, person, kg, whole mutton', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_min_qty',
        'label' => __('Minimum Quantity', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_min_qty', true) ?: '1',
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_max_qty',
        'label' => __('Maximum Quantity', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_max_qty', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_step_qty',
        'label' => __('Quantity Step', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_step_qty', true) ?: '1',
        'description' => __('Use 0.5 for half-KG style ordering.', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_track_stock',
        'label' => __('Track Reservation Stock', 'goody'),
        'type' => 'checkbox',
        'value' => get_post_meta($post->ID, 'goody_menu_track_stock', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_stock_qty',
        'label' => __('Stock Quantity', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_stock_qty', true),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_capacity_unit',
        'label' => __('Slot Capacity Unit', 'goody'),
        'type' => 'select',
        'value' => get_post_meta($post->ID, 'goody_menu_capacity_unit', true) ?: 'none',
        'options' => [
            'none' => __('No slot capacity effect', 'goody'),
            'person' => __('Consumes person capacity', 'goody'),
            'kg' => __('Consumes KG capacity', 'goody'),
        ],
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_capacity_value',
        'label' => __('Capacity Per Quantity', 'goody'),
        'type' => 'number',
        'value' => get_post_meta($post->ID, 'goody_menu_capacity_value', true) ?: '1',
        'description' => __('Example: 1 person or 1 KG consumed per ordered unit.', 'goody'),
    ]);

    goody_render_meta_field([
        'key' => 'goody_menu_addons_data',
        'label' => __('Add-ons', 'goody'),
        'type' => 'textarea',
        'value' => get_post_meta($post->ID, 'goody_menu_addons_data', true),
        'description' => __('One per line using Name|Price. Example: Extra Salad|120', 'goody'),
    ]);
}

function goody_render_booking_day_meta_box($post) {
    wp_nonce_field('goody_save_reservation_meta_boxes', 'goody_reservation_meta_nonce');

    $slots_raw = (string) get_post_meta($post->ID, 'goody_booking_slots', true);
    $slots = json_decode($slots_raw, true);
    if (! is_array($slots)) {
        $slots = [];
    }
    ?>
    <p>
        <label for="goody_booking_service_date"><strong><?php esc_html_e('Service Date', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="date" id="goody_booking_service_date" name="goody_booking_service_date" value="<?php echo esc_attr((string) get_post_meta($post->ID, 'goody_booking_service_date', true)); ?>">
    </p>
    <p>
        <label>
            <input type="checkbox" name="goody_booking_day_active" value="1" <?php checked(get_post_meta($post->ID, 'goody_booking_day_active', true) ?: '1', '1'); ?>>
            <?php esc_html_e('Date is open for booking', 'goody'); ?>
        </label>
    </p>
    <p>
        <label for="goody_booking_day_note"><strong><?php esc_html_e('Booking Note', 'goody'); ?></strong></label><br>
        <textarea style="width:100%;min-height:88px;" id="goody_booking_day_note" name="goody_booking_day_note"><?php echo esc_textarea((string) get_post_meta($post->ID, 'goody_booking_day_note', true)); ?></textarea>
    </p>
    <p><strong><?php esc_html_e('Time Slots', 'goody'); ?></strong></p>
    <input type="hidden" class="goody-repeater-input" id="goody_booking_slots" name="goody_booking_slots" value="<?php echo esc_attr($slots_raw); ?>">
    <div class="goody-repeater" data-target="goody_booking_slots" data-columns="<?php echo esc_attr(wp_json_encode([
        ['key' => 'time', 'label' => __('Time', 'goody'), 'type' => 'time'],
        ['key' => 'label', 'label' => __('Label', 'goody'), 'type' => 'text'],
        ['key' => 'capacity_persons', 'label' => __('Person Cap.', 'goody'), 'type' => 'text'],
        ['key' => 'capacity_kg', 'label' => __('KG Cap.', 'goody'), 'type' => 'text'],
        ['key' => 'order_types', 'label' => __('Allowed Types', 'goody'), 'type' => 'text'],
        ['key' => 'cutoff_minutes', 'label' => __('Cut-off Min.', 'goody'), 'type' => 'text'],
        ['key' => 'warning', 'label' => __('Warning', 'goody'), 'type' => 'text'],
        ['key' => 'enabled', 'label' => __('Enabled', 'goody'), 'type' => 'checkbox'],
    ])); ?>">
        <?php foreach ($slots as $index => $row) : ?>
            <div class="goody-repeater-row" data-index="<?php echo esc_attr((string) $index); ?>">
                <input type="time" data-field="time" placeholder="<?php esc_attr_e('Time', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['time'] ?? '')); ?>">
                <input type="text" data-field="label" placeholder="<?php esc_attr_e('Label', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['label'] ?? '')); ?>">
                <input type="text" data-field="capacity_persons" placeholder="<?php esc_attr_e('Person Cap.', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['capacity_persons'] ?? '')); ?>">
                <input type="text" data-field="capacity_kg" placeholder="<?php esc_attr_e('KG Cap.', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['capacity_kg'] ?? '')); ?>">
                <input type="text" data-field="order_types" placeholder="<?php esc_attr_e('dine_in,pickup,delivery', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['order_types'] ?? '')); ?>">
                <input type="text" data-field="cutoff_minutes" placeholder="<?php esc_attr_e('120', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['cutoff_minutes'] ?? '')); ?>">
                <input type="text" data-field="warning" placeholder="<?php esc_attr_e('Optional warning message', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['warning'] ?? '')); ?>">
                <label class="goody-repeater-checkbox"><input type="checkbox" data-field="enabled" value="1" <?php checked(($row['enabled'] ?? '1'), '1'); ?>> <?php esc_html_e('Enabled', 'goody'); ?></label>
                <button type="button" class="button goody-row-up">↑</button>
                <button type="button" class="button goody-row-down">↓</button>
                <button type="button" class="button goody-row-remove"><?php esc_html_e('Remove', 'goody'); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button button-secondary goody-repeater-add" data-target="goody_booking_slots"><?php esc_html_e('Add Slot', 'goody'); ?></button>
    <p class="description"><?php esc_html_e('Allowed Types accepts comma-separated values: dine_in, pickup, delivery. Leave blank for all.', 'goody'); ?></p>
    <?php
}

function goody_render_delivery_zone_meta_box($post) {
    wp_nonce_field('goody_save_reservation_meta_boxes', 'goody_reservation_meta_nonce');
    ?>
    <p>
        <label>
            <input type="checkbox" name="goody_delivery_zone_enabled" value="1" <?php checked(get_post_meta($post->ID, 'goody_delivery_zone_enabled', true) ?: '1', '1'); ?>>
            <?php esc_html_e('Zone is active', 'goody'); ?>
        </label>
    </p>
    <p>
        <label for="goody_delivery_zone_areas"><strong><?php esc_html_e('Covered Areas', 'goody'); ?></strong></label><br>
        <textarea style="width:100%;min-height:90px;" id="goody_delivery_zone_areas" name="goody_delivery_zone_areas"><?php echo esc_textarea((string) get_post_meta($post->ID, 'goody_delivery_zone_areas', true)); ?></textarea>
        <small style="display:block;margin-top:4px;"><?php esc_html_e('One area name per line.', 'goody'); ?></small>
    </p>
    <p>
        <label for="goody_delivery_zone_charge"><strong><?php esc_html_e('Delivery Charge', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="number" step="0.01" id="goody_delivery_zone_charge" name="goody_delivery_zone_charge" value="<?php echo esc_attr((string) get_post_meta($post->ID, 'goody_delivery_zone_charge', true)); ?>">
    </p>
    <p>
        <label for="goody_delivery_zone_free_limit"><strong><?php esc_html_e('Free Delivery Minimum', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="number" step="0.01" id="goody_delivery_zone_free_limit" name="goody_delivery_zone_free_limit" value="<?php echo esc_attr((string) get_post_meta($post->ID, 'goody_delivery_zone_free_limit', true)); ?>">
    </p>
    <p>
        <label for="goody_delivery_zone_min_order"><strong><?php esc_html_e('Minimum Order', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="number" step="0.01" id="goody_delivery_zone_min_order" name="goody_delivery_zone_min_order" value="<?php echo esc_attr((string) get_post_meta($post->ID, 'goody_delivery_zone_min_order', true)); ?>">
    </p>
    <p>
        <label for="goody_delivery_zone_warning"><strong><?php esc_html_e('Warning Message', 'goody'); ?></strong></label><br>
        <textarea style="width:100%;min-height:84px;" id="goody_delivery_zone_warning" name="goody_delivery_zone_warning"><?php echo esc_textarea((string) get_post_meta($post->ID, 'goody_delivery_zone_warning', true)); ?></textarea>
    </p>
    <p>
        <label for="goody_delivery_zone_eta"><strong><?php esc_html_e('Estimated Delivery Time', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="text" id="goody_delivery_zone_eta" name="goody_delivery_zone_eta" value="<?php echo esc_attr((string) get_post_meta($post->ID, 'goody_delivery_zone_eta', true)); ?>" placeholder="<?php esc_attr_e('35-45 min', 'goody'); ?>">
    </p>
    <?php
}

function goody_render_reservation_meta_box($post) {
    wp_nonce_field('goody_save_reservation_meta_boxes', 'goody_reservation_meta_nonce');

    $reservation_status = (string) get_post_meta($post->ID, 'goody_reservation_status', true);
    if ($reservation_status === '') {
        $reservation_status = 'pending-payment';
    }
    $summary = get_post_meta($post->ID, 'goody_reservation_summary_html', true);
    $order_id = absint(get_post_meta($post->ID, 'goody_wc_order_id', true));
    $print_url = wp_nonce_url(admin_url('admin-post.php?action=goody_print_reservation&reservation_id=' . $post->ID), 'goody_print_reservation_' . $post->ID);
    ?>
    <p><strong><?php esc_html_e('Reservation Reference:', 'goody'); ?></strong> <?php echo esc_html(goody_get_reservation_reference($post->ID)); ?></p>
    <?php if ($order_id > 0) : ?>
        <p><strong><?php esc_html_e('WooCommerce Order:', 'goody'); ?></strong>
            <a href="<?php echo esc_url(admin_url('post.php?post=' . $order_id . '&action=edit')); ?>">#<?php echo esc_html((string) $order_id); ?></a>
        </p>
    <?php endif; ?>
    <p>
        <label for="goody_reservation_status"><strong><?php esc_html_e('Reservation Status', 'goody'); ?></strong></label><br>
        <select id="goody_reservation_status" name="goody_reservation_status" style="width:100%;">
            <?php foreach (goody_get_reservation_statuses() as $status_key => $status_label) : ?>
                <option value="<?php echo esc_attr($status_key); ?>" <?php selected($reservation_status, $status_key); ?>><?php echo esc_html($status_label); ?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p><a class="button" href="<?php echo esc_url($print_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Print Order', 'goody'); ?></a></p>
    <?php if ($summary) : ?>
        <div class="goody-admin-summary"><?php echo wp_kses_post($summary); ?></div>
    <?php endif; ?>
    <?php
}

function goody_render_woocommerce_reservation_meta_box($post) {
    $reservation_id = absint(get_post_meta($post->ID, '_goody_reservation_id', true));
    if ($reservation_id < 1) {
        echo '<p>' . esc_html__('No reservation is linked with this order.', 'goody') . '</p>';
        return;
    }

    echo '<p><strong>' . esc_html__('Reservation:', 'goody') . '</strong> ';
    echo '<a href="' . esc_url(admin_url('post.php?post=' . $reservation_id . '&action=edit')) . '">';
    echo esc_html(goody_get_reservation_reference($reservation_id));
    echo '</a></p>';
}

function goody_sanitize_booking_slots_json($raw) {
    $rows = json_decode((string) $raw, true);
    if (! is_array($rows)) {
        return wp_json_encode([]);
    }

    $clean = [];
    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $time = sanitize_text_field((string) ($row['time'] ?? ''));
        if ($time === '') {
            continue;
        }

        $clean[] = [
            'time' => $time,
            'label' => sanitize_text_field((string) ($row['label'] ?? '')),
            'capacity_persons' => (string) max(0, absint($row['capacity_persons'] ?? 0)),
            'capacity_kg' => (string) max(0, (float) ($row['capacity_kg'] ?? 0)),
            'order_types' => sanitize_text_field((string) ($row['order_types'] ?? '')),
            'cutoff_minutes' => (string) max(0, absint($row['cutoff_minutes'] ?? 0)),
            'warning' => sanitize_text_field((string) ($row['warning'] ?? '')),
            'enabled' => ! isset($row['enabled']) || (string) $row['enabled'] === '1' ? '1' : '0',
        ];
    }

    return wp_json_encode(array_values($clean));
}

function goody_save_reservation_meta_boxes($post_id) {
    if (! isset($_POST['goody_reservation_meta_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_reservation_meta_nonce'])), 'goody_save_reservation_meta_boxes')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    $post_type = get_post_type($post_id);

    if ($post_type === 'menu_item') {
        $text_fields = [
            'goody_menu_name_bn',
            'goody_menu_unit_type',
            'goody_menu_unit_label',
            'goody_menu_capacity_unit',
            'goody_menu_addons_data',
        ];
        foreach ($text_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_textarea_field(wp_unslash($_POST[$field])));
            }
        }

        $number_fields = [
            'goody_menu_min_qty',
            'goody_menu_max_qty',
            'goody_menu_step_qty',
            'goody_menu_stock_qty',
            'goody_menu_capacity_value',
        ];
        foreach ($number_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, (string) (float) wp_unslash($_POST[$field]));
            }
        }

        update_post_meta($post_id, 'goody_menu_track_stock', isset($_POST['goody_menu_track_stock']) ? '1' : '0');
        update_post_meta($post_id, 'goody_menu_featured', isset($_POST['goody_menu_featured']) ? '1' : '0');
    }

    if ($post_type === 'goody_booking_day') {
        if (isset($_POST['goody_booking_service_date'])) {
            update_post_meta($post_id, 'goody_booking_service_date', sanitize_text_field(wp_unslash($_POST['goody_booking_service_date'])));
        }

        if (isset($_POST['goody_booking_day_note'])) {
            update_post_meta($post_id, 'goody_booking_day_note', sanitize_textarea_field(wp_unslash($_POST['goody_booking_day_note'])));
        }

        update_post_meta($post_id, 'goody_booking_day_active', isset($_POST['goody_booking_day_active']) ? '1' : '0');
        update_post_meta($post_id, 'goody_booking_slots', goody_sanitize_booking_slots_json(wp_unslash($_POST['goody_booking_slots'] ?? '[]')));

        $service_date = sanitize_text_field((string) ($_POST['goody_booking_service_date'] ?? ''));
        if ($service_date !== '') {
            remove_action('save_post', 'goody_save_reservation_meta_boxes');
            wp_update_post([
                'ID' => $post_id,
                'post_title' => sprintf(
                    /* translators: %s is a booking date. */
                    __('Booking %s', 'goody'),
                    $service_date
                ),
            ]);
            add_action('save_post', 'goody_save_reservation_meta_boxes');
        }
    }

    if ($post_type === 'goody_delivery_zone') {
        $textareas = [
            'goody_delivery_zone_areas',
            'goody_delivery_zone_warning',
            'goody_delivery_zone_eta',
        ];
        foreach ($textareas as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, sanitize_textarea_field(wp_unslash($_POST[$field])));
            }
        }

        $number_fields = [
            'goody_delivery_zone_charge',
            'goody_delivery_zone_free_limit',
            'goody_delivery_zone_min_order',
        ];
        foreach ($number_fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, $field, (string) (float) wp_unslash($_POST[$field]));
            }
        }

        update_post_meta($post_id, 'goody_delivery_zone_enabled', isset($_POST['goody_delivery_zone_enabled']) ? '1' : '0');
    }

    if ($post_type === 'goody_reservation' && isset($_POST['goody_reservation_status'])) {
        update_post_meta($post_id, 'goody_reservation_status', sanitize_key(wp_unslash($_POST['goody_reservation_status'])));
        goody_sync_order_tracking_from_reservation($post_id);
    }
}
add_action('save_post', 'goody_save_reservation_meta_boxes');

function goody_get_menu_item_addons($menu_item_id) {
    $raw = (string) get_post_meta($menu_item_id, 'goody_menu_addons_data', true);
    $lines = preg_split('/\r\n|\r|\n/', $raw);
    $addons = [];

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }

        $parts = array_map('trim', explode('|', $line));
        $name = sanitize_text_field((string) ($parts[0] ?? ''));
        $price = isset($parts[1]) ? (float) $parts[1] : 0;
        if ($name === '') {
            continue;
        }

        $addons[] = [
            'key' => sanitize_title($name),
            'name' => $name,
            'price' => $price,
        ];
    }

    return $addons;
}

function goody_get_reservation_stock_usage_map() {
    $map = [];
    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if (! $query->have_posts()) {
        return $map;
    }

    foreach ($query->posts as $reservation_id) {
        $status = (string) get_post_meta($reservation_id, 'goody_reservation_status', true);
        if (in_array($status, ['cancelled'], true)) {
            continue;
        }

        $items = json_decode((string) get_post_meta($reservation_id, 'goody_reservation_items_json', true), true);
        if (! is_array($items)) {
            continue;
        }

        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $menu_item_id = absint($item['id'] ?? 0);
            $qty = (float) ($item['qty'] ?? 0);
            if ($menu_item_id < 1 || $qty <= 0) {
                continue;
            }

            if (! isset($map[$menu_item_id])) {
                $map[$menu_item_id] = 0;
            }

            $map[$menu_item_id] += $qty;
        }
    }

    return $map;
}

function goody_get_reservable_menu_items() {
    $usage_map = goody_get_reservation_stock_usage_map();
    $query = new WP_Query([
        'post_type' => 'menu_item',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'goody_menu_sort_order',
        'orderby' => [
            'meta_value_num' => 'ASC',
            'title' => 'ASC',
        ],
        'order' => 'ASC',
    ]);

    $items = [];
    if (! $query->have_posts()) {
        return $items;
    }

    while ($query->have_posts()) {
        $query->the_post();
        $menu_item_id = get_the_ID();
        $available = get_post_meta($menu_item_id, 'goody_menu_available', true);
        $track_stock = get_post_meta($menu_item_id, 'goody_menu_track_stock', true) === '1';
        $stock_qty = (float) get_post_meta($menu_item_id, 'goody_menu_stock_qty', true);
        $reserved_qty = $usage_map[$menu_item_id] ?? 0;
        $remaining_qty = $track_stock ? max(0, $stock_qty - $reserved_qty) : null;
        $terms = get_the_terms($menu_item_id, 'menu_category');
        $category_ids = [];
        if (! is_wp_error($terms) && ! empty($terms)) {
            foreach ($terms as $term) {
                $category_ids[] = $term->term_id;
            }
        }

        $items[] = [
            'id' => $menu_item_id,
            'name' => goody_get_menu_item_display_name($menu_item_id),
            'default_name' => get_the_title(),
            'bn_name' => (string) get_post_meta($menu_item_id, 'goody_menu_name_bn', true),
            'description' => (string) (get_post_meta($menu_item_id, 'goody_menu_short_desc', true) ?: get_the_excerpt()),
            'price' => (float) get_post_meta($menu_item_id, 'goody_menu_price', true),
            'image' => get_the_post_thumbnail_url($menu_item_id, 'goody-card') ?: '',
            'unit_type' => (string) (get_post_meta($menu_item_id, 'goody_menu_unit_type', true) ?: 'item'),
            'unit_label' => (string) (get_post_meta($menu_item_id, 'goody_menu_unit_label', true) ?: __('item', 'goody')),
            'min_qty' => (float) (get_post_meta($menu_item_id, 'goody_menu_min_qty', true) ?: 1),
            'max_qty' => (float) get_post_meta($menu_item_id, 'goody_menu_max_qty', true),
            'step_qty' => (float) (get_post_meta($menu_item_id, 'goody_menu_step_qty', true) ?: 1),
            'available' => $available === '1',
            'track_stock' => $track_stock,
            'remaining_qty' => $remaining_qty,
            'capacity_unit' => (string) (get_post_meta($menu_item_id, 'goody_menu_capacity_unit', true) ?: 'none'),
            'capacity_value' => (float) (get_post_meta($menu_item_id, 'goody_menu_capacity_value', true) ?: 1),
            'addons' => goody_get_menu_item_addons($menu_item_id),
            'category_ids' => $category_ids,
            'badge' => (string) get_post_meta($menu_item_id, 'goody_menu_badge', true),
            'ingredients' => (string) get_post_meta($menu_item_id, 'goody_menu_ingredients', true),
            'featured' => get_post_meta($menu_item_id, 'goody_menu_featured', true) === '1',
        ];
    }
    wp_reset_postdata();

    return $items;
}

function goody_get_reservation_menu_categories() {
    $terms = get_terms([
        'taxonomy' => 'menu_category',
        'hide_empty' => false,
    ]);

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    $categories = [];
    foreach ($terms as $term) {
        $categories[] = [
            'id' => $term->term_id,
            'name' => $term->name,
            'icon' => goody_get_image_url(goody_get_menu_category_icon_id($term->term_id), 'thumbnail'),
        ];
    }

    return $categories;
}

function goody_get_booking_day_slots($booking_day_id) {
    $slots = json_decode((string) get_post_meta($booking_day_id, 'goody_booking_slots', true), true);
    return is_array($slots) ? array_values($slots) : [];
}

function goody_get_available_booking_days() {
    $cache_key = 'goody_available_booking_days_v1';
    $cached = get_transient($cache_key);
    if (is_array($cached)) {
        return $cached;
    }

    $today = current_time('Y-m-d');
    $max_date = gmdate('Y-m-d', strtotime('+' . goody_get_reservation_advance_days() . ' days', current_time('timestamp')));
    $disabled_dates = goody_get_reservation_disabled_dates();
    $max_bookings = goody_get_reservation_max_bookings_per_day();
    $query = new WP_Query([
        'post_type' => 'goody_booking_day',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'meta_key' => 'goody_booking_service_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'no_found_rows' => true,
        'update_post_term_cache' => false,
    ]);

    $days = [];
    if (! $query->have_posts()) {
        return $days;
    }

    while ($query->have_posts()) {
        $query->the_post();
        $booking_day_id = get_the_ID();
        $service_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
        $active = (string) get_post_meta($booking_day_id, 'goody_booking_day_active', true);
        if ($service_date === '' || $active === '0') {
            continue;
        }
        if ($service_date < $today || $service_date > $max_date) {
            continue;
        }
        if (in_array($service_date, $disabled_dates, true)) {
            continue;
        }

        if ($max_bookings > 0) {
            $day_query = new WP_Query([
                'post_type' => 'goody_reservation',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'meta_query' => [
                    [
                        'key' => 'goody_reservation_date',
                        'value' => $service_date,
                    ],
                    [
                        'key' => 'goody_reservation_status',
                        'value' => 'cancelled',
                        'compare' => '!=',
                    ],
                ],
            ]);
            if ((int) $day_query->found_posts >= $max_bookings) {
                continue;
            }
        }

        $days[] = [
            'id' => $booking_day_id,
            'date' => $service_date,
            'title' => get_the_title(),
            'note' => (string) get_post_meta($booking_day_id, 'goody_booking_day_note', true),
            'display' => date_i18n('D, j M', strtotime($service_date . ' 00:00:00')),
            'slots' => goody_get_booking_day_slots($booking_day_id),
        ];
    }
    wp_reset_postdata();

    set_transient($cache_key, $days, 60);

    return $days;
}

function goody_get_reservation_calendar_days() {
    $available_days = goody_get_available_booking_days();
    $available_map = [];
    $days = [];
    $timezone = wp_timezone();
    $today_timestamp = current_time('timestamp');
    $max_timestamp = strtotime('+' . goody_get_reservation_advance_days() . ' days', $today_timestamp);
    $holiday_message = goody_get_option('reservation_holiday_message', __('Booking is not available on this date.', 'goody'));

    foreach ($available_days as $day) {
        $available_map[$day['date']] = $day;
    }

    foreach ($available_days as $day) {
        $date_timestamp = strtotime($day['date'] . ' 00:00:00');
        if ($date_timestamp === false) {
            continue;
        }

        $days[$day['date']] = [
            'id' => $day['id'],
            'date' => $day['date'],
            'day' => wp_date('D', $date_timestamp, $timezone),
            'number' => wp_date('j', $date_timestamp, $timezone),
            'month' => wp_date('M', $date_timestamp, $timezone),
            'display' => $day['display'],
            'note' => $day['note'],
            'disabled' => false,
            'disabled_reason' => '',
        ];
    }

    foreach (goody_get_reservation_disabled_dates() as $disabled_date) {
        $date_timestamp = strtotime($disabled_date . ' 00:00:00');
        if ($date_timestamp === false || $date_timestamp < strtotime(current_time('Y-m-d') . ' 00:00:00') || $date_timestamp > $max_timestamp) {
            continue;
        }

        if (isset($days[$disabled_date])) {
            $days[$disabled_date]['disabled'] = true;
            $days[$disabled_date]['disabled_reason'] = $holiday_message;
            continue;
        }

        $days[$disabled_date] = [
            'id' => 0,
            'date' => $disabled_date,
            'day' => wp_date('D', $date_timestamp, $timezone),
            'number' => wp_date('j', $date_timestamp, $timezone),
            'month' => wp_date('M', $date_timestamp, $timezone),
            'display' => wp_date('D, j M', $date_timestamp, $timezone),
            'note' => '',
            'disabled' => true,
            'disabled_reason' => $holiday_message,
        ];
    }

    ksort($days);

    return array_values($days);
}

function goody_get_delivery_zones() {
    $query = new WP_Query([
        'post_type' => 'goody_delivery_zone',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC',
    ]);

    $zones = [];
    if (! $query->have_posts()) {
        return $zones;
    }

    while ($query->have_posts()) {
        $query->the_post();
        $zone_id = get_the_ID();
        if (get_post_meta($zone_id, 'goody_delivery_zone_enabled', true) === '0') {
            continue;
        }

        $areas_text = (string) get_post_meta($zone_id, 'goody_delivery_zone_areas', true);
        $areas = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $areas_text))));
        $zones[] = [
            'id' => $zone_id,
            'name' => get_the_title(),
            'areas' => $areas,
            'charge' => (float) get_post_meta($zone_id, 'goody_delivery_zone_charge', true),
            'free_limit' => (float) get_post_meta($zone_id, 'goody_delivery_zone_free_limit', true),
            'min_order' => (float) get_post_meta($zone_id, 'goody_delivery_zone_min_order', true),
            'warning' => (string) get_post_meta($zone_id, 'goody_delivery_zone_warning', true),
            'eta' => (string) get_post_meta($zone_id, 'goody_delivery_zone_eta', true),
        ];
    }
    wp_reset_postdata();

    return $zones;
}

function goody_get_delivery_zone_by_id($zone_id) {
    $zone_id = absint($zone_id);
    if ($zone_id < 1) {
        return null;
    }

    foreach (goody_get_delivery_zones() as $zone) {
        if ((int) $zone['id'] === $zone_id) {
            return $zone;
        }
    }

    return null;
}

function goody_is_slot_cutoff_passed($service_date, $slot_time, $cutoff_minutes = null) {
    $service_date = sanitize_text_field((string) $service_date);
    $slot_time = sanitize_text_field((string) $slot_time);
    if ($service_date === '' || $slot_time === '') {
        return true;
    }

    $service_ts = strtotime($service_date . ' ' . $slot_time);
    if ($service_ts === false) {
        return true;
    }

    $cutoff = $cutoff_minutes === null ? goody_get_reservation_cutoff_minutes() : max(0, absint($cutoff_minutes));
    $threshold = $service_ts - ($cutoff * MINUTE_IN_SECONDS);

    return current_time('timestamp') >= $threshold;
}

function goody_get_reservation_capacity_usage($booking_day_id, $slot_time) {
    $usage = [
        'persons' => 0,
        'kg' => 0,
    ];

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'goody_booking_day_id',
                'value' => absint($booking_day_id),
            ],
            [
                'key' => 'goody_reservation_slot_time',
                'value' => sanitize_text_field((string) $slot_time),
            ],
        ],
    ]);

    foreach ($query->posts as $reservation_id) {
        $status = (string) get_post_meta($reservation_id, 'goody_reservation_status', true);
        if ($status === 'cancelled') {
            continue;
        }

        $usage['persons'] += (float) get_post_meta($reservation_id, 'goody_capacity_persons_used', true);
        $usage['kg'] += (float) get_post_meta($reservation_id, 'goody_capacity_kg_used', true);
    }

    return $usage;
}

function goody_should_lock_booked_reservation_slots() {
    return goody_get_option('reservation_lock_booked_slots', '1') === '1';
}

function goody_is_reservation_slot_reserved($booking_day_id, $slot_time) {
    $booking_day_id = absint($booking_day_id);
    $slot_time = sanitize_text_field((string) $slot_time);
    if (! goody_should_lock_booked_reservation_slots() || $booking_day_id < 1 || $slot_time === '') {
        return false;
    }

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'meta_query' => [
            [
                'key' => 'goody_booking_day_id',
                'value' => $booking_day_id,
            ],
            [
                'key' => 'goody_reservation_slot_time',
                'value' => $slot_time,
            ],
            [
                'key' => 'goody_reservation_status',
                'value' => 'cancelled',
                'compare' => '!=',
            ],
        ],
    ]);

    return ! empty($query->posts);
}

function goody_render_reservation_slot_cards($booking_day_id, $order_type = '', $selected_time = '', $selected_person_need = 0, $selected_kg_need = 0) {
    $service_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
    $slots = goody_get_booking_day_slots($booking_day_id);

    if (empty($slots)) {
        return '<div class="goody-inline-empty">' . esc_html__('No time slots are available for this date yet.', 'goody') . '</div>';
    }

    ob_start();
    echo '<div class="goody-slot-grid">';
    foreach ($slots as $slot) {
        $time = sanitize_text_field((string) ($slot['time'] ?? ''));
        if ($time === '') {
            continue;
        }

        $label = sanitize_text_field((string) ($slot['label'] ?? ''));
        if ($label === '') {
            $label = $time;
        }

        $allowed_types = array_values(array_filter(array_map('sanitize_key', array_map('trim', explode(',', (string) ($slot['order_types'] ?? ''))))));
        $matches_order_type = empty($allowed_types) || $order_type === '' || in_array($order_type, $allowed_types, true);
        $is_enabled = ! isset($slot['enabled']) || (string) $slot['enabled'] === '1';
        $capacity_persons = max(0, absint($slot['capacity_persons'] ?? 0));
        $capacity_kg = max(0, (float) ($slot['capacity_kg'] ?? 0));
        $usage = goody_get_reservation_capacity_usage($booking_day_id, $time);
        $remaining_persons = max(0, $capacity_persons - $usage['persons']);
        $remaining_kg = max(0, $capacity_kg - $usage['kg']);
        $slot_cutoff = max(0, absint($slot['cutoff_minutes'] ?? 0));
        $slot_warning = sanitize_text_field((string) ($slot['warning'] ?? ''));
        $cutoff_passed = goody_is_slot_cutoff_passed($service_date, $time, $slot_cutoff > 0 ? $slot_cutoff : null);
        $fits_persons = $capacity_persons <= 0 || $selected_person_need <= $remaining_persons;
        $fits_kg = $capacity_kg <= 0 || $selected_kg_need <= $remaining_kg;
        $slot_reserved = goody_is_reservation_slot_reserved($booking_day_id, $time);
        $is_disabled = ! $is_enabled || ! $matches_order_type || $cutoff_passed || $slot_reserved || ! $fits_persons || ! $fits_kg;
        $selected_class = $selected_time === $time ? ' is-selected' : '';

        echo '<button type="button" class="goody-slot-card' . esc_attr($selected_class) . '" data-slot="' . esc_attr($time) . '"';
        echo $is_disabled ? ' disabled' : '';
        echo '>';
        echo '<span class="goody-slot-card__time">' . esc_html($label) . '</span>';

        $meta = [];
        if ($capacity_persons > 0) {
            $meta[] = sprintf(
                /* translators: %s is the remaining person capacity. */
                __('%s persons left', 'goody'),
                number_format_i18n($remaining_persons)
            );
        }
        if ($capacity_kg > 0) {
            $meta[] = sprintf(
                /* translators: %s is the remaining KG capacity. */
                __('%s KG left', 'goody'),
                number_format_i18n($remaining_kg, 1)
            );
        }
        if (! empty($meta)) {
            echo '<span class="goody-slot-card__meta">' . esc_html(implode(' • ', $meta)) . '</span>';
        }

        if (! $is_enabled) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Disabled by admin', 'goody') . '</span>';
        } elseif ($cutoff_passed) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Cut-off passed', 'goody') . '</span>';
        } elseif ($slot_reserved) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Already reserved', 'goody') . '</span>';
        } elseif (! $matches_order_type) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Not available for this order type', 'goody') . '</span>';
        } elseif (! $fits_persons || ! $fits_kg) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Not enough remaining capacity', 'goody') . '</span>';
        } elseif ($slot_warning !== '') {
            echo '<span class="goody-slot-card__state">' . esc_html($slot_warning) . '</span>';
        } else {
            echo '<span class="goody-slot-card__state">' . esc_html__('Available', 'goody') . '</span>';
        }

        echo '</button>';
    }
    echo '</div>';

    return ob_get_clean();
}

function goody_normalize_reservation_item_payload($items) {
    if (! is_array($items)) {
        return [];
    }

    $normalized = [];
    foreach ($items as $item) {
        if (! is_array($item)) {
            continue;
        }

        $menu_item_id = absint($item['id'] ?? 0);
        $qty = (float) ($item['qty'] ?? 0);
        $addons = isset($item['addons']) && is_array($item['addons']) ? array_values(array_map('sanitize_title', $item['addons'])) : [];
        if ($menu_item_id < 1 || $qty <= 0) {
            continue;
        }

        $normalized[] = [
            'id' => $menu_item_id,
            'qty' => $qty,
            'addons' => $addons,
        ];
    }

    return $normalized;
}

function goody_prepare_reservation_quote($payload, $require_customer = false) {
    if (! is_array($payload)) {
        return new WP_Error('invalid_payload', __('Invalid reservation request.', 'goody'));
    }

    $booking_day_id = absint($payload['booking_day_id'] ?? 0);
    $slot_time = sanitize_text_field((string) ($payload['slot_time'] ?? ''));
    $order_type = sanitize_key((string) ($payload['order_type'] ?? ''));
    $payment_mode = sanitize_key((string) ($payload['payment_mode'] ?? 'full'));
    $delivery_provider = goody_sanitize_delivery_provider($payload['delivery_provider'] ?? '');
    $guests = max(1, absint($payload['guests'] ?? 1));
    $zone_id = absint($payload['delivery_zone_id'] ?? 0);
    $items_payload = goody_normalize_reservation_item_payload($payload['items'] ?? []);
    $customer = is_array($payload['customer'] ?? null) ? $payload['customer'] : [];
    $notes = sanitize_textarea_field((string) ($customer['note'] ?? ''));
    $address = sanitize_textarea_field((string) ($customer['address'] ?? ''));
    $customer_name = sanitize_text_field((string) ($customer['name'] ?? ''));
    $customer_phone = sanitize_text_field((string) ($customer['phone'] ?? ''));

    if ($booking_day_id < 1 || get_post_type($booking_day_id) !== 'goody_booking_day') {
        return new WP_Error('invalid_day', __('Please select a booking date.', 'goody'));
    }

    $booking_day_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
    if ($booking_day_date === '') {
        return new WP_Error('invalid_day', __('Selected date is not available.', 'goody'));
    }

    if (empty($items_payload)) {
        return new WP_Error('no_items', __('Please add at least one menu item.', 'goody'));
    }

    $order_types = goody_get_enabled_reservation_order_types();
    if (! isset($order_types[$order_type])) {
        return new WP_Error('invalid_order_type', __('Please choose a valid order type.', 'goody'));
    }

    $payment_modes = goody_get_enabled_reservation_payment_modes();
    if (! isset($payment_modes[$payment_mode])) {
        $payment_mode = 'full';
    }

    $max_bookings_per_day = goody_get_reservation_max_bookings_per_day();
    if ($max_bookings_per_day > 0) {
        $day_bookings = new WP_Query([
            'post_type' => 'goody_reservation',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'meta_query' => [
                [
                    'key' => 'goody_reservation_date',
                    'value' => $booking_day_date,
                ],
                [
                    'key' => 'goody_reservation_status',
                    'value' => 'cancelled',
                    'compare' => '!=',
                ],
            ],
        ]);

        if ((int) $day_bookings->found_posts >= $max_bookings_per_day) {
            return new WP_Error('max_day_bookings', __('This date has reached its maximum number of bookings.', 'goody'));
        }
    }

    $slots = goody_get_booking_day_slots($booking_day_id);
    $slot_row = null;
    foreach ($slots as $slot) {
        if (sanitize_text_field((string) ($slot['time'] ?? '')) === $slot_time) {
            $slot_row = $slot;
            break;
        }
    }
    if (! is_array($slot_row)) {
        return new WP_Error('invalid_slot', __('Please choose a time slot.', 'goody'));
    }

    if (isset($slot_row['enabled']) && (string) $slot_row['enabled'] !== '1') {
        return new WP_Error('disabled_slot', __('This time slot is currently unavailable.', 'goody'));
    }

    $allowed_types = array_values(array_filter(array_map('sanitize_key', array_map('trim', explode(',', (string) ($slot_row['order_types'] ?? ''))))));
    if (! empty($allowed_types) && ! in_array($order_type, $allowed_types, true)) {
        return new WP_Error('invalid_slot_type', __('This slot is not available for the selected order type.', 'goody'));
    }

    if (goody_is_slot_cutoff_passed($booking_day_date, $slot_time, absint($slot_row['cutoff_minutes'] ?? 0))) {
        return new WP_Error('slot_cutoff', __('This slot can no longer be booked.', 'goody'));
    }

    if (goody_is_reservation_slot_reserved($booking_day_id, $slot_time)) {
        return new WP_Error('slot_reserved', __('This time slot is already reserved.', 'goody'));
    }

    $reservable_items = goody_get_reservable_menu_items();
    $items_map = [];
    foreach ($reservable_items as $item) {
        $items_map[$item['id']] = $item;
    }

    $items = [];
    $subtotal = 0;
    $capacity_persons = max(0, $guests);
    $capacity_kg = 0;

    foreach ($items_payload as $item_payload) {
        $menu_item_id = $item_payload['id'];
        if (! isset($items_map[$menu_item_id])) {
            return new WP_Error('invalid_item', __('One or more selected items are not available anymore.', 'goody'));
        }

        $item = $items_map[$menu_item_id];
        if (! $item['available']) {
            return new WP_Error('unavailable_item', sprintf(
                /* translators: %s is a menu item name. */
                __('%s is currently unavailable.', 'goody'),
                $item['name']
            ));
        }

        $qty = $item_payload['qty'];
        $min_qty = max(0.1, (float) $item['min_qty']);
        $max_qty = (float) $item['max_qty'];
        $step_qty = max(0.1, (float) $item['step_qty']);

        if ($qty < $min_qty) {
            return new WP_Error('low_qty', sprintf(
                /* translators: %s is the item name. */
                __('Minimum quantity for %s is not met.', 'goody'),
                $item['name']
            ));
        }

        if ($max_qty > 0 && $qty > $max_qty) {
            return new WP_Error('high_qty', sprintf(
                /* translators: %s is the item name. */
                __('Maximum quantity exceeded for %s.', 'goody'),
                $item['name']
            ));
        }

        $step_units = $qty / $step_qty;
        if (abs($step_units - round($step_units)) > 0.001) {
            return new WP_Error('step_qty', sprintf(
                /* translators: %s is the item name. */
                __('Please use the allowed quantity step for %s.', 'goody'),
                $item['name']
            ));
        }

        if ($item['track_stock'] && $item['remaining_qty'] !== null && $qty > $item['remaining_qty']) {
            return new WP_Error('stock_limit', sprintf(
                /* translators: %s is the item name. */
                __('Only limited stock is left for %s.', 'goody'),
                $item['name']
            ));
        }

        $addons = [];
        $addons_total = 0;
        foreach ($item['addons'] as $addon) {
            if (! in_array($addon['key'], $item_payload['addons'], true)) {
                continue;
            }

            $addons[] = $addon;
            $addons_total += (float) $addon['price'] * $qty;
        }

        $line_total = ((float) $item['price'] * $qty) + $addons_total;
        $subtotal += $line_total;

        if ($item['capacity_unit'] === 'person') {
            $capacity_persons += (float) $item['capacity_value'] * $qty;
        } elseif ($item['capacity_unit'] === 'kg') {
            $capacity_kg += (float) $item['capacity_value'] * $qty;
        }

        $items[] = [
            'id' => $item['id'],
            'name' => $item['name'],
            'qty' => $qty,
            'unit_label' => $item['unit_label'],
            'price' => (float) $item['price'],
            'line_total' => $line_total,
            'addons' => $addons,
            'capacity_unit' => $item['capacity_unit'],
            'capacity_value' => (float) $item['capacity_value'],
        ];
    }

    $capacity_usage = goody_get_reservation_capacity_usage($booking_day_id, $slot_time);
    $slot_capacity_persons = max(0, absint($slot_row['capacity_persons'] ?? 0));
    $slot_capacity_kg = max(0, (float) ($slot_row['capacity_kg'] ?? 0));
    if ($slot_capacity_persons > 0 && ($capacity_usage['persons'] + $capacity_persons) > $slot_capacity_persons) {
        return new WP_Error('slot_capacity_persons', __('Not enough person capacity left in this slot.', 'goody'));
    }
    if ($slot_capacity_kg > 0 && ($capacity_usage['kg'] + $capacity_kg) > $slot_capacity_kg) {
        return new WP_Error('slot_capacity_kg', __('Not enough KG capacity left in this slot.', 'goody'));
    }

    $delivery_zone = null;
    $delivery_charge = 0;
    if ($order_type === 'delivery') {
        if ($delivery_provider === '') {
            return new WP_Error('delivery_provider_missing', __('Please choose a delivery provider.', 'goody'));
        }

        $delivery_zone = goody_get_delivery_zone_by_id($zone_id);
        if (! $delivery_zone) {
            return new WP_Error('delivery_zone_missing', __('Please choose a delivery zone.', 'goody'));
        }
    } else {
        $delivery_provider = '';
    }

    if ($order_type === 'delivery') {
        if ($delivery_zone['min_order'] > 0 && $subtotal < $delivery_zone['min_order']) {
            return new WP_Error('delivery_minimum', sprintf(
                /* translators: %s is the minimum delivery order amount. */
                __('Minimum order for this zone is %s.', 'goody'),
                goody_reservation_price_plain($delivery_zone['min_order'])
            ));
        }

        $free_limit = $delivery_zone['free_limit'] > 0 ? $delivery_zone['free_limit'] : goody_get_reservation_free_delivery_threshold();
        if ($free_limit > 0 && $subtotal >= $free_limit) {
            $delivery_charge = 0;
        } else {
            $delivery_charge = (float) $delivery_zone['charge'];
        }
    }

    $type_minimums = [
        'dine_in' => (float) goody_get_option('reservation_min_order_dine_in', '0'),
        'pickup' => (float) goody_get_option('reservation_min_order_pickup', '0'),
        'delivery' => (float) goody_get_option('reservation_min_order_delivery', '0'),
    ];
    $type_minimum = $type_minimums[$order_type] ?? 0;
    if ($type_minimum > 0 && $subtotal < $type_minimum) {
        return new WP_Error('type_minimum', sprintf(
            /* translators: %s is a formatted minimum order amount. */
            __('Minimum order amount is %s for the selected order type.', 'goody'),
            goody_reservation_price_plain($type_minimum)
        ));
    }

    if ($require_customer) {
        $field_settings = goody_get_reservation_customer_field_settings();

        if (($field_settings['name']['required'] ?? true) && $customer_name === '') {
            return new WP_Error('missing_name', __('Please enter your name.', 'goody'));
        }

        $phone_validation = goody_validate_bangladeshi_phone($customer_phone);
        if (($field_settings['phone']['required'] ?? true) && ! $phone_validation['valid']) {
            return new WP_Error('invalid_phone', __('Please enter a valid Bangladeshi phone number.', 'goody'));
        }
        if ($phone_validation['valid']) {
            $customer_phone = $phone_validation['normalized'];
        }

        if (
            $order_type === 'delivery'
            && ($field_settings['address']['required'] ?? true)
            && $address === ''
        ) {
            return new WP_Error('missing_address', __('Please enter a delivery address.', 'goody'));
        }

        if (($field_settings['guests']['required'] ?? true) && $guests < 1) {
            return new WP_Error('missing_guests', __('Please enter the number of guests.', 'goody'));
        }

        if (($field_settings['note']['required'] ?? false) && $notes === '') {
            return new WP_Error('missing_note', __('Please enter your note.', 'goody'));
        }
    }

    $grand_total = $subtotal + $delivery_charge;
    $pay_now_total = $grand_total;
    $balance_due = 0;
    $deposit_percentage = goody_get_reservation_deposit_percentage();
    if ($payment_mode === 'advance') {
        $pay_now_total = round($grand_total * ($deposit_percentage / 100), 2);
        $balance_due = max(0, round($grand_total - $pay_now_total, 2));
    } elseif ($payment_mode === 'cash') {
        $pay_now_total = 0;
        $balance_due = $grand_total;
    }

    return [
        'booking_day_id' => $booking_day_id,
        'booking_date' => $booking_day_date,
        'slot_time' => $slot_time,
        'slot_label' => sanitize_text_field((string) ($slot_row['label'] ?? $slot_time)),
        'order_type' => $order_type,
        'order_type_label' => $order_types[$order_type],
        'payment_mode' => $payment_mode,
        'payment_mode_label' => $payment_modes[$payment_mode] ?? $payment_modes['full'],
        'delivery_provider' => $delivery_provider,
        'delivery_provider_label' => goody_get_delivery_provider_label($delivery_provider),
        'guests' => $guests,
        'items' => $items,
        'subtotal' => $subtotal,
        'delivery_charge' => $delivery_charge,
        'grand_total' => $grand_total,
        'pay_now_total' => $pay_now_total,
        'balance_due' => $balance_due,
        'delivery_zone' => $delivery_zone,
        'capacity_persons' => $capacity_persons,
        'capacity_kg' => $capacity_kg,
        'customer' => [
            'name' => $customer_name,
            'phone' => $customer_phone,
            'address' => $address,
            'note' => $notes,
        ],
    ];
}

function goody_render_reservation_summary_html($quote) {
    if (! is_array($quote)) {
        return '';
    }

    ob_start();
    ?>
    <div class="goody-summary-card">
        <div class="goody-summary-card__section">
            <h4><?php esc_html_e('Reservation Summary', 'goody'); ?></h4>
            <p><?php echo esc_html($quote['booking_date'] . ' • ' . $quote['slot_label']); ?></p>
            <p><?php echo esc_html($quote['order_type_label']); ?> • <?php echo esc_html($quote['payment_mode_label']); ?></p>
            <?php if (! empty($quote['delivery_provider_label'])) : ?>
                <p><?php echo esc_html__('Delivery provider:', 'goody'); ?> <?php echo esc_html($quote['delivery_provider_label']); ?></p>
            <?php endif; ?>
        </div>

        <div class="goody-summary-card__section">
            <?php foreach ($quote['items'] as $item) : ?>
                <div class="goody-summary-line">
                    <div>
                        <strong><?php echo esc_html($item['name']); ?></strong>
                        <small><?php echo esc_html(rtrim(rtrim(number_format($item['qty'], 2, '.', ''), '0'), '.') . ' ' . $item['unit_label']); ?></small>
                        <?php if (! empty($item['addons'])) : ?>
                            <small><?php echo esc_html(implode(', ', wp_list_pluck($item['addons'], 'name'))); ?></small>
                        <?php endif; ?>
                    </div>
                    <span><?php echo goody_reservation_price_html($item['line_total']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="goody-summary-card__section">
            <div class="goody-summary-line"><span><?php esc_html_e('Subtotal', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($quote['subtotal']); ?></strong></div>
            <?php if ($quote['delivery_charge'] > 0 || $quote['order_type'] === 'delivery') : ?>
                <div class="goody-summary-line"><span><?php esc_html_e('Delivery', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($quote['delivery_charge']); ?></strong></div>
            <?php endif; ?>
            <div class="goody-summary-line goody-summary-line--grand"><span><?php esc_html_e('Total', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($quote['grand_total']); ?></strong></div>
            <div class="goody-summary-line goody-summary-line--pay"><span><?php esc_html_e('Pay now', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($quote['pay_now_total']); ?></strong></div>
            <?php if ($quote['balance_due'] > 0) : ?>
                <div class="goody-summary-line"><span><?php esc_html_e('Pay later', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($quote['balance_due']); ?></strong></div>
            <?php endif; ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

function goody_create_reservation_post($quote) {
    $customer_name = sanitize_text_field((string) ($quote['customer']['name'] ?? __('Guest', 'goody')));
    $reservation_id = wp_insert_post([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'post_title' => $customer_name . ' - ' . $quote['booking_date'] . ' ' . $quote['slot_label'],
        'post_content' => sanitize_textarea_field((string) ($quote['customer']['note'] ?? '')),
    ], true);

    if (is_wp_error($reservation_id)) {
        return $reservation_id;
    }

    update_post_meta($reservation_id, 'goody_reservation_code', goody_get_reservation_reference($reservation_id));
    update_post_meta($reservation_id, 'goody_reservation_status', $quote['payment_mode'] === 'cash' ? 'confirmed' : 'pending-payment');
    update_post_meta($reservation_id, 'goody_booking_day_id', (string) $quote['booking_day_id']);
    update_post_meta($reservation_id, 'goody_reservation_date', $quote['booking_date']);
    update_post_meta($reservation_id, 'goody_reservation_slot_time', $quote['slot_time']);
    update_post_meta($reservation_id, 'goody_reservation_slot_label', $quote['slot_label']);
    update_post_meta($reservation_id, 'goody_reservation_order_type', $quote['order_type']);
    update_post_meta($reservation_id, 'goody_reservation_payment_mode', $quote['payment_mode']);
    if (! empty($quote['delivery_provider'])) {
        update_post_meta($reservation_id, 'goody_delivery_provider', sanitize_key((string) $quote['delivery_provider']));
    }
    update_post_meta($reservation_id, 'goody_reservation_guests', (string) $quote['guests']);
    update_post_meta($reservation_id, 'goody_capacity_persons_used', (string) $quote['capacity_persons']);
    update_post_meta($reservation_id, 'goody_capacity_kg_used', (string) $quote['capacity_kg']);
    update_post_meta($reservation_id, 'goody_reservation_name', sanitize_text_field((string) $quote['customer']['name']));
    update_post_meta($reservation_id, 'goody_reservation_phone', sanitize_text_field((string) $quote['customer']['phone']));
    update_post_meta($reservation_id, 'goody_reservation_address', sanitize_textarea_field((string) $quote['customer']['address']));
    update_post_meta($reservation_id, 'goody_reservation_note', sanitize_textarea_field((string) $quote['customer']['note']));
    update_post_meta($reservation_id, 'goody_reservation_subtotal', (string) $quote['subtotal']);
    update_post_meta($reservation_id, 'goody_reservation_delivery_charge', (string) $quote['delivery_charge']);
    update_post_meta($reservation_id, 'goody_reservation_total', (string) $quote['grand_total']);
    update_post_meta($reservation_id, 'goody_reservation_pay_now_total', (string) $quote['pay_now_total']);
    update_post_meta($reservation_id, 'goody_reservation_balance_due', (string) $quote['balance_due']);
    update_post_meta($reservation_id, 'goody_reservation_items_json', wp_json_encode($quote['items']));
    update_post_meta($reservation_id, 'goody_reservation_summary_html', goody_render_reservation_summary_html($quote));
    if (! empty($quote['delivery_zone']['id'])) {
        update_post_meta($reservation_id, 'goody_delivery_zone_id', (string) $quote['delivery_zone']['id']);
        update_post_meta($reservation_id, 'goody_delivery_zone_name', sanitize_text_field((string) $quote['delivery_zone']['name']));
    }

    if (function_exists('goody_reservation_post_created_sync_record')) {
        goody_reservation_post_created_sync_record($reservation_id);
    }

    return $reservation_id;
}

function goody_create_woocommerce_order_from_reservation($reservation_id, $quote) {
    if (! function_exists('wc_create_order') || ! class_exists('WC_Order_Item_Fee')) {
        return new WP_Error('woocommerce_missing', __('WooCommerce is required for payment processing.', 'goody'));
    }

    $order = wc_create_order();
    if (! $order instanceof WC_Order) {
        return new WP_Error('order_create_failed', __('Unable to create the WooCommerce order.', 'goody'));
    }

    foreach ($quote['items'] as $item) {
        $fee_item = new WC_Order_Item_Fee();
        $fee_item->set_name($item['name'] . ' × ' . rtrim(rtrim(number_format($item['qty'], 2, '.', ''), '0'), '.'));
        $fee_item->set_amount((float) $item['line_total']);
        $fee_item->set_total((float) $item['line_total']);
        $fee_item->set_tax_status('none');
        $fee_item->add_meta_data(__('Menu item ID', 'goody'), (string) $item['id'], true);
        if (! empty($item['addons'])) {
            $fee_item->add_meta_data(__('Add-ons', 'goody'), implode(', ', wp_list_pluck($item['addons'], 'name')), true);
        }
        $order->add_item($fee_item);
    }

    if ($quote['delivery_charge'] > 0) {
        $delivery_fee = new WC_Order_Item_Fee();
        $delivery_fee->set_name(__('Delivery charge', 'goody'));
        $delivery_fee->set_amount((float) $quote['delivery_charge']);
        $delivery_fee->set_total((float) $quote['delivery_charge']);
        $delivery_fee->set_tax_status('none');
        $order->add_item($delivery_fee);
    }

    if ($quote['payment_mode'] === 'advance' && $quote['balance_due'] > 0) {
        $balance_fee = new WC_Order_Item_Fee();
        $balance_fee->set_name(__('Balance due later', 'goody'));
        $balance_fee->set_amount((float) (0 - $quote['balance_due']));
        $balance_fee->set_total((float) (0 - $quote['balance_due']));
        $balance_fee->set_tax_status('none');
        $order->add_item($balance_fee);
    }

    if ($quote['payment_mode'] === 'cash' && $quote['grand_total'] > 0) {
        $cash_hold = new WC_Order_Item_Fee();
        $cash_hold->set_name(__('Cash payment to be collected later', 'goody'));
        $cash_hold->set_amount((float) (0 - $quote['grand_total']));
        $cash_hold->set_total((float) (0 - $quote['grand_total']));
        $cash_hold->set_tax_status('none');
        $order->add_item($cash_hold);
    }

    $order->set_created_via('goody_reservation_theme');
    $order->set_customer_note((string) ($quote['customer']['note'] ?? ''));
    $order->set_billing_first_name(sanitize_text_field((string) ($quote['customer']['name'] ?? '')));
    $order->set_billing_phone(sanitize_text_field((string) ($quote['customer']['phone'] ?? '')));
    $order->set_billing_address_1(sanitize_text_field((string) ($quote['customer']['address'] ?? '')));
    $order->set_shipping_first_name(sanitize_text_field((string) ($quote['customer']['name'] ?? '')));
    $order->set_shipping_phone(sanitize_text_field((string) ($quote['customer']['phone'] ?? '')));
    $order->set_shipping_address_1(sanitize_text_field((string) ($quote['customer']['address'] ?? '')));

    $order->update_meta_data('_goody_reservation_id', $reservation_id);
    $order->update_meta_data('_goody_reservation_code', goody_get_reservation_reference($reservation_id));
    $order->update_meta_data('_goody_reservation_date', $quote['booking_date']);
    $order->update_meta_data('_goody_reservation_slot', $quote['slot_label']);
    $order->update_meta_data('_goody_reservation_order_type', $quote['order_type']);
    if (! empty($quote['delivery_provider'])) {
        $order->update_meta_data('_goody_delivery_provider', sanitize_key((string) $quote['delivery_provider']));
        $order->update_meta_data('delivery_provider', sanitize_key((string) $quote['delivery_provider']));
    }
    $order->update_meta_data('_goody_reservation_guests', $quote['guests']);
    $order->update_meta_data('_goody_reservation_total_full', $quote['grand_total']);
    $order->update_meta_data('_goody_reservation_pay_now_total', $quote['pay_now_total']);
    $order->update_meta_data('_goody_reservation_balance_due', $quote['balance_due']);
    $order->update_meta_data('_goody_reservation_status', $quote['payment_mode'] === 'cash' ? 'confirmed' : 'pending-payment');

    $order->calculate_totals(false);

    if ($quote['payment_mode'] === 'cash') {
        $order->set_status('on-hold', __('Cash payment selected during reservation.', 'goody'));
    } else {
        $order->set_status('pending');
    }

    $order->save();

    update_post_meta($reservation_id, 'goody_wc_order_id', (string) $order->get_id());
    update_post_meta($reservation_id, 'goody_reservation_payment_status', sanitize_text_field((string) $order->get_status()));
    if (function_exists('goody_upsert_reservation_record')) {
        goody_upsert_reservation_record($reservation_id);
    }

    return $order;
}

function goody_get_reservation_redirect_url($reservation_id, $order = null) {
    $reservation_id = absint($reservation_id);
    if ($order instanceof WC_Order) {
        $order_total = (float) $order->get_total();
        if ($order_total > 0) {
            return $order->get_checkout_payment_url();
        }

        return $order->get_checkout_order_received_url();
    }

    return add_query_arg([
        'reference' => goody_get_reservation_reference($reservation_id),
    ], goody_get_reservation_status_lookup_page_url());
}

function goody_ajax_reservation_slots() {
    check_ajax_referer('goody_nonce', 'nonce');

    $booking_day_id = absint($_POST['booking_day_id'] ?? 0);
    $order_type = sanitize_key((string) ($_POST['order_type'] ?? ''));
    $person_need = (float) ($_POST['person_need'] ?? 0);
    $kg_need = (float) ($_POST['kg_need'] ?? 0);
    $selected_time = sanitize_text_field((string) ($_POST['selected_time'] ?? ''));

    wp_send_json_success([
        'html' => goody_render_reservation_slot_cards($booking_day_id, $order_type, $selected_time, $person_need, $kg_need),
    ]);
}
add_action('wp_ajax_goody_reservation_slots', 'goody_ajax_reservation_slots');
add_action('wp_ajax_nopriv_goody_reservation_slots', 'goody_ajax_reservation_slots');

function goody_ajax_reservation_quote() {
    check_ajax_referer('goody_nonce', 'nonce');

    $payload = json_decode((string) wp_unslash($_POST['payload'] ?? '{}'), true);
    $quote = goody_prepare_reservation_quote($payload, false);

    if (is_wp_error($quote)) {
        wp_send_json_error([
            'message' => $quote->get_error_message(),
        ], 422);
    }

    wp_send_json_success([
        'summary_html' => goody_render_reservation_summary_html($quote),
        'pay_now_total' => (float) $quote['pay_now_total'],
        'grand_total' => (float) $quote['grand_total'],
        'balance_due' => (float) $quote['balance_due'],
    ]);
}
add_action('wp_ajax_goody_reservation_quote', 'goody_ajax_reservation_quote');
add_action('wp_ajax_nopriv_goody_reservation_quote', 'goody_ajax_reservation_quote');

function goody_ajax_reservation_submit() {
    check_ajax_referer('goody_nonce', 'nonce');

    $payload = json_decode((string) wp_unslash($_POST['payload'] ?? '{}'), true);
    $quote = goody_prepare_reservation_quote($payload, true);

    if (is_wp_error($quote)) {
        wp_send_json_error([
            'message' => $quote->get_error_message(),
        ], 422);
    }

    $reservation_id = goody_create_reservation_post($quote);
    if (is_wp_error($reservation_id)) {
        wp_send_json_error([
            'message' => $reservation_id->get_error_message(),
        ], 500);
    }

    $order = null;
    $should_create_wc_order = class_exists('WooCommerce') && goody_get_option('reservation_auto_create_wc_order', '1') === '1';
    if ($should_create_wc_order) {
        $order = goody_create_woocommerce_order_from_reservation($reservation_id, $quote);
        if (is_wp_error($order)) {
            wp_trash_post($reservation_id);
            wp_send_json_error([
                'message' => $order->get_error_message(),
            ], 500);
        }
    }

    wp_send_json_success([
        'reservation_id' => $reservation_id,
        'reference' => goody_get_reservation_reference($reservation_id),
        'redirect_url' => goody_get_reservation_redirect_url($reservation_id, $order instanceof WC_Order ? $order : null),
        'message' => goody_get_option('reservation_success_message', __('Reservation created successfully.', 'goody')),
    ]);
}
add_action('wp_ajax_goody_reservation_submit', 'goody_ajax_reservation_submit');
add_action('wp_ajax_nopriv_goody_reservation_submit', 'goody_ajax_reservation_submit');

function goody_find_reservation_by_reference_and_phone($reference, $phone = '') {
    $reference = strtoupper(sanitize_text_field((string) $reference));
    $phone = sanitize_text_field((string) $phone);
    if ($reference === '') {
        return null;
    }

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'meta_query' => [
            [
                'key' => 'goody_reservation_code',
                'value' => $reference,
            ],
        ],
    ]);

    if (empty($query->posts[0])) {
        return null;
    }

    $reservation_id = absint($query->posts[0]);
    if ($phone !== '') {
        $stored_phone = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_phone', true));
        $candidate = goody_validate_bangladeshi_phone($phone);
        if ($candidate['valid'] && $stored_phone !== $candidate['normalized']) {
            return null;
        }
    }

    return get_post($reservation_id);
}

function goody_get_reservation_tracking_step_definitions() {
    return [
        'pending-payment' => __('Requested', 'goody'),
        'confirmed' => __('Confirmed', 'goody'),
        'preparing' => __('Preparing', 'goody'),
        'ready' => __('Ready', 'goody'),
        'completed' => __('Completed', 'goody'),
    ];
}

function goody_get_reservation_tracking_step_index($status) {
    $status = sanitize_key((string) $status);
    $keys = array_keys(goody_get_reservation_tracking_step_definitions());
    $index = array_search($status, $keys, true);

    return $index === false ? -1 : (int) $index;
}

function goody_render_reservation_tracking_panel($reservation) {
    if (! $reservation instanceof WP_Post) {
        return '';
    }

    $reservation_id = (int) $reservation->ID;
    $status = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_status', true));
    if ($status === '') {
        $status = 'pending-payment';
    }

    $status_label = goody_get_reservation_status_label($status);
    $reference = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_code', true));
    $booking_date = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_date', true));
    $slot_label = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_slot_label', true));
    $order_type = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_order_type', true));
    $delivery_provider = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_delivery_provider', true));
    if ($delivery_provider === '') {
        $delivery_provider = sanitize_text_field((string) get_post_meta($reservation_id, '_goody_delivery_provider', true));
    }
    $payment_mode = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_payment_mode', true));
    $total = (float) get_post_meta($reservation_id, 'goody_reservation_total', true);
    $tracking_eta = '';
    $tracking_note = '';
    $linked_order_id = absint(get_post_meta($reservation_id, 'goody_wc_order_id', true));
    if ($linked_order_id > 0 && function_exists('wc_get_order')) {
        $linked_order = wc_get_order($linked_order_id);
        if ($linked_order instanceof WC_Order) {
            $tracking_eta = sanitize_text_field((string) $linked_order->get_meta('_goody_tracking_eta', true));
            $tracking_note = sanitize_text_field((string) $linked_order->get_meta('_goody_tracking_note', true));
        }
    }
    $created_time = goody_format_tracking_datetime(get_post_time('U', true, $reservation));
    $updated_time = goody_format_tracking_datetime(get_post_modified_time('U', true, $reservation));
    $steps = goody_get_reservation_tracking_step_definitions();
    if ($order_type === 'dine_in') {
        $steps['ready'] = __('Ready to Dine In', 'goody');
    } elseif ($order_type === 'pickup') {
        $steps['ready'] = __('Ready to Pickup', 'goody');
    } elseif ($order_type === 'delivery') {
        if ($delivery_provider !== '') {
            $steps['ready'] = sprintf(__('Ready to Delivery Provider (%s)', 'goody'), $delivery_provider);
        } else {
            $steps['ready'] = __('Ready to Delivery Provider', 'goody');
        }
    }
    $current_index = goody_get_reservation_tracking_step_index($status);
    $is_cancelled = $status === 'cancelled';
    $order_types = goody_get_reservation_order_types();
    $payment_modes = goody_get_reservation_payment_modes();
    $timeline_descriptions = [
        'pending-payment' => __('Reservation request has been received.', 'goody'),
        'confirmed' => __('Reservation has been confirmed.', 'goody'),
        'preparing' => __('Kitchen is preparing your reservation order.', 'goody'),
        'ready' => $order_type === 'dine_in'
            ? __('Reservation order is ready for dine in.', 'goody')
            : ($order_type === 'pickup'
                ? __('Reservation order is ready for pickup.', 'goody')
                : __('Reservation order is ready for delivery provider.', 'goody')),
        'completed' => __('Reservation has been completed.', 'goody'),
    ];

    ob_start();
    ?>
    <div class="goody-reservation-tracking <?php echo $is_cancelled ? 'is-cancelled' : ''; ?>">
        <div class="goody-reservation-tracking__head">
            <span><?php esc_html_e('Reservation timeline', 'goody'); ?></span>
            <strong><?php echo esc_html($status_label); ?></strong>
        </div>

        <div class="goody-reservation-tracking__meta">
            <div><span><?php esc_html_e('Reference', 'goody'); ?></span><strong><?php echo esc_html($reference !== '' ? $reference : ('#' . $reservation_id)); ?></strong></div>
            <div><span><?php esc_html_e('Date / Time', 'goody'); ?></span><strong><?php echo esc_html(trim($booking_date . ' ' . $slot_label) ?: __('Not selected', 'goody')); ?></strong></div>
            <div><span><?php esc_html_e('Order Type', 'goody'); ?></span><strong><?php echo esc_html($order_types[$order_type] ?? ($order_type !== '' ? $order_type : __('Not selected', 'goody'))); ?></strong></div>
            <div><span><?php esc_html_e('Payment', 'goody'); ?></span><strong><?php echo esc_html($payment_modes[$payment_mode] ?? ($payment_mode !== '' ? $payment_mode : __('Not selected', 'goody'))); ?></strong></div>
            <?php if ($total > 0) : ?>
                <div><span><?php esc_html_e('Total', 'goody'); ?></span><strong><?php echo goody_reservation_price_html($total); ?></strong></div>
            <?php endif; ?>
            <?php if ($tracking_eta !== '') : ?>
                <div><span><?php esc_html_e('ETA', 'goody'); ?></span><strong><?php echo esc_html($tracking_eta); ?></strong></div>
            <?php endif; ?>
            <?php if ($tracking_note !== '') : ?>
                <div><span><?php esc_html_e('Latest Note', 'goody'); ?></span><strong><?php echo esc_html($tracking_note); ?></strong></div>
            <?php endif; ?>
        </div>

        <ol class="goody-reservation-tracking-steps" aria-label="<?php esc_attr_e('Reservation status steps', 'goody'); ?>">
            <?php $index = 0; ?>
            <?php foreach ($steps as $step_key => $step_label) : ?>
                <?php
                $step_classes = ['goody-reservation-tracking-step'];
                if (! $is_cancelled && $current_index >= 0 && $index <= $current_index) {
                    $step_classes[] = 'is-done';
                }
                if (! $is_cancelled && $index === $current_index) {
                    $step_classes[] = 'is-active';
                }
                ?>
                <li class="<?php echo esc_attr(implode(' ', $step_classes)); ?>">
                    <span class="goody-reservation-tracking-step__dot" aria-hidden="true"></span>
                    <span class="goody-reservation-tracking-step__label"><?php echo esc_html($step_label); ?></span>
                </li>
                <?php $index++; ?>
            <?php endforeach; ?>
        </ol>

        <div class="goody-reservation-tracking-timeline">
            <?php if ($is_cancelled) : ?>
                <article class="goody-reservation-event is-cancelled">
                    <span class="goody-reservation-event__dot" aria-hidden="true"></span>
                    <div>
                        <h4><?php esc_html_e('Cancelled', 'goody'); ?></h4>
                        <p><?php esc_html_e('Reservation was cancelled.', 'goody'); ?></p>
                        <?php if ($updated_time !== '') : ?><time><?php echo esc_html($updated_time); ?></time><?php endif; ?>
                    </div>
                </article>
            <?php else : ?>
                <?php $index = 0; ?>
                <?php foreach ($steps as $step_key => $step_label) : ?>
                    <?php if ($current_index >= 0 && $index > $current_index) { break; } ?>
                    <article class="goody-reservation-event is-done">
                        <span class="goody-reservation-event__dot" aria-hidden="true"></span>
                        <div>
                            <h4><?php echo esc_html($step_label); ?></h4>
                            <p><?php echo esc_html($timeline_descriptions[$step_key] ?? $step_label); ?></p>
                            <?php $event_time = $index === 0 ? $created_time : $updated_time; ?>
                            <?php if ($event_time !== '') : ?><time><?php echo esc_html($event_time); ?></time><?php endif; ?>
                        </div>
                    </article>
                    <?php $index++; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

function goody_ajax_reservation_status_lookup() {
    check_ajax_referer('goody_nonce', 'nonce');

    $reference = sanitize_text_field((string) ($_POST['reference'] ?? ''));
    $phone = sanitize_text_field((string) ($_POST['phone'] ?? ''));
    $reservation = goody_find_reservation_by_reference_and_phone($reference, $phone);

    if (! $reservation instanceof WP_Post) {
        wp_send_json_error([
            'message' => __('No reservation was found with that reference and phone number.', 'goody'),
        ], 404);
    }

    $status = (string) get_post_meta($reservation->ID, 'goody_reservation_status', true);
    $summary = (string) get_post_meta($reservation->ID, 'goody_reservation_summary_html', true);
    $payment_status = (string) get_post_meta($reservation->ID, 'goody_reservation_payment_status', true);

    wp_send_json_success([
        'status_label' => goody_get_reservation_status_label($status),
        'payment_status' => $payment_status,
        'tracking_html' => goody_render_reservation_tracking_panel($reservation),
        'summary_html' => $summary,
    ]);
}
add_action('wp_ajax_goody_reservation_status_lookup', 'goody_ajax_reservation_status_lookup');
add_action('wp_ajax_nopriv_goody_reservation_status_lookup', 'goody_ajax_reservation_status_lookup');

function goody_build_reservation_frontend_config() {
    $today_hours = goody_get_today_business_hours();
    $order_types = goody_get_enabled_reservation_order_types();
    $payment_modes = goody_get_enabled_reservation_payment_modes();
    $contact_phone = sanitize_text_field((string) goody_get_option('contact_phone', ''));
    $whatsapp_number = preg_replace('/\D+/', '', (string) goody_get_option('contact_whatsapp_number', ''));

    return [
        'dates' => goody_get_available_booking_days(),
        'calendarDays' => goody_get_reservation_calendar_days(),
        'menuItems' => goody_get_reservable_menu_items(),
        'categories' => goody_get_reservation_menu_categories(),
        'zones' => goody_get_delivery_zones(),
        'orderTypes' => $order_types,
        'paymentModes' => $payment_modes,
        'deliveryProviders' => goody_get_delivery_provider_choices(),
        'steps' => array_values(goody_get_reservation_step_titles()),
        'fieldSettings' => goody_get_reservation_customer_field_settings(),
        'texts' => [
            'pickupWarning' => goody_get_option('reservation_pickup_warning', __('Pickup orders must be collected on time from the restaurant.', 'goody')),
            'deliveryWarning' => goody_get_option('reservation_delivery_warning', __('Delivery address is required for delivery orders.', 'goody')),
            'cashWarning' => goody_get_option('reservation_cash_warning', __('Cash orders stay reserved and will be confirmed by the restaurant.', 'goody')),
            'dineInNote' => goody_get_option('reservation_dine_in_note', __('Dine-in reservations are held for a limited time after the selected slot starts.', 'goody')),
            'bookingNotice' => goody_get_option('reservation_booking_notice', __('Choose your preferred date, dishes, slot, and payment plan below.', 'goody')),
            'errorMessage' => goody_get_option('reservation_error_message', __('Please review the form and try again.', 'goody')),
            'stepCounterPrefix' => goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')),
            'summaryPlaceholder' => __('Your selected date, menu, slot, and totals will update here.', 'goody'),
            'selectItem' => __('Add to order', 'goody'),
            'updateItem' => __('Update selection', 'goody'),
            'unavailableItem' => __('Unavailable', 'goody'),
            'missingDate' => __('Please choose a date first.', 'goody'),
            'missingItem' => __('Please add at least one menu item.', 'goody'),
            'missingSlot' => __('Please choose a time slot.', 'goody'),
            'missingOrderType' => __('Please choose an order type.', 'goody'),
            'missingPaymentMode' => __('Please choose a payment option.', 'goody'),
            'missingZone' => __('Please choose a delivery zone.', 'goody'),
            'missingDeliveryProvider' => __('Please choose a delivery provider.', 'goody'),
            'missingName' => __('Please enter your name.', 'goody'),
            'missingPhone' => __('Please enter your phone number.', 'goody'),
            'missingAddress' => __('Please enter the delivery address.', 'goody'),
            'statusCurrent' => __('Current status:', 'goody'),
            'labelDate' => __('Date', 'goody'),
            'labelSlot' => __('Time slot', 'goody'),
            'labelOrderType' => __('Order type', 'goody'),
            'labelPayment' => __('Payment', 'goody'),
            'labelGuests' => __('Guests', 'goody'),
            'labelSubtotal' => __('Subtotal', 'goody'),
            'labelDelivery' => __('Delivery', 'goody'),
            'labelDeliveryProvider' => __('Delivery provider', 'goody'),
            'labelTotal' => __('Total', 'goody'),
        ],
        'settings' => [
            'depositPercentage' => goody_get_reservation_deposit_percentage(),
        ],
        'todayHours' => [
            'day' => $today_hours['day'] ?? '',
            'open' => $today_hours['open'] ?? '',
            'close' => $today_hours['close'] ?? '',
        ],
        'contactActions' => [
            'phone' => $contact_phone,
            'phoneUrl' => $contact_phone !== '' ? 'tel:' . preg_replace('/\s+/', '', $contact_phone) : '',
            'callText' => goody_get_option('contact_call_button_text', __('Call Now', 'goody')),
            'whatsappUrl' => $whatsapp_number !== '' ? 'https://wa.me/' . $whatsapp_number : '',
            'whatsappText' => goody_get_option('contact_whatsapp_button_text', __('WhatsApp Us', 'goody')),
        ],
    ];
}

function goody_render_reservation_menu_cards($menu_items, $categories = [], $show_direct_order = false) {
    ob_start();
    ?>
    <div class="goody-booking-menu" data-goody-menu-filter-app>
        <div class="goody-booking-menu__filters" role="tablist" aria-label="<?php esc_attr_e('Menu categories', 'goody'); ?>">
            <button type="button" class="goody-filter-pill is-active" data-menu-filter="all" role="tab" aria-selected="true"><?php esc_html_e('All', 'goody'); ?></button>
            <?php foreach ($categories as $category) : ?>
                <button type="button" class="goody-filter-pill" data-menu-filter="<?php echo esc_attr((string) $category['id']); ?>" role="tab" aria-selected="false">
                    <?php if (! empty($category['icon'])) : ?>
                        <img src="<?php echo esc_url($category['icon']); ?>" alt="" loading="lazy">
                    <?php endif; ?>
                    <span><?php echo esc_html($category['name']); ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="goody-booking-menu__grid">
            <?php foreach ($menu_items as $item) : ?>
                <?php
                $data_categories = implode(',', array_map('absint', $item['category_ids']));
                $is_disabled = ! $item['available'] || ($item['track_stock'] && $item['remaining_qty'] !== null && $item['remaining_qty'] <= 0);
                $qty_display = $item['min_qty'] > 0 ? $item['min_qty'] : 1;
                $badge_text = $item['badge'] !== '' ? $item['badge'] : ($item['featured'] ? __('Featured', 'goody') : '');
                $qty_label = $item['unit_type'] === 'kg' ? __('KG', 'goody') : __('Quantity', 'goody');
                $direct_order = $show_direct_order ? goody_get_menu_item_direct_order_data((int) $item['id']) : ['product_id' => 0, 'qty' => 1];
                $direct_order_product_id = ! empty($direct_order['product_id']) ? (int) $direct_order['product_id'] : 0;
                $direct_order_qty = ! empty($direct_order['qty']) ? (int) $direct_order['qty'] : $qty_display;
                $direct_order_price = wp_strip_all_tags(goody_reservation_price_html($item['price']));
                ?>
                <article class="goody-booking-card<?php echo $is_disabled ? ' is-disabled' : ''; ?>" data-menu-item-id="<?php echo esc_attr((string) $item['id']); ?>" data-category-ids="<?php echo esc_attr($data_categories); ?>">
                    <?php if ($badge_text !== '') : ?>
                        <span class="goody-booking-card__badge"><?php echo esc_html($badge_text); ?></span>
                    <?php endif; ?>
                    <span class="goody-booking-card__check" aria-hidden="true">✓</span>
                    <div class="goody-booking-card__media">
                        <?php if ($item['image'] !== '') : ?>
                            <img src="<?php echo esc_url($item['image']); ?>" alt="<?php echo esc_attr($item['name']); ?>" loading="lazy">
                        <?php else : ?>
                            <div class="goody-booking-card__placeholder"><?php esc_html_e('Menu', 'goody'); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="goody-booking-card__body">
                        <div class="goody-booking-card__head">
                            <div>
                                <h4><?php echo esc_html($item['name']); ?></h4>
                                <?php if (! goody_is_bengali_context() && $item['bn_name'] !== '') : ?>
                                    <p class="goody-booking-card__subhead"><?php echo esc_html($item['bn_name']); ?></p>
                                <?php endif; ?>
                            </div>
                            <strong><?php echo goody_reservation_price_html($item['price']); ?></strong>
                        </div>
                        <?php if ($item['description'] !== '') : ?>
                            <p><?php echo esc_html($item['description']); ?></p>
                        <?php endif; ?>
                        <div class="goody-booking-card__meta">
                            <span><?php echo esc_html($item['unit_label']); ?></span>
                            <?php if ($item['track_stock'] && $item['remaining_qty'] !== null) : ?>
                                <span><?php echo esc_html(sprintf(__('Stock left: %s', 'goody'), rtrim(rtrim(number_format($item['remaining_qty'], 2, '.', ''), '0'), '.'))); ?></span>
                            <?php endif; ?>
                        </div>

                        <?php if (! empty($item['addons'])) : ?>
                            <div class="goody-booking-card__addons">
                                <?php foreach ($item['addons'] as $addon) : ?>
                                    <label>
                                        <input type="checkbox" value="<?php echo esc_attr($addon['key']); ?>" data-addon-for="<?php echo esc_attr((string) $item['id']); ?>">
                                        <span><?php echo esc_html($addon['name']); ?></span>
                                        <small><?php echo goody_reservation_price_html($addon['price']); ?></small>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="goody-booking-card__actions">
                            <?php if ($show_direct_order && $direct_order_product_id > 0 && ! $is_disabled) : ?>
                                <button
                                    type="button"
                                    class="goody-item-select goody-direct-order-button"
                                    data-goody-direct-order-open
                                    data-goody-direct-order-target="goody-reservation-menu-order-modal"
                                    data-product-id="<?php echo esc_attr((string) $direct_order_product_id); ?>"
                                    data-quantity="<?php echo esc_attr((string) $direct_order_qty); ?>"
                                    data-min-quantity="<?php echo esc_attr((string) max(0.1, (float) $item['min_qty'])); ?>"
                                    <?php if ((float) $item['max_qty'] > 0) : ?>data-max-quantity="<?php echo esc_attr((string) $item['max_qty']); ?>"<?php endif; ?>
                                    data-step-quantity="<?php echo esc_attr((string) max(0.1, (float) $item['step_qty'])); ?>"
                                    data-title="<?php echo esc_attr($item['name']); ?>"
                                    data-price="<?php echo esc_attr($direct_order_price); ?>"
                                    data-image="<?php echo esc_url($item['image']); ?>"
                                >
                                    <?php esc_html_e('Order now', 'goody'); ?>
                                </button>
                            <?php else : ?>
                                <label class="goody-qty-control">
                                    <span><?php echo esc_html($qty_label); ?></span>
                                    <input
                                        type="number"
                                        min="<?php echo esc_attr((string) max(0.1, $item['min_qty'])); ?>"
                                        <?php if ((float) $item['max_qty'] > 0) : ?>max="<?php echo esc_attr((string) $item['max_qty']); ?>"<?php endif; ?>
                                        step="<?php echo esc_attr((string) max(0.1, $item['step_qty'])); ?>"
                                        value="<?php echo esc_attr((string) $qty_display); ?>"
                                        data-qty-for="<?php echo esc_attr((string) $item['id']); ?>"
                                        <?php echo $is_disabled ? 'disabled' : ''; ?>
                                    >
                                </label>
                                <button type="button" class="goody-item-select" data-select-item="<?php echo esc_attr((string) $item['id']); ?>" <?php echo $is_disabled ? 'disabled' : ''; ?>>
                                    <?php echo $is_disabled ? esc_html__('Unavailable', 'goody') : esc_html__('Add to order', 'goody'); ?>
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

function goody_render_reservation_booking_shortcode($atts = []) {
    $config = goody_build_reservation_frontend_config();
    $menu_markup = goody_render_reservation_menu_cards($config['menuItems'], $config['categories']);
    $calendar_days = $config['calendarDays'];
    $default_pickup_warning = $config['texts']['pickupWarning'];
    $default_delivery_warning = $config['texts']['deliveryWarning'];
    $default_cash_warning = $config['texts']['cashWarning'];
    $default_dine_in_note = $config['texts']['dineInNote'];
    $booking_notice = $config['texts']['bookingNotice'];
    $step_titles = goody_get_reservation_step_titles();
    $order_types = $config['orderTypes'];
    $payment_modes = $config['paymentModes'];
    $delivery_providers = $config['deliveryProviders'];
    $field_settings = $config['fieldSettings'];
    $today_hours = $config['todayHours'];
    $contact_actions = $config['contactActions'];
    $next_text = goody_get_option('reservation_next_button_text', __('Next', 'goody'));
    $back_text = goody_get_option('reservation_back_button_text', __('Back', 'goody'));
    $submit_text = goody_get_option('reservation_submit_button_text', __('Create reservation', 'goody'));
    ob_start();
    ?>
    <div class="goody-reservation-shell">
        <div class="goody-reservation-app" data-goody-reservation-app>
            <script type="application/json" class="goody-reservation-config"><?php echo wp_json_encode($config); ?></script>

            <div class="goody-reservation-intro goody-reservation-intro--centered">
                <span class="goody-reservation-kicker"><?php esc_html_e('Reservation & Pre-Order', 'goody'); ?></span>
                <h2><?php echo esc_html(goody_get_option('reservation_page_title', __('Book your table or pre-order your meal', 'goody'))); ?></h2>
                <p><?php echo esc_html($booking_notice); ?></p>
            </div>

            <div class="goody-reservation-progress">
                <div class="goody-reservation-progress__meta">
                    <strong class="goody-step-counter" data-step-counter><?php echo esc_html(goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')) . ' 1/6'); ?></strong>
                    <span class="goody-step-title-current" data-current-step-title><?php echo esc_html($step_titles[1] ?? __('Date', 'goody')); ?></span>
                </div>
                <div class="goody-reservation-progress__track" aria-hidden="true">
                    <span class="goody-reservation-progress__fill" data-progress-fill></span>
                </div>
            </div>

            <ol class="goody-reservation-steps" aria-label="<?php esc_attr_e('Booking steps', 'goody'); ?>">
                <?php foreach ($step_titles as $step_number => $step_title) : ?>
                    <li class="<?php echo $step_number === 1 ? 'is-active' : ''; ?>" data-step-marker="<?php echo esc_attr((string) $step_number); ?>"><span><?php echo esc_html((string) $step_number); ?></span><strong><?php echo esc_html($step_title); ?></strong></li>
                <?php endforeach; ?>
            </ol>

            <div class="goody-final-message" data-final-message></div>

            <div class="goody-reservation-layout">
                <div class="goody-reservation-panels">
                    <section class="goody-reservation-panel is-active" data-step-panel="1">
                        <h3><?php echo esc_html(sprintf('%s 1: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[1] ?? __('Date', 'goody'))); ?></h3>
                        <div class="goody-date-grid">
                            <?php foreach ($calendar_days as $day) : ?>
                                <button type="button" class="goody-date-card<?php echo $day['disabled'] ? ' is-disabled' : ''; ?>" data-booking-day="<?php echo esc_attr((string) $day['id']); ?>" <?php echo $day['disabled'] ? 'disabled' : ''; ?>>
                                    <span class="goody-date-card__day"><?php echo esc_html($day['day']); ?></span>
                                    <strong class="goody-date-card__number"><?php echo esc_html($day['number']); ?></strong>
                                    <small class="goody-date-card__month"><?php echo esc_html($day['month']); ?></small>
                                    <?php if ($day['note'] !== '') : ?>
                                        <em class="goody-date-card__note"><?php echo esc_html($day['note']); ?></em>
                                    <?php elseif ($day['disabled_reason'] !== '') : ?>
                                        <em class="goody-date-card__note"><?php echo esc_html($day['disabled_reason']); ?></em>
                                    <?php endif; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($config['dates'])) : ?>
                            <div class="goody-inline-empty"><?php esc_html_e('No booking dates are configured yet. Add them from Goody Green > Booking Dates.', 'goody'); ?></div>
                        <?php endif; ?>
                        <div class="goody-panel-actions">
                            <button type="button" class="button goody-step-next" data-next-step="2"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="2">
                        <h3><?php echo esc_html(sprintf('%s 2: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[2] ?? __('Menu', 'goody'))); ?></h3>
                        <?php echo $menu_markup; ?>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="1"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="3"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="3">
                        <h3><?php echo esc_html(sprintf('%s 3: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[3] ?? __('Time', 'goody'))); ?></h3>
                        <div data-slot-results class="goody-slot-results">
                            <div class="goody-inline-empty"><?php esc_html_e('Select a date and menu items first to see the live time slots.', 'goody'); ?></div>
                        </div>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="2"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="4"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="4">
                        <h3><?php echo esc_html(sprintf('%s 4: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[4] ?? __('Order Type', 'goody'))); ?></h3>
                        <div class="goody-choice-grid">
                            <?php foreach ($order_types as $type_key => $type_label) : ?>
                                <button type="button" class="goody-choice-card" data-order-type="<?php echo esc_attr($type_key); ?>"><?php echo esc_html($type_label); ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="goody-order-notices">
                            <div class="goody-notice" data-order-warning="dine_in" hidden><?php echo esc_html($default_dine_in_note); ?></div>
                            <div class="goody-notice" data-order-warning="pickup" hidden><?php echo esc_html($default_pickup_warning); ?></div>
                            <div class="goody-notice" data-order-warning="delivery" hidden><?php echo esc_html($default_delivery_warning); ?></div>
                        </div>

                        <div class="goody-zone-wrap" data-zone-wrap hidden>
                            <label for="goody-delivery-zone"><strong><?php esc_html_e('Delivery zone', 'goody'); ?></strong></label>
                            <select id="goody-delivery-zone" data-delivery-zone>
                                <option value=""><?php esc_html_e('Choose area', 'goody'); ?></option>
                                <?php foreach ($config['zones'] as $zone) : ?>
                                    <option value="<?php echo esc_attr((string) $zone['id']); ?>"><?php echo esc_html($zone['name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description" data-zone-warning></p>
                        </div>

                        <div class="goody-delivery-provider-wrap" data-delivery-provider-wrap hidden>
                            <label for="goody-delivery-provider"><strong><?php esc_html_e('Delivery provider', 'goody'); ?></strong></label>
                            <select id="goody-delivery-provider" data-delivery-provider>
                                <option value=""><?php esc_html_e('Choose provider', 'goody'); ?></option>
                                <?php foreach ($delivery_providers as $provider_key => $provider_label) : ?>
                                    <option value="<?php echo esc_attr($provider_key); ?>"><?php echo esc_html($provider_label); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="goody-payment-modes">
                            <?php foreach ($payment_modes as $payment_key => $payment_label) : ?>
                                <button type="button" class="goody-choice-card goody-choice-card--payment" data-payment-mode="<?php echo esc_attr($payment_key); ?>"><?php echo esc_html($payment_label); ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="goody-notice" data-payment-warning hidden><?php echo esc_html($default_cash_warning); ?></div>

                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="3"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="5"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="5">
                        <h3><?php echo esc_html(sprintf('%s 5: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[5] ?? __('Information', 'goody'))); ?></h3>
                        <div class="goody-form-grid">
                            <label>
                                <span><?php esc_html_e('Name', 'goody'); ?><?php echo ($field_settings['name']['required'] ?? false) ? ' *' : ''; ?></span>
                                <input type="text" data-customer-field="name" placeholder="<?php esc_attr_e('Your name', 'goody'); ?>">
                            </label>
                            <label>
                                <span><?php esc_html_e('Phone', 'goody'); ?><?php echo ($field_settings['phone']['required'] ?? false) ? ' *' : ''; ?></span>
                                <input type="text" inputmode="tel" data-customer-field="phone" placeholder="<?php esc_attr_e('01XXXXXXXXX', 'goody'); ?>">
                            </label>
                            <label>
                                <span><?php esc_html_e('Guests / Persons', 'goody'); ?><?php echo ($field_settings['guests']['required'] ?? false) ? ' *' : ''; ?></span>
                                <input type="number" min="1" step="1" value="1" data-customer-field="guests">
                            </label>
                            <label class="goody-form-grid__full" data-address-wrap hidden>
                                <span><?php esc_html_e('Delivery address', 'goody'); ?><?php echo ($field_settings['address']['required'] ?? false) ? ' *' : ''; ?></span>
                                <textarea data-customer-field="address" rows="3"></textarea>
                            </label>
                            <?php if ($field_settings['note']['enabled'] ?? true) : ?>
                                <label class="goody-form-grid__full">
                                    <span><?php esc_html_e('Note', 'goody'); ?><?php echo ($field_settings['note']['required'] ?? false) ? ' *' : ''; ?></span>
                                    <textarea data-customer-field="note" rows="3"></textarea>
                                </label>
                            <?php endif; ?>
                        </div>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="4"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next goody-summary-trigger" data-next-step="6"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="6">
                        <h3><?php echo esc_html(sprintf('%s 6: %s', goody_get_option('reservation_step_counter_prefix', __('Step', 'goody')), $step_titles[6] ?? __('Summary', 'goody'))); ?></h3>
                        <div data-final-summary></div>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="5"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-reservation-submit" data-submit-booking><?php echo esc_html($submit_text); ?></button>
                        </div>
                    </section>
                </div>

                <aside class="goody-reservation-sidebar">
                    <div class="goody-sidebar-card">
                        <h4><?php esc_html_e('Live summary', 'goody'); ?></h4>
                        <div data-live-summary>
                            <p><?php esc_html_e('Your selected date, menu, slot, and totals will update here.', 'goody'); ?></p>
                        </div>
                    </div>
                    <div class="goody-sidebar-card goody-sidebar-card--kitchen">
                        <h4><?php esc_html_e('Kitchen timing', 'goody'); ?></h4>
                        <?php if (! empty($today_hours['open']) || ! empty($today_hours['close'])) : ?>
                            <p class="goody-kitchen-timing__hours"><?php echo esc_html(trim(($today_hours['open'] ?? '') . ' - ' . ($today_hours['close'] ?? ''))); ?></p>
                            <p class="goody-kitchen-timing__day"><?php echo esc_html($today_hours['day'] ?? ''); ?></p>
                        <?php else : ?>
                            <p><?php esc_html_e('Update business hours from the dashboard to show kitchen timing here.', 'goody'); ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="goody-sidebar-card">
                        <h4><?php esc_html_e('Opening Hours', 'goody'); ?></h4>
                        <ul class="goody-sidebar-hours">
                            <?php foreach (goody_get_business_hours() as $hours) : ?>
                                <li><span><?php echo esc_html($hours['day']); ?></span><strong><?php echo esc_html($hours['open'] . ' - ' . $hours['close']); ?></strong></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php if ($contact_actions['phoneUrl'] !== '' || $contact_actions['whatsappUrl'] !== '') : ?>
                        <div class="goody-sidebar-card goody-sidebar-card--actions">
                            <h4><?php esc_html_e('Quick contact', 'goody'); ?></h4>
                            <div class="goody-sidebar-actions">
                                <?php if ($contact_actions['phoneUrl'] !== '') : ?>
                                    <a class="button button--ghost" href="<?php echo esc_url($contact_actions['phoneUrl']); ?>"><?php echo esc_html($contact_actions['callText']); ?></a>
                                <?php endif; ?>
                                <?php if ($contact_actions['whatsappUrl'] !== '') : ?>
                                    <a class="button" href="<?php echo esc_url($contact_actions['whatsappUrl']); ?>" target="_blank" rel="noopener"><?php echo esc_html($contact_actions['whatsappText']); ?></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('reservation_booking', 'goody_render_reservation_booking_shortcode');

function goody_render_reservation_menu_shortcode($atts = []) {
    $config = goody_build_reservation_frontend_config();
    ob_start();
    ?>
    <div class="goody-reservation-shell goody-reservation-shell--menu">
        <div class="goody-reservation-app goody-reservation-app--menu" data-goody-reservation-app>
            <script type="application/json" class="goody-reservation-config"><?php echo wp_json_encode($config); ?></script>
            <div class="goody-reservation-intro">
                <span class="goody-reservation-kicker"><?php esc_html_e('Menu', 'goody'); ?></span>
                <h2><?php echo esc_html(goody_get_option('menu_page_title', __('Restaurant Menu', 'goody'))); ?></h2>
                <p><?php echo esc_html(goody_get_option('menu_section_text', __('Browse the available menu and add-ons for your booking.', 'goody'))); ?></p>
            </div>
            <?php echo goody_render_reservation_menu_cards($config['menuItems'], $config['categories'], true); ?>
            <?php
            echo goody_render_direct_order_modal([
                'id' => 'goody-reservation-menu-order-modal',
                'title' => __('Choose your delivery provider', 'goody'),
                'eyebrow' => __('Direct checkout', 'goody'),
                'button_text' => __('Checkout', 'goody'),
            ]);
            ?>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('reservation_menu', 'goody_render_reservation_menu_shortcode');

function goody_render_order_tracking_status_panel($tracking_order_id = '', $tracking_order_key = '') {
    $tracking_order_id = sanitize_text_field((string) $tracking_order_id);
    $tracking_order_key = sanitize_text_field((string) $tracking_order_key);
    $has_tracking_identity = $tracking_order_id !== '' || $tracking_order_key !== '';
    $tracking_state = $has_tracking_identity ? goody_get_tracking_state(true, $tracking_order_id, $tracking_order_key) : goody_get_tracking_empty_state();
    $tracking_steps = goody_get_tracking_steps($tracking_state);
    $tracking_timeline = is_array($tracking_state['timeline'] ?? null) ? $tracking_state['timeline'] : [];
    $tracking_message = trim((string) ($tracking_state['message'] ?? ''));
    $tracking_url = goody_normalize_url_input((string) ($tracking_state['url'] ?? ''));
    $tracking_is_external = goody_is_external_url($tracking_url);
    $tracking_status = trim((string) ($tracking_state['status'] ?? ''));
    $tracking_stage = trim((string) ($tracking_state['stage'] ?? ''));
    $tracking_eta = trim((string) ($tracking_state['eta'] ?? ''));
    $tracking_note = trim((string) ($tracking_state['note'] ?? ''));
    $tracking_provider = trim((string) ($tracking_state['provider'] ?? ''));
    $tracking_consignment = trim((string) ($tracking_state['consignment_id'] ?? ''));
    if ($tracking_consignment === '') {
        $tracking_consignment = trim((string) ($tracking_state['order_id'] ?? $tracking_order_id));
    }

    if (empty($tracking_timeline) && $tracking_message !== '') {
        $tracking_timeline[] = [
            'stage' => $tracking_stage,
            'title' => $tracking_status !== '' ? $tracking_status : __('Tracking Update', 'goody'),
            'description' => $tracking_message,
            'time' => '',
            'completed' => true,
        ];
    }

    $tracking_stage_defs = goody_get_tracking_stage_definitions();
    $tracking_stage_key = goody_normalize_tracking_stage($tracking_stage);
    $tracking_stage_label = $tracking_stage_key !== '' && isset($tracking_stage_defs[$tracking_stage_key])
        ? (string) $tracking_stage_defs[$tracking_stage_key]
        : ($tracking_stage !== '' ? ucwords(str_replace(['_', '-'], ' ', $tracking_stage)) : '');

    if ($tracking_provider !== '' && ! empty($tracking_steps)) {
        foreach ($tracking_steps as &$tracking_step) {
            if (($tracking_step['key'] ?? '') === 'with_delivery_provider') {
                $tracking_step['label'] = sprintf(__('Delivery Provider (%s)', 'goody'), $tracking_provider);
                break;
            }
        }
        unset($tracking_step);
    }

    $status_page_url = get_permalink();
    if (! $status_page_url) {
        $status_page_url = goody_get_reservation_status_lookup_page_url();
    }

    ob_start();
    ?>
    <div class="goody-status-card goody-status-card--tracking">
        <h3><?php esc_html_e('Order Tracking', 'goody'); ?></h3>
        <p><?php echo esc_html(goody_get_option('tracking_description', __('Track your order in real time with our delivery partner.', 'goody'))); ?></p>

        <form class="tracking-search-form goody-order-status-form" action="<?php echo esc_url($status_page_url); ?>" method="get">
            <input type="hidden" name="tracking" value="order">
            <label>
                <span><?php esc_html_e('Order ID', 'goody'); ?></span>
                <input type="text" name="order_id" value="<?php echo esc_attr($tracking_order_id); ?>" placeholder="<?php esc_attr_e('Enter order ID', 'goody'); ?>">
            </label>
            <label>
                <span><?php esc_html_e('Order Key', 'goody'); ?></span>
                <input type="text" name="key" value="<?php echo esc_attr($tracking_order_key); ?>" placeholder="<?php esc_attr_e('Order key (optional)', 'goody'); ?>">
            </label>
            <button class="button" type="submit"><?php esc_html_e('Track Order', 'goody'); ?></button>
        </form>

        <div class="tracking-box tracking-box--primary tracking-box--page" data-goody-tracking-box data-tracking-strict="1" data-tracking-base="<?php echo esc_attr(trim((string) goody_get_option('tracking_description', ''))); ?>">
            <div class="tracking-box__head">
                <h4><?php echo esc_html(goody_get_option('tracking_title', __('Track Your Order', 'goody'))); ?></h4>
                <?php if ($tracking_url !== '') : ?>
                    <a class="button button--ghost" data-goody-tracking-link href="<?php echo esc_url($tracking_url); ?>" <?php if ($tracking_is_external) : ?>target="_blank" rel="noopener"<?php endif; ?>><?php esc_html_e('Provider Link', 'goody'); ?></a>
                <?php endif; ?>
            </div>
            <p data-goody-tracking-text><?php echo esc_html($tracking_message !== '' ? $tracking_message : __('Enter an order ID to load order tracking.', 'goody')); ?></p>
        </div>

        <?php if ($has_tracking_identity) : ?>
            <div class="tracking-info-grid goody-status-tracking-info">
                <div class="tracking-info-row">
                    <span class="tracking-info-label"><?php esc_html_e('Order / Consignment', 'goody'); ?></span>
                    <strong class="tracking-info-value" data-tracking-consignment-value><?php echo esc_html($tracking_consignment !== '' ? $tracking_consignment : __('Not available', 'goody')); ?></strong>
                </div>
                <div class="tracking-info-row">
                    <span class="tracking-info-label"><?php esc_html_e('Current Status', 'goody'); ?></span>
                    <strong class="tracking-info-value" data-tracking-status-value><?php echo esc_html($tracking_status !== '' ? $tracking_status : __('Waiting for update', 'goody')); ?></strong>
                </div>
                <?php if ($tracking_provider !== '') : ?>
                    <div class="tracking-info-row">
                        <span class="tracking-info-label"><?php esc_html_e('Delivery Provider', 'goody'); ?></span>
                        <strong class="tracking-info-value" data-tracking-provider-value><?php echo esc_html($tracking_provider); ?></strong>
                    </div>
                <?php endif; ?>
                <?php if ($tracking_stage !== '' || $tracking_eta !== '' || $tracking_provider !== '') : ?>
                    <div class="tracking-info-row">
                        <span class="tracking-info-label"><?php esc_html_e('Delivery Details', 'goody'); ?></span>
                        <div class="tracking-info-value tracking-info-value--stack" data-tracking-delivery-value>
                            <?php if ($tracking_provider !== '') : ?><strong><?php echo esc_html($tracking_provider); ?></strong><?php endif; ?>
                            <?php if ($tracking_stage_label !== '') : ?><span><?php echo esc_html($tracking_stage_label); ?></span><?php endif; ?>
                            <?php if ($tracking_eta !== '') : ?><span><?php echo esc_html($tracking_eta); ?></span><?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if ($tracking_note !== '') : ?>
                    <div class="tracking-info-row">
                        <span class="tracking-info-label"><?php esc_html_e('Latest Note', 'goody'); ?></span>
                        <strong class="tracking-info-value" data-tracking-note-value><?php echo esc_html($tracking_note); ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <div class="tracking-steps-wrap">
                <ol class="tracking-steps">
                    <?php foreach ($tracking_steps as $step) : ?>
                        <?php
                        $step_classes = ['tracking-step'];
                        if (! empty($step['done'])) {
                            $step_classes[] = 'is-done';
                        }
                        if (! empty($step['active'])) {
                            $step_classes[] = 'is-active';
                        }
                        ?>
                        <li class="<?php echo esc_attr(implode(' ', $step_classes)); ?>" data-tracking-step="<?php echo esc_attr((string) ($step['key'] ?? '')); ?>">
                            <span class="tracking-step__dot" aria-hidden="true"></span>
                            <span class="tracking-step__label"><?php echo esc_html((string) ($step['label'] ?? '')); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="tracking-timeline" data-goody-tracking-timeline>
                <?php if (! empty($tracking_timeline)) : ?>
                    <?php foreach ($tracking_timeline as $event) : ?>
                        <?php
                        $event_title = trim((string) ($event['title'] ?? ''));
                        $event_desc = trim((string) ($event['description'] ?? ''));
                        $event_time = trim((string) ($event['time'] ?? ''));
                        $event_class = ! empty($event['completed']) ? 'is-done' : '';
                        ?>
                        <article class="tracking-event <?php echo esc_attr($event_class); ?>">
                            <span class="tracking-event__dot" aria-hidden="true"></span>
                            <div class="tracking-event__content">
                                <?php if ($event_title !== '') : ?><h4><?php echo esc_html($event_title); ?></h4><?php endif; ?>
                                <?php if ($event_desc !== '') : ?><p><?php echo esc_html($event_desc); ?></p><?php endif; ?>
                                <?php if ($event_time !== '') : ?><time><?php echo esc_html($event_time); ?></time><?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="goody-inline-empty"><?php esc_html_e('No tracking events are available yet.', 'goody'); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php

    return ob_get_clean();
}

function goody_render_reservation_status_shortcode($atts = []) {
    $reference = sanitize_text_field((string) ($_GET['reference'] ?? ''));
    $phone = sanitize_text_field((string) ($_GET['phone'] ?? ''));
    $tracking_order_id = '';
    foreach (['order_id', 'order', 'id', 'tracking_id', 'external_order_id'] as $tracking_param) {
        $tracking_value = sanitize_text_field((string) wp_unslash($_GET[$tracking_param] ?? ''));
        if ($tracking_value !== '') {
            $tracking_order_id = $tracking_value;
            break;
        }
    }
    $tracking_order_key = sanitize_text_field((string) wp_unslash($_GET['key'] ?? $_GET['order_key'] ?? $_GET['wc_order_key'] ?? ''));
    $initial_summary = '';
    $initial_status = '';
    $initial_tracking_html = '';
    $initial_error = '';
    $status_page_url = get_permalink();
    if (! $status_page_url) {
        $status_page_url = goody_get_reservation_status_lookup_page_url();
    }

    if ($reference !== '') {
        $reservation = goody_find_reservation_by_reference_and_phone($reference, $phone);
        if ($reservation instanceof WP_Post) {
            $initial_status = goody_get_reservation_status_label((string) get_post_meta($reservation->ID, 'goody_reservation_status', true));
            $initial_tracking_html = goody_render_reservation_tracking_panel($reservation);
            $initial_summary = (string) get_post_meta($reservation->ID, 'goody_reservation_summary_html', true);
        } else {
            $initial_error = __('No reservation was found with that reference and phone number.', 'goody');
        }
    }

    ob_start();
    ?>
    <div class="goody-reservation-shell goody-reservation-shell--status">
        <div class="goody-status-app goody-status-app--combined" data-goody-status-app>
            <div class="goody-reservation-intro">
                <span class="goody-reservation-kicker"><?php esc_html_e('Status Center', 'goody'); ?></span>
                <h2><?php echo esc_html(goody_get_option('reservation_status_title', __('Check your reservation status', 'goody'))); ?></h2>
                <p><?php echo esc_html(goody_get_option('reservation_status_text', __('Enter your reservation reference or order ID to track everything from one page.', 'goody'))); ?></p>
            </div>

            <div class="goody-status-layout">
                <div class="goody-status-card goody-status-card--reservation">
                    <h3><?php esc_html_e('Reservation Tracking', 'goody'); ?></h3>
                    <p><?php esc_html_e('Check table booking, pickup, or pre-order reservation status.', 'goody'); ?></p>
                    <form class="goody-status-form" data-status-form method="get" action="<?php echo esc_url($status_page_url); ?>">
                        <input type="hidden" name="tracking" value="reservation">
                        <label><span><?php esc_html_e('Reference', 'goody'); ?></span><input type="text" name="reference" value="<?php echo esc_attr($reference); ?>" placeholder="GGR-00001"></label>
                        <label><span><?php esc_html_e('Phone', 'goody'); ?></span><input type="text" name="phone" value="<?php echo esc_attr($phone); ?>" placeholder="<?php esc_attr_e('01XXXXXXXXX', 'goody'); ?>"></label>
                        <button type="submit" class="button"><?php esc_html_e('Check Reservation', 'goody'); ?></button>
                    </form>

                    <div class="goody-status-result" data-status-result>
                        <?php if ($initial_tracking_html !== '') : ?>
                            <?php echo $initial_tracking_html; ?>
                        <?php elseif ($initial_status !== '') : ?>
                            <div class="goody-status-result__state"><strong><?php esc_html_e('Current status:', 'goody'); ?></strong> <?php echo esc_html($initial_status); ?></div>
                        <?php elseif ($initial_error !== '') : ?>
                            <div class="goody-inline-empty"><?php echo esc_html($initial_error); ?></div>
                        <?php endif; ?>
                        <?php echo wp_kses_post($initial_summary); ?>
                    </div>
                </div>

                <?php echo goody_render_order_tracking_status_panel($tracking_order_id, $tracking_order_key); ?>
            </div>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
add_shortcode('reservation_order_status', 'goody_render_reservation_status_shortcode');

function goody_map_woocommerce_status_to_reservation_status($order_status) {
    $order_status = str_replace('wc-', '', sanitize_key((string) $order_status));

    if (in_array($order_status, ['processing', 'completed'], true)) {
        return 'confirmed';
    }

    if (in_array($order_status, ['failed', 'cancelled', 'refunded'], true)) {
        return 'cancelled';
    }

    return 'pending-payment';
}

function goody_map_reservation_status_to_tracking_stage($reservation_status, $order_type = 'delivery') {
    $reservation_status = sanitize_key((string) $reservation_status);
    $order_type = sanitize_key((string) $order_type);

    if (in_array($reservation_status, ['cancelled', 'failed', 'refunded'], true)) {
        return 'requested';
    }

    if (in_array($reservation_status, ['pending-payment', 'pending', 'requested'], true)) {
        return 'requested';
    }

    if ($reservation_status === 'confirmed') {
        return 'confirmed';
    }

    if (in_array($reservation_status, ['preparing', 'processing'], true)) {
        return 'preparing';
    }

    if ($reservation_status === 'ready') {
        return $order_type === 'delivery' ? 'with_delivery_provider' : 'ready';
    }

    if (in_array($reservation_status, ['completed', 'served'], true)) {
        return 'completed';
    }

    return 'requested';
}

function goody_sync_order_tracking_from_reservation($reservation_id) {
    $reservation_id = absint($reservation_id);
    if ($reservation_id < 1 || ! function_exists('wc_get_order')) {
        return;
    }

    $order_id = absint(get_post_meta($reservation_id, 'goody_wc_order_id', true));
    if ($order_id < 1) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order instanceof WC_Order) {
        return;
    }

    $reservation_status = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_status', true));
    $order_type = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_order_type', true));
    if ($order_type === '') {
        $order_type = sanitize_key((string) $order->get_meta('_goody_reservation_order_type', true));
    }
    if ($order_type === '') {
        $order_type = 'delivery';
    }

    $stage = goody_map_reservation_status_to_tracking_stage($reservation_status, $order_type);
    $defs = goody_get_tracking_stage_definitions();
    $status_label = (string) ($defs[$stage] ?? ucfirst(str_replace('_', ' ', $stage)));

    $previous_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
    $previous_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));

    $order->update_meta_data('_goody_tracking_stage', $stage);
    $order->update_meta_data('_goody_tracking_status', $status_label);
    $order->update_meta_data('_goody_tracking_manual_updates', '1');
    $order->update_meta_data('_goody_reservation_order_type', $order_type);

    if ($stage !== $previous_stage || $status_label !== $previous_status) {
        if (function_exists('goody_append_tracking_timeline_event')) {
            $note = sprintf(__('Auto sync from reservation status (%s / %s)', 'goody'), $reservation_status, $order_type);
            goody_append_tracking_timeline_event($order, $stage, $status_label, $note);
        }
    }

    $order->save();
}

function goody_sync_reservation_from_woocommerce_status($order_id, $old_status, $new_status, $order) {
    if (! $order instanceof WC_Order) {
        $order = wc_get_order($order_id);
    }
    if (! $order instanceof WC_Order) {
        return;
    }

    $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
    if ($reservation_id < 1) {
        return;
    }

    update_post_meta($reservation_id, 'goody_reservation_payment_status', sanitize_text_field((string) $new_status));
    update_post_meta($reservation_id, 'goody_reservation_status', goody_map_woocommerce_status_to_reservation_status($new_status));
    goody_sync_order_tracking_from_reservation($reservation_id);
}
add_action('woocommerce_order_status_changed', 'goody_sync_reservation_from_woocommerce_status', 20, 4);

function goody_add_reservation_order_meta_to_admin($fields) {
    $fields['_goody_reservation_code'] = __('Reservation Code', 'goody');
    $fields['_goody_reservation_date'] = __('Reservation Date', 'goody');
    $fields['_goody_reservation_slot'] = __('Reservation Slot', 'goody');
    $fields['_goody_reservation_order_type'] = __('Order Type', 'goody');
    $fields['_goody_reservation_guests'] = __('Guests', 'goody');
    $fields['_goody_reservation_balance_due'] = __('Balance Due', 'goody');
    return $fields;
}
add_filter('woocommerce_admin_order_data_after_billing_address', function ($order) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $reference = sanitize_text_field((string) $order->get_meta('_goody_reservation_code', true));
    if ($reference === '') {
        return;
    }

    echo '<p><strong>' . esc_html__('Reservation Code:', 'goody') . '</strong> ' . esc_html($reference) . '</p>';
    echo '<p><strong>' . esc_html__('Reservation Slot:', 'goody') . '</strong> ' . esc_html((string) $order->get_meta('_goody_reservation_date', true) . ' • ' . (string) $order->get_meta('_goody_reservation_slot', true)) . '</p>';
}, 20);

function goody_register_reservation_tools_page() {
    add_submenu_page(
        'goody-theme',
        __('Reservation Tools', 'goody'),
        __('Reservation Tools', 'goody'),
        'edit_theme_options',
        'goody-reservation-tools',
        'goody_render_reservation_tools_page'
    );
}
add_action('admin_menu', 'goody_register_reservation_tools_page');

function goody_render_reservation_tools_page() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to access this page.', 'goody'));
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Reservation Tools', 'goody'); ?></h1>
        <p><?php esc_html_e('Use these tools to create the reservation pages, import demo booking data, and export reservations.', 'goody'); ?></p>

        <div class="card" style="max-width:920px;padding:24px;margin-top:16px;">
            <h2><?php esc_html_e('Create Default Pages', 'goody'); ?></h2>
            <p><?php esc_html_e('This creates Reservation, Menu, Order Status, About, Contact, Privacy Policy, and Terms pages with the right shortcodes/content.', 'goody'); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="goody_create_reservation_pages">
                <?php wp_nonce_field('goody_create_reservation_pages', 'goody_create_reservation_pages_nonce'); ?>
                <?php submit_button(__('Create / Update Pages', 'goody'), 'primary', 'submit', false); ?>
            </form>
        </div>

        <div class="card" style="max-width:920px;padding:24px;margin-top:16px;">
            <h2><?php esc_html_e('Import Demo Data', 'goody'); ?></h2>
            <p><?php esc_html_e('This creates sample menu items, booking dates, and delivery zones for testing the flow end to end.', 'goody'); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="goody_import_reservation_demo_data">
                <?php wp_nonce_field('goody_import_reservation_demo_data', 'goody_import_reservation_demo_data_nonce'); ?>
                <?php submit_button(__('Import Demo Reservation Data', 'goody'), 'secondary', 'submit', false); ?>
            </form>
        </div>

        <div class="card" style="max-width:920px;padding:24px;margin-top:16px;">
            <h2><?php esc_html_e('Export Reservations CSV', 'goody'); ?></h2>
            <p><?php esc_html_e('Download all reservations with dates, totals, statuses, customer info, and linked WooCommerce order IDs.', 'goody'); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="goody_export_reservations_csv">
                <?php wp_nonce_field('goody_export_reservations_csv', 'goody_export_reservations_csv_nonce'); ?>
                <?php submit_button(__('Download CSV', 'goody'), 'secondary', 'submit', false); ?>
            </form>
        </div>
    </div>
    <?php
}

function goody_upsert_page_by_slug($title, $slug, $content) {
    $page = get_page_by_path($slug, OBJECT, 'page');
    if ($page instanceof WP_Post) {
        wp_update_post([
            'ID' => $page->ID,
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
        ]);
        return $page->ID;
    }

    return wp_insert_post([
        'post_type' => 'page',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_name' => $slug,
        'post_content' => $content,
    ]);
}

function goody_handle_create_reservation_pages() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to perform this action.', 'goody'));
    }

    check_admin_referer('goody_create_reservation_pages', 'goody_create_reservation_pages_nonce');

    $reservation_page_id = goody_upsert_page_by_slug('Reservation', 'reservation', '[reservation_booking]');
    $menu_page_id = goody_upsert_page_by_slug('Menu', 'menu-page', '[reservation_menu]');
    $status_page_id = goody_upsert_page_by_slug('Order Status', 'order-status', '[reservation_order_status]');
    goody_upsert_page_by_slug('About', 'about', '<h2>About</h2><p>Tell your restaurant story here.</p>');
    goody_upsert_page_by_slug('Contact', 'contact', '<h2>Contact</h2><p>Add contact details and map shortcode here.</p>');
    goody_upsert_page_by_slug('Privacy Policy', 'privacy-policy', '<h2>Privacy Policy</h2><p>Add your privacy policy here.</p>');
    goody_upsert_page_by_slug('Terms', 'terms', '<h2>Terms & Conditions</h2><p>Add your booking and payment terms here.</p>');

    if ($reservation_page_id) {
        $options = goody_get_options();
        $options['reservation_platform'] = 'custom';
        $options['reservation_custom_url'] = get_permalink($reservation_page_id);
        if ($status_page_id) {
            $options['reservation_status_page_url'] = get_permalink($status_page_id);
        }
        update_option('goody_theme_options', $options, false);
        update_post_meta($reservation_page_id, '_wp_page_template', 'templates/reservation-page.php');
    }

    if ($menu_page_id) {
        delete_post_meta($menu_page_id, '_wp_page_template');
    }

    if ($status_page_id) {
        delete_post_meta($status_page_id, '_wp_page_template');
    }

    wp_safe_redirect(admin_url('admin.php?page=goody-reservation-tools&pages=created'));
    exit;
}
add_action('admin_post_goody_create_reservation_pages', 'goody_handle_create_reservation_pages');

function goody_maybe_sync_reservation_page_settings() {
    $options = goody_get_options();
    $updated = false;
    $reservation_page = get_page_by_path('reservation', OBJECT, 'page');
    $status_page = get_page_by_path('order-status', OBJECT, 'page');

    if ($reservation_page instanceof WP_Post) {
        $reservation_permalink = get_permalink($reservation_page);
        if ($reservation_permalink && empty($options['reservation_custom_url'])) {
            $options['reservation_platform'] = 'custom';
            $options['reservation_custom_url'] = $reservation_permalink;
            $updated = true;
        }

        if (get_post_meta($reservation_page->ID, '_wp_page_template', true) !== 'templates/reservation-page.php') {
            update_post_meta($reservation_page->ID, '_wp_page_template', 'templates/reservation-page.php');
        }
    }

    if ($status_page instanceof WP_Post) {
        $status_permalink = get_permalink($status_page);
        if ($status_permalink && empty($options['reservation_status_page_url'])) {
            $options['reservation_status_page_url'] = $status_permalink;
            $updated = true;
        }
    }

    if ($updated) {
        update_option('goody_theme_options', $options, false);
    }
}
add_action('init', 'goody_maybe_sync_reservation_page_settings', 30);

function goody_upsert_demo_menu_item($title, $price, $short_desc, $meta = []) {
    $existing = get_page_by_title($title, OBJECT, 'menu_item');
    $postarr = [
        'post_type' => 'menu_item',
        'post_status' => 'publish',
        'post_title' => $title,
        'post_excerpt' => $short_desc,
    ];
    if ($existing instanceof WP_Post) {
        $postarr['ID'] = $existing->ID;
        $menu_item_id = wp_update_post($postarr, true);
    } else {
        $menu_item_id = wp_insert_post($postarr, true);
    }

    if (is_wp_error($menu_item_id) || ! $menu_item_id) {
        return 0;
    }

    update_post_meta($menu_item_id, 'goody_menu_price', (string) $price);
    update_post_meta($menu_item_id, 'goody_menu_short_desc', $short_desc);
    update_post_meta($menu_item_id, 'goody_menu_available', '1');

    foreach ($meta as $key => $value) {
        update_post_meta($menu_item_id, $key, (string) $value);
    }

    return (int) $menu_item_id;
}

function goody_handle_import_reservation_demo_data() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to perform this action.', 'goody'));
    }

    check_admin_referer('goody_import_reservation_demo_data', 'goody_import_reservation_demo_data_nonce');

    $sample_items = [
        [
            'title' => 'Family Beef Platter',
            'price' => 2450,
            'desc' => 'Large sharing platter with premium beef cuts and side sauces.',
            'meta' => [
                'goody_menu_unit_type' => 'person',
                'goody_menu_unit_label' => 'person',
                'goody_menu_min_qty' => '2',
                'goody_menu_step_qty' => '1',
                'goody_menu_capacity_unit' => 'person',
                'goody_menu_capacity_value' => '1',
                'goody_menu_addons_data' => "Extra Sauce|120\nBread Basket|180",
            ],
        ],
        [
            'title' => 'Raw Meat KG Pack',
            'price' => 980,
            'desc' => 'Fresh marinated raw meat pack for custom barbecue orders.',
            'meta' => [
                'goody_menu_unit_type' => 'kg',
                'goody_menu_unit_label' => 'kg',
                'goody_menu_min_qty' => '1',
                'goody_menu_step_qty' => '0.5',
                'goody_menu_capacity_unit' => 'kg',
                'goody_menu_capacity_value' => '1',
                'goody_menu_track_stock' => '1',
                'goody_menu_stock_qty' => '18',
                'goody_menu_addons_data' => "Spice Rub|90\nExtra Marinade|110",
            ],
        ],
        [
            'title' => 'Whole Mutton Roast',
            'price' => 12500,
            'desc' => 'Whole mutton roast prepared for large pre-order events.',
            'meta' => [
                'goody_menu_unit_type' => 'whole',
                'goody_menu_unit_label' => 'whole',
                'goody_menu_min_qty' => '1',
                'goody_menu_step_qty' => '1',
                'goody_menu_capacity_unit' => 'person',
                'goody_menu_capacity_value' => '8',
                'goody_menu_track_stock' => '1',
                'goody_menu_stock_qty' => '4',
                'goody_menu_addons_data' => "Chef Carving Service|800",
            ],
        ],
    ];

    foreach ($sample_items as $sample_item) {
        goody_upsert_demo_menu_item($sample_item['title'], $sample_item['price'], $sample_item['desc'], $sample_item['meta']);
    }

    for ($i = 1; $i <= 5; $i += 1) {
        $service_date = gmdate('Y-m-d', strtotime('+' . $i . ' days', current_time('timestamp')));
        $existing = get_posts([
            'post_type' => 'goody_booking_day',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'meta_query' => [
                [
                    'key' => 'goody_booking_service_date',
                    'value' => $service_date,
                ],
            ],
            'fields' => 'ids',
        ]);

        if (! empty($existing[0])) {
            $booking_day_id = (int) $existing[0];
        } else {
            $booking_day_id = (int) wp_insert_post([
                'post_type' => 'goody_booking_day',
                'post_status' => 'publish',
                'post_title' => 'Booking ' . $service_date,
            ]);
        }

        if ($booking_day_id > 0) {
            update_post_meta($booking_day_id, 'goody_booking_service_date', $service_date);
            update_post_meta($booking_day_id, 'goody_booking_day_active', '1');
            update_post_meta($booking_day_id, 'goody_booking_day_note', __('Sample booking date for testing.', 'goody'));
            update_post_meta($booking_day_id, 'goody_booking_slots', wp_json_encode([
                ['time' => '12:00', 'label' => '12:00 PM', 'capacity_persons' => '25', 'capacity_kg' => '12', 'order_types' => 'dine_in,pickup,delivery', 'cutoff_minutes' => '90'],
                ['time' => '15:00', 'label' => '3:00 PM', 'capacity_persons' => '20', 'capacity_kg' => '10', 'order_types' => 'pickup,delivery', 'cutoff_minutes' => '90'],
                ['time' => '19:00', 'label' => '7:00 PM', 'capacity_persons' => '30', 'capacity_kg' => '16', 'order_types' => 'dine_in,pickup,delivery', 'cutoff_minutes' => '120'],
                ['time' => '21:00', 'label' => '9:00 PM', 'capacity_persons' => '18', 'capacity_kg' => '8', 'order_types' => 'dine_in,pickup', 'cutoff_minutes' => '120'],
            ]));
        }
    }

    $sample_zones = [
        ['Zone A - Gulshan', "Gulshan 1\nGulshan 2\nBanani", 120, 4000, 1500, __('Fast delivery zone.', 'goody')],
        ['Zone B - Dhanmondi', "Dhanmondi\nLalmatia\nKalabagan", 180, 5000, 1800, __('Delivery may take slightly longer during peak hours.', 'goody')],
        ['Zone C - Uttara', "Uttara Sector 3\nUttara Sector 7\nAirport Road", 220, 6500, 2200, __('Long-distance zone. Please double-check address details.', 'goody')],
    ];

    foreach ($sample_zones as $zone) {
        $existing = get_page_by_title($zone[0], OBJECT, 'goody_delivery_zone');
        if ($existing instanceof WP_Post) {
            $zone_id = $existing->ID;
        } else {
            $zone_id = wp_insert_post([
                'post_type' => 'goody_delivery_zone',
                'post_status' => 'publish',
                'post_title' => $zone[0],
            ]);
        }

        if (! is_wp_error($zone_id) && $zone_id) {
            update_post_meta($zone_id, 'goody_delivery_zone_enabled', '1');
            update_post_meta($zone_id, 'goody_delivery_zone_areas', $zone[1]);
            update_post_meta($zone_id, 'goody_delivery_zone_charge', (string) $zone[2]);
            update_post_meta($zone_id, 'goody_delivery_zone_free_limit', (string) $zone[3]);
            update_post_meta($zone_id, 'goody_delivery_zone_min_order', (string) $zone[4]);
            update_post_meta($zone_id, 'goody_delivery_zone_warning', $zone[5]);
        }
    }

    wp_safe_redirect(admin_url('admin.php?page=goody-reservation-tools&demo=imported'));
    exit;
}
add_action('admin_post_goody_import_reservation_demo_data', 'goody_handle_import_reservation_demo_data');

function goody_handle_export_reservations_csv() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to perform this action.', 'goody'));
    }

    check_admin_referer('goody_export_reservations_csv', 'goody_export_reservations_csv_nonce');

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    nocache_headers();
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=goody-reservations-' . gmdate('Y-m-d-His') . '.csv');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Reference', 'Status', 'Date', 'Slot', 'Order Type', 'Guests', 'Customer Name', 'Phone', 'Address', 'Total', 'Pay Now', 'Balance Due', 'Woo Order ID']);

    while ($query->have_posts()) {
        $query->the_post();
        $reservation_id = get_the_ID();
        fputcsv($output, [
            get_post_meta($reservation_id, 'goody_reservation_code', true),
            get_post_meta($reservation_id, 'goody_reservation_status', true),
            get_post_meta($reservation_id, 'goody_reservation_date', true),
            get_post_meta($reservation_id, 'goody_reservation_slot_label', true),
            get_post_meta($reservation_id, 'goody_reservation_order_type', true),
            get_post_meta($reservation_id, 'goody_reservation_guests', true),
            get_post_meta($reservation_id, 'goody_reservation_name', true),
            get_post_meta($reservation_id, 'goody_reservation_phone', true),
            get_post_meta($reservation_id, 'goody_reservation_address', true),
            get_post_meta($reservation_id, 'goody_reservation_total', true),
            get_post_meta($reservation_id, 'goody_reservation_pay_now_total', true),
            get_post_meta($reservation_id, 'goody_reservation_balance_due', true),
            get_post_meta($reservation_id, 'goody_wc_order_id', true),
        ]);
    }
    wp_reset_postdata();

    fclose($output);
    exit;
}
add_action('admin_post_goody_export_reservations_csv', 'goody_handle_export_reservations_csv');

function goody_handle_print_reservation() {
    $reservation_id = absint($_GET['reservation_id'] ?? 0);
    if ($reservation_id < 1) {
        wp_die(esc_html__('Invalid reservation.', 'goody'));
    }

    if (! current_user_can('edit_post', $reservation_id)) {
        wp_die(esc_html__('You are not allowed to print this reservation.', 'goody'));
    }

    check_admin_referer('goody_print_reservation_' . $reservation_id);

    $summary = (string) get_post_meta($reservation_id, 'goody_reservation_summary_html', true);
    $status = goody_get_reservation_status_label((string) get_post_meta($reservation_id, 'goody_reservation_status', true));
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?php echo esc_html(goody_get_reservation_reference($reservation_id)); ?></title>
        <style>
            body { font-family: Arial, sans-serif; padding: 32px; color: #23170f; }
            .print-wrap { max-width: 840px; margin: 0 auto; }
            h1 { margin-bottom: 8px; }
            .print-status { margin-bottom: 24px; font-weight: 700; }
            .goody-summary-card { border: 1px solid #ddd; border-radius: 16px; padding: 24px; }
            .goody-summary-line { display:flex; justify-content:space-between; gap:16px; padding:8px 0; border-bottom:1px dashed #ddd; }
            .goody-summary-line:last-child { border-bottom:0; }
            .goody-summary-card__section + .goody-summary-card__section { margin-top: 16px; }
        </style>
    </head>
    <body onload="window.print()">
        <div class="print-wrap">
            <h1><?php echo esc_html(goody_get_reservation_reference($reservation_id)); ?></h1>
            <div class="print-status"><?php echo esc_html($status); ?></div>
            <?php echo wp_kses_post($summary); ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}
add_action('admin_post_goody_print_reservation', 'goody_handle_print_reservation');

function goody_manage_reservation_columns($columns) {
    $columns['goody_reference'] = __('Reference', 'goody');
    $columns['goody_date_slot'] = __('Date & Slot', 'goody');
    $columns['goody_customer'] = __('Customer', 'goody');
    $columns['goody_order_type'] = __('Order Type', 'goody');
    $columns['goody_total'] = __('Total', 'goody');
    $columns['goody_status'] = __('Status', 'goody');
    return $columns;
}
add_filter('manage_goody_reservation_posts_columns', 'goody_manage_reservation_columns');

function goody_render_reservation_columns($column, $post_id) {
    if ($column === 'goody_reference') {
        echo esc_html((string) get_post_meta($post_id, 'goody_reservation_code', true));
    } elseif ($column === 'goody_date_slot') {
        echo esc_html((string) get_post_meta($post_id, 'goody_reservation_date', true));
        echo '<br><small>' . esc_html((string) get_post_meta($post_id, 'goody_reservation_slot_label', true)) . '</small>';
    } elseif ($column === 'goody_customer') {
        echo esc_html((string) get_post_meta($post_id, 'goody_reservation_name', true));
        echo '<br><small>' . esc_html((string) get_post_meta($post_id, 'goody_reservation_phone', true)) . '</small>';
    } elseif ($column === 'goody_order_type') {
        $order_type = (string) get_post_meta($post_id, 'goody_reservation_order_type', true);
        $types = goody_get_reservation_order_types();
        echo esc_html($types[$order_type] ?? $order_type);
    } elseif ($column === 'goody_total') {
        echo goody_reservation_price_html((float) get_post_meta($post_id, 'goody_reservation_total', true));
    } elseif ($column === 'goody_status') {
        echo esc_html(goody_get_reservation_status_label((string) get_post_meta($post_id, 'goody_reservation_status', true)));
    }
}
add_action('manage_goody_reservation_posts_custom_column', 'goody_render_reservation_columns', 10, 2);

function goody_add_reservation_row_actions($actions, $post) {
    if (! $post instanceof WP_Post || $post->post_type !== 'goody_reservation') {
        return $actions;
    }

    $actions['print_reservation'] = '<a href="' . esc_url(wp_nonce_url(admin_url('admin-post.php?action=goody_print_reservation&reservation_id=' . $post->ID), 'goody_print_reservation_' . $post->ID)) . '" target="_blank" rel="noopener">' . esc_html__('Print', 'goody') . '</a>';
    return $actions;
}
add_filter('post_row_actions', 'goody_add_reservation_row_actions', 10, 2);
