<?php

function goody_register_theme_menu() {
    add_menu_page(
        __('Goody Green Settings', 'goody'),
        __('Goody Green', 'goody'),
        'edit_theme_options',
        'goody-theme',
        'goody_render_theme_options_page',
        'dashicons-store'
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
            ['key' => 'hero_image', 'label' => __('Hero Image', 'goody'), 'type' => 'media'],
            ['key' => 'hero_video_file', 'label' => __('Hero Video File (Upload)', 'goody'), 'type' => 'media'],
            ['key' => 'hero_video_url', 'label' => __('Hero Video External URL', 'goody'), 'type' => 'url'],
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
            ['key' => 'reservation_max_bookings_per_day', 'label' => __('Max Booking Per Day', 'goody'), 'type' => 'number'],
            ['key' => 'reservation_lock_booked_slots', 'label' => __('Disable Reserved Date + Time Slots', 'goody'), 'type' => 'checkbox', 'description' => __('When enabled, a date and time slot becomes unavailable after one non-cancelled reservation is created.', 'goody')],
            ['key' => 'reservation_enable_dine_in', 'label' => __('Enable Dine In', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_pickup', 'label' => __('Enable Pickup', 'goody'), 'type' => 'checkbox'],
            ['key' => 'reservation_enable_delivery', 'label' => __('Enable Delivery', 'goody'), 'type' => 'checkbox'],
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
            ['key' => 'google_reviews_place_id', 'label' => __('Google Place ID / CID / Maps Link / SerpApi data_id', 'goody'), 'type' => 'text'],
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
            ['key' => 'integrations_maps_api_key', 'label' => __('Maps API Key or Script URL', 'goody'), 'type' => 'text'],
            ['key' => 'integrations_reviews_api_key', 'label' => __('Reviews API Key / Token (Google or SerpApi)', 'goody'), 'type' => 'text'],
            [
                'key' => 'integrations_google_reviews_api_key',
                'label' => __('Google Reviews API Key (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Use key starting with AIza... for Google Places reviews. If empty, shared Reviews key is used.', 'goody'),
            ],
            [
                'key' => 'integrations_serpapi_api_key',
                'label' => __('SerpApi Key (Optional)', 'goody'),
                'type' => 'text',
                'description' => __('Paste SerpApi private key (not playground URL, not data_id). If empty, shared Reviews key is used when it is not a Google AIza key.', 'goody'),
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
        } else {
            $sanitized[$key] = sanitize_text_field((string) $value);
        }
    }

    return $sanitized;
}

function goody_render_option_field($field, $options) {
    $key = $field['key'];
    $label = $field['label'];
    $type = $field['type'];
    $value = $options[$key] ?? '';
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
    } elseif ($type === 'color') {
        echo '<input type="color" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
    } elseif ($type === 'media') {
        $image_url = $value ? wp_get_attachment_image_url((int) $value, 'medium') : '';
        echo '<input type="hidden" class="goody-media-field" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
        echo '<button type="button" class="button goody-media-upload" data-target="' . esc_attr($key) . '">' . esc_html__('Upload / Select', 'goody') . '</button>';
        echo '<div class="goody-media-preview" style="margin-top:10px;">';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:240px;height:auto;">';
        }
        echo '</div>';
    } elseif ($type === 'gallery') {
        $ids = array_values(array_filter(array_map('absint', explode(',', (string) $value))));
        echo '<input type="hidden" class="goody-gallery-field" id="' . esc_attr($key) . '" name="goody_theme_options[' . esc_attr($key) . ']" value="' . esc_attr((string) $value) . '">';
        echo '<button type="button" class="button goody-gallery-upload" data-target="' . esc_attr($key) . '">' . esc_html__('Upload Multiple', 'goody') . '</button>';
        echo '<div class="goody-gallery-preview" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px;">';
        foreach ($ids as $id) {
            $src = wp_get_attachment_image_url($id, 'thumbnail');
            if ($src) {
                echo '<img src="' . esc_url($src) . '" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:6px;">';
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
        '}body{font-family:var(--font-body);color:var(--color-text);background:radial-gradient(circle at 84% 6%,color-mix(in srgb,var(--color-primary) 14%,transparent),transparent 25%),radial-gradient(circle at 12% 18%,color-mix(in srgb,var(--color-primary-2) 11%,transparent),transparent 24%),linear-gradient(180deg,var(--color-bg-deep) 0%,var(--color-bg) 42%,var(--color-section) 100%);}',
        'h1,h2,h3,h4,h5{font-family:var(--font-heading);}.hero__headline-line--accent,.accent-italic{font-family:var(--font-accent);color:var(--color-primary);}.eyebrow,.site-title,a:hover{color:var(--color-primary);}',
        '.button{background:linear-gradient(180deg,var(--color-primary-hover),var(--color-primary));color:var(--color-button-text);}.button:hover{color:var(--color-button-text);}',
        '.goody-reservation-shell .button,.goody-item-select{background:linear-gradient(180deg,color-mix(in srgb,var(--goody-reservation-button) 86%,white 14%),var(--goody-reservation-button));color:var(--goody-reservation-button-text);}.goody-reservation-shell .button:hover,.goody-item-select:hover{color:var(--goody-reservation-button-text);}',
        '.button--ghost,.button--outline,.goody-reservation-shell .button--ghost{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg-soft) 72%,transparent);color:var(--color-text);}',
        '.card{border-color:var(--color-border);background:linear-gradient(180deg,color-mix(in srgb,var(--color-card-soft) 72%,transparent),color-mix(in srgb,var(--color-card) 92%,transparent));box-shadow:var(--shadow-soft);}.badge{border-color:color-mix(in srgb,var(--color-primary) 45%,transparent);background:color-mix(in srgb,var(--color-primary) 16%,transparent);color:var(--color-primary-hover);}',
        'input,select,textarea{font-family:var(--font-body);border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg) 82%,transparent);color:var(--color-text);}.site-header{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg-deep) 86%,transparent);}.site-navigation a{color:var(--color-text);}',
        '#menu,.offers-zone,.reserve-zone,.page-section--soft{background:linear-gradient(180deg,var(--color-bg-deep) 0%,var(--color-bg) 100%);}',
        '.goody-reservation-shell{font-family:var(--reservation-font-family);background:radial-gradient(circle at 86% 8%,color-mix(in srgb,var(--goody-reservation-accent) 13%,transparent),transparent 24%),linear-gradient(180deg,color-mix(in srgb,var(--color-section) 72%,transparent) 0%,color-mix(in srgb,var(--color-bg) 98%,transparent) 100%);border-color:var(--color-border);}.goody-reservation-shell h2,.goody-reservation-shell h3,.goody-reservation-shell h4{font-family:var(--font-heading);}',
        '.goody-reservation-kicker,.goody-step-counter,.goody-summary-line--grand strong,.goody-summary-line--pay strong{color:var(--goody-reservation-accent);}.goody-filter-pill.is-active,.goody-slot-card.is-selected{background:var(--goody-reservation-button);color:var(--goody-reservation-button-text);}.goody-inline-empty,.goody-notice{background:var(--goody-reservation-accent-soft);border-color:color-mix(in srgb,var(--goody-reservation-accent) 30%,transparent);color:var(--goody-reservation-accent);}',
        '.goody-status-card,.goody-sidebar-card,.goody-reservation-panel,.goody-booking-card{border-color:var(--color-border);background:linear-gradient(180deg,var(--goody-reservation-surface),color-mix(in srgb,var(--color-bg) 96%,transparent));}.goody-booking-card.is-selected{border-color:var(--goody-reservation-accent);box-shadow:0 24px 44px color-mix(in srgb,var(--goody-reservation-accent) 16%,transparent);}',
        '.tracking-box,.tracking-steps-wrap,.tracking-timeline,.tracking-orders-list{border-color:var(--color-border);background:color-mix(in srgb,var(--color-bg) 72%,transparent);}.tracking-box--primary{background:radial-gradient(circle at 100% 0%,color-mix(in srgb,var(--color-primary) 10%,transparent),transparent 34%),linear-gradient(160deg,color-mix(in srgb,var(--color-surface) 88%,transparent),color-mix(in srgb,var(--color-bg) 94%,transparent));}.tracking-step.is-done .tracking-step__dot,.tracking-event.is-done .tracking-event__dot{background:var(--color-primary);border-color:color-mix(in srgb,var(--color-primary) 84%,white 16%);}',
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
        foreach (array_unique($sources) as $source) {
            delete_transient('goody_google_reviews_' . md5($source . '|' . $count));
        }

        // Clear cache keys used by current review fetch logic.
        if ($place_id) {
            delete_transient('goody_google_reviews_' . md5('pid:' . $place_id . '|' . $count));
        }
        if ($cid) {
            delete_transient('goody_google_reviews_' . md5('cid:' . $cid . '|' . $count));
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
            delete_transient('goody_google_reviews_' . md5('serp:' . $serp_source . '|' . $count));
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
            delete_transient('goody_google_reviews_' . md5('trustpilot:' . $cache_source . '|' . $count));
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
            delete_transient('goody_google_reviews_' . md5('custom:' . $cache_source . '|' . $count));
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
