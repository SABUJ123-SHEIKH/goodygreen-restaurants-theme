<?php
$event_id = get_the_ID();
$section = goody_get_single_home_section('event');
$event_date = get_post_meta($event_id, 'goody_event_date', true);
$event_time = get_post_meta($event_id, 'goody_event_time', true);
$cta_label = get_post_meta($event_id, 'goody_event_cta_label', true);
$cta_url = get_post_meta($event_id, 'goody_event_cta_url', true);
$event_timestamp = $event_date ? strtotime($event_date) : false;
?>
<section class="page-section cpt-single cpt-single--event">
    <div class="container">
        <nav class="cpt-single__crumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'goody'); ?>">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'goody'); ?></a>
            <span>/</span>
            <a href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php echo esc_html($section['label']); ?></a>
            <span>/</span>
            <strong><?php the_title(); ?></strong>
        </nav>

        <article <?php post_class('card cpt-single__hero'); ?>>
            <div class="cpt-single__media">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php else : ?>
                    <div class="cpt-single__placeholder"><?php esc_html_e('Event', 'goody'); ?></div>
                <?php endif; ?>
            </div>

            <div class="cpt-single__content">
                <span class="eyebrow"><?php esc_html_e('Event', 'goody'); ?></span>
                <h1><?php the_title(); ?></h1>
                <?php if (get_the_excerpt()) : ?><p class="cpt-single__lead"><?php echo esc_html(get_the_excerpt()); ?></p><?php endif; ?>

                <div class="cpt-single__chips">
                    <?php if ($event_timestamp) : ?><span><?php echo esc_html(date_i18n(get_option('date_format'), $event_timestamp)); ?></span><?php endif; ?>
                    <?php if ($event_time) : ?><span><?php echo esc_html($event_time); ?></span><?php endif; ?>
                </div>

                <div class="button-group cpt-single__actions">
                    <a class="button button--outline" href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php esc_html_e('Back to Events', 'goody'); ?></a>
                    <?php if ($cta_url) : ?>
                        <a class="button" href="<?php echo esc_url($cta_url); ?>" target="_blank" rel="noopener"><?php echo esc_html($cta_label ?: __('Join Event', 'goody')); ?></a>
                    <?php elseif (goody_get_option('reservation_custom_url')) : ?>
                        <a class="button" href="<?php echo esc_url(goody_get_option('reservation_custom_url')); ?>"><?php esc_html_e('Reserve a Table', 'goody'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
        </article>

        <div class="cpt-single__grid">
            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('About This Event', 'goody'); ?></h2>
                <div class="cpt-single__body">
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Event Info', 'goody'); ?></h2>
                <ul class="cpt-single__list">
                    <?php if ($event_timestamp) : ?><li><strong><?php esc_html_e('Date', 'goody'); ?>:</strong> <?php echo esc_html(date_i18n(get_option('date_format'), $event_timestamp)); ?></li><?php endif; ?>
                    <?php if ($event_time) : ?><li><strong><?php esc_html_e('Time', 'goody'); ?>:</strong> <?php echo esc_html($event_time); ?></li><?php endif; ?>
                    <li><strong><?php esc_html_e('Location', 'goody'); ?>:</strong> <?php echo esc_html(goody_get_option('contact_address', __('Goody Green Restaurant', 'goody'))); ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>
