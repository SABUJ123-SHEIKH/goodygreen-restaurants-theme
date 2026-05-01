<?php
if (goody_get_option('account_enabled', '0') !== '1') {
    return;
}

$account_title = goody_get_option('account_section_title', __('User Account', 'goody'));
$account_label = goody_get_option('account_eyebrow_text', __('Account', 'goody'));
$account_text = goody_get_option('account_section_text', '');
$account_placeholder_title = goody_get_option('account_placeholder_title', __('Placeholder Integration', 'goody'));
$account_placeholder_text = goody_get_option('account_placeholder_text', __('Use this block to connect login, profile, loyalty points, and saved delivery addresses with your preferred account system.', 'goody'));
$account_features = array_values(array_filter([
    goody_get_option('account_feature_text_1', __('Track order history and status', 'goody')),
    goody_get_option('account_feature_text_2', __('Save favorite dishes for fast reorder', 'goody')),
    goody_get_option('account_feature_text_3', __('Manage profile and delivery details', 'goody')),
]));
$account_actions_title = goody_get_option('account_actions_title', __('Quick Actions', 'goody'));
$account_login_text = goody_get_option('account_login_button_text', __('Login', 'goody'));
$account_register_text = goody_get_option('account_register_button_text', __('Create Account', 'goody'));
$account_profile_text = goody_get_option('account_profile_button_text', __('My Profile', 'goody'));
$account_empty_note = goody_get_option('account_empty_note_text', __('Set Login/Register/Profile URLs in Goody Green Settings > Account tab to activate buttons.', 'goody'));
$login_url = goody_get_option('account_login_url', '');
$register_url = goody_get_option('account_register_url', '');
$profile_url = goody_get_option('account_profile_url', '');
?>
<section id="account" class="page-section account-zone">
    <div class="container">
        <header class="section-heading">
            <span class="eyebrow"><?php echo esc_html($account_label); ?></span>
            <h2><?php echo esc_html($account_title); ?></h2>
            <?php if ($account_text) : ?>
                <p><?php echo esc_html($account_text); ?></p>
            <?php endif; ?>
        </header>

        <div class="archive-grid archive-grid--two account-grid">
            <article class="card account-card">
                <h3><?php echo esc_html($account_placeholder_title); ?></h3>
                <p><?php echo esc_html($account_placeholder_text); ?></p>
                <?php if (! empty($account_features)) : ?>
                    <ul>
                        <?php foreach ($account_features as $feature) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </article>

            <article class="card account-card">
                <h3><?php echo esc_html($account_actions_title); ?></h3>
                <div class="account-actions">
                    <?php if ($login_url) : ?>
                        <a class="button button--outline" href="<?php echo esc_url($login_url); ?>"><?php echo esc_html($account_login_text); ?></a>
                    <?php endif; ?>
                    <?php if ($register_url) : ?>
                        <a class="button button--outline" href="<?php echo esc_url($register_url); ?>"><?php echo esc_html($account_register_text); ?></a>
                    <?php endif; ?>
                    <?php if ($profile_url) : ?>
                        <a class="button button--outline" href="<?php echo esc_url($profile_url); ?>"><?php echo esc_html($account_profile_text); ?></a>
                    <?php endif; ?>
                </div>

                <?php if (! $login_url && ! $register_url && ! $profile_url) : ?>
                    <p class="account-note"><?php echo esc_html($account_empty_note); ?></p>
                <?php endif; ?>
            </article>
        </div>
    </div>
</section>
