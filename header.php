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
$language_items = [];
$short_language_code = static function ($value) {
    $label = strtoupper(preg_replace('/[^A-Z]/', '', (string) $value));
    if ($label === '') {
        return '';
    }

    return strlen($label) > 3 ? substr($label, 0, 3) : $label;
};

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

            $name = sanitize_text_field((string) ($lang['name'] ?? $lang['translated_name'] ?? ''));
            $label = $short_language_code($lang['slug'] ?? '');
            if ($label === '') {
                $label = $short_language_code($lang['locale'] ?? '');
            }
            if ($label === '' && $name !== '') {
                $label = $short_language_code($name);
            }
            if ($label === '') {
                continue;
            }

            $language_items[] = [
                'url' => esc_url((string) $lang['url']),
                'name' => $name ?: $label,
                'label' => $label,
                'current' => ! empty($lang['current_lang']),
            ];
        }
    }
} else {
    $wpml_languages = apply_filters('wpml_active_languages', null, [
        'skip_missing' => 0,
        'orderby' => 'code',
    ]);

    if (is_array($wpml_languages) && ! empty($wpml_languages)) {
        foreach ($wpml_languages as $lang) {
            if (! is_array($lang) || empty($lang['url'])) {
                continue;
            }

            $name = sanitize_text_field((string) ($lang['translated_name'] ?? $lang['native_name'] ?? ''));
            $label = $short_language_code($lang['code'] ?? ($lang['language_code'] ?? ''));
            if ($label === '' && $name !== '') {
                $label = $short_language_code($name);
            }
            if ($label === '') {
                continue;
            }

            $language_items[] = [
                'url' => esc_url((string) $lang['url']),
                'name' => $name ?: $label,
                'label' => $label,
                'current' => ! empty($lang['active']),
            ];
        }
    }
}

if (! empty($language_items)) {
    $language_switcher_markup .= '<ul class="header-language__list" role="list">';
    foreach ($language_items as $item) {
        $current_class = $item['current'] ? ' class="is-current"' : '';
        $aria_current = $item['current'] ? ' aria-current="page"' : '';
        $language_switcher_markup .= '<li><a' . $current_class . ' href="' . $item['url'] . '" title="' . esc_attr($item['name']) . '"' . $aria_current . '>' . esc_html($item['label']) . '</a></li>';
    }
    $language_switcher_markup .= '</ul>';
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
                <?php if ($header_search_enabled) : ?>
                    <form role="search" method="get" class="header-search" action="<?php echo esc_url(home_url('/')); ?>" data-goody-search-trigger-form>
                        <label class="screen-reader-text" for="goody-header-search"><?php esc_html_e('Search', 'goody'); ?></label>
                        <input id="goody-header-search" type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php echo esc_attr(goody_get_option('header_search_placeholder', __('Search...', 'goody'))); ?>" data-goody-search-trigger-input>
                        <button type="button" aria-label="<?php esc_attr_e('Open search', 'goody'); ?>" data-goody-search-open><?php echo goody_svg('search'); ?></button>
                    </form>
                <?php endif; ?>

                <?php if ($language_switcher_markup) : ?>
                    <div class="header-language"><?php echo $language_switcher_markup; ?></div>
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
