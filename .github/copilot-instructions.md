# Goody Green Theme - AI Coding Guidelines

## Overview
This is a WordPress theme for the "Goody Green" restaurant, featuring custom post types for menu items, offers, events, testimonials, and team members. It uses modular SCSS architecture and extensive theme options for customization.

## Architecture
- **Custom Post Types**: `menu_item`, `offer`, `testimonial`, `event`, `team_member`, `goody_message` (contact forms)
- **Taxonomies**: `menu_category`, `dietary_preference`, `meal_type`, `offer_tag`
- **SCSS Structure**: Organized with `@use` imports in `assets/scss/style.scss` - abstracts (variables), base (reset/global), components (buttons/cards/forms), layout (header/footer), sections (hero/menu/offers/etc.), pages (home), utilities (helpers)
- **Template Parts**: Modular components in `template-parts/` - cards for content display, sections for page building blocks
- **AJAX Integration**: Dynamic menu filtering and form submissions via `inc/ajax.php`

## Key Components
- **Theme Options** (`inc/theme-options.php`): Centralized settings for colors, content, integrations (Google Maps, newsletters)
- **Enqueue** (`inc/enqueue.php`): Loads `main.css`, `main.js`, Google Fonts; localizes script with `goodyTheme` object (ajaxUrl, nonce, mapsApiKey)
- **Helpers** (`inc/helpers.php`): Utility functions like `goody_get_option()` for theme settings
- **Forms/AJAX** (`inc/forms.php`, `inc/ajax.php`): Handle contact forms, menu filters; store submissions as `goody_message` posts

## Development Workflow
- **SCSS Compilation**: Source files in `assets/scss/`, compile to `assets/css/main.css` (no automated build - use `sass` CLI or VS Code extension)
- **Debugging**: Enable `WP_DEBUG` in `wp-config.php`; check browser console for JS errors
- **Content Management**: Use WordPress admin for post types; theme options under "Goody Green" menu
- **Integrations**: Paste API keys/embeds in theme options (e.g., Google Maps, newsletter shortcodes)

## Conventions
- **Function Prefix**: All functions start with `goody_` (e.g., `goody_get_option()`, `goody_register_post_types()`)
- **Constants**: `GOODY_THEME_VERSION`, `GOODY_THEME_DIR`, `GOODY_THEME_URI`
- **File Naming**: SCSS partials use `_` prefix (e.g., `_variables.scss`); template parts as `content-{type}.php`
- **Security**: Use `wp_unslash()`, `sanitize_text_field()` for inputs; `check_ajax_referer('goody_nonce')` for AJAX
- **Localization**: Text strings wrapped in `__()` with 'goody' domain
- **Post Type Queries**: Extend search to include custom types via `pre_get_posts` hook

## Examples
- **Adding a Section**: Create `template-parts/sections/new-section.php`, import in `assets/scss/style.scss` as `@use 'sections/new-section'`, enqueue if needed
- **Custom Field**: Use meta boxes in `inc/meta-boxes.php`; access with `get_post_meta(get_the_ID(), 'field_key', true)`
- **AJAX Endpoint**: Add action in `inc/ajax.php` with `add_action('wp_ajax_goody_action', 'callback')`; verify nonce and sanitize inputs
- **Theme Option**: Retrieve with `goody_get_option('key', 'default')`; set via admin page</content>
<parameter name="filePath">/Users/siyambiswas/Local Sites/ablocks/app/public/wp-content/themes/goody-green-theme/.github/copilot-instructions.md