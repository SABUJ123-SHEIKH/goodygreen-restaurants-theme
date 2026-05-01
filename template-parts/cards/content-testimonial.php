<?php
$testimonial_id = get_the_ID();
$name = get_post_meta($testimonial_id, 'goody_testimonial_customer_name', true) ?: get_the_title();
$rating = max(1, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_rating', true)));
$source = get_post_meta($testimonial_id, 'goody_testimonial_source_label', true);
$image_id = absint(get_post_meta($testimonial_id, 'goody_testimonial_customer_image', true));
$aspect_food = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_food_rating', true)));
$aspect_ambiance = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_ambiance_rating', true)));
$aspect_service = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_service_rating', true)));
$initial = strtoupper(substr(wp_strip_all_tags((string) $name), 0, 1));
?>
<article <?php post_class('card testimonial-card'); ?> data-review-card data-review-rating="<?php echo esc_attr((string) $rating); ?>">
    <div class="rating">
        <?php for ($i = 0; $i < $rating; $i++) : ?>
            <span class="icon-star"><?php echo goody_svg('star'); ?></span>
        <?php endfor; ?>
    </div>

    <div class="testimonial-card__content"><?php the_content(); ?></div>

    <div class="testimonial-card__author">
        <?php if ($image_id) : ?>
            <div class="testimonial-avatar"><?php echo wp_get_attachment_image($image_id, 'thumbnail'); ?></div>
        <?php elseif (has_post_thumbnail()) : ?>
            <div class="testimonial-avatar"><?php the_post_thumbnail('thumbnail'); ?></div>
        <?php else : ?>
            <div class="testimonial-avatar testimonial-avatar--fallback"><span><?php echo esc_html($initial ?: 'G'); ?></span></div>
        <?php endif; ?>
        <div>
            <strong><?php echo esc_html($name); ?></strong>
            <?php if ($source) : ?><span><?php echo esc_html($source); ?></span><?php endif; ?>
        </div>
    </div>

    <?php if ($aspect_food > 0 || $aspect_ambiance > 0 || $aspect_service > 0) : ?>
        <div class="testimonial-aspects">
            <?php if ($aspect_food > 0) : ?><span><?php esc_html_e('Food', 'goody'); ?>: <?php echo esc_html((string) $aspect_food); ?>/5</span><?php endif; ?>
            <?php if ($aspect_ambiance > 0) : ?><span><?php esc_html_e('Ambiance', 'goody'); ?>: <?php echo esc_html((string) $aspect_ambiance); ?>/5</span><?php endif; ?>
            <?php if ($aspect_service > 0) : ?><span><?php esc_html_e('Service', 'goody'); ?>: <?php echo esc_html((string) $aspect_service); ?>/5</span><?php endif; ?>
        </div>
    <?php endif; ?>

    <a class="text-link" href="<?php the_permalink(); ?>">
        <?php esc_html_e('Read full review', 'goody'); ?>
        <?php echo goody_svg('arrow'); ?>
    </a>
</article>
