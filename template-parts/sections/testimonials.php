<?php
$selected_review_rating = function_exists('goody_get_selected_review_rating_filter') ? goody_get_selected_review_rating_filter() : 0;
$testimonial_args = [
    'post_type' => 'testimonial',
    'posts_per_page' => 6,
];
$testimonials = goody_get_posts($testimonial_args);
$average_rating = goody_get_average_rating();
$reviews_title = goody_get_option('reviews_section_title', __('What they say about Goody', 'goody'));
$reviews_text = goody_get_option('reviews_section_text', '');
$reviews_layout = goody_get_option('reviews_layout', 'grid');
$google_reviews = goody_get_google_reviews_data();
$google_review_items = $google_reviews['reviews'] ?? [];
if ($selected_review_rating > 0 && function_exists('goody_filter_reviews_by_rating')) {
    $google_review_items = goody_filter_reviews_by_rating($google_review_items, $selected_review_rating);
}
$google_rating = (float) ($google_reviews['rating'] ?? 0);
$google_count = (int) ($google_reviews['user_ratings_total'] ?? 0);
$website_reviews_count = isset($testimonials->found_posts) ? (int) $testimonials->found_posts : 0;
$total_reviews_count = max(0, $google_count) + max(0, $website_reviews_count);
$google_maps_url = $google_reviews['url'] ?? '';
$google_review_handoff_url = function_exists('goody_get_google_review_handoff_url') ? goody_get_google_review_handoff_url($google_reviews) : '';
$google_review_external_url = $google_review_handoff_url !== '' ? $google_review_handoff_url : $google_maps_url;
$google_handoff_enabled = goody_get_option('reviews_google_handoff_after_submit', '1') === '1' && $google_review_handoff_url !== '';
$reviews_source_label = trim((string) ($google_reviews['source_label'] ?? 'Google'));
if ($reviews_source_label === '') {
    $reviews_source_label = 'Google';
}
$review_count_label = $reviews_source_label;
if ($google_count > 0 && $website_reviews_count > 0) {
    $review_count_label = __('Google + Website', 'goody');
} elseif ($google_count < 1 && $website_reviews_count > 0) {
    $review_count_label = __('Website', 'goody');
}
$is_mock_reviews = ! empty($google_reviews['is_mock']);
$google_reviews_source = (string) goody_get_option('google_reviews_place_id', '');
$provider_key_hint = goody_get_effective_reviews_api_key('auto');
$reviews_provider = goody_get_reviews_provider($provider_key_hint, $google_reviews_source);
$google_reviews_api_key = goody_get_effective_reviews_api_key($reviews_provider);
$is_google_style_reviews_key = goody_is_google_api_key($google_reviews_api_key);
$trustpilot_api_url = goody_normalize_url_input(goody_get_option('trustpilot_api_url', ''));
$custom_reviews_api_url = goody_normalize_url_input(goody_get_option('custom_reviews_api_url', ''));
$is_cid_input = $google_reviews_source !== '' && goody_extract_google_place_id($google_reviews_source) === '' && goody_extract_google_cid($google_reviews_source) !== '';
$testimonial_form_message = goody_form_message('testimonial');
$testimonial_form_status = sanitize_key($_GET['goody_status'] ?? '');
$should_open_review_form = $testimonial_form_message !== '' && $testimonial_form_status !== 'success';
$display_rating = $google_rating > 0 ? $google_rating : $average_rating;
$aspect_summary = goody_get_aspect_ratings_summary();
$has_reviews_content = ! empty($google_review_items) || $testimonials->have_posts();

if (! in_array($reviews_layout, ['grid', 'carousel'], true)) {
    $reviews_layout = 'grid';
}
?>
<section
    id="reviews"
    class="page-section testimonials-zone"
    data-google-reviews-fallback="<?php echo $has_reviews_content ? '0' : '1'; ?>"
    data-google-reviews-provider="<?php echo esc_attr($reviews_provider); ?>"
    data-google-place-id="<?php echo esc_attr($google_reviews_source); ?>"
    data-google-reviews-count="<?php echo esc_attr((string) max(1, (int) goody_get_option('google_reviews_count', '6'))); ?>">
    <div class="container" data-review-open-default="<?php echo $should_open_review_form ? '1' : '0'; ?>">
        <div class="reviews-head">
            <header class="section-heading">
                <span class="eyebrow"><?php esc_html_e('Reviews', 'goody'); ?></span>
                <h2><?php echo esc_html($reviews_title); ?></h2>
                <?php if ($reviews_text) : ?>
                    <p><?php echo esc_html($reviews_text); ?></p>
                <?php endif; ?>
            </header>

            <?php if ($display_rating > 0) : ?>
                <div class="card review-score">
                    <strong><?php echo esc_html(number_format($display_rating, 1)); ?></strong>
                    <small>/ 5 stars</small>
                    <?php if ($total_reviews_count > 0) : ?>
                        <span class="review-score-meta"><?php echo esc_html(sprintf(_n('%1$d %2$s review', '%1$d %2$s reviews', $total_reviews_count, 'goody'), $total_reviews_count, $review_count_label)); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($testimonial_form_message) : ?>
            <p class="form-notice"><?php echo esc_html($testimonial_form_message); ?></p>
        <?php endif; ?>

        <?php if ($is_mock_reviews) : ?>
            <div class="card reviews-embed">
                <p><?php echo esc_html(sprintf(__('Mock review mode is enabled for testing. Turn it off to fetch live %s reviews.', 'goody'), $reviews_source_label)); ?></p>
            </div>
        <?php endif; ?>

        <?php if (! empty($aspect_summary)) : ?>
            <div class="review-aspects-grid">
                <?php foreach ($aspect_summary as $aspect) : ?>
                    <article class="card review-aspect-card">
                        <h3><?php echo esc_html($aspect['label']); ?></h3>
                        <strong><?php echo esc_html(number_format((float) $aspect['average'], 1)); ?></strong>
                        <small>/ 5</small>
                        <span><?php echo esc_html(sprintf(_n('%d rating', '%d ratings', (int) $aspect['count'], 'goody'), (int) $aspect['count'])); ?></span>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($has_reviews_content) : ?>
            <?php if ($reviews_layout === 'carousel') : ?>
                <div class="testimonials-nav">
                    <button type="button" class="button button--outline button--small" data-testimonial-prev><?php esc_html_e('Previous', 'goody'); ?></button>
                    <button type="button" class="button button--outline button--small" data-testimonial-next><?php esc_html_e('Next', 'goody'); ?></button>
                </div>
                <div class="testimonials-carousel">
                    <?php foreach ($google_review_items as $review) : ?>
                        <?php $review_rating = max(1, min(5, (int) ($review['rating'] ?? 5))); ?>
                        <article class="card testimonial-card testimonial-card--google" data-review-card data-review-rating="<?php echo esc_attr((string) $review_rating); ?>">
                            <div class="rating">
                                <?php for ($i = 0; $i < $review_rating; $i++) : ?>
                                    <span class="icon-star"><?php echo goody_svg('star'); ?></span>
                                <?php endfor; ?>
                            </div>
                            <div class="testimonial-card__content"><?php echo esc_html(wp_trim_words($review['text'], 32, '...')); ?></div>
                            <div class="testimonial-card__author">
                                <?php if (! empty($review['profile_photo_url'])) : ?>
                                    <div class="testimonial-avatar"><img src="<?php echo esc_url($review['profile_photo_url']); ?>" alt="<?php echo esc_attr($review['author_name']); ?>"></div>
                                <?php else : ?>
                                    <div class="testimonial-avatar testimonial-avatar--fallback"><span><?php echo esc_html(strtoupper(substr((string) $review['author_name'], 0, 1)) ?: 'G'); ?></span></div>
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo esc_html($review['author_name']); ?></strong>
                                    <span><?php echo esc_html($review['time_text'] ?: sprintf(__('%s review', 'goody'), $reviews_source_label)); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                        <?php get_template_part('template-parts/cards/content', 'testimonial'); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php else : ?>
                <div class="archive-grid archive-grid--three review-grid">
                    <?php foreach ($google_review_items as $review) : ?>
                        <?php $review_rating = max(1, min(5, (int) ($review['rating'] ?? 5))); ?>
                        <article class="card testimonial-card testimonial-card--google" data-review-card data-review-rating="<?php echo esc_attr((string) $review_rating); ?>">
                            <div class="rating">
                                <?php for ($i = 0; $i < $review_rating; $i++) : ?>
                                    <span class="icon-star"><?php echo goody_svg('star'); ?></span>
                                <?php endfor; ?>
                            </div>
                            <div class="testimonial-card__content"><?php echo esc_html(wp_trim_words($review['text'], 32, '...')); ?></div>
                            <div class="testimonial-card__author">
                                <?php if (! empty($review['profile_photo_url'])) : ?>
                                    <div class="testimonial-avatar"><img src="<?php echo esc_url($review['profile_photo_url']); ?>" alt="<?php echo esc_attr($review['author_name']); ?>"></div>
                                <?php else : ?>
                                    <div class="testimonial-avatar testimonial-avatar--fallback"><span><?php echo esc_html(strtoupper(substr((string) $review['author_name'], 0, 1)) ?: 'G'); ?></span></div>
                                <?php endif; ?>
                                <div>
                                    <strong><?php echo esc_html($review['author_name']); ?></strong>
                                    <span><?php echo esc_html($review['time_text'] ?: sprintf(__('%s review', 'goody'), $reviews_source_label)); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                    <?php while ($testimonials->have_posts()) : $testimonials->the_post(); ?>
                        <?php get_template_part('template-parts/cards/content', 'testimonial'); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <div class="card empty-state" data-google-reviews-empty-state>
                <h3><?php esc_html_e('No reviews available yet', 'goody'); ?></h3>
                <?php if ($reviews_provider === 'trustpilot' && $trustpilot_api_url === '') : ?>
                    <p><?php esc_html_e('Add Trustpilot Reviews API URL in Reviews tab.', 'goody'); ?></p>
                <?php elseif ($reviews_provider === 'custom' && $custom_reviews_api_url === '') : ?>
                    <p><?php esc_html_e('Add Custom Reviews API URL in Reviews tab.', 'goody'); ?></p>
                <?php elseif ($reviews_provider === 'serpapi' && $is_google_style_reviews_key) : ?>
                    <p><?php esc_html_e('SerpApi mode detected but current key looks like a Google key (AIza...). Add a real SerpApi key or switch provider to Google.', 'goody'); ?></p>
                <?php elseif ($reviews_provider === 'google' && $google_reviews_api_key !== '' && ! $is_google_style_reviews_key) : ?>
                    <p><?php esc_html_e('Google mode detected but current key does not look like a Google Places key. Use a key starting with AIza... or switch provider to SerpApi.', 'goody'); ?></p>
                <?php elseif ($reviews_provider === 'google' && $is_cid_input) : ?>
                    <p><?php esc_html_e('CID value detected (0x... format). Theme tried auto-resolve, but Google did not return reviews. Add full Maps place URL, Place ID (ChIJ...), or exact business name + address.', 'goody'); ?></p>
                <?php elseif (($reviews_provider === 'google' || $reviews_provider === 'serpapi') && $google_reviews_api_key === '') : ?>
                    <p><?php esc_html_e('Add Reviews API Key in Integrations tab to fetch Google/SerpApi reviews.', 'goody'); ?></p>
                <?php elseif ($selected_review_rating > 0) : ?>
                    <p><?php echo esc_html(sprintf(__('No %d-star reviews are available right now. Try All reviews.', 'goody'), $selected_review_rating)); ?></p>
                <?php else : ?>
                    <p><?php esc_html_e('Add testimonials from Dashboard or configure reviews source options in the Reviews tab.', 'goody'); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="card review-cta">
            <p><?php esc_html_e('Want to leave your feedback? We read every message and improve every day.', 'goody'); ?></p>
            <div class="review-cta__actions">
                <button type="button" class="button" data-open-review-form><?php esc_html_e('Submit a review', 'goody'); ?></button>
                <?php if ($google_review_external_url) : ?>
                    <a class="button button--outline" href="<?php echo esc_url($google_review_external_url); ?>" target="_blank" rel="noopener"><?php echo esc_html(sprintf(__('Write on %s', 'goody'), $reviews_source_label)); ?></a>
                <?php endif; ?>
            </div>
        </div>

        <div class="review-modal" data-review-modal hidden>
            <div class="review-modal__backdrop" data-close-review-form></div>
            <div class="review-modal__panel card" role="dialog" aria-modal="true" aria-labelledby="goody-review-form-title">
                <button type="button" class="review-modal__close" aria-label="<?php esc_attr_e('Close review form', 'goody'); ?>" data-close-review-form>&times;</button>
                <h3 id="goody-review-form-title"><?php esc_html_e('Share your experience', 'goody'); ?></h3>
                <?php if ($google_handoff_enabled) : ?>
                    <p><?php echo esc_html(sprintf(__('Your review will be saved locally, then %s will open so you can publish it there.', 'goody'), $reviews_source_label)); ?></p>
                <?php else : ?>
                    <p><?php esc_html_e('Your review will be saved as draft and published after admin approval.', 'goody'); ?></p>
                <?php endif; ?>

                <form class="review-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="goody_testimonial_submit">
                    <?php wp_nonce_field('goody_testimonial_submit', 'goody_testimonial_nonce'); ?>
                    <?php if ($google_handoff_enabled) : ?>
                        <input type="hidden" name="goody_testimonial_google_redirect" value="1">
                    <?php endif; ?>
                    <p class="screen-reader-text" aria-hidden="true">
                        <label for="goody_testimonial_website"><?php esc_html_e('Leave this field empty', 'goody'); ?></label>
                        <input type="text" id="goody_testimonial_website" name="goody_testimonial_website" value="" tabindex="-1" autocomplete="off">
                    </p>

                    <div class="review-form__grid">
                        <label class="review-form__full">
                            <span><?php esc_html_e('Order ID', 'goody'); ?></span>
                            <input type="text" name="goody_testimonial_order_id" data-review-order-id inputmode="numeric" pattern="[0-9]*" autocomplete="off" required>
                        </label>
                    </div>

                    <div class="review-form__actions">
                        <button type="button" class="button button--outline" data-review-order-unlock><?php esc_html_e('Continue', 'goody'); ?></button>
                    </div>
                    <p class="form-notice" data-review-order-message hidden aria-live="polite"></p>

                    <div data-review-fields hidden>
                        <div class="review-form__grid">
                            <label>
                                <span><?php esc_html_e('Name', 'goody'); ?></span>
                                <input type="text" name="goody_testimonial_name" required>
                            </label>
                            <label>
                                <span><?php esc_html_e('Email (optional)', 'goody'); ?></span>
                                <input type="email" name="goody_testimonial_email">
                            </label>
                            <label>
                                <span><?php esc_html_e('Overall Rating', 'goody'); ?></span>
                                <select name="goody_testimonial_rating" required>
                                    <option value=""><?php esc_html_e('Select rating', 'goody'); ?></option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </label>
                            <label>
                                <span><?php esc_html_e('Food Rating (optional)', 'goody'); ?></span>
                                <select name="goody_testimonial_food_rating">
                                    <option value="0"><?php esc_html_e('Skip', 'goody'); ?></option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </label>
                            <label>
                                <span><?php esc_html_e('Ambiance Rating (optional)', 'goody'); ?></span>
                                <select name="goody_testimonial_ambiance_rating">
                                    <option value="0"><?php esc_html_e('Skip', 'goody'); ?></option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </label>
                            <label>
                                <span><?php esc_html_e('Service Rating (optional)', 'goody'); ?></span>
                                <select name="goody_testimonial_service_rating">
                                    <option value="0"><?php esc_html_e('Skip', 'goody'); ?></option>
                                    <option value="5">5</option>
                                    <option value="4">4</option>
                                    <option value="3">3</option>
                                    <option value="2">2</option>
                                    <option value="1">1</option>
                                </select>
                            </label>
                            <label class="review-form__full">
                                <span><?php esc_html_e('Your Review', 'goody'); ?></span>
                                <textarea name="goody_testimonial_message" rows="5" required></textarea>
                            </label>
                            <label class="review-form__full">
                                <span><?php esc_html_e('Upload Image (optional)', 'goody'); ?></span>
                                <input type="file" name="goody_testimonial_image" accept="image/jpeg,image/png,image/gif,image/webp">
                            </label>
                        </div>

                        <div class="review-form__actions">
                            <button type="submit" class="button"><?php esc_html_e('Submit Review', 'goody'); ?></button>
                            <button type="button" class="button button--outline" data-close-review-form><?php esc_html_e('Cancel', 'goody'); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
