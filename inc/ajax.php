<?php

function goody_render_menu_items_markup($query) {
    ob_start();

    if ($query->have_posts()) {
        echo '<div class="archive-grid archive-grid--three">';
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/cards/content', 'menu_item');
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<div class="card empty-state"><h3>' . esc_html__('No menu items found', 'goody') . '</h3><p>' . esc_html__('Try changing filters or add more dishes from Dashboard → Menu Items.', 'goody') . '</p></div>';
    }

    return ob_get_clean();
}

function goody_ajax_filter_menu_items() {
    check_ajax_referer('goody_nonce', 'nonce');

    $filters = [
        'category' => sanitize_text_field(wp_unslash($_POST['category'] ?? '')),
        'dietary' => sanitize_text_field(wp_unslash($_POST['dietary'] ?? '')),
        'meal_type' => sanitize_text_field(wp_unslash($_POST['meal_type'] ?? '')),
        'offer' => sanitize_text_field(wp_unslash($_POST['offer'] ?? '')),
        'new_only' => sanitize_text_field(wp_unslash($_POST['new_only'] ?? '0')),
        'q' => sanitize_text_field(wp_unslash($_POST['q'] ?? '')),
    ];

    $query = new WP_Query(goody_build_menu_query_args($filters));
    $html = goody_render_menu_items_markup($query);

    wp_send_json_success([
        'html' => $html,
        'found_posts' => (int) $query->found_posts,
    ]);
}
add_action('wp_ajax_goody_filter_menu', 'goody_ajax_filter_menu_items');
add_action('wp_ajax_nopriv_goody_filter_menu', 'goody_ajax_filter_menu_items');

function goody_ajax_tracking_status() {
    check_ajax_referer('goody_nonce', 'nonce');

    if (goody_get_option('tracking_enabled', '0') !== '1') {
        wp_send_json_success(goody_get_tracking_empty_state());
    }

    $order_id = sanitize_text_field(wp_unslash($_POST['order_id'] ?? ''));
    $order_key = sanitize_text_field(wp_unslash($_POST['order_key'] ?? ''));
    $force_refresh = ($order_id !== '' || $order_key !== '');
    $state = goody_get_tracking_state($force_refresh, $order_id, $order_key);
    wp_send_json_success($state);
}
add_action('wp_ajax_goody_tracking_status', 'goody_ajax_tracking_status');
add_action('wp_ajax_nopriv_goody_tracking_status', 'goody_ajax_tracking_status');

function goody_rest_test_delivery(WP_REST_Request $request) {
    $provider = sanitize_key((string) $request->get_param('provider'));
    if (! in_array($provider, ['glovo', 'ubereats', 'deliveroo', 'custom'], true)) {
        $provider = 'custom';
    }

    $order_id = sanitize_text_field((string) $request->get_param('order_id'));
    if ($order_id === '') {
        $order_id = 'GG-1001';
    }

    $order_key = sanitize_text_field((string) $request->get_param('order_key'));
    if ($order_key === '' && ctype_digit((string) $order_id) && function_exists('wc_get_order')) {
        $wc_order = wc_get_order((int) $order_id);
        if ($wc_order instanceof WC_Order) {
            $order_key = (string) $wc_order->get_order_key();
        }
    }
    if ($order_key === '') {
        $order_key = 'goody-' . strtolower(substr(preg_replace('/[^a-zA-Z0-9]+/', '', (string) $order_id), 0, 18));
    }

    $external_order_id = strtoupper(substr($provider, 0, 2)) . '-' . preg_replace('/[^A-Za-z0-9]+/', '', (string) $order_id) . '-' . (string) gmdate('i');
    $order_url = add_query_arg([
        'provider' => $provider,
    ], goody_get_tracking_page_url($order_id, $order_key));
    $tracking_api_url = add_query_arg([
        'order_id' => $order_id,
        'key' => $order_key,
        'provider' => $provider,
        'external_order_id' => $external_order_id,
    ], rest_url('goody/v1/test/tracking'));

    return rest_ensure_response([
        'provider' => $provider,
        'external_order_id' => $external_order_id,
        'order_url' => $order_url,
        'tracking_api_url' => $tracking_api_url,
        'status' => 'ok',
        'data' => [
            'order' => [
                'id' => $external_order_id,
            ],
            'tracking' => [
                'url' => $order_url,
                'api_url' => $tracking_api_url,
            ],
            'order_url' => $order_url,
            'provider' => $provider,
        ],
    ]);
}

function goody_rest_test_tracking(WP_REST_Request $request) {
    $order_id = sanitize_text_field((string) $request->get_param('order_id'));
    if ($order_id === '') {
        $order_id = 'GG-1001';
    }

    $provider = sanitize_key((string) $request->get_param('provider'));
    if ($provider === '') {
        $provider = 'goody-test';
    }

    $order_key = sanitize_text_field((string) $request->get_param('key'));
    if ($order_key === '') {
        $order_key = 'test-' . strtolower(preg_replace('/[^a-zA-Z0-9]+/', '', $order_id));
    }

    $consignment_id = sanitize_text_field((string) $request->get_param('external_order_id'));
    if ($consignment_id === '') {
        $consignment_id = 'DH' . strtoupper(substr(md5($order_id), 0, 12));
    }
    $base_timestamp = strtotime('-1 day');
    if ($base_timestamp === false) {
        $base_timestamp = time() - DAY_IN_SECONDS;
    }

    $timeline_all = [
        [
            'stage' => 'accepted',
            'status' => 'Accepted',
            'description' => 'New order pickup requested.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (2 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'picked',
            'status' => 'Picked',
            'description' => 'Order assigned to rider for pickup.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (5 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'in_transit',
            'status' => 'In Transit',
            'description' => 'Order is on the way to delivery hub.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (8 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'ready_for_delivery',
            'status' => 'Ready For Delivery',
            'description' => 'Order has reached local delivery hub.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (11 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'delivered',
            'status' => 'Delivered',
            'description' => 'Order has been delivered to customer.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (14 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
    ];

    $index = ((int) gmdate('i')) % count($timeline_all);
    $timeline = array_slice($timeline_all, 0, $index + 1);
    $current = $timeline[$index];

    $eta_map = [
        'accepted' => '35-40 min',
        'picked' => '20-25 min',
        'in_transit' => '10-15 min',
        'ready_for_delivery' => '5-8 min',
        'delivered' => 'Completed',
    ];

    $tracking_url = goody_get_tracking_page_url($order_id, $order_key);
    $eta = $eta_map[$current['stage']] ?? '';

    return rest_ensure_response([
        'provider' => $provider,
        'tracking_url' => $tracking_url,
        'tracking_status' => $current['status'],
        'stage' => $current['stage'],
        'eta' => $eta,
        'order_id' => $order_id,
        'consignment_id' => $consignment_id,
        'shipping_name' => 'Saju Sheikh',
        'shipping_phone' => '01982228979',
        'shipping_address' => 'Chalna Bazar, Khulna 4200',
        'payment_amount' => 'BDT 1800',
        'payment_method' => 'Cash Payment',
        'timeline' => $timeline,
        'data' => [
            'tracking_url' => $tracking_url,
            'status' => $current['status'],
            'stage' => $current['stage'],
            'eta' => $eta,
            'order_id' => $order_id,
            'consignment_id' => $consignment_id,
            'shipping_name' => 'Saju Sheikh',
            'shipping_phone' => '01982228979',
            'shipping_address' => 'Chalna Bazar, Khulna 4200',
            'payment_amount' => 'BDT 1800',
            'payment_method' => 'Cash Payment',
            'timeline' => $timeline,
        ],
    ]);
}

function goody_rest_delivery_webhook(WP_REST_Request $request) {
    if (! goody_is_woocommerce_available()) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'WooCommerce is not active.',
        ], 400);
    }

    $secret = goody_normalize_api_token(goody_get_option('delivery_webhook_secret', ''));
    if ($secret !== '') {
        $provided_secret = sanitize_text_field((string) (
            $request->get_header('x-goody-webhook-secret') ?:
            $request->get_header('x-webhook-secret') ?:
            $request->get_param('secret')
        ));

        if ($provided_secret === '') {
            $authorization = sanitize_text_field((string) $request->get_header('authorization'));
            if (stripos($authorization, 'Bearer ') === 0) {
                $provided_secret = trim((string) substr($authorization, 7));
            }
        }

        if ($provided_secret === '' || ! hash_equals($secret, $provided_secret)) {
            return new WP_REST_Response([
                'status' => 'error',
                'message' => 'Invalid webhook secret.',
            ], 401);
        }
    }

    $payload = $request->get_json_params();
    if (! is_array($payload) || empty($payload)) {
        $payload = $request->get_params();
    }
    if (! is_array($payload) || empty($payload)) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Invalid webhook payload.',
        ], 400);
    }

    $order = goody_find_woocommerce_order_for_delivery_payload($payload);
    if (! $order instanceof WC_Order) {
        return new WP_REST_Response([
            'status' => 'error',
            'message' => 'Order not found.',
        ], 404);
    }

    $provider = goody_reviews_pick_value($payload, ['provider', 'source', 'data.provider', 'data.source']);
    if (! is_scalar($provider)) {
        $provider = '';
    }
    $provider = sanitize_key((string) $provider);

    $fallback_url = goody_normalize_url_input((string) $order->get_meta('_goody_tracking_api_url', true));
    $state = goody_parse_tracking_payload($payload, $fallback_url, (string) $provider);
    $external_order_id = goody_extract_external_order_id_from_payload($payload, (string) $provider);
    if ($external_order_id === '') {
        $external_order_id = sanitize_text_field((string) $order->get_meta('_goody_external_order_id', true));
    }

    if ($provider !== '') {
        $state['provider'] = sanitize_text_field((string) $provider);
    } else {
        $provider_text = goody_reviews_pick_value($payload, ['provider', 'source', 'data.provider', 'data.source']);
        if (is_scalar($provider_text)) {
            $provider_text = sanitize_text_field((string) $provider_text);
            if ($provider_text !== '') {
                $state['provider'] = $provider_text;
            }
        }
    }

    goody_sync_tracking_state_to_order_meta($order, $state, $external_order_id);

    return rest_ensure_response([
        'status' => 'ok',
        'order_id' => (int) $order->get_id(),
        'external_order_id' => sanitize_text_field((string) $external_order_id),
        'tracking_status' => sanitize_text_field((string) ($state['status'] ?? '')),
        'stage' => sanitize_text_field((string) ($state['stage'] ?? '')),
    ]);
}

function goody_register_test_api_routes() {
    register_rest_route('goody/v1', '/test/delivery/(?P<provider>[a-z0-9_-]+)', [
        'methods' => 'GET, POST',
        'callback' => 'goody_rest_test_delivery',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('goody/v1', '/test/tracking', [
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'goody_rest_test_tracking',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('goody/v1', '/delivery/webhook', [
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'goody_rest_delivery_webhook',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'goody_register_test_api_routes');
