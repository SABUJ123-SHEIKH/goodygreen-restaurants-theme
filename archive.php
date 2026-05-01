<?php get_header(); ?>
<section class="page-section">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php post_type_archive_title(); ?></span>
            <h1><?php the_archive_title(); ?></h1>
            <?php the_archive_description('<div class="archive-description">', '</div>'); ?>
        </header>

        <?php if (have_posts()) : ?>
            <div class="archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $post_type = get_post_type();
                    $template = locate_template('template-parts/cards/content-' . $post_type . '.php');
                    if ($template) {
                        get_template_part('template-parts/cards/content', $post_type);
                    } else {
                        get_template_part('template-parts/cards/content');
                    }
                    ?>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No items found.', 'goody'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
