<?php

function goody_theme_setup() {
    load_theme_textdomain('goody', GOODY_THEME_DIR . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('woocommerce');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    add_theme_support('custom-logo', [
        'height' => 90,
        'width' => 260,
        'flex-height' => true,
        'flex-width' => true,
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'goody'),
        'footer_quick' => __('Footer Quick Links', 'goody'),
        'footer_legal' => __('Footer Legal Links', 'goody'),
    ]);

    add_image_size('goody-card', 640, 460, true);
    add_image_size('goody-card-mobile', 360, 260, true);
    add_image_size('goody-square', 680, 680, true);
    add_image_size('goody-hero', 1920, 1080, true);
    add_image_size('goody-hero-mobile', 960, 720, true);
    add_image_size('goody-chip', 40, 40, true);
}
add_action('after_setup_theme', 'goody_theme_setup');

function goody_content_width() {
    $GLOBALS['content_width'] = apply_filters('goody_content_width', 1280);
}
add_action('after_setup_theme', 'goody_content_width', 0);

function goody_tracking_template_include($template) {
    if (is_admin()) {
        return $template;
    }

    $tracking_flag = sanitize_text_field((string) wp_unslash($_GET['goody_tracking'] ?? ''));
    if ($tracking_flag === '' || ! in_array(strtolower($tracking_flag), ['1', 'true', 'yes'], true)) {
        return $template;
    }

    $tracking_template = GOODY_THEME_DIR . '/template-parts/pages/tracking-details.php';
    if (file_exists($tracking_template)) {
        return $tracking_template;
    }

    return $template;
}
add_filter('template_include', 'goody_tracking_template_include', 99);
