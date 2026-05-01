<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php if (is_page('gallery')) : ?>
        <?php
        $gallery_page_images = goody_get_showcase_gallery_image_urls('goody-square', [
            'limit' => 0,
            'include_menu_fallback' => false,
        ]);
        $has_gallery_intro = trim((string) get_post_field('post_content', get_the_ID())) !== '';
        ?>
        <section id="gallery-page" class="page-section gallery-zone">
            <div class="container">
                <header class="section-heading section-heading--center">
                    <span class="eyebrow"><?php esc_html_e('Gallery', 'goody'); ?></span>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                </header>

                <?php if ($has_gallery_intro) : ?>
                    <article <?php post_class('entry-content'); ?>>
                        <?php the_content(); ?>
                    </article>
                <?php endif; ?>

                <?php if (! empty($gallery_page_images)) : ?>
                    <div class="goody-mosaic">
                        <?php foreach ($gallery_page_images as $img) : ?>
                            <article class="goody-mosaic__item">
                                <img src="<?php echo esc_url($img); ?>" alt="<?php esc_attr_e('Gallery item', 'goody'); ?>">
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('No gallery images found yet.', 'goody'); ?></p>
                <?php endif; ?>
            </div>
        </section>
    <?php else : ?>
        <section class="page-section">
            <div class="container">
                <article <?php post_class('entry-content'); ?>>
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="featured-image"><?php the_post_thumbnail('large'); ?></div>
                    <?php endif; ?>
                    <?php the_content(); ?>
                </article>
            </div>
        </section>
    <?php endif; ?>
<?php endwhile; ?>
<?php get_footer(); ?>
