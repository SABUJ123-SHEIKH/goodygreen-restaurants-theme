<?php get_header(); ?>
<section class="page-section">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php esc_html_e('Search Results', 'goody'); ?></span>
            <h1><?php printf(esc_html__('Results for: %s', 'goody'), esc_html(get_search_query())); ?></h1>
        </header>

        <?php if (have_posts()) : ?>
            <div class="archive-grid archive-grid--three">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $post_type = get_post_type();
                    $template = locate_template('template-parts/cards/content-' . $post_type . '.php');
                    if ($template) {
                        get_template_part('template-parts/cards/content', $post_type);
                    } else {
                        get_template_part('template-parts/cards/content');
                    }
                    ?>
                <?php endwhile; ?>
            </div>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <div class="card empty-state">
                <h3><?php esc_html_e('No results found', 'goody'); ?></h3>
                <p><?php esc_html_e('Try another keyword to search menu items, offers, events, and pages.', 'goody'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php get_footer(); ?>
