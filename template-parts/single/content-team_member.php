<?php
$member_id = get_the_ID();
$section = goody_get_single_home_section('team_member');
$role = get_post_meta($member_id, 'goody_team_role', true);
$short_bio = get_post_meta($member_id, 'goody_team_short_bio', true);
?>
<section class="page-section cpt-single cpt-single--team">
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
                    <div class="cpt-single__placeholder"><?php esc_html_e('Team Member', 'goody'); ?></div>
                <?php endif; ?>
            </div>

            <div class="cpt-single__content">
                <span class="eyebrow"><?php esc_html_e('Team Member', 'goody'); ?></span>
                <h1><?php the_title(); ?></h1>
                <?php if ($role) : ?><p class="cpt-single__lead"><?php echo esc_html($role); ?></p><?php endif; ?>

                <div class="button-group cpt-single__actions">
                    <a class="button button--outline" href="<?php echo esc_url(home_url('/' . $section['anchor'])); ?>"><?php esc_html_e('Back to Team', 'goody'); ?></a>
                    <a class="button" href="<?php echo esc_url(home_url('/#contact')); ?>"><?php esc_html_e('Contact Us', 'goody'); ?></a>
                </div>
            </div>
        </article>

        <div class="cpt-single__grid">
            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Profile', 'goody'); ?></h2>
                <?php if ($short_bio) : ?><p><?php echo esc_html($short_bio); ?></p><?php endif; ?>
                <div class="cpt-single__body">
                    <?php the_content(); ?>
                </div>
            </div>

            <div class="card cpt-single__panel">
                <h2><?php esc_html_e('Role Information', 'goody'); ?></h2>
                <ul class="cpt-single__list">
                    <li><strong><?php esc_html_e('Name', 'goody'); ?>:</strong> <?php the_title(); ?></li>
                    <?php if ($role) : ?><li><strong><?php esc_html_e('Role', 'goody'); ?>:</strong> <?php echo esc_html($role); ?></li><?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
