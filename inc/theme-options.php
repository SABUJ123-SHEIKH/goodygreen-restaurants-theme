<?php

function goody_register_theme_menu() {
    add_menu_page(
        __('Goody Green Settings', 'goody'),
        __('Goody Green', 'goody'),
        'edit_theme_options',
        'goody-theme',
        'goody_render_theme_options_page',
        'dashicons-store',
        2
    );
}
add_action('admin_menu', 'goody_register_theme_menu');

function goody_register_theme_submenus() {
    add_submenu_page(
        'goody-theme',
        __('Goody Green Theme Settings', 'goody'),
        __('Theme Settings', 'goody'),
        'edit_theme_options',
        'goody-theme',
        'goody_render_theme_options_page'
    );

    add_submenu_page(
        'goody-theme',
        __('Form Submissions', 'goody'),
        __('Form Submissions', 'goody'),
        'edit_theme_options',
        'goody-theme-submissions',
        'goody_render_form_submissions_page'
    );
}
add_action('admin_menu', 'goody_register_theme_submenus');

function goody_ensure_theme_settings_first_submenu() {
    global $submenu;

    if (! isset($submenu['goody-theme']) || ! is_array($submenu['goody-theme'])) {
        return;
    }

    $theme_settings_key = null;
    foreach ($submenu['goody-theme'] as $index => $item) {
        if (! empty($item[2]) && $item[2] === 'goody-theme') {
            $theme_settings_key = $index;
            break;
        }
    }

    if ($theme_settings_key === null || $theme_settings_key === 0) {
        return;
    }

    $theme_settings_item = $submenu['goody-theme'][$theme_settings_key];
    unset($submenu['goody-theme'][$theme_settings_key]);
    array_unshift($submenu['goody-theme'], $theme_settings_item);
}
add_action('admin_menu', 'goody_ensure_theme_settings_first_submenu', 999);

function goody_get_settings_sections() {
    return [
        'general' => __('General', 'goody'),
        'design' => __('Design', 'goody'),
        'header' => __('Header', 'goody'),
        'hero' => __('Hero', 'goody'),
        'menu' => __('Menu', 'goody'),
        'offers' => __('Offers', 'goody'),
        'delivery' => __('Delivery', 'goody'),
        'reservation' => __('Reservation', 'goody'),
        'about' => __('About', 'goody'),
        'reviews' => __('Reviews', 'goody'),
        'events' => __('Events', 'goody'),
        'blog' => __('Blog', 'goody'),
        'account' => __('Account', 'goody'),
        'newsletter' => __('Newsletter', 'goody'),
        'contact' => __('Contact', 'goody'),
        'footer' => __('Footer', 'goody'),
        'seo' => __('SEO', 'goody'),
        'integrations' => __('Integrations', 'goody'),
    ];
}

function goody_get_settings_fields() {
    return [
        'general' => [
            ['key' => 'restaurant_name', 'label' => __('Restaurant Name', 'goody'), 'type' => 'text'],
            ['key' => 'restaurant_tagline', 'label' => __('Restaurant Tagline', 'goody'), 'type' => 'text'],
            ['key' => 'restaurant_logo_alt', 'label' => __('Logo Alt Text', 'goody'), 'type' => 'text'],
            ['key' => 'restaurant_logo', 'label' => __('Restaurant Logo (Theme Option)', 'goody'), 'type' => 'media'],
        ],
        'design' => [
            [
                'key' => 'design_color_preset',
                'label' => __('Color Preset', 'goody'),
                'type' => 'select',
                'options' => [
                    'custom' => __('Custom (Keep Manual Colors)', 'goody'),
                    'trusted_professional' => __('Trusted Professional (Default System Preset)', 'goody'),
                ],
                'description' => __('Choose preset and save. Custom keeps manual colors.', 'goody'),
            ],
            [
                'key' => 'design_auto_harmony',
                'label' => __('Auto Apply Theme Harmony From Primary', 'goody'),
                'type' => 'checkbox',
                'description' => __('When enabled, changing one primary color auto-adjusts full theme token balance.', 'goody'),
            ],
            ['key' => 'token_color_primary', 'label' => __('Primary Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_primary_2', 'label' => __('Secondary Green Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_primary_hover', 'label' => __('Primary Hover Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_button_text', 'label' => __('Button Text Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_bg_deep', 'label' => __('Deep Background Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_bg', 'label' => __('Background Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_bg_soft', 'label' => __('Soft Background Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_section', 'label' => __('Section Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_card', 'label' => __('Card Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_card_soft', 'label' => __('Soft Card Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_surface', 'label' => __('Surface Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_text', 'label' => __('Text Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_muted', 'label' => __('Muted Color', 'goody'), 'type' => 'color'],
            ['key' => 'token_color_border', 'label' => __('Border Color CSS Value', 'goody'), 'type' => 'text', 'description' => __('Use hex or rgba(), for example rgba(142, 190, 152, 0.22).', 'goody')],
            ['key' => 'token_color_shadow', 'label' => __('Shadow Color CSS Value', 'goody'), 'type' => 'text', 'description' => __('Use rgba(), for example rgba(0, 0, 0, 0.45).', 'goody')],
            ['key' => 'token_fonts_url', 'label' => __('Fonts Stylesheet URL', 'goody'), 'type' => 'url', 'description' => __('Optional Google Fonts or hosted font CSS URL loaded by the theme.', 'goody')],
            ['key' => 'token_font_heading', 'label' => __('Heading Font Family', 'goody'), 'type' => 'text'],
            ['key' => 'token_font_body', 'label' => __('Body Font Family', 'goody'), 'type' => 'text'],
            ['key' => 'token_font_accent', 'label' => __('Accent Font Family', 'goody'), 'type' => 'text'],
            ['key' => 'token_shadow', 'label' => __('Shadow Token', 'goody'), 'type' => 'text'],
            ['key' => 'token_space_section', 'label' => __('Section Spacing Token', 'goody'), 'type' => 'text'],
            ['key' => 'token_radius_sm', 'label' => __('Small Radius Token', 'goody'), 'type' => 'text'],
            ['key' => 'token_radius', 'label' => __('Border Radius Token', 'goody'), 'type' => 'text'],
            ['key' => 'token_radius_lg', 'label' => __('Large Radius Token', 'goody'), 'type' => 'text'],
            ['key' => 'token_container', 'label' => __('Container Width Token', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_button_color', 'label' => __('Reservation Button Color', 'goody'), 'type' => 'color'],
            ['key' => 'reservation_accent_color', 'label' => __('Reservation Accent Color', 'goody'), 'type' => 'color'],
            ['key' => 'reservation_card_radius', 'label' => __('Reservation Card Radius', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_font_family', 'label' => __('Reservation Font Family', 'goody'), 'type' => 'text'],
        ],
        'header' => [
            ['key' => 'header_sticky', 'label' => __('Enable Sticky Header', 'goody'), 'type' => 'checkbox'],
            ['key' => 'header_show_search', 'label' => __('Show Header Search', 'goody'), 'type' => 'checkbox'],
            ['key' => 'header_search_placeholder', 'label' => __('Search Placeholder', 'goody'), 'type' => 'text'],
            ['key' => 'header_enable_mobile_bottom_nav', 'label' => __('Enable Mobile Bottom Navigation', 'goody'), 'type' => 'checkbox'],
            ['key' => 'header_enable_bottom_cta_bar', 'label' => __('Enable Bottom CTA Bar', 'goody'), 'type' => 'checkbox'],
            ['key' => 'header_enable_dropdown_menu', 'label' => __('Enable Dropdown Menu', 'goody'), 'type' => 'checkbox'],
            [
                'key' => 'header_enable_mega_menu',
                'label' => __('Enable Mega Menu', 'goody'),
                'type' => 'checkbox',
                'depends_on' => 'header_enable_dropdown_menu',
                'depends_value' => '1',
            ],
        ],
        'hero' => [
            [
                'key' => 'hero_background_type',
                'label' => __('Hero Background Type', 'goody'),
                'type' => 'select',
                'options' => [
                    'image' => __('Image', 'goody'),
                    'video' => __('Video', 'goody'),
                ],
            ],
            [
                'key' => 'hero_image',
                'label' => __('Hero Image', 'goody'),
                'type' => 'media',
                'depends_on' => 'hero_background_type',
                'depends_value' => 'image',
            ],
            [
                'key' => 'hero_video_file',
                'label' => __('Hero Video File (Upload)', 'goody'),
                'type' => 'media',
                'depends_on' => 'hero_background_type',
                'depends_value' => 'video',
            ],
            [
                'key' => 'hero_video_url',
                'label' => __('Hero Video External URL', 'goody'),
                'type' => 'url',
                'depends_on' => 'hero_background_type',
                'depends_value' => 'video',
            ],
            ['key' => 'hero_overlay_strength', 'label' => __('Overlay Strength (%)', 'goody'), 'type' => 'number'],
            ['key' => 'hero_heading', 'label' => __('Hero Heading', 'goody'), 'type' => 'text'],
            ['key' => 'hero_highlight_text', 'label' => __('Hero Highlight Text', 'goody'), 'type' => 'text', 'description' => __('Optional word or phrase to render in script/accent style inside the hero heading.', 'goody')],
            ['key' => 'hero_subheading', 'label' => __('Hero Subheading', 'goody'), 'type' => 'textarea'],
            ['key' => 'hero_concept_tagline', 'label' => __('Hero Concept Tagline', 'goody'), 'type' => 'text'],
            ['key' => 'hero_primary_text', 'label' => __('Primary CTA Text', 'goody'), 'type' => 'text'],
            ['key' => 'hero_primary_url', 'label' => __('Primary CTA URL', 'goody'), 'type' => 'url'],
            ['key' => 'hero_secondary_text', 'label' => __('Secondary CTA Text', 'goody'), 'type' => 'text'],
            ['key' => 'hero_secondary_url', 'label' => __('Secondary CTA URL', 'goody'), 'type' => 'url'],
        ],
        'menu' => [
            ['key' => 'menu_section_title', 'label' => __('Menu Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'menu_section_text', 'label' => __('Menu Section Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'menu_page_title', 'label' => __('Menu Page Title', 'goody'), 'type' => 'text'],
            ['key' => 'menu_show_unavailable', 'label' => __('Show Unavailable Dishes', 'goody'), 'type' => 'checkbox'],
            ['key' => 'menu_items_count', 'label' => __('Menu Items Per Load', 'goody'), 'type' => 'number'],
        ],
        'offers' => [
            ['key' => 'offers_section_title', 'label' => __('Offers Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'offers_section_text', 'label' => __('Offers Section Text', 'goody'), 'type' => 'textarea'],
        ],
        'delivery' => [
            ['key' => 'order_section_title', 'label' => __('Order Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'order_section_text', 'label' => __('Order Section Text', 'goody'), 'type' => 'textarea'],
            [
                'key' => 'delivery_data_source',
                'label' => __('Delivery Link Source', 'goody'),
                'type' => 'select',
                'options' => [
                    'manual' => __('Manual URLs', 'goody'),
                    'api' => __('API Endpoints', 'goody'),
                ],
            ],
            ['key' => 'glovo_url', 'label' => __('Glovo URL', 'goody'), 'type' => 'url'],
            ['key' => 'ubereats_url', 'label' => __('UberEats URL', 'goody'), 'type' => 'url'],
            ['key' => 'deliveroo_url', 'label' => __('Deliveroo URL', 'goody'), 'type' => 'url'],
            ['key' => 'custom_order_url', 'label' => __('Custom Order URL', 'goody'), 'type' => 'url'],
            ['key' => 'custom_order_text', 'label' => __('Custom Order Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'glovo_api_url', 'label' => __('Glovo API Endpoint URL', 'goody'), 'type' => 'url'],
            ['key' => 'glovo_api_token', 'label' => __('Glovo API Token / Key', 'goody'), 'type' => 'text'],
            [
                'key' => 'ubereats_environment',
                'label' => __('UberEats Environment', 'goody'),
                'type' => 'select',
                'options' => [
                    'sandbox' => __('Sandbox (test-api.uber.com)', 'goody'),
                    'production' => __('Production (api.uber.com)', 'goody'),
                ],
            ],
            [
                'key' => 'ubereats_api_base_url',
                'label' => __('UberEats API Base URL (Optional Override)', 'goody'),
                'type' => 'url',
                'description' => __('Leave empty to auto-use environment base URL.', 'goody'),
            ],
            ['key' => 'ubereats_api_url', 'label' => __('UberEats API Endpoint URL', 'goody'), 'type' => 'url'],
            [
                'key' => 'ubereats_api_token',
                'label' => __('UberEats API Token / Key', 'goody'),
                'type' => 'text',
                'description' => __('Use Uber access token here (not client ID). Leave empty if using OAuth Client ID + Secret fields below.', 'goody'),
            ],
            ['key' => 'ubereats_client_id', 'label' => __('UberEats OAuth Client ID (Optional)', 'goody'), 'type' => 'text'],
            ['key' => 'ubereats_client_secret', 'label' => __('UberEats OAuth Client Secret (Optional)', 'goody'), 'type' => 'text'],
            [
                'key' => 'ubereats_oauth_scope',
                'label' => __('UberEats OAuth Scope (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Default: eats.deliveries', 'goody'),
            ],
            [
                'key' => 'ubereats_oauth_token_url',
                'label' => __('UberEats OAuth Token URL (Optional Override)', 'goody'),
                'type' => 'url',
                'description' => __('Leave empty to auto-use sandbox-login.uber.com for sandbox, auth.uber.com for production.', 'goody'),
            ],
            ['key' => 'deliveroo_api_url', 'label' => __('Deliveroo API Endpoint URL', 'goody'), 'type' => 'url'],
            ['key' => 'deliveroo_api_token', 'label' => __('Deliveroo API Token / Key', 'goody'), 'type' => 'text'],
            ['key' => 'delivery_auto_create_enabled', 'label' => __('Auto Create Delivery Order After WooCommerce Order', 'goody'), 'type' => 'checkbox'],
            [
                'key' => 'reservation_delivery_providers',
                'label' => __('WooCommerce Delivery Providers (Custom)', 'goody'),
                'type' => 'textarea',
                'description' => __('WooCommerce provider list only. One provider per line using key|Label|enabled. Example: goody|Goody|1 or uber|UberEats|0', 'goody'),
            ],
            [
                'key' => 'reservation_default_delivery_provider',
                'label' => __('WooCommerce Default Delivery Provider Key', 'goody'),
                'type' => 'text',
                'description' => __('WooCommerce only. Must match one provider key from the custom list. Example: goody', 'goody'),
            ],
            [
                'key' => 'delivery_auto_provider',
                'label' => __('Auto Delivery Provider', 'goody'),
                'type' => 'select',
                'options' => [
                    'ubereats' => __('UberEats', 'goody'),
                    'glovo' => __('Glovo', 'goody'),
                    'deliveroo' => __('Deliveroo', 'goody'),
                    'custom' => __('Custom', 'goody'),
                ],
            ],
            [
                'key' => 'delivery_mapping_profile',
                'label' => __('Delivery Mapping Profile', 'goody'),
                'type' => 'select',
                'options' => [
                    'auto' => __('Auto (Use Provider)', 'goody'),
                    'ubereats' => __('UberEats Preset', 'goody'),
                    'glovo' => __('Glovo Preset', 'goody'),
                    'deliveroo' => __('Deliveroo Preset', 'goody'),
                    'custom' => __('Custom (Only Manual Paths)', 'goody'),
                ],
                'description' => __('When JSON path fields are empty, preset mapping tries common provider response fields.', 'goody'),
            ],
            [
                'key' => 'ubereats_order_create_api_url',
                'label' => __('UberEats Create Order API URL (Optional Override)', 'goody'),
                'type' => 'url',
                'description' => __('Leave empty to auto-use /v1/eats/deliveries/orders based on selected environment.', 'goody'),
            ],
            ['key' => 'glovo_order_create_api_url', 'label' => __('Glovo Create Order API URL', 'goody'), 'type' => 'url'],
            ['key' => 'deliveroo_order_create_api_url', 'label' => __('Deliveroo Create Order API URL', 'goody'), 'type' => 'url'],
            ['key' => 'custom_order_create_api_url', 'label' => __('Custom Create Order API URL', 'goody'), 'type' => 'url'],
            ['key' => 'custom_order_create_api_token', 'label' => __('Custom Create Order API Token', 'goody'), 'type' => 'text'],
            [
                'key' => 'delivery_webhook_secret',
                'label' => __('Delivery Webhook Secret (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Webhook URL: /wp-json/goody/v1/delivery/webhook . If set, provider must send this secret in x-goody-webhook-secret header, Authorization Bearer, or ?secret= query.', 'goody'),
            ],
            [
                'key' => 'delivery_create_response_external_id_path',
                'label' => __('Create Response External ID Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.order.id', 'goody'),
            ],
            [
                'key' => 'delivery_create_response_tracking_url_path',
                'label' => __('Create Response Tracking URL Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.tracking.url', 'goody'),
            ],
            [
                'key' => 'delivery_create_response_tracking_api_url_path',
                'label' => __('Create Response Tracking API URL Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.tracking.api_url', 'goody'),
            ],
            [
                'key' => 'delivery_tracking_response_url_path',
                'label' => __('Tracking Response URL Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path for provider tracking page URL', 'goody'),
            ],
            [
                'key' => 'delivery_tracking_response_status_path',
                'label' => __('Tracking Response Status Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.status.current', 'goody'),
            ],
            [
                'key' => 'delivery_tracking_response_stage_path',
                'label' => __('Tracking Response Stage Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.status.stage', 'goody'),
            ],
            [
                'key' => 'delivery_tracking_response_eta_path',
                'label' => __('Tracking Response ETA Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path, e.g. data.status.eta', 'goody'),
            ],
            [
                'key' => 'delivery_tracking_response_timeline_path',
                'label' => __('Tracking Response Timeline Path (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('JSON path to events array, e.g. data.timeline.events', 'goody'),
            ],
            ['key' => 'tracking_enabled', 'label' => __('Enable Order Tracking Block', 'goody'), 'type' => 'checkbox'],
            [
                'key' => 'tracking_update_roles',
                'label' => __('Tracking Update Roles', 'goody'),
                'type' => 'roles_multiselect',
                'description' => __('Select which user roles can update reservation/order tracking. Leave empty to use default access rules.', 'goody'),
            ],
            ['key' => 'tracking_title', 'label' => __('Tracking Title', 'goody'), 'type' => 'text'],
            ['key' => 'tracking_description', 'label' => __('Tracking Description', 'goody'), 'type' => 'textarea'],
            ['key' => 'tracking_url', 'label' => __('Tracking URL', 'goody'), 'type' => 'url'],
            ['key' => 'tracking_embed', 'label' => __('Tracking Embed Code', 'goody'), 'type' => 'code'],
        ],
        'reservation' => [
            ['key' => 'reservation_section_title', 'label' => __('Reservation Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_section_text', 'label' => __('Reservation Section Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_section_image', 'label' => __('Reservation Section Image', 'goody'), 'type' => 'media'],
            ['key' => 'reservation_page_title', 'label' => __('Reservation Page Title', 'goody'), 'type' => 'text'],
            [
                'key' => 'reservation_platform',
                'label' => __('Reservation Platform', 'goody'),
                'type' => 'select',
                'options' => [
                    'resy' => __('Resy', 'goody'),
                    'bookatable' => __('Bookatable', 'goody'),
                    'custom' => __('Custom', 'goody'),
                ],
            ],
            ['key' => 'reservation_resy_url', 'label' => __('Resy URL', 'goody'), 'type' => 'url'],
            ['key' => 'reservation_bookatable_url', 'label' => __('Bookatable URL', 'goody'), 'type' => 'url'],
            ['key' => 'reservation_custom_url', 'label' => __('Custom Reservation URL', 'goody'), 'type' => 'url'],
            ['key' => 'reservation_status_page_url', 'label' => __('Reservation Status Page URL', 'goody'), 'type' => 'url'],
            ['key' => 'reservation_button_text', 'label' => __('Reservation Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_embed', 'label' => __('Reservation Embed Code', 'goody'), 'type' => 'code'],
            ['key' => 'reservation_status_title', 'label' => __('Reservation Status Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_status_text', 'label' => __('Reservation Status Description', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_success_message', 'label' => __('Reservation Success Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_booking_notice', 'label' => __('Booking Intro Notice', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_error_message', 'label' => __('Reservation Error Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_pickup_warning', 'label' => __('Pickup Warning Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_delivery_warning', 'label' => __('Delivery Warning Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_cash_warning', 'label' => __('Cash Payment Warning Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_dine_in_note', 'label' => __('Dine In Note', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_step_counter_prefix', 'label' => __('Step Counter Prefix', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_1', 'label' => __('Step 1 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_2', 'label' => __('Step 2 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_3', 'label' => __('Step 3 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_4', 'label' => __('Step 4 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_5', 'label' => __('Step 5 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_step_title_6', 'label' => __('Step 6 Title', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_order_type_label_dine_in', 'label' => __('Order Type Label (Dine In)', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_order_type_label_pickup', 'label' => __('Order Type Label (Pickup)', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_order_type_label_delivery', 'label' => __('Order Type Label (Delivery)', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_next_button_text', 'label' => __('Next Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_back_button_text', 'label' => __('Back Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'reservation_submit_button_text', 'label' => __('Submit Button Text', 'goody'), 'type' => 'text'],
            [
                'key' => 'reservation_deposit_percentage',
                'label' => __('Advance Payment Percentage', 'goody'),
                'type' => 'number',
                'min' => 10,
                'max' => 100,
                'validate' => 'number_range',
            ],
            [
                'key' => 'reservation_advance_days',
                'label' => __('Advance Booking Limit (Days)', 'goody'),
                'type' => 'number',
                'min' => 1,
                'max' => 365,
                'validate' => 'number_range',
            ],
            [
                'key' => 'reservation_cutoff_minutes',
                'label' => __('Default Cut-off Time (Minutes)', 'goody'),
                'type' => 'number',
                'min' => 0,
                'max' => 1440,
                'validate' => 'number_range',
            ],
            [
                'key' => 'reservation_free_delivery_threshold',
                'label' => __('Global Free Delivery Minimum', 'goody'),
                'type' => 'number',
            ],
            ['key' => 'reservation_disabled_dates', 'label' => __('Disabled Dates', 'goody'), 'type' => 'textarea', 'description' => __('Enter one date per line in YYYY-MM-DD format.', 'goody')],
            ['key' => 'reservation_holiday_message', 'label' => __('Holiday / Off-day Message', 'goody'), 'type' => 'textarea'],
            ['key' => 'reservation_tables_layout', 'label' => __('Table Layout', 'goody'), 'type' => 'textarea', 'description' => __('One table per line: table_id|label|location|capacity. Example: T1|Table 1|Indoor Window|4', 'goody')],
            ['key' => 'reservation_max_bookings_per_day', 'label' => __('Max Booking Per Day', 'goody'), 'type' => 'number'],
            ['key' => 'reservation_lock_booked_slots', 'label' => __('Disable Reserved Date + Time Slots', 'goody'), 'type' => 'checkbox', 'description' => __('When enabled, a date and time slot becomes unavailable after one non-cancelled reservation is created.', 'goody')],
            ['key' => 'reservation_enable_dine_in', 'label' => __('Enable Dine In', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_pickup', 'label' => __('Enable Pickup', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_delivery', 'label' => __('Enable Delivery', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_menu_step', 'label' => __('Enable Menu Step', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_min_order_dine_in', 'label' => __('Minimum Order for Dine In', 'goody'), 'type' => 'number'],
            ['key' => 'reservation_min_order_pickup', 'label' => __('Minimum Order for Pickup', 'goody'), 'type' => 'number'],
            ['key' => 'reservation_min_order_delivery', 'label' => __('Minimum Order for Delivery', 'goody'), 'type' => 'number'],
            ['key' => 'reservation_enable_full_payment', 'label' => __('Enable Full Payment', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_advance_payment', 'label' => __('Enable Advance Payment', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_cash_payment', 'label' => __('Enable Cash Payment', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_auto_create_wc_order', 'label' => __('Auto Create WooCommerce Order', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_name_required', 'label' => __('Name Field Required', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_phone_required', 'label' => __('Phone Field Required', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_guests_required', 'label' => __('Guest Field Required', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_address_enabled', 'label' => __('Show Address Field', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_address_required', 'label' => __('Address Field Required', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_note_enabled', 'label' => __('Show Note Field', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_customer_note_required', 'label' => __('Note Field Required', 'goody'), 'type' => 'checkbox'],
        ],
        'about' => [
            ['key' => 'about_story_title', 'label' => __('About Story Title', 'goody'), 'type' => 'text'],
            ['key' => 'about_story_text', 'label' => __('About Story Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'about_mission_title', 'label' => __('Mission Title', 'goody'), 'type' => 'text'],
            ['key' => 'about_mission_text', 'label' => __('Mission Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'about_vision_title', 'label' => __('Vision Title', 'goody'), 'type' => 'text'],
            ['key' => 'about_vision_text', 'label' => __('Vision Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'about_featured_image', 'label' => __('About Featured Image', 'goody'), 'type' => 'media'],
            ['key' => 'about_interior_gallery', 'label' => __('Interior Gallery (Multiple)', 'goody'), 'type' => 'gallery'],
            ['key' => 'about_exterior_gallery', 'label' => __('Exterior Gallery (Multiple)', 'goody'), 'type' => 'gallery'],
            [
                'key' => 'gallery_zone_items_count',
                'label' => __('Gallery Zone Image Count', 'goody'),
                'type' => 'number',
                'description' => __('Set 0 to show all uploaded gallery images on homepage gallery zone.', 'goody'),
                'min' => 0,
                'max' => 60,
                'validate' => 'number_range',
            ],
        ],
        'reviews' => [
            ['key' => 'reviews_section_title', 'label' => __('Reviews Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'reviews_section_text', 'label' => __('Reviews Section Text', 'goody'), 'type' => 'textarea'],
            [
                'key' => 'reviews_layout',
                'label' => __('Reviews Layout', 'goody'),
                'type' => 'select',
                'options' => [
                    'grid' => __('Grid', 'goody'),
                    'carousel' => __('Carousel', 'goody'),
                ],
            ],
            [
                'key' => 'reviews_api_provider',
                'label' => __('Reviews API Provider', 'goody'),
                'type' => 'select',
                'options' => [
                    'auto' => __('Auto Detect', 'goody'),
                    'google' => __('Google Places API', 'goody'),
                    'serpapi' => __('SerpApi Google Maps', 'goody'),
                    'trustpilot' => __('Trustpilot / External URL', 'goody'),
                    'custom' => __('Custom Reviews API URL', 'goody'),
                ],
            ],
            ['key' => 'google_reviews_mock_mode', 'label' => __('Enable Mock Reviews (Test Mode)', 'goody'), 'type' => 'checkbox'],
            [
                'key' => 'google_reviews_place_id',
                'label' => __('Google Place ID', 'goody'),
                'type' => 'text',
                'description' => __('Paste Place ID (ChIJ...), or CID/Maps link if needed. Live Google reviews also require Google Reviews API key in Integrations.', 'goody'),
            ],
            ['key' => 'google_reviews_count', 'label' => __('Google Reviews Count', 'goody'), 'type' => 'number'],
            [
                'key' => 'reviews_default_rating_filter',
                'label' => __('Default Reviews Star Filter', 'goody'),
                'type' => 'select',
                'options' => [
                    '0' => __('All reviews', 'goody'),
                    '5' => __('5 stars only', 'goody'),
                    '4' => __('4 stars only', 'goody'),
                    '3' => __('3 stars only', 'goody'),
                    '2' => __('2 stars only', 'goody'),
                    '1' => __('1 star only', 'goody'),
                ],
                'description' => __('Visitors can still switch filters on the reviews section.', 'goody'),
            ],
            [
                'key' => 'google_review_submit_url',
                'label' => __('Google Write Review URL', 'goody'),
                'type' => 'url',
                'description' => __('Optional. Paste your Google review link. If empty, the theme builds one from the Place ID when possible.', 'goody'),
            ],
            [
                'key' => 'reviews_google_handoff_after_submit',
                'label' => __('Send Review Submitters to Google', 'goody'),
                'type' => 'checkbox',
                'description' => __('Google does not let websites create customer reviews by API. This saves the local draft, then opens Google so the guest can publish there.', 'goody'),
            ],
            ['key' => 'trustpilot_api_url', 'label' => __('Trustpilot Reviews API URL', 'goody'), 'type' => 'url'],
            ['key' => 'custom_reviews_api_url', 'label' => __('Custom Reviews API URL', 'goody'), 'type' => 'url'],
        ],
        'events' => [
            ['key' => 'events_section_title', 'label' => __('Events Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'events_section_text', 'label' => __('Events Section Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'events_show_past', 'label' => __('Include Past Events', 'goody'), 'type' => 'checkbox'],
        ],
        'blog' => [
            [
                'key' => 'news_enabled',
                'label' => __('Enable Blog/News Section', 'goody'),
                'type' => 'checkbox',
                'description' => __('Turn on to show blog/news cards on homepage. Keep off if you do not use posts.', 'goody'),
            ],
            [
                'key' => 'news_section_title',
                'label' => __('News Section Title', 'goody'),
                'type' => 'text',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'news_eyebrow_text',
                'label' => __('News Eyebrow Label', 'goody'),
                'type' => 'text',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
                'description' => __('Small label shown above the section heading.', 'goody'),
            ],
            [
                'key' => 'news_section_text',
                'label' => __('News Section Text', 'goody'),
                'type' => 'textarea',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'news_posts_count',
                'label' => __('Number of Posts', 'goody'),
                'type' => 'number',
                'min' => 1,
                'max' => 8,
                'validate' => 'number_range',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
                'description' => __('Allowed range: 1 to 8 posts.', 'goody'),
            ],
            [
                'key' => 'news_button_text',
                'label' => __('News Button Text', 'goody'),
                'type' => 'text',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'news_read_more_text',
                'label' => __('Read More Button Text', 'goody'),
                'type' => 'text',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'news_button_url',
                'label' => __('News Button URL', 'goody'),
                'type' => 'url',
                'placeholder' => 'https://example.com/news',
                'validate' => 'url_optional',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
                'description' => __('Use full URL with http:// or https://', 'goody'),
            ],
            [
                'key' => 'news_empty_title',
                'label' => __('Empty State Title', 'goody'),
                'type' => 'text',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'news_empty_text',
                'label' => __('Empty State Text', 'goody'),
                'type' => 'textarea',
                'depends_on' => 'news_enabled',
                'depends_value' => '1',
            ],
        ],
        'account' => [
            [
                'key' => 'account_enabled',
                'label' => __('Enable Account/Profile Placeholder', 'goody'),
                'type' => 'checkbox',
                'description' => __('Turn on to display account placeholder block and action buttons on homepage.', 'goody'),
            ],
            [
                'key' => 'account_section_title',
                'label' => __('Account Section Title', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_eyebrow_text',
                'label' => __('Account Eyebrow Label', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_section_text',
                'label' => __('Account Section Text', 'goody'),
                'type' => 'textarea',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_placeholder_title',
                'label' => __('Placeholder Card Title', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_placeholder_text',
                'label' => __('Placeholder Card Text', 'goody'),
                'type' => 'textarea',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_feature_text_1',
                'label' => __('Feature Line 1', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_feature_text_2',
                'label' => __('Feature Line 2', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_feature_text_3',
                'label' => __('Feature Line 3', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_actions_title',
                'label' => __('Actions Card Title', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_login_button_text',
                'label' => __('Login Button Text', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_register_button_text',
                'label' => __('Register Button Text', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_profile_button_text',
                'label' => __('Profile Button Text', 'goody'),
                'type' => 'text',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_login_url',
                'label' => __('Login URL', 'goody'),
                'type' => 'url',
                'placeholder' => 'https://example.com/login',
                'validate' => 'url_optional',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_register_url',
                'label' => __('Register URL', 'goody'),
                'type' => 'url',
                'placeholder' => 'https://example.com/register',
                'validate' => 'url_optional',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_profile_url',
                'label' => __('Profile URL', 'goody'),
                'type' => 'url',
                'placeholder' => 'https://example.com/profile',
                'validate' => 'url_optional',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
            ],
            [
                'key' => 'account_empty_note_text',
                'label' => __('Empty Actions Note', 'goody'),
                'type' => 'textarea',
                'depends_on' => 'account_enabled',
                'depends_value' => '1',
                'description' => __('Shown when login/register/profile URLs are empty.', 'goody'),
            ],
        ],
        'newsletter' => [
            ['key' => 'newsletter_title', 'label' => __('Newsletter Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'newsletter_text', 'label' => __('Newsletter Section Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'newsletter_embed', 'label' => __('Newsletter Embed/Shortcode', 'goody'), 'type' => 'code'],
        ],
        'contact' => [
            ['key' => 'contact_section_title', 'label' => __('Contact Section Title', 'goody'), 'type' => 'text'],
            ['key' => 'contact_section_text', 'label' => __('Contact Section Text', 'goody'), 'type' => 'textarea'],
            ['key' => 'contact_phone', 'label' => __('Phone', 'goody'), 'type' => 'text'],
            ['key' => 'contact_email', 'label' => __('Email', 'goody'), 'type' => 'email'],
            ['key' => 'contact_address', 'label' => __('Address', 'goody'), 'type' => 'textarea'],
            ['key' => 'contact_map_lat', 'label' => __('Map Latitude', 'goody'), 'type' => 'text', 'description' => __('For native map marker. Example: 41.3787', 'goody')],
            ['key' => 'contact_map_lng', 'label' => __('Map Longitude', 'goody'), 'type' => 'text', 'description' => __('For native map marker. Example: 2.1658', 'goody')],
            ['key' => 'contact_whatsapp_number', 'label' => __('WhatsApp Number', 'goody'), 'type' => 'text'],
            ['key' => 'contact_whatsapp_button_text', 'label' => __('WhatsApp Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'contact_call_button_text', 'label' => __('Call Button Text', 'goody'), 'type' => 'text'],
            ['key' => 'contact_form_shortcode', 'label' => __('Contact Form Shortcode', 'goody'), 'type' => 'textarea'],
            ['key' => 'google_maps_embed', 'label' => __('Google Maps Embed', 'goody'), 'type' => 'code'],
            ['key' => 'map_script_embed', 'label' => __('Map Script/Custom Marker Embed', 'goody'), 'type' => 'code'],
            [
                'key' => 'business_hours',
                'label' => __('Business Hours (Repeater)', 'goody'),
                'type' => 'repeater',
                'columns' => [
                    ['key' => 'day', 'label' => __('Day', 'goody'), 'type' => 'text'],
                    ['key' => 'open', 'label' => __('Open', 'goody'), 'type' => 'time'],
                    ['key' => 'close', 'label' => __('Close', 'goody'), 'type' => 'time'],
                ],
            ],
            [
                'key' => 'social_links',
                'label' => __('Social Links (Repeater)', 'goody'),
                'type' => 'repeater',
                'columns' => [
                    ['key' => 'label', 'label' => __('Label', 'goody'), 'type' => 'text'],
                    ['key' => 'url', 'label' => __('URL', 'goody'), 'type' => 'url'],
                ],
            ],
        ],
        'footer' => [
            [
                'key' => 'footer_content_source',
                'label' => __('Footer Layout', 'goody'),
                'type' => 'select',
                'options' => [
                    'theme' => __('Default Footer', 'goody'),
                    'gutenberg' => __('Gutenberg Footer (Selectable)', 'goody'),
                ],
            ],
            [
                'key' => 'footer_gutenberg_content_id',
                'label' => __('Gutenberg Footer Content', 'goody'),
                'type' => 'post_select',
                'post_type' => ['wp_block', 'page', 'post'],
                'depends_on' => 'footer_content_source',
                'depends_value' => 'gutenberg',
                'description' => __('Select a reusable block/page/post built with Gutenberg.', 'goody'),
            ],
            ['key' => 'footer_quick_title', 'label' => __('Quick Links Title', 'goody'), 'type' => 'text'],
            ['key' => 'footer_legal_title', 'label' => __('Legal Links Title', 'goody'), 'type' => 'text'],
            ['key' => 'footer_payment_icons', 'label' => __('Payment Icons (Upload Multiple)', 'goody'), 'type' => 'gallery'],
            ['key' => 'footer_copyright', 'label' => __('Footer Copyright Text', 'goody'), 'type' => 'text'],
        ],
        'seo' => [
            ['key' => 'seo_home_meta_title', 'label' => __('Homepage Meta Title', 'goody'), 'type' => 'text'],
            ['key' => 'seo_home_meta_description', 'label' => __('Homepage Meta Description', 'goody'), 'type' => 'textarea'],
            ['key' => 'seo_local_text', 'label' => __('Local SEO Text', 'goody'), 'type' => 'textarea'],
        ],
        'integrations' => [
            ['key' => 'integrations_maps_api_key', 'label' => __('Maps API Key or Script URL (Google Maps JavaScript API)', 'goody'), 'type' => 'text'],
            ['key' => 'integrations_reviews_api_key', 'label' => __('Reviews API Key / Token (Google or SerpApi)', 'goody'), 'type' => 'text'],
            [
                'key' => 'integrations_google_reviews_api_key',
                'label' => __('Google Reviews API Key (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Use a server-side key starting with AIza... for Google Places reviews. Do not use an HTTP referrer restricted browser key here; restrict this key by server IP and Places API instead. If empty, shared Reviews key is used.', 'goody'),
            ],
            [
                'key' => 'integrations_serpapi_api_key',
                'label' => __('SerpApi Key (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Paste SerpApi private key (not playground URL, not data_id). If empty, shared Reviews key is used when it is not a Google AIza key.', 'goody'),
            ],
            [
                'key' => 'integrations_mailchimp_api_key',
                'label' => __('Mailchimp API Key', 'goody'),
                'type' => 'text',
                'description' => __('Used to sync contact/newsletter emails to Mailchimp audience.', 'goody'),
            ],
            [
                'key' => 'integrations_mailchimp_audience_id',
                'label' => __('Mailchimp Audience ID', 'goody'),
                'type' => 'text',
            ],
            [
                'key' => 'integrations_mailchimp_server_prefix',
                'label' => __('Mailchimp Server Prefix (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Example: us21. Leave empty to auto-detect from API key suffix.', 'goody'),
            ],
            ['key' => 'trustpilot_api_token', 'label' => __('Trustpilot API Token / Key', 'goody'), 'type' => 'text'],
            ['key' => 'custom_reviews_api_token', 'label' => __('Custom Reviews API Token / Key', 'goody'), 'type' => 'text'],
            ['key' => 'integrations_custom_head_code', 'label' => __('Custom <head> Code', 'goody'), 'type' => 'code'],
            ['key' => 'integrations_custom_footer_code', 'label' => __('Custom Footer Code', 'goody'), 'type' => 'code'],
        ],
    ];
}

function goody_register_settings() {
    register_setting('goody_theme_options_group', 'goody_theme_options', 'goody_sanitize_options');
}
add_action('admin_init', 'goody_register_settings');

function goody_get_theme_options_language_from_request() {
    $raw = '';
    if (isset($_POST['goody_lang'])) {
        $raw = (string) wp_unslash($_POST['goody_lang']);
    } elseif (isset($_GET['goody_lang'])) {
        $raw = (string) wp_unslash($_GET['goody_lang']);
    }

    $code = sanitize_key($raw);
    if ($code === '') {
        return '';
    }

    $supported = array_keys(goody_get_language_locale_map());
    return in_array($code, $supported, true) ? $code : '';
}

function goody_get_theme_options_localizable_keys() {
    return [
        'restaurant_name',
        'restaurant_tagline',
        'restaurant_logo_alt',
        'header_search_placeholder',
        'hero_heading',
        'hero_highlight_text',
        'hero_subheading',
        'hero_concept_tagline',
        'hero_primary_text',
        'hero_secondary_text',
        'menu_section_title',
        'menu_section_text',
        'menu_page_title',
        'offers_section_title',
        'offers_section_text',
        'order_section_title',
        'order_section_text',
        'custom_order_text',
        'tracking_title',
        'tracking_description',
        'reservation_section_title',
        'reservation_section_text',
        'reservation_page_title',
        'reservation_button_text',
        'reservation_status_title',
        'reservation_status_text',
        'reservation_success_message',
        'reservation_booking_notice',
        'reservation_error_message',
        'reservation_pickup_warning',
        'reservation_delivery_warning',
        'reservation_cash_warning',
        'reservation_dine_in_note',
        'reservation_step_counter_prefix',
        'reservation_step_title_1',
        'reservation_step_title_2',
        'reservation_step_title_3',
        'reservation_step_title_4',
        'reservation_step_title_5',
        'reservation_step_title_6',
        'reservation_order_type_label_dine_in',
        'reservation_order_type_label_pickup',
        'reservation_order_type_label_delivery',
        'reservation_next_button_text',
        'reservation_back_button_text',
        'reservation_submit_button_text',
        'reservation_holiday_message',
        'about_story_title',
        'about_story_text',
        'about_mission_title',
        'about_mission_text',
        'about_vision_title',
        'about_vision_text',
        'reviews_section_title',
        'reviews_section_text',
        'events_section_title',
        'events_section_text',
        'news_section_title',
        'news_eyebrow_text',
        'news_section_text',
        'news_button_text',
        'news_read_more_text',
        'news_empty_title',
        'news_empty_text',
        'account_section_title',
        'account_eyebrow_text',
        'account_section_text',
        'account_placeholder_title',
        'account_placeholder_text',
        'account_feature_text_1',
        'account_feature_text_2',
        'account_feature_text_3',
        'account_actions_title',
        'account_login_button_text',
        'account_register_button_text',
        'account_profile_button_text',
        'account_empty_note_text',
        'newsletter_title',
        'newsletter_text',
        'contact_section_title',
        'contact_section_text',
        'contact_address',
        'contact_whatsapp_button_text',
        'contact_call_button_text',
        'footer_quick_title',
        'footer_legal_title',
        'footer_copyright',
        'seo_home_meta_title',
        'seo_home_meta_description',
        'seo_local_text',
    ];
}

function goody_is_theme_option_localizable_field($field) {
    $key = (string) ($field['key'] ?? '');
    $type = (string) ($field['type'] ?? '');

    if ($key === '' || $type === '') {
        return false;
    }

    if (! in_array($type, ['text', 'textarea'], true)) {
        return false;
    }

    $localizable_keys = goody_get_theme_options_localizable_keys();
    return in_array($key, $localizable_keys, true);
}

function goody_handle_theme_options_save() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to manage these settings.', 'goody'));
    }

    check_admin_referer('goody_save_theme_options', 'goody_theme_options_nonce');

    $input = $_POST['goody_theme_options'] ?? [];
    if (! is_array($input)) {
        $input = [];
    }

    $sanitized = goody_sanitize_options(wp_unslash($input));

    $active_lang = goody_get_theme_options_language_from_request();
    if ($active_lang !== '' && $active_lang !== 'en') {
        $current = goody_get_options();
        $fields_by_section = goody_get_settings_fields();
        $flat_fields = [];

        foreach ($fields_by_section as $fields) {
            foreach ($fields as $field) {
                if (! is_array($field) || empty($field['key'])) {
                    continue;
                }
                $flat_fields[(string) $field['key']] = $field;
            }
        }

        foreach ($flat_fields as $key => $field) {
            if (! goody_is_theme_option_localizable_field($field)) {
                continue;
            }
            if (! array_key_exists($key, $input) || ! array_key_exists($key, $sanitized)) {
                continue;
            }

            $localized_key = $key . '__' . $active_lang;
            $sanitized[$localized_key] = $sanitized[$key];
            $sanitized[$key] = $current[$key] ?? '';
        }
    }

    update_option('goody_theme_options', $sanitized, false);

    $redirect = wp_get_referer();
    if (! $redirect) {
        $redirect = add_query_arg([
            'page' => 'goody-theme',
        ], admin_url('admin.php'));
    }

    $redirect = remove_query_arg('updated', $redirect);
    $redirect = add_query_arg('updated', '1', $redirect);

    wp_safe_redirect($redirect);
    exit;
}
add_action('admin_post_goody_save_theme_options', 'goody_handle_theme_options_save');

function goody_register_theme_settings_snapshot_post_type() {
    register_post_type('goody_theme_snapshot', [
        'labels' => [
            'name' => __('Theme Settings Snapshots', 'goody'),
            'singular_name' => __('Theme Settings Snapshot', 'goody'),
        ],
        'public' => false,
        'show_ui' => false,
        'show_in_menu' => false,
        'supports' => ['title', 'editor', 'revisions'],
        'can_export' => true,
    ]);
}
add_action('init', 'goody_register_theme_settings_snapshot_post_type', 5);

function goody_get_theme_settings_snapshot_post() {
    $snapshot = get_page_by_path('goody-theme-settings-snapshot', OBJECT, 'goody_theme_snapshot');
    if ($snapshot instanceof WP_Post) {
        return $snapshot;
    }

    $snapshots = get_posts([
        'post_type' => 'goody_theme_snapshot',
        'post_status' => ['private', 'publish', 'draft'],
        'posts_per_page' => 1,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    return $snapshots[0] ?? null;
}

function goody_sync_theme_settings_snapshot($old_value, $new_value) {
    if (! is_array($new_value)) {
        return;
    }

    $payload = wp_json_encode($new_value);
    if (! is_string($payload) || $payload === '') {
        return;
    }

    $snapshot = goody_get_theme_settings_snapshot_post();

    $postarr = [
        'post_type' => 'goody_theme_snapshot',
        'post_status' => 'private',
        'post_title' => 'Goody Theme Settings Snapshot',
        'post_name' => 'goody-theme-settings-snapshot',
        'post_content' => 'Snapshot container for goody_theme_options export/import.',
    ];

    if ($snapshot instanceof WP_Post) {
        $postarr['ID'] = $snapshot->ID;
        $snapshot_id = wp_update_post($postarr);
        if (! is_wp_error($snapshot_id) && $snapshot_id) {
            update_post_meta((int) $snapshot_id, '_goody_theme_snapshot_json', wp_slash($payload));
        }
        return;
    }

    $snapshot_id = wp_insert_post($postarr);
    if (! is_wp_error($snapshot_id) && $snapshot_id) {
        update_post_meta((int) $snapshot_id, '_goody_theme_snapshot_json', wp_slash($payload));
    }
}
add_action('update_option_goody_theme_options', 'goody_sync_theme_settings_snapshot', 20, 2);

function goody_restore_theme_settings_from_snapshot_if_missing() {
    $stored = get_option('goody_theme_options', null);
    if (is_array($stored) && ! empty($stored)) {
        return;
    }

    $snapshot = goody_get_theme_settings_snapshot_post();
    if (! ($snapshot instanceof WP_Post)) {
        return;
    }

    $stored_payload = (string) get_post_meta($snapshot->ID, '_goody_theme_snapshot_json', true);
    if ($stored_payload === '') {
        $stored_payload = (string) $snapshot->post_content;
    }

    $restored = json_decode($stored_payload, true);
    if (! is_array($restored) || empty($restored)) {
        return;
    }

    update_option('goody_theme_options', $restored, false);
}
add_action('init', 'goody_restore_theme_settings_from_snapshot_if_missing', 40);

function goody_maybe_seed_theme_settings_snapshot() {
    $options = get_option('goody_theme_options', []);
    if (! is_array($options) || empty($options)) {
        return;
    }

    $snapshot = get_page_by_path('goody-theme-settings-snapshot', OBJECT, 'goody_theme_snapshot');
    if ($snapshot instanceof WP_Post) {
        return;
    }

    goody_sync_theme_settings_snapshot([], $options);
}
add_action('admin_init', 'goody_maybe_seed_theme_settings_snapshot', 20);

function goody_sanitize_repeater_value($json, $columns) {
    $rows = json_decode((string) $json, true);
    if (! is_array($rows)) {
        return wp_json_encode([]);
    }

    $clean = [];
    foreach ($rows as $row) {
        if (! is_array($row)) {
            continue;
        }

        $item = [];
        foreach ($columns as $column) {
            $key = $column['key'];
            $type = $column['type'];
            $value = $row[$key] ?? '';

            if ($type === 'url') {
                $item[$key] = esc_url_raw($value);
            } elseif ($type === 'time') {
                $item[$key] = sanitize_text_field((string) $value);
            } else {
                $item[$key] = sanitize_text_field((string) $value);
            }
        }

        if (implode('', $item) !== '') {
            $clean[] = $item;
        }
    }

    return wp_json_encode($clean);
}

function goody_hex_to_rgb($color) {
    $hex = strtolower(trim((string) $color));
    if ($hex === '') {
        return null;
    }
    if ($hex[0] === '#') {
        $hex = substr($hex, 1);
    }
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    if (! preg_match('/^[0-9a-f]{6}$/', $hex)) {
        return null;
    }
    return [
        'r' => hexdec(substr($hex, 0, 2)),
        'g' => hexdec(substr($hex, 2, 2)),
        'b' => hexdec(substr($hex, 4, 2)),
    ];
}

function goody_rgb_to_hex($rgb) {
    $r = max(0, min(255, (int) round((float) ($rgb['r'] ?? 0))));
    $g = max(0, min(255, (int) round((float) ($rgb['g'] ?? 0))));
    $b = max(0, min(255, (int) round((float) ($rgb['b'] ?? 0))));
    return sprintf('#%02X%02X%02X', $r, $g, $b);
}

function goody_blend_rgb($base, $target, $target_weight) {
    $w = max(0, min(1, (float) $target_weight));
    return [
        'r' => ((1 - $w) * (float) $base['r']) + ($w * (float) $target['r']),
        'g' => ((1 - $w) * (float) $base['g']) + ($w * (float) $target['g']),
        'b' => ((1 - $w) * (float) $base['b']) + ($w * (float) $target['b']),
    ];
}

function goody_rgba_from_rgb($rgb, $alpha) {
    $a = max(0, min(1, (float) $alpha));
    $r = max(0, min(255, (int) round((float) ($rgb['r'] ?? 0))));
    $g = max(0, min(255, (int) round((float) ($rgb['g'] ?? 0))));
    $b = max(0, min(255, (int) round((float) ($rgb['b'] ?? 0))));
    return 'rgba(' . $r . ', ' . $g . ', ' . $b . ', ' . rtrim(rtrim(number_format($a, 2, '.', ''), '0'), '.') . ')';
}

function goody_generate_harmony_palette($anchor_hex) {
    $anchor = goody_hex_to_rgb($anchor_hex);
    if (! $anchor) {
        return [];
    }

    // Ratio intent:
    // Cream/light ~60%, dark green ~15%, soft green ~10%, gold accent ~5-8%, white ~5-7%, text ~3-5%.
    $cream = ['r' => 250, 'g' => 246, 'b' => 239];
    $dark_green = ['r' => 43, 'g' => 94, 'b' => 62];
    $soft_green = ['r' => 122, 'g' => 158, 'b' => 126];
    $gold = ['r' => 201, 'g' => 168, 'b' => 76];
    $white = ['r' => 255, 'g' => 255, 'b' => 255];
    $black = ['r' => 0, 'g' => 0, 'b' => 0];

    $primary = goody_blend_rgb($dark_green, $anchor, 0.42);
    $primary_2 = goody_blend_rgb($primary, $black, 0.22);
    $primary_hover = goody_blend_rgb($primary, $white, 0.16);

    $bg_deep = goody_blend_rgb($cream, $dark_green, 0.08);
    $bg = goody_blend_rgb($cream, $white, 0.18);
    $bg_soft = goody_blend_rgb($cream, $soft_green, 0.14);
    $section = goody_blend_rgb($cream, $soft_green, 0.20);
    $card = $white;
    $card_soft = goody_blend_rgb($cream, $soft_green, 0.22);
    $surface = goody_blend_rgb($cream, $soft_green, 0.16);
    $text = goody_blend_rgb($dark_green, $black, 0.38);
    $muted = goody_blend_rgb($text, $white, 0.34);
    $accent = goody_blend_rgb($gold, $anchor, 0.10);

    return [
        'token_color_primary' => goody_rgb_to_hex($primary),
        'token_color_primary_2' => goody_rgb_to_hex($primary_2),
        'token_color_primary_hover' => goody_rgb_to_hex($primary_hover),
        'token_color_button_text' => '#FAF6EF',
        'reservation_button_color' => goody_rgb_to_hex($primary),
        'reservation_accent_color' => goody_rgb_to_hex($accent),
        'token_color_bg_deep' => goody_rgb_to_hex($bg_deep),
        'token_color_bg' => goody_rgb_to_hex($bg),
        'token_color_bg_soft' => goody_rgb_to_hex($bg_soft),
        'token_color_section' => goody_rgb_to_hex($section),
        'token_color_card' => goody_rgb_to_hex($card),
        'token_color_card_soft' => goody_rgb_to_hex($card_soft),
        'token_color_surface' => goody_rgb_to_hex($surface),
        'token_color_text' => goody_rgb_to_hex($text),
        'token_color_muted' => goody_rgb_to_hex($muted),
        'token_color_border' => goody_rgba_from_rgb($soft_green, 0.25),
        'token_color_shadow' => goody_rgba_from_rgb($black, 0.16),
    ];
}

function goody_sanitize_options($input) {
    $defaults = goody_default_options();
    $current = goody_get_options();
    $fields_by_section = goody_get_settings_fields();
    $flat_fields = [];

    foreach ($fields_by_section as $fields) {
        foreach ($fields as $field) {
            $flat_fields[$field['key']] = $field;
        }
    }

    $sanitized = $current;

    foreach ($flat_fields as $key => $field) {
        $type = $field['type'];
        $has_value = is_array($input) && array_key_exists($key, $input);
        $value = $has_value ? $input[$key] : '';

        if ($type === 'checkbox') {
            $sanitized[$key] = (string) ($value) === '1' ? '1' : '0';
        } elseif (! $has_value) {
            continue;
        } elseif (substr($key, -8) === '_api_url') {
            $sanitized[$key] = goody_normalize_url_input((string) $value);
        } elseif (substr($key, -10) === '_api_token') {
            $sanitized[$key] = goody_normalize_api_token((string) $value);
        } elseif ($key === 'integrations_maps_api_key') {
            $sanitized[$key] = goody_extract_google_maps_api_key((string) $value);
        } elseif ($key === 'integrations_reviews_api_key') {
            $sanitized[$key] = goody_normalize_api_token((string) $value);
        } elseif ($key === 'integrations_google_reviews_api_key') {
            $sanitized[$key] = goody_extract_google_maps_api_key((string) $value);
        } elseif ($key === 'integrations_serpapi_api_key') {
            $sanitized[$key] = goody_normalize_api_token((string) $value);
        } elseif ($key === 'google_reviews_place_id') {
            $sanitized[$key] = sanitize_text_field((string) $value);
        } elseif ($key === 'google_reviews_count') {
            $count = absint($value);
            if ($count < 1) {
                $count = 6;
            }
            $sanitized[$key] = (string) min(10, $count);
        } elseif ($key === 'tracking_url') {
            $sanitized[$key] = sanitize_text_field((string) $value);
        } elseif ($key === 'news_posts_count') {
            $count = absint($value);
            if ($count < 1) {
                $count = 3;
            }
            $sanitized[$key] = (string) min(8, $count);
        } elseif ($key === 'gallery_zone_items_count') {
            $count = absint($value);
            $sanitized[$key] = (string) min(60, $count);
        } elseif ($type === 'url') {
            $sanitized[$key] = goody_normalize_url_input((string) $value);
        } elseif ($type === 'email') {
            $sanitized[$key] = sanitize_email((string) $value);
        } elseif ($type === 'number') {
            $sanitized[$key] = sanitize_text_field((string) $value);
        } elseif ($type === 'color') {
            $sanitized[$key] = sanitize_hex_color((string) $value) ?: ($defaults[$key] ?? '#000000');
        } elseif ($type === 'media') {
            $sanitized[$key] = absint($value);
        } elseif ($type === 'gallery') {
            $ids = is_string($value) ? explode(',', $value) : [];
            $ids = array_values(array_filter(array_map('absint', $ids)));
            $sanitized[$key] = implode(',', $ids);
        } elseif ($type === 'code') {
            $sanitized[$key] = goody_sanitize_code_input($value);
        } elseif ($type === 'textarea') {
            $sanitized[$key] = sanitize_textarea_field((string) $value);
        } elseif ($type === 'repeater') {
            $columns = $field['columns'] ?? [];
            $sanitized[$key] = goody_sanitize_repeater_value($value, $columns);
        } elseif ($type === 'select') {
            $allowed = array_keys($field['options'] ?? []);
            $sanitized[$key] = in_array($value, $allowed, true) ? $value : ($defaults[$key] ?? '');
        } elseif ($type === 'roles_multiselect') {
            $role_keys = [];
            if (function_exists('wp_roles')) {
                $wp_roles = wp_roles();
                if ($wp_roles instanceof WP_Roles) {
                    $role_keys = array_keys((array) $wp_roles->roles);
                }
            }
            if (empty($role_keys)) {
                $role_keys = ['administrator', 'shop_manager', 'shop_worker'];
            }
            $selected = is_array($value) ? $value : [];
            $selected = array_values(array_unique(array_filter(array_map('sanitize_key', $selected))));
            $selected = array_values(array_intersect($selected, $role_keys));
            $sanitized[$key] = implode(',', $selected);
        } elseif ($type === 'post_select') {
            $sanitized[$key] = (string) absint($value);
        } else {
            $sanitized[$key] = sanitize_text_field((string) $value);
        }
    }

    $next_preset = (string) ($sanitized['design_color_preset'] ?? 'custom');
    $current_preset = (string) ($current['design_color_preset'] ?? 'custom');
    if ($next_preset === 'trusted_professional' && $current_preset !== 'trusted_professional') {
        $sanitized['token_color_primary'] = '#2B5E3E';
        $sanitized['token_color_primary_2'] = '#1F4A32';
        $sanitized['token_color_primary_hover'] = '#4A7C59';
        $sanitized['token_color_button_text'] = '#FAF6EF';
        $sanitized['reservation_button_color'] = '#2B5E3E';
        $sanitized['reservation_accent_color'] = '#C9A84C';
        $sanitized['token_color_bg_deep'] = '#EDE3D0';
        $sanitized['token_color_bg'] = '#FAF6EF';
        $sanitized['token_color_bg_soft'] = '#F2EAD8';
        $sanitized['token_color_section'] = '#F5EFE2';
        $sanitized['token_color_card'] = '#FFFFFF';
        $sanitized['token_color_card_soft'] = '#E8F1E8';
        $sanitized['token_color_surface'] = '#FFFDF9';
        $sanitized['token_color_text'] = '#1A1A18';
        $sanitized['token_color_muted'] = '#7A7570';
        $sanitized['token_color_border'] = 'rgba(122, 158, 126, 0.25)';
        $sanitized['token_color_shadow'] = 'rgba(26, 26, 24, 0.15)';
    }

    $next_auto_harmony = (string) ($sanitized['design_auto_harmony'] ?? '0');
    $current_auto_harmony = (string) ($current['design_auto_harmony'] ?? '0');
    if ($next_auto_harmony === '1') {
        $color_anchor_keys = [
            'token_color_primary',
            'token_color_primary_2',
            'token_color_primary_hover',
            'reservation_button_color',
        ];
        $anchor_hex = '';
        foreach ($color_anchor_keys as $anchor_key) {
            $prev = sanitize_hex_color((string) ($current[$anchor_key] ?? ''));
            $next = sanitize_hex_color((string) ($sanitized[$anchor_key] ?? ''));
            if ($next && $next !== $prev) {
                $anchor_hex = $next;
                break;
            }
        }
        $should_apply_harmony = $anchor_hex !== '' || $current_auto_harmony !== '1';
        if ($should_apply_harmony) {
            if ($anchor_hex === '') {
                $anchor_hex = sanitize_hex_color((string) ($sanitized['token_color_primary'] ?? ''));
            }
            $derived = goody_generate_harmony_palette($anchor_hex);
            if (! empty($derived)) {
                foreach ($derived as $palette_key => $palette_value) {
                    $sanitized[$palette_key] = $palette_value;
                }
            }
        }
    }

    return $sanitized;
}

function goody_render_option_field($field, $options) {
    $key = $field['key'];
    $label = $field['label'];
    $type = $field['type'];
    $value = $options[$key] ?? '';
    if (goody_is_theme_option_localizable_field($field)) {
        $value = goody_get_option($key, (string) $value);
    }
    $description = $field['description'] ?? '';

    $render_attrs = static function ($attrs) {
        $html = '';
        foreach ($attrs as $attr_key => $attr_value) {
            if ($attr_value === null || $attr_value === '') {
                continue;
            }

            $html .= ' ' . $attr_key . '="' . esc_attr((string) $attr_value) . '"';
        }
        return $html;
    };

    $row_attrs = [
        'class' => 'goody-option-row',
        'data-field-key' => $key,
        'data-depends-on' => $field['depends_on'] ?? '',
        'data-depends-value' => isset($field['depends_value']) ? (string) $field['depends_value'] : '',
    ];

    echo '<tr' . $render_attrs($row_attrs) . '><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($label) . '</label></th><td>';

    if ($type === 'textarea' || $type === 'code') {
        echo '<textarea class="large-text" rows="4" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']">' . esc_textarea((string) $value) . '</textarea>';
    } elseif ($type === 'checkbox') {
        echo '<input type="hidden" name="goody_theme_options[' . esc_attr($key) . ']" value="0">';
        echo '<label><input type="checkbox" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="1" ' . checked($value, '1', false) . '> ' . esc_html__('Enable', 'goody') . '</label>';
    } elseif ($type === 'select') {
        echo '<select id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']">';
        foreach (($field['options'] ?? []) as $option_value => $option_label) {
            echo '<option value="' . esc_attr($option_value) . '" ' . selected($value, $option_value, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select>';
    } elseif ($type === 'roles_multiselect') {
        $selected_roles = array_values(array_filter(array_map('sanitize_key', explode(',', (string) $value))));
        $roles = [];
        if (function_exists('wp_roles')) {
            $wp_roles = wp_roles();
            if ($wp_roles instanceof WP_Roles) {
                $roles = (array) $wp_roles->roles;
            }
        }
        if (empty($roles)) {
            $roles = [
                'administrator' => ['name' => 'Administrator'],
                'shop_manager' => ['name' => 'Shop manager'],
                'shop_worker' => ['name' => 'Shop worker'],
            ];
        }
        echo '<div id="' . esc_attr($key) . '" style="display:grid;gap:6px;">';
        foreach ($roles as $role_key => $role_data) {
            $role_name = is_array($role_data) ? (string) ($role_data['name'] ?? $role_key) : (string) $role_key;
            $role_key = (string) $role_key;
            $input_id = $key . '_' . $role_key;
            echo '<label for="' . esc_attr($input_id) . '" style="display:flex;align-items:center;gap:8px;">';
            echo '<input type="checkbox" id="' . esc_attr($input_id) . '" name="goody_theme_options[' . esc_attr($key) . '][]" value="' . esc_attr($role_key) . '" ' . checked(in_array($role_key, $selected_roles, true), true, false) . '>';
            echo '<span>' . esc_html(translate_user_role($role_name)) . '</span>';
            echo '</label>';
        }
        echo '</div>';
        echo '<p class="description">' . esc_html__('Check roles to allow update access. Uncheck to remove access.', 'goody') . '</p>';
    } elseif ($type === 'color') {
        echo '<input type="color" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
    } elseif ($type === 'media') {
        $image_url = $value ? wp_get_attachment_image_url((int) $value, 'medium') : '';
        echo '<input type="hidden" class="goody-media-field" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
        echo '<button type="button" class="button goody-media-upload" data-target="' . esc_attr($key) . '">' . esc_html__('Upload / Select', 'goody') . '</button>';
        echo ' <button type="button" class="button button-link-delete goody-media-clear" data-target="' . esc_attr($key) . '">' . esc_html__('Remove', 'goody') . '</button>';
        echo '<div class="goody-media-preview" style="margin-top:10px;">';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:240px;height:auto;">';
        }
        echo '</div>';
    } elseif ($type === 'gallery') {
        $ids = array_values(array_filter(array_map('absint', explode(',', (string) $value))));
        echo '<input type="hidden" class="goody-gallery-field" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
        echo '<button type="button" class="button goody-gallery-upload" data-target="' . esc_attr($key) . '">' . esc_html__('Upload Multiple', 'goody') . '</button>';
        echo ' <button type="button" class="button button-link-delete goody-gallery-clear" data-target="' . esc_attr($key) . '">' . esc_html__('Clear All', 'goody') . '</button>';
        echo '<div class="goody-gallery-preview" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px;">';
        foreach ($ids as $id) {
            $src = wp_get_attachment_image_url($id, 'thumbnail');
            if ($src) {
                echo '<span class="goody-gallery-thumb" data-id="' . esc_attr((string) $id) . '" style="position:relative;display:inline-flex;">';
                echo '<img src="' . esc_url($src) . '" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">';
                echo '<button type="button" class="goody-gallery-thumb-remove" aria-label="' . esc_attr__('Remove image', 'goody') . '" title="' . esc_attr__('Remove image', 'goody') . '" style="position:absolute;top:-7px;right:-7px;border:0;border-radius:999px;width:18px;height:18px;line-height:18px;padding:0;background:#b32d2e;color:#fff;cursor:pointer;">&times;</button>';
                echo '</span>';
            }
        }
        echo '</div>';
    } elseif ($type === 'repeater') {
        $columns = $field['columns'] ?? [];
        $rows = json_decode((string) $value, true);
        if (! is_array($rows)) {
            $rows = [];
        }

        echo '<input type="hidden" class="goody-repeater-input" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
        echo '<div class="goody-repeater" data-target="' . esc_attr($key) . '" data-columns="' . esc_attr(wp_json_encode($columns)) . '">';

        foreach ($rows as $index => $row) {
            echo '<div class="goody-repeater-row" data-index="' . esc_attr((string) $index) . '">';
            foreach ($columns as $column) {
                $column_key = $column['key'];
                $column_value = $row[$column_key] ?? '';
                $input_type = $column['type'] === 'time' ? 'time' : 'text';
                echo '<input type="' . esc_attr($input_type) . '" data-field="' . esc_attr($column_key) . '" placeholder="' . esc_attr($column['label']) . '" value="' . esc_attr((string) $column_value) . '">';
            }
            echo '<button type="button" class="button goody-row-up">↑</button>';
            echo '<button type="button" class="button goody-row-down">↓</button>';
            echo '<button type="button" class="button goody-row-remove">' . esc_html__('Remove', 'goody') . '</button>';
            echo '</div>';
        }

        echo '</div>';
        echo '<button type="button" class="button button-secondary goody-repeater-add" data-target="' . esc_attr($key) . '">' . esc_html__('Add Row', 'goody') . '</button>';
    } elseif ($type === 'post_select') {
        $post_types = $field['post_type'] ?? ['page'];
        if (! is_array($post_types) || empty($post_types)) {
            $post_types = ['page'];
        }

        $posts = get_posts([
            'post_type' => $post_types,
            'post_status' => 'publish',
            'numberposts' => 200,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);

        echo '<select id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']">';
        echo '<option value="0">' . esc_html__('Select content', 'goody') . '</option>';
        foreach ($posts as $post_item) {
            if (! $post_item instanceof WP_Post) {
                continue;
            }
            $label = get_the_title($post_item);
            if ($label === '') {
                $label = __('(No title)', 'goody');
            }
            $type_obj = get_post_type_object($post_item->post_type);
            $type_label = $type_obj && isset($type_obj->labels->singular_name)
                ? (string) $type_obj->labels->singular_name
                : ucfirst((string) $post_item->post_type);
            $option_label = $label . ' [' . $type_label . ']';
            echo '<option value="' . esc_attr((string) $post_item->ID) . '" ' . selected((string) $value, (string) $post_item->ID, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select>';
    } else {
        $input_type = in_array($type, ['text', 'email', 'number'], true) ? $type : 'text';
        if ($type === 'url') {
            $input_type = 'text';
        }

        $validation = $field['validate'] ?? '';
        if ($validation === '' && $type === 'url') {
            $validation = 'url_optional';
        } elseif ($validation === '' && $type === 'number' && (isset($field['min']) || isset($field['max']))) {
            $validation = 'number_range';
        }

        $input_attrs = [
            'class' => 'regular-text',
            'type' => $input_type,
            'id' => $key,
            'name' => 'goody_theme_options[' . $key . ']',
            'value' => (string) $value,
            'data-goody-validate' => $validation,
            'min' => isset($field['min']) ? (string) $field['min'] : null,
            'max' => isset($field['max']) ? (string) $field['max'] : null,
            'placeholder' => $field['placeholder'] ?? ($type === 'url' ? 'https://example.com' : ''),
        ];

        echo '<input' . $render_attrs($input_attrs) . '>';
    }

    if ($description !== '') {
        echo '<p class="description goody-field-hint">' . esc_html($description) . '</p>';
    }

    echo '</td></tr>';
}

function goody_render_theme_options_page() {
    $options = goody_get_options();
    $sections = goody_get_settings_sections();
    $fields_by_section = goody_get_settings_fields();
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Goody Green Theme Settings', 'goody'); ?></h1>
        <p><?php esc_html_e('All restaurant functionality is managed from this dashboard.', 'goody'); ?></p>

        <?php if (isset($_GET['updated']) && $_GET['updated'] === '1') : ?>
            <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Settings saved successfully.', 'goody'); ?></p></div>
        <?php endif; ?>

        <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
            <input type="hidden" name="action" value="goody_save_theme_options">
            <input type="hidden" name="goody_lang" value="<?php echo esc_attr(goody_get_theme_options_language_from_request()); ?>">
            <?php wp_nonce_field('goody_save_theme_options', 'goody_theme_options_nonce'); ?>

            <h2 class="nav-tab-wrapper goody-tab-wrapper">
                <?php $first = true; ?>
                <?php foreach ($sections as $section_key => $section_label) : ?>
                    <a href="#goody-<?php echo esc_attr($section_key); ?>" class="nav-tab <?php echo $first ? 'nav-tab-active' : ''; ?>"><?php echo esc_html($section_label); ?></a>
                    <?php $first = false; ?>
                <?php endforeach; ?>
            </h2>

            <?php $first_panel = true; ?>
            <?php foreach ($sections as $section_key => $section_label) : ?>
                <div id="goody-<?php echo esc_attr($section_key); ?>" class="goody-tab-panel" style="display:<?php echo $first_panel ? 'block' : 'none'; ?>;">
                    <table class="form-table" role="presentation">
                        <?php foreach (($fields_by_section[$section_key] ?? []) as $field) : ?>
                            <?php goody_render_option_field($field, $options); ?>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php $first_panel = false; ?>
            <?php endforeach; ?>

            <?php submit_button(__('Save Theme Settings', 'goody')); ?>
        </form>
    </div>
    <?php
}

function goody_render_form_submissions_page() {
    if (! current_user_can('edit_theme_options')) {
        wp_die(esc_html__('You are not allowed to access this page.', 'goody'));
    }

    $messages = new WP_Query([
        'post_type' => 'goody_message',
        'post_status' => 'private',
        'posts_per_page' => 20,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    $subscribers = get_option('goody_newsletter_subscribers', []);
    if (! is_array($subscribers)) {
        $subscribers = [];
    }
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Form Submissions', 'goody'); ?></h1>

        <h2><?php esc_html_e('Contact Messages', 'goody'); ?></h2>
        <?php if ($messages->have_posts()) : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Date', 'goody'); ?></th>
                        <th><?php esc_html_e('Name', 'goody'); ?></th>
                        <th><?php esc_html_e('Email', 'goody'); ?></th>
                        <th><?php esc_html_e('Phone', 'goody'); ?></th>
                        <th><?php esc_html_e('Message', 'goody'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($messages->have_posts()) : $messages->the_post(); ?>
                        <tr>
                            <td><?php echo esc_html(get_the_date('Y-m-d H:i')); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), 'goody_message_name', true)); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), 'goody_message_email', true)); ?></td>
                            <td><?php echo esc_html(get_post_meta(get_the_ID(), 'goody_message_phone', true)); ?></td>
                            <td><?php echo esc_html(get_the_content()); ?></td>
                        </tr>
                    <?php endwhile; wp_reset_postdata(); ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No contact messages yet.', 'goody'); ?></p>
        <?php endif; ?>

        <h2 style="margin-top:32px;"><?php esc_html_e('Newsletter Subscribers', 'goody'); ?></h2>
        <?php if (! empty($subscribers)) : ?>
            <table class="widefat striped">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Email', 'goody'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $subscriber_email) : ?>
                        <tr><td><?php echo esc_html($subscriber_email); ?></td></tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><?php esc_html_e('No subscribers yet.', 'goody'); ?></p>
        <?php endif; ?>
    </div>
    <?php
}

function goody_print_dynamic_css() {
    $options = goody_get_options();
    $css_value = static function ($key, $fallback = '') use ($options) {
        $value = trim((string) ($options[$key] ?? $fallback));
        if ($value === '') {
            $value = (string) $fallback;
        }

        $value = preg_replace('/[<>{};]/', '', $value);
        return $value !== '' ? $value : (string) $fallback;
    };

    $overlay = max(0, min(100, (int) ($options['hero_overlay_strength'] ?? 60))) / 100;
    $vars = [
        'primary-color' => $css_value('token_color_primary', '#a3db3f'),
        'secondary-green-color' => $css_value('token_color_primary_2', '#4fa93c'),
        'primary-hover-color' => $css_value('token_color_primary_hover', '#b8e567'),
        'button-text-color' => $css_value('token_color_button_text', '#07200f'),
        'reservation-button-color' => $css_value('reservation_button_color', '#4e2d1c'),
        'reservation-accent-color' => $css_value('reservation_accent_color', '#ff9b54'),
        'deep-background-color' => $css_value('token_color_bg_deep', '#020906'),
        'background-color' => $css_value('token_color_bg', '#07160f'),
        'soft-background-color' => $css_value('token_color_bg_soft', '#10251b'),
        'section-color' => $css_value('token_color_section', '#072a1d'),
        'card-color' => $css_value('token_color_card', '#0a1913'),
        'soft-card-color' => $css_value('token_color_card_soft', '#0f2d20'),
        'surface-color' => $css_value('token_color_surface', '#153024'),
        'text-color' => $css_value('token_color_text', '#f4f2e8'),
        'muted-color' => $css_value('token_color_muted', '#b5c3b8'),
        'color-primary' => $css_value('token_color_primary', '#a3db3f'),
        'color-primary-2' => $css_value('token_color_primary_2', '#4fa93c'),
        'color-primary-hover' => $css_value('token_color_primary_hover', '#b8e567'),
        'color-button-text' => $css_value('token_color_button_text', '#07200f'),
        'reservation-button-color' => $css_value('reservation_button_color', '#4e2d1c'),
        'reservation-accent-color' => $css_value('reservation_accent_color', '#ff9b54'),
        'goody-reservation-button' => 'var(--reservation-button-color)',
        'goody-reservation-button-text' => 'var(--color-button-text)',
        'goody-reservation-accent' => 'var(--reservation-accent-color)',
        'goody-reservation-accent-soft' => 'color-mix(in srgb,var(--reservation-accent-color) 16%,transparent)',
        'goody-reservation-surface' => 'color-mix(in srgb,var(--color-card) 92%,transparent)',
        'goody-reservation-surface-soft' => 'color-mix(in srgb,var(--color-card-soft) 88%,transparent)',
        'goody-reservation-border' => 'var(--color-border)',
        'color-bg-deep' => $css_value('token_color_bg_deep', '#020906'),
        'color-bg' => $css_value('token_color_bg', '#07160f'),
        'color-bg-soft' => $css_value('token_color_bg_soft', '#10251b'),
        'color-section' => $css_value('token_color_section', '#072a1d'),
        'color-card' => $css_value('token_color_card', '#0a1913'),
        'color-card-soft' => $css_value('token_color_card_soft', '#0f2d20'),
        'color-surface' => $css_value('token_color_surface', '#153024'),
        'color-text' => $css_value('token_color_text', '#f4f2e8'),
        'color-muted' => $css_value('token_color_muted', '#b5c3b8'),
        'color-border' => $css_value('token_color_border', 'rgba(142, 190, 152, 0.22)'),
        'color-shadow' => $css_value('token_color_shadow', 'rgba(0, 0, 0, 0.45)'),
        'radius-sm' => $css_value('token_radius_sm', '8px'),
        'radius-md' => $css_value('token_radius', '18px'),
        'radius-lg' => $css_value('token_radius_lg', '28px'),
        'reservation-card-radius' => $css_value('reservation_card_radius', '28px'),
        'shadow-soft' => $css_value('token_shadow', '0 20px 55px rgba(0,0,0,0.35)'),
        'space' => $css_value('token_space_section', '5rem'),
        'space-section' => $css_value('token_space_section', '5rem'),
        'container' => $css_value('token_container', '1240px'),
        'font-heading' => $css_value('token_font_heading', 'Cormorant Garamond, Georgia, Times New Roman, serif'),
        'font-body' => $css_value('token_font_body', 'Manrope, Segoe UI, sans-serif'),
        'font-accent' => $css_value('token_font_accent', 'Allura, Brush Script MT, cursive'),
        'reservation-font-family' => $css_value('reservation_font_family', $css_value('token_font_body', 'Manrope, Segoe UI, sans-serif')),
        'hero-overlay' => (string) $overlay,
    ];

    echo '<style id="goody-dynamic-vars">:root{';
    foreach ($vars as $var_name => $var_value) {
        echo '--' . esc_html($var_name) . ':' . $var_value . ';';
    }
    $dynamic_css = [
        '--color-primary:var(--primary-color);--color-primary-2:var(--secondary-green-color);--color-primary-hover:var(--primary-hover-color);--color-button-text:var(--button-text-color);--color-bg-deep:var(--deep-background-color);--color-bg:var(--background-color);--color-bg-soft:var(--soft-background-color);--color-section:var(--section-color);--color-card:var(--card-color);--color-card-soft:var(--soft-card-color);--color-surface:var(--surface-color);--color-text:var(--text-color);--color-muted:var(--muted-color);}',
        'body{font-family:var(--font-body);color:var(--color-text);background:radial-gradient(circle at 84% 6%,color-mix(in srgb,var(--color-primary) 14%,transparent),transparent 25%),radial-gradient(circle at 12% 18%,color-mix(in srgb,var(--color-primary-2) 11%,transparent),transparent 24%),linear-gradient(180deg,var(--color-bg-deep) 0%,var(--color-bg) 42%,var(--color-section) 100%);}',
        '.hero::before{background-image:linear-gradient(color-mix(in srgb,var(--color-primary) 8%,transparent) 1px,transparent 1px),linear-gradient(90deg,color-mix(in srgb,var(--color-primary) 8%,transparent) 1px,transparent 1px);opacity:.32;}',
        '.hero::after{background:radial-gradient(circle at 82% 56%,color-mix(in srgb,var(--color-primary-2) 24%,transparent),transparent 18%),radial-gradient(circle at 72% 9%,color-mix(in srgb,var(--color-primary) 18%,transparent),transparent 16%);}',
        '.hero__overlay{background:linear-gradient(92deg,color-mix(in srgb,var(--color-bg) 82%,var(--color-bg-deep) 18%) 0%,color-mix(in srgb,var(--color-bg) 68%,var(--color-bg-deep) 32%) 48%,color-mix(in srgb,var(--color-bg) 52%,var(--color-bg-deep) 48%) 100%);}',
        '.hero__video-wrap{position:absolute;inset:0;overflow:hidden;z-index:0;background:var(--color-bg-deep);} .hero__video{width:100%;height:100%;object-fit:cover;object-position:center;} .hero__iframe{position:absolute;top:50%;left:50%;width:100vw;height:56.25vw;min-width:177.78vh;min-height:100vh;border:0;transform:translate(-50%,-50%);pointer-events:none;} .hero--has-video{background-image:none!important;} @media (max-width:980px){.hero--has-video{min-height:clamp(560px,86vh,860px);} .hero--has-video .hero__video-wrap,.hero--has-video .hero__video,.hero--has-video .hero__iframe{inset:0;} .hero--has-video .hero__video{width:100%;height:100%;object-fit:cover;object-position:center center;} .hero__iframe{width:177.78vw;height:100vw;min-width:0;min-height:100%;max-width:none;max-height:none;}}',
        '.hero__headline-line{color:color-mix(in srgb,var(--color-text) 96%,var(--color-card) 4%);} .hero__headline-line--accent{color:color-mix(in srgb,var(--color-primary) 82%,var(--color-primary-2) 18%);} .hero__eyebrow{color:color-mix(in srgb,var(--color-primary) 58%,var(--color-text) 42%);} .hero__eyebrow-line{background:color-mix(in srgb,var(--color-primary) 62%,transparent);} ',
        '.hero__content p{color:color-mix(in srgb,var(--color-text) 52%,var(--color-muted) 48%);} .hero-stat__value{color:color-mix(in srgb,var(--color-text) 90%,var(--color-card) 10%);} .hero-stat__label{color:color-mix(in srgb,var(--color-text) 62%,var(--color-muted) 38%);} .hero__content .button--ghost,.hero__content .button--outline,.hero__content .button--hero-secondary{color:var(--color-text);border-color:color-mix(in srgb,var(--color-primary) 24%,var(--color-border));background:color-mix(in srgb,var(--color-card) 82%,transparent);} .hero__content .button--ghost svg,.hero__content .button--outline svg,.hero__content .button--hero-secondary svg{fill:currentColor;color:currentColor;} .hero__content .button--ghost:hover,.hero__content .button--outline:hover,.hero__content .button--hero-secondary:hover{color:color-mix(in srgb,var(--color-text) 92%,var(--color-card) 8%);border-color:color-mix(in srgb,var(--color-primary) 40%,var(--color-border));background:color-mix(in srgb,var(--color-card) 90%,transparent);} ',
        '.hero-stat__icon{border-color:color-mix(in srgb,var(--color-primary) 32%,transparent);background:var(--color-primary),color-mix(in srgb,var(--color-bg-deep) 92%,transparent));color:color-mix(in srgb,var(--color-primary) 66%,var(--color-text) 34%);} .hero-stat--clock .hero-stat__icon{color:color-mix(in srgb,var(--color-text) 74%,var(--color-muted) 26%);} .hero-stat--delivery .hero-stat__icon,.hero-stat--calendar .hero-stat__icon{color:var(--button-text-color);} ',
        '.hero__review-chip,.hero__visual-note{background:color-mix(in srgb,var(--card-color) 92%,transparent);border-color:color-mix(in srgb,var(--color-primary) 24%,var(--color-border));}',
        '.hero__review-chip{left:1rem;bottom:1rem;}.hero__visual-note{right:.7rem;bottom:.7rem;}',
        '.hero__review-chip strong,.hero__visual-note strong{display:block;max-width:100%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:color-mix(in srgb,var(--color-text) 92%,var(--color-card) 8%);} .hero__review-chip small,.hero__visual-note small{color:color-mix(in srgb,var(--color-text) 60%,var(--color-muted) 40%);} .hero__visual-note span{color:var(--reservation-accent-color);} .hero__review-stars{color:var(--reservation-accent-color);}',
        'h1,h2,h3,h4,h5,h6{font-family:var(--font-heading);}.hero__headline-line--accent,.accent-italic{font-family:var(--font-accent);color:var(--color-muted);}.eyebrow,.site-title,a:hover{color:var(--color-primary);} .hero__content a:hover{color:color-mix(in srgb,var(--color-primary) 72%,var(--color-text) 28%);}',
        '.button{background:var(--color-primary);color:var(--color-button-text);}.button:hover,.button:focus-visible{background:var(--color-primary-hover);border-color:var(--color-primary-hover);color:var(--color-button-text);}',
        '.goody-reservation-shell .button,.goody-item-select{    background: var(--color-text);color: var(--button-text-color);}.goody-reservation-shell .button:hover,.goody-reservation-shell .button:focus-visible,.goody-item-select:hover,.goody-item-select:focus-visible{background:var(--color-primary-hover);border-color:var(--color-primary-hover);color:var(--goody-reservation-button-text);}',
        '.button--ghost,.button--outline,.button--hero-secondary,.goody-reservation-shell .button--ghost{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg-soft) 72%,transparent);color:var(--color-text);} .button--hero-secondary svg,.button--ghost svg,.button--outline svg{fill:currentColor;color:currentColor;}',
        '.card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 92%,transparent));}.badge{border-color:color-mix(in srgb,var(--color-primary) 45%,transparent);background:color-mix(in srgb,var(--color-primary) 16%,transparent);color:var(--color-primary-hover);}',
        'input,select,textarea{font-family:var(--font-body);border-color:var(--color-border);background:var(--goody-theme-card,var(--color-card));color:var(--color-text);}.site-header{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg-deep) 86%,transparent);}.site-navigation a{color:var(--color-text);}',
        '#menu,.offers-zone,.reserve-zone,.page-section--soft{background:linear-gradient(180deg,var(--color-bg-deep) 0%,var(--color-bg) 100%);}',
        '.site-footer,.site-footer *{border-color:var(--color-border);} .site-footer{background:linear-gradient(180deg,color-mix(in srgb,var(--color-bg-deep) 96%,transparent),color-mix(in srgb,var(--color-bg) 94%,transparent));} .site-footer p,.site-footer li,.site-footer a{color:color-mix(in srgb,var(--color-text) 76%,var(--color-muted));}',
        '.hero,.menu-zone,.about-zone,.reviews-zone,.contact-zone,#events,.offers-zone,.reserve-zone,.reserve-zone--showcase,.page-section{color:var(--color-text);} .hero p,.menu-card-desc,.about-content p,.review-text,.contact-text-value,.delivery-description,.offer-card__content p,.event-card__content p{color:color-mix(in srgb,var(--color-text) 74%,var(--color-muted));}',
        '.menu-card,.offer-card,.event-card,.team-card,.review-card,.contact-card,.goody-status-card,.goody-sidebar-card,.goody-booking-card,.goody-reservation-panel,.news-card,.account-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));box-shadow:none;transition:box-shadow .24s ease,transform .24s ease,border-color .24s ease;} .menu-card:hover,.offer-card:hover,.event-card:hover,.team-card:hover,.review-card:hover,.contact-card:hover,.goody-status-card:hover,.goody-sidebar-card:hover,.goody-booking-card:hover,.goody-reservation-panel:hover,.news-card:hover,.account-card:hover{box-shadow:0 16px 34px color-mix(in srgb,var(--color-shadow) 64%,transparent);}',
        '.section-heading h2,.menu-heading__copy h2{color:color-mix(in srgb,var(--color-text) 92%,var(--color-card) 8%);} .section-heading p,.menu-heading__copy p,.menu-heading__status strong,.menu-heading__status-label{color:color-mix(in srgb,var(--color-text) 66%,var(--color-muted) 34%);} .menu-heading__status{box-shadow:none;transition:box-shadow .24s ease,transform .24s ease,border-color .24s ease;} .menu-heading__status:hover{box-shadow:0 12px 28px color-mix(in srgb,var(--color-shadow) 56%,transparent);}',
        '.menu-filters{border-color:var(--color-border);background:transparent;box-shadow:none;} .menu-filters__advanced{border-color:color-mix(in srgb,var(--color-primary) 20%,var(--color-border));background:color-mix(in srgb,var(--color-surface) 86%,transparent);} .menu-filters__advanced select,.menu-filters__advanced input,.menu-filters__field span,.menu-filters__check span{color:var(--color-text);}',
        '.menu-filter-chip{border-color:color-mix(in srgb,var(--color-primary) 18%,var(--color-border));background:color-mix(in srgb,var(--color-card) 88%,transparent);color:color-mix(in srgb,var(--color-text) 80%,var(--color-muted) 20%);} .menu-filter-chip:hover,.menu-filter-chip:focus-visible{border-color:color-mix(in srgb,var(--color-primary) 44%,var(--color-border));background:color-mix(in srgb,var(--color-card) 94%,transparent);} .menu-filter-chip.is-active{border-color:color-mix(in srgb,var(--color-primary) 70%,transparent);background:color-mix(in srgb,var(--color-primary) 18%,transparent);color:var(--color-text);}',
        '.goody-direct-order-modal__dialog{border-color:color-mix(in srgb,var(--color-primary) 24%,var(--color-border));background:radial-gradient(circle at 88% 0%,color-mix(in srgb,var(--color-primary) 14%,transparent),transparent 34%),linear-gradient(180deg,color-mix(in srgb,var(--color-bg-deep) 94%,transparent),color-mix(in srgb,var(--color-bg) 96%,transparent));} .goody-direct-order-modal__copy h3,.goody-direct-order-modal__copy p,.goody-direct-order-modal__copy span{color:var(--color-text);} .goody-direct-order-form--modal .goody-provider-select span,.goody-direct-order-form--modal .goody-direct-order-form__quantity span{color:color-mix(in srgb,var(--color-text) 72%,var(--color-muted) 28%);} ',
        '[data-menu-results] .menu-card,[data-menu-results] .card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));} [data-menu-results] .menu-card h3,[data-menu-results] .menu-card h3 a,[data-menu-results] .menu-card__pricing strong{color:var(--color-text);} [data-menu-results] .menu-card p,[data-menu-results] .menu-card__meta span,[data-menu-results] .menu-card__details-label{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);}',
        '.reserve-zone--showcase,.reserve-showcase{background:transparent;} .reserve-showcase-kicker{color:color-mix(in srgb,var(--reservation-accent-color) 74%,var(--color-primary) 26%);} .reserve-showcase h2,.reserve-showcase h3,.reserve-showcase strong{color:var(--color-text);} .reserve-showcase p,.reserve-showcase li,.reserve-showcase small,.reserve-showcase span{color:color-mix(in srgb,var(--color-text) 70%,var(--color-muted) 30%);} .reserve-showcase .card,.reserve-showcase__embed,.reserve-form,.reserve-embed,.reserve-info{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));} .reserve-booking__panel,.reserve-delivery-card{border-color:var(--color-border);background:var(--color-card);}',
        '.reserve-delivery-card,.reserve-booking__panel{box-shadow:none;transition:box-shadow .24s ease,transform .24s ease,border-color .24s ease,color .24s ease;} .reserve-delivery-card:hover,.reserve-booking__panel:hover{box-shadow:0 16px 34px color-mix(in srgb,var(--color-shadow) 62%,transparent);} .reserve-delivery-card strong{color:color-mix(in srgb,var(--color-text) 88%,var(--color-card) 12%);} .reserve-delivery-card small{color:color-mix(in srgb,var(--color-text) 62%,var(--color-muted) 38%);} .reserve-delivery-card:hover strong{color:color-mix(in srgb,var(--color-card) 92%,var(--color-text) 8%);} .reserve-delivery-card:hover small{color:color-mix(in srgb,var(--color-card) 74%,var(--color-muted) 26%);} ',
        '.page-section.about-zone,.about-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .about-zone .section-heading h2,.about-zone h3,.about-zone strong{color:var(--color-text);} .about-zone .section-heading p,.about-zone p,.about-zone li,.about-zone small,.about-zone span{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);} .about-zone .card,.about-zone .about-box,.about-zone .about-value-card{border-color:var(--color-border);background:var(--color-card);box-shadow:none;transition:box-shadow .24s ease,transform .24s ease,border-color .24s ease;} .about-zone .about-box:hover,.about-zone .about-value-card:hover,.about-zone .card:hover{box-shadow:0 16px 34px color-mix(in srgb,var(--color-shadow) 60%,transparent);}',
        '.page-section.gallery-zone,.gallery-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 76%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .gallery-zone .section-heading h2,.gallery-zone h3,.gallery-zone strong{color:var(--color-text);} .gallery-zone .section-heading p,.gallery-zone p,.gallery-zone li,.gallery-zone small,.gallery-zone span{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);} .gallery-zone .card,.gallery-zone .goody-mosaic__item,.gallery-zone .gallery-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));} .goody-mosaic{background:linear-gradient(180deg,color-mix(in srgb,var(--color-surface) 74%,transparent),color-mix(in srgb,var(--color-bg) 96%,transparent));border:0;border-radius:0;box-shadow:none;} .goody-mosaic::before{background:radial-gradient(circle at 84% 18%,color-mix(in srgb,var(--color-primary) 14%,transparent),transparent 58%),radial-gradient(circle at 12% 78%,color-mix(in srgb,var(--reservation-accent-color) 12%,transparent),transparent 54%);} ',
        '.page-section.testimonials-zone,.testimonials-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 74%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .testimonials-zone .section-heading h2,.testimonials-zone h3,.testimonials-zone strong{color:var(--color-text);} .testimonials-zone .section-heading p,.testimonials-zone p,.testimonials-zone li,.testimonials-zone small{color:color-mix(in srgb,var(--color-text) 76%,var(--color-muted) 24%);} .testimonials-zone span{color:color-mix(in srgb,var(--color-text) 84%,var(--color-muted) 16%);} .testimonials-zone .card,.testimonials-zone .review-card,.testimonials-zone .testimonial-card{border-color:var(--color-border);background:var(--color-card);} .testimonials-zone .review-stars,.testimonials-zone .rating-stars{color:var(--reservation-accent-color);}',
        '.testimonials-zone .card,.testimonials-zone .review-card,.testimonials-zone .testimonial-card,.testimonials-zone .reviews-embed,.testimonials-zone .review-cta__actions{box-shadow:none;transition:box-shadow .24s ease,transform .24s ease,border-color .24s ease;} .testimonials-zone .card:hover,.testimonials-zone .reviews-embed:hover,.testimonials-zone .review-cta__actions:hover{box-shadow:none;} .testimonials-zone .review-card:hover,.testimonials-zone .testimonial-card:hover{box-shadow:0 16px 34px color-mix(in srgb,var(--color-shadow) 60%,transparent);} .testimonials-zone .review-score,.testimonials-zone .review-score strong,.testimonials-zone .review-score span{color:color-mix(in srgb,var(--color-text) 86%,var(--color-card) 14%);} .testimonials-zone .review-score small{color:color-mix(in srgb,var(--color-text) 62%,var(--color-muted) 38%);} ',
        '.archive-grid.archive-grid--three.review-grid .review-card{border-color:var(--color-border)!important;background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent))!important;} .archive-grid.archive-grid--three.review-grid .review-card h3,.archive-grid.archive-grid--three.review-grid .review-card strong{color:var(--color-text)!important;} .archive-grid.archive-grid--three.review-grid .review-card p,.archive-grid.archive-grid--three.review-grid .review-card small,.archive-grid.archive-grid--three.review-grid .review-card span{color:color-mix(in srgb,var(--color-text) 74%,var(--color-muted) 26%)!important;}',
        '.page-section.news-zone,.news-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 74%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .news-zone .section-heading h2,.news-zone h3,.news-zone strong{color:var(--color-text);} .news-zone .section-heading p,.news-zone p,.news-zone li,.news-zone small,.news-zone span{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);} .news-zone .card,.news-zone .news-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));}',
        '.newsletter-zone,.newsletter-card,.page-section.newsletter-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .newsletter-card,.newsletter-zone .card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));} .newsletter-zone h2,.newsletter-zone h3,.newsletter-zone strong,.newsletter-card h2,.newsletter-card h3{color:var(--color-text);} .newsletter-zone p,.newsletter-zone small,.newsletter-zone span,.newsletter-card p{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);}',
        '#account,.account-zone,.page-section.account-zone{background:radial-gradient(circle at 12% 10%,color-mix(in srgb,var(--color-primary) 16%,transparent),transparent 28%),radial-gradient(circle at 92% 90%,color-mix(in srgb,var(--color-primary-2) 14%,transparent),transparent 36%),linear-gradient(180deg,color-mix(in srgb,var(--color-section) 74%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} #account .section-heading h2,#account h3,#account strong,.account-zone .section-heading h2,.account-zone h3,.account-zone strong{color:var(--color-text);} #account .section-heading p,#account p,#account li,#account small,#account span,.account-zone p,.account-zone li,.account-zone small,.account-zone span{color:color-mix(in srgb,var(--color-text) 70%,var(--color-muted) 30%);} #account .card,#account .account-card,.account-zone .card,.account-zone .account-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));}',
        '#events,.events-zone,.page-section.events-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 74%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} #events .section-heading h2,#events h3,#events strong,.events-zone .section-heading h2,.events-zone h3,.events-zone strong{color:var(--color-text);} #events .section-heading p,#events p,#events li,#events small,#events span,.events-zone p,.events-zone small,.events-zone span{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);} #events .card,#events .event-card,.events-zone .card,.events-zone .event-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));}',
        '#contact,.contact-zone,.page-section.contact-zone{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 74%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} #contact .section-heading h2,#contact h3,#contact strong,.contact-zone .section-heading h2,.contact-zone h3,.contact-zone strong{color:var(--color-text);} #contact .section-heading p,#contact p,#contact li,#contact small,#contact span,.contact-zone p,.contact-zone small,.contact-zone span{color:color-mix(in srgb,var(--color-text) 68%,var(--color-muted) 32%);} #contact .card,#contact .contact-card,.contact-zone .card,.contact-zone .contact-card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 70%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent));}',
        '.goody-page-section--reservation{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent));} .goody-page-section--reservation h1,.goody-page-section--reservation h2,.goody-page-section--reservation h3,.goody-page-section--reservation strong{color:color-mix(in srgb,var(--color-text) 94%,var(--color-card) 6%);} .goody-page-section--reservation p,.goody-page-section--reservation li,.goody-page-section--reservation small,.goody-page-section--reservation span,.goody-page-section--reservation label{color:color-mix(in srgb,var(--color-text) 78%,var(--color-muted) 22%);} .goody-page-section--reservation li{background:transparent;box-shadow:none;} .goody-page-section.goody-page-section--reservation p,.goody-page-section.goody-page-section--reservation li,.goody-page-section.goody-page-section--reservation small,.goody-page-section.goody-page-section--reservation label{color:color-mix(in srgb,var(--color-text) 78%,var(--color-muted) 22%)!important;} .goody-page-section--reservation .card,.goody-page-section--reservation .goody-booking-card,.goody-page-section--reservation .goody-sidebar-card,.goody-page-section--reservation .goody-status-card,.goody-page-section--reservation .goody-reservation-panel,.goody-page-section--reservation .goody-reservation-shell{border-color:color-mix(in srgb,var(--color-border) 88%,var(--color-primary) 12%);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 68%,transparent),color-mix(in srgb,var(--color-card) 96%,transparent));} .goody-page-section--reservation .goody-step-counter,.goody-page-section--reservation .goody-reservation-kicker,.goody-page-section--reservation .goody-summary-line--grand strong,.goody-page-section--reservation .goody-summary-line--pay strong{color:color-mix(in srgb,var(--reservation-accent-color) 78%,var(--color-primary) 22%);}',
        '.tag,.pill,.badge,.delivery-pill,.event-meta span,.offer-meta span,.event-card__date,.reserve-delivery-card{border-color:color-mix(in srgb,var(--color-primary) 26%,var(--color-border));background:color-mix(in srgb,var(--color-surface) 86%,transparent);color:var(--color-text);}',
        'input:focus,select:focus,textarea:focus,.goody-search-modal__form input:focus{border-color:color-mix(in srgb,var(--color-primary-hover) 72%,white 28%);box-shadow:0 0 0 3px color-mix(in srgb,var(--color-primary) 22%,transparent);}',
        '.site-navigation a::before,.goody-search-result__type,.reserve-showcase-kicker,.tracking-step.is-active .tracking-step__dot{color:var(--color-primary);} .button--ghost:hover,.button--outline:hover,.site-navigation.site-navigation--dropdown-enabled .sub-menu a:hover{border-color:color-mix(in srgb,var(--color-primary) 42%,var(--color-border));}',
        '.goody-reservation-shell{font-family:var(--reservation-font-family);background:radial-gradient(circle at 86% 8%,color-mix(in srgb,var(--goody-reservation-accent) 13%,transparent),transparent 24%),linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent) 0%,color-mix(in srgb,var(--color-bg) 98%,transparent) 100%);border-color:var(--color-border);}.goody-reservation-shell h2,.goody-reservation-shell h3,.goody-reservation-shell h4{font-family:var(--font-heading);}',
        '.goody-reservation-kicker,.goody-step-counter,.goody-summary-line--grand strong,.goody-summary-line--pay strong{color:var(--goody-reservation-accent);}.goody-filter-pill.is-active,.goody-slot-card.is-selected{background:var(--goody-reservation-button);color:var(--goody-reservation-button-text);}.goody-inline-empty,.goody-notice{background:var(--goody-reservation-accent-soft);border-color:color-mix(in srgb,var(--goody-reservation-accent) 30%,transparent);color:var(--goody-reservation-accent);}',
        '.goody-status-card,.goody-sidebar-card,.goody-reservation-panel,.goody-booking-card{border-color:var(--color-border);background:linear-gradient(180deg,var(--goody-reservation-surface),color-mix(in srgb,var(--color-bg) 96%,transparent));}.goody-booking-card.is-selected{border-color:var(--goody-reservation-accent);box-shadow:0 24px 44px color-mix(in srgb,var(--goody-reservation-accent) 16%,transparent);}',
        '.tracking-box,.tracking-steps-wrap,.tracking-timeline,.tracking-orders-list{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg) 72%,transparent);}.tracking-box--primary{background:radial-gradient(circle at 100% 0%,color-mix(in srgb,var(--color-primary) 10%,transparent),transparent 34%),linear-gradient(160deg,color-mix(in srgb,var(--color-surface) 88%,transparent),color-mix(in srgb,var(--color-bg) 94%,transparent));}.tracking-step.is-done .tracking-step__dot,.tracking-event.is-done .tracking-event__dot{background:var(--color-primary);border-color:color-mix(in srgb,var(--color-primary) 84%,white 16%);}',
        '.site-header,.site-navigation,.menu-heading,.menu-filters,.menu-filters__advanced,.menu-card,.offer-card,.event-card,.team-card,.review-card,.news-card,.account-card,.contact-card,.about-box,.about-value-card,.goody-direct-order-modal__dialog,.reserve-delivery-card,.reserve-booking__panel,.reserve-showcase__embed,.reserve-form,.reserve-embed,.reserve-info,.tracking-box,.tracking-steps-wrap,.tracking-timeline,.tracking-orders-list,.newsletter-card,.reviews-embed,.review-cta__actions,.goody-status-card,.goody-sidebar-card,.goody-reservation-panel,.goody-booking-card{border-color:var(--color-border)!important;}',
        '.site-header,.menu-filters__advanced,.menu-card,.offer-card,.event-card,.team-card,.review-card,.news-card,.account-card,.contact-card,.about-box,.about-value-card,.goody-direct-order-modal__dialog,.reserve-showcase__embed,.reserve-form,.reserve-embed,.reserve-info,.tracking-box,.tracking-steps-wrap,.tracking-timeline,.tracking-orders-list,.newsletter-card,.reviews-embed,.goody-status-card,.goody-sidebar-card,.goody-reservation-panel,.goody-booking-card{background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 94%,transparent))!important;} .menu-filters{background:transparent!important;box-shadow:none!important;} .reserve-booking__panel,.reserve-delivery-card{background:var(--color-card)!important;} .review-cta__actions{background:transparent!important;}',
        '.site-header a,.site-navigation a,.menu-heading h2,.menu-heading h3,.section-heading h2,.menu-card h3,.menu-card h3 a,.offer-card h3,.event-card h3,.review-card h3,.news-card h3,.account-card h3,.contact-card h3,.about-box h3,.about-value-card h3,.reserve-showcase h2,.reserve-showcase h3,.reserve-showcase strong,.testimonials-zone h2,.testimonials-zone h3,.news-zone h2,.news-zone h3,.newsletter-zone h2,.newsletter-zone h3,.contact-zone h2,.contact-zone h3,#events h2,#events h3,.goody-page-section--reservation h1,.goody-page-section--reservation h2,.goody-page-section--reservation h3{color:var(--color-text)!important;}',
        '.menu-heading p,.menu-heading span,.section-heading p,.menu-card p,.menu-card__meta span,.menu-card__details-label,.offer-card p,.event-card p,.review-card p,.news-card p,.account-card p,.contact-card p,.about-box p,.about-value-card p,.reserve-showcase p,.reserve-showcase li,.reserve-showcase small,.reserve-showcase span,.testimonials-zone p,.testimonials-zone li,.testimonials-zone small,.testimonials-zone span,.news-zone p,.news-zone li,.news-zone small,.news-zone span,.newsletter-zone p,.newsletter-zone small,.newsletter-zone span,.contact-zone p,.contact-zone li,.contact-zone small,.contact-zone span,#events p,#events li,#events small,#events span,.goody-page-section--reservation p,.goody-page-section--reservation li,.goody-page-section--reservation small,.goody-page-section--reservation span,.goody-page-section--reservation label{color:color-mix(in srgb,var(--color-text) 74%,var(--color-muted) 26%)!important;}',
        '.single .cpt-single,.single .cpt-single .page-section{background:linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent),color-mix(in srgb,var(--color-bg) 98%,transparent))!important;} .single .cpt-single .card,.single .cpt-single__hero,.single .cpt-single__panel{border-color:color-mix(in srgb,var(--color-primary) 20%,var(--color-border))!important;background:var(--color-card)!important;} .single .cpt-single h1,.single .cpt-single h2,.single .cpt-single h3,.single .cpt-single strong{color:color-mix(in srgb,var(--color-text) 94%,var(--color-card) 6%)!important;} .single .cpt-single p,.single .cpt-single li,.single .cpt-single small,.single .cpt-single span,.single .cpt-single label{color:var(--button-text-color)!important;} .single .cpt-single__crumbs,.single .cpt-single__crumbs a{color:color-mix(in srgb,var(--color-text) 70%,var(--color-muted) 30%)!important;} .single .cpt-single__crumbs strong{color:var(--color-text)!important;}',
        '.button,.button--hero-secondary,.button--ghost,.button--outline,.menu-card__order,.goody-direct-order-form__submit{color:var(--color-button-text)!important;} .button--ghost,.button--outline,.button--hero-secondary{color:var(--color-text)!important;background:color-mix(in srgb,var(--color-bg-soft) 72%,transparent)!important;border-color:var(--color-border)!important;} .button svg,.button--ghost svg,.button--outline svg,.button--hero-secondary svg{fill:currentColor!important;color:currentColor!important;}',
        '.badge,.tag,.pill,.delivery-pill,.event-meta span,.offer-meta span,.event-card__date,.menu-filter-chip.is-active{background:color-mix(in srgb,var(--color-primary) 16%,transparent)!important;border-color:color-mix(in srgb,var(--color-primary) 45%,var(--color-border))!important;color:var(--color-text)!important;}',
    ];
    echo implode('', $dynamic_css) . '</style>';
}
add_action('wp_head', 'goody_print_dynamic_css', 20);

function goody_print_maps_callback_stub() {
    ?>
    <script id="goody-map-callback-stub">
        window.goodyMapReady = window.goodyMapReady || false;
        if (typeof window.initMap !== 'function') {
            window.initMap = function () {
                window.goodyMapReady = true;
                try {
                    window.dispatchEvent(new Event('goodyMapReady'));
                } catch (error) {
                    if (document && document.createEvent) {
                        var evt = document.createEvent('Event');
                        evt.initEvent('goodyMapReady', true, true);
                        window.dispatchEvent(evt);
                    }
                }
            };
        }
    </script>
    <?php
}
add_action('wp_head', 'goody_print_maps_callback_stub', 5);

function goody_print_custom_code_head() {
    $head_code = goody_get_option('integrations_custom_head_code');
    if ($head_code) {
        echo do_shortcode($head_code);
    }
}
add_action('wp_head', 'goody_print_custom_code_head', 99);

function goody_print_custom_code_footer() {
    $footer_code = goody_get_option('integrations_custom_footer_code');
    if ($footer_code) {
        echo do_shortcode($footer_code);
    }
}
add_action('wp_footer', 'goody_print_custom_code_footer', 99);

function goody_print_map_reviews_runtime_fallback() {
    if (is_admin()) {
        return;
    }

    if (
        (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_account_page') && is_account_page())
        || (function_exists('is_wc_endpoint_url') && (is_wc_endpoint_url('order-pay') || is_wc_endpoint_url('order-received')))
    ) {
        return;
    }
    ?>
    <script id="goody-map-reviews-runtime-fallback">
        (function () {
            var isLocalDevHost = function () {
                var host = String(window.location.hostname || '').toLowerCase();
                return host === 'localhost' || host === '127.0.0.1' || host === '::1' || host.slice(-6) === '.local';
            };

            if (isLocalDevHost()) {
                return;
            }

            var onReady = function (fn) {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', fn, { once: true });
                    return;
                }
                fn();
            };

            var readGoogleKey = function () {
                var raw = '';
                if (window.goodyTheme && typeof window.goodyTheme.mapsApiKey === 'string') {
                    raw = window.goodyTheme.mapsApiKey;
                }
                if (!raw && window.goodyTheme && typeof window.goodyTheme.googleReviewsApiKey === 'string') {
                    raw = window.goodyTheme.googleReviewsApiKey;
                }
                var m = String(raw || '').match(/(AIza[0-9A-Za-z_-]{20,})/);
                return m && m[1] ? m[1] : '';
            };

            onReady(function () {
                window.setTimeout(function () {
                    var mapTarget = document.querySelector('[data-goody-map]');
                    if (mapTarget && mapTarget.getAttribute('data-map-ready') !== '1' && !mapTarget.querySelector('iframe')) {
                        var query = String(mapTarget.getAttribute('data-address') || mapTarget.getAttribute('data-title') || '').trim();
                        if (query) {
                            var iframe = document.createElement('iframe');
                            iframe.loading = 'lazy';
                            iframe.referrerPolicy = 'no-referrer-when-downgrade';
                            iframe.src = 'https://www.google.com/maps?q=' + encodeURIComponent(query) + '&output=embed';
                            iframe.style.width = '100%';
                            iframe.style.minHeight = '340px';
                            iframe.style.border = '0';
                            mapTarget.innerHTML = '';
                            mapTarget.appendChild(iframe);
                            mapTarget.setAttribute('data-map-ready', '1');
                        }
                    }
                }, 5000);

                var reviewsRoot = document.querySelector('#reviews');
                if (!reviewsRoot || reviewsRoot.querySelector('[data-review-card]')) {
                    return;
                }

                var placeRaw = String(reviewsRoot.getAttribute('data-google-place-id') || '').trim();
                var placeMatch = placeRaw.match(/\b(ChI[0-9A-Za-z_-]{10,})\b/);
                var placeId = placeMatch && placeMatch[1] ? placeMatch[1] : '';
                var apiKey = readGoogleKey();
                if (!placeId || !apiKey) {
                    return;
                }

                var boot = function () {
                    if (!(window.google && google.maps && google.maps.places) || reviewsRoot.querySelector('[data-review-card]')) {
                        return;
                    }
                    var holder = document.createElement('div');
                    new google.maps.places.PlacesService(holder).getDetails({ placeId: placeId, fields: ['reviews'] }, function (res, status) {
                        if (!(status === google.maps.places.PlacesServiceStatus.OK && res && Array.isArray(res.reviews) && res.reviews.length) || reviewsRoot.querySelector('[data-review-card]')) {
                            return;
                        }
                        var grid = reviewsRoot.querySelector('.review-grid, .archive-grid.review-grid, .archive-grid--three.review-grid');
                        if (!grid) {
                            grid = document.createElement('div');
                            grid.className = 'archive-grid archive-grid--three review-grid';
                            var cta = reviewsRoot.querySelector('.review-cta');
                            if (cta && cta.parentNode) {
                                cta.parentNode.insertBefore(grid, cta);
                            } else {
                                reviewsRoot.appendChild(grid);
                            }
                        }
                        res.reviews.slice(0, 6).forEach(function (r) {
                            var rating = Math.max(1, Math.min(5, parseInt(r.rating || 0, 10) || 5));
                            var card = document.createElement('article');
                            card.className = 'card testimonial-card testimonial-card--google';
                            card.setAttribute('data-review-card', '');
                            card.innerHTML = '<div class="rating">' + Array(rating + 1).join('<span class="icon-star">★</span>') + '</div><div class="testimonial-card__content"></div><div class="testimonial-card__author"><div><strong></strong><span></span></div></div>';
                            card.querySelector('.testimonial-card__content').textContent = String(r.text || '').trim() || 'Great experience.';
                            card.querySelector('strong').textContent = String(r.author_name || 'Google User').trim();
                            card.querySelector('span').textContent = String(r.relative_time_description || 'Google review').trim();
                            grid.appendChild(card);
                        });
                    });
                };

                if (window.google && google.maps && google.maps.places) {
                    boot();
                    return;
                }

                var existing = document.querySelector('script[src*="maps.googleapis.com/maps/api/js"]');
                if (existing) {
                    existing.addEventListener('load', boot, { once: true });
                    return;
                }

                var script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=' + encodeURIComponent(apiKey) + '&libraries=places&loading=async';
                script.async = true;
                script.defer = true;
                script.addEventListener('load', boot, { once: true });
                document.head.appendChild(script);
            });
        }());
    </script>
    <?php
}
add_action('wp_footer', 'goody_print_map_reviews_runtime_fallback', 100);

function goody_clear_delivery_cache_on_options_update($old_value, $value) {
    $old = is_array($old_value) ? $old_value : [];
    $new = is_array($value) ? $value : [];

    $option_changed = static function ($key) use ($old, $new) {
        $old_raw = array_key_exists($key, $old) ? $old[$key] : null;
        $new_raw = array_key_exists($key, $new) ? $new[$key] : null;

        if (is_array($old_raw) || is_array($new_raw) || is_object($old_raw) || is_object($new_raw)) {
            return wp_json_encode($old_raw) !== wp_json_encode($new_raw);
        }

        return (string) $old_raw !== (string) $new_raw;
    };

    $delivery_related_keys = [
        'delivery_data_source',
        'glovo_api_url',
        'glovo_api_token',
        'ubereats_api_url',
        'ubereats_api_token',
        'deliveroo_api_url',
        'deliveroo_api_token',
        'glovo_url',
        'ubereats_url',
        'deliveroo_url',
        'ubereats_environment',
        'ubereats_api_base_url',
        'ubereats_oauth_token_url',
        'ubereats_oauth_scope',
        'ubereats_order_create_api_url',
    ];
    $reviews_related_keys = [
        'google_reviews_place_id',
        'google_reviews_count',
        'restaurant_name',
        'contact_address',
        'trustpilot_api_url',
        'custom_reviews_api_url',
        'integrations_reviews_api_key',
        'integrations_google_reviews_api_key',
        'integrations_serpapi_api_key',
        'trustpilot_api_token',
        'custom_reviews_api_token',
        'reviews_api_provider',
    ];
    $tracking_related_keys = [
        'tracking_url',
    ];

    $delivery_related_changed = false;
    foreach ($delivery_related_keys as $key) {
        if ($option_changed($key)) {
            $delivery_related_changed = true;
            break;
        }
    }

    $reviews_related_changed = false;
    foreach ($reviews_related_keys as $key) {
        if ($option_changed($key)) {
            $reviews_related_changed = true;
            break;
        }
    }

    $tracking_related_changed = false;
    foreach ($tracking_related_keys as $key) {
        if ($option_changed($key)) {
            $tracking_related_changed = true;
            break;
        }
    }

    if (! $delivery_related_changed && ! $reviews_related_changed && ! $tracking_related_changed) {
        return;
    }

    if ($delivery_related_changed) {
        delete_transient('goody_delivery_glovo_link');
        delete_transient('goody_delivery_ubereats_link');
        delete_transient('goody_delivery_deliveroo_link');
        delete_transient('goody_ubereats_oauth_access_token');
        delete_transient('goody_ubereats_oauth_last_error');
    }

    $reviews_source = $value['google_reviews_place_id'] ?? '';
    $place_id = goody_extract_google_place_id($reviews_source);
    $cid = goody_extract_google_cid($reviews_source);
    $data_id = goody_extract_serpapi_data_id($reviews_source);
    $trustpilot_url = goody_normalize_url_input($value['trustpilot_api_url'] ?? '');
    $custom_url = goody_normalize_url_input($value['custom_reviews_api_url'] ?? '');
    $count = absint($value['google_reviews_count'] ?? 6);
    if ($count < 1) {
        $count = 6;
    }
    $count = min(10, $count);
    $sources = [];
    if ($place_id) {
        $sources[] = 'pid:' . $place_id;
        $sources[] = 'serp:' . $place_id;
    }
    if ($cid) {
        $sources[] = 'cid:' . $cid;
        $sources[] = 'serp:' . $cid;
    }
    if ($data_id) {
        $sources[] = 'serp:' . $data_id;
    }

    $raw_source = trim((string) $reviews_source);
    if ($raw_source !== '') {
        $sources[] = 'serp:' . $raw_source;
    }
    if ($trustpilot_url !== '') {
        $sources[] = 'trustpilot:' . $trustpilot_url . '|' . $raw_source;
    }
    if ($custom_url !== '') {
        $sources[] = 'custom:' . $custom_url . '|' . $raw_source;
    }

    if ($reviews_related_changed) {
        $delete_reviews_cache = static function ($key) {
            delete_transient($key);
            delete_transient($key . '_empty');
        };

        foreach (array_unique($sources) as $source) {
            $delete_reviews_cache('goody_google_reviews_' . md5($source . '|' . $count));
        }

        // Clear cache keys used by current review fetch logic.
        if ($place_id) {
            $delete_reviews_cache('goody_google_reviews_' . md5('pid:' . $place_id . '|' . $count));
        }
        if ($cid) {
            $delete_reviews_cache('goody_google_reviews_' . md5('cid:' . $cid . '|' . $count));
        }

        $serp_sources = [];
        if ($data_id) {
            $serp_sources[] = $data_id;
        }
        if ($place_id) {
            $serp_sources[] = $place_id;
        }
        if ($cid) {
            $serp_sources[] = $cid;
        }
        if ($raw_source !== '') {
            $serp_sources[] = $raw_source;
        }
        $restaurant_name = trim((string) ($value['restaurant_name'] ?? ''));
        if ($restaurant_name !== '') {
            $serp_sources[] = $restaurant_name;
        }
        $serp_sources[] = 'fallback';

        foreach (array_unique($serp_sources) as $serp_source) {
            $delete_reviews_cache('goody_google_reviews_' . md5('serp:' . $serp_source . '|' . $count));
        }

        $shared_reviews_key = goody_normalize_api_token($value['integrations_reviews_api_key'] ?? '');
        $trustpilot_token = goody_normalize_api_token($value['trustpilot_api_token'] ?? '');
        $custom_token = goody_normalize_api_token($value['custom_reviews_api_token'] ?? '');

        $trustpilot_tokens = array_unique(array_filter([
            $trustpilot_token,
            $shared_reviews_key,
            '',
        ], static function ($token) {
            return $token !== null;
        }));

        foreach ($trustpilot_tokens as $token) {
            $cache_source = trim($trustpilot_url . '|' . $raw_source . '|' . md5((string) $token));
            $delete_reviews_cache('goody_google_reviews_' . md5('trustpilot:' . $cache_source . '|' . $count));
        }

        $custom_tokens = array_unique(array_filter([
            $custom_token,
            $shared_reviews_key,
            '',
        ], static function ($token) {
            return $token !== null;
        }));

        foreach ($custom_tokens as $token) {
            $cache_source = trim($custom_url . '|' . $raw_source . '|' . md5((string) $token));
            $delete_reviews_cache('goody_google_reviews_' . md5('custom:' . $cache_source . '|' . $count));
        }

        $next_reviews_sync = function_exists('wp_next_scheduled') ? wp_next_scheduled('goody_google_reviews_sync_event') : false;
        if (function_exists('wp_schedule_single_event') && (! $next_reviews_sync || $next_reviews_sync > (time() + MINUTE_IN_SECONDS))) {
            wp_schedule_single_event(time() + 15, 'goody_google_reviews_sync_event');
        }
    }

    if ($tracking_related_changed) {
        $tracking_source = goody_normalize_url_input($value['tracking_url'] ?? '');
        if ($tracking_source !== '') {
            delete_transient('goody_tracking_state_' . md5($tracking_source));
        }
    }
}
add_action('update_option_goody_theme_options', 'goody_clear_delivery_cache_on_options_update', 10, 2);
