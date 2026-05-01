<?php
$today = current_time('Y-m-d');
$args = [
    'post_type' => 'event',
    'posts_per_page' => 6,
    'post_status' => 'publish',
    'meta_key' => 'goody_event_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
];

if (goody_get_option('events_show_past', '0') !== '1') {
    $args['meta_query'] = [
        [
            'key' => 'goody_event_date',
            'value' => $today,
            'compare' => '>=',
            'type' => 'DATE',
        ],
    ];
}

$events = new WP_Query($args);
?>
<section id="events" class="page-section">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php esc_html_e('Events', 'goody'); ?></span>
            <h2><?php echo esc_html(goody_get_option('events_section_title', __('Upcoming Events', 'goody'))); ?></h2>
            <p><?php echo esc_html(goody_get_option('events_section_text', '')); ?></p>
        </header>

        <?php if ($events->have_posts()) : ?>
            <div class="archive-grid archive-grid--two">
                <?php while ($events->have_posts()) : $events->the_post(); ?>
                    <?php get_template_part('template-parts/cards/content', 'event'); ?>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="card empty-state">
                <h3><?php esc_html_e('No current or upcoming events', 'goody'); ?></h3>
                <p><?php esc_html_e('Create Event posts to show promotions and event campaigns.', 'goody'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
