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

    $order_id = sanitize_text_field(wp_unslash($_POST['order_id'] ?? ''));
    $order_key = sanitize_text_field(wp_unslash($_POST['order_key'] ?? ''));
    $force_refresh = ($order_id !== '' || $order_key !== '');

    if (goody_get_option('tracking_enabled', '0') !== '1' && ! $force_refresh) {
        wp_send_json_success(goody_get_tracking_empty_state());
    }

    $state = goody_get_tracking_state($force_refresh, $order_id, $order_key);
    if (
        $force_refresh &&
        is_array($state) &&
        trim((string) ($state['order_id'] ?? '')) === '' &&
        trim((string) ($state['status'] ?? '')) === '' &&
        trim((string) ($state['message'] ?? '')) === ''
    ) {
        $state['order_id'] = sanitize_text_field($order_id);
        $state['order_key'] = sanitize_text_field($order_key);
        $state['message'] = __('Tracking data not found for this order. Please check Order ID / key.', 'goody');
    }
    wp_send_json_success($state);
}
add_action('wp_ajax_goody_tracking_status', 'goody_ajax_tracking_status');
add_action('wp_ajax_nopriv_goody_tracking_status', 'goody_ajax_tracking_status');

function goody_ajax_search() {
    check_ajax_referer('goody_nonce', 'nonce');

    $term = sanitize_text_field(wp_unslash($_POST['q'] ?? ''));
    $term = trim($term);

    if ($term === '' || strlen($term) < 2) {
        wp_send_json_success([
            'html' => '',
            'count' => 0,
        ]);
    }

    $query = new WP_Query([
        'post_type' => ['menu_item', 'offer', 'event', 'post', 'team_member'],
        'post_status' => 'publish',
        'posts_per_page' => 8,
        's' => $term,
        'ignore_sticky_posts' => true,
        'no_found_rows' => true,
    ]);

    ob_start();
    if ($query->have_posts()) {
        echo '<div class="goody-search-results-grid">';
        while ($query->have_posts()) {
            $query->the_post();

            $post_type = get_post_type() ?: 'post';
            $post_type_object = get_post_type_object($post_type);
            $post_type_label = $post_type_object && isset($post_type_object->labels->singular_name)
                ? (string) $post_type_object->labels->singular_name
                : ucfirst(str_replace('_', ' ', $post_type));

            $title = get_the_title();
            if ($title === '') {
                $title = __('Untitled', 'goody');
            }

            $excerpt = get_the_excerpt();
            if ($excerpt === '') {
                $excerpt = wp_trim_words(wp_strip_all_tags((string) get_the_content(null, false)), 16);
            }

            echo '<a class="goody-search-result" href="' . esc_url(get_permalink()) . '">';
            if (has_post_thumbnail()) {
                echo '<span class="goody-search-result__thumb">' . get_the_post_thumbnail(get_the_ID(), 'thumbnail', ['loading' => 'lazy', 'decoding' => 'async']) . '</span>';
            } else {
                echo '<span class="goody-search-result__thumb"><span class="goody-search-result__thumb-placeholder">' . esc_html(strtoupper(substr($post_type_label, 0, 1))) . '</span></span>';
            }

            echo '<span class="goody-search-result__body">';
            echo '<span class="goody-search-result__type">' . esc_html($post_type_label) . '</span>';
            echo '<strong class="goody-search-result__title">' . esc_html($title) . '</strong>';
            if ($excerpt !== '') {
                echo '<span class="goody-search-result__excerpt">' . esc_html($excerpt) . '</span>';
            }
            echo '</span>';
            echo '</a>';
        }
        echo '</div>';
        wp_reset_postdata();
    } else {
        echo '<div class="card empty-state"><h3>' . esc_html__('No results found', 'goody') . '</h3><p>' . esc_html__('Try a different keyword.', 'goody') . '</p></div>';
    }

    wp_send_json_success([
        'html' => (string) ob_get_clean(),
        'count' => (int) $query->post_count,
    ]);
}
add_action('wp_ajax_goody_search', 'goody_ajax_search');
add_action('wp_ajax_nopriv_goody_search', 'goody_ajax_search');

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
            'stage' => 'requested',
            'status' => 'Requested',
            'description' => 'Order request has been received.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (3 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'confirmed',
            'status' => 'Confirmed',
            'description' => 'Order has been confirmed.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (7 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'preparing',
            'status' => 'Preparing',
            'description' => 'Order is being prepared.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (11 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
        [
            'stage' => 'ready',
            'status' => 'Ready',
            'description' => 'Order is ready.',
            'time' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $base_timestamp + (14 * HOUR_IN_SECONDS)),
            'completed' => true,
        ],
    ];

    $index = ((int) gmdate('i')) % count($timeline_all);
    $timeline = array_slice($timeline_all, 0, $index + 1);
    $current = $timeline[$index];

    $eta_map = [
        'requested' => '30-35 min',
        'confirmed' => '20-25 min',
        'preparing' => '10-15 min',
        'ready' => 'Ready',
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
