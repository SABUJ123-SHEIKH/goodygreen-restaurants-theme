<section id="newsletter" class="page-section">
    <div class="container">
        <div class="newsletter card">
            <div>
                <span class="eyebrow"><?php esc_html_e('Email marketing', 'goody'); ?></span>
                <h2><?php echo esc_html(goody_get_option('newsletter_title', __('Be the first to know the news', 'goody'))); ?></h2>
                <p><?php echo esc_html(goody_get_option('newsletter_text', '')); ?></p>
            </div>
            <div class="newsletter__form">
                <?php $newsletter_message = goody_form_message('newsletter'); ?>
                <?php if ($newsletter_message) : ?>
                    <p class="form-notice"><?php echo esc_html($newsletter_message); ?></p>
                <?php endif; ?>
                <?php if (goody_get_option('newsletter_embed')) : ?>
                    <?php echo do_shortcode(goody_get_option('newsletter_embed')); ?>
                <?php else : ?>
                    <form class="newsletter-fallback" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="goody_newsletter_subscribe">
                        <?php wp_nonce_field('goody_newsletter_submit', 'goody_newsletter_nonce'); ?>
                        <input type="email" name="goody_newsletter_email" required placeholder="<?php esc_attr_e('Your email address', 'goody'); ?>">
                        <button type="submit" class="button"><?php esc_html_e('Subscribe', 'goody'); ?></button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
