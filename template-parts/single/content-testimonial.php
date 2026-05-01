<?php
$testimonial_id = get_the_ID();
$section = goody_get_single_home_section('testimonial');
$name = get_post_meta($testimonial_id, 'goody_testimonial_customer_name', true) ?: get_the_title();
$source = get_post_meta($testimonial_id, 'goody_testimonial_source_label', true);
$rating = max(1, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_rating', true)));
$food = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_food_rating', true)));
$ambiance = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_ambiance_rating', true)));
$service = max(0, min(5, (int) get_post_meta($testimonial_id, 'goody_testimonial_service_rating', true)));
$customer_image_id = absint(get_post_meta($testimonial_id, 'goody_testimonial_customer_image', true));
?>
<section class="page-section cpt-single cpt-single--testimonial">
    <div class="container">
        <nav class="cpt-single__crumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'goody'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'goody'); ?></a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php echo esc_html($section['label']); ?></a>
            <span>/</span>
            <strong><?php echo esc_html($name); ?></strong>
        </nav>

        <article <?php post_class('card cpt-single__hero'); ?>>
            <div class="cpt-single__media cpt-single__media--compact">
                <?php if ($customer_image_id) : ?>
                    <?php echo wp_get_attachment_image($customer_image_id, 'medium'); ?>
                <?php elseif (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium'); ?>
                <?php else : ?>
                    <div class="cpt-single__placeholder"><?php esc_html_e('Review', 'goody'); ?></div>
                <?php endif; ?>
            </div>

            <div class="cpt-single__content">
                <span class="eyebrow"><?php esc_html_e('Guest Review', 'goody'); ?></span>
                <h1><?php echo esc_html($name); ?></h1>
                <?php if ($source) : ?><p class="cpt-single__lead"><?php echo esc_html($source); ?></p><?php endif; ?>

                <div class="cpt-single__chips cpt-single__chips--rating">
                    <span>
                        <?php for ($i = 0; $i < $rating; $i++) : ?>
                            <span class="icon-star"><?php echo goody_svg('star'); ?></span>
                        <?php endfor; ?>
                    </span>
                    <span><?php echo esc_html(sprintf(__('Overall: %d/5', 'goody'), $rating)); ?></span>
                    <?php if ($food) : ?><span><?php echo esc_html(sprintf(__('Food: %d/5', 'goody'), $food)); ?></span><?php endif; ?>
                    <?php if ($ambiance) : ?><span><?php echo esc_html(sprintf(__('Ambiance: %d/5', 'goody'), $ambiance)); ?></span><?php endif; ?>
                    <?php if ($service) : ?><span><?php echo esc_html(sprintf(__('Service: %d/5', 'goody'), $service)); ?></span><?php endif; ?>
                </div>

                <div class="button-group cpt-single__actions">
                    <a class="button button--outline" href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php esc_html_e('Back to Reviews', 'goody'); ?></a>
                    <a class="button" href="<?php echo esc_url(home_url('/#contact')); ?>"><?php esc_html_e('Visit Us', 'goody'); ?></a>
                </div>
            </div>
        </article>

        <div class="card cpt-single__panel">
            <h2><?php esc_html_e('What They Shared', 'goody'); ?></h2>
            <div class="cpt-single__body">
                <?php the_content(); ?>
            </div>
        </div>
    </div>
</section>
