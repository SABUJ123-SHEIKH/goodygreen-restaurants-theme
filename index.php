<?php get_header(); ?>
<section class="page-section">
    <div class="container">
        <?php if (have_posts()) : ?>
            <div class="archive-grid">
                <?php while (have_posts()) : the_post(); ?>
                    <article <?php post_class('card post-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>" class="post-card__image"><?php the_post_thumbnail('goody-card'); ?></a>
                        <?php endif; ?>
                        <div class="post-card__content">
                            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                            <div class="meta"><?php echo esc_html(get_the_date()); ?></div>
                            <p><?php echo esc_html(get_the_excerpt()); ?></p>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No content found.', 'goody'); ?></p>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
