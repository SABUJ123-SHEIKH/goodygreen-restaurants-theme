<?php
$team_query = goody_get_posts([
    'post_type' => 'team_member',
    'posts_per_page' => 6,
    'orderby' => 'menu_order title',
    'order' => 'ASC',
]);

if (! $team_query->have_posts()) {
    return;
}
?>
<section id="team" class="page-section team-zone">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php esc_html_e('Team', 'goody'); ?></span>
            <h2><?php esc_html_e('Meet Our Team', 'goody'); ?></h2>
            <p><?php esc_html_e('The people behind your Goody experience.', 'goody'); ?></p>
        </header>

        <div class="archive-grid team-grid">
            <?php while ($team_query->have_posts()) : $team_query->the_post(); ?>
                <?php
                $member_id = get_the_ID();
                $role = get_post_meta($member_id, 'goody_team_role', true);
                $short_bio = get_post_meta($member_id, 'goody_team_short_bio', true);
                ?>
                <article <?php post_class('card team-card'); ?>>
                    <?php if (has_post_thumbnail()) : ?>
                        <a class="team-card__image" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                            <?php the_post_thumbnail('goody-card'); ?>
                        </a>
                    <?php endif; ?>
                    <div class="team-card__content">
                        <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                        <?php if ($role) : ?>
                            <p class="team-card__role"><?php echo esc_html($role); ?></p>
                        <?php endif; ?>
                        <?php if ($short_bio) : ?>
                            <p><?php echo esc_html($short_bio); ?></p>
                        <?php else : ?>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 20, '...')); ?></p>
                        <?php endif; ?>
                        <a class="text-link" href="<?php the_permalink(); ?>">
                            <?php esc_html_e('View profile', 'goody'); ?>
                            <?php echo goody_svg('arrow'); ?>
                        </a>
                    </div>
                </article>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    </div>
</section>
