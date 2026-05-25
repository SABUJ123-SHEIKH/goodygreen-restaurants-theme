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

function goody_sync_menu_item_wc_product($menu_item_id) {
    $menu_item_id = absint($menu_item_id);
    if ($menu_item_id < 1 || get_post_type($menu_item_id) !== 'menu_item') {
        return 0;
    }
    if (! class_exists('WooCommerce') || ! function_exists('wc_get_product') || ! class_exists('WC_Product_Simple')) {
        return 0;
    }

    $product_id = absint(get_post_meta($menu_item_id, 'goody_menu_wc_product_id', true));
    $product = $product_id > 0 ? wc_get_product($product_id) : null;
    if (! $product instanceof WC_Product) {
        $product = new WC_Product_Simple();
    }

    $menu_name = trim((string) get_the_title($menu_item_id));
    if ($menu_name === '') {
        $menu_name = sprintf(__('Menu Item #%d', 'goody'), $menu_item_id);
    }
    $menu_price = max(0, (float) get_post_meta($menu_item_id, 'goody_menu_price', true));
    $menu_desc = (string) get_post_meta($menu_item_id, 'goody_menu_short_desc', true);

    $product->set_name($menu_name);
    $product->set_status('publish');
    $product->set_catalog_visibility('hidden');
    $product->set_regular_price((string) $menu_price);
    $product->set_price((string) $menu_price);
    $product->set_description(wp_kses_post($menu_desc));
    $product->set_short_description(sanitize_text_field($menu_desc));
    $product->set_virtual(true);
    $product->set_sold_individually(false);
    $product->set_manage_stock(false);
    $product->set_reviews_allowed(false);
    $product->set_slug(sanitize_title($menu_name . '-' . $menu_item_id));

    $saved_id = (int) $product->save();
    if ($saved_id < 1) {
        return 0;
    }

    update_post_meta($menu_item_id, 'goody_menu_wc_product_id', (string) $saved_id);
    update_post_meta($saved_id, '_goody_menu_item_id', (string) $menu_item_id);

    return $saved_id;
}

function goody_get_reservation_step_titles() {
    $defaults = goody_get_reservation_step_defaults();

    return [
        1 => goody_get_reservation_localized_option('reservation_step_title_1', $defaults['step_title_1']),
        2 => goody_get_reservation_localized_option('reservation_step_title_2', $defaults['step_title_2']),
        3 => goody_get_reservation_localized_option('reservation_step_title_3', $defaults['step_title_3']),
        4 => goody_get_reservation_localized_option('reservation_step_title_4', $defaults['step_title_4']),
        5 => goody_get_reservation_localized_option('reservation_step_title_5', $defaults['step_title_5']),
        6 => goody_get_reservation_localized_option('reservation_step_title_6', $defaults['step_title_6']),
    ];
}

function goody_is_reservation_menu_step_enabled() {
    return goody_get_option('reservation_enable_menu_step', '1') === '1';
}

function goody_get_reservation_step_counter_prefix() {
    $defaults = goody_get_reservation_step_defaults();
    return goody_get_reservation_localized_option('reservation_step_counter_prefix', $defaults['step_counter_prefix']);
}

function goody_get_reservation_step_defaults() {
    $locale = strtolower((string) (function_exists('determine_locale') ? determine_locale() : get_locale()));
    $language = explode('_', str_replace('-', '_', $locale))[0] ?? 'en';

    $defaults_by_language = [
        'en' => [
            'step_counter_prefix' => 'Step',
            'step_title_1' => 'Date',
            'step_title_2' => 'Menu',
            'step_title_3' => 'Time',
            'step_title_4' => 'Order Type',
            'step_title_5' => 'Information',
            'step_title_6' => 'Summary',
        ],
        'es' => [
            'step_counter_prefix' => 'Paso',
            'step_title_1' => 'Fecha',
            'step_title_2' => 'Menu',
            'step_title_3' => 'Hora',
            'step_title_4' => 'Tipo de pedido',
            'step_title_5' => 'Informacion',
            'step_title_6' => 'Resumen',
        ],
        'ca' => [
            'step_counter_prefix' => 'Pas',
            'step_title_1' => 'Data',
            'step_title_2' => 'Menu',
            'step_title_3' => 'Hora',
            'step_title_4' => 'Tipus de comanda',
            'step_title_5' => 'Informacio',
            'step_title_6' => 'Resum',
        ],
    ];

    return $defaults_by_language[$language] ?? $defaults_by_language['en'];
}

function goody_get_reservation_legacy_step_defaults() {
    return [
        'reservation_step_counter_prefix' => ['ধাপ', 'Step'],
        'reservation_step_title_1' => ['তারিখ', 'Date'],
        'reservation_step_title_2' => ['মেনু', 'Menu'],
        'reservation_step_title_3' => ['সময়', 'Time'],
        'reservation_step_title_4' => ['অর্ডার ধরন', 'Order Type'],
        'reservation_step_title_5' => ['তথ্য', 'Information'],
        'reservation_step_title_6' => ['সারাংশ', 'Summary'],
    ];
}

function goody_get_reservation_localized_option($option_key, $localized_default) {
    $raw_options = (array) get_option('goody_theme_options', []);

    // Respect explicit admin overrides. Use localized fallback only for empty/legacy defaults.
    if (array_key_exists($option_key, $raw_options)) {
        $value = sanitize_text_field((string) $raw_options[$option_key]);
        if ($value !== '') {
            return $value;
        }
    }

    $legacy_defaults = goody_get_reservation_legacy_step_defaults();
    $fallback_value = sanitize_text_field((string) goody_get_option($option_key, $localized_default));

    if (isset($legacy_defaults[$option_key]) && in_array($fallback_value, $legacy_defaults[$option_key], true)) {
        return $localized_default;
    }

    return $fallback_value !== '' ? $fallback_value : $localized_default;
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
        'email' => [
            'required' => false,
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

function goody_get_reservation_delivery_provider_choices() {
    $defaults = [
        'goody' => 'Goody',
    ];

    $raw = (string) goody_get_option('reservation_delivery_providers', '');
    if ($raw === '') {
        return $defaults;
    }

    $choices = [];
    $lines = preg_split('/\r\n|\r|\n/', $raw);
    if (! is_array($lines)) {
        return $defaults;
    }

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }

        $parts = array_map('trim', explode('|', $line));
        if (count($parts) === 1 && strpos($parts[0], ',') !== false) {
            $parts = array_map('trim', explode(',', $parts[0]));
        }

        $provider_key = sanitize_key((string) ($parts[0] ?? ''));
        if ($provider_key === '') {
            continue;
        }

        $provider_label = sanitize_text_field((string) ($parts[1] ?? ''));
        if ($provider_label === '') {
            $provider_label = ucwords(str_replace(['-', '_'], ' ', $provider_key));
        }

        // Optional third value supports admin-side enable/disable control.
        $enabled_raw = strtolower(trim((string) ($parts[2] ?? '1')));
        $is_enabled = ! in_array($enabled_raw, ['0', 'false', 'no', 'off', 'disabled'], true);
        if (! $is_enabled) {
            continue;
        }

        $choices[$provider_key] = $provider_label;
    }

    return ! empty($choices) ? $choices : $defaults;
}

function goody_get_reservation_default_delivery_provider() {
    $choices = goody_get_reservation_delivery_provider_choices();
    $default_key = sanitize_key((string) goody_get_option('reservation_default_delivery_provider', ''));
    if ($default_key !== '' && isset($choices[$default_key])) {
        return $default_key;
    }

    $keys = array_keys($choices);
    return (string) ($keys[0] ?? 'goody');
}

function goody_sanitize_reservation_delivery_provider($provider) {
    if (! is_scalar($provider)) {
        return '';
    }

    $provider = sanitize_key((string) $provider);
    $choices = goody_get_reservation_delivery_provider_choices();

    return isset($choices[$provider]) ? $provider : '';
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
    $order_types = goody_get_reservation_order_types();
    $service_date = (string) get_post_meta($post->ID, 'goody_booking_service_date', true);
    $service_date_from = (string) get_post_meta($post->ID, 'goody_booking_service_date_from', true);
    $service_date_to = (string) get_post_meta($post->ID, 'goody_booking_service_date_to', true);
    if ($service_date_from === '') {
        $service_date_from = $service_date;
    }
    if ($service_date_to === '') {
        $service_date_to = $service_date_from;
    }
    ?>
    <p>
        <label for="goody_booking_service_date"><strong><?php esc_html_e('Service Date', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="date" id="goody_booking_service_date" name="goody_booking_service_date" value="<?php echo esc_attr($service_date_from); ?>">
    </p>
    <p>
        <label for="goody_booking_service_date_to"><strong><?php esc_html_e('Service Date End (optional)', 'goody'); ?></strong></label><br>
        <input style="width:100%;" type="date" id="goody_booking_service_date_to" name="goody_booking_service_date_to" value="<?php echo esc_attr($service_date_to); ?>">
        <small style="display:block;margin-top:4px;"><?php esc_html_e('If set, this one booking entry will be available for all dates from Service Date to End Date.', 'goody'); ?></small>
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
            <?php
            $row_types_raw = (string) ($row['order_types'] ?? '');
            $row_types = array_values(array_filter(array_map('sanitize_key', array_map('trim', explode(',', $row_types_raw)))));
            $row_all_types = empty($row_types);
            ?>
            <div class="goody-repeater-row" data-index="<?php echo esc_attr((string) $index); ?>">
                <input type="time" data-field="time" placeholder="<?php esc_attr_e('Time', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['time'] ?? '')); ?>">
                <input type="text" data-field="label" placeholder="<?php esc_attr_e('Label', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['label'] ?? '')); ?>">
                <input type="text" data-field="capacity_persons" placeholder="<?php esc_attr_e('Person Cap.', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['capacity_persons'] ?? '')); ?>">
                <input type="text" data-field="capacity_kg" placeholder="<?php esc_attr_e('KG Cap.', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['capacity_kg'] ?? '')); ?>">
                <div style="display:flex;flex-wrap:wrap;gap:8px 12px;">
                    <?php foreach ($order_types as $type_key => $type_label) : ?>
                        <label style="display:inline-flex;align-items:center;">
                            <input
                                type="checkbox"
                                data-slot-order-type
                                data-order-type-key="<?php echo esc_attr($type_key); ?>"
                                <?php checked($row_all_types || in_array($type_key, $row_types, true)); ?>
                            >
                            <span style="margin-left:4px;"><?php echo esc_html($type_label); ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" data-field="order_types" value="<?php echo esc_attr($row_types_raw); ?>">
                <input type="text" data-field="cutoff_minutes" placeholder="<?php esc_attr_e('120', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['cutoff_minutes'] ?? '')); ?>">
                <input type="text" data-field="warning" placeholder="<?php esc_attr_e('Optional warning message', 'goody'); ?>" value="<?php echo esc_attr((string) ($row['warning'] ?? '')); ?>">
                <label class="goody-repeater-checkbox"><input type="checkbox" data-field="enabled" value="1" <?php checked(($row['enabled'] ?? '0'), '1'); ?>> <?php esc_html_e('Enabled', 'goody'); ?></label>
                <button type="button" class="button goody-row-up">↑</button>
                <button type="button" class="button goody-row-down">↓</button>
                <button type="button" class="button goody-row-remove"><?php esc_html_e('Remove', 'goody'); ?></button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" class="button button-secondary goody-repeater-add" data-target="goody_booking_slots"><?php esc_html_e('Add Slot', 'goody'); ?></button>
    <p class="description"><?php esc_html_e('Tick order types per slot. If all are selected, the slot allows all order types.', 'goody'); ?></p>
    <script>
    (function () {
        function syncSlotAllowedTypes(root) {
            var rows = root.querySelectorAll('.goody-repeater-row');
            rows.forEach(function (row) {
                var hiddenField = row.querySelector('input[data-field="order_types"]');
                if (!hiddenField) {
                    return;
                }
                var checks = Array.prototype.slice.call(row.querySelectorAll('input[data-slot-order-type][data-order-type-key]'));
                if (!checks.length) {
                    hiddenField.value = '';
                    return;
                }
                var selected = checks.filter(function (input) {
                    return !!input.checked;
                }).map(function (input) {
                    return String(input.getAttribute('data-order-type-key') || '').trim();
                }).filter(Boolean);
                if (selected.length === 0) {
                    hiddenField.value = '__none__';
                } else if (selected.length === checks.length) {
                    hiddenField.value = '';
                } else {
                    hiddenField.value = selected.join(',');
                }
                hiddenField.dispatchEvent(new Event('input', { bubbles: true }));
            });
        }

        var wrapper = document.currentScript ? document.currentScript.previousElementSibling : null;
        while (wrapper && !wrapper.classList.contains('goody-repeater')) {
            wrapper = wrapper.previousElementSibling;
        }
        if (!wrapper) {
            return;
        }

        wrapper.addEventListener('change', function (event) {
            if (event.target && event.target.matches('input[data-slot-order-type]')) {
                syncSlotAllowedTypes(wrapper);
            }
        });

        syncSlotAllowedTypes(wrapper);
    })();
    </script>
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
            'enabled' => isset($row['enabled']) && (string) $row['enabled'] === '1' ? '1' : '0',
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
        $service_date_from = sanitize_text_field((string) ($_POST['goody_booking_service_date'] ?? ''));
        $service_date_to = sanitize_text_field((string) ($_POST['goody_booking_service_date_to'] ?? ''));
        if ($service_date_from !== '') {
            update_post_meta($post_id, 'goody_booking_service_date', $service_date_from);
            update_post_meta($post_id, 'goody_booking_service_date_from', $service_date_from);
        }
        if ($service_date_to === '') {
            $service_date_to = $service_date_from;
        }
        if ($service_date_to !== '') {
            if ($service_date_from !== '' && $service_date_to < $service_date_from) {
                $service_date_to = $service_date_from;
            }
            update_post_meta($post_id, 'goody_booking_service_date_to', $service_date_to);
        }

        if (isset($_POST['goody_booking_day_note'])) {
            update_post_meta($post_id, 'goody_booking_day_note', sanitize_textarea_field(wp_unslash($_POST['goody_booking_day_note'])));
        }

        update_post_meta($post_id, 'goody_booking_day_active', isset($_POST['goody_booking_day_active']) ? '1' : '0');
        $slots = json_decode(goody_sanitize_booking_slots_json(wp_unslash($_POST['goody_booking_slots'] ?? '[]')), true);
        if (! is_array($slots)) {
            $slots = [];
        }
        $slots_json = wp_json_encode(array_values($slots));
        update_post_meta($post_id, 'goody_booking_slots', $slots_json);

        if ($service_date_from !== '') {
            remove_action('save_post', 'goody_save_reservation_meta_boxes');
            wp_update_post([
                'ID' => $post_id,
                'post_title' => sprintf(
                    /* translators: 1: start date, 2: end date. */
                    $service_date_to !== '' && $service_date_to !== $service_date_from ? __('Booking %1$s to %2$s', 'goody') : __('Booking %1$s', 'goody'),
                    $service_date_from,
                    $service_date_to
                ),
            ]);
            add_action('save_post', 'goody_save_reservation_meta_boxes');
        }
        delete_transient('goody_available_booking_days_v1');

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

function goody_sync_menu_item_wc_product_on_save($post_id, $post, $update) {
    if (! $post instanceof WP_Post || $post->post_type !== 'menu_item') {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (wp_is_post_revision($post_id)) {
        return;
    }
    if (! current_user_can('edit_post', $post_id)) {
        return;
    }

    goody_sync_menu_item_wc_product($post_id);
}
add_action('save_post_menu_item', 'goody_sync_menu_item_wc_product_on_save', 30, 3);

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
    if (! is_array($slots)) {
        return [];
    }

    $filtered = [];
    foreach ($slots as $slot) {
        if (! is_array($slot)) {
            continue;
        }

        // Strictly keep only explicitly enabled slots for frontend/runtime safety.
        if (! isset($slot['enabled']) || (string) $slot['enabled'] !== '1') {
            continue;
        }

        $filtered[] = $slot;
    }

    return array_values($filtered);
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
    $seen_service_dates = [];
    if (! $query->have_posts()) {
        return $days;
    }

    while ($query->have_posts()) {
        $query->the_post();
        $booking_day_id = get_the_ID();
        $service_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
        $service_date_from = (string) get_post_meta($booking_day_id, 'goody_booking_service_date_from', true);
        $service_date_to = (string) get_post_meta($booking_day_id, 'goody_booking_service_date_to', true);
        if ($service_date_from === '') {
            $service_date_from = $service_date;
        }
        if ($service_date_to === '') {
            $service_date_to = $service_date_from;
        }
        $active = (string) get_post_meta($booking_day_id, 'goody_booking_day_active', true);
        if ($service_date_from === '' || $active === '0') {
            continue;
        }
        if ($service_date_to < $service_date_from) {
            $service_date_to = $service_date_from;
        }

        $cursor = strtotime($service_date_from . ' 00:00:00');
        $end_ts = strtotime($service_date_to . ' 00:00:00');
        if ($cursor === false || $end_ts === false) {
            continue;
        }

        while ($cursor <= $end_ts) {
            $date_cursor = gmdate('Y-m-d', $cursor);
            $cursor = strtotime('+1 day', $cursor);
            if ($date_cursor < $today || $date_cursor > $max_date) {
                continue;
            }
            if (in_array($date_cursor, $disabled_dates, true)) {
                continue;
            }
            if (isset($seen_service_dates[$date_cursor])) {
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
                            'value' => $date_cursor,
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
                'date' => $date_cursor,
                'title' => get_the_title(),
                'note' => (string) get_post_meta($booking_day_id, 'goody_booking_day_note', true),
                'display' => date_i18n('D, j M', strtotime($date_cursor . ' 00:00:00')),
                'slots' => goody_get_booking_day_slots($booking_day_id),
            ];
            $seen_service_dates[$date_cursor] = true;
        }
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

    $no_slots_message = __('No available slots for this date.', 'goody');
    $enabled_order_types = array_keys(goody_get_enabled_reservation_order_types());

    foreach ($available_days as $day) {
        $date_timestamp = strtotime($day['date'] . ' 00:00:00');
        if ($date_timestamp === false) {
            continue;
        }

        $has_bookable_slot = false;
        $slots = is_array($day['slots'] ?? null) ? $day['slots'] : [];
        foreach ($slots as $slot) {
            if (! is_array($slot)) {
                continue;
            }

            if (isset($slot['enabled']) && (string) $slot['enabled'] !== '1') {
                continue;
            }

            $slot_time = sanitize_text_field((string) ($slot['time'] ?? ''));
            if ($slot_time === '') {
                continue;
            }

            $allowed_types = array_values(array_filter(array_map('sanitize_key', array_map('trim', explode(',', (string) ($slot['order_types'] ?? ''))))));
            if (in_array('__none__', $allowed_types, true)) {
                continue;
            }

            $allowed_types_for_slot = empty($allowed_types)
                ? $enabled_order_types
                : array_values(array_intersect($allowed_types, $enabled_order_types));

            if (empty($allowed_types_for_slot)) {
                continue;
            }

            $slot_cutoff = max(0, absint($slot['cutoff_minutes'] ?? 0));
            if (goody_is_slot_cutoff_passed($day['date'], $slot_time, $slot_cutoff > 0 ? $slot_cutoff : null)) {
                continue;
            }

            if (goody_is_reservation_slot_reserved((int) ($day['id'] ?? 0), $slot_time, $day['date'])) {
                continue;
            }

            $has_bookable_slot = true;
            break;
        }

        $days[$day['date']] = [
            'id' => $day['id'],
            'date' => $day['date'],
            'day' => wp_date('D', $date_timestamp, $timezone),
            'number' => wp_date('j', $date_timestamp, $timezone),
            'month' => wp_date('M', $date_timestamp, $timezone),
            'display' => $day['display'],
            'note' => $day['note'],
            'disabled' => ! $has_bookable_slot,
            'disabled_reason' => $has_bookable_slot ? '' : $no_slots_message,
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

function goody_get_reservation_capacity_usage($booking_day_id, $slot_time, $service_date = '') {
    $usage = [
        'persons' => 0,
        'kg' => 0,
    ];
    $service_date = sanitize_text_field((string) $service_date);

    $meta_query = [
        [
            'key' => 'goody_booking_day_id',
            'value' => absint($booking_day_id),
        ],
        [
            'key' => 'goody_reservation_slot_time',
            'value' => sanitize_text_field((string) $slot_time),
        ],
    ];
    if ($service_date !== '') {
        $meta_query[] = [
            'key' => 'goody_reservation_date',
            'value' => $service_date,
        ];
    }

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
        'meta_query' => $meta_query,
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

function goody_is_reservation_slot_reserved($booking_day_id, $slot_time, $service_date = '') {
    $booking_day_id = absint($booking_day_id);
    $slot_time = sanitize_text_field((string) $slot_time);
    $service_date = sanitize_text_field((string) $service_date);
    if (! goody_should_lock_booked_reservation_slots() || $booking_day_id < 1 || $slot_time === '') {
        return false;
    }

    $meta_query = [
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
    ];
    if ($service_date !== '') {
        $meta_query[] = [
            'key' => 'goody_reservation_date',
            'value' => $service_date,
        ];
    }

    $table_layout = goody_get_reservation_table_layout();
    if (! empty($table_layout)) {
        $total_tables = 0;
        $booked_table_ids = [];

        foreach ($table_layout as $table) {
            $table_id = sanitize_key((string) ($table['id'] ?? ''));
            if ($table_id === '') {
                continue;
            }
            $total_tables++;
            if (goody_is_reservation_table_booked($booking_day_id, $slot_time, $service_date, $table_id)) {
                $booked_table_ids[$table_id] = true;
            }
        }

        // Slot is reserved only when every configured table is already booked.
        if ($total_tables > 0) {
            return count($booked_table_ids) >= $total_tables;
        }
    }

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'meta_query' => $meta_query,
    ]);

    return ! empty($query->posts);
}

function goody_render_reservation_slot_cards($booking_day_id, $order_type = '', $selected_time = '', $selected_person_need = 0, $selected_kg_need = 0, $service_date_override = '') {
    $service_date = sanitize_text_field((string) $service_date_override);
    if ($service_date === '') {
        $service_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
    }
    $slots = goody_get_booking_day_slots($booking_day_id);

    if (empty($slots)) {
        return '<div class="goody-inline-empty">' . esc_html__('No time slots are available for this date yet.', 'goody') . '</div>';
    }

    $enabled_order_type_keys = array_keys(goody_get_enabled_reservation_order_types());

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
        if (in_array('__none__', $allowed_types, true)) {
            continue;
        }
        $allowed_types_for_ui = empty($allowed_types)
            ? $enabled_order_type_keys
            : array_values(array_intersect($allowed_types, $enabled_order_type_keys));

        // If no enabled order type can use this slot, skip it for frontend safety.
        if (empty($allowed_types_for_ui)) {
            continue;
        }

        $matches_order_type = $order_type === '' || in_array($order_type, $allowed_types_for_ui, true);
        $is_enabled = ! isset($slot['enabled']) || (string) $slot['enabled'] === '1';
        if (! $is_enabled) {
            continue;
        }
        $capacity_persons = max(0, absint($slot['capacity_persons'] ?? 0));
        $capacity_kg = max(0, (float) ($slot['capacity_kg'] ?? 0));
        $usage = goody_get_reservation_capacity_usage($booking_day_id, $time, $service_date);
        $remaining_persons = max(0, $capacity_persons - $usage['persons']);
        $remaining_kg = max(0, $capacity_kg - $usage['kg']);
        $slot_cutoff = max(0, absint($slot['cutoff_minutes'] ?? 0));
        $slot_warning = sanitize_text_field((string) ($slot['warning'] ?? ''));
        $cutoff_passed = goody_is_slot_cutoff_passed($service_date, $time, $slot_cutoff > 0 ? $slot_cutoff : null);
        $fits_persons = $capacity_persons <= 0 || $selected_person_need <= $remaining_persons;
        $fits_kg = $capacity_kg <= 0 || $selected_kg_need <= $remaining_kg;
        $slot_reserved = goody_is_reservation_slot_reserved($booking_day_id, $time, $service_date);
        $is_disabled = ! $matches_order_type || $cutoff_passed || $slot_reserved || ! $fits_persons || ! $fits_kg;
        $state_class = ' is-available';
        $state_key = 'available';
        if ($cutoff_passed) {
            $state_class = ' is-disabled-cutoff';
            $state_key = 'cutoff';
        } elseif ($slot_reserved) {
            $state_class = ' is-booked';
            $state_key = 'booked';
        } elseif (! $matches_order_type) {
            $state_class = ' is-disabled-order-type';
            $state_key = 'order_type';
        } elseif (! $fits_persons || ! $fits_kg) {
            $state_class = ' is-disabled-capacity';
            $state_key = 'capacity';
        }
        $selected_class = $selected_time === $time ? ' is-selected' : '';

        echo '<button type="button" class="goody-slot-card' . esc_attr($selected_class . $state_class) . '" data-slot="' . esc_attr($time) . '" data-state="' . esc_attr($state_key) . '"';
        echo ' data-slot-types="' . esc_attr(implode(',', $allowed_types_for_ui)) . '"';
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

        if ($cutoff_passed) {
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

function goody_get_reservation_table_layout() {
    $raw_layout = (string) goody_get_option('reservation_tables_layout', '');
    $lines = preg_split('/\r\n|\r|\n/', $raw_layout);
    if (! is_array($lines)) {
        $lines = [];
    }

    $tables = [];
    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            continue;
        }

        $parts = array_map('trim', explode('|', $line));
        if (count($parts) < 4) {
            continue;
        }

        $table_id = sanitize_key((string) ($parts[0] ?? ''));
        if ($table_id === '') {
            continue;
        }

        $label = sanitize_text_field((string) ($parts[1] ?? ''));
        $location = sanitize_text_field((string) ($parts[2] ?? ''));
        $capacity = max(1, absint($parts[3] ?? 1));

        $tables[$table_id] = [
            'id' => $table_id,
            'label' => $label !== '' ? $label : strtoupper($table_id),
            'location' => $location,
            'capacity' => $capacity,
        ];
    }

    return array_values($tables);
}

function goody_is_reservation_table_booked($booking_day_id, $slot_time, $service_date, $table_id) {
    $booking_day_id = absint($booking_day_id);
    $slot_time = sanitize_text_field((string) $slot_time);
    $service_date = sanitize_text_field((string) $service_date);
    $table_id = sanitize_key((string) $table_id);

    if ($booking_day_id < 1 || $slot_time === '' || $service_date === '' || $table_id === '') {
        return false;
    }

    $meta_query = [
        'relation' => 'AND',
        [
            'key' => 'goody_booking_day_id',
            'value' => (string) $booking_day_id,
        ],
        [
            'key' => 'goody_reservation_date',
            'value' => $service_date,
        ],
        [
            'key' => 'goody_reservation_slot_time',
            'value' => $slot_time,
        ],
        [
            'key' => 'goody_reservation_table_id',
            'value' => $table_id,
        ],
        [
            'key' => 'goody_reservation_status',
            'value' => 'cancelled',
            'compare' => '!=',
        ],
    ];

    $query = new WP_Query([
        'post_type' => 'goody_reservation',
        'post_status' => 'publish',
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'meta_query' => $meta_query,
    ]);

    return ! empty($query->posts);
}

function goody_render_reservation_table_cards($booking_day_id, $service_date, $slot_time, $guests = 1, $selected_table_id = '') {
    $tables = goody_get_reservation_table_layout();
    if (empty($tables)) {
        return '<div class="goody-inline-empty">' . esc_html__('No tables are configured yet. Add table layout from Theme Settings.', 'goody') . '</div>';
    }

    $guests = max(1, absint($guests));
    $selected_table_id = sanitize_key((string) $selected_table_id);

    ob_start();
    echo '<div class="goody-slot-grid goody-table-grid">';
    foreach ($tables as $table) {
        $table_id = sanitize_key((string) ($table['id'] ?? ''));
        if ($table_id === '') {
            continue;
        }

        $capacity = max(1, absint($table['capacity'] ?? 1));
        $is_booked = goody_is_reservation_table_booked($booking_day_id, $slot_time, $service_date, $table_id);
        $cutoff_passed = goody_is_slot_cutoff_passed($service_date, $slot_time, null);
        $fits_guests = $guests <= $capacity;
        $is_disabled = $cutoff_passed || $is_booked || ! $fits_guests;
        $state_class = ' is-available';
        $state_key = 'available';
        if ($cutoff_passed) {
            $state_class = ' is-disabled-cutoff';
            $state_key = 'cutoff';
        } elseif ($is_booked) {
            $state_class = ' is-booked';
            $state_key = 'booked';
        } elseif (! $fits_guests) {
            $state_class = ' is-disabled-capacity';
            $state_key = 'capacity';
        }
        $selected_class = $selected_table_id === $table_id ? ' is-selected' : '';

        $seat_segments = [
            'top' => 0,
            'right' => 0,
            'bottom' => 0,
            'left' => 0,
        ];
        $segment_sides = ['top', 'right', 'bottom', 'left'];
        for ($i = 0; $i < $capacity; $i++) {
            $segment_side = $segment_sides[$i % 4];
            $seat_segments[$segment_side]++;
        }
        $max_per_side = max(1, (int) ceil($capacity / 4));
        $top_pct = (int) round(($seat_segments['top'] / $max_per_side) * 100);
        $right_pct = (int) round(($seat_segments['right'] / $max_per_side) * 100);
        $bottom_pct = (int) round(($seat_segments['bottom'] / $max_per_side) * 100);
        $left_pct = (int) round(($seat_segments['left'] / $max_per_side) * 100);
        $table_side_style = '--goody-seat-top:' . (int) $seat_segments['top'] . ';--goody-seat-right:' . (int) $seat_segments['right'] . ';--goody-seat-bottom:' . (int) $seat_segments['bottom'] . ';--goody-seat-left:' . (int) $seat_segments['left'] . ';--goody-seat-top-len:' . $top_pct . '%;--goody-seat-right-len:' . $right_pct . '%;--goody-seat-bottom-len:' . $bottom_pct . '%;--goody-seat-left-len:' . $left_pct . '%;';

        echo '<button type="button" class="goody-slot-card goody-table-card' . esc_attr($selected_class . $state_class) . '" data-table-id="' . esc_attr($table_id) . '" data-table-label="' . esc_attr((string) ($table['label'] ?? '')) . '" data-table-location="' . esc_attr((string) ($table['location'] ?? '')) . '" data-table-capacity="' . esc_attr((string) $capacity) . '" data-state="' . esc_attr($state_key) . '" style="' . esc_attr($table_side_style) . '"';
        echo $is_disabled ? ' disabled' : '';
        echo '>';
        echo '<span class="goody-slot-card__time">' . esc_html((string) ($table['label'] ?? strtoupper($table_id))) . '</span>';
        echo '<span class="goody-slot-card__meta">' . esc_html((string) ($table['location'] ?? '')) . ' • ' . esc_html(sprintf(__('%d seats', 'goody'), $capacity)) . '</span>';

        if ($cutoff_passed) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Cut-off passed', 'goody') . '</span>';
        } elseif ($is_booked) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Booked', 'goody') . '</span>';
        } elseif (! $fits_guests) {
            echo '<span class="goody-slot-card__state">' . esc_html__('Not enough seats', 'goody') . '</span>';
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
    $selected_booking_date = sanitize_text_field((string) ($payload['booking_date'] ?? ''));
    $slot_time = sanitize_text_field((string) ($payload['slot_time'] ?? ''));
    $table_id = sanitize_key((string) ($payload['table_id'] ?? ''));
    $order_type = sanitize_key((string) ($payload['order_type'] ?? ''));
    $payment_mode = sanitize_key((string) ($payload['payment_mode'] ?? 'full'));
    $delivery_provider = goody_sanitize_reservation_delivery_provider($payload['delivery_provider'] ?? '');
    if ($delivery_provider === '') {
        $delivery_provider = goody_get_reservation_default_delivery_provider();
    }
    $guests = max(1, absint($payload['guests'] ?? 1));
    $items_payload = goody_normalize_reservation_item_payload($payload['items'] ?? []);
    $customer = is_array($payload['customer'] ?? null) ? $payload['customer'] : [];
    $notes = sanitize_textarea_field((string) ($customer['note'] ?? ''));
    $address = sanitize_textarea_field((string) ($customer['address'] ?? ''));
    $customer_name = sanitize_text_field((string) ($customer['name'] ?? ''));
    $customer_phone = sanitize_text_field((string) ($customer['phone'] ?? ''));
    $customer_email = sanitize_email((string) ($customer['email'] ?? ''));

    if ($booking_day_id < 1 || get_post_type($booking_day_id) !== 'goody_booking_day') {
        return new WP_Error('invalid_day', __('Please select a booking date.', 'goody'));
    }

    $booking_day_date = (string) get_post_meta($booking_day_id, 'goody_booking_service_date', true);
    $booking_day_start = (string) get_post_meta($booking_day_id, 'goody_booking_service_date_from', true);
    $booking_day_end = (string) get_post_meta($booking_day_id, 'goody_booking_service_date_to', true);
    if ($booking_day_start === '') {
        $booking_day_start = $booking_day_date;
    }
    if ($booking_day_end === '') {
        $booking_day_end = $booking_day_start;
    }
    if ($booking_day_start === '') {
        return new WP_Error('invalid_day', __('Selected date is not available.', 'goody'));
    }
    if ($booking_day_end < $booking_day_start) {
        $booking_day_end = $booking_day_start;
    }
    if ($selected_booking_date === '') {
        $selected_booking_date = $booking_day_start;
    }
    if ($selected_booking_date < $booking_day_start || $selected_booking_date > $booking_day_end) {
        return new WP_Error('invalid_day', __('Selected date is not available.', 'goody'));
    }
    $booking_day_date = $selected_booking_date;

    $menu_step_enabled = goody_is_reservation_menu_step_enabled();
    if ($menu_step_enabled && empty($items_payload)) {
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
    if (in_array('__none__', $allowed_types, true)) {
        return new WP_Error('invalid_slot_type', __('This slot is not available for the selected order type.', 'goody'));
    }
    if (! empty($allowed_types) && ! in_array($order_type, $allowed_types, true)) {
        return new WP_Error('invalid_slot_type', __('This slot is not available for the selected order type.', 'goody'));
    }

    if (goody_is_slot_cutoff_passed($booking_day_date, $slot_time, absint($slot_row['cutoff_minutes'] ?? 0))) {
        return new WP_Error('slot_cutoff', __('This slot can no longer be booked.', 'goody'));
    }

    if (goody_is_reservation_slot_reserved($booking_day_id, $slot_time, $booking_day_date)) {
        return new WP_Error('slot_reserved', __('This time slot is already reserved.', 'goody'));
    }

    $table_layout = goody_get_reservation_table_layout();
    $tables_by_id = [];
    foreach ($table_layout as $table) {
        $id = sanitize_key((string) ($table['id'] ?? ''));
        if ($id === '') {
            continue;
        }
        $tables_by_id[$id] = $table;
    }
    if (! empty($tables_by_id)) {
        if ($table_id === '' || ! isset($tables_by_id[$table_id])) {
            return new WP_Error('invalid_table', __('Please choose a table.', 'goody'));
        }

        $selected_table = $tables_by_id[$table_id];
        $table_capacity = max(1, absint($selected_table['capacity'] ?? 1));
        if ($guests > $table_capacity) {
            return new WP_Error('table_capacity', __('Selected table does not have enough seats.', 'goody'));
        }

        if (goody_is_reservation_table_booked($booking_day_id, $slot_time, $booking_day_date, $table_id)) {
            return new WP_Error('table_booked', __('Selected table is already booked for this slot.', 'goody'));
        }
    } else {
        $selected_table = null;
        $table_id = '';
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

    $capacity_usage = goody_get_reservation_capacity_usage($booking_day_id, $slot_time, $booking_day_date);
    $slot_capacity_persons = max(0, absint($slot_row['capacity_persons'] ?? 0));
    $slot_capacity_kg = max(0, (float) ($slot_row['capacity_kg'] ?? 0));
    if ($slot_capacity_persons > 0 && ($capacity_usage['persons'] + $capacity_persons) > $slot_capacity_persons) {
        return new WP_Error('slot_capacity_persons', __('Not enough person capacity left in this slot.', 'goody'));
    }
    if ($slot_capacity_kg > 0 && ($capacity_usage['kg'] + $capacity_kg) > $slot_capacity_kg) {
        return new WP_Error('slot_capacity_kg', __('Not enough KG capacity left in this slot.', 'goody'));
    }

    $delivery_charge = 0;
    if ($order_type === 'delivery') {
        if ($delivery_provider === '') {
            return new WP_Error('delivery_provider_missing', __('Please choose a delivery provider.', 'goody'));
        }
    } else {
        $delivery_provider = '';
    }

    $type_minimums = [
        'dine_in' => (float) goody_get_option('reservation_min_order_dine_in', '0'),
        'pickup' => (float) goody_get_option('reservation_min_order_pickup', '0'),
        'delivery' => (float) goody_get_option('reservation_min_order_delivery', '0'),
    ];
    $type_minimum = $type_minimums[$order_type] ?? 0;
    if ($menu_step_enabled && $type_minimum > 0 && $subtotal < $type_minimum) {
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
        if ($customer_email !== '' && ! is_email($customer_email)) {
            return new WP_Error('invalid_email', __('Please enter a valid email address.', 'goody'));
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
        'table_id' => $table_id,
        'table_label' => $selected_table ? sanitize_text_field((string) ($selected_table['label'] ?? $table_id)) : '',
        'table_location' => $selected_table ? sanitize_text_field((string) ($selected_table['location'] ?? '')) : '',
        'order_type' => $order_type,
        'order_type_label' => $order_types[$order_type],
        'payment_mode' => $payment_mode,
        'payment_mode_label' => $payment_modes[$payment_mode] ?? $payment_modes['full'],
        'delivery_provider' => $delivery_provider,
        'delivery_provider_label' => goody_get_reservation_delivery_provider_choices()[$delivery_provider] ?? 'Goody',
        'guests' => $guests,
        'items' => $items,
        'subtotal' => $subtotal,
        'delivery_charge' => $delivery_charge,
        'grand_total' => $grand_total,
        'pay_now_total' => $pay_now_total,
        'balance_due' => $balance_due,
        'capacity_persons' => $capacity_persons,
        'capacity_kg' => $capacity_kg,
        'customer' => [
            'name' => $customer_name,
            'phone' => $customer_phone,
            'email' => $customer_email,
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
            <?php if (! empty($quote['table_label'])) : ?>
                <p><?php echo esc_html($quote['table_label']); ?><?php echo ! empty($quote['table_location']) ? ' • ' . esc_html($quote['table_location']) : ''; ?></p>
            <?php endif; ?>
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
    update_post_meta($reservation_id, 'goody_reservation_table_id', sanitize_key((string) ($quote['table_id'] ?? '')));
    update_post_meta($reservation_id, 'goody_reservation_table_label', sanitize_text_field((string) ($quote['table_label'] ?? '')));
    update_post_meta($reservation_id, 'goody_reservation_table_location', sanitize_text_field((string) ($quote['table_location'] ?? '')));
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
    update_post_meta($reservation_id, 'goody_reservation_email', sanitize_email((string) ($quote['customer']['email'] ?? '')));
    update_post_meta($reservation_id, 'goody_reservation_address', sanitize_textarea_field((string) $quote['customer']['address']));
    update_post_meta($reservation_id, 'goody_reservation_note', sanitize_textarea_field((string) $quote['customer']['note']));
    update_post_meta($reservation_id, 'goody_reservation_subtotal', (string) $quote['subtotal']);
    update_post_meta($reservation_id, 'goody_reservation_delivery_charge', (string) $quote['delivery_charge']);
    update_post_meta($reservation_id, 'goody_reservation_total', (string) $quote['grand_total']);
    update_post_meta($reservation_id, 'goody_reservation_pay_now_total', (string) $quote['pay_now_total']);
    update_post_meta($reservation_id, 'goody_reservation_balance_due', (string) $quote['balance_due']);
    update_post_meta($reservation_id, 'goody_reservation_items_json', wp_json_encode($quote['items']));
    update_post_meta($reservation_id, 'goody_reservation_summary_html', goody_render_reservation_summary_html($quote));
    if (function_exists('goody_reservation_post_created_sync_record')) {
        goody_reservation_post_created_sync_record($reservation_id);
    }

    return $reservation_id;
}

function goody_create_woocommerce_order_from_reservation($reservation_id, $quote) {
    $reservation_id = absint($reservation_id);
    if (! function_exists('wc_create_order') || ! class_exists('WC_Order_Item_Fee')) {
        return new WP_Error('woocommerce_missing', __('WooCommerce is required for payment processing.', 'goody'));
    }

    $order = wc_create_order();
    if (! $order instanceof WC_Order) {
        return new WP_Error('order_create_failed', __('Unable to create the WooCommerce order.', 'goody'));
    }

    foreach ($quote['items'] as $item) {
        $menu_item_id = absint($item['id'] ?? 0);
        $qty = max(0.01, (float) ($item['qty'] ?? 1));
        $line_added = false;
        try {
            $product_id = goody_sync_menu_item_wc_product($menu_item_id);
            $product = $product_id > 0 ? wc_get_product($product_id) : null;

            if ($product instanceof WC_Product) {
                $order_item_id = $order->add_product($product, $qty);
                if ($order_item_id) {
                    $order_item = $order->get_item($order_item_id);
                    if ($order_item instanceof WC_Order_Item_Product) {
                        $order_item->add_meta_data(__('Menu item ID', 'goody'), (string) $menu_item_id, true);
                        if (! empty($item['unit_label'])) {
                            $order_item->add_meta_data(__('Unit', 'goody'), sanitize_text_field((string) $item['unit_label']), true);
                        }
                        $order_item->save();
                    }
                    $line_added = true;
                }
            }
        } catch (Throwable $e) {
            $line_added = false;
        }

        if (! $line_added) {
            $fee_item = new WC_Order_Item_Fee();
            $fee_item->set_name($item['name'] . ' × ' . rtrim(rtrim(number_format($qty, 2, '.', ''), '0'), '.'));
            $fee_item->set_amount((float) $item['line_total']);
            $fee_item->set_total((float) $item['line_total']);
            $fee_item->set_tax_status('none');
            $fee_item->add_meta_data(__('Menu item ID', 'goody'), (string) $menu_item_id, true);
            $order->add_item($fee_item);
        }

        $addons_total = 0;
        if (! empty($item['addons']) && is_array($item['addons'])) {
            foreach ($item['addons'] as $addon) {
                $addons_total += ((float) ($addon['price'] ?? 0)) * $qty;
            }
        }
        if ($addons_total > 0) {
            $addons_fee = new WC_Order_Item_Fee();
            $addons_fee->set_name(sprintf(__('Add-ons for %s', 'goody'), sanitize_text_field((string) $item['name'])));
            $addons_fee->set_amount((float) $addons_total);
            $addons_fee->set_total((float) $addons_total);
            $addons_fee->set_tax_status('none');
            $addons_fee->add_meta_data(__('Menu item ID', 'goody'), (string) $menu_item_id, true);
            $addons_fee->add_meta_data(__('Add-ons', 'goody'), implode(', ', wp_list_pluck((array) $item['addons'], 'name')), true);
            $order->add_item($addons_fee);
        }
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
    $order->set_billing_email(sanitize_email((string) ($quote['customer']['email'] ?? '')));
    $order->set_billing_address_1(sanitize_text_field((string) ($quote['customer']['address'] ?? '')));
    $order->set_shipping_first_name(sanitize_text_field((string) ($quote['customer']['name'] ?? '')));
    $order->set_shipping_phone(sanitize_text_field((string) ($quote['customer']['phone'] ?? '')));
    $order->set_shipping_address_1(sanitize_text_field((string) ($quote['customer']['address'] ?? '')));

    if ($reservation_id > 0) {
        $order->update_meta_data('_goody_reservation_id', $reservation_id);
        $order->update_meta_data('_goody_reservation_code', goody_get_reservation_reference($reservation_id));
    }
    $order->update_meta_data('_goody_reservation_date', $quote['booking_date']);
    $order->update_meta_data('_goody_reservation_slot', $quote['slot_label']);
    $order->update_meta_data('_goody_reservation_table', sanitize_text_field((string) ($quote['table_label'] ?? '')));
    $order->update_meta_data('_goody_reservation_table_id', sanitize_key((string) ($quote['table_id'] ?? '')));
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

    if ($reservation_id > 0) {
        update_post_meta($reservation_id, 'goody_wc_order_id', (string) $order->get_id());
        update_post_meta($reservation_id, 'goody_reservation_payment_status', sanitize_text_field((string) $order->get_status()));
        if (function_exists('goody_upsert_reservation_record')) {
            goody_upsert_reservation_record($reservation_id);
        }
    }

    return $order;
}

function goody_maybe_sync_reservation_customer_to_mailchimp($quote) {
    if (! is_array($quote) || ! function_exists('goody_mailchimp_upsert_subscriber')) {
        return;
    }

    $customer = is_array($quote['customer'] ?? null) ? $quote['customer'] : [];
    $email = sanitize_email((string) ($customer['email'] ?? ''));
    if (! is_email($email)) {
        return;
    }

    goody_mailchimp_upsert_subscriber($email, [
        'merge_fields' => [
            'FNAME' => sanitize_text_field((string) ($customer['name'] ?? '')),
            'PHONE' => sanitize_text_field((string) ($customer['phone'] ?? '')),
        ],
        'tags' => ['Reservation'],
        'status' => 'subscribed',
    ]);
}

function goody_maybe_create_reservation_after_payment($order_id) {
    $order_id = absint($order_id);
    if ($order_id < 1 || ! function_exists('wc_get_order')) {
        return;
    }

    $order = wc_get_order($order_id);
    if (! $order instanceof WC_Order) {
        return;
    }

    $existing_reservation_id = absint($order->get_meta('_goody_reservation_id', true));
    if ($existing_reservation_id > 0) {
        return;
    }

    $raw_quote = (string) $order->get_meta('_goody_pending_reservation_quote', true);
    if ($raw_quote === '') {
        return;
    }

    $quote = json_decode($raw_quote, true);
    if (! is_array($quote)) {
        return;
    }

    $reservation_id = goody_create_reservation_post($quote);
    if (is_wp_error($reservation_id)) {
        return;
    }
    goody_maybe_sync_reservation_customer_to_mailchimp($quote);

    update_post_meta($reservation_id, 'goody_wc_order_id', (string) $order_id);
    update_post_meta($reservation_id, 'goody_reservation_payment_status', sanitize_text_field((string) $order->get_status()));
    if (function_exists('goody_upsert_reservation_record')) {
        goody_upsert_reservation_record($reservation_id);
    }

    $order->update_meta_data('_goody_reservation_id', $reservation_id);
    $order->update_meta_data('_goody_reservation_code', goody_get_reservation_reference($reservation_id));
    $order->delete_meta_data('_goody_pending_reservation_quote');
    $order->save();
}
add_action('woocommerce_payment_complete', 'goody_maybe_create_reservation_after_payment', 20);
add_action('woocommerce_order_status_processing', 'goody_maybe_create_reservation_after_payment', 20);
add_action('woocommerce_order_status_completed', 'goody_maybe_create_reservation_after_payment', 20);

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
    $booking_date = sanitize_text_field((string) ($_POST['booking_date'] ?? ''));

    wp_send_json_success([
        'html' => goody_render_reservation_slot_cards($booking_day_id, $order_type, $selected_time, $person_need, $kg_need, $booking_date),
    ]);
}
add_action('wp_ajax_goody_reservation_slots', 'goody_ajax_reservation_slots');
add_action('wp_ajax_nopriv_goody_reservation_slots', 'goody_ajax_reservation_slots');

function goody_ajax_reservation_tables() {
    check_ajax_referer('goody_nonce', 'nonce');

    $booking_day_id = absint($_POST['booking_day_id'] ?? 0);
    $booking_date = sanitize_text_field((string) ($_POST['booking_date'] ?? ''));
    $slot_time = sanitize_text_field((string) ($_POST['slot_time'] ?? ''));
    $guests = max(1, absint($_POST['guests'] ?? 1));
    $selected_table_id = sanitize_key((string) ($_POST['selected_table_id'] ?? ''));

    if ($booking_day_id < 1 || $booking_date === '' || $slot_time === '') {
        wp_send_json_error([
            'message' => __('Please choose date and slot first.', 'goody'),
        ], 422);
    }

    wp_send_json_success([
        'html' => goody_render_reservation_table_cards($booking_day_id, $booking_date, $slot_time, $guests, $selected_table_id),
    ]);
}
add_action('wp_ajax_goody_reservation_tables', 'goody_ajax_reservation_tables');
add_action('wp_ajax_nopriv_goody_reservation_tables', 'goody_ajax_reservation_tables');

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

    $should_create_wc_order = class_exists('WooCommerce') && goody_get_option('reservation_auto_create_wc_order', '1') === '1';
    $payment_mode = sanitize_key((string) ($quote['payment_mode'] ?? 'full'));

    // Cash mode: create reservation immediately (existing behavior).
    if ($payment_mode === 'cash') {
        $reservation_id = goody_create_reservation_post($quote);
        if (is_wp_error($reservation_id)) {
            wp_send_json_error([
                'message' => $reservation_id->get_error_message(),
            ], 500);
        }
        goody_maybe_sync_reservation_customer_to_mailchimp($quote);

        $order = null;
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

    // Full/advance: require payment-first flow.
    if (! $should_create_wc_order) {
        wp_send_json_error([
            'message' => __('Online payment is currently unavailable. Please use cash payment or contact support.', 'goody'),
        ], 422);
    }

    $order = goody_create_woocommerce_order_from_reservation(0, $quote);
    if (is_wp_error($order)) {
        wp_send_json_error([
            'message' => $order->get_error_message(),
        ], 500);
    }

    if ($order instanceof WC_Order) {
        $order->update_meta_data('_goody_pending_reservation_quote', wp_json_encode($quote));
        $order->save();
    }

    wp_send_json_success([
        'reservation_id' => 0,
        'reference' => '',
        'redirect_url' => goody_get_reservation_redirect_url(0, $order instanceof WC_Order ? $order : null),
        'message' => __('Proceed to payment to confirm your reservation.', 'goody'),
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
    $table_layout = goody_get_reservation_table_layout();
    $menu_step_enabled = goody_is_reservation_menu_step_enabled();
    $contact_phone = sanitize_text_field((string) goody_get_option('contact_phone', ''));
    $whatsapp_number = preg_replace('/\D+/', '', (string) goody_get_option('contact_whatsapp_number', ''));

    return [
        'dates' => goody_get_available_booking_days(),
        'calendarDays' => goody_get_reservation_calendar_days(),
        'menuItems' => goody_get_reservable_menu_items(),
        'categories' => goody_get_reservation_menu_categories(),
        'zones' => [],
        'orderTypes' => $order_types,
        'paymentModes' => $payment_modes,
        'deliveryProviders' => goody_get_reservation_delivery_provider_choices(),
        'steps' => array_values(goody_get_reservation_step_titles()),
        'fieldSettings' => goody_get_reservation_customer_field_settings(),
        'texts' => [
            'pickupWarning' => goody_get_option('reservation_pickup_warning', __('Pickup orders must be collected on time from the restaurant.', 'goody')),
            'deliveryWarning' => goody_get_option('reservation_delivery_warning', __('Delivery address is required for delivery orders.', 'goody')),
            'cashWarning' => goody_get_option('reservation_cash_warning', __('Cash orders stay reserved and will be confirmed by the restaurant.', 'goody')),
            'dineInNote' => goody_get_option('reservation_dine_in_note', __('Dine-in reservations are held for a limited time after the selected slot starts.', 'goody')),
            'bookingNotice' => goody_get_option('reservation_booking_notice', __('Choose your preferred date, dishes, slot, and payment plan below.', 'goody')),
            'errorMessage' => goody_get_option('reservation_error_message', __('Please review the form and try again.', 'goody')),
            'stepCounterPrefix' => goody_get_reservation_step_counter_prefix(),
            'summaryPlaceholder' => __('Your selected date, menu, slot, and totals will update here.', 'goody'),
            'selectItem' => __('Add to order', 'goody'),
            'updateItem' => __('Update selection', 'goody'),
            'unavailableItem' => __('Unavailable', 'goody'),
            'missingDate' => __('Please choose a date first.', 'goody'),
            'missingItem' => __('Please add at least one menu item.', 'goody'),
            'missingSlot' => __('Please choose a time slot.', 'goody'),
            'missingTable' => __('Please choose a table.', 'goody'),
            'missingOrderType' => __('Please choose an order type.', 'goody'),
            'missingPaymentMode' => __('Please choose a payment option.', 'goody'),
            'missingDeliveryProvider' => __('Please choose a delivery provider.', 'goody'),
            'missingName' => __('Please enter your name.', 'goody'),
            'missingPhone' => __('Please enter your phone number.', 'goody'),
            'invalidEmail' => __('Please enter a valid email address.', 'goody'),
            'missingAddress' => __('Please enter the delivery address.', 'goody'),
            'statusCurrent' => __('Current status:', 'goody'),
            'labelDate' => __('Date', 'goody'),
            'labelSlot' => __('Time slot', 'goody'),
            'labelTable' => __('Table', 'goody'),
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
            'defaultDeliveryProvider' => goody_get_reservation_default_delivery_provider(),
            'hasTableLayout' => ! empty($table_layout),
            'menuStepEnabled' => $menu_step_enabled,
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
    if ($show_direct_order) {
        $menu_item_ids = array_values(array_unique(array_filter(array_map(
            static function ($item) {
                return absint(is_array($item) ? ($item['id'] ?? 0) : 0);
            },
            $menu_items
        ))));

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

            <?php
            if (! empty($menu_item_ids)) {
                $menu_query = new WP_Query([
                    'post_type' => 'menu_item',
                    'post_status' => 'publish',
                    'post__in' => $menu_item_ids,
                    'orderby' => 'post__in',
                    'posts_per_page' => count($menu_item_ids),
                    'ignore_sticky_posts' => true,
                    'no_found_rows' => true,
                ]);

                echo goody_render_menu_items_markup($menu_query);
            } else {
                echo '<div class="card empty-state"><h3>' . esc_html__('No menu items found', 'goody') . '</h3><p>' . esc_html__('Try changing filters or add more dishes from Dashboard → Menu Items.', 'goody') . '</p></div>';
            }
            ?>
        </div>
        <?php

        return ob_get_clean();
    }

    $menu_item_ids = array_values(array_unique(array_filter(array_map(
        static function ($item) {
            return absint(is_array($item) ? ($item['id'] ?? 0) : 0);
        },
        $menu_items
    ))));
    $reservation_items_map = [];
    foreach ($menu_items as $item) {
        if (! is_array($item) || empty($item['id'])) {
            continue;
        }
        $reservation_items_map[absint($item['id'])] = $item;
    }

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

        <?php
        if (! empty($menu_item_ids)) {
            $menu_query = new WP_Query([
                'post_type' => 'menu_item',
                'post_status' => 'publish',
                'post__in' => $menu_item_ids,
                'orderby' => 'post__in',
                'posts_per_page' => count($menu_item_ids),
                'ignore_sticky_posts' => true,
                'no_found_rows' => true,
            ]);

            $GLOBALS['goody_reservation_menu_context'] = [
                'enabled' => true,
                'show_direct_order' => false,
                'items' => $reservation_items_map,
            ];
            $reservation_menu_html = (string) goody_render_menu_items_markup($menu_query);
            $reservation_menu_html = preg_replace(
                '/class="archive-grid archive-grid--three"/',
                'class="goody-booking-menu__grid archive-grid archive-grid--three"',
                $reservation_menu_html,
                1
            );
            echo $reservation_menu_html;
            unset($GLOBALS['goody_reservation_menu_context']);
        } else {
            echo '<div class="card empty-state"><h3>' . esc_html__('No menu items found', 'goody') . '</h3><p>' . esc_html__('Try changing filters or add more dishes from Dashboard → Menu Items.', 'goody') . '</p></div>';
        }
        ?>
    </div>
    <?php

    return ob_get_clean();
}

function goody_get_reservation_choice_icon_svg($group, $key) {
    $group = sanitize_key((string) $group);
    $key = sanitize_key((string) $key);

    $icons = [
        'order_type' => [
            'dine_in' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4v7a3 3 0 0 0 3 3h1v6h2V4H8v5H7V4H5v5H4V4Zm8 2h7v2h-1v12h-2V8h-2v12h-2V6Z"/></svg>',
            'pickup' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h15a3 3 0 0 1 3 3v4h-2v-1H5v1H3V7Zm2 4h14v-1a1 1 0 0 0-1-1H5v2Zm2 4h2a2 2 0 1 0 4 0h4a2 2 0 1 0 4 0h1v2h-1a4 4 0 0 1-8 0H13a4 4 0 0 1-8 0H3v-2h2a2 2 0 0 0 2 2Z"/></svg>',
            'delivery' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 6h11v8h2.5l2.2-3H21v5h-1a3 3 0 0 1-6 0H9a3 3 0 0 1-6 0H2V6Zm2 8h1a3 3 0 0 1 6 0h.5V8H4v6Zm13.7-1 1.1-1.5H15v1.5h2.7ZM6 19a1 1 0 1 0 0-2 1 1 0 0 0 0 2Zm11 0a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/></svg>',
        ],
        'payment_mode' => [
            'full' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6Zm2 0v2h14V6H5Zm0 4v8h14v-8H5Zm2 2h5v2H7v-2Z"/></svg>',
            'advance' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2 3 6v6c0 5 3.8 9.7 9 11 5.2-1.3 9-6 9-11V6l-9-4Zm0 3.2 6 2.7V12c0 3.8-2.6 7.5-6 8.6-3.4-1.1-6-4.8-6-8.6V7.9l6-2.7Zm-1 3.8h2v3h3v2h-5V9Z"/></svg>',
            'cash' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2 7a3 3 0 0 1 3-3h14a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3H5a3 3 0 0 1-3-3V7Zm3-1a1 1 0 0 0-1 1v1h16V7a1 1 0 0 0-1-1H5Zm-1 4v7a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-7H4Zm8 1a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 2a1 1 0 1 0 0 2 1 1 0 0 0 0-2Z"/></svg>',
        ],
    ];

    return (string) ($icons[$group][$key] ?? '');
}

function goody_render_reservation_choice_label_with_icon($label, $group, $key) {
    $label = sanitize_text_field((string) $label);
    $svg = goody_get_reservation_choice_icon_svg($group, $key);
    $svg_html = '';
    if ($svg !== '') {
        $svg_html = wp_kses(
            $svg,
            [
                'svg' => [
                    'viewBox' => true,
                    'aria-hidden' => true,
                    'focusable' => true,
                    'class' => true,
                ],
                'path' => [
                    'd' => true,
                    'fill' => true,
                ],
            ]
        );
    }

    return '<span class="goody-choice-card__content">' .
        ($svg_html !== '' ? '<span class="goody-choice-card__icon">' . $svg_html . '</span>' : '') .
        '<span class="goody-choice-card__label">' . esc_html($label) . '</span>' .
    '</span>';
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
    $menu_step_enabled = goody_is_reservation_menu_step_enabled();
    $step_titles_display = $step_titles;
    if (! $menu_step_enabled && isset($step_titles_display[2])) {
        unset($step_titles_display[2]);
    }
    $step_numbers = array_values(array_keys($step_titles_display));
    $step_display_numbers = [];
    foreach ($step_numbers as $display_index => $step_number) {
        $step_display_numbers[(int) $step_number] = $display_index + 1;
    }
    $first_step_number = ! empty($step_numbers) ? (int) $step_numbers[0] : 1;
    $first_step_title = $step_titles_display[$first_step_number] ?? ($step_titles[1] ?? __('Date', 'goody'));
    $first_step_display_number = (int) ($step_display_numbers[$first_step_number] ?? 1);
    $total_steps = max(1, count($step_numbers));
    $step1_next = $menu_step_enabled ? 2 : 3;
    $step3_prev = $menu_step_enabled ? 2 : 1;
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
                    <strong class="goody-step-counter" data-step-counter><?php echo esc_html(goody_get_reservation_step_counter_prefix() . ' ' . $first_step_display_number . '/' . $total_steps); ?></strong>
                    <span class="goody-step-title-current" data-current-step-title><?php echo esc_html($first_step_title); ?></span>
                </div>
                <div class="goody-reservation-progress__track" aria-hidden="true">
                    <span class="goody-reservation-progress__fill" data-progress-fill></span>
                </div>
            </div>

            <ol class="goody-reservation-steps" aria-label="<?php esc_attr_e('Booking steps', 'goody'); ?>">
                <?php foreach ($step_titles_display as $step_number => $step_title) : ?>
                    <li class="<?php echo $step_number === $first_step_number ? 'is-active' : ''; ?>" data-step-marker="<?php echo esc_attr((string) $step_number); ?>"><span><?php echo esc_html((string) ($step_display_numbers[(int) $step_number] ?? $step_number)); ?></span><strong><?php echo esc_html($step_title); ?></strong></li>
                <?php endforeach; ?>
            </ol>

            <div class="goody-final-message" data-final-message></div>

            <div class="goody-reservation-layout">
                <div class="goody-reservation-panels">
                    <section class="goody-reservation-panel is-active" data-step-panel="1">
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[1] ?? 1), $step_titles[1] ?? __('Date', 'goody'))); ?></h3>
                        <div class="goody-date-grid">
                            <?php foreach ($calendar_days as $day) : ?>
                                <button type="button" class="goody-date-card<?php echo $day['disabled'] ? ' is-disabled' : ''; ?>" data-booking-day="<?php echo esc_attr((string) $day['id']); ?>" data-booking-date="<?php echo esc_attr((string) $day['date']); ?>" <?php echo $day['disabled'] ? 'disabled' : ''; ?>>
                                    <span class="goody-date-card__day"><?php echo esc_html($day['day']); ?></span>
                                    <strong class="goody-date-card__number"><?php echo esc_html($day['number']); ?></strong>
                                    <small class="goody-date-card__month"><?php echo esc_html($day['month']); ?></small>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <?php if (empty($config['dates'])) : ?>
                            <div class="goody-inline-empty"><?php esc_html_e('No booking dates are configured yet. Add them from Goody Green > Booking Dates.', 'goody'); ?></div>
                        <?php endif; ?>
                        <div class="goody-panel-actions">
                            <button type="button" class="button goody-step-next" data-next-step="<?php echo esc_attr((string) $step1_next); ?>"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel<?php echo ! $menu_step_enabled ? ' is-hidden' : ''; ?>" data-step-panel="2" <?php echo ! $menu_step_enabled ? 'hidden aria-hidden="true"' : ''; ?>>
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[2] ?? 2), $step_titles[2] ?? __('Menu', 'goody'))); ?></h3>
                        <?php echo $menu_markup; ?>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="1"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="3"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="3">
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[3] ?? 3), $step_titles[3] ?? __('Time', 'goody'))); ?></h3>
                        <div data-slot-results class="goody-slot-results">
                            <div class="goody-inline-empty"><?php echo esc_html($menu_step_enabled ? __('Select a date and menu items first to see the live time slots.', 'goody') : __('Select a date first to see the live time slots.', 'goody')); ?></div>
                        </div>
                        <div data-table-results class="goody-slot-results">
                            <div class="goody-inline-empty"><?php esc_html_e('Select a slot to see available tables and locations.', 'goody'); ?></div>
                        </div>
                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="<?php echo esc_attr((string) $step3_prev); ?>"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="4"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="4">
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[4] ?? 4), $step_titles[4] ?? __('Order Type', 'goody'))); ?></h3>
                        <div class="goody-choice-grid">
                            <?php foreach ($order_types as $type_key => $type_label) : ?>
                                <button type="button" class="goody-choice-card" data-order-type="<?php echo esc_attr($type_key); ?>"><?php echo goody_render_reservation_choice_label_with_icon($type_label, 'order_type', $type_key); ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="goody-order-notices">
                            <div class="goody-notice" data-order-warning="dine_in" hidden><?php echo esc_html($default_dine_in_note); ?></div>
                            <div class="goody-notice" data-order-warning="pickup" hidden><?php echo esc_html($default_pickup_warning); ?></div>
                            <div class="goody-notice" data-order-warning="delivery" hidden><?php echo esc_html($default_delivery_warning); ?></div>
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
                                <button type="button" class="goody-choice-card goody-choice-card--payment" data-payment-mode="<?php echo esc_attr($payment_key); ?>"><?php echo goody_render_reservation_choice_label_with_icon($payment_label, 'payment_mode', $payment_key); ?></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="goody-notice" data-payment-warning hidden><?php echo esc_html($default_cash_warning); ?></div>

                        <div class="goody-panel-actions">
                            <button type="button" class="button button--ghost goody-step-prev" data-prev-step="3"><?php echo esc_html($back_text); ?></button>
                            <button type="button" class="button goody-step-next" data-next-step="5"><?php echo esc_html($next_text); ?></button>
                        </div>
                    </section>

                    <section class="goody-reservation-panel" data-step-panel="5">
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[5] ?? 5), $step_titles[5] ?? __('Information', 'goody'))); ?></h3>
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
                                <span><?php esc_html_e('Email (optional)', 'goody'); ?></span>
                                <input type="email" inputmode="email" data-customer-field="email" placeholder="<?php esc_attr_e('you@example.com', 'goody'); ?>">
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
                        <h3><?php echo esc_html(sprintf('%s %d: %s', goody_get_reservation_step_counter_prefix(), (int) ($step_display_numbers[6] ?? 6), $step_titles[6] ?? __('Summary', 'goody'))); ?></h3>
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
                    <div class="goody-sidebar-card">
                                                <h4><?php esc_html_e('Booking Note', 'goody'); ?></h4>
                        <?php if ($day['note'] !== '') : ?>
                            <em class="goody-date-card__note"><?php echo esc_html($day['note']); ?></em>
                        <?php elseif ($day['disabled_reason'] !== '') : ?>
                            <em class="goody-date-card__note"><?php echo esc_html($day['disabled_reason']); ?></em>
                        <?php endif; ?>
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
                'id' => 'goody-menu-order-modal',
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
    $print_body_font = trim((string) goody_get_option('token_font_body', 'Arial, sans-serif'));
    $print_heading_font = trim((string) goody_get_option('token_font_heading', $print_body_font));
    ?>
    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8">
        <title><?php echo esc_html(goody_get_reservation_reference($reservation_id)); ?></title>
        <style>
            body { font-family: <?php echo esc_html($print_body_font); ?>; padding: 32px; color: #23170f; }
            .print-wrap { max-width: 840px; margin: 0 auto; }
            h1,h2,h3,h4,h5,h6 { font-family: <?php echo esc_html($print_heading_font); ?>; letter-spacing: -0.02em; }
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
