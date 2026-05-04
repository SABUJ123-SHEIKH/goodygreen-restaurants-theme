<?php

function goody_get_reservations_table_name() {
    global $wpdb;
    return $wpdb->prefix . 'goody_reservations';
}

function goody_create_reservations_table() {
    global $wpdb;

    $table_name = goody_get_reservations_table_name();
    $charset_collate = $wpdb->get_charset_collate();

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';

    $sql = "CREATE TABLE {$table_name} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        reservation_post_id bigint(20) unsigned NOT NULL,
        reference_code varchar(60) NOT NULL DEFAULT '',
        customer_name varchar(255) NOT NULL DEFAULT '',
        customer_phone varchar(40) NOT NULL DEFAULT '',
        booking_day_id bigint(20) unsigned NOT NULL DEFAULT 0,
        booking_date date DEFAULT NULL,
        slot_time varchar(40) NOT NULL DEFAULT '',
        slot_label varchar(120) NOT NULL DEFAULT '',
        order_type varchar(40) NOT NULL DEFAULT '',
        payment_mode varchar(40) NOT NULL DEFAULT '',
        status varchar(40) NOT NULL DEFAULT '',
        payment_status varchar(60) NOT NULL DEFAULT '',
        guests int(11) unsigned NOT NULL DEFAULT 0,
        delivery_zone varchar(200) NOT NULL DEFAULT '',
        address text NULL,
        notes text NULL,
        subtotal decimal(12,2) NOT NULL DEFAULT 0.00,
        delivery_charge decimal(12,2) NOT NULL DEFAULT 0.00,
        total decimal(12,2) NOT NULL DEFAULT 0.00,
        pay_now_total decimal(12,2) NOT NULL DEFAULT 0.00,
        balance_due decimal(12,2) NOT NULL DEFAULT 0.00,
        wc_order_id bigint(20) unsigned NOT NULL DEFAULT 0,
        items_json longtext NULL,
        summary_html longtext NULL,
        created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        UNIQUE KEY reservation_post_id (reservation_post_id),
        KEY reference_code (reference_code),
        KEY customer_phone (customer_phone),
        KEY booking_date (booking_date),
        KEY slot_time (slot_time),
        KEY status (status),
        KEY order_type (order_type),
        KEY wc_order_id (wc_order_id)
    ) {$charset_collate};";

    dbDelta($sql);
}
add_action('after_switch_theme', 'goody_create_reservations_table');
add_action('init', 'goody_create_reservations_table', 5);

function goody_get_reservation_record_from_post($reservation_post_id) {
    $reservation_post_id = absint($reservation_post_id);
    if ($reservation_post_id < 1) {
        return [];
    }

    return [
        'reservation_post_id' => $reservation_post_id,
        'reference_code' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_code', true)),
        'customer_name' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_name', true)),
        'customer_phone' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_phone', true)),
        'booking_day_id' => absint(get_post_meta($reservation_post_id, 'goody_booking_day_id', true)),
        'booking_date' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_date', true)),
        'slot_time' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_slot_time', true)),
        'slot_label' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_slot_label', true)),
        'order_type' => sanitize_key((string) get_post_meta($reservation_post_id, 'goody_reservation_order_type', true)),
        'payment_mode' => sanitize_key((string) get_post_meta($reservation_post_id, 'goody_reservation_payment_mode', true)),
        'status' => sanitize_key((string) get_post_meta($reservation_post_id, 'goody_reservation_status', true)),
        'payment_status' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_reservation_payment_status', true)),
        'guests' => absint(get_post_meta($reservation_post_id, 'goody_reservation_guests', true)),
        'delivery_zone' => sanitize_text_field((string) get_post_meta($reservation_post_id, 'goody_delivery_zone_name', true)),
        'address' => sanitize_textarea_field((string) get_post_meta($reservation_post_id, 'goody_reservation_address', true)),
        'notes' => sanitize_textarea_field((string) get_post_meta($reservation_post_id, 'goody_reservation_note', true)),
        'subtotal' => (float) get_post_meta($reservation_post_id, 'goody_reservation_subtotal', true),
        'delivery_charge' => (float) get_post_meta($reservation_post_id, 'goody_reservation_delivery_charge', true),
        'total' => (float) get_post_meta($reservation_post_id, 'goody_reservation_total', true),
        'pay_now_total' => (float) get_post_meta($reservation_post_id, 'goody_reservation_pay_now_total', true),
        'balance_due' => (float) get_post_meta($reservation_post_id, 'goody_reservation_balance_due', true),
        'wc_order_id' => absint(get_post_meta($reservation_post_id, 'goody_wc_order_id', true)),
        'items_json' => (string) get_post_meta($reservation_post_id, 'goody_reservation_items_json', true),
        'summary_html' => (string) get_post_meta($reservation_post_id, 'goody_reservation_summary_html', true),
    ];
}

function goody_upsert_reservation_record($reservation_post_id) {
    global $wpdb;

    $record = goody_get_reservation_record_from_post($reservation_post_id);
    if (empty($record['reservation_post_id'])) {
        return false;
    }

    $table_name = goody_get_reservations_table_name();
    $existing_id = $wpdb->get_var($wpdb->prepare("SELECT id FROM {$table_name} WHERE reservation_post_id = %d", $record['reservation_post_id']));
    $data = array_merge($record, [
        'updated_at' => current_time('mysql'),
    ]);
    $formats = [
        '%d', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s',
        '%s', '%s', '%d', '%s', '%s', '%s', '%f', '%f', '%f', '%f',
        '%f', '%d', '%s', '%s', '%s',
    ];

    if ($existing_id) {
        return (bool) $wpdb->update(
            $table_name,
            $data,
            ['reservation_post_id' => $record['reservation_post_id']],
            $formats,
            ['%d']
        );
    }

    $data['created_at'] = current_time('mysql');
    $formats[] = '%s';

    return (bool) $wpdb->insert($table_name, $data, $formats);
}

function goody_sync_reservation_table_on_save($post_id, $post = null, $update = false) {
    if (get_post_type($post_id) !== 'goody_reservation') {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    goody_upsert_reservation_record($post_id);
}
add_action('save_post_goody_reservation', 'goody_sync_reservation_table_on_save', 50, 3);

function goody_get_reservation_dashboard_filters() {
    return [
        'search' => sanitize_text_field((string) ($_GET['s'] ?? '')),
        'date' => sanitize_text_field((string) ($_GET['booking_date'] ?? '')),
        'time' => sanitize_text_field((string) ($_GET['slot_time'] ?? '')),
        'status' => sanitize_key((string) ($_GET['reservation_status'] ?? '')),
        'order_type' => sanitize_key((string) ($_GET['order_type'] ?? '')),
    ];
}

function goody_get_reservation_dashboard_records($filters = []) {
    global $wpdb;
    $table_name = goody_get_reservations_table_name();

    $where = ['1=1'];
    $params = [];

    if (! empty($filters['search'])) {
        $needle = '%' . $wpdb->esc_like($filters['search']) . '%';
        $where[] = '(customer_name LIKE %s OR customer_phone LIKE %s OR reference_code LIKE %s OR wc_order_id LIKE %s)';
        $params[] = $needle;
        $params[] = $needle;
        $params[] = $needle;
        $params[] = $needle;
    }

    if (! empty($filters['date'])) {
        $where[] = 'booking_date = %s';
        $params[] = $filters['date'];
    }

    if (! empty($filters['time'])) {
        $where[] = 'slot_time = %s';
        $params[] = $filters['time'];
    }

    if (! empty($filters['status'])) {
        $where[] = 'status = %s';
        $params[] = $filters['status'];
    }

    if (! empty($filters['order_type'])) {
        $where[] = 'order_type = %s';
        $params[] = $filters['order_type'];
    }

    $sql = "SELECT * FROM {$table_name} WHERE " . implode(' AND ', $where) . ' ORDER BY booking_date DESC, slot_time DESC, id DESC';

    if (! empty($params)) {
        $sql = $wpdb->prepare($sql, $params);
    }

    return $wpdb->get_results($sql);
}

function goody_register_reservation_dashboard_menu() {
    add_submenu_page(
        'goody-theme',
        __('Reservation Dashboard', 'goody'),
        __('Reservation Dashboard', 'goody'),
        'edit_theme_options',
        'goody-reservation-dashboard',
        'goody_render_reservation_dashboard_page'
    );
}
add_action('admin_menu', 'goody_register_reservation_dashboard_menu');

function goody_handle_reservation_dashboard_actions() {
    if (! is_admin() || ! current_user_can('edit_theme_options')) {
        return;
    }

    $page = sanitize_text_field((string) ($_GET['page'] ?? ''));
    if ($page !== 'goody-reservation-dashboard') {
        return;
    }

    if (isset($_POST['goody_reservation_status_nonce'], $_POST['reservation_post_id'], $_POST['reservation_status'])) {
        if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_reservation_status_nonce'])), 'goody_update_reservation_status')) {
            return;
        }

        $reservation_post_id = absint($_POST['reservation_post_id']);
        $status = sanitize_key(wp_unslash($_POST['reservation_status']));
        if ($reservation_post_id > 0 && isset(goody_get_reservation_statuses()[$status])) {
            update_post_meta($reservation_post_id, 'goody_reservation_status', $status);
            goody_upsert_reservation_record($reservation_post_id);
            if (function_exists('goody_sync_order_tracking_from_reservation')) {
                goody_sync_order_tracking_from_reservation($reservation_post_id);
            }
        }
    }

    if (isset($_POST['goody_order_tracking_nonce'], $_POST['reservation_post_id'], $_POST['tracking_stage'])) {
        if (! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_order_tracking_nonce'])), 'goody_update_order_tracking_from_dashboard')) {
            return;
        }

        $reservation_post_id = absint($_POST['reservation_post_id']);
        if ($reservation_post_id < 1 || ! function_exists('wc_get_order')) {
            return;
        }

        $order_id = absint(get_post_meta($reservation_post_id, 'goody_wc_order_id', true));
        if ($order_id < 1) {
            return;
        }

        $order = wc_get_order($order_id);
        if (! $order instanceof WC_Order) {
            return;
        }

        $stage = goody_normalize_tracking_stage((string) wp_unslash($_POST['tracking_stage']));
        if ($stage === '') {
            return;
        }

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
            update_post_meta($reservation_post_id, 'goody_reservation_status', $reservation_status);
            goody_upsert_reservation_record($reservation_post_id);
        }

        $stage_defs = goody_get_tracking_stage_definitions();
        $status = isset($stage_defs[$stage]) ? (string) $stage_defs[$stage] : sanitize_text_field((string) wp_unslash($_POST['tracking_status'] ?? ''));
        $provider = sanitize_text_field((string) wp_unslash($_POST['tracking_provider'] ?? ''));
        $eta = sanitize_text_field((string) wp_unslash($_POST['tracking_eta'] ?? ''));
        $note = sanitize_text_field((string) wp_unslash($_POST['tracking_note'] ?? ''));

        $previous_stage = goody_normalize_tracking_stage((string) $order->get_meta('_goody_tracking_stage', true));
        $previous_status = sanitize_text_field((string) $order->get_meta('_goody_tracking_status', true));
        $previous_provider = sanitize_text_field((string) $order->get_meta('_goody_delivery_provider', true));
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
            if (function_exists('goody_append_tracking_timeline_event')) {
                goody_append_tracking_timeline_event($order, $stage, $status, $timeline_note);
            }
        }

        $order->save();

        if ($reservation_status !== '' && function_exists('goody_sync_order_tracking_from_reservation')) {
            goody_sync_order_tracking_from_reservation($reservation_post_id);
        }
    }
}
add_action('admin_init', 'goody_handle_reservation_dashboard_actions');

function goody_render_reservation_dashboard_page() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to access this page.', 'goody'));
    }

    $filters = goody_get_reservation_dashboard_filters();
    $records = goody_get_reservation_dashboard_records($filters);
    $view_id = absint($_GET['view_reservation'] ?? 0);
    $view_post = $view_id > 0 ? get_post($view_id) : null;
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Reservation Dashboard', 'goody'); ?></h1>
        <p><?php esc_html_e('Search, filter, print, export, and manage all restaurant reservations from here.', 'goody'); ?></p>

        <form method="get" style="margin:16px 0 20px;">
            <input type="hidden" name="page" value="goody-reservation-dashboard">
            <input type="search" name="s" value="<?php echo esc_attr($filters['search']); ?>" placeholder="<?php esc_attr_e('Search by name, phone, reference, order ID', 'goody'); ?>">
            <input type="date" name="booking_date" value="<?php echo esc_attr($filters['date']); ?>">
            <input type="time" name="slot_time" value="<?php echo esc_attr($filters['time']); ?>">
            <select name="reservation_status">
                <option value=""><?php esc_html_e('All statuses', 'goody'); ?></option>
                <?php foreach (goody_get_reservation_statuses() as $status_key => $status_label) : ?>
                    <option value="<?php echo esc_attr($status_key); ?>" <?php selected($filters['status'], $status_key); ?>><?php echo esc_html($status_label); ?></option>
                <?php endforeach; ?>
            </select>
            <select name="order_type">
                <option value=""><?php esc_html_e('All order types', 'goody'); ?></option>
                <?php foreach (goody_get_reservation_order_types() as $type_key => $type_label) : ?>
                    <option value="<?php echo esc_attr($type_key); ?>" <?php selected($filters['order_type'], $type_key); ?>><?php echo esc_html($type_label); ?></option>
                <?php endforeach; ?>
            </select>
            <?php submit_button(__('Filter', 'goody'), 'secondary', '', false); ?>
            <a class="button" href="<?php echo esc_url(admin_url('admin.php?page=goody-reservation-dashboard')); ?>"><?php esc_html_e('Reset', 'goody'); ?></a>
            <a class="button button-primary" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=goody_export_reservations_csv'), 'goody_export_reservations_csv', 'goody_export_reservations_csv_nonce')); ?>"><?php esc_html_e('Export CSV', 'goody'); ?></a>
        </form>

        <div style="display:grid;grid-template-columns:minmax(0,1.6fr) minmax(320px,0.9fr);gap:20px;align-items:start;">
            <div class="card" style="padding:0;max-width:none;">
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Reference', 'goody'); ?></th>
                            <th><?php esc_html_e('Customer', 'goody'); ?></th>
                            <th><?php esc_html_e('Date', 'goody'); ?></th>
                            <th><?php esc_html_e('Slot', 'goody'); ?></th>
                            <th><?php esc_html_e('Type', 'goody'); ?></th>
                            <th><?php esc_html_e('Total', 'goody'); ?></th>
                            <th><?php esc_html_e('Status', 'goody'); ?></th>
                            <th><?php esc_html_e('Actions', 'goody'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($records)) : ?>
                            <?php foreach ($records as $record) : ?>
                                <?php
                                $print_url = wp_nonce_url(admin_url('admin-post.php?action=goody_print_reservation&reservation_id=' . $record->reservation_post_id), 'goody_print_reservation_' . $record->reservation_post_id);
                                $view_url = add_query_arg([
                                    'page' => 'goody-reservation-dashboard',
                                    'view_reservation' => $record->reservation_post_id,
                                ], admin_url('admin.php'));
                                ?>
                                <tr>
                                    <td><strong><?php echo esc_html($record->reference_code); ?></strong><br><small>#<?php echo esc_html((string) $record->reservation_post_id); ?></small></td>
                                    <td><?php echo esc_html($record->customer_name); ?><br><small><?php echo esc_html($record->customer_phone); ?></small></td>
                                    <td><?php echo esc_html((string) $record->booking_date); ?></td>
                                    <td><?php echo esc_html($record->slot_label ?: $record->slot_time); ?></td>
                                    <td><?php echo esc_html(goody_get_reservation_order_types()[$record->order_type] ?? $record->order_type); ?></td>
                                    <td><?php echo goody_reservation_price_html((float) $record->total); ?></td>
                                    <td><?php echo esc_html(goody_get_reservation_status_label($record->status)); ?></td>
                                    <td>
                                        <a class="button button-small" href="<?php echo esc_url($view_url); ?>"><?php esc_html_e('View', 'goody'); ?></a>
                                        <a class="button button-small" href="<?php echo esc_url($print_url); ?>" target="_blank" rel="noopener"><?php esc_html_e('Print', 'goody'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr><td colspan="8"><?php esc_html_e('No reservations found for the selected filters.', 'goody'); ?></td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="card" style="padding:20px;">
                <h2 style="margin-top:0;"><?php esc_html_e('Reservation Details', 'goody'); ?></h2>
                <?php if ($view_post instanceof WP_Post && $view_post->post_type === 'goody_reservation') : ?>
                    <?php
                    $summary_html = (string) get_post_meta($view_post->ID, 'goody_reservation_summary_html', true);
                    $order_id = absint(get_post_meta($view_post->ID, 'goody_wc_order_id', true));
                    $order_type = sanitize_key((string) get_post_meta($view_post->ID, 'goody_reservation_order_type', true));
                    $payment_mode = sanitize_key((string) get_post_meta($view_post->ID, 'goody_reservation_payment_mode', true));
                    ?>
                    <p><strong><?php esc_html_e('Reference:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_code', true)); ?></p>
                    <p><strong><?php esc_html_e('Customer:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_name', true)); ?></p>
                    <p><strong><?php esc_html_e('Phone:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_phone', true)); ?></p>
                    <p><strong><?php esc_html_e('Booking Date:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_date', true)); ?></p>
                    <p><strong><?php esc_html_e('Time Slot:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_slot_label', true)); ?></p>
                    <p><strong><?php esc_html_e('Order Type:', 'goody'); ?></strong> <?php echo esc_html(goody_get_reservation_order_types()[$order_type] ?? $order_type); ?></p>
                    <p><strong><?php esc_html_e('Payment Mode:', 'goody'); ?></strong> <?php echo esc_html(goody_get_reservation_payment_modes()[$payment_mode] ?? $payment_mode); ?></p>
                    <p><strong><?php esc_html_e('Payment Status:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_payment_status', true)); ?></p>
                    <p><strong><?php esc_html_e('Guests:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_guests', true)); ?></p>
                    <p><strong><?php esc_html_e('Address:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_address', true)); ?></p>
                    <p><strong><?php esc_html_e('Note:', 'goody'); ?></strong> <?php echo esc_html((string) get_post_meta($view_post->ID, 'goody_reservation_note', true)); ?></p>
                    <?php if ($order_id > 0) : ?>
                        <p><strong><?php esc_html_e('WooCommerce Order:', 'goody'); ?></strong> <a href="<?php echo esc_url(admin_url('post.php?post=' . $order_id . '&action=edit')); ?>">#<?php echo esc_html((string) $order_id); ?></a></p>
                    <?php endif; ?>
                    <?php if ($order_id > 0 && function_exists('wc_get_order')) : ?>
                        <?php
                        $tracking_order = wc_get_order($order_id);
                        $tracking_stage = $tracking_order instanceof WC_Order ? goody_normalize_tracking_stage((string) $tracking_order->get_meta('_goody_tracking_stage', true)) : '';
                        if ($tracking_stage === '' && $tracking_order instanceof WC_Order) {
                            $tracking_stage = goody_normalize_tracking_stage((string) $tracking_order->get_status());
                        }
                        $tracking_status = $tracking_order instanceof WC_Order ? sanitize_text_field((string) $tracking_order->get_meta('_goody_tracking_status', true)) : '';
                        $tracking_provider = $tracking_order instanceof WC_Order ? sanitize_text_field((string) ($tracking_order->get_meta('_goody_delivery_provider', true) ?: $tracking_order->get_meta('delivery_provider', true))) : '';
                        $tracking_eta = $tracking_order instanceof WC_Order ? sanitize_text_field((string) $tracking_order->get_meta('_goody_tracking_eta', true)) : '';
                        ?>
                        <form method="post" style="margin:16px 0;padding:12px;border:1px solid #dcdcde;border-radius:8px;">
                            <?php wp_nonce_field('goody_update_order_tracking_from_dashboard', 'goody_order_tracking_nonce'); ?>
                            <input type="hidden" name="reservation_post_id" value="<?php echo esc_attr((string) $view_post->ID); ?>">
                            <p style="margin:0 0 10px;"><strong><?php esc_html_e('Order Tracking Update', 'goody'); ?></strong></p>
                            <label for="tracking_stage"><strong><?php esc_html_e('Tracking Stage', 'goody'); ?></strong></label><br>
                            <select name="tracking_stage" id="tracking_stage">
                                <?php foreach (goody_get_tracking_stage_definitions() as $stage_key => $stage_label) : ?>
                                    <option value="<?php echo esc_attr($stage_key); ?>" <?php selected($tracking_stage, $stage_key); ?>><?php echo esc_html($stage_label); ?></option>
                                <?php endforeach; ?>
                            </select><br><br>
                            <label for="tracking_status"><strong><?php esc_html_e('Status Label', 'goody'); ?></strong></label><br>
                            <input type="text" id="tracking_status" name="tracking_status" value="<?php echo esc_attr($tracking_status); ?>" style="width:100%;"><br><br>
                            <label for="tracking_provider"><strong><?php esc_html_e('Delivery Provider Name', 'goody'); ?></strong></label><br>
                            <input type="text" id="tracking_provider" name="tracking_provider" value="<?php echo esc_attr($tracking_provider); ?>" style="width:100%;"><br><br>
                            <label for="tracking_eta"><strong><?php esc_html_e('ETA', 'goody'); ?></strong></label><br>
                            <input type="text" id="tracking_eta" name="tracking_eta" value="<?php echo esc_attr($tracking_eta); ?>" style="width:100%;"><br><br>
                            <label for="tracking_note"><strong><?php esc_html_e('Note (optional)', 'goody'); ?></strong></label><br>
                            <input type="text" id="tracking_note" name="tracking_note" value="" style="width:100%;"><br><br>
                            <?php submit_button(__('Update Reservation & Tracking', 'goody'), 'primary', '', false); ?>
                        </form>
                    <?php endif; ?>
                    <div><?php echo wp_kses_post($summary_html); ?></div>
                <?php else : ?>
                    <p><?php esc_html_e('Select a reservation from the list to view details.', 'goody'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}
