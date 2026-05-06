<?php
$options = goody_get_options();
$sticky_class = goody_get_option('header_sticky', '1') === '1' ? 'site-header--sticky' : '';
$custom_logo_option = absint(goody_get_option('restaurant_logo'));
$custom_logo_option_url = $custom_logo_option ? wp_get_attachment_image_url($custom_logo_option, 'full') : '';
$restaurant_name = trim((string) goody_get_option('restaurant_name', get_bloginfo('name')));
$restaurant_tagline = trim((string) goody_get_option('restaurant_tagline', get_bloginfo('description')));
$header_search_enabled = goody_get_option('header_show_search', '1') === '1';
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
<body <?php body_class(); ?>>
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
