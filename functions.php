<?php
if (! defined('GOODY_THEME_VERSION')) {
    define('GOODY_THEME_VERSION', '2.1.10');
}
if (! defined('GOODY_THEME_DIR')) {
    define('GOODY_THEME_DIR', get_template_directory());
}
if (! defined('GOODY_THEME_URI')) {
    define('GOODY_THEME_URI', get_template_directory_uri());
}

require_once GOODY_THEME_DIR . '/inc/helpers.php';
require_once GOODY_THEME_DIR . '/inc/setup.php';
require_once GOODY_THEME_DIR . '/inc/enqueue.php';
require_once GOODY_THEME_DIR . '/inc/post-types.php';
require_once GOODY_THEME_DIR . '/inc/meta-boxes.php';
require_once GOODY_THEME_DIR . '/inc/theme-options.php';
require_once GOODY_THEME_DIR . '/inc/ajax.php';
require_once GOODY_THEME_DIR . '/inc/forms.php';
require_once GOODY_THEME_DIR . '/inc/seo.php';
require_once GOODY_THEME_DIR . '/includes/reservation-functions.php';
require_once GOODY_THEME_DIR . '/includes/admin-dashboard.php';
require_once GOODY_THEME_DIR . '/includes/woocommerce-integration.php';
