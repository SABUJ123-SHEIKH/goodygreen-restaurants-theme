<?php

function goody_filter_document_title($parts) {
    if (is_front_page()) {
        $parts['title'] = goody_get_option('seo_home_meta_title', get_bloginfo('name'));
    }

    return $parts;
}
add_filter('document_title_parts', 'goody_filter_document_title');

function goody_print_home_meta_description() {
    if (! is_front_page()) {
        return;
    }

    $description = goody_get_option('seo_home_meta_description', get_bloginfo('description'));
    if ($description) {
        echo '<meta name="description" content="' . esc_attr($description) . '">';
    }
}
add_action('wp_head', 'goody_print_home_meta_description', 5);

function goody_print_local_business_schema() {
    if (! is_front_page()) {
        return;
    }

    $social_links = goody_get_social_links();
    $same_as = [];
    foreach ($social_links as $social) {
        $same_as[] = esc_url_raw($social['url']);
    }

    $hours = [];
    foreach (goody_get_business_hours() as $item) {
        if ($item['day'] && $item['open'] && $item['close']) {
            $hours[] = $item['day'] . ' ' . $item['open'] . '-' . $item['close'];
        }
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'Restaurant',
        'name' => goody_get_option('restaurant_name', get_bloginfo('name')),
        'description' => goody_get_option('seo_home_meta_description', ''),
        'url' => home_url('/'),
        'telephone' => goody_get_option('contact_phone', ''),
        'email' => goody_get_option('contact_email', ''),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => goody_get_option('contact_address', ''),
        ],
        'openingHours' => $hours,
        'sameAs' => $same_as,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'goody_print_local_business_schema', 30);
