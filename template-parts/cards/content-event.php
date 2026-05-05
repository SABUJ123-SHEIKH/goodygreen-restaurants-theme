<?php
$event_id = get_the_ID();
$event_date = get_post_meta($event_id, 'goody_event_date', true);
$event_time = get_post_meta($event_id, 'goody_event_time', true);
$cta_label = get_post_meta($event_id, 'goody_event_cta_label', true);
$cta_url = get_post_meta($event_id, 'goody_event_cta_url', true);
$timestamp = $event_date ? strtotime($event_date) : time();
?>
<article <?php post_class('card event-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a class="event-card__banner" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('goody-card', ['sizes' => '(max-width: 767px) 46vw, 240px']); ?></a>
    <?php endif; ?>

    <div class="event-card__body">
        <div class="event-card__date">
            <span><?php echo esc_html(date_i18n('d', $timestamp)); ?></span>
            <small><?php echo esc_html(date_i18n('M', $timestamp)); ?></small>
        </div>

        <div class="event-card__content">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <p><?php echo esc_html(get_the_excerpt()); ?></p>

            <div class="event-meta">
                <?php if ($event_date) : ?><span><?php echo esc_html(date_i18n(get_option('date_format'), $timestamp)); ?></span><?php endif; ?>
                <?php if ($event_time) : ?><span><?php echo esc_html($event_time); ?></span><?php endif; ?>
            </div>
            <a class="text-link event-card__details-button" href="<?php the_permalink(); ?>">
                <?php esc_html_e('View event details', 'goody'); ?>
                <span><?php echo goody_svg('arrow'); ?></span>
            </a>
        </div>
    </div>
</article>
