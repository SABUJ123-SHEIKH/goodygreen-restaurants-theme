<?php

function goody_get_asset_path($relative_path) {
    $relative_path = ltrim((string) $relative_path, '/');
    $use_minified = ! (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG);

    if ($use_minified) {
        $minified_path = preg_replace('/\.(css|js)$/', '.min.$1', $relative_path);
        if (is_string($minified_path) && $minified_path !== '' && file_exists(get_theme_file_path($minified_path))) {
            return $minified_path;
        }
    }

    return $relative_path;
}

function goody_should_enqueue_reservation_assets() {
    if (is_admin()) {
        return false;
    }

    if (is_front_page()) {
        $embed = (string) goody_get_option('reservation_embed', '');
        if ($embed !== '' && (
            stripos($embed, 'reservation_booking') !== false ||
            stripos($embed, 'reservation_menu') !== false ||
            stripos($embed, 'reservation_order_status') !== false ||
            stripos($embed, 'goody-reservation-app') !== false
        )) {
            return true;
        }
    }

    if (is_singular()) {
        $post = get_post();
        $content = $post instanceof WP_Post ? (string) $post->post_content : '';
        if ($content !== '' && (
            has_shortcode($content, 'reservation_booking') ||
            has_shortcode($content, 'reservation_menu') ||
            has_shortcode($content, 'reservation_order_status')
        )) {
            return true;
        }
    }

    $tracking = sanitize_text_field((string) wp_unslash($_GET['tracking'] ?? ''));
    if ($tracking !== '') {
        return true;
    }

    return false;
}

function goody_enqueue_assets() {
    $maps_api_key = goody_extract_google_maps_api_key(goody_get_option('integrations_maps_api_key', ''));
    if ($maps_api_key === '') {
        $maps_api_key = goody_extract_google_maps_api_key(goody_get_option('map_script_embed', ''));
    }
    $google_reviews_api_key = function_exists('goody_get_effective_reviews_api_key')
        ? goody_get_effective_reviews_api_key('google')
        : '';

    $goody_polyfill_script = <<<'JS'
(function () {
  var cryptoObject = window.crypto || window.msCrypto || {};

  if (!window.crypto) {
    window.crypto = cryptoObject;
  }

  if (typeof cryptoObject.randomUUID === 'function') {
    return;
  }

  function fallbackSegment() {
    return Math.floor((1 + Math.random()) * 0x10000).toString(16).slice(1);
  }

  cryptoObject.randomUUID = function () {
    if (typeof cryptoObject.getRandomValues === 'function' && typeof Uint8Array !== 'undefined') {
      var bytes = new Uint8Array(16);
      cryptoObject.getRandomValues(bytes);
      bytes[6] = (bytes[6] & 15) | 64;
      bytes[8] = (bytes[8] & 63) | 128;

      var hex = Array.from(bytes, function (value) {
        return value.toString(16).padStart(2, '0');
      }).join('');

      return hex.slice(0, 8) + '-' + hex.slice(8, 12) + '-' + hex.slice(12, 16) + '-' + hex.slice(16, 20) + '-' + hex.slice(20);
    }

    return fallbackSegment() + fallbackSegment() + '-' + fallbackSegment() + '-' + fallbackSegment() + '-' + fallbackSegment() + '-' + fallbackSegment() + fallbackSegment() + fallbackSegment();
  };
}());
JS;

    wp_register_script('goody-polyfills', false, [], GOODY_THEME_VERSION, false);
    wp_enqueue_script('goody-polyfills');
    wp_add_inline_script('goody-polyfills', $goody_polyfill_script, 'before');

    $fonts_url = goody_normalize_url_input(goody_get_option('token_fonts_url', 'https://fonts.googleapis.com/css2?family=Allura&family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap'));
    if ($fonts_url !== '') {
        wp_enqueue_style('goody-fonts', $fonts_url, [], null);
    }
    $main_css_asset = goody_get_asset_path('assets/css/main.css');
    $main_js_asset = goody_get_asset_path('assets/js/main.js');

    wp_enqueue_style('goody-main', GOODY_THEME_URI . '/' . $main_css_asset, array_values(array_filter([$fonts_url !== '' ? 'goody-fonts' : ''])), GOODY_THEME_VERSION);

    wp_enqueue_script('goody-main', GOODY_THEME_URI . '/' . $main_js_asset, [], GOODY_THEME_VERSION, true);
    wp_localize_script('goody-main', 'goodyTheme', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('goody_nonce'),
        'isFrontPage' => is_front_page(),
        'mapsApiKey' => $maps_api_key,
        'googleReviewsApiKey' => $google_reviews_api_key,
    ]);
    wp_script_add_data('goody-main', 'defer', true);

    if (goody_should_enqueue_reservation_assets()) {
        $reservation_css_asset = goody_get_asset_path('assets/css/reservation.css');
        $reservation_js_asset = goody_get_asset_path('assets/js/reservation.js');

        wp_enqueue_style('goody-reservation', GOODY_THEME_URI . '/' . $reservation_css_asset, ['goody-main'], GOODY_THEME_VERSION);
        wp_enqueue_script('goody-reservation', GOODY_THEME_URI . '/' . $reservation_js_asset, ['goody-main'], GOODY_THEME_VERSION, true);
        wp_localize_script('goody-reservation', 'goodyReservationTheme', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('goody_nonce'),
            'statusLookupUrl' => goody_get_reservation_status_lookup_page_url(),
            'currencySymbol' => function_exists('get_woocommerce_currency_symbol') ? get_woocommerce_currency_symbol() : '$',
            'locale' => str_replace('_', '-', function_exists('determine_locale') ? determine_locale() : get_locale()),
        ]);
        wp_script_add_data('goody-reservation', 'defer', true);
    }
}
add_action('wp_enqueue_scripts', 'goody_enqueue_assets');

function goody_optimize_frontend_assets() {
    if (is_admin()) {
        return;
    }

    if (! is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }

    if (function_exists('is_woocommerce') && function_exists('is_cart') && function_exists('is_checkout') && function_exists('is_account_page')) {
        $needs_wc_assets = is_woocommerce() || is_cart() || is_checkout() || is_account_page();
        if (! $needs_wc_assets) {
            wp_dequeue_style('woocommerce-general');
            wp_dequeue_style('woocommerce-layout');
            wp_dequeue_style('woocommerce-smallscreen');
            wp_dequeue_style('wc-blocks-style');
            wp_dequeue_style('wc-blocks-vendors-style');
            wp_dequeue_style('woocommerce-inline');

            wp_dequeue_script('jquery-blockui');
            wp_dequeue_script('js-cookie');
            wp_dequeue_script('woocommerce');
            wp_dequeue_script('wc-add-to-cart');
            wp_dequeue_script('wc-cart-fragments');
            wp_dequeue_script('sourcebuster-js');
            wp_dequeue_script('sourcebuster');
            wp_dequeue_script('wc-order-attribution');
        }
    }

    wp_dequeue_script('wp-emoji-release');
}
add_action('wp_enqueue_scripts', 'goody_optimize_frontend_assets', 99);

function goody_add_resource_hints($urls, $relation_type) {
    if (! in_array($relation_type, ['preconnect', 'dns-prefetch'], true)) {
        return $urls;
    }

    $hints = ['//fonts.gstatic.com'];

    $maps_api_key = goody_extract_google_maps_api_key(goody_get_option('integrations_maps_api_key', ''));
    if ($maps_api_key === '') {
        $maps_api_key = goody_extract_google_maps_api_key(goody_get_option('map_script_embed', ''));
    }
    if ($maps_api_key !== '' && is_front_page()) {
        $hints[] = '//maps.googleapis.com';
        $hints[] = '//maps.gstatic.com';
    }

    foreach ($hints as $hint) {
        if (in_array($hint, $urls, true)) {
            continue;
        }
        $urls[] = $hint;
    }

    return $urls;
}
add_filter('wp_resource_hints', 'goody_add_resource_hints', 10, 2);

function goody_async_google_fonts_tag($html, $handle, $href, $media) {
    if ($handle !== 'goody-fonts') {
        return $html;
    }

    return "<link rel='preload' as='style' id='goody-fonts-css' href='" . esc_url($href) . "' media='all' onload=\"this.onload=null;this.rel='stylesheet'\" /><noscript><link rel='stylesheet' id='goody-fonts-css-noscript' href='" . esc_url($href) . "' media='all' /></noscript>";
}
add_filter('style_loader_tag', 'goody_async_google_fonts_tag', 10, 4);

function goody_print_lcp_image_preloads() {
    if (! is_front_page()) {
        return;
    }

    $preloads = [];

    $hero_image_id = absint(goody_get_option('hero_image'));
    if ($hero_image_id > 0) {
        $hero_image_mobile_url = wp_get_attachment_image_url($hero_image_id, 'goody-hero-mobile');
        $hero_image_url = wp_get_attachment_image_url($hero_image_id, 'goody-hero');
        if (is_string($hero_image_mobile_url) && $hero_image_mobile_url !== '') {
            $preloads[] = [
                'url' => $hero_image_mobile_url,
                'media' => '(max-width: 860px)',
            ];
        }
        if (is_string($hero_image_url) && $hero_image_url !== '') {
            $preloads[] = [
                'url' => $hero_image_url,
                'media' => '(min-width: 861px)',
            ];
        }
    }

    $seen = [];
    foreach ($preloads as $preload) {
        if (! is_array($preload) || empty($preload['url'])) {
            continue;
        }

        $url = (string) $preload['url'];
        $media = trim((string) ($preload['media'] ?? ''));
        $dedupe_key = $url . '|' . $media;
        if (isset($seen[$dedupe_key])) {
            continue;
        }
        $seen[$dedupe_key] = true;

        $media_attr = $media !== '' ? ' media="' . esc_attr($media) . '"' : '';
        echo '<link rel="preload" as="image" href="' . esc_url($url) . '" fetchpriority="high"' . $media_attr . '>' . "\n";
    }
}
add_action('wp_head', 'goody_print_lcp_image_preloads', 2);

function goody_admin_assets($hook) {
    $is_theme_screen = strpos((string) $hook, 'goody-theme') !== false;
    $is_post_screen = in_array($hook, ['post.php', 'post-new.php'], true);
    $is_menu_category_screen = in_array($hook, ['edit-tags.php', 'term.php'], true)
        && isset($_GET['taxonomy'])
        && sanitize_key(wp_unslash($_GET['taxonomy'])) === 'menu_category';

    if (! $is_theme_screen && ! $is_post_screen && ! $is_menu_category_screen) {
        return;
    }

    wp_enqueue_media();
    $admin_css_asset = goody_get_asset_path('assets/css/admin.css');
    $admin_js_asset = goody_get_asset_path('assets/js/admin.js');

    wp_enqueue_style('goody-admin-style', GOODY_THEME_URI . '/' . $admin_css_asset, [], GOODY_THEME_VERSION);
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_script('goody-admin', GOODY_THEME_URI . '/' . $admin_js_asset, ['jquery', 'jquery-ui-sortable'], GOODY_THEME_VERSION, true);
}
add_action('admin_enqueue_scripts', 'goody_admin_assets');
