<?php
if (goody_get_option('news_enabled', '0') !== '1') {
    return;
}

$posts_count = absint(goody_get_option('news_posts_count', '3'));
if ($posts_count < 1) {
    $posts_count = 3;
}
$posts_count = min(8, $posts_count);

$news_query = goody_get_posts([
    'post_type' => 'post',
    'post_status' => 'publish',
    'posts_per_page' => $posts_count,
    'orderby' => 'date',
    'order' => 'DESC',
]);

$news_title = goody_get_option('news_section_title', __('Restaurant News', 'goody'));
$news_label = goody_get_option('news_eyebrow_text', __('News', 'goody'));
$news_text = goody_get_option('news_section_text', '');
$news_button_text = goody_get_option('news_button_text', __('Read all news', 'goody'));
$news_read_more_text = goody_get_option('news_read_more_text', __('Read more', 'goody'));
$news_button_url = goody_get_option('news_button_url', '');
$news_empty_title = goody_get_option('news_empty_title', __('No news posts yet', 'goody'));
$news_empty_text = goody_get_option('news_empty_text', __('Publish blog posts from WordPress dashboard to show this section.', 'goody'));
$has_news_posts = $news_query->have_posts();
?>
<section id="news" class="page-section news-zone">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php echo esc_html($news_label); ?></span>
            <h2><?php echo esc_html($news_title); ?></h2>
            <?php if ($news_text) : ?>
                <p><?php echo esc_html($news_text); ?></p>
            <?php endif; ?>
        </header>

        <?php if ($has_news_posts) : ?>
            <div class="archive-grid archive-grid--three news-grid">
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>
                    <article <?php post_class('card news-card'); ?>>
                        <a href="<?php the_permalink(); ?>" class="news-card__image" aria-label="<?php the_title_attribute(); ?>">
                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('goody-card', ['sizes' => '(max-width: 767px) 46vw, 240px']); ?>
                            <?php else : ?>
                                <span class="news-card__placeholder"><?php esc_html_e('Goody News', 'goody'); ?></span>
                            <?php endif; ?>
                        </a>

                        <div class="news-card__content">
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date(get_option('date_format'))); ?></time>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt() ?: get_the_content(), 22, '...')); ?></p>
                            <a class="text-link" href="<?php the_permalink(); ?>">
                                <?php echo esc_html($news_read_more_text); ?>
                                <?php echo goody_svg('arrow'); ?>
                            </a>
                        </div>
                    </article>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        <?php else : ?>
            <div class="card empty-state">
                <h3><?php echo esc_html($news_empty_title); ?></h3>
                <p><?php echo esc_html($news_empty_text); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($news_button_url && $has_news_posts) : ?>
            <div class="news-actions">
                <a class="button button--outline" href="<?php echo esc_url($news_button_url); ?>"><?php echo esc_html($news_button_text); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>
