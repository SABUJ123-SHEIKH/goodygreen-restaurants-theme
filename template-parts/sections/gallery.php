<?php
$gallery_limit = goody_get_gallery_zone_item_limit();
$pool = goody_get_showcase_gallery_image_urls('goody-square', [
    'limit' => $gallery_limit,
    'include_menu_fallback' => false,
]);

if (empty($pool)) {
    $fallback_limit = $gallery_limit > 0 ? $gallery_limit : 7;
    $pool = goody_get_showcase_gallery_image_urls('goody-square', [
        'limit' => $fallback_limit,
        'include_menu_fallback' => true,
        'menu_fallback_count' => 8,
    ]);
}
?>
<?php if (! empty($pool)) : ?>
<section id="gallery" class="page-section gallery-zone">
    <div class="container">
        <header class="section-heading section-heading--center">
            <span class="eyebrow"><?php esc_html_e('Gallery', 'goody'); ?></span>
            <h2><?php esc_html_e('The world of', 'goody'); ?> <span class="accent-italic"><?php esc_html_e('Goody', 'goody'); ?></span></h2>
        </header>

        <div class="goody-mosaic">
            <?php foreach ($pool as $index => $img) : ?>
                <article class="goody-mosaic__item goody-mosaic__item--<?php echo esc_attr((string) ($index + 1)); ?>">
                    <img src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e('Gallery item', 'goody'); ?>">
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
