<?php get_header(); ?>
<?php while (have_posts()) : the_post(); ?>
    <?php
    $post_type = get_post_type();
    $cpt_single_templates = ['menu_item', 'offer', 'event', 'team_member', 'testimonial'];
    ?>
    <?php if (in_array($post_type, $cpt_single_templates, true) && locate_template('template-parts/single/content-' . $post_type . '.php')) : ?>
        <?php get_template_part('template-parts/single/content', $post_type); ?>
    <?php else : ?>
        <section class="page-section cpt-single cpt-single--default">
            <div class="container ">
                <article <?php post_class('entry-content cpt-single__article'); ?>>
                    <p class="meta"><?php echo esc_html(get_the_date(get_option('date_format'))); ?></p>
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
