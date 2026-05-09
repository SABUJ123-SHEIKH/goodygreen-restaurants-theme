<?php
$options = goody_get_options();
$sticky_class = goody_get_option('header_sticky', '1') === '1' ? 'site-header--sticky' : '';
$custom_logo_option = absint(goody_get_option('restaurant_logo'));
$custom_logo_option_url = $custom_logo_option ? wp_get_attachment_image_url($custom_logo_option, 'full') : '';
$restaurant_name = trim((string) goody_get_option('restaurant_name', get_bloginfo('name')));
$restaurant_tagline = trim((string) goody_get_option('restaurant_tagline', get_bloginfo('description')));
$header_search_enabled = goody_get_option('header_show_search', '1') === '1';
$mobile_bottom_nav_enabled = goody_get_option('header_enable_mobile_bottom_nav', '1') === '1';
$bottom_cta_bar_enabled = goody_get_option('header_enable_bottom_cta_bar', '0') === '1';
$header_dropdown_menu_enabled = goody_is_dropdown_menu_enabled();
$header_mega_menu_enabled = goody_is_mega_menu_enabled();
$site_navigation_classes = [
    'site-navigation',
    $header_dropdown_menu_enabled ? 'site-navigation--dropdown-enabled' : 'site-navigation--dropdown-disabled',
    $header_mega_menu_enabled ? 'site-navigation--mega-enabled' : 'site-navigation--mega-disabled',
];
$header_reserve_url = goody_get_reservation_url();
if (! $header_reserve_url) {
    $header_reserve_url = goody_maybe_get_direct_checkout_url(goody_get_option('hero_primary_url', ''));
}
$header_reserve_text = trim((string) goody_get_option('reservation_button_text', ''));
if ($header_reserve_text === '') {
    $header_reserve_text = __('Reserve', 'goody');
}
$bottom_cta_text = trim((string) goody_get_option('newsletter_title', ''));
if ($bottom_cta_text === '') {
    $bottom_cta_text = __('Be the first to know the news', 'goody');
}
$show_bottom_cta = $bottom_cta_bar_enabled && $header_reserve_url;
$show_mobile_bottom_nav = $mobile_bottom_nav_enabled && ! $show_bottom_cta;
$body_classes = [];
if ($show_bottom_cta) {
    $body_classes[] = 'goody-bottom-cta-active';
}
if ($show_mobile_bottom_nav) {
    $body_classes[] = 'goody-bottom-nav-active';
}
$mobile_bottom_nav_targets = [];
$mobile_bottom_nav_items = [];
if ($show_mobile_bottom_nav) {
    $mobile_bottom_nav_targets = [
        'menu' => [
            'aliases' => ['menu'],
            'class' => 'goody-mobile-nav-item--menu',
        ],
        'account' => [
            'aliases' => ['account', 'my-account', 'myaccount'],
            'class' => 'goody-mobile-nav-item--account',
        ],
        'order-status' => [
            'aliases' => ['order-status', 'orderstatus', 'tracking', 'track-order', 'trackorder'],
            'class' => 'goody-mobile-nav-item--order-status',
        ],
        'reservation' => [
            'aliases' => ['reservation', 'reserve', 'booking'],
            'class' => 'goody-mobile-nav-item--reservation',
        ],
        'live-chat' => [
            'aliases' => ['live-chat', 'livechat', 'chat', 'support'],
            'class' => 'goody-mobile-nav-item--live-chat',
        ],
    ];
    $mobile_nav_normalize = static function ($value) {
        $value = strtolower(trim((string) $value));
        $value = wp_strip_all_tags($value);
        $value = preg_replace('/[^a-z0-9]+/', '-', $value);
        return trim((string) $value, '-');
    };
    $locations = get_nav_menu_locations();
    $primary_menu_id = absint($locations['primary'] ?? 0);
    $primary_menu_items = $primary_menu_id > 0 ? wp_get_nav_menu_items($primary_menu_id) : [];
    if (empty($primary_menu_items)) {
        $menus = wp_get_nav_menus(['hide_empty' => true]);
        if (! empty($menus) && ! is_wp_error($menus)) {
            foreach ($menus as $menu) {
                $items = wp_get_nav_menu_items((int) $menu->term_id);
                if (! empty($items)) {
                    $primary_menu_items = $items;
                    break;
                }
            }
        }
    }
    if (! empty($primary_menu_items)) {
        foreach ($primary_menu_items as $item) {
            if (! is_object($item) || ! isset($item->title, $item->url)) {
                continue;
            }
            if ((string) ($item->menu_item_parent ?? '0') !== '0') {
                continue;
            }
            $title_key = $mobile_nav_normalize($item->title);
            $url_path = $mobile_nav_normalize((string) parse_url((string) $item->url, PHP_URL_PATH));
            foreach ($mobile_bottom_nav_targets as $target_key => $target) {
                if (isset($mobile_bottom_nav_items[$target_key])) {
                    continue;
                }
                $aliases = $target['aliases'] ?? [];
                foreach ($aliases as $alias) {
                    $alias_key = $mobile_nav_normalize($alias);
                    if ($title_key === $alias_key || strpos($title_key, $alias_key) !== false || strpos($url_path, $alias_key) !== false) {
                        $item_classes = is_array($item->classes ?? null) ? $item->classes : [];
                        $is_current = in_array('current-menu-item', $item_classes, true) || in_array('current_page_item', $item_classes, true) || in_array('current-menu-ancestor', $item_classes, true);
                        $mobile_bottom_nav_items[$target_key] = [
                            'url' => esc_url((string) $item->url),
                            'label' => esc_html((string) $item->title),
                            'class' => $target['class'] . ($is_current ? ' current-menu-item' : ''),
                        ];
                        break 2;
                    }
                }
            }
        }
    }
    if (empty($mobile_bottom_nav_items['live-chat'])) {
        $whatsapp_number = preg_replace('/\D+/', '', (string) goody_get_option('contact_whatsapp_number', ''));
        $live_chat_url = $whatsapp_number !== '' ? 'https://wa.me/' . $whatsapp_number : home_url('/#contact');
        $mobile_bottom_nav_items['live-chat'] = [
            'url' => esc_url($live_chat_url),
            'label' => esc_html__('Live Chat', 'goody'),
            'class' => 'goody-mobile-nav-item--live-chat',
        ];
    }
}
$language_switcher_markup = '';
if (function_exists('goody_get_language_switcher_items')) {
    $language_items = goody_get_language_switcher_items();
} else {
    $language_items = [];
}
if (! empty($language_items)) {
    $language_switcher_markup .= '<label class="screen-reader-text" for="goody-language-switcher">' . esc_html__('Select language', 'goody') . '</label>';
    $language_switcher_markup .= '<select id="goody-language-switcher" class="header-language__select" aria-label="' . esc_attr__('Select language', 'goody') . '" onchange="if(this.value){window.location.href=this.value;}">';
    foreach ($language_items as $item) {
        $selected = ! empty($item['current']) ? ' selected' : '';
        $language_switcher_markup .= '<option value="' . esc_url((string) $item['url']) . '"' . $selected . '>' . esc_html((string) ($item['flag'] ?? '🌐')) . '</option>';
    }
    $language_switcher_markup .= '</select>';
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class($body_classes); ?>>
<?php wp_body_open(); ?>

<header class="site-header <?php echo esc_attr($sticky_class); ?>">
    <div class="container site-header__inner">
        <div class="site-branding">
            <?php if ($custom_logo_option_url) : ?>
                <a class="custom-logo-link" href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <img class="custom-logo" src="<?php echo esc_url($custom_logo_option_url); ?>" alt="<?php echo esc_attr(goody_get_option('restaurant_logo_alt', $restaurant_name)); ?>">
                </a>
            <?php elseif (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a class="site-title" href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html($restaurant_name); ?></a>
            <?php endif; ?>

            <?php if ($restaurant_tagline) : ?>
                <p class="site-tagline"><?php echo esc_html($restaurant_tagline); ?></p>
            <?php endif; ?>
        </div>

        <button class="menu-toggle" aria-expanded="false" aria-controls="site-navigation" aria-label="<?php esc_attr_e('Toggle navigation menu', 'goody'); ?>">
            <span></span><span></span><span></span>
        </button>

        <nav id="site-navigation" class="<?php echo esc_attr(implode(' ', $site_navigation_classes)); ?>" aria-label="<?php esc_attr_e('Primary menu', 'goody'); ?>">
            <div class="site-navigation__menu">
                <?php wp_nav_menu(goody_get_primary_nav_menu_args()); ?>
            </div>

            <div class="site-navigation__tools">
                <?php if ($language_switcher_markup) : ?>
                    <div class="header-language"><?php echo $language_switcher_markup; ?></div>
                <?php endif; ?>
                <?php if ($header_search_enabled) : ?>
                    <form role="search" method="get" class="header-search" action="<?php echo esc_url(home_url('/')); ?>" data-goody-search-trigger-form>
                        <label class="screen-reader-text" for="goody-header-search"><?php esc_html_e('Search', 'goody'); ?></label>
                        <input id="goody-header-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr(goody_get_option('header_search_placeholder', __('Search...', 'goody'))); ?>" data-goody-search-trigger-input>
                        <button type="button" aria-label="<?php esc_attr_e('Open search', 'goody'); ?>" data-goody-search-open><?php echo goody_svg('search'); ?></button>
                    </form>
                <?php endif; ?>

                <?php if ($header_reserve_url) : ?>
                    <a class="button button--small button--header" href="<?php echo esc_url($header_reserve_url); ?>"><?php echo esc_html($header_reserve_text); ?></a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>

<?php if ($show_bottom_cta) : ?>
<nav class="goody-mobile-bottom-nav goody-mobile-bottom-nav--cta" aria-label="<?php esc_attr_e('Quick action', 'goody'); ?>">
    <div class="goody-mobile-bottom-nav__inner">
        <p class="goody-mobile-bottom-nav__text"><span aria-hidden="true">🍽</span> <?php echo esc_html($bottom_cta_text); ?></p>
        <a class="goody-mobile-bottom-nav__button" href="<?php echo esc_url($header_reserve_url); ?>"><?php echo esc_html($header_reserve_text); ?></a>
    </div>
</nav>
<?php endif; ?>

<?php if (! empty($mobile_bottom_nav_items)) : ?>
<nav class="goody-mobile-bottom-nav goody-mobile-bottom-nav--menu" aria-label="<?php esc_attr_e('Mobile quick menu', 'goody'); ?>">
    <div class="goody-mobile-bottom-nav__inner">
        <ul class="goody-mobile-bottom-menu">
            <?php foreach ($mobile_bottom_nav_targets as $target_key => $target) : ?>
                <?php if (empty($mobile_bottom_nav_items[$target_key])) : ?>
                    <?php continue; ?>
                <?php endif; ?>
                <?php $nav_item = $mobile_bottom_nav_items[$target_key]; ?>
                <li class="<?php echo esc_attr((string) $nav_item['class']); ?>">
                    <a href="<?php echo esc_url((string) $nav_item['url']); ?>"><?php echo esc_html((string) $nav_item['label']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>
<?php endif; ?>

<?php if ($header_search_enabled) : ?>
    <section class="goody-search-modal" data-goody-search-modal hidden>
        <button class="goody-search-modal__backdrop" type="button" aria-label="<?php esc_attr_e('Close search', 'goody'); ?>" data-goody-search-close></button>
        <div class="goody-search-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="goody-search-modal-title">
            <div class="goody-search-modal__head">
                <p id="goody-search-modal-title"><?php esc_html_e('Search', 'goody'); ?></p>
                <button class="goody-search-modal__close" type="button" aria-label="<?php esc_attr_e('Close search', 'goody'); ?>" data-goody-search-close>&times;</button>
            </div>
            <form class="goody-search-modal__form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" data-goody-search-form>
                <label class="screen-reader-text" for="goody-search-modal-input"><?php esc_html_e('Search', 'goody'); ?></label>
                <input id="goody-search-modal-input" type="search" name="s" value="" placeholder="<?php echo esc_attr(goody_get_option('header_search_placeholder', __('Search menu, offers, events...', 'goody'))); ?>" autocomplete="off" data-goody-search-input>
                <button class="button button--small" type="submit"><?php esc_html_e('Search', 'goody'); ?></button>
            </form>
            <div class="goody-search-modal__meta" data-goody-search-meta><?php esc_html_e('Start typing to find menu items, offers, events, and posts.', 'goody'); ?></div>
            <div class="goody-search-modal__results" data-goody-search-results></div>
        </div>
    </section>
<?php endif; ?>

<main class="site-main">
