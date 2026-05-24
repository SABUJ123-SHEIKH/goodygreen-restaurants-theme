<?php

function goody_default_options() {
    return [
        'restaurant_name' => 'Goody Green',
        'restaurant_tagline' => 'Brunch & More at Goody',
        'restaurant_logo_alt' => 'Goody Green Restaurant',
        'restaurant_logo' => 0,
        'header_sticky' => '1',
        'header_show_search' => '1',
        'header_search_placeholder' => 'Search menu, offers, events...',
        'header_enable_dropdown_menu' => '1',
        'header_enable_mega_menu' => '1',
        'header_enable_mobile_bottom_nav' => '1',
        'header_enable_bottom_cta_bar' => '0',
        'hero_background_type' => 'image',
        'hero_image' => 0,
        'hero_video_file' => 0,
        'hero_video_url' => '',
        'hero_overlay_strength' => '60',
        'hero_heading' => 'Luxury Brunch, Naturally Green',
        'hero_highlight_text' => 'Brunch',
        'hero_subheading' => 'Premium ingredients, soulful recipes, and a refined dining experience.',
        'hero_concept_tagline' => 'Brunch & More at Goody',
        'hero_primary_text' => 'Order Now',
        'hero_primary_url' => '#order',
        'hero_secondary_text' => 'Explore Menu',
        'hero_secondary_url' => '#menu',
        'menu_section_title' => 'Signature Menu',
        'menu_section_text' => 'Filter by category, dietary preference, meal type, and special tags to find your perfect dish.',
        'menu_show_unavailable' => '0',
        'menu_items_count' => '12',
        'offers_section_title' => 'Special Offers',
        'offers_section_text' => 'Daily and weekly deals crafted for brunch lovers.',
        'order_section_title' => 'Order & Delivery',
        'order_section_text' => 'Fast delivery partners and direct ordering in one place.',
        'delivery_data_source' => 'manual',
        'glovo_url' => '',
        'ubereats_url' => '',
        'deliveroo_url' => '',
        'custom_order_url' => '',
        'custom_order_text' => 'Order Direct',
        'glovo_api_url' => '',
        'glovo_api_token' => '',
        'ubereats_environment' => 'production',
        'ubereats_api_base_url' => '',
        'ubereats_api_url' => '',
        'ubereats_api_token' => '',
        'ubereats_client_id' => '',
        'ubereats_client_secret' => '',
        'ubereats_oauth_scope' => 'eats.deliveries',
        'ubereats_oauth_token_url' => '',
        'deliveroo_api_url' => '',
        'deliveroo_api_token' => '',
        'delivery_auto_create_enabled' => '0',
        'delivery_auto_provider' => 'ubereats',
        'delivery_mapping_profile' => 'auto',
        'glovo_order_create_api_url' => '',
        'ubereats_order_create_api_url' => '',
        'deliveroo_order_create_api_url' => '',
        'custom_order_create_api_url' => '',
        'custom_order_create_api_token' => '',
        'delivery_webhook_secret' => '',
        'delivery_create_response_external_id_path' => '',
        'delivery_create_response_tracking_url_path' => '',
        'delivery_create_response_tracking_api_url_path' => '',
        'delivery_tracking_response_url_path' => '',
        'delivery_tracking_response_status_path' => '',
        'delivery_tracking_response_stage_path' => '',
        'delivery_tracking_response_eta_path' => '',
        'delivery_tracking_response_timeline_path' => '',
        'tracking_enabled' => '0',
        'tracking_title' => 'Track Your Order',
        'tracking_description' => 'Track your order in real time with our delivery partner.',
        'tracking_url' => '',
        'tracking_embed' => '',
        'reservation_section_title' => 'Book a Table',
        'reservation_section_text' => 'Reserve your table in seconds.',
        'reservation_section_image' => 0,
        'reservation_platform' => 'custom',
        'reservation_resy_url' => '',
        'reservation_bookatable_url' => '',
        'reservation_custom_url' => '',
        'reservation_button_text' => 'Reserve Now',
        'reservation_embed' => '',
        'reservation_page_title' => 'Book your table or pre-order your meal',
        'reservation_status_page_url' => '',
        'reservation_status_title' => 'Check your reservation status',
        'reservation_status_text' => 'Enter your reservation reference and phone number.',
        'reservation_success_message' => 'Reservation created successfully.',
        'reservation_error_message' => 'Please review the form and try again.',
        'reservation_booking_notice' => 'Choose your preferred date, dishes, slot, and payment plan below.',
        'reservation_pickup_warning' => 'Pickup orders must be collected on time from the restaurant.',
        'reservation_delivery_warning' => 'Delivery address is required for delivery orders.',
        'reservation_cash_warning' => 'Cash orders stay reserved and will be confirmed by the restaurant.',
        'reservation_dine_in_note' => 'Dine-in reservations are held for a limited time after the selected slot starts.',
        'reservation_step_counter_prefix' => 'Step',
        'reservation_step_title_1' => 'Date',
        'reservation_step_title_2' => 'Menu',
        'reservation_step_title_3' => 'Time',
        'reservation_step_title_4' => 'Order Type',
        'reservation_step_title_5' => 'Information',
        'reservation_step_title_6' => 'Summary',
        'reservation_order_type_label_dine_in' => 'Dine In',
        'reservation_order_type_label_pickup' => 'Pickup',
        'reservation_order_type_label_delivery' => 'Delivery',
        'reservation_next_button_text' => 'Next',
        'reservation_back_button_text' => 'Back',
        'reservation_submit_button_text' => 'Create reservation',
        'reservation_deposit_percentage' => '50',
        'reservation_advance_days' => '30',
        'reservation_cutoff_minutes' => '120',
        'reservation_free_delivery_threshold' => '0',
        'reservation_disabled_dates' => '',
        'reservation_holiday_message' => 'Booking is not available on this date.',
        'reservation_tables_layout' => "T1|Table 1|Indoor Window|4\nT2|Table 2|Indoor Center|4\nT3|Table 3|Garden Deck|6\nT4|Table 4|Garden Corner|2\nT5|Table 5|Family Hall|8\nT6|Table 6|Rooftop View|4",
        'reservation_max_bookings_per_day' => '0',
        'reservation_lock_booked_slots' => '1',
        'reservation_enable_dine_in' => '1',
        'reservation_enable_pickup' => '1',
        'reservation_enable_delivery' => '1',
        'reservation_min_order_dine_in' => '0',
        'reservation_min_order_pickup' => '0',
        'reservation_min_order_delivery' => '0',
        'reservation_enable_full_payment' => '1',
        'reservation_enable_advance_payment' => '1',
        'reservation_enable_cash_payment' => '1',
        'reservation_auto_create_wc_order' => '1',
        'reservation_customer_name_required' => '1',
        'reservation_customer_phone_required' => '1',
        'reservation_customer_guests_required' => '1',
        'reservation_customer_address_required' => '1',
        'reservation_customer_note_required' => '0',
        'reservation_customer_address_enabled' => '1',
        'reservation_customer_note_enabled' => '1',
        'menu_page_title' => 'Restaurant Menu',
        'about_story_title' => 'About Goody Green',
        'about_story_text' => 'Goody Green blends elevated brunch culture with warm hospitality and seasonal craft.',
        'about_mission_title' => 'Our Mission',
        'about_mission_text' => 'Serve memorable meals with quality ingredients, consistent care, and design-forward atmosphere.',
        'about_vision_title' => 'Our Vision',
        'about_vision_text' => 'To become the city\'s most loved destination for conscious luxury dining.',
        'about_featured_image' => 0,
        'about_interior_gallery' => '',
        'about_exterior_gallery' => '',
        'gallery_zone_items_count' => '0',
        'reviews_section_title' => 'Guest Reviews',
        'reviews_section_text' => 'What guests love about their Goody Green experience.',
        'reviews_layout' => 'grid',
        'reviews_api_provider' => 'auto',
        'google_reviews_place_id' => '',
        'google_reviews_count' => '6',
        'reviews_default_rating_filter' => '0',
        'google_review_submit_url' => '',
        'reviews_google_handoff_after_submit' => '1',
        'google_reviews_mock_mode' => '0',
        'trustpilot_api_url' => '',
        'trustpilot_api_token' => '',
        'custom_reviews_api_url' => '',
        'custom_reviews_api_token' => '',
        'events_section_title' => 'Upcoming Events',
        'events_section_text' => 'Join brunch pop-ups, tasting nights, and seasonal specials.',
        'events_show_past' => '0',
        'news_enabled' => '0',
        'news_section_title' => 'Restaurant News',
        'news_eyebrow_text' => 'News',
        'news_section_text' => 'Latest updates, new menu drops, and brunch stories from Goody.',
        'news_posts_count' => '3',
        'news_button_text' => 'Read all news',
        'news_read_more_text' => 'Read more',
        'news_button_url' => '',
        'news_empty_title' => 'No news posts yet',
        'news_empty_text' => 'Publish blog posts from WordPress dashboard to show this section.',
        'account_enabled' => '0',
        'account_section_title' => 'User Account',
        'account_eyebrow_text' => 'Account',
        'account_section_text' => 'Track orders, save favorites, and manage your profile from one place.',
        'account_placeholder_title' => 'Placeholder Integration',
        'account_placeholder_text' => 'Use this block to connect login, profile, loyalty points, and saved delivery addresses with your preferred account system.',
        'account_feature_text_1' => 'Track order history and status',
        'account_feature_text_2' => 'Save favorite dishes for fast reorder',
        'account_feature_text_3' => 'Manage profile and delivery details',
        'account_actions_title' => 'Quick Actions',
        'account_login_button_text' => 'Login',
        'account_register_button_text' => 'Create Account',
        'account_profile_button_text' => 'My Profile',
        'account_login_url' => '',
        'account_register_url' => '',
        'account_profile_url' => '',
        'account_empty_note_text' => 'Set Login/Register/Profile URLs in Goody Green Settings > Account tab to activate buttons.',
        'newsletter_title' => 'Be the first to know the news',
        'newsletter_text' => 'Get special offers, seasonal menu updates, and event highlights in your inbox.',
        'newsletter_embed' => '',
        'contact_section_title' => 'Contact & Location',
        'contact_section_text' => 'Reach us quickly for table bookings, events, and private dining.',
        'contact_phone' => '',
        'contact_email' => '',
        'contact_address' => '',
        'contact_map_lat' => '',
        'contact_map_lng' => '',
        'contact_whatsapp_number' => '',
        'contact_whatsapp_button_text' => 'WhatsApp Us',
        'contact_call_button_text' => 'Call Now',
        'contact_form_shortcode' => '',
        'google_maps_embed' => '',
        'map_script_embed' => '',
        'business_hours' => wp_json_encode([
            ['day' => 'Monday', 'open' => '08:00', 'close' => '22:00'],
            ['day' => 'Tuesday', 'open' => '08:00', 'close' => '22:00'],
        ]),
        'social_links' => wp_json_encode([
            ['label' => 'Instagram', 'url' => ''],
            ['label' => 'Facebook', 'url' => ''],
        ]),
        'footer_quick_title' => 'Quick Links',
        'footer_legal_title' => 'Legal',
        'footer_content_source' => 'theme',
        'footer_gutenberg_content_id' => '0',
        'footer_payment_icons' => '',
        'footer_copyright' => 'All rights reserved.',
        'seo_home_meta_title' => 'Goody Green | Luxury Brunch Restaurant',
        'seo_home_meta_description' => 'Discover premium brunch, seasonal specials, and elegant dining at Goody Green.',
        'seo_local_text' => 'Best luxury brunch and restaurant experience in the city center.',
        'integrations_maps_api_key' => '',
        'integrations_reviews_api_key' => '',
        'integrations_google_reviews_api_key' => '',
        'integrations_serpapi_api_key' => '',
        'integrations_mailchimp_api_key' => '',
        'integrations_mailchimp_audience_id' => '',
        'integrations_mailchimp_server_prefix' => '',
        'integrations_custom_head_code' => '',
        'integrations_custom_footer_code' => '',
        'design_color_preset' => 'custom',
        'design_auto_harmony' => '1',
        'token_color_primary' => '#a3db3f',
        'token_color_primary_2' => '#4fa93c',
        'token_color_primary_hover' => '#b8e567',
        'token_color_button_text' => '#07200f',
        'reservation_button_color' => '#4e2d1c',
        'reservation_accent_color' => '#ff9b54',
        'reservation_card_radius' => '28px',
        'reservation_font_family' => 'Manrope, sans-serif',
        'token_color_bg_deep' => '#020906',
        'token_color_bg' => '#07160f',
        'token_color_bg_soft' => '#10251b',
        'token_color_section' => '#072a1d',
        'token_color_card' => '#0a1913',
        'token_color_card_soft' => '#0f2d20',
        'token_color_surface' => '#153024',
        'token_color_text' => '#f4f2e8',
        'token_color_muted' => '#b5c3b8',
        'token_color_border' => 'rgba(142, 190, 152, 0.22)',
        'token_color_shadow' => 'rgba(0, 0, 0, 0.45)',
        'token_shadow' => '0 20px 55px rgba(0,0,0,0.35)',
        'token_space_section' => '5rem',
        'token_radius_sm' => '8px',
        'token_radius' => '18px',
        'token_radius_lg' => '28px',
        'token_container' => '1240px',
        'token_fonts_url' => 'https://fonts.googleapis.com/css2?family=Allura&family=Cormorant+Garamond:wght@500;600;700&family=Manrope:wght@400;500;600;700&display=swap',
        'token_font_heading' => 'Cormorant Garamond, Georgia, Times New Roman, serif',
        'token_font_body' => 'Manrope, Segoe UI, sans-serif',
        'token_font_accent' => 'Allura, Brush Script MT, cursive',
    ];
}

function goody_get_options() {
    return wp_parse_args((array) get_option('goody_theme_options', []), goody_default_options());
}

function goody_get_language_code() {
    static $language_code = null;
    if ($language_code !== null) {
        return $language_code;
    }

    $supported_codes = array_keys(goody_get_language_locale_map());

    $selected_code = goody_get_selected_language_code();
    if ($selected_code !== '' && in_array($selected_code, $supported_codes, true)) {
        $language_code = $selected_code;
        return $language_code;
    }

    if (function_exists('pll_current_language')) {
        $pll_code = sanitize_key((string) pll_current_language('slug'));
        if ($pll_code !== '' && in_array($pll_code, $supported_codes, true)) {
            $language_code = $pll_code;
            return $language_code;
        }
    }

    if (has_filter('wpml_current_language')) {
        $wpml_code = sanitize_key((string) apply_filters('wpml_current_language', null));
        if ($wpml_code !== '' && in_array($wpml_code, $supported_codes, true)) {
            $language_code = $wpml_code;
            return $language_code;
        }
    }

    $locale = function_exists('determine_locale') ? determine_locale() : get_locale();
    $normalized = strtolower(str_replace('-', '_', (string) $locale));
    $locale_code = sanitize_key((string) (explode('_', $normalized)[0] ?? ''));

    if ($locale_code !== '' && in_array($locale_code, $supported_codes, true)) {
        $language_code = $locale_code;
        return $language_code;
    }

    $language_code = 'en';
    return $language_code;
}

function goody_get_language_locale_map() {
    return [
        'en' => 'en_US',
        'es' => 'es_ES',
        'ca' => 'ca',
    ];
}

function goody_get_language_flag_map() {
    return [
        'en' => '🇺🇸',
        'es' => '🇪🇸',
        'ca' => '🏴',
    ];
}

function goody_get_language_label_map() {
    return [
        'en' => 'English',
        'es' => 'Spanish',
        'ca' => 'Catalan',
    ];
}

function goody_get_requested_language_code() {
    $requested = sanitize_key((string) ($_GET['goody_lang'] ?? ''));
    if ($requested === '') {
        return '';
    }

    $map = goody_get_language_locale_map();
    return array_key_exists($requested, $map) ? $requested : '';
}

function goody_get_cookie_language_code() {
    $cookie = sanitize_key((string) ($_COOKIE['goody_lang'] ?? ''));
    if ($cookie === '') {
        return '';
    }

    $map = goody_get_language_locale_map();
    return array_key_exists($cookie, $map) ? $cookie : '';
}

function goody_get_selected_language_code() {
    $requested = goody_get_requested_language_code();
    if ($requested !== '') {
        return $requested;
    }

    return goody_get_cookie_language_code();
}

function goody_handle_language_switch_request() {
    if (is_admin()) {
        return;
    }

    if (strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')) !== 'GET') {
        return;
    }

    $requested = goody_get_requested_language_code();
    if ($requested === '') {
        return;
    }

    setcookie('goody_lang', $requested, time() + MONTH_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), false);
    $_COOKIE['goody_lang'] = $requested;

    $redirect_url = remove_query_arg('goody_lang');
    if (is_string($redirect_url) && $redirect_url !== '') {
        wp_safe_redirect($redirect_url);
        exit;
    }
}
add_action('init', 'goody_handle_language_switch_request', 1);

function goody_filter_site_locale($locale) {
    if (is_admin() && ! wp_doing_ajax()) {
        return $locale;
    }

    $selected = goody_get_selected_language_code();
    if ($selected === '') {
        return $locale;
    }

    $map = goody_get_language_locale_map();
    return $map[$selected] ?? $locale;
}
add_filter('locale', 'goody_filter_site_locale', 20);
add_filter('determine_locale', 'goody_filter_site_locale', 20);

function goody_get_language_switcher_items() {
    $items = [];
    $flags = goody_get_language_flag_map();
    $labels = goody_get_language_label_map();
    $current_code = goody_get_language_code();

    if (function_exists('pll_the_languages')) {
        $pll_languages = pll_the_languages([
            'raw' => 1,
            'hide_if_empty' => 0,
            'hide_if_no_translation' => 0,
            'hide_current' => 0,
        ]);

        if (is_array($pll_languages) && ! empty($pll_languages)) {
            foreach ($pll_languages as $lang) {
                if (! is_array($lang) || empty($lang['url'])) {
                    continue;
                }

                $code = sanitize_key((string) ($lang['slug'] ?? ''));
                $items[] = [
                    'url' => esc_url((string) $lang['url']),
                    'name' => sanitize_text_field((string) ($lang['name'] ?? $lang['translated_name'] ?? ($labels[$code] ?? strtoupper($code)))),
                    'code' => $code !== '' ? strtoupper($code) : 'LANG',
                    'flag' => $flags[$code] ?? '🌐',
                    'current' => ! empty($lang['current_lang']),
                ];
            }
        }
    } elseif (has_filter('wpml_active_languages')) {
        $wpml_languages = apply_filters('wpml_active_languages', null, [
            'skip_missing' => 0,
            'orderby' => 'code',
        ]);

        if (is_array($wpml_languages) && ! empty($wpml_languages)) {
            foreach ($wpml_languages as $lang) {
                if (! is_array($lang) || empty($lang['url'])) {
                    continue;
                }

                $code = sanitize_key((string) ($lang['code'] ?? ($lang['language_code'] ?? '')));
                $items[] = [
                    'url' => esc_url((string) $lang['url']),
                    'name' => sanitize_text_field((string) ($lang['translated_name'] ?? $lang['native_name'] ?? ($labels[$code] ?? strtoupper($code)))),
                    'code' => $code !== '' ? strtoupper($code) : 'LANG',
                    'flag' => $flags[$code] ?? '🌐',
                    'current' => ! empty($lang['active']),
                ];
            }
        }
    }

    if (! empty($items)) {
        return $items;
    }

    $request_uri = wp_unslash((string) ($_SERVER['REQUEST_URI'] ?? '/'));
    $current_url = home_url($request_uri);
    $current_url = remove_query_arg('goody_lang', $current_url);

    foreach (goody_get_language_locale_map() as $code => $unused_locale) {
        $items[] = [
            'url' => esc_url(add_query_arg('goody_lang', $code, $current_url)),
            'name' => $labels[$code] ?? strtoupper($code),
            'code' => strtoupper($code),
            'flag' => $flags[$code] ?? '🌐',
            'current' => $current_code === $code,
        ];
    }

    return $items;
}

function goody_get_localized_default_option($key) {
    static $localized_defaults = null;

    if ($localized_defaults === null) {
        $localized_defaults = [
            'es' => [
                'restaurant_tagline' => 'Brunch y mas en Goody',
                'hero_heading' => 'Brunch de lujo, naturalmente verde',
                'hero_highlight_text' => 'Brunch',
                'hero_subheading' => 'Ingredientes premium, recetas con alma y una experiencia refinada.',
                'hero_primary_text' => 'Pedir ahora',
                'hero_secondary_text' => 'Ver menu',
                'menu_section_title' => 'Menu destacado',
                'menu_section_text' => 'Filtra por categoria y preferencias para encontrar tu plato ideal.',
                'offers_section_title' => 'Ofertas especiales',
                'offers_section_text' => 'Promociones diarias y semanales para amantes del brunch.',
                'order_section_title' => 'Pedido y entrega',
                'order_section_text' => 'Socios de entrega rapida y pedido directo en un solo lugar.',
                'reservation_section_title' => 'Reservar mesa',
                'reservation_section_text' => 'Reserva tu mesa en segundos.',
                'reservation_button_text' => 'Reservar ahora',
                'reservation_page_title' => 'Reserva tu mesa o pide tu comida con antelacion',
                'reservation_status_title' => 'Consulta el estado de tu reserva',
                'reservation_status_text' => 'Ingresa tu referencia y numero de telefono.',
                'reservation_next_button_text' => 'Siguiente',
                'reservation_back_button_text' => 'Atras',
                'reservation_submit_button_text' => 'Crear reserva',
                'tracking_title' => 'Rastrea tu pedido',
                'tracking_description' => 'Sigue tu pedido en tiempo real con nuestro socio de entrega.',
                'about_story_title' => 'Sobre Goody Green',
                'about_mission_title' => 'Nuestra mision',
                'about_vision_title' => 'Nuestra vision',
                'reviews_section_title' => 'Resenas de clientes',
                'events_section_title' => 'Proximos eventos',
                'newsletter_title' => 'Se el primero en enterarte',
                'contact_section_title' => 'Contacto y ubicacion',
                'contact_whatsapp_button_text' => 'Escribenos por WhatsApp',
                'contact_call_button_text' => 'Llamar ahora',
                'footer_quick_title' => 'Enlaces rapidos',
                'footer_legal_title' => 'Legal',
                'footer_copyright' => 'Todos los derechos reservados.',
            ],
            'ca' => [
                'restaurant_tagline' => 'Brunch i mes a Goody',
                'hero_heading' => 'Brunch de luxe, naturalment verd',
                'hero_highlight_text' => 'Brunch',
                'hero_subheading' => 'Ingredients premium, receptes amb anima i una experiencia refinada.',
                'hero_primary_text' => 'Demanar ara',
                'hero_secondary_text' => 'Veure menu',
                'menu_section_title' => 'Menu destacat',
                'menu_section_text' => 'Filtra per categoria i preferencies per trobar el teu plat ideal.',
                'offers_section_title' => 'Ofertes especials',
                'offers_section_text' => 'Promocions diaries i setmanals per amants del brunch.',
                'order_section_title' => 'Comanda i entrega',
                'order_section_text' => 'Partners de lliurament rapid i comanda directa en un sol lloc.',
                'reservation_section_title' => 'Reservar taula',
                'reservation_section_text' => 'Reserva la teva taula en segons.',
                'reservation_button_text' => 'Reservar ara',
                'reservation_page_title' => 'Reserva la teva taula o precomanda el teu apat',
                'reservation_status_title' => 'Consulta l estat de la teva reserva',
                'reservation_status_text' => 'Introdueix la referencia i el telefon.',
                'reservation_next_button_text' => 'Seguent',
                'reservation_back_button_text' => 'Enrere',
                'reservation_submit_button_text' => 'Crear reserva',
                'tracking_title' => 'Segueix la teva comanda',
                'tracking_description' => 'Segueix la teva comanda en temps real amb el nostre partner.',
                'about_story_title' => 'Sobre Goody Green',
                'about_mission_title' => 'La nostra missio',
                'about_vision_title' => 'La nostra visio',
                'reviews_section_title' => 'Ressenyes de clients',
                'events_section_title' => 'Propers esdeveniments',
                'newsletter_title' => 'Sigues el primer a saber les novetats',
                'contact_section_title' => 'Contacte i ubicacio',
                'contact_whatsapp_button_text' => 'WhatsApp',
                'contact_call_button_text' => 'Trucar ara',
                'footer_quick_title' => 'Enllacos rapids',
                'footer_legal_title' => 'Legal',
                'footer_copyright' => 'Tots els drets reservats.',
            ],
        ];
    }

    $language_code = goody_get_language_code();
    if (! isset($localized_defaults[$language_code][$key])) {
        return null;
    }

    return $localized_defaults[$language_code][$key];
}

function goody_get_option($key, $default = '') {
    $raw_options = (array) get_option('goody_theme_options', []);
    $language_code = goody_get_language_code();
    $language_keys = [
        $key . '__' . $language_code,
        $key . '_' . $language_code,
    ];

    foreach ($language_keys as $localized_key) {
        if (! array_key_exists($localized_key, $raw_options)) {
            continue;
        }
        $localized_value = trim((string) $raw_options[$localized_key]);
        if ($localized_value !== '') {
            return $raw_options[$localized_key];
        }
    }

    $defaults = goody_default_options();
    $resolved_default = $default;

    if (array_key_exists($key, $defaults)) {
        $resolved_default = $defaults[$key];
    }

    $localized_default = goody_get_localized_default_option($key);
    if (array_key_exists($key, $raw_options)) {
        $raw_value = trim((string) $raw_options[$key]);
        if ($raw_value !== '') {
            if ($localized_default !== null && (string) $raw_options[$key] === (string) $resolved_default) {
                return $localized_default;
            }
            return $raw_options[$key];
        }
    }

    if ($localized_default !== null) {
        return $localized_default;
    }

    return $resolved_default;
}

function goody_get_image_url($attachment_id, $size = 'full') {
    if (! $attachment_id) {
        return '';
    }

    $image = wp_get_attachment_image_src((int) $attachment_id, $size);
    return $image ? $image[0] : '';
}

function goody_svg($name) {
    $icons = [
        'star' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.7l2.9 5.88 6.49.95-4.69 4.57 1.11 6.46L12 17.52 6.2 20.56l1.11-6.46L2.62 9.53l6.49-.95L12 2.7z"/></svg>',
        'arrow' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13 5l7 7-7 7-1.4-1.4 4.6-4.6H4v-2h12.2l-4.6-4.6L13 5z"/></svg>',
        'phone' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8c1.4 2.7 3.8 5.1 6.6 6.6l2.2-2.2c.3-.3.8-.4 1.2-.3 1 .3 2 .4 3 .4.7 0 1.2.5 1.2 1.2V20c0 .7-.5 1.2-1.2 1.2C10.5 21.2 2.8 13.5 2.8 4.4c0-.7.5-1.2 1.2-1.2h3.5c.7 0 1.2.5 1.2 1.2 0 1 .1 2 .4 3 .1.4 0 .9-.3 1.2l-2.2 2.2z"/></svg>',
        'mail' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4.2l-8 4.8-8-4.8V6l8 4.8L20 6v2.2z"/></svg>',
        'pin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 00-7 7c0 5.2 7 13 7 13s7-7.8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 1112 6a2.5 2.5 0 010 5.5z"/></svg>',
        'clock' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.8A10.2 10.2 0 1022.2 12 10.21 10.21 0 0012 1.8zm.9 10.6l3.7 2.2-1 1.7-4.7-2.8V6h2z"/></svg>',
        'search' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10.5 2a8.5 8.5 0 016.8 13.6L22 20.3 20.3 22l-4.7-4.7A8.5 8.5 0 1110.5 2zm0 2.4a6.1 6.1 0 100 12.2 6.1 6.1 0 000-12.2z"/></svg>',
        'calendar' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2h2v2h6V2h2v2h2.2A2.8 2.8 0 0122 6.8v12.4A2.8 2.8 0 0119.2 22H4.8A2.8 2.8 0 012 19.2V6.8A2.8 2.8 0 014.8 4H7V2zm12.2 8H4.8v9.2c0 .4.3.8.8.8h13.6c.4 0 .8-.3.8-.8V10zM5.6 6a.8.8 0 00-.8.8V8h14.4V6.8a.8.8 0 00-.8-.8H17v1.2h-2V6H9v1.2H7V6H5.6z"/></svg>',
        'delivery' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 18.5A2.5 2.5 0 119 21a2.5 2.5 0 01-2.5-2.5zm9 0A2.5 2.5 0 1118 21a2.5 2.5 0 01-2.5-2.5zM3 5h11.2a1 1 0 01.9.6l1.8 4.1h2.3c.6 0 1.1.3 1.5.8l1.8 2.5c.3.3.4.7.4 1.1v2.4h-2.1a3.6 3.6 0 00-6.8 0h-1.6a3.6 3.6 0 00-6.8 0H3V5zm2 2v7.4h1.6a3.6 3.6 0 016.8 0h1.9l-1.4-3.3H9.4V9.7h3.9L12.5 7H5zm13.3 4.7l1.5 2h1.1l-1.4-2h-1.2z"/></svg>',
        'filter' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6.2A1.2 1.2 0 015.2 5h13.6a1.2 1.2 0 010 2.4H5.2A1.2 1.2 0 014 6.2zm3.2 5.8A1.2 1.2 0 018.4 10.8h7.2a1.2 1.2 0 010 2.4H8.4A1.2 1.2 0 017.2 12zm3.2 5.8a1.2 1.2 0 011.2-1.2h.8a1.2 1.2 0 010 2.4h-.8a1.2 1.2 0 01-1.2-1.2z"/></svg>',
    ];

    return $icons[$name] ?? '';
}

function goody_allowed_embed_html() {
    return [
        'a' => [
            'href' => true,
            'target' => true,
            'rel' => true,
            'class' => true,
        ],
        'iframe' => [
            'src' => true,
            'width' => true,
            'height' => true,
            'style' => true,
            'allow' => true,
            'allowfullscreen' => true,
            'loading' => true,
            'referrerpolicy' => true,
            'title' => true,
            'frameborder' => true,
            'class' => true,
        ],
        'script' => [
            'src' => true,
            'async' => true,
            'defer' => true,
            'type' => true,
            'id' => true,
            'class' => true,
        ],
        'div' => [
            'class' => true,
            'id' => true,
            'style' => true,
            'data-*' => true,
        ],
        'span' => [
            'class' => true,
            'id' => true,
            'style' => true,
        ],
        'p' => [
            'class' => true,
            'style' => true,
        ],
        'br' => [],
        'strong' => [],
        'em' => [],
        'ul' => [
            'class' => true,
        ],
        'li' => [
            'class' => true,
        ],
    ];
}

function goody_social_svg($label) {
    $map = [
        'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7.8 2h8.4A5.8 5.8 0 0122 7.8v8.4a5.8 5.8 0 01-5.8 5.8H7.8A5.8 5.8 0 012 16.2V7.8A5.8 5.8 0 017.8 2zm0 1.9A3.9 3.9 0 003.9 7.8v8.4a3.9 3.9 0 003.9 3.9h8.4a3.9 3.9 0 003.9-3.9V7.8a3.9 3.9 0 00-3.9-3.9H7.8zm8.9 1.4a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.9a3.1 3.1 0 100 6.2 3.1 3.1 0 000-6.2z"/></svg>',
        'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 8.5V6.9c0-.8.5-1.3 1.4-1.3h1.6V3h-2.3c-2.5 0-3.9 1.5-3.9 4v1.5H8v2.8h2.3V21h3.2v-9.7H16l.4-2.8h-2.9z"/></svg>',
        'twitter' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.5 6.3a6.8 6.8 0 01-1.9.5 3.3 3.3 0 001.5-1.8 6.5 6.5 0 01-2.1.8A3.3 3.3 0 0016.6 5c-1.8 0-3.3 1.5-3.3 3.3 0 .3 0 .5.1.8A9.4 9.4 0 016.2 5.7a3.3 3.3 0 001 4.4 3.2 3.2 0 01-1.5-.4v.1c0 1.6 1.1 2.9 2.6 3.2a3.3 3.3 0 01-1.5.1c.4 1.3 1.7 2.2 3.1 2.2A6.7 6.7 0 015 17.2a9.4 9.4 0 005.1 1.5c6.1 0 9.5-5.1 9.5-9.5v-.4c.7-.5 1.3-1.1 1.9-1.8z"/></svg>',
        'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.3 8.8H3.4V21h2.9V8.8zM4.8 3A1.7 1.7 0 103.1 4.7 1.7 1.7 0 004.8 3zm16.2 10.4c0-3-1.6-4.8-4.5-4.8a3.9 3.9 0 00-3.5 1.9V8.8h-2.9V21h2.9v-6.8c0-1.8 1-2.8 2.4-2.8s2.2 1 2.2 2.8V21H21z"/></svg>',
        'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.3a2.8 2.8 0 00-2-2C17.9 5 12 5 12 5s-5.9 0-7.6.3a2.8 2.8 0 00-2 2A29.6 29.6 0 002 12a29.6 29.6 0 00.4 4.7 2.8 2.8 0 002 2c1.7.3 7.6.3 7.6.3s5.9 0 7.6-.3a2.8 2.8 0 002-2A29.6 29.6 0 0022 12a29.6 29.6 0 00-.4-4.7zM10 15.1V8.9l5.2 3.1z"/></svg>',
        'tiktok' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14.8 3h2.6a4.8 4.8 0 003.2 3.1v2.6a7.5 7.5 0 01-3.1-.7v5.9a5.9 5.9 0 11-5.9-5.9c.3 0 .7 0 1 .1v2.7a3.2 3.2 0 102.2 3V3z"/></svg>',
    ];

    $slug = sanitize_title($label);
    return $map[$slug] ?? '';
}

function goody_sanitize_code_input($value) {
    $value = (string) $value;

    if (current_user_can('unfiltered_html')) {
        return $value;
    }

    return wp_kses($value, goody_allowed_embed_html());
}

function goody_format_price($price) {
    if ($price === '' || $price === null) {
        return '';
    }

    return esc_html('$' . number_format((float) $price, 2));
}

function goody_primary_fallback_menu() {
    echo '<ul class="menu">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'goody') . '</a></li>';
    echo '<li><a href="#menu">' . esc_html__('Menu', 'goody') . '</a></li>';
    echo '<li><a href="#about">' . esc_html__('About Us', 'goody') . '</a></li>';
    echo '<li><a href="#contact">' . esc_html__('Contact Us', 'goody') . '</a></li>';
    echo '<li><a href="#reviews">' . esc_html__('Reviews', 'goody') . '</a></li>';
    if (goody_get_option('news_enabled', '0') === '1') {
        echo '<li><a href="#news">' . esc_html(goody_get_option('news_eyebrow_text', __('News', 'goody'))) . '</a></li>';
    }
    if (goody_get_option('account_enabled', '0') === '1') {
        echo '<li><a href="#account">' . esc_html(goody_get_option('account_eyebrow_text', __('Account', 'goody'))) . '</a></li>';
    }
    echo '<li><a href="#order">' . esc_html__('Order Now', 'goody') . '</a></li>';
    echo '</ul>';
}

function goody_is_dropdown_menu_enabled() {
    return goody_get_option('header_enable_dropdown_menu', '1') === '1';
}

function goody_is_mega_menu_enabled() {
    if (! goody_is_dropdown_menu_enabled()) {
        return false;
    }

    return goody_get_option('header_enable_mega_menu', '1') === '1';
}

function goody_get_primary_nav_menu_args() {
    $dropdown_enabled = goody_is_dropdown_menu_enabled();
    $mega_enabled = goody_is_mega_menu_enabled();
    $menu_classes = ['primary-menu'];

    $menu_classes[] = $dropdown_enabled ? 'primary-menu--dropdown-enabled' : 'primary-menu--dropdown-disabled';
    $menu_classes[] = $mega_enabled ? 'primary-menu--mega-enabled' : 'primary-menu--mega-disabled';

    $args = [
        'theme_location' => 'primary',
        'container' => false,
        'menu_class' => implode(' ', $menu_classes),
        'depth' => $dropdown_enabled ? 3 : 1,
        'fallback_cb' => 'goody_primary_fallback_menu',
        'goody_primary_menu' => true,
    ];

    $locations = get_nav_menu_locations();
    $primary_menu_id = absint($locations['primary'] ?? 0);
    $primary_items = $primary_menu_id > 0 ? wp_get_nav_menu_items($primary_menu_id) : [];

    if ($primary_menu_id < 1 || empty($primary_items)) {
        $menus = wp_get_nav_menus([
            'hide_empty' => true,
        ]);

        if (! empty($menus) && ! is_wp_error($menus)) {
            foreach ($menus as $menu) {
                $items = wp_get_nav_menu_items((int) $menu->term_id);
                if (empty($items)) {
                    continue;
                }

                unset($args['theme_location']);
                $args['menu'] = (int) $menu->term_id;
                break;
            }
        }
    }

    return $args;
}

function goody_is_primary_nav_menu_args($args) {
    if (! is_object($args)) {
        return false;
    }

    if (! empty($args->goody_primary_menu)) {
        return true;
    }

    return isset($args->theme_location) && $args->theme_location === 'primary';
}

function goody_primary_menu_classes($classes, $item, $args, $depth) {
    if (! goody_is_primary_nav_menu_args($args)) {
        return $classes;
    }

    $classes = is_array($classes) ? $classes : [];
    $mega_enabled = goody_is_mega_menu_enabled();
    if (! $mega_enabled) {
        $classes = array_values(array_diff($classes, ['menu-item-has-mega']));
        return array_values(array_unique($classes));
    }

    $mega_classes = ['mega', 'mega-menu', 'goody-mega-menu', 'has-mega-menu'];
    $has_mega_class = count(array_intersect($mega_classes, $classes)) > 0;
    $has_children = in_array('menu-item-has-children', $classes, true);
    if ((int) $depth === 0 && ($has_mega_class || $has_children)) {
        $classes[] = 'menu-item-has-mega';
    }

    return array_values(array_unique($classes));
}
add_filter('nav_menu_css_class', 'goody_primary_menu_classes', 10, 4);

function goody_get_menu_item_image_id($item_id) {
    return absint(get_post_meta((int) $item_id, '_goody_menu_item_image_id', true));
}

function goody_primary_menu_item_image_field($item_id, $item) {
    $image_id = goody_get_menu_item_image_id($item_id);
    $image_url = $image_id > 0 ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
    ?>
    <p class="description description-wide goody-menu-image-field">
        <label for="edit-menu-item-goody-image-<?php echo esc_attr((string) $item_id); ?>">
            <?php esc_html_e('Submenu image (optional)', 'goody'); ?><br>
            <input type="hidden" class="widefat code edit-menu-item-goody-image-id" id="edit-menu-item-goody-image-<?php echo esc_attr((string) $item_id); ?>" name="menu-item-goody-image-id[<?php echo esc_attr((string) $item_id); ?>]" value="<?php echo esc_attr((string) $image_id); ?>">
            <span class="goody-menu-image-preview-wrap" style="display:block;margin:8px 0;">
                <?php if ($image_url) : ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="" style="width:44px;height:44px;object-fit:cover;border-radius:999px;border:1px solid #d0d6d2;">
                <?php endif; ?>
            </span>
            <button type="button" class="button goody-menu-image-select"><?php esc_html_e('Select image', 'goody'); ?></button>
            <button type="button" class="button goody-menu-image-remove"<?php echo $image_id > 0 ? '' : ' style="display:none;"'; ?>><?php esc_html_e('Remove image', 'goody'); ?></button>
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'goody_primary_menu_item_image_field', 10, 2);

function goody_save_primary_menu_item_image($menu_id, $menu_item_db_id) {
    if (! isset($_POST['menu-item-goody-image-id']) || ! is_array($_POST['menu-item-goody-image-id'])) {
        delete_post_meta((int) $menu_item_db_id, '_goody_menu_item_image_id');
        return;
    }

    $raw = wp_unslash($_POST['menu-item-goody-image-id']);
    $image_id = absint($raw[$menu_item_db_id] ?? 0);
    if ($image_id > 0) {
        update_post_meta((int) $menu_item_db_id, '_goody_menu_item_image_id', $image_id);
        return;
    }

    delete_post_meta((int) $menu_item_db_id, '_goody_menu_item_image_id');
}
add_action('wp_update_nav_menu_item', 'goody_save_primary_menu_item_image', 10, 2);

function goody_primary_menu_admin_image_picker_script($hook_suffix) {
    if ($hook_suffix !== 'nav-menus.php') {
        return;
    }

    wp_enqueue_media();
    wp_add_inline_script(
        'jquery-core',
        '(function($){
            function bindMenuImagePicker($scope) {
                $scope.find(".goody-menu-image-select").off("click.goodyMenuImage").on("click.goodyMenuImage", function(e){
                    e.preventDefault();
                    var $button = $(this);
                    var $field = $button.closest(".goody-menu-image-field");
                    var $input = $field.find(".edit-menu-item-goody-image-id");
                    var $previewWrap = $field.find(".goody-menu-image-preview-wrap");
                    var $remove = $field.find(".goody-menu-image-remove");
                    var frame = wp.media({
                        title: "Select submenu image",
                        button: { text: "Use image" },
                        multiple: false,
                        library: { type: "image" }
                    });
                    frame.on("select", function(){
                        var selection = frame.state().get("selection").first();
                        if (!selection) {
                            return;
                        }
                        var data = selection.toJSON();
                        var thumb = data.sizes && data.sizes.thumbnail ? data.sizes.thumbnail.url : data.url;
                        $input.val(data.id || "");
                        $previewWrap.html(\'<img src="\' + thumb + \'" alt="" style="width:44px;height:44px;object-fit:cover;border-radius:999px;border:1px solid #d0d6d2;">\');
                        $remove.show();
                    });
                    frame.open();
                });

                $scope.find(".goody-menu-image-remove").off("click.goodyMenuImage").on("click.goodyMenuImage", function(e){
                    e.preventDefault();
                    var $field = $(this).closest(".goody-menu-image-field");
                    $field.find(".edit-menu-item-goody-image-id").val("");
                    $field.find(".goody-menu-image-preview-wrap").empty();
                    $(this).hide();
                });
            }

            $(document).ready(function(){ bindMenuImagePicker($(document)); });
            $(document).on("menu-item-added", function(e, menuItem){ bindMenuImagePicker($(menuItem)); });
        })(jQuery);'
    );
}
add_action('admin_enqueue_scripts', 'goody_primary_menu_admin_image_picker_script');

function goody_primary_menu_item_description($item_output, $item, $depth, $args) {
    if (! goody_is_primary_nav_menu_args($args)) {
        return $item_output;
    }

    if ((int) $depth > 0) {
        $image_id = goody_get_menu_item_image_id($item->ID ?? 0);
        if ($image_id > 0) {
            $thumb = wp_get_attachment_image(
                $image_id,
                'goody-chip',
                false,
                [
                    'class' => 'menu-item-thumb',
                    'loading' => 'lazy',
                    'decoding' => 'async',
                ]
            );
            if (is_string($thumb) && $thumb !== '') {
                $item_output = preg_replace('/(<a\b[^>]*>)/', '$1' . $thumb, $item_output, 1);
            }
        }
    }

    $description = trim((string) ($item->description ?? ''));
    if ($description === '' || (int) $depth > 1 || strpos($item_output, '</a>') === false) {
        return $item_output;
    }

    $description_markup = '<span class="menu-item-description">' . esc_html($description) . '</span>';
    return str_replace('</a>', $description_markup . '</a>', $item_output);
}
add_filter('walker_nav_menu_start_el', 'goody_primary_menu_item_description', 10, 4);

function goody_get_posts($args = []) {
    $defaults = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 6,
        'orderby' => 'date',
        'order' => 'DESC',
    ];

    return new WP_Query(wp_parse_args($args, $defaults));
}

function goody_get_single_home_section($post_type) {
    $map = [
        'menu_item' => [
            'anchor' => '#menu',
            'label' => __('Menu', 'goody'),
        ],
        'offer' => [
            'anchor' => '#offers',
            'label' => __('Offers', 'goody'),
        ],
        'event' => [
            'anchor' => '#events',
            'label' => __('Events', 'goody'),
        ],
        'team_member' => [
            'anchor' => '#team',
            'label' => __('Team', 'goody'),
        ],
        'testimonial' => [
            'anchor' => '#reviews',
            'label' => __('Reviews', 'goody'),
        ],
    ];

    if (! isset($map[$post_type])) {
        return [
            'anchor' => '',
            'label' => __('Home', 'goody'),
        ];
    }

    return $map[$post_type];
}

function goody_is_offer_active($offer_id) {
    $status = get_post_meta($offer_id, 'goody_offer_active', true);
    if ($status === '0') {
        return false;
    }

    $today_ts = current_time('timestamp');
    $start = get_post_meta($offer_id, 'goody_offer_start_date', true);
    $end = get_post_meta($offer_id, 'goody_offer_end_date', true);
    $start_ts = $start ? strtotime((string) $start) : false;
    $end_ts = $end ? strtotime((string) $end) : false;

    if ($start_ts && $today_ts < $start_ts) {
        return false;
    }

    if ($end_ts && $today_ts > strtotime('tomorrow', $end_ts) - 1) {
        return false;
    }

    return true;
}

function goody_get_active_offer_menu_ids() {
    $query = new WP_Query([
        'post_type' => 'offer',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]);

    $menu_ids = [];
    if ($query->have_posts()) {
        foreach ($query->posts as $offer_id) {
            if (! goody_is_offer_active($offer_id)) {
                continue;
            }
            $linked = get_post_meta($offer_id, 'goody_offer_linked_menu_items', true);
            if (is_array($linked)) {
                $menu_ids = array_merge($menu_ids, array_map('absint', $linked));
            }
        }
    }

    return array_values(array_unique(array_filter($menu_ids)));
}

function goody_get_active_offers($posts_per_page = 6) {
    $query = goody_get_posts([
        'post_type' => 'offer',
        'posts_per_page' => $posts_per_page,
        'meta_key' => 'goody_offer_start_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    ]);

    if (! $query->have_posts()) {
        return [];
    }

    $offers = [];
    while ($query->have_posts()) {
        $query->the_post();
        $offer_id = get_the_ID();
        if (goody_is_offer_active($offer_id)) {
            $offers[] = $offer_id;
        }
    }
    wp_reset_postdata();

    return $offers;
}

function goody_get_business_hours() {
    $json = goody_get_option('business_hours', '[]');
    $items = json_decode((string) $json, true);
    if (! is_array($items)) {
        return [];
    }

    $hours = [];
    foreach ($items as $item) {
        if (! is_array($item)) {
            continue;
        }

        $day = sanitize_text_field($item['day'] ?? '');
        if ($day === '') {
            continue;
        }

        $hours[] = [
            'day' => $day,
            'open' => sanitize_text_field($item['open'] ?? ''),
            'close' => sanitize_text_field($item['close'] ?? ''),
        ];
    }

    return $hours;
}

function goody_get_today_business_hours() {
    $business_hours = goody_get_business_hours();
    $today_key = strtolower((string) current_time('l'));
    $today_hours = null;

    foreach ($business_hours as $hours_row) {
        $row_day = strtolower(trim((string) ($hours_row['day'] ?? '')));
        if ($row_day === '') {
            continue;
        }

        if ($row_day === $today_key || strpos($row_day, $today_key) !== false || strpos($today_key, $row_day) !== false) {
            $today_hours = $hours_row;
            break;
        }
    }

    if (! $today_hours && ! empty($business_hours)) {
        $today_hours = $business_hours[0];
    }

    return is_array($today_hours) ? $today_hours : [];
}

function goody_get_social_links() {
    $json = goody_get_option('social_links', '[]');
    $items = json_decode((string) $json, true);
    if (! is_array($items)) {
        return [];
    }

    $links = [];
    foreach ($items as $item) {
        if (! is_array($item)) {
            continue;
        }

        $label = sanitize_text_field($item['label'] ?? '');
        $url = esc_url_raw($item['url'] ?? '');

        if ($label && $url) {
            $links[] = [
                'label' => $label,
                'url' => $url,
            ];
        }
    }

    return $links;
}

function goody_get_gallery_ids($key) {
    $raw = (string) goody_get_option($key, '');
    if ($raw === '') {
        return [];
    }

    $ids = array_map('absint', explode(',', $raw));
    return array_values(array_filter($ids));
}

function goody_get_gallery_zone_item_limit() {
    $raw = trim((string) goody_get_option('gallery_zone_items_count', '0'));
    if ($raw === '') {
        return 0;
    }

    $limit = absint($raw);
    if ($limit < 1) {
        return 0;
    }

    return min(60, $limit);
}

function goody_get_showcase_gallery_image_urls($size = 'goody-square', $args = []) {
    $size = is_string($size) && $size !== '' ? $size : 'goody-square';
    $args = wp_parse_args($args, [
        'limit' => 0,
        'include_menu_fallback' => true,
        'menu_fallback_count' => 8,
    ]);

    $image_ids = array_merge(
        goody_get_gallery_ids('about_interior_gallery'),
        goody_get_gallery_ids('about_exterior_gallery')
    );

    $image_ids = array_values(array_unique(array_filter(array_map('absint', $image_ids))));

    $pool = [];
    foreach ($image_ids as $image_id) {
        $url = wp_get_attachment_image_url($image_id, $size);
        if ($url) {
            $pool[] = $url;
        }
    }

    $pool = array_values(array_unique($pool));

    if (empty($pool) && ! empty($args['include_menu_fallback'])) {
        $fallback_count = absint($args['menu_fallback_count']);
        if ($fallback_count < 1) {
            $fallback_count = 8;
        }

        $fallback = new WP_Query([
            'post_type' => 'menu_item',
            'post_status' => 'publish',
            'posts_per_page' => $fallback_count,
            'no_found_rows' => true,
        ]);

        if ($fallback->have_posts()) {
            while ($fallback->have_posts()) {
                $fallback->the_post();
                $url = get_the_post_thumbnail_url(get_the_ID(), $size);
                if ($url) {
                    $pool[] = $url;
                }
            }
            wp_reset_postdata();
        }
    }

    $pool = array_values(array_unique($pool));

    $limit = absint($args['limit']);
    if ($limit > 0) {
        $pool = array_slice($pool, 0, $limit);
    }

    return $pool;
}

function goody_get_payment_icons() {
    return goody_get_gallery_ids('footer_payment_icons');
}

function goody_get_footer_gutenberg_content_html() {
    $post_id = absint(goody_get_option('footer_gutenberg_content_id', '0'));
    if ($post_id < 1) {
        return '';
    }

    $post = get_post($post_id);
    if (! $post instanceof WP_Post || $post->post_status !== 'publish') {
        return '';
    }

    $allowed_types = ['wp_block', 'page', 'post'];
    if (! in_array($post->post_type, $allowed_types, true)) {
        return '';
    }

    $content = (string) $post->post_content;
    if ($content === '') {
        return '';
    }

    return (string) apply_filters('the_content', $content);
}

function goody_get_reservation_url() {
    $platform = goody_get_option('reservation_platform', 'custom');

    if ($platform === 'resy') {
        return goody_get_option('reservation_resy_url');
    }

    if ($platform === 'bookatable') {
        return goody_get_option('reservation_bookatable_url');
    }

    return goody_get_option('reservation_custom_url');
}

function goody_normalize_url_input($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    $sanitize_http_url = static function ($candidate) {
        $candidate = trim((string) $candidate);
        if ($candidate === '') {
            return '';
        }

        $strict = esc_url_raw($candidate);
        if ($strict !== '') {
            return $strict;
        }

        // Fallback for valid local/dev hosts like *.local that esc_url_raw may reject.
        $candidate = filter_var($candidate, FILTER_SANITIZE_URL);
        if (! is_string($candidate) || $candidate === '') {
            return '';
        }

        $parsed = wp_parse_url($candidate);
        if (! is_array($parsed)) {
            return '';
        }

        $scheme = strtolower((string) ($parsed['scheme'] ?? ''));
        $host = strtolower((string) ($parsed['host'] ?? ''));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return '';
        }
        if ($host === '' || ! preg_match('/^[a-z0-9][a-z0-9.-]*[a-z0-9]$/i', $host)) {
            return '';
        }

        if (isset($parsed['port']) && (! is_numeric($parsed['port']) || (int) $parsed['port'] < 1 || (int) $parsed['port'] > 65535)) {
            return '';
        }

        return $candidate;
    };

    if (strpos($raw, '#') === 0 || strpos($raw, '/') === 0 || strpos($raw, '?') === 0) {
        return sanitize_text_field($raw);
    }

    if (preg_match('/src=[\'"]([^\'"]+)[\'"]/i', $raw, $matches) && ! empty($matches[1])) {
        $raw = html_entity_decode(trim((string) $matches[1]), ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('#https?://[^\s\'"<>]+#i', $raw, $matches) && ! empty($matches[0])) {
        return $sanitize_http_url((string) $matches[0]);
    }

    if (strpos($raw, '//') === 0) {
        return $sanitize_http_url('https:' . $raw);
    }

    return $sanitize_http_url($raw);
}

function goody_normalize_api_token($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    $raw = trim($raw, "\"'` \t\n\r\0\x0B");

    if (preg_match('/^Bearer\s+(.+)$/i', $raw, $matches) && ! empty($matches[1])) {
        $raw = trim((string) $matches[1]);
    }

    $parsed = wp_parse_url($raw);
    if (is_array($parsed) && ! empty($parsed['query'])) {
        parse_str((string) $parsed['query'], $query);
        foreach (['token', 'access_token', 'api_key', 'key'] as $k) {
            if (! empty($query[$k])) {
                return sanitize_text_field((string) $query[$k]);
            }
        }

        return '';
    }

    if (is_array($parsed) && ! empty($parsed['host'])) {
        return '';
    }

    if (preg_match('/(?:token|access_token|api_key|key)=([^&\\s]+)/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field(urldecode((string) $matches[1]));
    }

    if (goody_extract_serpapi_data_id($raw) !== '' || goody_extract_google_place_id($raw) !== '' || goody_extract_google_cid($raw) !== '') {
        return '';
    }

    return sanitize_text_field($raw);
}

function goody_get_delivery_api_link($provider) {
    $provider = sanitize_key($provider);
    $endpoint = goody_normalize_url_input(goody_get_option($provider . '_api_url'));
    $token = goody_normalize_api_token(goody_get_option($provider . '_api_token'));

    if (! $endpoint) {
        return '';
    }

    $cache_key = 'goody_delivery_' . $provider . '_link';
    $cached = get_transient($cache_key);
    if (is_string($cached) && $cached !== '') {
        return $cached;
    }

    if (! goody_should_allow_live_remote_fetch()) {
        goody_schedule_delivery_api_link_warmup($provider);
        return '';
    }

    $args = [
        'timeout' => 8,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ];

    if ($token && strpos($endpoint, '{token}') !== false) {
        $endpoint = str_replace('{token}', rawurlencode($token), $endpoint);
    }

    if ($token) {
        $args['headers']['Authorization'] = 'Bearer ' . $token;
    }

    $response = wp_remote_get($endpoint, $args);
    $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);

    if ((is_wp_error($response) || $code < 200 || $code >= 300) && $token) {
        $retry_args = [
            'timeout' => 8,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];

        $retry_urls = [];
        foreach (['token', 'access_token', 'api_key', 'key'] as $param) {
            $candidate = add_query_arg($param, $token, $endpoint);
            $candidate = goody_normalize_url_input($candidate);
            if ($candidate && ! in_array($candidate, $retry_urls, true)) {
                $retry_urls[] = $candidate;
            }
        }

        foreach ($retry_urls as $retry_url) {
            $retry_response = wp_remote_get($retry_url, $retry_args);
            if (is_wp_error($retry_response)) {
                continue;
            }

            $retry_code = (int) wp_remote_retrieve_response_code($retry_response);
            if ($retry_code >= 200 && $retry_code < 300) {
                $response = $retry_response;
                $code = $retry_code;
                break;
            }
        }
    }

    if (is_wp_error($response) || $code < 200 || $code >= 300) {
        return '';
    }

    $body = wp_remote_retrieve_body($response);

    $body_url = goody_normalize_url_input($body);
    if ($body_url && (strpos($body_url, 'http://') === 0 || strpos($body_url, 'https://') === 0)) {
        set_transient($cache_key, $body_url, 10 * MINUTE_IN_SECONDS);
        return $body_url;
    }

    $data = json_decode((string) $body, true);
    if (! is_array($data)) {
        return '';
    }

    $candidates = [
        $data['order_url'] ?? '',
        $data['store_url'] ?? '',
        $data['deep_link'] ?? '',
        $data['deeplink'] ?? '',
        $data['checkout_url'] ?? '',
        $data['redirect_url'] ?? '',
        $data['link'] ?? '',
        $data['href'] ?? '',
        $data['url'] ?? '',
        $data['data']['order_url'] ?? '',
        $data['data']['store_url'] ?? '',
        $data['data']['deep_link'] ?? '',
        $data['data']['deeplink'] ?? '',
        $data['data']['checkout_url'] ?? '',
        $data['data']['redirect_url'] ?? '',
        $data['data']['link'] ?? '',
        $data['data']['href'] ?? '',
        $data['data']['url'] ?? '',
    ];

    foreach ($candidates as $candidate) {
        $candidate = goody_normalize_url_input((string) $candidate);
        if ($candidate !== '') {
            set_transient($cache_key, $candidate, 10 * MINUTE_IN_SECONDS);
            return $candidate;
        }
    }

    $queue = [$data];
    while (! empty($queue)) {
        $current = array_shift($queue);
        if (is_array($current)) {
            foreach ($current as $item) {
                if (is_array($item)) {
                    $queue[] = $item;
                } elseif (is_string($item)) {
                    $url = goody_normalize_url_input($item);
                    if (! $url) {
                        continue;
                    }

                    if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
                        set_transient($cache_key, $url, 10 * MINUTE_IN_SECONDS);
                        return $url;
                    }
                }
            }
        }
    }

    return '';
}

function goody_schedule_delivery_api_link_warmup($provider) {
    $provider = sanitize_key($provider);
    if ($provider === '') {
        return;
    }

    if (! function_exists('wp_schedule_single_event') || ! function_exists('wp_next_scheduled')) {
        return;
    }

    $args = [$provider];
    $next_run = wp_next_scheduled('goody_delivery_link_warmup_event', $args);
    if ($next_run) {
        return;
    }

    wp_schedule_single_event(time() + 20, 'goody_delivery_link_warmup_event', $args);
}

function goody_run_delivery_api_link_warmup($provider) {
    $provider = sanitize_key($provider);
    if ($provider === '') {
        return;
    }

    goody_get_delivery_api_link($provider);
}
add_action('goody_delivery_link_warmup_event', 'goody_run_delivery_api_link_warmup', 10, 1);

function goody_get_delivery_link($provider) {
    $provider = sanitize_key($provider);
    $source = goody_get_option('delivery_data_source', 'manual');

    if ($source === 'api') {
        $api_link = goody_get_delivery_api_link($provider);
        if ($api_link) {
            return $api_link;
        }
    }

    return esc_url_raw(goody_get_option($provider . '_url'));
}

function goody_is_external_url($url) {
    $url = goody_normalize_url_input($url);
    if ($url === '' || strpos($url, '#') === 0 || strpos($url, '/') === 0 || strpos($url, '?') === 0) {
        return false;
    }

    $parsed = wp_parse_url($url);
    if (! is_array($parsed) || empty($parsed['host'])) {
        return false;
    }

    $home_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
    if (! is_string($home_host) || $home_host === '') {
        return true;
    }

    return strcasecmp((string) $parsed['host'], $home_host) !== 0;
}

function goody_is_woocommerce_available() {
    return class_exists('WooCommerce') && function_exists('wc_get_product') && function_exists('wc_get_checkout_url');
}

function goody_get_delivery_provider_choices() {
    return [
        'restaurant_delivery' => __('Restaurant Delivery', 'goody'),
        'pickup' => __('Pickup', 'goody'),
        'foodpanda' => __('Foodpanda', 'goody'),
        'pathao' => __('Pathao', 'goody'),
        'ubereats' => __('Uber Eats', 'goody'),
        // Legacy providers are preserved for backward compatibility in old orders/settings.
        'glovo' => __('Glovo', 'goody'),
        'deliveroo' => __('Deliveroo', 'goody'),
    ];
}

function goody_sanitize_delivery_provider($provider) {
    if (! is_scalar($provider)) {
        return '';
    }

    $provider = sanitize_key((string) $provider);
    $choices = goody_get_delivery_provider_choices();

    return isset($choices[$provider]) ? $provider : '';
}

function goody_get_delivery_provider_label($provider) {
    $provider = goody_sanitize_delivery_provider($provider);
    $choices = goody_get_delivery_provider_choices();

    return $provider !== '' ? $choices[$provider] : '';
}

function goody_get_menu_item_direct_order_data($menu_item_id) {
    $menu_item_id = absint($menu_item_id);
    if ($menu_item_id < 1 || ! goody_is_woocommerce_available()) {
        return [
            'product_id' => 0,
            'qty' => 1,
        ];
    }

    $product_id = absint(get_post_meta($menu_item_id, 'goody_menu_wc_product_id', true));
    if ($product_id < 1) {
        $product_id = goody_get_woocommerce_product_id_from_url(goody_get_option('custom_order_url', ''));
    }

    $qty = absint(get_post_meta($menu_item_id, 'goody_menu_wc_qty', true));
    if ($qty < 1) {
        $qty = 1;
    }

    return [
        'product_id' => $product_id,
        'qty' => $qty,
    ];
}

function goody_render_direct_checkout_provider_form($product_id, $qty = 1, $args = []) {
    if (! goody_is_woocommerce_available()) {
        return '';
    }

    $product_id = absint($product_id);
    if ($product_id < 1) {
        return '';
    }

    $product = wc_get_product($product_id);
    if (! $product instanceof WC_Product || ! $product->is_purchasable()) {
        return '';
    }

    if (method_exists($product, 'is_in_stock') && ! $product->is_in_stock()) {
        return '';
    }

    $qty = absint($qty);
    if ($qty < 1) {
        $qty = 1;
    }

    $choices = goody_get_delivery_provider_choices();
    $form_class = trim('goody-direct-order-form ' . (string) ($args['form_class'] ?? ''));
    $select_id = 'goody-delivery-provider-' . $product_id . '-' . wp_generate_uuid4();
    $provider_label = (string) ($args['provider_label'] ?? __('Provider', 'goody'));
    $placeholder = (string) ($args['placeholder'] ?? __('Choose provider', 'goody'));
    $button_text = (string) ($args['button_text'] ?? goody_get_option('custom_order_text', __('Order now', 'goody')));
    $button_class = trim('button goody-direct-order-form__submit ' . (string) ($args['button_class'] ?? ''));
    $show_quantity = ! empty($args['show_quantity']);
    $quantity_label = (string) ($args['quantity_label'] ?? __('Quantity', 'goody'));
    $quantity_attrs = isset($args['quantity_attrs']) && is_array($args['quantity_attrs']) ? $args['quantity_attrs'] : [];

    ob_start();
    ?>
    <form class="<?php echo esc_attr($form_class); ?>" method="get" action="<?php echo esc_url(home_url('/')); ?>">
        <input type="hidden" name="goody_buy_now" value="<?php echo esc_attr((string) $product_id); ?>">
        <input type="hidden" name="goody_dc" value="1">
        <?php if ($show_quantity) : ?>
            <label class="goody-direct-order-form__quantity">
                <span><?php echo esc_html($quantity_label); ?></span>
                <input
                    type="number"
                    name="quantity"
                    min="<?php echo esc_attr((string) ($quantity_attrs['min'] ?? 1)); ?>"
                    <?php if (isset($quantity_attrs['max']) && (float) $quantity_attrs['max'] > 0) : ?>max="<?php echo esc_attr((string) $quantity_attrs['max']); ?>"<?php endif; ?>
                    step="<?php echo esc_attr((string) ($quantity_attrs['step'] ?? 1)); ?>"
                    value="<?php echo esc_attr((string) $qty); ?>"
                    <?php if (! empty($quantity_attrs['data_qty_for'])) : ?>data-qty-for="<?php echo esc_attr((string) $quantity_attrs['data_qty_for']); ?>"<?php endif; ?>
                >
            </label>
        <?php else : ?>
            <input type="hidden" name="quantity" value="<?php echo esc_attr((string) $qty); ?>">
        <?php endif; ?>
        <label class="goody-provider-select" for="<?php echo esc_attr($select_id); ?>">
            <span><?php echo esc_html($provider_label); ?></span>
            <select id="<?php echo esc_attr($select_id); ?>" name="goody_delivery_provider" required>
                <option value=""><?php echo esc_html($placeholder); ?></option>
                <?php foreach ($choices as $provider_key => $provider_name) : ?>
                    <option value="<?php echo esc_attr($provider_key); ?>"><?php echo esc_html($provider_name); ?></option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="<?php echo esc_attr($button_class); ?>"><?php echo esc_html($button_text); ?></button>
    </form>
    <?php

    return ob_get_clean();
}

function goody_render_direct_order_modal($args = []) {
    if (! goody_is_woocommerce_available()) {
        return '';
    }

    $choices = goody_get_delivery_provider_choices();
    $modal_id = sanitize_html_class((string) ($args['id'] ?? 'goody-direct-order-modal'));
    if ($modal_id === '') {
        $modal_id = 'goody-direct-order-modal';
    }

    $title_id = $modal_id . '-title';
    $title = (string) ($args['title'] ?? __('Complete your order', 'goody'));
    $eyebrow = (string) ($args['eyebrow'] ?? __('Order setup', 'goody'));
    $quantity_label = (string) ($args['quantity_label'] ?? __('Quantity', 'goody'));
    $provider_label = (string) ($args['provider_label'] ?? __('Delivery provider', 'goody'));
    $placeholder = (string) ($args['placeholder'] ?? __('Choose provider', 'goody'));
    $button_text = (string) ($args['button_text'] ?? __('Checkout', 'goody'));

    ob_start();
    ?>
    <div class="goody-direct-order-modal" id="<?php echo esc_attr($modal_id); ?>" data-goody-direct-order-modal hidden>
        <div class="goody-direct-order-modal__backdrop" data-goody-direct-order-close></div>
        <div class="goody-direct-order-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="<?php echo esc_attr($title_id); ?>">
            <button type="button" class="goody-direct-order-modal__close" data-goody-direct-order-close aria-label="<?php esc_attr_e('Close order popup', 'goody'); ?>"><span aria-hidden="true">&times;</span></button>

            <div class="goody-direct-order-modal__summary">
                <div class="goody-direct-order-modal__image" data-goody-direct-order-image-wrap hidden>
                    <img src="" alt="" data-goody-direct-order-image>
                </div>
                <div class="goody-direct-order-modal__copy">
                    <span><?php echo esc_html($eyebrow); ?></span>
                    <h3 id="<?php echo esc_attr($title_id); ?>" data-goody-direct-order-title><?php echo esc_html($title); ?></h3>
                    <p data-goody-direct-order-price></p>
                </div>
            </div>

            <form class="goody-direct-order-form goody-direct-order-form--modal" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <input type="hidden" name="goody_buy_now" value="" data-goody-direct-order-product>
                <input type="hidden" name="goody_dc" value="1">
                <label class="goody-direct-order-form__quantity">
                    <span><?php echo esc_html($quantity_label); ?></span>
                    <input type="number" name="quantity" min="1" step="1" value="1" data-goody-direct-order-quantity>
                </label>
                <label class="goody-provider-select">
                    <span><?php echo esc_html($provider_label); ?></span>
                    <select name="goody_delivery_provider" required data-goody-direct-order-provider>
                        <option value=""><?php echo esc_html($placeholder); ?></option>
                        <?php foreach ($choices as $provider_key => $provider_name) : ?>
                            <option value="<?php echo esc_attr($provider_key); ?>"><?php echo esc_html($provider_name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <button type="submit" class="button goody-direct-order-form__submit"><?php echo esc_html($button_text); ?></button>
            </form>
        </div>
    </div>
    <?php

    return ob_get_clean();
}

function goody_get_woocommerce_direct_checkout_url($product_id, $qty = 1, $provider = '') {
    if (! goody_is_woocommerce_available()) {
        return '';
    }

    $product_id = absint($product_id);
    if ($product_id < 1) {
        return '';
    }

    $qty = absint($qty);
    if ($qty < 1) {
        $qty = 1;
    }

    $product = wc_get_product($product_id);
    if (! $product instanceof WC_Product || ! $product->is_purchasable()) {
        return '';
    }

    if (method_exists($product, 'is_in_stock') && ! $product->is_in_stock()) {
        return '';
    }

    $query_args = [
        'goody_buy_now' => $product_id,
        'quantity' => $qty,
        'goody_dc' => '1',
    ];

    $provider = goody_sanitize_delivery_provider($provider);
    if ($provider !== '') {
        $query_args['goody_delivery_provider'] = $provider;
    }

    return add_query_arg($query_args, home_url('/'));
}

function goody_get_woocommerce_product_id_from_url($url) {
    if (! goody_is_woocommerce_available()) {
        return 0;
    }

    $url = goody_normalize_url_input($url);
    if ($url === '' || strpos($url, '#') === 0) {
        return 0;
    }

    $parsed = wp_parse_url($url);
    if (is_array($parsed) && ! empty($parsed['host'])) {
        $home_host = wp_parse_url(home_url('/'), PHP_URL_HOST);
        if (is_string($home_host) && $home_host !== '' && strcasecmp((string) $parsed['host'], $home_host) !== 0) {
            return 0;
        }
    }

    $query = '';
    if (is_array($parsed) && isset($parsed['query'])) {
        $query = (string) $parsed['query'];
    }

    if ($query !== '') {
        $params = [];
        parse_str($query, $params);
        foreach (['goody_buy_now', 'add-to-cart', 'add_to_cart', 'product_id'] as $key) {
            $candidate = absint($params[$key] ?? 0);
            if ($candidate < 1) {
                continue;
            }

            $product = wc_get_product($candidate);
            if ($product instanceof WC_Product) {
                return $candidate;
            }
        }
    }

    if (! function_exists('url_to_postid')) {
        return 0;
    }

    $lookup_url = $url;
    if (strpos($lookup_url, '/') === 0 || strpos($lookup_url, '?') === 0) {
        $lookup_url = home_url($lookup_url);
    }

    $post_id = absint(url_to_postid($lookup_url));
    if ($post_id < 1 || get_post_type($post_id) !== 'product') {
        return 0;
    }

    $product = wc_get_product($post_id);
    return $product instanceof WC_Product ? $post_id : 0;
}

function goody_maybe_get_direct_checkout_url($url, $qty = 1) {
    $url = goody_normalize_url_input($url);
    if ($url === '') {
        return '';
    }

    $product_id = goody_get_woocommerce_product_id_from_url($url);
    if ($product_id < 1) {
        return $url;
    }

    $checkout_url = goody_get_woocommerce_direct_checkout_url($product_id, $qty);
    return $checkout_url !== '' ? $checkout_url : $url;
}

function goody_get_menu_item_checkout_url($menu_item_id) {
    $menu_item_id = absint($menu_item_id);
    if ($menu_item_id < 1 || ! goody_is_woocommerce_available()) {
        return '';
    }

    $direct_order = goody_get_menu_item_direct_order_data($menu_item_id);
    if (empty($direct_order['product_id'])) {
        return '';
    }

    return goody_get_woocommerce_direct_checkout_url((int) $direct_order['product_id'], (int) $direct_order['qty']);
}

function goody_handle_woocommerce_buy_now_redirect() {
    if (! goody_is_woocommerce_available()) {
        return;
    }

    if (! isset($_GET['goody_buy_now'])) {
        return;
    }

    $product_id = absint(wp_unslash($_GET['goody_buy_now']));
    if ($product_id < 1) {
        return;
    }

    $quantity = absint(wp_unslash($_GET['quantity'] ?? 1));
    if ($quantity < 1) {
        $quantity = 1;
    }

    $product = wc_get_product($product_id);
    if (! $product instanceof WC_Product || ! $product->is_purchasable()) {
        return;
    }

    if (! WC()->cart) {
        return;
    }

    $provider = goody_sanitize_delivery_provider(wp_unslash($_GET['goody_delivery_provider'] ?? ''));
    if ($provider !== '' && WC()->session) {
        WC()->session->set('goody_delivery_provider', $provider);
    }

    // Direct checkout should represent selected dish only.
    WC()->cart->empty_cart();

    $cart_item_data = [
        'goody_direct_order' => '1',
    ];
    if ($provider !== '') {
        $cart_item_data['goody_delivery_provider'] = $provider;
    }

    $added = WC()->cart->add_to_cart($product_id, $quantity, 0, [], $cart_item_data);
    if (! $added) {
        return;
    }

    wp_safe_redirect(wc_get_checkout_url());
    exit;
}
add_action('template_redirect', 'goody_handle_woocommerce_buy_now_redirect', 11);

function goody_get_wc_session_delivery_provider() {
    if (! function_exists('WC') || ! WC() || ! WC()->session) {
        return '';
    }

    return goody_sanitize_delivery_provider((string) WC()->session->get('goody_delivery_provider', ''));
}

function goody_get_cart_delivery_provider() {
    $session_provider = goody_get_wc_session_delivery_provider();
    if ($session_provider !== '') {
        return $session_provider;
    }

    if (! function_exists('WC') || ! WC() || ! WC()->cart) {
        return '';
    }

    foreach (WC()->cart->get_cart() as $cart_item) {
        $provider = goody_sanitize_delivery_provider($cart_item['goody_delivery_provider'] ?? '');
        if ($provider !== '') {
            return $provider;
        }
    }

    return '';
}

function goody_cart_needs_delivery_provider() {
    if (! function_exists('WC') || ! WC()) {
        return false;
    }

    if (goody_get_cart_delivery_provider() !== '') {
        return true;
    }

    if (! WC()->cart) {
        return false;
    }

    foreach (WC()->cart->get_cart() as $cart_item) {
        if (! empty($cart_item['goody_direct_order'])) {
            return true;
        }
    }

    return false;
}

function goody_add_delivery_provider_cart_item_data($item_data, $cart_item) {
    $provider = goody_sanitize_delivery_provider($cart_item['goody_delivery_provider'] ?? '');
    if ($provider === '') {
        $provider = goody_get_wc_session_delivery_provider();
    }
    if ($provider === '') {
        return $item_data;
    }

    $item_data[] = [
        'key' => __('Delivery provider', 'goody'),
        'value' => goody_get_delivery_provider_label($provider),
        'display' => goody_get_delivery_provider_label($provider),
    ];

    return $item_data;
}
add_filter('woocommerce_get_item_data', 'goody_add_delivery_provider_cart_item_data', 20, 2);

function goody_prepend_checkout_delivery_provider_notice($content) {
    if (locate_template('woocommerce/checkout/form-checkout.php', false, false)) {
        return $content;
    }

    if (
        ! function_exists('is_checkout')
        || ! is_checkout()
        || (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('order-received'))
        || ! in_the_loop()
        || ! is_main_query()
    ) {
        return $content;
    }

    $provider = goody_get_cart_delivery_provider();
    if ($provider === '') {
        return $content;
    }

    $notice = '<div class="goody-checkout-provider-note"><strong>' . esc_html__('Delivery provider:', 'goody') . '</strong> ' . esc_html(goody_get_delivery_provider_label($provider)) . '</div>';

    return $notice . $content;
}
add_filter('the_content', 'goody_prepend_checkout_delivery_provider_notice', 8);

function goody_add_checkout_delivery_provider_field($fields) {
    if (goody_is_store_api_request() || goody_is_block_checkout_context()) {
        return $fields;
    }

    if (! is_array($fields)) {
        return $fields;
    }

    $source_billing = isset($fields['billing']) && is_array($fields['billing']) ? $fields['billing'] : [];

    // Hard-enforce exactly the requested customer fields.
    $fields['billing'] = [];

    $name_field = isset($source_billing['billing_first_name']) && is_array($source_billing['billing_first_name']) ? $source_billing['billing_first_name'] : [];
    $name_field['label'] = __('Full Name', 'goody');
    $name_field['required'] = true;
    $name_field['priority'] = 5;
    $name_field['class'] = ['form-row-wide'];
    $name_field['placeholder'] = __('Enter your full name', 'goody');
    $fields['billing']['billing_first_name'] = $name_field;

    $phone_field = isset($source_billing['billing_phone']) && is_array($source_billing['billing_phone']) ? $source_billing['billing_phone'] : [];
    $phone_field['label'] = __('Phone Number', 'goody');
    $phone_field['required'] = true;
    $phone_field['priority'] = 20;
    $phone_field['class'] = ['form-row-first'];
    $phone_field['placeholder'] = __('e.g. +8801XXXXXXXXX', 'goody');
    $fields['billing']['billing_phone'] = $phone_field;

    $email_field = isset($source_billing['billing_email']) && is_array($source_billing['billing_email']) ? $source_billing['billing_email'] : [];
    $email_field['label'] = __('Email Address', 'goody');
    $email_field['required'] = true;
    $email_field['priority'] = 15;
    $email_field['class'] = ['form-row-last'];
    $fields['billing']['billing_email'] = $email_field;

    $address_field = isset($source_billing['billing_address_1']) && is_array($source_billing['billing_address_1']) ? $source_billing['billing_address_1'] : [];
    $address_field['label'] = __('Full Address', 'goody');
    $address_field['required'] = true;
    $address_field['priority'] = 30;
    $address_field['class'] = ['form-row-wide'];
    $address_field['placeholder'] = __('House, Road, Area, City', 'goody');
    $fields['billing']['billing_address_1'] = $address_field;

    // Remove extra checkout sections/fields.
    $fields['shipping'] = [];
    $fields['order'] = [];
    $fields['account'] = [];

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'goody_add_checkout_delivery_provider_field', 999);

function goody_relax_unneeded_address_requirements($fields) {
    if (goody_is_store_api_request() || goody_is_block_checkout_context()) {
        return $fields;
    }

    if (! is_array($fields)) {
        return $fields;
    }

    foreach (['first_name', 'last_name', 'company', 'country', 'address_2', 'city', 'state', 'postcode'] as $key) {
        if (! isset($fields[$key]) || ! is_array($fields[$key])) {
            continue;
        }
        $fields[$key]['required'] = false;
    }

    if (isset($fields['address_1']) && is_array($fields['address_1'])) {
        $fields['address_1']['required'] = true;
    }

    return $fields;
}
add_filter('woocommerce_default_address_fields', 'goody_relax_unneeded_address_requirements', 999);

function goody_restrict_billing_field_requirements($fields) {
    if (goody_is_store_api_request() || goody_is_block_checkout_context()) {
        return $fields;
    }

    if (! is_array($fields)) {
        return $fields;
    }

    if (isset($fields['billing_phone']) && is_array($fields['billing_phone'])) {
        $fields['billing_phone']['required'] = true;
    }
    if (isset($fields['billing_email']) && is_array($fields['billing_email'])) {
        $fields['billing_email']['required'] = true;
    }
    if (isset($fields['billing_address_1']) && is_array($fields['billing_address_1'])) {
        $fields['billing_address_1']['required'] = true;
    }

    return $fields;
}
add_filter('woocommerce_billing_fields', 'goody_restrict_billing_field_requirements', 999);

function goody_checkout_hide_ship_to_different_address($checked) {
    return false;
}
add_filter('woocommerce_ship_to_different_address_checked', 'goody_checkout_hide_ship_to_different_address', 20);

function goody_is_valid_checkout_phone($phone) {
    $phone = trim((string) $phone);
    if ($phone === '') {
        return false;
    }

    if (! preg_match('/^[0-9+\-\s]+$/', $phone)) {
        return false;
    }

    $digits = preg_replace('/\D+/', '', $phone);
    if (! is_string($digits)) {
        return false;
    }
    $length = strlen($digits);
    if ($length < 8 || $length > 15) {
        return false;
    }

    $blocked = [
        '00000000',
        '11111111',
        '12345678',
        '99999999',
        '0123456789',
    ];
    if (in_array($digits, $blocked, true)) {
        return false;
    }

    // Block obvious repeated fake patterns like 22222222, 33333333, etc.
    if (preg_match('/^(\d)\1{7,}$/', $digits)) {
        return false;
    }

    return true;
}

function goody_is_store_api_request() {
    if (function_exists('wp_is_serving_rest_request') && wp_is_serving_rest_request()) {
        return true;
    }

    if (function_exists('wp_doing_rest') && wp_doing_rest()) {
        return true;
    }

    return defined('REST_REQUEST') && REST_REQUEST;
}

function goody_is_block_checkout_context() {
    if (! empty($GLOBALS['goody_force_classic_checkout'])) {
        return false;
    }

    // Theme-level checkout override is active, treat checkout as classic context.
    if (locate_template('woocommerce/checkout/form-checkout.php', false, false)) {
        return false;
    }

    if (! function_exists('is_checkout') || ! is_checkout()) {
        return false;
    }

    if (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('order-received')) {
        return false;
    }

    $checkout_page_id = function_exists('wc_get_page_id') ? (int) wc_get_page_id('checkout') : 0;
    if ($checkout_page_id <= 0) {
        return false;
    }

    $checkout_post = get_post($checkout_page_id);
    if (! ($checkout_post instanceof WP_Post)) {
        return false;
    }

    return function_exists('has_block')
        && has_block('woocommerce/checkout', $checkout_post)
        && ! has_shortcode((string) $checkout_post->post_content, 'woocommerce_checkout');
}

function goody_strip_unwanted_checkout_billing_fields($fields) {
    if (! is_array($fields)) {
        return $fields;
    }

    $remove_keys = [
        'billing_last_name',
        'billing_country',
        'billing_city',
        'billing_state',
        'billing_postcode',
    ];

    foreach ($remove_keys as $key) {
        if (isset($fields[$key])) {
            unset($fields[$key]);
        }
    }

    return $fields;
}
add_filter('woocommerce_billing_fields', 'goody_strip_unwanted_checkout_billing_fields', 1001);

function goody_add_checkout_honeypot_field($checkout) {
    if (goody_is_block_checkout_context()) {
        return;
    }

    echo '<div class="goody-checkout-honeypot" aria-hidden="true">';
    woocommerce_form_field('goody_checkout_website', [
        'type' => 'text',
        'label' => __('Leave this field empty', 'goody'),
        'required' => false,
        'class' => ['goody-checkout-honeypot__field'],
        'input_class' => ['goody-checkout-honeypot__input'],
        'autocomplete' => 'off',
    ], $checkout->get_value('goody_checkout_website'));
    echo '</div>';
}
add_action('woocommerce_after_order_notes', 'goody_add_checkout_honeypot_field', 25);

function goody_validate_checkout_delivery_provider() {
    // WooCommerce block checkout uses Store API payload, not classic checkout POST fields.
    if (goody_is_store_api_request() || goody_is_block_checkout_context()) {
        return;
    }

    $honeypot = sanitize_text_field((string) wp_unslash($_POST['goody_checkout_website'] ?? ''));
    if ($honeypot !== '' && function_exists('wc_add_notice')) {
        wc_add_notice(__('We could not process your request. Please refresh and try again.', 'goody'), 'error');
        return;
    }

    $phone = sanitize_text_field((string) wp_unslash($_POST['billing_phone'] ?? ''));
    $full_name = sanitize_text_field((string) wp_unslash($_POST['billing_first_name'] ?? ''));
    if ($full_name === '' && function_exists('wc_add_notice')) {
        wc_add_notice(__('Full name is required.', 'goody'), 'error');
    }

    if ($phone === '' && function_exists('wc_add_notice')) {
        wc_add_notice(__('Phone number is required.', 'goody'), 'error');
    } elseif (! goody_is_valid_checkout_phone($phone) && function_exists('wc_add_notice')) {
        wc_add_notice(__('Please enter a valid phone number (8-15 digits, using only numbers, +, space or hyphen).', 'goody'), 'error');
    }

    $email = sanitize_email((string) wp_unslash($_POST['billing_email'] ?? ''));
    if ($email === '' && function_exists('wc_add_notice')) {
        wc_add_notice(__('Email address is required.', 'goody'), 'error');
    } elseif (! is_email($email) && function_exists('wc_add_notice')) {
        wc_add_notice(__('Please enter a valid email address.', 'goody'), 'error');
    }

    $address = sanitize_text_field((string) wp_unslash($_POST['billing_address_1'] ?? ''));
    if ($address === '' && function_exists('wc_add_notice')) {
        wc_add_notice(__('Full address is required.', 'goody'), 'error');
    }
}
add_action('woocommerce_checkout_process', 'goody_validate_checkout_delivery_provider');

function goody_save_checkout_delivery_provider_to_order($order, $data) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $provider = goody_sanitize_delivery_provider(wp_unslash($_POST['goody_delivery_provider'] ?? ''));
    if ($provider === '') {
        $provider = goody_get_cart_delivery_provider();
    }
    if ($provider === '') {
        return;
    }

    $order->update_meta_data('_goody_delivery_provider', $provider);
    $order->update_meta_data('delivery_provider', $provider);

    if (function_exists('WC') && WC() && WC()->session) {
        WC()->session->set('goody_delivery_provider', $provider);
    }
}
add_action('woocommerce_checkout_create_order', 'goody_save_checkout_delivery_provider_to_order', 20, 2);

function goody_save_store_api_checkout_delivery_provider_to_order($order, $request = null) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $provider = goody_get_cart_delivery_provider();
    if ($provider === '') {
        return;
    }

    $order->update_meta_data('_goody_delivery_provider', $provider);
    $order->update_meta_data('delivery_provider', $provider);
}
add_action('woocommerce_store_api_checkout_update_order_from_request', 'goody_save_store_api_checkout_delivery_provider_to_order', 20, 2);

function goody_add_delivery_provider_to_order_line_item($item, $cart_item_key, $values, $order) {
    if (! ($item instanceof WC_Order_Item_Product) && ! ($item instanceof WC_Order_Item_Fee)) {
        return;
    }

    $provider = goody_sanitize_delivery_provider($values['goody_delivery_provider'] ?? '');
    if ($provider === '') {
        $provider = goody_get_wc_session_delivery_provider();
    }
    if ($provider === '') {
        return;
    }

    $item->add_meta_data(__('Delivery provider', 'goody'), goody_get_delivery_provider_label($provider), true);
}
add_action('woocommerce_checkout_create_order_line_item', 'goody_add_delivery_provider_to_order_line_item', 20, 4);

function goody_show_delivery_provider_in_admin_order($order) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $provider = goody_sanitize_delivery_provider((string) $order->get_meta('_goody_delivery_provider', true));
    if ($provider === '') {
        return;
    }

    echo '<p><strong>' . esc_html__('Delivery Provider:', 'goody') . '</strong> ' . esc_html(goody_get_delivery_provider_label($provider)) . '</p>';
}
add_action('woocommerce_admin_order_data_after_billing_address', 'goody_show_delivery_provider_in_admin_order', 30);

function goody_show_delivery_provider_in_order_emails($order, $sent_to_admin, $plain_text) {
    if (! $order instanceof WC_Order) {
        return;
    }

    $provider = goody_sanitize_delivery_provider((string) $order->get_meta('_goody_delivery_provider', true));
    if ($provider === '') {
        return;
    }

    $label = goody_get_delivery_provider_label($provider);
    if ($plain_text) {
        echo "\n" . __('Delivery Provider:', 'goody') . ' ' . $label . "\n";
        return;
    }

    echo '<p><strong>' . esc_html__('Delivery Provider:', 'goody') . '</strong> ' . esc_html($label) . '</p>';
}
add_action('woocommerce_email_order_meta', 'goody_show_delivery_provider_in_order_emails', 25, 3);

function goody_clear_checkout_delivery_provider_session() {
    if (function_exists('WC') && WC() && WC()->session) {
        WC()->session->set('goody_delivery_provider', null);
    }
}
add_action('woocommerce_thankyou', 'goody_clear_checkout_delivery_provider_session', 20);

function goody_get_delivery_api_tokens($provider = '') {
    $tokens = [];
    $provider = sanitize_key((string) $provider);

    if ($provider !== '') {
        $provider_token = goody_get_delivery_provider_token($provider);
        if ($provider_token !== '') {
            $tokens[] = $provider_token;
        }
    }

    foreach ([
        'glovo_api_token',
        'ubereats_api_token',
        'deliveroo_api_token',
        'custom_order_create_api_token',
    ] as $key) {
        $token = goody_normalize_api_token(goody_get_option($key, ''));
        if ($token !== '') {
            $tokens[] = $token;
        }
    }

    $ubereats_oauth_token = goody_get_ubereats_oauth_access_token();
    if ($ubereats_oauth_token !== '') {
        $tokens[] = $ubereats_oauth_token;
    }

    return array_values(array_unique(array_filter($tokens)));
}

function goody_get_tracking_empty_state() {
    return [
        'url' => '',
        'order_id' => '',
        'order_key' => '',
        'consignment_id' => '',
        'shipping_name' => '',
        'shipping_phone' => '',
        'shipping_address' => '',
        'payment_amount' => '',
        'payment_currency' => '',
        'payment_method' => '',
        'status' => '',
        'stage' => '',
        'eta' => '',
        'note' => '',
        'provider' => '',
        'message' => '',
        'timeline' => [],
        'order_type' => '',
        'manual_mode' => false,
        'source' => '',
    ];
}

function goody_get_tracking_page_url($order_id = '', $order_key = '') {
    $base_url = goody_normalize_url_input(goody_get_option('reservation_status_page_url', ''));
    if ($base_url === '') {
        $pages = get_pages([
            'post_status' => ['publish', 'private'],
            'number' => 100,
        ]);

        foreach ($pages as $page) {
            if (! $page instanceof WP_Post) {
                continue;
            }

            if (has_shortcode((string) $page->post_content, 'reservation_order_status')) {
                $base_url = get_permalink($page);
                break;
            }
        }
    }

    $args = [];
    if ($base_url === '') {
        $base_url = home_url('/');
        $args['goody_tracking'] = '1';
    } else {
        $args['tracking'] = 'order';
    }

    $order_id = sanitize_text_field((string) $order_id);
    if ($order_id !== '') {
        $args['order_id'] = $order_id;
    }

    $order_key = sanitize_text_field((string) $order_key);
    if ($order_key !== '') {
        $args['key'] = $order_key;
    }

    return add_query_arg($args, $base_url);
}

function goody_get_tracking_stage_definitions() {
    return [
        'requested' => __('Requested', 'goody'),
        'confirmed' => __('Confirmed', 'goody'),
        'preparing' => __('Preparing', 'goody'),
        'ready' => __('Ready', 'goody'),
        'with_delivery_provider' => __('Delivery Provider', 'goody'),
        'completed' => __('Completed', 'goody'),
    ];
}

function goody_get_tracking_stage_index($stage) {
    $stage = sanitize_key((string) $stage);
    $keys = array_keys(goody_get_tracking_stage_definitions());
    $index = array_search($stage, $keys, true);

    return $index === false ? -1 : (int) $index;
}

function goody_normalize_tracking_stage($value) {
    $raw = strtolower(trim((string) $value));
    if ($raw === '') {
        return '';
    }

    $raw = str_replace(['-', ' '], '_', $raw);

    $aliases = [
        'requested' => 'requested',
        'accepted' => 'requested',
        'order_received' => 'requested',
        'received' => 'requested',
        'pending' => 'requested',
        'on_hold' => 'requested',
        'draft' => 'requested',
        'checkout_draft' => 'requested',
        'failed' => 'requested',
        'cancelled' => 'requested',
        'refunded' => 'requested',
        'confirmed' => 'confirmed',
        'picked' => 'confirmed',
        'pickup' => 'confirmed',
        'picked_up' => 'confirmed',
        'assigned' => 'confirmed',
        'courier_assigned' => 'confirmed',
        'preparing' => 'preparing',
        'in_transit' => 'preparing',
        'transit' => 'preparing',
        'on_the_way' => 'preparing',
        'shipping' => 'preparing',
        'processing' => 'preparing',
        'shipped' => 'preparing',
        'ready_for_delivery' => 'ready',
        'out_for_delivery' => 'ready',
        'ready' => 'ready',
        'with_delivery_provider' => 'with_delivery_provider',
        'delivery_provider' => 'with_delivery_provider',
        'provider' => 'with_delivery_provider',
        'delivered' => 'completed',
        'complete' => 'completed',
        'completed' => 'completed',
    ];

    if (isset($aliases[$raw])) {
        return $aliases[$raw];
    }

    return '';
}

function goody_detect_tracking_stage_from_text($text) {
    $text = sanitize_text_field((string) $text);
    if ($text === '') {
        return '';
    }

    $normalized = goody_normalize_tracking_stage($text);
    if ($normalized !== '') {
        return $normalized;
    }

    $needle = strtolower($text);
    if (strpos($needle, 'deliver') !== false) {
        return strpos($needle, 'provider') !== false ? 'with_delivery_provider' : 'completed';
    }
    if (strpos($needle, 'ready') !== false || strpos($needle, 'out for delivery') !== false) {
        return 'ready';
    }
    if (strpos($needle, 'transit') !== false || strpos($needle, 'way') !== false || strpos($needle, 'ship') !== false) {
        return 'preparing';
    }
    if (strpos($needle, 'pick') !== false || strpos($needle, 'assign') !== false || strpos($needle, 'courier') !== false) {
        return 'confirmed';
    }
    if (strpos($needle, 'accept') !== false || strpos($needle, 'receiv') !== false || strpos($needle, 'confirm') !== false || strpos($needle, 'new order') !== false) {
        return strpos($needle, 'confirm') !== false ? 'confirmed' : 'requested';
    }
    if (strpos($needle, 'complete') !== false || strpos($needle, 'serve') !== false || strpos($needle, 'finished') !== false) {
        return 'completed';
    }

    return '';
}

function goody_get_tracking_stage_order_for_type($order_type = '') {
    $order_type = sanitize_key((string) $order_type);
    if ($order_type === 'delivery') {
        return ['requested', 'confirmed', 'preparing', 'ready', 'with_delivery_provider', 'completed'];
    }

    return ['requested', 'confirmed', 'preparing', 'ready', 'completed'];
}

function goody_format_tracking_datetime($value) {
    if (class_exists('WC_DateTime') && $value instanceof WC_DateTime) {
        $timestamp = (int) $value->getTimestamp();
        if ($timestamp > 0) {
            return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
        }
    }

    if (is_numeric($value)) {
        $timestamp = (int) $value;
        if ($timestamp > 0) {
            return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
        }
    }

    $raw = sanitize_text_field((string) $value);
    if ($raw === '') {
        return '';
    }

    $timestamp = strtotime($raw);
    if ($timestamp !== false && $timestamp > 0) {
        return date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $timestamp);
    }

    return $raw;
}

function goody_normalize_tracking_timeline($items) {
    if (! is_array($items) || empty($items)) {
        return [];
    }

    $is_list = array_keys($items) === range(0, count($items) - 1);
    if (! $is_list) {
        $items = [$items];
    }

    $stages = goody_get_tracking_stage_definitions();
    $timeline = [];

    foreach ($items as $item) {
        if (! is_array($item)) {
            continue;
        }

        $title = sanitize_text_field((string) ($item['status'] ?? $item['title'] ?? $item['label'] ?? $item['state'] ?? ''));
        $description = sanitize_text_field((string) ($item['description'] ?? $item['message'] ?? $item['note'] ?? $item['details'] ?? ''));
        $stage = goody_normalize_tracking_stage((string) ($item['stage'] ?? $title));
        if ($stage === '') {
            $stage = goody_detect_tracking_stage_from_text($title . ' ' . $description);
        }

        if ($title === '' && $stage !== '' && isset($stages[$stage])) {
            $title = (string) $stages[$stage];
        }

        $time_display = goody_format_tracking_datetime($item['time'] ?? $item['timestamp'] ?? $item['date'] ?? $item['created_at'] ?? $item['updated_at'] ?? '');

        if ($title === '' && $description === '' && $time_display === '') {
            continue;
        }

        $timeline[] = [
            'stage' => $stage,
            'title' => $title,
            'description' => $description,
            'time' => $time_display,
            'completed' => isset($item['completed']) ? (bool) $item['completed'] : true,
        ];
    }

    return $timeline;
}

function goody_get_tracking_steps($state) {
    $defs = goody_get_tracking_stage_definitions();
    $order_type = sanitize_key((string) ($state['order_type'] ?? ''));
    $allowed_stage_keys = goody_get_tracking_stage_order_for_type($order_type);
    $current_stage = goody_normalize_tracking_stage($state['stage'] ?? '');
    if ($current_stage === '') {
        $current_stage = goody_detect_tracking_stage_from_text((string) ($state['status'] ?? ''));
    }

    $timeline = $state['timeline'] ?? [];
    if ($current_stage === '' && is_array($timeline) && ! empty($timeline)) {
        $last = end($timeline);
        if (is_array($last)) {
            $current_stage = goody_normalize_tracking_stage($last['stage'] ?? '');
            if ($current_stage === '') {
                $current_stage = goody_detect_tracking_stage_from_text((string) ($last['title'] ?? ''));
            }
        }
        reset($timeline);
    }

    $current_index = goody_get_tracking_stage_index($current_stage);
    $current_order_index = array_search($current_stage, $allowed_stage_keys, true);
    $current_order_index = $current_order_index === false ? -1 : (int) $current_order_index;
    $steps = [];
    foreach ($allowed_stage_keys as $index => $key) {
        $label = (string) ($defs[$key] ?? ucfirst(str_replace('_', ' ', $key)));
        $steps[] = [
            'key' => $key,
            'label' => $label,
            'done' => $current_order_index >= 0 && $index <= $current_order_index,
            'active' => $current_order_index === $index,
        ];
    }

    return $steps;
}

function goody_get_woocommerce_order_id_from_request($override = '') {
    $order_id = absint($override);
    if ($order_id < 1 && is_scalar($override)) {
        $override_text = trim((string) $override);
        if ($override_text !== '' && preg_match('/(\d+)/', $override_text, $matches) && ! empty($matches[1])) {
            $order_id = absint($matches[1]);
        }
    }
    if ($order_id > 0) {
        return $order_id;
    }

    foreach (['order_id', 'order', 'id', 'order-received', 'view-order', 'order-pay'] as $key) {
        $raw_value = wp_unslash($_GET[$key] ?? 0);
        $value = absint($raw_value);
        if ($value < 1 && is_scalar($raw_value)) {
            $raw_text = trim((string) $raw_value);
            if ($raw_text !== '' && preg_match('/(\d+)/', $raw_text, $matches) && ! empty($matches[1])) {
                $value = absint($matches[1]);
            }
        }
        if ($value > 0) {
            return $value;
        }
    }

    if (function_exists('get_query_var')) {
        foreach (['order-received', 'view-order', 'order-pay'] as $key) {
            $value = absint(get_query_var($key));
            if ($value > 0) {
                return $value;
            }
        }
    }

    $request_uri = sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI'] ?? ''));
    if ($request_uri !== '' && preg_match('#/(?:order-received|view-order|order-pay)/([0-9]+)#', $request_uri, $matches) && ! empty($matches[1])) {
        return absint($matches[1]);
    }

    return 0;
}

function goody_get_tracking_order_id($override = '') {
    $order_id = sanitize_text_field((string) $override);
    if ($order_id !== '') {
        return $order_id;
    }

    $keys = [
        'order_id',
        'track',
        'tracking_id',
        'order',
        'id',
        'external_order_id',
        'reference',
        'ref',
    ];

    foreach ($keys as $key) {
        $value = sanitize_text_field(wp_unslash($_GET[$key] ?? ''));
        if ($value !== '') {
            return $value;
        }
    }

    $woo_order_id = goody_get_woocommerce_order_id_from_request();
    if ($woo_order_id > 0) {
        return (string) $woo_order_id;
    }

    return '';
}

function goody_get_tracking_order_key($override = '') {
    $order_key = sanitize_text_field((string) $override);
    if ($order_key !== '') {
        return $order_key;
    }

    foreach (['key', 'order_key', 'wc_order_key'] as $param) {
        $value = sanitize_text_field(wp_unslash($_GET[$param] ?? ''));
        if ($value !== '') {
            return $value;
        }
    }

    return '';
}

function goody_get_tracking_external_order_id($override = '') {
    $external_order_id = sanitize_text_field((string) $override);
    if ($external_order_id !== '') {
        return $external_order_id;
    }

    foreach (['external_order_id', 'external_id', 'consignment_id', 'tracking_id', 'awb', 'shipment_id'] as $param) {
        $value = sanitize_text_field(wp_unslash($_GET[$param] ?? ''));
        if ($value !== '') {
            return $value;
        }
    }

    $order_id = goody_get_woocommerce_order_id_from_request();
    if ($order_id > 0 && function_exists('wc_get_order')) {
        $order = wc_get_order($order_id);
        if ($order instanceof WC_Order) {
            $external_meta_keys = [
                '_goody_external_order_id',
                '_goody_consignment_id',
                '_goody_tracking_id',
                '_goody_shipment_id',
            ];
            foreach ($external_meta_keys as $meta_key) {
                $value = sanitize_text_field((string) $order->get_meta($meta_key, true));
                if ($value !== '') {
                    return $value;
                }
            }
        }
    }

    return '';
}

function goody_should_allow_live_remote_fetch() {
    if (is_admin()) {
        return true;
    }

    if (function_exists('wp_doing_ajax') && wp_doing_ajax()) {
        return true;
    }

    if (function_exists('wp_doing_cron') && wp_doing_cron()) {
        return true;
    }

    if ((defined('REST_REQUEST') && REST_REQUEST) || (defined('WP_CLI') && WP_CLI)) {
        return true;
    }

    return false;
}

function goody_is_tracking_api_source($url) {
    $url = trim((string) $url);
    if ($url === '') {
        return false;
    }

    if (
        strpos($url, '{order_id}') !== false ||
        strpos($url, '{order_key}') !== false ||
        strpos($url, '{external_order_id}') !== false ||
        strpos($url, '{token}') !== false
    ) {
        return true;
    }

    $needle = strtolower($url);
    if (
        strpos($needle, '/api/') !== false ||
        strpos($needle, '/wp-json/') !== false ||
        strpos($needle, '.json') !== false ||
        strpos($needle, 'format=json') !== false ||
        strpos($needle, 'output=json') !== false
    ) {
        return true;
    }

    return false;
}

function goody_apply_tracking_placeholders($url, $order_id_override = '', $order_key_override = '', $external_order_id_override = '') {
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    $order_id = goody_get_tracking_order_id($order_id_override);
    $order_key = goody_get_tracking_order_key($order_key_override);
    $external_order_id = goody_get_tracking_external_order_id($external_order_id_override);

    if (strpos($url, '{order_id}') !== false) {
        if ($order_id === '') {
            return '';
        }
        $url = str_replace('{order_id}', rawurlencode($order_id), $url);
    }

    if (strpos($url, '{order_key}') !== false) {
        if ($order_key === '') {
            return '';
        }
        $url = str_replace('{order_key}', rawurlencode($order_key), $url);
    }

    if (strpos($url, '{external_order_id}') !== false) {
        if ($external_order_id === '') {
            return '';
        }
        $url = str_replace('{external_order_id}', rawurlencode($external_order_id), $url);
    }

    if (strpos($url, '{token}') !== false) {
        $tokens = goody_get_delivery_api_tokens();
        $token = $tokens[0] ?? '';
        $url = str_replace('{token}', rawurlencode($token), $url);
    }

    return goody_normalize_url_input($url);
}

function goody_get_ubereats_environment() {
    $environment = sanitize_key((string) goody_get_option('ubereats_environment', 'production'));
    if (! in_array($environment, ['sandbox', 'production'], true)) {
        $environment = 'production';
    }

    return $environment;
}

function goody_get_ubereats_api_base_url() {
    $custom_base = goody_normalize_url_input((string) goody_get_option('ubereats_api_base_url', ''));
    if ($custom_base !== '') {
        return untrailingslashit($custom_base);
    }

    return goody_get_ubereats_environment() === 'sandbox'
        ? 'https://test-api.uber.com'
        : 'https://api.uber.com';
}

function goody_get_ubereats_oauth_default_token_url() {
    return goody_get_ubereats_environment() === 'sandbox'
        ? 'https://sandbox-login.uber.com/oauth/v2/token'
        : 'https://auth.uber.com/oauth/v2/token';
}

function goody_get_ubereats_default_create_order_url() {
    return goody_get_ubereats_api_base_url() . '/v1/eats/deliveries/orders';
}

function goody_get_ubereats_default_tracking_template() {
    return goody_get_ubereats_api_base_url() . '/v1/eats/deliveries/orders/{external_order_id}';
}

function goody_get_ubereats_tracking_status_url($external_order_id = '') {
    $template = goody_get_ubereats_default_tracking_template();
    $external_order_id = sanitize_text_field((string) $external_order_id);
    if ($external_order_id !== '') {
        $template = str_replace('{external_order_id}', rawurlencode($external_order_id), $template);
    }

    return goody_normalize_url_input($template);
}

function goody_get_ubereats_oauth_last_error() {
    $error = get_transient('goody_ubereats_oauth_last_error');
    if (! is_string($error)) {
        return '';
    }

    return sanitize_text_field($error);
}

function goody_set_ubereats_oauth_last_error($message = '') {
    $message = sanitize_text_field((string) $message);
    if ($message === '') {
        delete_transient('goody_ubereats_oauth_last_error');
        return;
    }

    set_transient('goody_ubereats_oauth_last_error', $message, 10 * MINUTE_IN_SECONDS);
}

function goody_get_ubereats_oauth_config() {
    $client_id = sanitize_text_field((string) goody_get_option('ubereats_client_id', ''));
    $client_secret = sanitize_text_field((string) goody_get_option('ubereats_client_secret', ''));
    $scope = trim((string) goody_get_option('ubereats_oauth_scope', ''));
    if ($scope === '') {
        $scope = 'eats.deliveries';
    }
    $scope = preg_replace('/\s+/', ' ', sanitize_text_field($scope));

    $token_url = goody_normalize_url_input((string) goody_get_option('ubereats_oauth_token_url', goody_get_ubereats_oauth_default_token_url()));
    if ($token_url === '') {
        $token_url = goody_get_ubereats_oauth_default_token_url();
    }

    return [
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'scope' => $scope,
        'token_url' => $token_url,
    ];
}

function goody_get_ubereats_oauth_access_token($force_refresh = false) {
    $config = goody_get_ubereats_oauth_config();
    if ($config['client_id'] === '' || $config['client_secret'] === '') {
        return '';
    }

    $fingerprint = md5($config['client_id'] . '|' . $config['client_secret'] . '|' . $config['scope'] . '|' . $config['token_url']);
    $cache_key = 'goody_ubereats_oauth_access_token';

    if (! $force_refresh) {
        $cached = get_transient($cache_key);
        if (is_array($cached)) {
            $cached_fingerprint = sanitize_text_field((string) ($cached['fingerprint'] ?? ''));
            $cached_token = goody_normalize_api_token((string) ($cached['token'] ?? ''));
            if ($cached_fingerprint === $fingerprint && $cached_token !== '') {
                return $cached_token;
            }
        }
    }

    $request_token = static function ($body) use ($config) {
        return wp_remote_post($config['token_url'], [
            'timeout' => 12,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'body' => $body,
        ]);
    };

    $request_body = [
        'client_id' => $config['client_id'],
        'client_secret' => $config['client_secret'],
        'grant_type' => 'client_credentials',
    ];
    if ($config['scope'] !== '') {
        $request_body['scope'] = $config['scope'];
    }

    $response = $request_token($request_body);

    if (is_wp_error($response)) {
        goody_set_ubereats_oauth_last_error((string) $response->get_error_message());
        return '';
    }

    $status_code = (int) wp_remote_retrieve_response_code($response);
    $body = (string) wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Some Uber apps reject requested scope. Retry once without scope.
    if (
        $status_code >= 400 &&
        $status_code < 500 &&
        is_array($data) &&
        strtolower((string) ($data['error'] ?? '')) === 'invalid_scope' &&
        isset($request_body['scope'])
    ) {
        unset($request_body['scope']);
        $response = $request_token($request_body);
        if (is_wp_error($response)) {
            goody_set_ubereats_oauth_last_error((string) $response->get_error_message());
            return '';
        }
        $status_code = (int) wp_remote_retrieve_response_code($response);
        $body = (string) wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
    }

    if ($status_code < 200 || $status_code >= 300 || ! is_array($data)) {
        $error = '';
        if (is_array($data)) {
            $error_value = goody_reviews_pick_value($data, ['error_description', 'message', 'error.message', 'error']);
            if (is_scalar($error_value)) {
                $error = sanitize_text_field((string) $error_value);
            }
        }
        if ($error === '') {
            $error = sprintf('Uber OAuth failed (HTTP %d)', $status_code);
        }
        goody_set_ubereats_oauth_last_error($error);
        return '';
    }

    $token = goody_normalize_api_token((string) ($data['access_token'] ?? ''));
    if ($token === '') {
        goody_set_ubereats_oauth_last_error('Uber OAuth token missing in response.');
        return '';
    }

    $expires_in = absint($data['expires_in'] ?? 3600);
    if ($expires_in < 120) {
        $expires_in = 120;
    }
    $ttl = max(60, $expires_in - 120);
    set_transient($cache_key, [
        'fingerprint' => $fingerprint,
        'token' => $token,
    ], $ttl);
    goody_set_ubereats_oauth_last_error('');

    return $token;
}

function goody_get_delivery_provider_token($provider) {
    $provider = sanitize_key((string) $provider);

    if ($provider === 'custom') {
        $token = goody_normalize_api_token(goody_get_option('custom_order_create_api_token', ''));
        if ($token !== '') {
            return $token;
        }
    }

    if ($provider === 'ubereats') {
        $provider_token = goody_normalize_api_token(goody_get_option('ubereats_api_token', ''));
        if ($provider_token !== '') {
            return $provider_token;
        }

        $oauth_token = goody_get_ubereats_oauth_access_token();
        if ($oauth_token !== '') {
            return $oauth_token;
        }
    }

    $provider_token = goody_normalize_api_token(goody_get_option($provider . '_api_token', ''));
    if ($provider_token !== '') {
        return $provider_token;
    }

    return '';
}

function goody_delivery_api_request($method, $url, $args = [], $tokens = []) {
    $method = strtoupper(trim((string) $method));
    if (! in_array($method, ['GET', 'POST'], true)) {
        $method = 'GET';
    }

    $url = goody_normalize_url_input($url);
    if ($url === '') {
        return new WP_Error('goody_invalid_delivery_url', __('Invalid delivery API URL.', 'goody'));
    }

    $base_args = is_array($args) ? $args : [];
    $base_headers = [];
    if (isset($base_args['headers']) && is_array($base_args['headers'])) {
        $base_headers = $base_args['headers'];
    }
    $base_args['headers'] = $base_headers;
    if (empty($base_args['timeout'])) {
        $base_args['timeout'] = 15;
    }

    $tokens = is_array($tokens) ? $tokens : [$tokens];
    $tokens = array_values(array_unique(array_filter(array_map('goody_normalize_api_token', $tokens))));
    if (empty($tokens)) {
        $tokens = [''];
    }

    $attempts = [];
    foreach ($tokens as $token) {
        $headers = $base_headers;
        if ($token !== '') {
            $attempts[] = [
                'url' => $url,
                'headers' => array_merge($headers, ['Authorization' => 'Bearer ' . $token]),
            ];
            $attempts[] = [
                'url' => $url,
                'headers' => array_merge($headers, ['Authorization' => $token]),
            ];
            $attempts[] = [
                'url' => $url,
                'headers' => array_merge($headers, ['x-api-key' => $token]),
            ];
            $attempts[] = [
                'url' => $url,
                'headers' => array_merge($headers, ['api-key' => $token]),
            ];

            foreach (['api_key', 'apikey', 'token', 'access_token', 'key'] as $param) {
                $token_url = goody_normalize_url_input(add_query_arg($param, $token, $url));
                if ($token_url !== '') {
                    $attempts[] = [
                        'url' => $token_url,
                        'headers' => $headers,
                    ];
                }
            }
        } else {
            $attempts[] = [
                'url' => $url,
                'headers' => $headers,
            ];
        }
    }

    $seen = [];
    $deduped_attempts = [];
    foreach ($attempts as $attempt) {
        $signature = $attempt['url'] . '|' . md5(wp_json_encode($attempt['headers']));
        if (isset($seen[$signature])) {
            continue;
        }
        $seen[$signature] = true;
        $deduped_attempts[] = $attempt;
    }

    $last_response = null;
    foreach ($deduped_attempts as $attempt) {
        $request_args = $base_args;
        $request_args['headers'] = $attempt['headers'];

        if ($method === 'POST') {
            $response = wp_remote_post($attempt['url'], $request_args);
        } else {
            $response = wp_remote_get($attempt['url'], $request_args);
        }

        $last_response = $response;
        if (is_wp_error($response)) {
            continue;
        }

        $status_code = (int) wp_remote_retrieve_response_code($response);
        if ($status_code >= 200 && $status_code < 300) {
            return $response;
        }
    }

    if ($last_response instanceof WP_Error) {
        return $last_response;
    }

    if ($last_response !== null) {
        return $last_response;
    }

    return new WP_Error('goody_delivery_request_failed', __('Delivery API request failed.', 'goody'));
}

function goody_get_delivery_create_api_endpoint($provider) {
    $provider = sanitize_key((string) $provider);

    if ($provider === 'ubereats') {
        $explicit = goody_normalize_url_input(goody_get_option('ubereats_order_create_api_url', ''));
        if ($explicit !== '') {
            return $explicit;
        }

        $legacy = goody_normalize_url_input(goody_get_option('ubereats_api_url', ''));
        if ($legacy !== '') {
            return $legacy;
        }

        return goody_get_ubereats_default_create_order_url();
    }

    $map = [
        'glovo' => 'glovo_order_create_api_url',
        'ubereats' => 'ubereats_order_create_api_url',
        'deliveroo' => 'deliveroo_order_create_api_url',
        'custom' => 'custom_order_create_api_url',
    ];

    $option_key = $map[$provider] ?? 'custom_order_create_api_url';
    $endpoint = goody_normalize_url_input(goody_get_option($option_key, ''));
    if ($endpoint !== '') {
        return $endpoint;
    }

    // Backward-compatible fallback to existing provider API endpoint.
    return goody_normalize_url_input(goody_get_option($provider . '_api_url', ''));
}

function goody_get_woocommerce_order_items_payload($order) {
    if (! $order instanceof WC_Order) {
        return [];
    }

    $items = [];
    foreach ($order->get_items('line_item') as $item) {
        if (! $item instanceof WC_Order_Item_Product) {
            continue;
        }

        $product = $item->get_product();
        $items[] = [
            'product_id' => (int) $item->get_product_id(),
            'variation_id' => (int) $item->get_variation_id(),
            'sku' => $product instanceof WC_Product ? (string) $product->get_sku() : '',
            'name' => (string) $item->get_name(),
            'quantity' => (float) $item->get_quantity(),
            'line_total' => (float) $item->get_total(),
            'line_subtotal' => (float) $item->get_subtotal(),
        ];
    }

    return $items;
}

function goody_build_woocommerce_delivery_payload($order, $provider) {
    if (! $order instanceof WC_Order) {
        return [];
    }

    $shipping_address = trim(wp_strip_all_tags((string) $order->get_formatted_shipping_address()));
    if ($shipping_address === '') {
        $shipping_address = trim(wp_strip_all_tags((string) $order->get_formatted_billing_address()));
    }

    $shipping_name = trim((string) $order->get_formatted_shipping_full_name());
    if ($shipping_name === '') {
        $shipping_name = trim((string) $order->get_formatted_billing_full_name());
    }

    return [
        'provider' => sanitize_key((string) $provider),
        'order_id' => (string) $order->get_id(),
        'order_number' => (string) $order->get_order_number(),
        'order_key' => (string) $order->get_order_key(),
        'status' => (string) $order->get_status(),
        'currency' => (string) $order->get_currency(),
        'total' => (float) $order->get_total(),
        'subtotal' => (float) $order->get_subtotal(),
        'shipping_total' => (float) $order->get_shipping_total(),
        'discount_total' => (float) $order->get_discount_total(),
        'payment_method' => (string) $order->get_payment_method(),
        'payment_method_title' => (string) $order->get_payment_method_title(),
        'customer' => [
            'id' => (int) $order->get_customer_id(),
            'name' => $shipping_name,
            'phone' => (string) $order->get_billing_phone(),
            'email' => (string) $order->get_billing_email(),
        ],
        'shipping' => [
            'name' => $shipping_name,
            'phone' => (string) $order->get_billing_phone(),
            'address' => $shipping_address,
            'city' => (string) $order->get_shipping_city(),
            'postcode' => (string) $order->get_shipping_postcode(),
            'country' => (string) $order->get_shipping_country(),
        ],
        'billing' => [
            'name' => trim((string) $order->get_formatted_billing_full_name()),
            'phone' => (string) $order->get_billing_phone(),
            'email' => (string) $order->get_billing_email(),
            'city' => (string) $order->get_billing_city(),
            'postcode' => (string) $order->get_billing_postcode(),
            'country' => (string) $order->get_billing_country(),
        ],
        'items' => goody_get_woocommerce_order_items_payload($order),
        'meta' => [
            'site_url' => home_url('/'),
            'tracking_page' => goody_get_tracking_page_url((string) $order->get_id(), (string) $order->get_order_key()),
        ],
    ];
}

function goody_replace_delivery_url_placeholders($url, $order, $provider = '', $token = '') {
    $url = trim((string) $url);
    if ($url === '') {
        return '';
    }

    if (! $order instanceof WC_Order) {
        return goody_normalize_url_input($url);
    }

    $external_order_id = goody_get_tracking_external_order_id((string) $order->get_meta('_goody_external_order_id', true));
    $replace = [
        '{order_id}' => rawurlencode((string) $order->get_id()),
        '{order_number}' => rawurlencode((string) $order->get_order_number()),
        '{order_key}' => rawurlencode((string) $order->get_order_key()),
        '{external_order_id}' => rawurlencode($external_order_id),
        '{provider}' => rawurlencode(sanitize_key((string) $provider)),
        '{currency}' => rawurlencode((string) $order->get_currency()),
        '{amount}' => rawurlencode((string) $order->get_total()),
        '{token}' => rawurlencode((string) $token),
        '{tracking_url}' => rawurlencode(goody_get_tracking_page_url((string) $order->get_id(), (string) $order->get_order_key())),
    ];

    return goody_normalize_url_input(strtr($url, $replace));
}

function goody_get_delivery_mapping_profile($provider = '') {
    $profile = sanitize_key((string) goody_get_option('delivery_mapping_profile', 'auto'));
    $provider = sanitize_key((string) $provider);

    if ($profile === '' || ! in_array($profile, ['auto', 'ubereats', 'glovo', 'deliveroo', 'custom'], true)) {
        $profile = 'auto';
    }

    if ($profile === 'auto') {
        if ($provider !== '' && in_array($provider, ['ubereats', 'glovo', 'deliveroo', 'custom'], true)) {
            return $provider;
        }

        $auto_provider = sanitize_key((string) goody_get_option('delivery_auto_provider', 'custom'));
        if ($auto_provider !== '' && in_array($auto_provider, ['ubereats', 'glovo', 'deliveroo', 'custom'], true)) {
            return $auto_provider;
        }

        return 'custom';
    }

    return $profile;
}

function goody_get_delivery_mapping_presets() {
    return [
        'ubereats' => [
            'delivery_create_response_external_id_path' => ['data.order.id', 'data.id', 'order.id', 'delivery_id', 'id'],
            'delivery_create_response_tracking_url_path' => ['data.tracking.url', 'data.tracking_url', 'tracking.url', 'tracking_url'],
            'delivery_create_response_tracking_api_url_path' => ['data.tracking.api_url', 'data.tracking_api_url', 'tracking.api_url', 'tracking_api_url', 'status_url'],
            'delivery_tracking_response_url_path' => ['data.tracking.url', 'data.tracking_url', 'tracking.url', 'tracking_url'],
            'delivery_tracking_response_status_path' => ['data.status.current', 'data.status', 'status.current', 'status'],
            'delivery_tracking_response_stage_path' => ['data.status.stage', 'data.stage', 'status.stage', 'stage'],
            'delivery_tracking_response_eta_path' => ['data.status.eta', 'data.eta', 'status.eta', 'eta'],
            'delivery_tracking_response_timeline_path' => ['data.timeline.events', 'data.timeline', 'data.events', 'timeline', 'events'],
        ],
        'glovo' => [
            'delivery_create_response_external_id_path' => ['data.id', 'data.order_id', 'order.id', 'order_id', 'id'],
            'delivery_create_response_tracking_url_path' => ['data.tracking_url', 'data.tracking.url', 'tracking_url', 'tracking.url'],
            'delivery_create_response_tracking_api_url_path' => ['data.tracking_api_url', 'data.status_url', 'tracking_api_url', 'status_url'],
            'delivery_tracking_response_url_path' => ['data.tracking_url', 'tracking_url', 'data.url', 'url'],
            'delivery_tracking_response_status_path' => ['data.status', 'data.order_status', 'status', 'order_status'],
            'delivery_tracking_response_stage_path' => ['data.stage', 'data.delivery_stage', 'stage', 'delivery_stage'],
            'delivery_tracking_response_eta_path' => ['data.eta', 'data.estimated_delivery', 'eta', 'estimated_delivery'],
            'delivery_tracking_response_timeline_path' => ['data.events', 'data.timeline', 'events', 'timeline'],
        ],
        'deliveroo' => [
            'delivery_create_response_external_id_path' => ['data.order.id', 'data.id', 'order.id', 'order_id', 'id'],
            'delivery_create_response_tracking_url_path' => ['data.tracking.url', 'data.tracking_url', 'tracking.url', 'tracking_url'],
            'delivery_create_response_tracking_api_url_path' => ['data.tracking.api_url', 'data.status_api_url', 'tracking.api_url', 'tracking_api_url', 'status_api_url'],
            'delivery_tracking_response_url_path' => ['data.tracking.url', 'tracking.url', 'data.url', 'url'],
            'delivery_tracking_response_status_path' => ['data.status.current', 'data.status', 'status.current', 'status'],
            'delivery_tracking_response_stage_path' => ['data.status.stage', 'data.stage', 'status.stage', 'stage'],
            'delivery_tracking_response_eta_path' => ['data.status.eta', 'data.eta', 'status.eta', 'eta'],
            'delivery_tracking_response_timeline_path' => ['data.timeline.events', 'data.timeline', 'timeline', 'events'],
        ],
    ];
}

function goody_get_delivery_mapping_preset_paths($option_key, $provider = '') {
    $option_key = sanitize_key((string) $option_key);
    if ($option_key === '') {
        return [];
    }

    $presets = goody_get_delivery_mapping_presets();
    $profile = goody_get_delivery_mapping_profile($provider);
    $paths = [];

    if (isset($presets[$profile][$option_key]) && is_array($presets[$profile][$option_key])) {
        $paths = $presets[$profile][$option_key];
    }

    if (empty($paths) && isset($presets['ubereats'][$option_key]) && is_array($presets['ubereats'][$option_key])) {
        $paths = $presets['ubereats'][$option_key];
    }

    return array_values(array_unique(array_filter(array_map('sanitize_text_field', $paths))));
}

function goody_extract_external_order_id_from_payload($data, $provider = '') {
    $custom_external = goody_pick_payload_value_by_option_path($data, 'delivery_create_response_external_id_path', false, $provider);
    if (is_scalar($custom_external)) {
        $custom_external = sanitize_text_field((string) $custom_external);
        if ($custom_external !== '') {
            return $custom_external;
        }
    }

    $external = goody_reviews_pick_value($data, [
        'external_order_id',
        'consignment_id',
        'tracking_number',
        'tracking_id',
        'shipment_id',
        'order_id',
        'data.external_order_id',
        'data.consignment_id',
        'data.tracking_number',
        'data.tracking_id',
        'data.shipment_id',
        'data.order_id',
    ]);

    return sanitize_text_field((string) $external);
}

function goody_pick_payload_value_by_option_path($data, $option_key, $allow_array = false, $provider = '') {
    if (! is_array($data)) {
        return null;
    }

    $paths = [];
    $custom_path = sanitize_text_field((string) goody_get_option($option_key, ''));
    if ($custom_path !== '') {
        $paths[] = $custom_path;
    }

    $paths = array_merge($paths, goody_get_delivery_mapping_preset_paths($option_key, $provider));
    $paths = array_values(array_unique(array_filter($paths)));
    if (empty($paths)) {
        return null;
    }

    foreach ($paths as $path) {
        $value = goody_reviews_get_by_path($data, $path);
        if ($value === null) {
            continue;
        }

        if ($allow_array && is_array($value)) {
            return $value;
        }

        if (is_scalar($value)) {
            $value = trim((string) $value);
            if ($value !== '') {
                return $value;
            }
        }
    }

    return null;
}

function goody_sync_tracking_state_to_order_meta($order, $state, $external_order_id = '') {
    if (! $order instanceof WC_Order || ! is_array($state)) {
        return;
    }

    $external_order_id = sanitize_text_field((string) $external_order_id);
    if ($external_order_id !== '') {
        $order->update_meta_data('_goody_external_order_id', $external_order_id);
        if ((string) $order->get_meta('_goody_consignment_id', true) === '') {
            $order->update_meta_data('_goody_consignment_id', $external_order_id);
        }
    }

    $consignment_id = sanitize_text_field((string) ($state['consignment_id'] ?? ''));
    if ($consignment_id !== '') {
        $order->update_meta_data('_goody_consignment_id', $consignment_id);
    }

    $tracking_url = goody_normalize_url_input((string) ($state['url'] ?? ''));
    if ($tracking_url !== '') {
        $order->update_meta_data('_goody_tracking_url', $tracking_url);
        $order->update_meta_data('goody_tracking_url', $tracking_url);
        $order->update_meta_data('tracking_url', $tracking_url);
        $order->update_meta_data('_tracking_url', $tracking_url);
    }

    $status = sanitize_text_field((string) ($state['status'] ?? ''));
    if ($status !== '') {
        $order->update_meta_data('_goody_tracking_status', $status);
    }

    $stage = sanitize_text_field((string) ($state['stage'] ?? ''));
    if ($stage !== '') {
        $order->update_meta_data('_goody_tracking_stage', $stage);
    }

    $eta = sanitize_text_field((string) ($state['eta'] ?? ''));
    if ($eta !== '') {
        $order->update_meta_data('_goody_tracking_eta', $eta);
    }

    $provider = sanitize_text_field((string) ($state['provider'] ?? ''));
    if ($provider !== '') {
        $order->update_meta_data('_goody_delivery_provider', $provider);
        $order->update_meta_data('delivery_provider', $provider);
    }

    foreach ([
        'shipping_name' => '_goody_shipping_name',
        'shipping_phone' => '_goody_shipping_phone',
        'shipping_address' => '_goody_shipping_address',
        'payment_amount' => '_goody_payment_amount',
        'payment_currency' => '_goody_payment_currency',
        'payment_method' => '_goody_payment_method',
    ] as $state_key => $meta_key) {
        $value = sanitize_text_field((string) ($state[$state_key] ?? ''));
        if ($value !== '') {
            $order->update_meta_data($meta_key, $value);
        }
    }

    $timeline = $state['timeline'] ?? [];
    if (is_array($timeline) && ! empty($timeline)) {
        $order->update_meta_data('_goody_tracking_timeline', wp_json_encode($timeline));
    }

    $order->update_meta_data('_goody_tracking_synced_at', (string) time());
    $order->save();
}

function goody_get_delivery_response_error_message($status_code, $response_body = '', $response_data = [], $provider = '') {
    $status_code = absint($status_code);
    $provider = sanitize_key((string) $provider);
    $response_body = trim((string) $response_body);
    $response_data = is_array($response_data) ? $response_data : [];

    $default_message = $status_code > 0
        ? sprintf(__('Delivery API request failed with HTTP %d.', 'goody'), $status_code)
        : __('Delivery API request failed.', 'goody');

    $candidate = '';
    if (! empty($response_data)) {
        $value = goody_reviews_pick_value($response_data, [
            'message',
            'error_description',
            'error.message',
            'error.detail',
            'error',
            'data.message',
            'data.error.message',
            'data.error',
        ]);
        if (is_scalar($value)) {
            $candidate = sanitize_text_field((string) $value);
        }
    }

    if ($candidate === '' && $response_body !== '') {
        $candidate = sanitize_text_field(wp_html_excerpt($response_body, 220, '...'));
    }

    $message = $candidate !== '' ? $candidate : $default_message;

    if ($provider === 'ubereats' && in_array($status_code, [401, 403], true)) {
        $message .= ' ' . __('For UberEats, use an access token or set UberEats OAuth Client ID + Client Secret.', 'goody');
    }

    return trim($message);
}

function goody_validate_delivery_create_request($provider, $request_url, $token = '') {
    $provider = sanitize_key((string) $provider);
    $request_url = goody_normalize_url_input((string) $request_url);
    $token = goody_normalize_api_token((string) $token);

    if ($provider !== 'ubereats') {
        return '';
    }

    $environment = goody_get_ubereats_environment();
    $host = strtolower((string) wp_parse_url($request_url, PHP_URL_HOST));
    if ($environment === 'sandbox' && $host === 'api.uber.com') {
        return __('UberEats environment is Sandbox, but create URL is production domain (api.uber.com). Use test-api.uber.com or switch environment to Production.', 'goody');
    }
    if ($environment === 'production' && $host === 'test-api.uber.com') {
        return __('UberEats environment is Production, but create URL is sandbox domain (test-api.uber.com). Use api.uber.com or switch environment to Sandbox.', 'goody');
    }

    if ($token === '') {
        $client_id = sanitize_text_field((string) goody_get_option('ubereats_client_id', ''));
        $client_secret = sanitize_text_field((string) goody_get_option('ubereats_client_secret', ''));
        if ($client_id === '' || $client_secret === '') {
            return __('UberEats token missing. Provide UberEats API token or valid OAuth Client ID + Client Secret.', 'goody');
        }

        $oauth_error = goody_get_ubereats_oauth_last_error();
        if ($oauth_error !== '') {
            return sprintf(__('UberEats OAuth failed: %s', 'goody'), $oauth_error);
        }
    }

    return '';
}

function goody_create_external_delivery_order_from_woocommerce($order, $provider = '') {
    if (! $order instanceof WC_Order) {
        return false;
    }

    $provider = sanitize_key((string) $provider);
    if ($provider === '') {
        $provider = sanitize_key((string) goody_get_option('delivery_auto_provider', 'ubereats'));
    }
    if ($provider === '') {
        $provider = 'ubereats';
    }

    $endpoint = goody_get_delivery_create_api_endpoint($provider);
    if ($endpoint === '') {
        return false;
    }

    $token = goody_get_delivery_provider_token($provider);
    $tokens = goody_get_delivery_api_tokens($provider);
    $request_url = goody_replace_delivery_url_placeholders($endpoint, $order, $provider, $token);
    if ($request_url === '') {
        return false;
    }

    $config_error = goody_validate_delivery_create_request($provider, $request_url, $token);
    if ($config_error !== '') {
        $order->update_meta_data('_goody_delivery_create_error', sanitize_text_field($config_error));
        $order->update_meta_data('_goody_delivery_create_endpoint', $request_url);
        $order->update_meta_data('_goody_delivery_create_status_code', '0');
        $order->update_meta_data('_goody_delivery_create_synced', '0');
        $order->update_meta_data('_goody_delivery_create_attempt_at', (string) time());
        $order->save();
        return false;
    }

    $payload = goody_build_woocommerce_delivery_payload($order, $provider);
    $headers = [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ];
    $attempt_time = time();
    $response = goody_delivery_api_request('POST', $request_url, [
        'timeout' => 15,
        'headers' => $headers,
        'body' => wp_json_encode($payload),
    ], $tokens);

    if (is_wp_error($response)) {
        $order->update_meta_data('_goody_delivery_create_error', sanitize_text_field((string) $response->get_error_message()));
        $order->update_meta_data('_goody_delivery_create_endpoint', $request_url);
        $order->update_meta_data('_goody_delivery_create_status_code', '0');
        $order->update_meta_data('_goody_delivery_create_synced', '0');
        $order->update_meta_data('_goody_delivery_create_attempt_at', (string) $attempt_time);
        $order->save();
        return false;
    }

    $status_code = (int) wp_remote_retrieve_response_code($response);
    if ($status_code < 200 || $status_code >= 300) {
        $fallback_url = goody_normalize_url_input(add_query_arg([
            'order_id' => (string) $order->get_id(),
            'order_number' => (string) $order->get_order_number(),
            'order_key' => (string) $order->get_order_key(),
            'provider' => $provider,
        ], $request_url));
        if ($fallback_url !== '') {
            $fallback_response = goody_delivery_api_request('GET', $fallback_url, [
                'timeout' => 15,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ], $tokens);
            if (! is_wp_error($fallback_response)) {
                $fallback_code = (int) wp_remote_retrieve_response_code($fallback_response);
                if ($fallback_code >= 200 && $fallback_code < 300) {
                    $response = $fallback_response;
                    $status_code = $fallback_code;
                }
            }
        }
    }

    $body = (string) wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    $error_message = '';
    if ($status_code < 200 || $status_code >= 300) {
        $error_message = goody_get_delivery_response_error_message($status_code, $body, is_array($data) ? $data : [], $provider);
    }

    $state = [];
    if (is_array($data)) {
        $state = goody_parse_tracking_payload($data, $request_url, $provider);
    } else {
        $plain_url = goody_normalize_url_input($body);
        if ($plain_url !== '' && (strpos($plain_url, 'http://') === 0 || strpos($plain_url, 'https://') === 0)) {
            $state = goody_get_tracking_empty_state();
            $state['url'] = $plain_url;
        }
    }

    $external_order_id = is_array($data) ? goody_extract_external_order_id_from_payload($data, $provider) : '';
    if ($external_order_id === '') {
        $external_order_id = sanitize_text_field((string) ($state['consignment_id'] ?? ''));
    }

    $tracking_api_url = is_array($data) ? goody_normalize_url_input((string) (
        goody_pick_payload_value_by_option_path($data, 'delivery_create_response_tracking_api_url_path', false, $provider) ?: goody_reviews_pick_value($data, [
        'tracking_api_url',
        'status_api_url',
        'tracking_endpoint',
        'data.tracking_api_url',
        'data.status_api_url',
        'data.tracking_endpoint',
    ]))) : '';

    if ($provider === 'ubereats' && $tracking_api_url === '' && $external_order_id !== '') {
        $tracking_api_url = goody_get_ubereats_tracking_status_url($external_order_id);
    }

    if (is_array($data) && empty($state['url'])) {
        $custom_tracking_url = goody_pick_payload_value_by_option_path($data, 'delivery_create_response_tracking_url_path', false, $provider);
        if (is_scalar($custom_tracking_url)) {
            $custom_tracking_url = goody_normalize_url_input((string) $custom_tracking_url);
            if ($custom_tracking_url !== '') {
                $state['url'] = $custom_tracking_url;
            }
        }
    }

    $order->update_meta_data('_goody_delivery_provider', $provider);
    $order->update_meta_data('_goody_delivery_create_endpoint', $request_url);
    $order->update_meta_data('_goody_delivery_create_status_code', (string) $status_code);
    $order->update_meta_data('_goody_delivery_create_synced', ($status_code >= 200 && $status_code < 300) ? '1' : '0');
    $order->update_meta_data('_goody_delivery_create_error', $error_message);
    $order->update_meta_data('_goody_delivery_create_attempt_at', (string) $attempt_time);
    $order->update_meta_data('_goody_delivery_create_at', (string) time());
    if ($tracking_api_url !== '') {
        $order->update_meta_data('_goody_tracking_api_url', $tracking_api_url);
    }

    if (is_array($data)) {
        $order->update_meta_data('_goody_delivery_create_payload', wp_json_encode($data));
    } else {
        $order->update_meta_data('_goody_delivery_create_payload', sanitize_text_field($body));
    }
    $order->save();

    if (! empty($state)) {
        if (empty($state['provider'])) {
            $state['provider'] = $provider;
        }
        goody_sync_tracking_state_to_order_meta($order, $state, $external_order_id);
    } elseif ($external_order_id !== '') {
        $order->update_meta_data('_goody_external_order_id', $external_order_id);
        $order->save();
    }

    return $status_code >= 200 && $status_code < 300;
}

function goody_maybe_auto_create_delivery_order_for_woocommerce($order_id) {
    if (! goody_is_woocommerce_available() || ! function_exists('wc_get_order')) {
        return;
    }

    if (goody_get_option('delivery_auto_create_enabled', '0') !== '1') {
        return;
    }

    $order = wc_get_order(absint($order_id));
    if (! $order instanceof WC_Order) {
        return;
    }

    $already_synced = (string) $order->get_meta('_goody_delivery_create_synced', true) === '1';
    $has_external_order_id = (string) $order->get_meta('_goody_external_order_id', true) !== '';
    $last_attempt = absint($order->get_meta('_goody_delivery_create_attempt_at', true));
    if ($last_attempt > 0 && (time() - $last_attempt) < 30) {
        return;
    }
    if ($already_synced && $has_external_order_id) {
        return;
    }

    $provider = goody_sanitize_delivery_provider((string) $order->get_meta('_goody_delivery_provider', true));
    if ($provider === '') {
        $provider = sanitize_key((string) goody_get_option('delivery_auto_provider', 'ubereats'));
    }
    goody_create_external_delivery_order_from_woocommerce($order, $provider);
}
add_action('woocommerce_order_status_processing', 'goody_maybe_auto_create_delivery_order_for_woocommerce', 20);
add_action('woocommerce_order_status_completed', 'goody_maybe_auto_create_delivery_order_for_woocommerce', 20);

function goody_sync_woocommerce_order_tracking_from_api($order, $force = false) {
    if (! $order instanceof WC_Order) {
        return [];
    }

    $last_synced = absint($order->get_meta('_goody_tracking_synced_at', true));
    if (! $force && $last_synced > 0 && (time() - $last_synced) < 20) {
        return [];
    }

    $provider = sanitize_key((string) ($order->get_meta('_goody_delivery_provider', true) ?: goody_get_option('delivery_auto_provider', '')));
    $token = goody_get_delivery_provider_token($provider);
    $tokens = goody_get_delivery_api_tokens($provider);
    $tracking_api_url = goody_normalize_url_input((string) $order->get_meta('_goody_tracking_api_url', true));
    if ($tracking_api_url === '' && $provider === 'ubereats') {
        $external_id = sanitize_text_field((string) $order->get_meta('_goody_external_order_id', true));
        if ($external_id !== '') {
            $tracking_api_url = goody_get_ubereats_tracking_status_url('{external_order_id}');
        }
    }
    if ($tracking_api_url === '') {
        $tracking_api_url = trim((string) goody_get_option('tracking_url', ''));
    }
    if ($tracking_api_url === '') {
        return [];
    }

    $resolved_url = goody_replace_delivery_url_placeholders($tracking_api_url, $order, $provider, $token);
    $resolved_url = goody_apply_tracking_placeholders($resolved_url, (string) $order->get_id(), (string) $order->get_order_key(), (string) $order->get_meta('_goody_external_order_id', true));
    if ($resolved_url === '') {
        return [];
    }

    $response = goody_delivery_api_request('GET', $resolved_url, [
        'timeout' => 10,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ], $tokens);

    if (is_wp_error($response)) {
        return [];
    }

    $status_code = (int) wp_remote_retrieve_response_code($response);
    if ($status_code < 200 || $status_code >= 300) {
        return [];
    }

    $body = (string) wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (! is_array($data)) {
        return [];
    }

    $state = goody_parse_tracking_payload($data, $resolved_url, $provider);
    if (empty($state)) {
        return [];
    }

    if (empty($state['provider'])) {
        $state['provider'] = $provider;
    }

    $external_order_id = goody_extract_external_order_id_from_payload($data, $provider);
    if ($external_order_id === '') {
        $external_order_id = sanitize_text_field((string) $order->get_meta('_goody_external_order_id', true));
    }
    goody_sync_tracking_state_to_order_meta($order, $state, $external_order_id);

    return $state;
}

function goody_find_woocommerce_order_for_delivery_payload($payload) {
    if (! goody_is_woocommerce_available() || ! function_exists('wc_get_order') || ! function_exists('wc_get_orders') || ! is_array($payload)) {
        return null;
    }

    $order_id_candidates = [
        goody_reviews_pick_value($payload, ['order_id', 'id', 'data.order_id', 'data.id']),
    ];

    foreach ($order_id_candidates as $candidate) {
        if (! is_scalar($candidate)) {
            continue;
        }
        $order_id = absint($candidate);
        if ($order_id < 1) {
            continue;
        }

        $order = wc_get_order($order_id);
        if ($order instanceof WC_Order) {
            return $order;
        }
    }

    $order_key = goody_reviews_pick_value($payload, ['order_key', 'key', 'wc_order_key', 'data.order_key', 'data.key']);
    if (is_scalar($order_key) && function_exists('wc_get_order_id_by_order_key')) {
        $order_key = sanitize_text_field((string) $order_key);
        if ($order_key !== '') {
            $order_id = absint(wc_get_order_id_by_order_key($order_key));
            if ($order_id > 0) {
                $order = wc_get_order($order_id);
                if ($order instanceof WC_Order) {
                    return $order;
                }
            }
        }
    }

    $provider = goody_reviews_pick_value($payload, ['provider', 'source', 'data.provider', 'data.source']);
    if (! is_scalar($provider)) {
        $provider = '';
    }
    $provider = sanitize_key((string) $provider);

    $external_order_id = goody_extract_external_order_id_from_payload($payload, $provider);
    if ($external_order_id === '') {
        return null;
    }

    $external_order_id = sanitize_text_field($external_order_id);
    foreach ([
        '_goody_external_order_id',
        '_goody_consignment_id',
        '_goody_tracking_id',
        '_goody_shipment_id',
        'consignment_id',
        '_consignment_id',
        'tracking_number',
        '_tracking_number',
    ] as $meta_key) {
        $orders = wc_get_orders([
            'limit' => 1,
            'meta_key' => $meta_key,
            'meta_value' => $external_order_id,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        if (! empty($orders[0]) && $orders[0] instanceof WC_Order) {
            return $orders[0];
        }
    }

    return null;
}

function goody_can_access_woocommerce_order_tracking($order, $order_key = '') {
    if (! $order instanceof WC_Order) {
        return false;
    }

    if (current_user_can('manage_woocommerce')) {
        return true;
    }

    $order_key = sanitize_text_field((string) $order_key);
    $matches_key = $order_key !== '' && hash_equals((string) $order->get_order_key(), $order_key);
    $current_user_id = get_current_user_id();
    $order_user_id = (int) $order->get_user_id();

    if ($current_user_id > 0 && $order_user_id > 0 && $current_user_id === $order_user_id) {
        return true;
    }

    // Reservation-linked orders are intended to be trackable from the public
    // status page with Order ID only.
    $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
    if ($reservation_id > 0) {
        return true;
    }

    return $matches_key;
}

function goody_get_woocommerce_tracking_url_from_order($order) {
    if (! $order instanceof WC_Order) {
        return '';
    }

    $meta_keys = [
        'goody_tracking_url',
        '_goody_tracking_url',
        'tracking_url',
        '_tracking_url',
        'shipment_tracking_url',
        '_shipment_tracking_url',
    ];

    foreach ($meta_keys as $meta_key) {
        $candidate = goody_normalize_url_input($order->get_meta($meta_key, true));
        if ($candidate !== '') {
            return $candidate;
        }
    }

    $shipment_items = $order->get_meta('_wc_shipment_tracking_items', true);
    if (is_array($shipment_items)) {
        foreach ($shipment_items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $candidate = goody_normalize_url_input((string) ($item['tracking_link'] ?? $item['tracking_url'] ?? ''));
            if ($candidate !== '') {
                return $candidate;
            }
        }
    }

    if (function_exists('wc_get_endpoint_url') && function_exists('wc_get_page_permalink')) {
        $my_account_url = wc_get_page_permalink('myaccount');
        if (is_string($my_account_url) && $my_account_url !== '') {
            return goody_normalize_url_input(wc_get_endpoint_url('view-order', (string) $order->get_id(), $my_account_url));
        }
    }

    return '';
}

function goody_get_woocommerce_tracking_state($order_id_override = '', $order_key_override = '') {
    $state = goody_get_tracking_empty_state();

    if (! goody_is_woocommerce_available() || ! function_exists('wc_get_order')) {
        return $state;
    }

    $order_id = goody_get_woocommerce_order_id_from_request($order_id_override);
    if ($order_id < 1) {
        return $state;
    }

    $order = wc_get_order($order_id);
    if (! $order instanceof WC_Order) {
        return $state;
    }

    $manual_tracking_mode = (string) $order->get_meta('_goody_tracking_manual_updates', true) === '1';
    $state['manual_mode'] = $manual_tracking_mode;
    $state['source'] = 'woocommerce';
    $state['order_type'] = sanitize_key((string) $order->get_meta('_goody_reservation_order_type', true));
    if ($state['order_type'] === '') {
        $reservation_id = absint($order->get_meta('_goody_reservation_id', true));
        if ($reservation_id > 0) {
            $state['order_type'] = sanitize_key((string) get_post_meta($reservation_id, 'goody_reservation_order_type', true));
        }
    }

    $order_key = goody_get_tracking_order_key($order_key_override);
    if (! goody_can_access_woocommerce_order_tracking($order, $order_key)) {
        $state['order_id'] = (string) $order_id;
        $state['order_key'] = $order_key;
        $state['message'] = __('Order key required to view tracking for this order.', 'goody');
        return $state;
    }

    $state['order_id'] = (string) $order_id;
    $state['order_key'] = $order_key !== '' ? $order_key : (string) $order->get_order_key();
    $state['consignment_id'] = sanitize_text_field((string) (
        $order->get_meta('_goody_consignment_id', true) ?:
        $order->get_meta('_goody_external_order_id', true) ?:
        $order->get_meta('consignment_id', true) ?:
        $order->get_meta('_consignment_id', true) ?:
        $order->get_meta('tracking_number', true) ?:
        $order->get_order_number()
    ));
    $state['provider'] = sanitize_text_field((string) $order->get_meta('delivery_provider', true));
    if ($state['provider'] === '') {
        $state['provider'] = sanitize_text_field((string) $order->get_meta('_delivery_provider', true));
    }
    if ($state['provider'] === '') {
        $state['provider'] = sanitize_text_field((string) $order->get_meta('_goody_delivery_provider', true));
    }
    if ($state['provider'] === '') {
        $state['provider'] = sanitize_text_field((string) $order->get_meta('tracking_provider', true));
    }
    if ($state['provider'] === '') {
        $state['provider'] = sanitize_text_field((string) $order->get_meta('_tracking_provider', true));
    }
    if ($state['provider'] === '') {
        $state['provider'] = 'Goody';
    }

    $shipping_name = trim((string) $order->get_formatted_shipping_full_name());
    if ($shipping_name === '') {
        $shipping_name = trim((string) $order->get_formatted_billing_full_name());
    }
    $state['shipping_name'] = sanitize_text_field((string) ($order->get_meta('_goody_shipping_name', true) ?: $shipping_name));
    $shipping_phone_value = sanitize_text_field((string) $order->get_meta('_goody_shipping_phone', true));
    if ($shipping_phone_value === '' && method_exists($order, 'get_shipping_phone')) {
        $shipping_phone_value = sanitize_text_field((string) $order->get_shipping_phone());
    }
    if ($shipping_phone_value === '') {
        $shipping_phone_value = sanitize_text_field((string) $order->get_billing_phone());
    }
    if ($shipping_phone_value === '') {
        $shipping_phone_value = sanitize_text_field((string) $order->get_meta('shipping_phone', true));
    }
    $state['shipping_phone'] = $shipping_phone_value;

    $shipping_address = trim(wp_strip_all_tags((string) $order->get_formatted_shipping_address()));
    if ($shipping_address === '') {
        $shipping_address = trim(wp_strip_all_tags((string) $order->get_formatted_billing_address()));
    }
    $state['shipping_address'] = sanitize_text_field((string) ($order->get_meta('_goody_shipping_address', true) ?: $shipping_address));

    $state['payment_currency'] = sanitize_text_field((string) ($order->get_meta('_goody_payment_currency', true) ?: $order->get_currency()));
    $state['payment_method'] = sanitize_text_field((string) ($order->get_meta('_goody_payment_method', true) ?: $order->get_payment_method_title()));
    $state['payment_amount'] = sanitize_text_field((string) $order->get_meta('_goody_payment_amount', true));
    if ($state['payment_amount'] === '') {
        $state['payment_amount'] = wp_strip_all_tags(wc_price((float) $order->get_total(), [
            'currency' => $order->get_currency(),
        ]));
    }

    $status_label = function_exists('wc_get_order_status_name')
        ? (string) wc_get_order_status_name($order->get_status())
        : ucfirst((string) $order->get_status());
    $state['status'] = sanitize_text_field((string) ($order->get_meta('_goody_tracking_status', true) ?: $status_label));
    $state['stage'] = goody_normalize_tracking_stage((string) ($order->get_meta('_goody_tracking_stage', true) ?: $order->get_status()));
    if ($state['stage'] === '') {
        $state['stage'] = goody_detect_tracking_stage_from_text($status_label);
    }

    $state['eta'] = sanitize_text_field((string) $order->get_meta('_goody_tracking_eta', true));
    $state['note'] = sanitize_text_field((string) $order->get_meta('_goody_tracking_note', true));
    if ($state['eta'] === '') {
        foreach (['goody_delivery_eta', '_goody_delivery_eta', 'delivery_eta', '_delivery_eta', 'estimated_delivery', '_estimated_delivery', 'eta', '_eta'] as $meta_key) {
            $eta = sanitize_text_field((string) $order->get_meta($meta_key, true));
            if ($eta !== '') {
                $state['eta'] = $eta;
                break;
            }
        }
    }

    $stored_timeline = json_decode((string) $order->get_meta('_goody_tracking_timeline', true), true);
    if (is_array($stored_timeline) && ! empty($stored_timeline)) {
        $state['timeline'] = goody_normalize_tracking_timeline($stored_timeline);
    }

    if (goody_get_option('delivery_auto_create_enabled', '0') === '1') {
        $has_external_order_id = (string) $order->get_meta('_goody_external_order_id', true) !== '';
        $already_synced = (string) $order->get_meta('_goody_delivery_create_synced', true) === '1';
        $last_attempt = absint($order->get_meta('_goody_delivery_create_attempt_at', true));
        if ((! $has_external_order_id || ! $already_synced) && ($last_attempt === 0 || (time() - $last_attempt) >= 30)) {
            $provider = sanitize_key((string) ($order->get_meta('_goody_delivery_provider', true) ?: goody_get_option('delivery_auto_provider', 'ubereats')));
            goody_create_external_delivery_order_from_woocommerce($order, $provider);

            if (function_exists('wc_get_order')) {
                $reloaded = wc_get_order((int) $order->get_id());
                if ($reloaded instanceof WC_Order) {
                    $order = $reloaded;
                }
            }
        }
    }

    // Pull fresh provider-side tracking data when available.
    $synced_state = $manual_tracking_mode ? [] : goody_sync_woocommerce_order_tracking_from_api($order, false);
    if (is_array($synced_state) && ! empty($synced_state)) {
        foreach ([
            'consignment_id',
            'shipping_name',
            'shipping_phone',
            'shipping_address',
            'payment_amount',
            'payment_currency',
            'payment_method',
            'status',
            'stage',
            'eta',
            'provider',
        ] as $key) {
            $incoming = sanitize_text_field((string) ($synced_state[$key] ?? ''));
            if ($incoming !== '') {
                $state[$key] = $incoming;
            }
        }

        if (is_array($synced_state['timeline'] ?? null) && ! empty($synced_state['timeline'])) {
            $state['timeline'] = $synced_state['timeline'];
        }
    }

    $state['url'] = goody_get_woocommerce_tracking_url_from_order($order);
    if ($state['url'] === '') {
        $state['url'] = goody_normalize_url_input((string) $order->get_meta('_goody_tracking_api_url', true));
    }

    $timeline = [];
    $stages = goody_get_tracking_stage_definitions();
    $stage_index = goody_get_tracking_stage_index($state['stage']);
    $created_time = goody_format_tracking_datetime($order->get_date_created());
    $paid_time = goody_format_tracking_datetime($order->get_date_paid());
    $updated_time = goody_format_tracking_datetime($order->get_date_modified());
    $completed_time = goody_format_tracking_datetime($order->get_date_completed());

    if ($stage_index >= 0) {
        $timeline[] = [
            'stage' => 'requested',
            'title' => (string) ($stages['requested'] ?? __('Requested', 'goody')),
            'description' => __('Order request has been received.', 'goody'),
            'time' => $paid_time !== '' ? $paid_time : ($created_time !== '' ? $created_time : $updated_time),
            'completed' => true,
        ];
    }

    if ($stage_index >= 1) {
        $timeline[] = [
            'stage' => 'confirmed',
            'title' => (string) ($stages['confirmed'] ?? __('Confirmed', 'goody')),
            'description' => __('Order has been confirmed.', 'goody'),
            'time' => $updated_time,
            'completed' => true,
        ];
    }

    if ($stage_index >= 2) {
        $timeline[] = [
            'stage' => 'preparing',
            'title' => (string) ($stages['preparing'] ?? __('Preparing', 'goody')),
            'description' => __('Order is being prepared.', 'goody'),
            'time' => $updated_time,
            'completed' => true,
        ];
    }

    if ($stage_index >= 3) {
        $timeline[] = [
            'stage' => 'ready',
            'title' => (string) ($stages['ready'] ?? __('Ready', 'goody')),
            'description' => __('Order is ready.', 'goody'),
            'time' => $updated_time,
            'completed' => true,
        ];
    }

    if ($state['order_type'] === 'delivery' && $stage_index >= 4) {
        $timeline[] = [
            'stage' => 'with_delivery_provider',
            'title' => (string) ($stages['with_delivery_provider'] ?? __('Delivery Provider', 'goody')),
            'description' => __('Order handed over to delivery provider.', 'goody'),
            'time' => $updated_time,
            'completed' => true,
        ];
    }

    $completed_threshold = $state['order_type'] === 'delivery' ? 5 : 4;
    if ($stage_index >= $completed_threshold) {
        $timeline[] = [
            'stage' => 'completed',
            'title' => (string) ($stages['completed'] ?? __('Completed', 'goody')),
            'description' => __('Order has been completed.', 'goody'),
            'time' => $completed_time !== '' ? $completed_time : $updated_time,
            'completed' => true,
        ];
    }

    if (empty($state['timeline'])) {
        $state['timeline'] = $timeline;
    }

    $parts = [];
    if ($state['status'] !== '') {
        $parts[] = sprintf(__('Order status: %s', 'goody'), $state['status']);
    }
    if ($state['eta'] !== '') {
        $parts[] = sprintf(__('ETA: %s', 'goody'), $state['eta']);
    }
    if ($state['provider'] !== '') {
        $parts[] = sprintf(__('Provider: %s', 'goody'), $state['provider']);
    }
    $state['message'] = implode(' | ', $parts);

    return $state;
}

function goody_render_woocommerce_thankyou_track_link($order_id) {
    if (! goody_is_woocommerce_available() || goody_get_option('tracking_enabled', '0') !== '1') {
        return;
    }

    $order = function_exists('wc_get_order') ? wc_get_order(absint($order_id)) : null;
    if (! $order instanceof WC_Order) {
        return;
    }

    $track_url = goody_get_tracking_page_url((string) $order->get_id(), (string) $order->get_order_key());
    ?>
    <p class="goody-woo-track-link">
        <a class="button" href="<?php echo esc_url($track_url); ?>"><?php esc_html_e('Track order on our site', 'goody'); ?></a>
    </p>
    <?php
}
add_action('woocommerce_thankyou', 'goody_render_woocommerce_thankyou_track_link', 25);

function goody_parse_tracking_payload($data, $fallback_url = '', $provider_hint = '') {
    $state = goody_get_tracking_empty_state();
    $state['url'] = goody_normalize_url_input($fallback_url);
    $state['order_id'] = goody_get_tracking_order_id();
    $state['order_key'] = goody_get_tracking_order_key();
    $provider_hint = sanitize_key((string) $provider_hint);

    if (! is_array($data)) {
        return $state;
    }

    $pick = static function ($paths) use ($data) {
        foreach ($paths as $path) {
            $value = goody_reviews_get_by_path($data, $path);
            if (is_scalar($value)) {
                $value = trim((string) $value);
                if ($value !== '') {
                    return $value;
                }
            }
        }

        return '';
    };

    $url = '';
    $url_candidates = [
        'tracking_url',
        'track_url',
        'order_url',
        'url',
        'link',
        'data.tracking_url',
        'data.track_url',
        'data.order_url',
        'data.url',
        'data.link',
    ];
    foreach ($url_candidates as $path) {
        $candidate = goody_normalize_url_input($pick([$path]));
        if ($candidate !== '') {
            $url = $candidate;
            break;
        }
    }
    if ($url !== '') {
        $state['url'] = $url;
    }

    $custom_tracking_url = goody_pick_payload_value_by_option_path($data, 'delivery_tracking_response_url_path', false, $provider_hint);
    if (is_scalar($custom_tracking_url)) {
        $custom_tracking_url = goody_normalize_url_input((string) $custom_tracking_url);
        if ($custom_tracking_url !== '') {
            $state['url'] = $custom_tracking_url;
        }
    }

    $status = $pick([
        'tracking_status',
        'order_status',
        'status',
        'state',
        'data.tracking_status',
        'data.order_status',
        'data.status',
        'data.state',
    ]);

    $eta = $pick([
        'eta',
        'eta_text',
        'estimated_time',
        'estimated_delivery',
        'data.eta',
        'data.eta_text',
        'data.estimated_time',
        'data.estimated_delivery',
    ]);

    $provider = $pick([
        'provider',
        'source',
        'data.provider',
        'data.source',
    ]);

    $order_id = $pick([
        'order_id',
        'consignment_id',
        'tracking_number',
        'tracking_id',
        'reference',
        'shipment_id',
        'data.order_id',
        'data.consignment_id',
        'data.tracking_number',
        'data.tracking_id',
        'data.reference',
        'data.shipment_id',
    ]);

    $consignment_id = $pick([
        'consignment_id',
        'tracking_number',
        'tracking_id',
        'shipment_id',
        'awb',
        'data.consignment_id',
        'data.tracking_number',
        'data.tracking_id',
        'data.shipment_id',
        'data.awb',
    ]);

    $shipping_name = $pick([
        'shipping_name',
        'customer_name',
        'receiver_name',
        'recipient_name',
        'shipping.name',
        'customer.name',
        'recipient.name',
        'data.shipping_name',
        'data.customer_name',
        'data.receiver_name',
        'data.recipient_name',
        'data.shipping.name',
        'data.customer.name',
        'data.recipient.name',
    ]);

    $shipping_phone = $pick([
        'shipping_phone',
        'customer_phone',
        'receiver_phone',
        'recipient_phone',
        'shipping.phone',
        'customer.phone',
        'recipient.phone',
        'data.shipping_phone',
        'data.customer_phone',
        'data.receiver_phone',
        'data.recipient_phone',
        'data.shipping.phone',
        'data.customer.phone',
        'data.recipient.phone',
    ]);

    $shipping_address = $pick([
        'shipping_address',
        'delivery_address',
        'address',
        'shipping.address',
        'delivery.address',
        'recipient.address',
        'data.shipping_address',
        'data.delivery_address',
        'data.address',
        'data.shipping.address',
        'data.delivery.address',
        'data.recipient.address',
    ]);

    $payment_amount = $pick([
        'payment_amount',
        'amount',
        'total_amount',
        'payment.total',
        'payment.amount',
        'data.payment_amount',
        'data.amount',
        'data.total_amount',
        'data.payment.total',
        'data.payment.amount',
    ]);

    $payment_currency = $pick([
        'payment_currency',
        'currency',
        'payment.currency',
        'data.payment_currency',
        'data.currency',
        'data.payment.currency',
    ]);

    $payment_method = $pick([
        'payment_method',
        'payment_type',
        'payment.method',
        'data.payment_method',
        'data.payment_type',
        'data.payment.method',
    ]);

    $stage = $pick([
        'stage',
        'tracking_stage',
        'delivery_stage',
        'data.stage',
        'data.tracking_stage',
        'data.delivery_stage',
    ]);

    $custom_status = goody_pick_payload_value_by_option_path($data, 'delivery_tracking_response_status_path', false, $provider_hint);
    if (is_scalar($custom_status)) {
        $status = (string) $custom_status;
    }

    $custom_eta = goody_pick_payload_value_by_option_path($data, 'delivery_tracking_response_eta_path', false, $provider_hint);
    if (is_scalar($custom_eta)) {
        $eta = (string) $custom_eta;
    }

    $custom_stage = goody_pick_payload_value_by_option_path($data, 'delivery_tracking_response_stage_path', false, $provider_hint);
    if (is_scalar($custom_stage)) {
        $stage = (string) $custom_stage;
    }

    $state['status'] = sanitize_text_field($status);
    $state['eta'] = sanitize_text_field($eta);
    $state['provider'] = sanitize_text_field($provider);
    if ($state['provider'] === '' && $provider_hint !== '') {
        $state['provider'] = sanitize_text_field($provider_hint);
    }
    if ($order_id !== '') {
        $state['order_id'] = sanitize_text_field($order_id);
    }
    if ($consignment_id !== '') {
        $state['consignment_id'] = sanitize_text_field($consignment_id);
    } elseif ($state['order_id'] !== '') {
        $state['consignment_id'] = sanitize_text_field($state['order_id']);
    }
    $state['shipping_name'] = sanitize_text_field($shipping_name);
    $state['shipping_phone'] = sanitize_text_field($shipping_phone);
    $state['shipping_address'] = sanitize_text_field($shipping_address);
    $state['payment_currency'] = sanitize_text_field($payment_currency);
    $state['payment_method'] = sanitize_text_field($payment_method);

    $payment_amount_value = sanitize_text_field($payment_amount);
    if ($payment_amount_value !== '' && is_numeric($payment_amount_value) && $state['payment_currency'] !== '') {
        $payment_amount_value = $state['payment_currency'] . ' ' . $payment_amount_value;
    }
    $state['payment_amount'] = $payment_amount_value;

    $state['stage'] = goody_normalize_tracking_stage($stage);
    if ($state['stage'] === '') {
        $state['stage'] = goody_detect_tracking_stage_from_text($state['status']);
    }

    $timeline_data = null;
    $custom_timeline = goody_pick_payload_value_by_option_path($data, 'delivery_tracking_response_timeline_path', true, $provider_hint);
    if (is_array($custom_timeline) && ! empty($custom_timeline)) {
        $timeline_data = $custom_timeline;
    }

    if ($timeline_data === null) {
        foreach ([
            'timeline',
            'events',
            'history',
            'tracking_events',
            'data.timeline',
            'data.events',
            'data.history',
            'data.tracking_events',
        ] as $timeline_path) {
            $candidate = goody_reviews_get_by_path($data, $timeline_path);
            if (is_array($candidate) && ! empty($candidate)) {
                $timeline_data = $candidate;
                break;
            }
        }
    }

    $state['timeline'] = goody_normalize_tracking_timeline($timeline_data);
    if (empty($state['timeline']) && $state['status'] !== '') {
        $state['timeline'] = [[
            'stage' => $state['stage'],
            'title' => $state['status'],
            'description' => '',
            'time' => '',
            'completed' => true,
        ]];
    }

    if ($state['stage'] === '' && ! empty($state['timeline'])) {
        $last_event = end($state['timeline']);
        if (is_array($last_event)) {
            $state['stage'] = goody_normalize_tracking_stage($last_event['stage'] ?? '');
            if ($state['stage'] === '') {
                $state['stage'] = goody_detect_tracking_stage_from_text((string) ($last_event['title'] ?? ''));
            }
        }
        reset($state['timeline']);
    }

    $parts = [];
    if ($state['status'] !== '') {
        $parts[] = sprintf(__('Live status: %s', 'goody'), $state['status']);
    }
    if ($state['eta'] !== '') {
        $parts[] = sprintf(__('ETA: %s', 'goody'), $state['eta']);
    }
    $state['message'] = implode(' | ', $parts);

    return $state;
}

function goody_get_tracking_state($force_refresh = false, $order_id_override = '', $order_key_override = '') {
    $woo_state = goody_get_woocommerce_tracking_state($order_id_override, $order_key_override);
    if (! empty($woo_state['manual_mode']) && ($woo_state['order_id'] !== '' || $woo_state['message'] !== '')) {
        return $woo_state;
    }

    $tracking_source_raw = trim((string) goody_get_option('tracking_url', ''));
    if ($tracking_source_raw === '') {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            return $woo_state;
        }

        return goody_get_tracking_empty_state();
    }

    $tracking_source = $tracking_source_raw;
    $tracking_source_fallback_url = goody_normalize_url_input($tracking_source_raw);
    $resolved_order_id = goody_get_tracking_order_id($order_id_override);
    $resolved_order_key = goody_get_tracking_order_key($order_key_override);
    $resolved_external_order_id = goody_get_tracking_external_order_id();
    $resolved_source = goody_apply_tracking_placeholders($tracking_source, $resolved_order_id, $resolved_order_key, $resolved_external_order_id);
    if ($resolved_source === '') {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            return $woo_state;
        }

        $state = goody_get_tracking_empty_state();
        $state['url'] = $tracking_source_fallback_url !== '' ? $tracking_source_fallback_url : $tracking_source;
        $state['order_id'] = $resolved_order_id;
        $state['order_key'] = $resolved_order_key;
        $state['consignment_id'] = $resolved_external_order_id;
        if (strpos($tracking_source, '{order_id}') !== false && $resolved_order_id === '') {
            $state['message'] = __('Order ID required for live tracking.', 'goody');
        } elseif (strpos($tracking_source, '{order_key}') !== false && $resolved_order_key === '') {
            $state['message'] = __('Order key required for live tracking.', 'goody');
        } elseif (strpos($tracking_source, '{external_order_id}') !== false && $resolved_external_order_id === '') {
            $state['message'] = __('External delivery order ID required for live tracking.', 'goody');
        }
        return $state;
    }

    if (! goody_is_tracking_api_source($resolved_source)) {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            if ($woo_state['url'] === '') {
                $woo_state['url'] = $resolved_source;
            }
            return $woo_state;
        }

        $state = goody_get_tracking_empty_state();
        $state['url'] = $resolved_source;
        $state['order_id'] = $resolved_order_id;
        $state['order_key'] = $resolved_order_key;
        return $state;
    }

    $has_tracking_identity = ($resolved_order_id !== '' || $resolved_order_key !== '' || $resolved_external_order_id !== '');
    if (! $has_tracking_identity && ! $force_refresh) {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            if ($woo_state['url'] === '') {
                $woo_state['url'] = $resolved_source;
            }
            return $woo_state;
        }

        $state = goody_get_tracking_empty_state();
        $state['url'] = $resolved_source;
        return $state;
    }

    $cache_key = 'goody_tracking_state_' . md5($resolved_source . '|' . $resolved_order_id . '|' . $resolved_order_key . '|' . $resolved_external_order_id);
    $cached = get_transient($cache_key);
    if (! $force_refresh && is_array($cached)) {
        return wp_parse_args($cached, goody_get_tracking_empty_state());
    }

    $tokens = goody_get_delivery_api_tokens();
    $response = goody_delivery_api_request('GET', $resolved_source, [
        'timeout' => 8,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ], $tokens);
    $code = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);

    if (is_wp_error($response) || $code < 200 || $code >= 300) {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            if ($woo_state['url'] === '') {
                $woo_state['url'] = $resolved_source;
            }
            return $woo_state;
        }

        $state = goody_get_tracking_empty_state();
        $state['url'] = $resolved_source;
        $state['order_id'] = $resolved_order_id;
        $state['order_key'] = $resolved_order_key;
        return $state;
    }

    $body = (string) wp_remote_retrieve_body($response);
    $plain_url = goody_normalize_url_input($body);
    if ($plain_url !== '' && (strpos($plain_url, 'http://') === 0 || strpos($plain_url, 'https://') === 0)) {
        $state = goody_get_tracking_empty_state();
        $state['url'] = $plain_url;
        $state['order_id'] = $resolved_order_id;
        $state['order_key'] = $resolved_order_key;
        set_transient($cache_key, $state, 20);
        return $state;
    }

    $data = json_decode($body, true);
    if (! is_array($data)) {
        if ($woo_state['order_id'] !== '' || $woo_state['message'] !== '') {
            if ($woo_state['url'] === '') {
                $woo_state['url'] = $resolved_source;
            }
            return $woo_state;
        }

        $state = goody_get_tracking_empty_state();
        $state['url'] = $resolved_source;
        $state['order_id'] = $resolved_order_id;
        $state['order_key'] = $resolved_order_key;
        return $state;
    }

    $state = goody_parse_tracking_payload($data, $resolved_source, sanitize_key((string) goody_get_option('delivery_auto_provider', '')));
    if ($state['order_id'] === '') {
        $state['order_id'] = $resolved_order_id;
    }
    if ($state['order_key'] === '') {
        $state['order_key'] = $resolved_order_key;
    }
    set_transient($cache_key, $state, 20);

    return $state;
}

function goody_get_hero_video_embed_url($url) {
    $url = esc_url_raw($url);
    if (! $url) {
        return '';
    }

    $host = wp_parse_url($url, PHP_URL_HOST);
    if (! $host) {
        return '';
    }

    if (strpos($host, 'youtube.com') !== false || strpos($host, 'youtu.be') !== false) {
        $video_id = '';
        $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');

        if (strpos($host, 'youtu.be') !== false) {
            $parts = $path !== '' ? explode('/', $path) : [];
            $video_id = sanitize_text_field((string) ($parts[0] ?? ''));
        } else {
            parse_str((string) wp_parse_url($url, PHP_URL_QUERY), $query);
            $video_id = sanitize_text_field((string) ($query['v'] ?? ''));

            if ($video_id === '' && $path !== '') {
                $parts = explode('/', $path);
                $patterns = [
                    'embed' => 1,
                    'shorts' => 1,
                    'live' => 1,
                    'v' => 1,
                ];

                foreach ($parts as $index => $part) {
                    $part = strtolower(trim((string) $part));
                    if ($part === '' || ! isset($patterns[$part])) {
                        continue;
                    }

                    $video_id = sanitize_text_field((string) ($parts[$index + 1] ?? ''));
                    if ($video_id !== '') {
                        break;
                    }
                }
            }
        }

        $video_id = trim((string) preg_replace('/[^A-Za-z0-9_-]/', '', (string) $video_id));
        if ($video_id !== '' && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $video_id)) {
            return 'https://www.youtube-nocookie.com/embed/' . rawurlencode($video_id) . '?autoplay=1&mute=1&loop=1&controls=0&rel=0&modestbranding=1&playsinline=1&iv_load_policy=3&disablekb=1&fs=0&playlist=' . rawurlencode($video_id);
        }
    }

    if (strpos($host, 'vimeo.com') !== false) {
        $path = trim((string) wp_parse_url($url, PHP_URL_PATH), '/');
        if ($path) {
            return 'https://player.vimeo.com/video/' . rawurlencode($path) . '?autoplay=1&muted=1&loop=1&background=1';
        }
    }

    return '';
}

function goody_extract_google_maps_api_key($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    if (preg_match('/src=[\'"]([^\'"]+)[\'"]/i', $raw, $matches) && ! empty($matches[1])) {
        $raw = html_entity_decode(trim((string) $matches[1]), ENT_QUOTES, 'UTF-8');
    }

    if (preg_match('/(AIza[0-9A-Za-z_-]{20,})/', $raw, $matches)) {
        return sanitize_text_field($matches[1]);
    }

    $parsed = wp_parse_url($raw);
    if (is_array($parsed) && ! empty($parsed['query'])) {
        parse_str((string) $parsed['query'], $query);
        if (! empty($query['key'])) {
            $candidate = trim((string) $query['key']);
            if (preg_match('/(AIza[0-9A-Za-z_-]{20,})/', $candidate, $matches)) {
                return sanitize_text_field($matches[1]);
            }

            if ((strpos($candidate, 'http://') === 0 || strpos($candidate, 'https://') === 0 || strpos($candidate, '%3A%2F%2F') !== false) && $candidate !== $raw) {
                $nested = goody_extract_google_maps_api_key(urldecode($candidate));
                if ($nested !== '') {
                    return $nested;
                }
            }

            return '';
        }
    }

    if (preg_match('/[?&]key=([^&]+)/', $raw, $matches)) {
        $candidate = urldecode((string) $matches[1]);
        if (preg_match('/(AIza[0-9A-Za-z_-]{20,})/', $candidate, $direct)) {
            return sanitize_text_field($direct[1]);
        }

        if ((strpos($candidate, 'http://') === 0 || strpos($candidate, 'https://') === 0 || strpos($candidate, '%3A%2F%2F') !== false) && $candidate !== $raw) {
            $nested = goody_extract_google_maps_api_key($candidate);
            if ($nested !== '') {
                return $nested;
            }
        }

        return '';
    }

    if (preg_match('/^AIza[0-9A-Za-z_-]{20,}$/', $raw)) {
        return sanitize_text_field($raw);
    }

    return '';
}

function goody_is_embed_code($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return false;
    }

    if (strpos($raw, '<') !== false || strpos($raw, '[') !== false || strpos($raw, ']') !== false) {
        return true;
    }

    $needle = strtolower($raw);
    return strpos($needle, 'iframe') !== false || strpos($needle, 'script') !== false || strpos($needle, 'shortcode') !== false;
}

function goody_extract_google_place_id($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    if (preg_match('/(ChIJ[0-9A-Za-z_-]{10,})/', $raw, $matches)) {
        return sanitize_text_field($matches[1]);
    }

    if (preg_match('/[?&](?:query_place_id|place_id|placeid)=([^&]+)/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field(urldecode((string) $matches[1]));
    }

    if (preg_match('/place_id:([A-Za-z0-9_-]+)/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field((string) $matches[1]);
    }

    return '';
}

function goody_hex_to_decimal_string($hex) {
    $hex = strtolower(trim((string) $hex));
    $hex = preg_replace('/[^0-9a-f]/', '', $hex);
    $hex = ltrim((string) $hex, '0');

    if ($hex === '') {
        return '0';
    }

    $dec = '0';
    $len = strlen($hex);
    for ($i = 0; $i < $len; $i++) {
        $carry = hexdec($hex[$i]);

        for ($j = strlen($dec) - 1; $j >= 0; $j--) {
            $num = ((int) $dec[$j] * 16) + $carry;
            $dec[$j] = (string) ($num % 10);
            $carry = intdiv($num, 10);
        }

        while ($carry > 0) {
            $dec = (string) ($carry % 10) . $dec;
            $carry = intdiv($carry, 10);
        }
    }

    return $dec;
}

function goody_extract_google_cid($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    if (preg_match('/[?&]cid=([0-9]{6,})/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field((string) $matches[1]);
    }

    if (preg_match('/\\bcid\\s*[:=]\\s*([0-9]{6,})/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field((string) $matches[1]);
    }

    if (preg_match('/0x[0-9a-f]+:0x([0-9a-f]+)/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field(goody_hex_to_decimal_string((string) $matches[1]));
    }

    if (preg_match('/^([0-9]{10,})$/', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field((string) $matches[1]);
    }

    return '';
}

function goody_extract_serpapi_data_id($value) {
    $raw = trim((string) $value);
    if ($raw === '') {
        return '';
    }

    if (preg_match('/[?&]data_id=([^&]+)/i', $raw, $matches) && ! empty($matches[1])) {
        $candidate = urldecode((string) $matches[1]);
        if (preg_match('/(0x[0-9a-f]+:0x[0-9a-f]+)/i', $candidate, $data_id)) {
            return sanitize_text_field((string) $data_id[1]);
        }
    }

    if (preg_match('/(0x[0-9a-f]+:0x[0-9a-f]+)/i', $raw, $matches) && ! empty($matches[1])) {
        return sanitize_text_field((string) $matches[1]);
    }

    return '';
}

function goody_get_reviews_provider($api_key, $place_input = '') {
    $provider = sanitize_key((string) goody_get_option('reviews_api_provider', 'auto'));
    $allowed = ['auto', 'google', 'serpapi', 'trustpilot', 'custom'];
    if (! in_array($provider, $allowed, true)) {
        $provider = 'auto';
    }

    if ($provider !== 'auto') {
        return $provider;
    }

    $place_input = trim((string) $place_input);
    $has_place_input = $place_input !== '';
    $has_data_id = goody_extract_serpapi_data_id($place_input) !== '';
    $has_place_id = goody_extract_google_place_id($place_input) !== '';
    $has_cid = goody_extract_google_cid($place_input) !== '';

    if ($has_data_id) {
        return 'serpapi';
    }

    if ($has_place_id || $has_cid) {
        if (goody_is_google_api_key((string) $api_key)) {
            return 'google';
        }
        if (trim((string) $api_key) !== '') {
            return 'serpapi';
        }

        return 'google';
    }

    $trustpilot_url = goody_normalize_url_input(goody_get_option('trustpilot_api_url', ''));
    if ($trustpilot_url !== '' && ! $has_place_input) {
        return 'trustpilot';
    }

    $custom_url = goody_normalize_url_input(goody_get_option('custom_reviews_api_url', ''));
    if ($custom_url !== '' && ! $has_place_input) {
        return 'custom';
    }

    if (goody_is_google_api_key((string) $api_key)) {
        return 'google';
    }

    if (trim((string) $api_key) !== '') {
        return 'serpapi';
    }

    if ($trustpilot_url !== '') {
        return 'trustpilot';
    }

    if ($custom_url !== '') {
        return 'custom';
    }

    return 'google';
}

function goody_is_google_api_key($value) {
    return (bool) preg_match('/^AIza[0-9A-Za-z_-]{20,}$/', (string) $value);
}

function goody_looks_like_reviews_api_key($value) {
    $value = trim((string) $value);
    if ($value === '') {
        return false;
    }

    if (goody_is_google_api_key($value)) {
        return true;
    }

    if ((bool) preg_match('/^[a-f0-9]{32,128}$/i', $value)) {
        return true;
    }

    if ((bool) preg_match('/^(sk|pk)_[A-Za-z0-9._-]{16,}$/', $value)) {
        return true;
    }

    return false;
}

function goody_get_effective_reviews_api_key($provider = 'auto') {
    $provider = sanitize_key((string) $provider);
    if ($provider === '') {
        $provider = 'auto';
    }

    $shared_key = goody_normalize_api_token(goody_get_option('integrations_reviews_api_key', ''));
    $google_key = goody_extract_google_maps_api_key(goody_get_option('integrations_google_reviews_api_key', ''));
    $serpapi_key = goody_normalize_api_token(goody_get_option('integrations_serpapi_api_key', ''));
    $maps_key = goody_extract_google_maps_api_key(goody_get_option('integrations_maps_api_key', ''));

    if ($provider === 'google') {
        if (goody_is_google_api_key($google_key)) {
            return $google_key;
        }
        if (goody_is_google_api_key($shared_key)) {
            return $shared_key;
        }
        if (goody_is_google_api_key($maps_key)) {
            return $maps_key;
        }
        return '';
    }

    if ($provider === 'serpapi') {
        if ($serpapi_key !== '') {
            return $serpapi_key;
        }
        if ($shared_key !== '' && ! goody_is_google_api_key($shared_key)) {
            return $shared_key;
        }
        return '';
    }

    if ($provider === 'trustpilot' || $provider === 'custom') {
        return $shared_key;
    }

    if ($shared_key !== '') {
        return $shared_key;
    }
    if ($google_key !== '') {
        return $google_key;
    }
    if ($serpapi_key !== '') {
        return $serpapi_key;
    }
    if ($maps_key !== '') {
        return $maps_key;
    }

    return '';
}

function goody_reviews_get_by_path($data, $path) {
    if (! is_array($data) || ! is_string($path) || $path === '') {
        return null;
    }

    $segments = explode('.', $path);
    $current = $data;
    foreach ($segments as $segment) {
        if (is_array($current) && array_key_exists($segment, $current)) {
            $current = $current[$segment];
            continue;
        }

        if (is_array($current) && ctype_digit($segment)) {
            $index = (int) $segment;
            if (array_key_exists($index, $current)) {
                $current = $current[$index];
                continue;
            }
        }

        return null;
    }

    return $current;
}

function goody_reviews_pick_value($data, $paths) {
    if (! is_array($paths)) {
        return null;
    }

    foreach ($paths as $path) {
        $value = goody_reviews_get_by_path($data, (string) $path);
        if ($value === null) {
            continue;
        }

        if (is_string($value) && trim($value) === '') {
            continue;
        }

        if (is_numeric($value) && (float) $value <= 0) {
            continue;
        }

        return $value;
    }

    return null;
}

function goody_parse_external_reviews_response($data, $provider = 'custom') {
    if (! is_array($data) || ! empty($data['error'])) {
        return [];
    }

    $name = goody_reviews_pick_value($data, [
        'name',
        'title',
        'business.name',
        'business.title',
        'company.name',
        'place_info.title',
        'businessUnit.displayName',
        'business_unit.display_name',
    ]);

    $rating = goody_reviews_pick_value($data, [
        'rating',
        'score',
        'average_rating',
        'averageScore',
        'stars',
        'place_info.rating',
        'businessUnit.score.trustScore',
        'business_unit.score.trust_score',
    ]);

    $total = goody_reviews_pick_value($data, [
        'user_ratings_total',
        'review_count',
        'reviews_count',
        'total_reviews',
        'totalReviews',
        'numberOfReviews.total',
        'place_info.reviews',
        'businessUnit.numberOfReviews.total',
        'business_unit.number_of_reviews.total',
    ]);

    $url = goody_reviews_pick_value($data, [
        'url',
        'link',
        'review_url',
        'business.url',
        'businessUnit.websiteUrl',
        'business_unit.website_url',
    ]);

    $review_sets = [
        goody_reviews_get_by_path($data, 'reviews'),
        goody_reviews_get_by_path($data, 'results'),
        goody_reviews_get_by_path($data, 'items'),
        goody_reviews_get_by_path($data, 'data.reviews'),
        goody_reviews_get_by_path($data, 'data.items'),
        goody_reviews_get_by_path($data, 'data.results'),
    ];

    $reviews_raw = [];
    foreach ($review_sets as $set) {
        if (! is_array($set) || empty($set)) {
            continue;
        }

        $sample = $set[0] ?? null;
        if (is_array($sample)) {
            $reviews_raw = $set;
            break;
        }
    }

    $reviews = [];
    foreach ($reviews_raw as $review) {
        if (! is_array($review)) {
            continue;
        }

        $author = goody_reviews_pick_value($review, [
            'author_name',
            'author',
            'user.name',
            'user.display_name',
            'consumer.displayName',
            'consumer.display_name',
            'name',
        ]);
        $author = is_scalar($author) ? (string) $author : '';

        $text = goody_reviews_pick_value($review, [
            'text',
            'content',
            'snippet',
            'review',
            'comment',
            'message',
            'extracted_snippet.original',
        ]);
        $text = is_scalar($text) ? trim((string) $text) : '';

        $title = goody_reviews_pick_value($review, ['title', 'headline']);
        $title = is_scalar($title) ? trim((string) $title) : '';

        if ($text === '' && $title !== '') {
            $text = $title;
        } elseif ($text !== '' && $title !== '' && stripos($text, $title) === false) {
            $text = $title . ' - ' . $text;
        }

        if ($text === '') {
            continue;
        }

        $item_rating = goody_reviews_pick_value($review, [
            'rating',
            'score',
            'stars',
            'star_rating',
        ]);

        $time_text = goody_reviews_pick_value($review, [
            'time_text',
            'relative_time_description',
            'date',
            'created_at',
            'createdAt',
            'published_at',
            'publishedAt',
        ]);

        $profile_photo = goody_reviews_pick_value($review, [
            'profile_photo_url',
            'avatar',
            'avatar_url',
            'user.thumbnail',
            'consumer.imageUrl',
            'consumer.image_url',
        ]);

        $author_url = goody_reviews_pick_value($review, [
            'author_url',
            'url',
            'link',
            'user.link',
            'consumer.profileUrl',
            'consumer.profile_url',
        ]);

        $reviews[] = [
            'author_name' => $author,
            'rating' => (int) round((float) (is_scalar($item_rating) ? $item_rating : 5)),
            'text' => $text,
            'time_text' => is_scalar($time_text) ? (string) $time_text : '',
            'profile_photo_url' => is_scalar($profile_photo) ? (string) $profile_photo : '',
            'author_url' => is_scalar($author_url) ? (string) $author_url : '',
        ];
    }

    $source_label = 'Reviews';
    if ($provider === 'trustpilot') {
        $source_label = 'Trustpilot';
    } elseif ($provider === 'serpapi' || $provider === 'google') {
        $source_label = 'Google';
    } elseif ($provider === 'custom') {
        $source_label = 'API';
    }

    return goody_parse_google_reviews_payload([
        'name' => is_scalar($name) ? (string) $name : '',
        'rating' => is_scalar($rating) ? (float) $rating : 0,
        'user_ratings_total' => is_scalar($total) ? (int) $total : 0,
        'url' => is_scalar($url) ? (string) $url : '',
        'reviews' => $reviews,
        'source_label' => $source_label,
    ]);
}

function goody_get_external_reviews_data($endpoint, $token = '', $count = 6, $provider = 'custom') {
    $endpoint = goody_normalize_url_input($endpoint);
    $token = goody_normalize_api_token($token);

    if ($endpoint === '') {
        return [];
    }

    $args = [
        'timeout' => 12,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ];

    if ($token !== '') {
        $args['headers']['Authorization'] = 'Bearer ' . $token;
    }

    $response = wp_remote_get($endpoint, $args);
    $status = is_wp_error($response) ? 0 : (int) wp_remote_retrieve_response_code($response);

    if (($status < 200 || $status >= 300) && $token !== '') {
        $retry_args = [
            'timeout' => 12,
            'headers' => [
                'Accept' => 'application/json',
                'x-api-key' => $token,
            ],
        ];

        $retry_urls = [];
        foreach (['api_key', 'apikey', 'token', 'access_token', 'key'] as $param) {
            $candidate = goody_normalize_url_input(add_query_arg($param, $token, $endpoint));
            if ($candidate !== '' && ! in_array($candidate, $retry_urls, true)) {
                $retry_urls[] = $candidate;
            }
        }

        foreach ($retry_urls as $retry_url) {
            $retry = wp_remote_get($retry_url, $retry_args);
            if (is_wp_error($retry)) {
                continue;
            }

            $retry_status = (int) wp_remote_retrieve_response_code($retry);
            if ($retry_status >= 200 && $retry_status < 300) {
                $response = $retry;
                $status = $retry_status;
                break;
            }
        }
    }

    if (is_wp_error($response) || $status < 200 || $status >= 300) {
        return [];
    }

    $data = json_decode((string) wp_remote_retrieve_body($response), true);
    if (! is_array($data)) {
        return [];
    }

    $payload = goody_parse_external_reviews_response($data, $provider);
    if (empty($payload)) {
        return [];
    }

    if (! empty($payload['reviews']) && count($payload['reviews']) > $count) {
        $payload['reviews'] = array_slice($payload['reviews'], 0, $count);
    }

    return $payload;
}

function goody_resolve_google_place_id($api_key, $queries = []) {
    $api_key = goody_normalize_api_token($api_key);
    if ($api_key === '') {
        return '';
    }

    $candidates = [];
    foreach ($queries as $query) {
        $query = trim((string) $query);
        if ($query !== '' && ! in_array($query, $candidates, true)) {
            $candidates[] = $query;
        }
    }

    if (empty($candidates)) {
        return '';
    }

    foreach ($candidates as $query) {
        $find_place_endpoint = add_query_arg([
            'input' => $query,
            'inputtype' => 'textquery',
            'fields' => 'place_id',
            'key' => $api_key,
        ], 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json');

        $find_place_response = wp_remote_get($find_place_endpoint, [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        if (! is_wp_error($find_place_response) && (int) wp_remote_retrieve_response_code($find_place_response) >= 200 && (int) wp_remote_retrieve_response_code($find_place_response) < 300) {
            $find_place_data = json_decode((string) wp_remote_retrieve_body($find_place_response), true);
            $resolved = goody_extract_google_place_id($find_place_data['candidates'][0]['place_id'] ?? '');
            if ($resolved !== '') {
                return $resolved;
            }
        }
    }

    foreach ($candidates as $query) {
        $search_payload = wp_json_encode([
            'textQuery' => $query,
            'maxResultCount' => 1,
        ]);
        if (! is_string($search_payload) || $search_payload === '') {
            continue;
        }

        $search_response = wp_remote_post('https://places.googleapis.com/v1/places:searchText', [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'X-Goog-Api-Key' => $api_key,
                'X-Goog-FieldMask' => 'places.id,places.name',
            ],
            'body' => $search_payload,
        ]);

        if (is_wp_error($search_response) || (int) wp_remote_retrieve_response_code($search_response) < 200 || (int) wp_remote_retrieve_response_code($search_response) >= 300) {
            continue;
        }

        $search_data = json_decode((string) wp_remote_retrieve_body($search_response), true);
        $place = $search_data['places'][0] ?? [];
        if (! is_array($place)) {
            continue;
        }

        $resolved = goody_extract_google_place_id($place['id'] ?? '');
        if ($resolved !== '') {
            return $resolved;
        }

        if (! empty($place['name']) && is_string($place['name']) && preg_match('#^places/([A-Za-z0-9_-]+)$#', $place['name'], $matches)) {
            return sanitize_text_field((string) $matches[1]);
        }
    }

    return '';
}

function goody_parse_google_reviews_payload($payload) {
    if (! is_array($payload)) {
        return [];
    }

    $reviews = [];
    foreach (($payload['reviews'] ?? []) as $review) {
        if (! is_array($review)) {
            continue;
        }

        $text = sanitize_textarea_field((string) ($review['text'] ?? ''));
        if ($text === '') {
            continue;
        }

        $reviews[] = [
            'author_name' => sanitize_text_field((string) ($review['author_name'] ?? __('Google User', 'goody'))),
            'rating' => max(1, min(5, (int) ($review['rating'] ?? 5))),
            'text' => $text,
            'time_text' => sanitize_text_field((string) ($review['time_text'] ?? '')),
            'profile_photo_url' => esc_url_raw((string) ($review['profile_photo_url'] ?? '')),
            'author_url' => esc_url_raw((string) ($review['author_url'] ?? '')),
        ];
    }

    return [
        'name' => sanitize_text_field((string) ($payload['name'] ?? '')),
        'rating' => (float) ($payload['rating'] ?? 0),
        'user_ratings_total' => absint($payload['user_ratings_total'] ?? 0),
        'url' => esc_url_raw((string) ($payload['url'] ?? '')),
        'source_label' => sanitize_text_field((string) ($payload['source_label'] ?? 'Google')),
        'reviews' => $reviews,
    ];
}

function goody_parse_google_reviews_legacy_response($data) {
    if (! is_array($data) || ($data['status'] ?? '') !== 'OK' || empty($data['result']) || ! is_array($data['result'])) {
        return [];
    }

    $result = $data['result'];
    $reviews = [];
    foreach (($result['reviews'] ?? []) as $review) {
        if (! is_array($review)) {
            continue;
        }

        $reviews[] = [
            'author_name' => $review['author_name'] ?? '',
            'rating' => $review['rating'] ?? 0,
            'text' => $review['text'] ?? '',
            'time_text' => $review['relative_time_description'] ?? '',
            'profile_photo_url' => $review['profile_photo_url'] ?? '',
            'author_url' => $review['author_url'] ?? '',
        ];
    }

    return goody_parse_google_reviews_payload([
        'name' => $result['name'] ?? '',
        'rating' => $result['rating'] ?? 0,
        'user_ratings_total' => $result['user_ratings_total'] ?? 0,
        'url' => $result['url'] ?? '',
        'source_label' => 'Google',
        'reviews' => $reviews,
    ]);
}

function goody_parse_google_reviews_new_response($data) {
    if (! is_array($data)) {
        return [];
    }

    $reviews = [];
    foreach (($data['reviews'] ?? []) as $review) {
        if (! is_array($review)) {
            continue;
        }

        $reviews[] = [
            'author_name' => $review['authorAttribution']['displayName'] ?? '',
            'rating' => $review['rating'] ?? 0,
            'text' => $review['text']['text'] ?? '',
            'time_text' => $review['relativePublishTimeDescription'] ?? '',
            'profile_photo_url' => $review['authorAttribution']['photoUri'] ?? '',
            'author_url' => $review['authorAttribution']['uri'] ?? '',
        ];
    }

    return goody_parse_google_reviews_payload([
        'name' => $data['displayName']['text'] ?? '',
        'rating' => $data['rating'] ?? 0,
        'user_ratings_total' => $data['userRatingCount'] ?? 0,
        'url' => $data['googleMapsUri'] ?? '',
        'source_label' => 'Google',
        'reviews' => $reviews,
    ]);
}

function goody_parse_serpapi_reviews_response($data) {
    if (! is_array($data) || ! empty($data['error'])) {
        return [];
    }

    $place_info = $data['place_info'] ?? [];
    if (! is_array($place_info)) {
        $place_info = [];
    }

    $maps_url = '';
    if (! empty($data['search_metadata']['google_maps_reviews_url'])) {
        $maps_url = (string) $data['search_metadata']['google_maps_reviews_url'];
    }

    $reviews = [];
    foreach (($data['reviews'] ?? []) as $review) {
        if (! is_array($review)) {
            continue;
        }

        $snippet = '';
        if (! empty($review['extracted_snippet']['original'])) {
            $snippet = (string) $review['extracted_snippet']['original'];
        } elseif (! empty($review['snippet'])) {
            $snippet = (string) $review['snippet'];
        }

        $snippet = trim($snippet);
        if ($snippet === '') {
            continue;
        }

        $reviews[] = [
            'author_name' => $review['user']['name'] ?? __('Google User', 'goody'),
            'rating' => (int) round((float) ($review['rating'] ?? 0)),
            'text' => $snippet,
            'time_text' => $review['date'] ?? '',
            'profile_photo_url' => $review['user']['thumbnail'] ?? '',
            'author_url' => $review['user']['link'] ?? '',
        ];
    }

    return goody_parse_google_reviews_payload([
        'name' => $place_info['title'] ?? '',
        'rating' => $place_info['rating'] ?? 0,
        'user_ratings_total' => $place_info['reviews'] ?? 0,
        'url' => $maps_url,
        'source_label' => 'Google',
        'reviews' => $reviews,
    ]);
}

function goody_trim_reviews_payload($payload, $count = 6) {
    if (! is_array($payload) || empty($payload)) {
        return [];
    }

    $count = max(1, min(10, (int) $count));
    if (! empty($payload['reviews']) && is_array($payload['reviews']) && count($payload['reviews']) > $count) {
        $payload['reviews'] = array_slice($payload['reviews'], 0, $count);
    }

    return $payload;
}

function goody_get_selected_review_rating_filter() {
    $default = absint(goody_get_option('reviews_default_rating_filter', '0'));
    return ($default >= 1 && $default <= 5) ? $default : 0;
}

function goody_filter_reviews_by_rating($reviews, $rating) {
    if (! is_array($reviews) || $rating < 1 || $rating > 5) {
        return is_array($reviews) ? $reviews : [];
    }

    return array_values(array_filter($reviews, static function ($review) use ($rating) {
        if (! is_array($review)) {
            return false;
        }

        return (int) ($review['rating'] ?? 0) === (int) $rating;
    }));
}

function goody_is_trusted_review_handoff_url($url) {
    $url = goody_normalize_url_input($url);
    if ($url === '') {
        return false;
    }

    $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
    if ($host === '') {
        return false;
    }

    return (bool) (
        preg_match('/(^|\.)google\.[a-z.]+$/', $host) ||
        preg_match('/(^|\.)goo\.gl$/', $host) ||
        $host === 'g.page'
    );
}

function goody_get_google_review_handoff_url($reviews_payload = []) {
    $configured_url = goody_normalize_url_input((string) goody_get_option('google_review_submit_url', ''));
    if ($configured_url !== '' && goody_is_trusted_review_handoff_url($configured_url)) {
        return $configured_url;
    }

    $place_input = (string) goody_get_option('google_reviews_place_id', '');
    $place_id = goody_extract_google_place_id($place_input);
    if ($place_id !== '') {
        return 'https://search.google.com/local/writereview?placeid=' . rawurlencode($place_id);
    }

    $reviews_url = '';
    if (is_array($reviews_payload) && ! empty($reviews_payload['url'])) {
        $reviews_url = goody_normalize_url_input((string) $reviews_payload['url']);
    }

    return goody_is_trusted_review_handoff_url($reviews_url) ? $reviews_url : '';
}

function goody_get_serpapi_reviews_data($api_key, $place_input, $count = 6) {
    $api_key = goody_normalize_api_token($api_key);
    if ($api_key === '') {
        return [];
    }

    $place_input = trim((string) $place_input);
    if (
        $place_input !== '' &&
        goody_extract_serpapi_data_id($place_input) === '' &&
        goody_extract_google_place_id($place_input) === '' &&
        goody_extract_google_cid($place_input) === '' &&
        goody_looks_like_reviews_api_key($place_input)
    ) {
        $place_input = '';
    }

    $params = [
        'engine' => 'google_maps_reviews',
        'api_key' => $api_key,
        'hl' => 'en',
        'sort_by' => 'newestFirst',
    ];

    $place_id = goody_extract_google_place_id($place_input);
    $data_id = goody_extract_serpapi_data_id($place_input);

    if ($place_id !== '') {
        $params['place_id'] = $place_id;
    } elseif ($data_id !== '') {
        $params['data_id'] = $data_id;
    } else {
        $queries = [];
        if ($place_input !== '') {
            $queries[] = $place_input;
        }

        $name = trim((string) goody_get_option('restaurant_name', ''));
        $address = trim((string) goody_get_option('contact_address', ''));
        if ($name !== '' && $address !== '') {
            $queries[] = trim($name . ' ' . $address);
        }
        if ($name !== '') {
            $queries[] = $name;
        }

        $queries = array_values(array_unique(array_filter(array_map('trim', $queries))));
        foreach ($queries as $query) {
            $search_endpoint = add_query_arg([
                'engine' => 'google_maps',
                'api_key' => $api_key,
                'type' => 'search',
                'hl' => 'en',
                'q' => $query,
            ], 'https://serpapi.com/search.json');

            $search_response = wp_remote_get($search_endpoint, [
                'timeout' => 12,
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);

            if (is_wp_error($search_response) || (int) wp_remote_retrieve_response_code($search_response) < 200 || (int) wp_remote_retrieve_response_code($search_response) >= 300) {
                continue;
            }

            $search_data = json_decode((string) wp_remote_retrieve_body($search_response), true);
            if (! is_array($search_data)) {
                continue;
            }

            $candidate_data_id = goody_extract_serpapi_data_id($search_data['place_results']['data_id'] ?? '');
            if ($candidate_data_id === '') {
                $candidate_data_id = goody_extract_serpapi_data_id($search_data['local_results'][0]['data_id'] ?? '');
            }

            $candidate_place_id = goody_extract_google_place_id($search_data['place_results']['place_id'] ?? '');
            if ($candidate_place_id === '') {
                $candidate_place_id = goody_extract_google_place_id($search_data['local_results'][0]['place_id'] ?? '');
            }

            if ($candidate_place_id !== '') {
                $params['place_id'] = $candidate_place_id;
                break;
            }

            if ($candidate_data_id !== '') {
                $params['data_id'] = $candidate_data_id;
                break;
            }
        }
    }

    if (empty($params['place_id']) && empty($params['data_id'])) {
        return [];
    }

    $endpoint = add_query_arg($params, 'https://serpapi.com/search.json');
    $response = wp_remote_get($endpoint, [
        'timeout' => 12,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ]);

    if (is_wp_error($response) || (int) wp_remote_retrieve_response_code($response) < 200 || (int) wp_remote_retrieve_response_code($response) >= 300) {
        return [];
    }

    $data = json_decode((string) wp_remote_retrieve_body($response), true);
    $payload = goody_parse_serpapi_reviews_response($data);
    if (empty($payload)) {
        return [];
    }

    if (! empty($payload['reviews']) && count($payload['reviews']) > $count) {
        $payload['reviews'] = array_slice($payload['reviews'], 0, $count);
    }

    return $payload;
}

function goody_get_mock_google_reviews_data($count = 6) {
    $count = max(1, min(10, (int) $count));

    $pool = [
        [
            'author_name' => 'Sarah M.',
            'rating' => 5,
            'text' => 'Excellent brunch quality and very friendly team. Food presentation was beautiful and service was fast.',
            'time_text' => '2 days ago',
        ],
        [
            'author_name' => 'Nafis R.',
            'rating' => 4,
            'text' => 'Great ambiance and cozy interior. Coffee and desserts were outstanding.',
            'time_text' => '1 week ago',
        ],
        [
            'author_name' => 'Lina K.',
            'rating' => 5,
            'text' => 'Loved the menu variety and healthy options. Highly recommended for family brunch.',
            'time_text' => '2 weeks ago',
        ],
        [
            'author_name' => 'Arif H.',
            'rating' => 4,
            'text' => 'Good service and clean environment. Reservation experience was smooth.',
            'time_text' => '3 weeks ago',
        ],
        [
            'author_name' => 'Maya T.',
            'rating' => 5,
            'text' => 'Fresh ingredients and balanced flavors. Will visit again.',
            'time_text' => '1 month ago',
        ],
        [
            'author_name' => 'Jules P.',
            'rating' => 4,
            'text' => 'Nice atmosphere, friendly staff, and consistent food quality.',
            'time_text' => '1 month ago',
        ],
        [
            'author_name' => 'Rafi S.',
            'rating' => 5,
            'text' => 'One of the best brunch experiences in town. Perfect for group visits.',
            'time_text' => '2 months ago',
        ],
    ];

    $items = array_slice($pool, 0, $count);
    $sum = 0;
    foreach ($items as $item) {
        $sum += (int) $item['rating'];
    }
    $avg = $count > 0 ? round($sum / $count, 1) : 0;

    return [
        'name' => goody_get_option('restaurant_name', get_bloginfo('name')),
        'rating' => $avg,
        'user_ratings_total' => 120 + $count,
        'url' => '',
        'is_mock' => true,
        'source_label' => 'Google',
        'reviews' => array_map(static function ($item) {
            return [
                'author_name' => $item['author_name'],
                'rating' => max(1, min(5, (int) $item['rating'])),
                'text' => $item['text'],
                'time_text' => $item['time_text'],
                'profile_photo_url' => '',
                'author_url' => '',
            ];
        }, $items),
    ];
}

function goody_build_google_place_queries($place_input, $cid = '') {
    $queries = [];

    $place_input = trim((string) $place_input);
    $cid = trim((string) $cid);

    if ($place_input !== '') {
        $queries[] = $place_input;
    }
    if ($cid !== '') {
        $queries[] = $cid;
        $queries[] = 'cid:' . $cid;
        $queries[] = 'https://www.google.com/maps?cid=' . $cid;
    }

    $name = trim((string) goody_get_option('restaurant_name', ''));
    $address = trim((string) goody_get_option('contact_address', ''));

    if ($name !== '' && $address !== '') {
        $queries[] = trim($name . ' ' . $address);
    }
    if ($name !== '') {
        $queries[] = $name;
    }
    if ($address !== '') {
        $queries[] = $address;
    }

    return array_values(array_unique(array_filter(array_map('trim', $queries))));
}

function goody_get_reviews_external_provider_data($provider, $place_input, $count, $force_refresh = false, $allow_live_fetch = true) {
    if ($provider !== 'trustpilot' && $provider !== 'custom') {
        return [];
    }

    $endpoint = $provider === 'trustpilot'
        ? goody_normalize_url_input(goody_get_option('trustpilot_api_url', ''))
        : goody_normalize_url_input(goody_get_option('custom_reviews_api_url', ''));

    $token = $provider === 'trustpilot'
        ? goody_normalize_api_token(goody_get_option('trustpilot_api_token', ''))
        : goody_normalize_api_token(goody_get_option('custom_reviews_api_token', ''));

    $provider_key = goody_get_effective_reviews_api_key($provider);
    if ($token === '' && $provider_key !== '') {
        $token = $provider_key;
    }

    $cache_source = trim($endpoint . '|' . $place_input . '|' . md5($token));
    $cache_key = 'goody_google_reviews_' . md5($provider . ':' . $cache_source . '|' . $count);
    $negative_cache_key = $cache_key . '_empty';
    $cached = get_transient($cache_key);
    if (! $force_refresh && is_array($cached) && ! empty($cached)) {
        return $cached;
    }
    if (! $force_refresh && get_transient($negative_cache_key) === '1') {
        return [];
    }

    if (! $allow_live_fetch) {
        return [];
    }

    if ($endpoint === '') {
        return [];
    }

    $payload = goody_get_external_reviews_data($endpoint, $token, $count, $provider);
    if (empty($payload)) {
        set_transient($negative_cache_key, '1', 10 * MINUTE_IN_SECONDS);
        return [];
    }

    $payload = goody_trim_reviews_payload($payload, $count);
    set_transient($cache_key, $payload, 30 * MINUTE_IN_SECONDS);
    delete_transient($negative_cache_key);

    return $payload;
}

function goody_get_reviews_serpapi_provider_data($place_input, $count, $force_refresh = false, $allow_live_fetch = true) {
    $api_key = goody_get_effective_reviews_api_key('serpapi');
    if ($api_key === '') {
        return [];
    }

    $cache_source = goody_extract_serpapi_data_id($place_input);
    if ($cache_source === '') {
        $cache_source = goody_extract_google_place_id($place_input);
    }
    if ($cache_source === '') {
        $cache_source = goody_extract_google_cid($place_input);
    }
    if ($cache_source === '') {
        $cache_source = trim((string) $place_input);
    }
    if ($cache_source === '') {
        $cache_source = trim((string) goody_get_option('restaurant_name', ''));
    }
    if ($cache_source === '') {
        $cache_source = 'fallback';
    }

    $cache_key = 'goody_google_reviews_' . md5('serp:' . $cache_source . '|' . $count);
    $negative_cache_key = $cache_key . '_empty';
    $cached = get_transient($cache_key);
    if (! $force_refresh && is_array($cached) && ! empty($cached)) {
        return $cached;
    }
    if (! $force_refresh && get_transient($negative_cache_key) === '1') {
        return [];
    }

    if (! $allow_live_fetch) {
        return [];
    }

    $payload = goody_get_serpapi_reviews_data($api_key, $place_input, $count);
    if (empty($payload)) {
        set_transient($negative_cache_key, '1', 10 * MINUTE_IN_SECONDS);
        return [];
    }

    $payload = goody_trim_reviews_payload($payload, $count);
    set_transient($cache_key, $payload, 30 * MINUTE_IN_SECONDS);
    delete_transient($negative_cache_key);

    return $payload;
}

function goody_get_reviews_google_provider_data($place_input, $count, $force_refresh = false, $allow_live_fetch = true) {
    $api_key = goody_get_effective_reviews_api_key('google');
    if ($api_key === '') {
        return [];
    }

    $place_id = goody_extract_google_place_id($place_input);
    $cid = goody_extract_google_cid($place_input);

    if ($place_id === '') {
        $place_id = goody_resolve_google_place_id($api_key, goody_build_google_place_queries($place_input, $cid));
    }

    if ($place_id === '' && $cid === '') {
        return [];
    }

    $cache_source = $place_id !== '' ? 'pid:' . $place_id : 'cid:' . $cid;
    $cache_key = 'goody_google_reviews_' . md5($cache_source . '|' . $count);
    $negative_cache_key = $cache_key . '_empty';
    $cached = get_transient($cache_key);
    if (! $force_refresh && is_array($cached) && ! empty($cached)) {
        return $cached;
    }
    if (! $force_refresh && get_transient($negative_cache_key) === '1') {
        return [];
    }

    if (! $allow_live_fetch) {
        return [];
    }

    $legacy_params = [
        'fields' => 'name,rating,user_ratings_total,reviews,url',
        'reviews_sort' => 'newest',
        'key' => $api_key,
    ];
    if ($place_id !== '') {
        $legacy_params['place_id'] = $place_id;
    } else {
        $legacy_params['cid'] = $cid;
    }

    $legacy_endpoint = add_query_arg($legacy_params, 'https://maps.googleapis.com/maps/api/place/details/json');
    $legacy_response = wp_remote_get($legacy_endpoint, [
        'timeout' => 10,
        'headers' => [
            'Accept' => 'application/json',
        ],
    ]);

    $payload = [];
    if (! is_wp_error($legacy_response) && (int) wp_remote_retrieve_response_code($legacy_response) >= 200 && (int) wp_remote_retrieve_response_code($legacy_response) < 300) {
        $legacy_data = json_decode((string) wp_remote_retrieve_body($legacy_response), true);
        $payload = goody_parse_google_reviews_legacy_response($legacy_data);
    }

    if (empty($payload) && $place_id !== '') {
        $new_endpoint = 'https://places.googleapis.com/v1/places/' . rawurlencode($place_id) . '?fields=id,displayName,rating,userRatingCount,reviews,googleMapsUri';
        $new_response = wp_remote_get($new_endpoint, [
            'timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'X-Goog-Api-Key' => $api_key,
            ],
        ]);

        if (! is_wp_error($new_response) && (int) wp_remote_retrieve_response_code($new_response) >= 200 && (int) wp_remote_retrieve_response_code($new_response) < 300) {
            $new_data = json_decode((string) wp_remote_retrieve_body($new_response), true);
            $payload = goody_parse_google_reviews_new_response($new_data);
        }
    }

    if (empty($payload)) {
        set_transient($negative_cache_key, '1', 10 * MINUTE_IN_SECONDS);
        return [];
    }

    $payload = goody_trim_reviews_payload($payload, $count);
    set_transient($cache_key, $payload, 30 * MINUTE_IN_SECONDS);
    delete_transient($negative_cache_key);

    return $payload;
}

function goody_get_google_reviews_data($force_refresh = false) {
    $count = absint(goody_get_option('google_reviews_count', '6'));
    if ($count < 1) {
        $count = 6;
    }
    $count = min(10, $count);

    if (goody_get_option('google_reviews_mock_mode', '0') === '1') {
        return goody_get_mock_google_reviews_data($count);
    }

    $place_input = (string) goody_get_option('google_reviews_place_id', '');
    if (
        $place_input !== '' &&
        goody_extract_serpapi_data_id($place_input) === '' &&
        goody_extract_google_place_id($place_input) === '' &&
        goody_extract_google_cid($place_input) === '' &&
        goody_looks_like_reviews_api_key($place_input)
    ) {
        $place_input = '';
    }

    $provider_key_hint = goody_get_effective_reviews_api_key('auto');
    $selected_provider = goody_get_reviews_provider($provider_key_hint, $place_input);

    $has_google_place_context = goody_extract_google_place_id($place_input) !== '' || goody_extract_google_cid($place_input) !== '';
    $google_key_hint = goody_get_effective_reviews_api_key('google');
    if ($has_google_place_context && $google_key_hint !== '') {
        $selected_provider = 'google';
    }

    $trustpilot_url = goody_normalize_url_input(goody_get_option('trustpilot_api_url', ''));
    $custom_url = goody_normalize_url_input(goody_get_option('custom_reviews_api_url', ''));
    $google_key = goody_get_effective_reviews_api_key('google');
    $serpapi_key = goody_get_effective_reviews_api_key('serpapi');

    $providers = [$selected_provider];
    if ($google_key !== '') {
        $providers[] = 'google';
    }
    if ($serpapi_key !== '') {
        $providers[] = 'serpapi';
    }
    if ($trustpilot_url !== '') {
        $providers[] = 'trustpilot';
    }
    if ($custom_url !== '') {
        $providers[] = 'custom';
    }

    $providers = array_values(array_unique(array_filter($providers, static function ($provider) {
        return in_array($provider, ['google', 'serpapi', 'trustpilot', 'custom'], true);
    })));

    $allow_live_fetch = $force_refresh || goody_should_allow_live_remote_fetch();
    if (! $allow_live_fetch && ! is_admin()) {
        // Allow cache-backed live reviews on frontend so public pages can show fresh data.
        $allow_live_fetch = true;
    }
    if (! $allow_live_fetch && function_exists('wp_schedule_single_event')) {
        $next_sync = wp_next_scheduled('goody_google_reviews_sync_event');
        if (! $next_sync || ($next_sync - time()) > 300) {
            wp_schedule_single_event(time() + 45, 'goody_google_reviews_sync_event');
        }
    }

    foreach ($providers as $provider) {
        $payload = [];

        if ($provider === 'google') {
            $payload = goody_get_reviews_google_provider_data($place_input, $count, $force_refresh, $allow_live_fetch);
        } elseif ($provider === 'serpapi') {
            $payload = goody_get_reviews_serpapi_provider_data($place_input, $count, $force_refresh, $allow_live_fetch);
        } elseif ($provider === 'trustpilot' || $provider === 'custom') {
            $payload = goody_get_reviews_external_provider_data($provider, $place_input, $count, $force_refresh, $allow_live_fetch);
        }

        if (! empty($payload)) {
            return $payload;
        }
    }

    return [];
}

function goody_refresh_google_reviews_cache() {
    goody_get_google_reviews_data(true);
}
add_action('goody_google_reviews_sync_event', 'goody_refresh_google_reviews_cache');

function goody_schedule_google_reviews_sync() {
    if (wp_next_scheduled('goody_google_reviews_sync_event')) {
        return;
    }

    wp_schedule_event(time() + 300, 'hourly', 'goody_google_reviews_sync_event');
}
add_action('wp', 'goody_schedule_google_reviews_sync');

function goody_clear_google_reviews_sync_schedule() {
    $timestamp = wp_next_scheduled('goody_google_reviews_sync_event');
    if ($timestamp) {
        wp_unschedule_event($timestamp, 'goody_google_reviews_sync_event');
    }
}
add_action('switch_theme', 'goody_clear_google_reviews_sync_schedule');

function goody_get_average_rating() {
    $query = goody_get_posts([
        'post_type' => 'testimonial',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]);

    if (! $query->have_posts()) {
        return 0;
    }

    $sum = 0;
    $count = 0;
    foreach ($query->posts as $testimonial_id) {
        $rating = (int) get_post_meta($testimonial_id, 'goody_testimonial_rating', true);
        if ($rating > 0) {
            $sum += min(5, $rating);
            $count++;
        }
    }

    if ($count === 0) {
        return 0;
    }

    return round($sum / $count, 1);
}

function goody_get_aspect_ratings_summary() {
    $query = goody_get_posts([
        'post_type' => 'testimonial',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]);

    if (! $query->have_posts()) {
        return [];
    }

    $aspects = [
        'food' => [
            'label' => __('Food', 'goody'),
            'meta_key' => 'goody_testimonial_food_rating',
        ],
        'ambiance' => [
            'label' => __('Ambiance', 'goody'),
            'meta_key' => 'goody_testimonial_ambiance_rating',
        ],
        'service' => [
            'label' => __('Service', 'goody'),
            'meta_key' => 'goody_testimonial_service_rating',
        ],
    ];

    $summary = [];
    foreach ($aspects as $slug => $config) {
        $summary[$slug] = [
            'label' => $config['label'],
            'average' => 0,
            'count' => 0,
        ];
    }

    foreach ($query->posts as $testimonial_id) {
        foreach ($aspects as $slug => $config) {
            $value = (int) get_post_meta($testimonial_id, $config['meta_key'], true);
            if ($value < 1 || $value > 5) {
                continue;
            }

            $summary[$slug]['average'] += $value;
            $summary[$slug]['count']++;
        }
    }

    $result = [];
    foreach ($summary as $slug => $stats) {
        if ($stats['count'] < 1) {
            continue;
        }

        $result[$slug] = [
            'label' => $stats['label'],
            'average' => round($stats['average'] / $stats['count'], 1),
            'count' => $stats['count'],
        ];
    }

    return $result;
}

function goody_build_menu_query_args($filters = []) {
    $filters = wp_parse_args($filters, [
        'category' => '',
        'dietary' => '',
        'meal_type' => '',
        'offer' => '',
        'new_only' => '0',
        'q' => '',
    ]);

    $meta_query = [];
    $tax_query = [];
    $post_in = [];

    if (goody_get_option('menu_show_unavailable', '0') !== '1') {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key' => 'goody_menu_available',
                'value' => '1',
                'compare' => '=',
            ],
            [
                'key' => 'goody_menu_available',
                'compare' => 'NOT EXISTS',
            ],
            [
                'key' => 'goody_menu_available',
                'value' => '',
                'compare' => '=',
            ],
        ];
    }

    if ($filters['new_only'] === '1') {
        $meta_query[] = [
            'relation' => 'OR',
            [
                'key' => 'goody_menu_is_new',
                'value' => '1',
                'compare' => '=',
            ],
            [
                'key' => 'goody_menu_badge',
                'value' => 'new',
                'compare' => '=',
            ],
        ];
    }

    if ($filters['category']) {
        $tax_query[] = [
            'taxonomy' => 'menu_category',
            'field' => 'slug',
            'terms' => sanitize_title($filters['category']),
        ];
    }

    if ($filters['dietary']) {
        $tax_query[] = [
            'taxonomy' => 'dietary_preference',
            'field' => 'slug',
            'terms' => sanitize_title($filters['dietary']),
        ];
    }

    if ($filters['meal_type']) {
        $tax_query[] = [
            'taxonomy' => 'meal_type',
            'field' => 'slug',
            'terms' => sanitize_title($filters['meal_type']),
        ];
    }

    if ($filters['offer']) {
        if ($filters['offer'] === 'special') {
            $menu_ids = goody_get_active_offer_menu_ids();
            if (empty($menu_ids)) {
                $menu_ids = [0];
            }
            $post_in = $menu_ids;
        } else {
            $tax_query[] = [
                'taxonomy' => 'offer_tag',
                'field' => 'slug',
                'terms' => sanitize_title($filters['offer']),
            ];
        }
    }

    $args = [
        'post_type' => 'menu_item',
        'post_status' => 'publish',
        'posts_per_page' => (int) goody_get_option('menu_items_count', '12'),
        'goody_menu_sort_order' => true,
        'orderby' => [
            'title' => 'ASC',
        ],
    ];

    if (! empty($meta_query)) {
        $args['meta_query'] = count($meta_query) > 1 ? array_merge(['relation' => 'AND'], $meta_query) : $meta_query;
    }

    if (! empty($tax_query)) {
        $args['tax_query'] = count($tax_query) > 1 ? array_merge(['relation' => 'AND'], $tax_query) : $tax_query;
    }

    if (! empty($post_in)) {
        $args['post__in'] = $post_in;
    }

    if ($filters['q']) {
        $args['s'] = sanitize_text_field($filters['q']);
    }

    return $args;
}

function goody_menu_items_sort_order_clauses($clauses, $query) {
    if (! $query instanceof WP_Query || ! $query->get('goody_menu_sort_order')) {
        return $clauses;
    }

    global $wpdb;

    $join = " LEFT JOIN {$wpdb->postmeta} AS goody_menu_sort_order_meta ON ({$wpdb->posts}.ID = goody_menu_sort_order_meta.post_id AND goody_menu_sort_order_meta.meta_key = 'goody_menu_sort_order')";
    if (strpos((string) $clauses['join'], 'goody_menu_sort_order_meta') === false) {
        $clauses['join'] .= $join;
    }

    $clauses['orderby'] = "CASE WHEN goody_menu_sort_order_meta.meta_value IS NULL OR goody_menu_sort_order_meta.meta_value = '' THEN 1 ELSE 0 END ASC, CAST(goody_menu_sort_order_meta.meta_value AS SIGNED) ASC, {$wpdb->posts}.post_title ASC";

    return $clauses;
}
add_filter('posts_clauses', 'goody_menu_items_sort_order_clauses', 10, 2);
