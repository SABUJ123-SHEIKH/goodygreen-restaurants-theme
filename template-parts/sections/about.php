<?php
$about_featured_image_id = absint(goody_get_option('about_featured_image', 0));
$first_image = $about_featured_image_id > 0 ? wp_get_attachment_image_url($about_featured_image_id, 'large') : '';

$interior_ids = goody_get_gallery_ids('about_interior_gallery');
if ($first_image === '' && ! empty($interior_ids)) {
    $first_image = wp_get_attachment_image_url($interior_ids[0], 'large');
}
$about_title = goody_get_option('about_story_title', __('More than a brunch, an experience', 'goody'));
?>
<section id="about" class="page-section about-zone">
    <div class="container split about-layout">
        <div class="card about-visual" <?php if ($first_image) : ?>style="background-image:url('<?php echo esc_url($first_image); ?>');"<?php endif; ?>>
            <span class="about-year">2025</span>
        </div>

        <div>
            <header class="section-heading">
                <span class="eyebrow"><?php esc_html_e('Our story', 'goody'); ?></span>
                <h2><?php echo esc_html($about_title); ?></h2>
                <p><?php echo esc_html(goody_get_option('about_story_text', '')); ?></p>
            </header>

            <div class="about-box-grid">
                <article class="card about-box">
                    <h3><?php echo esc_html(goody_get_option('about_mission_title', __('Our mission', 'goody'))); ?></h3>
                    <p><?php echo esc_html(goody_get_option('about_mission_text', '')); ?></p>
                </article>
                <article class="card about-box">
                    <h3><?php echo esc_html(goody_get_option('about_vision_title', __('Our vision', 'goody'))); ?></h3>
                    <p><?php echo esc_html(goody_get_option('about_vision_text', '')); ?></p>
                </article>
                <article class="card about-box">
                    <h3><?php esc_html_e('Premium quality', 'goody'); ?></h3>
                    <p><?php esc_html_e('Fresh ingredients and signature plating in every dish.', 'goody'); ?></p>
                </article>
                <article class="card about-box">
                    <h3><?php esc_html_e('Loved by guests', 'goody'); ?></h3>
                    <p><?php esc_html_e('A trusted destination for brunch, dinner and family moments.', 'goody'); ?></p>
                </article>
            </div>
        </div>
    </div>
</section>
