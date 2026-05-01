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
