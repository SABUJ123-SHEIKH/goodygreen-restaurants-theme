<?php

function goody_reservation_post_created_sync_record($reservation_id) {
    if (function_exists('goody_upsert_reservation_record')) {
        goody_upsert_reservation_record($reservation_id);
    }
}

function goody_sync_reservation_table_after_woocommerce_status($order_id, $old_status, $new_status, $order) {
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

    if (in_array($new_status, ['processing', 'completed'], true)) {
        update_post_meta($reservation_id, 'goody_reservation_status', 'confirmed');
    } elseif (in_array($new_status, ['cancelled', 'failed', 'refunded'], true)) {
        update_post_meta($reservation_id, 'goody_reservation_status', 'cancelled');
    }

    if (function_exists('goody_upsert_reservation_record')) {
        goody_upsert_reservation_record($reservation_id);
    }
}
add_action('woocommerce_order_status_changed', 'goody_sync_reservation_table_after_woocommerce_status', 35, 4);

function goody_add_reservation_meta_to_woocommerce_email($order, $sent_to_admin, $plain_text, $email) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
    if ($reservation_id < 1) {
        return;
    }

    $reference = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_code', true));
    $date = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_date', true));
    $slot = sanitize_text_field((string) get_post_meta($reservation_id, 'goody_reservation_slot_label', true));
    $order_type = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_order_type', true));
    $types = goody_get_reservation_order_types();

    if ($plain_text) {
        echo "\n" . __('Reservation Reference:', 'goody') . ' ' . $reference . "\n";
        echo __('Reservation Time:', 'goody') . ' ' . $date . ' ' . $slot . "\n";
        echo __('Order Type:', 'goody') . ' ' . ($types[$order_type] ?? $order_type) . "\n";
        return;
    }

    echo '<h2>' . esc_html__('Reservation Details', 'goody') . '</h2>';
    echo '<p><strong>' . esc_html__('Reference:', 'goody') . '</strong> ' . esc_html($reference) . '</p>';
    echo '<p><strong>' . esc_html__('Reservation Time:', 'goody') . '</strong> ' . esc_html(trim($date . ' ' . $slot)) . '</p>';
    echo '<p><strong>' . esc_html__('Order Type:', 'goody') . '</strong> ' . esc_html($types[$order_type] ?? $order_type) . '</p>';
}
add_action('woocommerce_email_order_meta', 'goody_add_reservation_meta_to_woocommerce_email', 20, 4);

function goody_can_manage_order_tracking() {
    // Always allow administrators/site managers to avoid lockout by misconfiguration.
    if (current_user_can('manage_options') || current_user_can('manage_woocommerce')) {
        return true;
    }

    $configured_roles_raw = (string) goody_get_option('tracking_update_roles', '');
    $configured_roles = array_values(array_filter(array_map('sanitize_key', explode(',', $configured_roles_raw))));
    if (! empty($configured_roles)) {
        $user = wp_get_current_user();
        if (! $user instanceof WP_User || empty($user->roles)) {
            return false;
        }
        foreach ((array) $user->roles as $user_role) {
            if (in_array(sanitize_key((string) $user_role), $configured_roles, true)) {
                return true;
            }
        }
        return false;
    }

    if (current_user_can('edit_shop_orders')) {
        return true;
    }

    $user = wp_get_current_user();
    if ($user instanceof WP_User && in_array('shop_worker', (array) $user->roles, true)) {
        return true;
    }

    return false;
}

function goody_append_tracking_timeline_event($order, $stage, $status, $note = '') {
    if (! $order instanceof WC_Order) {
        return;
    }

    $stage = goody_normalize_tracking_stage($stage);
    if ($stage === '') {
        return;
    }

    $stage_labels = goody_get_tracking_stage_definitions();
    $event_title = sanitize_text_field((string) $status);
    if ($event_title === '' && isset($stage_labels[$stage])) {
        $event_title = (string) $stage_labels[$stage];
    }

    $note = sanitize_text_field((string) $note);
    $current_user = wp_get_current_user();
    $actor = $current_user instanceof WP_User ? sanitize_text_field((string) $current_user->display_name) : '';
    $description = $note;
    if ($description === '' && $actor !== '') {
        $description = sprintf(__('Updated by %s', 'goody'), $actor);
    } elseif ($description !== '' && $actor !== '') {
        $description .= ' ' . sprintf(__('(Updated by %s)', 'goody'), $actor);
    }

    $timeline = json_decode((string) $order->get_meta('_goody_tracking_timeline', true), true);
    if (! is_array($timeline)) {
        $timeline = [];
    }
    $timeline = goody_normalize_tracking_timeline($timeline);

    $timeline[] = [
        'stage' => $stage,
        'title' => $event_title,
        'description' => $description,
        'time' => goody_format_tracking_datetime(time()),
        'completed' => true,
    ];

    $order->update_meta_data('_goody_tracking_timeline', wp_json_encode($timeline));
}

function goody_render_order_tracking_admin_panel($order) {
    if (! $order instanceof WC_Order || ! goody_can_manage_order_tracking()) {
        return;
    }

    $current_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
    if ($current_stage === '') {
        $current_stage = goody_normalize_tracking_stage((string) $order->get_status());
    }
    $current_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));
    $current_eta = sanitize_text_field((string) $order->get_meta('_goody_tracking_eta', true));
    $manual_mode = (string) $order->get_meta('_goody_tracking_manual_updates', true) === '1';
    $stages = goody_get_tracking_stage_definitions();
    ?>
    <div class="order_data_column" style="width:100%;padding-top:12px;">
        <h3><?php esc_html_e('Order Tracking Updates', 'goody'); ?></h3>
        <p style="margin:0 0 10px;"><?php esc_html_e('Admin and shop workers can update the live customer tracking stage from here.', 'goody'); ?></p>
        <?php wp_nonce_field('goody_update_order_tracking', 'goody_order_tracking_nonce'); ?>
        <p class="form-field">
            <label for="goody_tracking_stage"><?php esc_html_e('Tracking Stage', 'goody'); ?></label>
            <select id="goody_tracking_stage" name="goody_tracking_stage" style="width:100%;">
                <?php foreach ($stages as $stage_key => $stage_label) : ?>
                    <option value="<?php echo esc_attr($stage_key); ?>" <?php selected($current_stage, $stage_key); ?>><?php echo esc_html($stage_label); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p class="form-field">
            <label for="goody_tracking_status"><?php esc_html_e('Status Label', 'goody'); ?></label>
            <input type="text" id="goody_tracking_status" name="goody_tracking_status" value="<?php echo esc_attr($current_status); ?>" placeholder="<?php esc_attr_e('Ready for Delivery', 'goody'); ?>" style="width:100%;">
        </p>
        <p class="form-field">
            <label for="goody_tracking_eta"><?php esc_html_e('ETA (optional)', 'goody'); ?></label>
            <input type="text" id="goody_tracking_eta" name="goody_tracking_eta" value="<?php echo esc_attr($current_eta); ?>" placeholder="<?php esc_attr_e('15-20 min', 'goody'); ?>" style="width:100%;">
        </p>
        <p class="form-field">
            <label for="goody_tracking_note"><?php esc_html_e('Update Note (optional)', 'goody'); ?></label>
            <textarea id="goody_tracking_note" name="goody_tracking_note" rows="2" style="width:100%;"></textarea>
        </p>
        <p class="form-field">
            <label>
                <input type="checkbox" name="goody_tracking_manual_updates" value="1" <?php checked($manual_mode); ?>>
                <?php esc_html_e('Manual tracking mode (do not auto-overwrite from provider sync)', 'goody'); ?>
            </label>
        </p>
    </div>
    <?php
}
add_action('woocommerce_admin_order_data_after_order_details', 'goody_render_order_tracking_admin_panel', 35);

function goody_save_order_tracking_admin_panel($order_id, $order) {
    if (! $order instanceof WC_Order || ! goody_can_manage_order_tracking()) {
        return;
    }

    $nonce = sanitize_text_field((string) wp_unslash($_POST['goody_order_tracking_nonce'] ?? ''));
    if ($nonce === '' || ! wp_verify_nonce($nonce, 'goody_update_order_tracking')) {
        return;
    }

    $stage = goody_normalize_tracking_stage((string) wp_unslash($_POST['goody_tracking_stage'] ?? ''));
    if ($stage === '') {
        return;
    }

    $stages = goody_get_tracking_stage_definitions();
    $status = isset($stages[$stage]) ? (string) $stages[$stage] : sanitize_text_field((string) wp_unslash($_POST['goody_tracking_status'] ?? ''));
    $eta = sanitize_text_field((string) wp_unslash($_POST['goody_tracking_eta'] ?? ''));
    $note = sanitize_text_field((string) wp_unslash($_POST['goody_tracking_note'] ?? ''));
    $manual_mode = isset($_POST['goody_tracking_manual_updates']) ? '1' : '0';

    $previous_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
    $previous_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));
    $previous_eta = sanitize_text_field((string) $order->get_meta('_goody_tracking_eta', true));

    $order->update_meta_data('_goody_tracking_stage', $stage);
    $order->update_meta_data('_goody_tracking_status', $status);
    $order->update_meta_data('_goody_tracking_eta', $eta);
    $order->update_meta_data('_goody_tracking_note', $note);
    $order->update_meta_data('_goody_tracking_manual_updates', $manual_mode);

    if ($stage !== $previous_stage || $status !== $previous_status || $eta !== $previous_eta || $note !== '') {
        goody_append_tracking_timeline_event($order, $stage, $status, $note);
    }

    $order->save();
}
add_action('woocommerce_process_shop_order_meta', 'goody_save_order_tracking_admin_panel', 35, 2);

function goody_register_order_tracking_account_endpoint() {
    if (! function_exists('add_rewrite_endpoint')) {
        return;
    }

    add_rewrite_endpoint('order-tracking', EP_ROOT | EP_PAGES);
}
add_action('init', 'goody_register_order_tracking_account_endpoint');

function goody_maybe_flush_order_tracking_endpoint_rules() {
    if (! function_exists('flush_rewrite_rules')) {
        return;
    }

    $flag_key = 'goody_order_tracking_endpoint_rules_flushed';
    if (get_option($flag_key) === '1') {
        return;
    }

    flush_rewrite_rules(false);
    update_option($flag_key, '1', false);
}
add_action('init', 'goody_maybe_flush_order_tracking_endpoint_rules', 30);

function goody_get_tracking_public_status_page_url() {
    if (function_exists('goody_get_reservation_status_lookup_page_url')) {
        $url = goody_get_reservation_status_lookup_page_url();
        if (is_string($url) && trim($url) !== '') {
            return $url;
        }
    }

    $configured = goody_normalize_url_input((string) goody_get_option('reservation_status_page_url', ''));
    if ($configured !== '') {
        return $configured;
    }

    return home_url('/order-status/');
}

function goody_add_order_tracking_to_account_menu($items) {
    if (! is_array($items)) {
        return $items;
    }

    if (! goody_can_manage_order_tracking()) {
        return $items;
    }

    $updated = [];
    foreach ($items as $key => $label) {
        $updated[$key] = $label;
        if ($key === 'orders') {
            $updated['order-tracking'] = __('Reservation & Order Tracking', 'goody');
        }
    }

    if (! isset($updated['order-tracking'])) {
        $updated['order-tracking'] = __('Reservation & Order Tracking', 'goody');
    }

    return $updated;
}
add_filter('woocommerce_account_menu_items', 'goody_add_order_tracking_to_account_menu', 30);

function goody_render_account_tracking_status_button() {
    if (! is_user_logged_in() || goody_can_manage_order_tracking()) {
        return;
    }

    $status_url = goody_get_tracking_public_status_page_url();
    if (! is_string($status_url) || trim($status_url) === '') {
        return;
    }

    echo '<p><a class="button" href="' . esc_url($status_url) . '">' . esc_html__('Track Reservation / Order Status', 'goody') . '</a></p>';
}
add_action('woocommerce_account_dashboard', 'goody_render_account_tracking_status_button', 40);

function goody_add_order_tracking_query_var($vars) {
    $vars[] = 'order-tracking';
    return $vars;
}
add_filter('query_vars', 'goody_add_order_tracking_query_var');

function goody_render_order_tracking_account_endpoint() {
    if (! is_user_logged_in() || ! function_exists('wc_get_orders')) {
        echo '<p>' . esc_html__('Please log in to track your orders.', 'goody') . '</p>';
        return;
    }

    if (! goody_can_manage_order_tracking()) {
        $status_url = goody_get_tracking_public_status_page_url();
        echo '<div class="goody-inline-empty">';
        echo '<p>' . esc_html__('You do not have permission to open this tracking panel.', 'goody') . '</p>';
        echo '<p><a class="button" href="' . esc_url($status_url) . '">' . esc_html__('Go to Status Page', 'goody') . '</a></p>';
        echo '</div>';
        return;
    }

    $customer_id = get_current_user_id();
    $can_manage_all_orders = true;
    $order_query = [
        'limit' => $can_manage_all_orders ? 50 : 12,
        'orderby' => 'date',
        'order' => 'DESC',
        'status' => array_keys(wc_get_order_statuses()),
    ];
    if (! $can_manage_all_orders) {
        $order_query['customer_id'] = $customer_id;
    }
    $orders = wc_get_orders($order_query);

    $selected_order = null;
    $selected_order_id = absint(wp_unslash($_GET['track_order_id'] ?? 0));
    if ($selected_order_id > 0) {
        $candidate = wc_get_order($selected_order_id);
        if (
            $candidate instanceof WC_Order &&
            ($can_manage_all_orders || (int) $candidate->get_customer_id() === (int) $customer_id)
        ) {
            $selected_order = $candidate;
        }
    }
    if (! $selected_order instanceof WC_Order && ! empty($orders) && $orders[0] instanceof WC_Order) {
        $selected_order = $orders[0];
    }

    echo '<div class="goody-account-tracking">';
    echo '<div class="goody-account-tracking__head">';
    echo '<h3>' . esc_html__('Reservation & Order Tracking', 'goody') . '</h3>';
    echo '<p>' . esc_html__('Track orders and linked reservation progress with live timeline updates.', 'goody') . '</p>';
    if ($can_manage_all_orders) {
        echo '<p>' . esc_html__('Manager mode: you can load and update tracking for all orders.', 'goody') . '</p>';
    }
    echo '</div>';

    if (! empty($orders)) {
        echo '<form class="goody-account-tracking__picker" method="get">';
        foreach ($_GET as $key => $value) {
            if (! is_scalar($value)) {
                continue;
            }
            $key = sanitize_key((string) $key);
            if ($key === 'track_order_id') {
                continue;
            }
            echo '<input type="hidden" name="' . esc_attr($key) . '" value="' . esc_attr((string) $value) . '">';
        }
        echo '<label for="goody-account-track-order">' . esc_html__('Select Order', 'goody') . '</label>';
        echo '<select id="goody-account-track-order" name="track_order_id">';
        foreach ($orders as $order) {
            if (! $order instanceof WC_Order) {
                continue;
            }
            $oid = (int) $order->get_id();
            $label = sprintf('#%d - %s', $oid, goody_format_tracking_datetime($order->get_date_created()));
            $tracking_type_label = __('Order Tracking', 'goody');
            $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
            if ($reservation_id > 0) {
                $tracking_type_label = __('Reservation Tracking', 'goody');
            }
            if ($can_manage_all_orders) {
                $customer_name = trim((string) $order->get_formatted_billing_full_name());
                if ($customer_name === '') {
                    $customer_name = sanitize_text_field((string) $order->get_billing_email());
                }
                if ($customer_name !== '') {
                    $label .= ' - ' . $customer_name;
                }
            }
            $label .= ' - ' . $tracking_type_label;
            echo '<option value="' . esc_attr((string) $oid) . '" ' . selected($selected_order instanceof WC_Order ? (int) $selected_order->get_id() : 0, $oid, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
        echo '<button class="button" type="submit">' . esc_html__('Load Tracking', 'goody') . '</button>';
        echo '</form>';
    }

    if ($selected_order instanceof WC_Order) {
        if ($can_manage_all_orders) {
            $current_stage = goody_normalize_tracking_stage((string) $selected_order->get_meta('_goody_tracking_stage', true));
            if ($current_stage === '') {
                $current_stage = goody_normalize_tracking_stage((string) $selected_order->get_status());
            }
            $current_status = sanitize_text_field((string) $selected_order->get_meta('_goody_tracking_status', true));
            $current_provider = sanitize_text_field((string) ($selected_order->get_meta('_goody_delivery_provider', true) ?: $selected_order->get_meta('delivery_provider', true)));
            $current_eta = sanitize_text_field((string) $selected_order->get_meta('_goody_tracking_eta', true));
            $current_note = sanitize_text_field((string) $selected_order->get_meta('_goody_tracking_note', true));
            $stages = goody_get_tracking_stage_definitions();

            echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" style="margin:16px 0;padding:12px;border:1px solid #dcdcde;border-radius:8px;">';
            wp_nonce_field('goody_account_update_order_tracking', 'goody_account_tracking_nonce');
            echo '<input type="hidden" name="action" value="goody_account_update_order_tracking">';
            echo '<input type="hidden" name="order_id" value="' . esc_attr((string) $selected_order->get_id()) . '">';
            echo '<input type="hidden" name="redirect_to" value="' . esc_url(add_query_arg('track_order_id', (string) $selected_order->get_id(), wc_get_account_endpoint_url('order-tracking'))) . '">';
            echo '<p style="margin:0 0 10px;"><strong>' . esc_html__('Update Order Tracking', 'goody') . '</strong></p>';
            echo '<label for="goody-account-tracking-stage"><strong>' . esc_html__('Tracking Stage', 'goody') . '</strong></label><br>';
            echo '<select id="goody-account-tracking-stage" name="tracking_stage">';
            foreach ($stages as $stage_key => $stage_label) {
                echo '<option value="' . esc_attr($stage_key) . '" ' . selected($current_stage, $stage_key, false) . '>' . esc_html($stage_label) . '</option>';
            }
            echo '</select><br><br>';
            echo '<label for="goody-account-tracking-provider"><strong>' . esc_html__('Delivery Provider Name', 'goody') . '</strong></label><br>';
            echo '<input type="text" id="goody-account-tracking-provider" name="tracking_provider" value="' . esc_attr($current_provider) . '" style="width:100%;"><br><br>';
            echo '<label for="goody-account-tracking-eta"><strong>' . esc_html__('ETA', 'goody') . '</strong></label><br>';
            echo '<input type="text" id="goody-account-tracking-eta" name="tracking_eta" value="' . esc_attr($current_eta) . '" style="width:100%;"><br><br>';
            echo '<label for="goody-account-tracking-note"><strong>' . esc_html__('Note', 'goody') . '</strong></label><br>';
            echo '<input type="text" id="goody-account-tracking-note" name="tracking_note" value="' . esc_attr($current_note) . '" style="width:100%;"><br><br>';
            echo '<button class="button" type="submit">' . esc_html__('Update Tracking', 'goody') . '</button>';
            echo '</form>';
        }
        echo goody_render_order_tracking_status_panel((string) $selected_order->get_id(), (string) $selected_order->get_order_key());
    } else {
        echo '<div class="goody-inline-empty">' . esc_html__('No orders found for your account yet.', 'goody') . '</div>';
    }

    echo '</div>';
}
add_action('woocommerce_account_order-tracking_endpoint', 'goody_render_order_tracking_account_endpoint');

function goody_handle_account_order_tracking_update() {
    if (! is_user_logged_in() || ! goody_can_manage_order_tracking() || ! function_exists('wc_get_order')) {
        wp_safe_redirect(wc_get_account_endpoint_url('order-tracking'));
        exit;
    }

    $nonce = sanitize_text_field((string) wp_unslash($_POST['goody_account_tracking_nonce'] ?? ''));
    if ($nonce === '' || ! wp_verify_nonce($nonce, 'goody_account_update_order_tracking')) {
        wp_safe_redirect(wc_get_account_endpoint_url('order-tracking'));
        exit;
    }

    $order_id = absint($_POST['order_id'] ?? 0);
    $order = wc_get_order($order_id);
    if (! $order instanceof WC_Order) {
        wp_safe_redirect(wc_get_account_endpoint_url('order-tracking'));
        exit;
    }

    $stage = goody_normalize_tracking_stage((string) wp_unslash($_POST['tracking_stage'] ?? ''));
    if ($stage === '') {
        wp_safe_redirect(wc_get_account_endpoint_url('order-tracking'));
        exit;
    }

    $stages = goody_get_tracking_stage_definitions();
    $status = isset($stages[$stage]) ? (string) $stages[$stage] : $stage;
    $provider = sanitize_text_field((string) wp_unslash($_POST['tracking_provider'] ?? ''));
    $eta = sanitize_text_field((string) wp_unslash($_POST['tracking_eta'] ?? ''));
    $note = sanitize_text_field((string) wp_unslash($_POST['tracking_note'] ?? ''));

    $order->update_meta_data('_goody_tracking_stage', $stage);
    $order->update_meta_data('_goody_tracking_status', $status);
    $order->update_meta_data('_goody_tracking_eta', $eta);
    $order->update_meta_data('_goody_tracking_note', $note);
    $order->update_meta_data('_goody_tracking_manual_updates', '1');
    if ($provider !== '') {
        $order->update_meta_data('_goody_delivery_provider', $provider);
        $order->update_meta_data('delivery_provider', $provider);
    }

    $timeline_note = $note;
    if ($provider !== '') {
        $timeline_note = trim(($timeline_note !== '' ? $timeline_note . ' | ' : '') . sprintf(__('Provider: %s', 'goody'), $provider));
    }
    goody_append_tracking_timeline_event($order, $stage, $status, $timeline_note);
    $order->save();

    // Keep linked reservation timeline/status in sync when shop worker updates tracking.
    $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
    if ($reservation_id < 1) {
        $reservation_id = absint(get_post_meta((int) $order->get_id(), '_goody_reservation_id', true));
    }
    if ($reservation_id > 0 && function_exists('goody_sync_order_tracking_from_reservation')) {
        $stage_to_reservation_status = [
            'requested' => 'pending-payment',
            'confirmed' => 'confirmed',
            'preparing' => 'preparing',
            'ready' => 'ready',
            'with_delivery_provider' => 'ready',
            'completed' => 'completed',
        ];
        $reservation_status = isset($stage_to_reservation_status[$stage]) ? $stage_to_reservation_status[$stage] : '';
        if ($reservation_status !== '' && isset(goody_get_reservation_statuses()[$reservation_status])) {
            update_post_meta($reservation_id, 'goody_reservation_status', $reservation_status);
            if (function_exists('goody_upsert_reservation_record')) {
                goody_upsert_reservation_record($reservation_id);
            }
            goody_sync_order_tracking_from_reservation($reservation_id);
        }
    }

    $redirect_to = esc_url_raw((string) wp_unslash($_POST['redirect_to'] ?? ''));
    if ($redirect_to === '') {
        $redirect_to = add_query_arg('track_order_id', (string) $order_id, wc_get_account_endpoint_url('order-tracking'));
    }
    wp_safe_redirect($redirect_to);
    exit;
}
add_action('admin_post_goody_account_update_order_tracking', 'goody_handle_account_order_tracking_update');

function goody_register_order_tracking_admin_menu() {
    add_submenu_page(
        'goody-theme',
        __('Order Tracking', 'goody'),
        __('Order Tracking', 'goody'),
        'edit_shop_orders',
        'goody-order-tracking-admin',
        'goody_render_order_tracking_admin_page'
    );

    add_submenu_page(
        'woocommerce',
        __('Order Tracking', 'goody'),
        __('Order Tracking', 'goody'),
        'edit_shop_orders',
        'goody-order-tracking-admin',
        'goody_render_order_tracking_admin_page'
    );
}
add_action('admin_menu', 'goody_register_order_tracking_admin_menu');

function goody_handle_order_tracking_admin_actions() {
    if (! is_admin() || ! goody_can_manage_order_tracking()) {
        return;
    }

    $page = sanitize_text_field((string) wp_unslash($_GET['page'] ?? ''));
    if ($page !== 'goody-order-tracking-admin') {
        return;
    }

    if (! isset($_POST['goody_order_tracking_admin_nonce'], $_POST['order_id'], $_POST['tracking_stage'])) {
        return;
    }

    $nonce = sanitize_text_field((string) wp_unslash($_POST['goody_order_tracking_admin_nonce']));
    if (! wp_verify_nonce($nonce, 'goody_update_order_tracking_admin')) {
        return;
    }

    if (! function_exists('wc_get_order')) {
        return;
    }

    $order_id = absint($_POST['order_id']);
    $order = wc_get_order($order_id);
    if (! $order instanceof WC_Order) {
        return;
    }

    $stage = goody_normalize_tracking_stage((string) wp_unslash($_POST['tracking_stage']));
    if ($stage === '') {
        return;
    }

    $stages = goody_get_tracking_stage_definitions();
    $status = isset($stages[$stage]) ? (string) $stages[$stage] : sanitize_text_field((string) wp_unslash($_POST['tracking_status'] ?? ''));
    $provider = sanitize_text_field((string) wp_unslash($_POST['tracking_provider'] ?? ''));
    $eta = sanitize_text_field((string) wp_unslash($_POST['tracking_eta'] ?? ''));
    $note = sanitize_text_field((string) wp_unslash($_POST['tracking_note'] ?? ''));

    $previous_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
    $previous_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));
    $previous_provider = sanitize_text_field((string) ($order->get_meta('_goody_delivery_provider', true) ?: $order->get_meta('delivery_provider', true)));
    $previous_eta = sanitize_text_field((string) $order->get_meta('_goody_tracking_eta', true));

    $order->update_meta_data('_goody_tracking_stage', $stage);
    $order->update_meta_data('_goody_tracking_status', $status);
    $order->update_meta_data('_goody_tracking_eta', $eta);
    $order->update_meta_data('_goody_tracking_note', $note);
    $order->update_meta_data('_goody_tracking_manual_updates', '1');

    if ($provider !== '') {
        $order->update_meta_data('_goody_delivery_provider', $provider);
        $order->update_meta_data('delivery_provider', $provider);
    }

    if (
        $stage !== $previous_stage ||
        $status !== $previous_status ||
        $provider !== $previous_provider ||
        $eta !== $previous_eta ||
        $note !== ''
    ) {
        $timeline_note = $note;
        if ($provider !== '') {
            $timeline_note = trim(($timeline_note !== '' ? $timeline_note . ' | ' : '') . sprintf(__('Provider: %s', 'goody'), $provider));
        }
        goody_append_tracking_timeline_event($order, $stage, $status, $timeline_note);
    }

    $order->save();

    $redirect = add_query_arg([
        'page' => 'goody-order-tracking-admin',
        'updated_order' => $order_id,
    ], admin_url('admin.php'));
    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_init', 'goody_handle_order_tracking_admin_actions');

function goody_render_order_tracking_admin_page() {
    if (! goody_can_manage_order_tracking()) {
        wp_die(esc_html__('You are not allowed to access this page.', 'goody'));
    }

    if (! function_exists('wc_get_orders')) {
        echo '<div class="wrap"><h1>' . esc_html__('Order Tracking', 'goody') . '</h1><p>' . esc_html__('WooCommerce is required for this page.', 'goody') . '</p></div>';
        return;
    }

    $search = sanitize_text_field((string) wp_unslash($_GET['s'] ?? ''));
    $orders = wc_get_orders([
        'limit' => 50,
        'orderby' => 'date',
        'order' => 'DESC',
        'type' => 'shop_order',
        'status' => array_keys(wc_get_order_statuses()),
        'search' => $search !== '' ? '*' . $search . '*' : '',
    ]);

    $stages = goody_get_tracking_stage_definitions();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Order Tracking', 'goody'); ?></h1>
        <p><?php esc_html_e('Update tracking process, provider, ETA, and status label for each order from one place.', 'goody'); ?></p>

        <?php if (isset($_GET['updated_order'])) : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Order tracking updated successfully.', 'goody'); ?></p></div>
        <?php endif; ?>

        <form method="get" style="margin:12px 0 16px;">
            <input type="hidden" name="page" value="goody-order-tracking-admin">
            <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="<?php esc_attr_e('Search by order id, customer, email, phone', 'goody'); ?>" style="min-width:320px;">
            <?php submit_button(__('Search', 'goody'), 'secondary', '', false); ?>
            <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=goody-order-tracking-admin')); ?>"><?php esc_html_e('Reset', 'goody'); ?></a>
        </form>

        <div class="card" style="padding:0;max-width:none;">
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Order', 'goody'); ?></th>
                        <th><?php esc_html_e('Customer', 'goody'); ?></th>
                        <th><?php esc_html_e('Total', 'goody'); ?></th>
                        <th><?php esc_html_e('Tracking Update', 'goody'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (! empty($orders)) : ?>
                        <?php foreach ($orders as $order) : ?>
                            <?php
                            if (! $order instanceof WC_Order) {
                                continue;
                            }
                            $order_id = (int) $order->get_id();
                            $customer = trim((string) $order->get_formatted_billing_full_name());
                            if ($customer === '') {
                                $customer = trim((string) $order->get_billing_email());
                            }
                            $current_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
                            if ($current_stage === '') {
                                $current_stage = goody_normalize_tracking_stage((string) $order->get_status());
                            }
                            $current_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));
                            $current_provider = sanitize_text_field((string) ($order->get_meta('_goody_delivery_provider', true) ?: $order->get_meta('delivery_provider', true)));
                            $current_eta = sanitize_text_field((string) $order->get_meta('_goody_tracking_eta', true));
                            ?>
                            <tr>
                                <td>
                                    <strong>#<?php echo esc_html((string) $order_id); ?></strong><br>
                                    <small><?php echo esc_html(goody_format_tracking_datetime($order->get_date_created())); ?></small><br>
                                    <a href="<?php echo esc_url(admin_url('post.php?post=' . $order_id . '&action=edit')); ?>"><?php esc_html_e('Open Order', 'goody'); ?></a>
                                </td>
                                <td><?php echo esc_html($customer); ?><br><small><?php echo esc_html((string) $order->get_billing_phone()); ?></small></td>
                                <td><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                <td>
                                    <form method="post">
                                        <?php wp_nonce_field('goody_update_order_tracking_admin', 'goody_order_tracking_admin_nonce'); ?>
                                        <input type="hidden" name="order_id" value="<?php echo esc_attr((string) $order_id); ?>">
                                        <select name="tracking_stage">
                                            <?php foreach ($stages as $stage_key => $stage_label) : ?>
                                                <option value="<?php echo esc_attr($stage_key); ?>" <?php selected($current_stage, $stage_key); ?>><?php echo esc_html($stage_label); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <input type="text" name="tracking_status" value="<?php echo esc_attr($current_status); ?>" placeholder="<?php esc_attr_e('Status Label', 'goody'); ?>">
                                        <input type="text" name="tracking_provider" value="<?php echo esc_attr($current_provider); ?>" placeholder="<?php esc_attr_e('Provider', 'goody'); ?>">
                                        <input type="text" name="tracking_eta" value="<?php echo esc_attr($current_eta); ?>" placeholder="<?php esc_attr_e('ETA', 'goody'); ?>">
                                        <input type="text" name="tracking_note" value="" placeholder="<?php esc_attr_e('Note (optional)', 'goody'); ?>">
                                        <?php submit_button(__('Update', 'goody'), 'secondary', '', false); ?>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr><td colspan="4"><?php esc_html_e('No orders found.', 'goody'); ?></td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
