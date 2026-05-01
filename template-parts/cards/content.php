<article <?php post_class('card post-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="post-card__image"><?php the_post_thumbnail('goody-card'); ?></a>
    <?php endif; ?>
    <div class="post-card__content">
        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>
    </div>
</article>
