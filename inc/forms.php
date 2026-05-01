<?php

function goody_get_redirect_with_status($status, $context = 'contact') {
    $redirect = wp_get_referer();
    if (! $redirect) {
        $redirect = home_url('/');
    }

    return add_query_arg([
        'goody_form' => sanitize_key($context),
        'goody_status' => sanitize_key($status),
    ], $redirect);
}

function goody_handle_contact_form_submission() {
    if (! isset($_POST['goody_contact_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_contact_nonce'])), 'goody_contact_submit')) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_nonce'));
        exit;
    }

    $name = sanitize_text_field(wp_unslash($_POST['goody_contact_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['goody_contact_email'] ?? ''));
    $phone = sanitize_text_field(wp_unslash($_POST['goody_contact_phone'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['goody_contact_message'] ?? ''));

    if (! $name || ! is_email($email) || ! $message) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_fields'));
        exit;
    }

    $to = goody_get_option('contact_email');
    if (! is_email($to)) {
        $to = get_option('admin_email');
    }

    $subject = sprintf(__('New contact message from %s', 'goody'), $name);
    $body_lines = [
        'Name: ' . $name,
        'Email: ' . $email,
    ];

    if ($phone) {
        $body_lines[] = 'Phone: ' . $phone;
    }

    $body_lines[] = '';
    $body_lines[] = 'Message:';
    $body_lines[] = $message;

    $headers = [
        'Reply-To: ' . $name . ' <' . $email . '>',
        'Content-Type: text/plain; charset=UTF-8',
    ];

    $post_id = wp_insert_post([
        'post_type' => 'goody_message',
        'post_status' => 'private',
        'post_title' => $name . ' - ' . current_time('Y-m-d H:i'),
        'post_content' => $message,
    ]);

    if ($post_id && ! is_wp_error($post_id)) {
        update_post_meta($post_id, 'goody_message_name', $name);
        update_post_meta($post_id, 'goody_message_email', $email);
        update_post_meta($post_id, 'goody_message_phone', $phone);
    }

    $sent = wp_mail($to, $subject, implode("\n", $body_lines), $headers);

    if (! $sent) {
        wp_safe_redirect(goody_get_redirect_with_status('mail_failed'));
        exit;
    }

    wp_safe_redirect(goody_get_redirect_with_status('success'));
    exit;
}
add_action('admin_post_goody_contact_form', 'goody_handle_contact_form_submission');
add_action('admin_post_nopriv_goody_contact_form', 'goody_handle_contact_form_submission');

function goody_handle_newsletter_submission() {
    if (! isset($_POST['goody_newsletter_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_newsletter_nonce'])), 'goody_newsletter_submit')) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_nonce', 'newsletter'));
        exit;
    }

    $email = sanitize_email(wp_unslash($_POST['goody_newsletter_email'] ?? ''));
    if (! is_email($email)) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_fields', 'newsletter'));
        exit;
    }

    $subscribers = get_option('goody_newsletter_subscribers', []);
    if (! is_array($subscribers)) {
        $subscribers = [];
    }

    if (in_array($email, $subscribers, true)) {
        wp_safe_redirect(goody_get_redirect_with_status('already_exists', 'newsletter'));
        exit;
    }

    $subscribers[] = $email;
    update_option('goody_newsletter_subscribers', array_values(array_unique($subscribers)));

    $admin_email = get_option('admin_email');
    if (is_email($admin_email)) {
        wp_mail(
            $admin_email,
            __('New newsletter subscriber', 'goody'),
            sprintf(__('Subscriber email: %s', 'goody'), $email)
        );
    }

    wp_safe_redirect(goody_get_redirect_with_status('success', 'newsletter'));
    exit;
}
add_action('admin_post_goody_newsletter_subscribe', 'goody_handle_newsletter_submission');
add_action('admin_post_nopriv_goody_newsletter_subscribe', 'goody_handle_newsletter_submission');

function goody_create_testimonial_image_attachment($file, $post_id) {
    if (! is_array($file) || empty($file['name']) || empty($file['tmp_name'])) {
        return new WP_Error('invalid_file', __('Invalid image upload.', 'goody'));
    }

    $allowed_mimes = [
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';

    $upload = wp_handle_upload($file, [
        'test_form' => false,
        'mimes' => $allowed_mimes,
    ]);

    if (! is_array($upload) || isset($upload['error'])) {
        return new WP_Error('upload_error', __('Could not upload image.', 'goody'));
    }

    $attachment = [
        'post_mime_type' => $upload['type'] ?? 'image/jpeg',
        'post_title' => sanitize_text_field(pathinfo((string) $file['name'], PATHINFO_FILENAME)),
        'post_content' => '',
        'post_status' => 'inherit',
    ];

    $attachment_id = wp_insert_attachment($attachment, $upload['file'], $post_id);
    if (! $attachment_id || is_wp_error($attachment_id)) {
        return new WP_Error('attachment_error', __('Could not save image attachment.', 'goody'));
    }

    $metadata = wp_generate_attachment_metadata($attachment_id, $upload['file']);
    if (is_array($metadata)) {
        wp_update_attachment_metadata($attachment_id, $metadata);
    }

    return (int) $attachment_id;
}

function goody_has_testimonial_for_order_id($order_id) {
    $order_id = absint($order_id);
    if ($order_id < 1) {
        return false;
    }

    $existing = get_posts([
        'post_type' => 'testimonial',
        'post_status' => ['publish', 'pending', 'draft', 'future', 'private'],
        'posts_per_page' => 1,
        'fields' => 'ids',
        'no_found_rows' => true,
        'suppress_filters' => true,
        'cache_results' => false,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'meta_query' => [
            [
                'key' => 'goody_testimonial_order_id',
                'value' => (string) $order_id,
                'compare' => '=',
            ],
        ],
    ]);

    return ! empty($existing);
}

function goody_get_testimonial_order_source($order_id) {
    $order_id = absint($order_id);
    if ($order_id < 1) {
        return '';
    }

    if (function_exists('goody_is_woocommerce_available') && goody_is_woocommerce_available() && function_exists('wc_get_order')) {
        $order = wc_get_order($order_id);
        if ($order) {
            return 'woocommerce';
        }
    }

    $reservation_post = get_post($order_id);
    if ($reservation_post instanceof WP_Post && $reservation_post->post_type === 'goody_reservation') {
        return 'reservation';
    }

    return '';
}

function goody_validate_testimonial_order_id($order_id) {
    $order_id = absint($order_id);
    if ($order_id < 1) {
        return [
            'valid' => false,
            'code' => 'invalid_order',
            'message' => __('Please enter a valid order ID before submitting your review.', 'goody'),
            'order_source' => '',
        ];
    }

    $order_source = goody_get_testimonial_order_source($order_id);
    if ($order_source === '') {
        return [
            'valid' => false,
            'code' => 'invalid_order',
            'message' => __('Please enter a valid order ID before submitting your review.', 'goody'),
            'order_source' => '',
        ];
    }

    if (goody_has_testimonial_for_order_id($order_id)) {
        return [
            'valid' => false,
            'code' => 'order_already_reviewed',
            'message' => __('A review has already been submitted for this order ID.', 'goody'),
            'order_source' => '',
        ];
    }

    return [
        'valid' => true,
        'code' => 'ok',
        'message' => '',
        'order_source' => $order_source,
    ];
}

function goody_ajax_validate_testimonial_order() {
    check_ajax_referer('goody_nonce', 'nonce');

    $order_id = absint(wp_unslash($_POST['order_id'] ?? 0));
    $validation = goody_validate_testimonial_order_id($order_id);

    if (! $validation['valid']) {
        wp_send_json_error([
            'code' => $validation['code'],
            'message' => $validation['message'],
        ]);
    }

    wp_send_json_success([
        'order_id' => $order_id,
        'order_source' => $validation['order_source'],
    ]);
}
add_action('wp_ajax_goody_validate_testimonial_order', 'goody_ajax_validate_testimonial_order');
add_action('wp_ajax_nopriv_goody_validate_testimonial_order', 'goody_ajax_validate_testimonial_order');

function goody_handle_testimonial_submission() {
    if (! isset($_POST['goody_testimonial_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['goody_testimonial_nonce'])), 'goody_testimonial_submit')) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_nonce', 'testimonial'));
        exit;
    }

    $honeypot = sanitize_text_field(wp_unslash($_POST['goody_testimonial_website'] ?? ''));
    if ($honeypot !== '') {
        wp_safe_redirect(goody_get_redirect_with_status('spam_blocked', 'testimonial'));
        exit;
    }

    $order_id = absint(wp_unslash($_POST['goody_testimonial_order_id'] ?? 0));
    $order_validation = goody_validate_testimonial_order_id($order_id);
    if (! $order_validation['valid']) {
        wp_safe_redirect(goody_get_redirect_with_status($order_validation['code'], 'testimonial'));
        exit;
    }
    $order_source = $order_validation['order_source'];

    $name = sanitize_text_field(wp_unslash($_POST['goody_testimonial_name'] ?? ''));
    $email = sanitize_email(wp_unslash($_POST['goody_testimonial_email'] ?? ''));
    $message = sanitize_textarea_field(wp_unslash($_POST['goody_testimonial_message'] ?? ''));
    $rating = (int) wp_unslash($_POST['goody_testimonial_rating'] ?? 0);
    $raw_rating = $rating;
    $food_rating = (int) wp_unslash($_POST['goody_testimonial_food_rating'] ?? 0);
    $ambiance_rating = (int) wp_unslash($_POST['goody_testimonial_ambiance_rating'] ?? 0);
    $service_rating = (int) wp_unslash($_POST['goody_testimonial_service_rating'] ?? 0);

    $rating = max(1, min(5, $rating));
    $food_rating = max(0, min(5, $food_rating));
    $ambiance_rating = max(0, min(5, $ambiance_rating));
    $service_rating = max(0, min(5, $service_rating));

    if ($name === '' || $message === '' || $raw_rating < 1 || $raw_rating > 5) {
        wp_safe_redirect(goody_get_redirect_with_status('invalid_fields', 'testimonial'));
        exit;
    }

    $post_id = wp_insert_post([
        'post_type' => 'testimonial',
        'post_status' => 'draft',
        'post_title' => sprintf('%s - %s', $name, current_time('Y-m-d H:i')),
        'post_content' => $message,
    ], true);

    if (! $post_id || is_wp_error($post_id)) {
        wp_safe_redirect(goody_get_redirect_with_status('save_failed', 'testimonial'));
        exit;
    }

    update_post_meta($post_id, 'goody_testimonial_customer_name', $name);
    update_post_meta($post_id, 'goody_testimonial_rating', (string) $rating);
    update_post_meta($post_id, 'goody_testimonial_food_rating', (string) $food_rating);
    update_post_meta($post_id, 'goody_testimonial_ambiance_rating', (string) $ambiance_rating);
    update_post_meta($post_id, 'goody_testimonial_service_rating', (string) $service_rating);
    update_post_meta($post_id, 'goody_testimonial_source_label', 'Direct');
    update_post_meta($post_id, 'goody_testimonial_order_id', (string) $order_id);
    update_post_meta($post_id, 'goody_testimonial_order_source', $order_source);

    if (is_email($email)) {
        update_post_meta($post_id, 'goody_testimonial_customer_email', $email);
    }

    if (isset($_FILES['goody_testimonial_image']) && is_array($_FILES['goody_testimonial_image'])) {
        $image = $_FILES['goody_testimonial_image'];
        if (! empty($image['name'])) {
            if ((int) ($image['error'] ?? 0) !== UPLOAD_ERR_OK) {
                wp_safe_redirect(goody_get_redirect_with_status('upload_failed', 'testimonial'));
                exit;
            }

            $attachment_id = goody_create_testimonial_image_attachment($image, (int) $post_id);
            if (is_wp_error($attachment_id)) {
                wp_safe_redirect(goody_get_redirect_with_status('upload_failed', 'testimonial'));
                exit;
            }

            update_post_meta($post_id, 'goody_testimonial_customer_image', $attachment_id);
            set_post_thumbnail((int) $post_id, $attachment_id);
        }
    }

    $google_redirect_requested = sanitize_text_field(wp_unslash($_POST['goody_testimonial_google_redirect'] ?? '')) === '1';
    if ($google_redirect_requested && goody_get_option('reviews_google_handoff_after_submit', '1') === '1' && function_exists('goody_get_google_review_handoff_url')) {
        $google_review_url = goody_get_google_review_handoff_url();
        if ($google_review_url !== '' && goody_is_trusted_review_handoff_url($google_review_url)) {
            update_post_meta((int) $post_id, 'goody_testimonial_google_handoff_url', esc_url_raw($google_review_url));
            update_post_meta((int) $post_id, 'goody_testimonial_google_handoff_requested', '1');
            wp_redirect($google_review_url);
            exit;
        }
    }

    wp_safe_redirect(goody_get_redirect_with_status('success', 'testimonial'));
    exit;
}
add_action('admin_post_goody_testimonial_submit', 'goody_handle_testimonial_submission');
add_action('admin_post_nopriv_goody_testimonial_submit', 'goody_handle_testimonial_submission');

function goody_form_message($context = 'contact') {
    $form = sanitize_key($_GET['goody_form'] ?? '');
    $status = sanitize_key($_GET['goody_status'] ?? '');

    if ($form !== $context || $status === '') {
        return '';
    }

    $messages = [
        'contact' => [
            'success' => __('Thank you. Your message has been sent successfully.', 'goody'),
            'invalid_nonce' => __('Session expired. Please try again.', 'goody'),
            'invalid_fields' => __('Please fill all required fields correctly.', 'goody'),
            'mail_failed' => __('Could not send now. Please try again shortly.', 'goody'),
            'already_exists' => __('This email is already subscribed.', 'goody'),
        ],
        'newsletter' => [
            'success' => __('Subscription completed successfully.', 'goody'),
            'invalid_nonce' => __('Session expired. Please try again.', 'goody'),
            'invalid_fields' => __('Please fill all required fields correctly.', 'goody'),
            'already_exists' => __('This email is already subscribed.', 'goody'),
        ],
        'testimonial' => [
            'success' => __('Thank you. Your review has been submitted and is waiting for approval.', 'goody'),
            'invalid_nonce' => __('Session expired. Please try again.', 'goody'),
            'invalid_fields' => __('Please provide your name, rating, and review text.', 'goody'),
            'invalid_order' => __('Please enter a valid order ID before submitting your review.', 'goody'),
            'order_already_reviewed' => __('A review has already been submitted for this order ID.', 'goody'),
            'spam_blocked' => __('Submission blocked. Please try again.', 'goody'),
            'upload_failed' => __('Review saved as draft, but image upload failed. Please try again.', 'goody'),
            'save_failed' => __('Could not save your review now. Please try again shortly.', 'goody'),
        ],
    ];

    $context_messages = $messages[$context] ?? $messages['contact'];
    return $context_messages[$status] ?? '';
}
