<?php
/**
 * Theme override: WooCommerce checkout form.
 */

defined('ABSPATH') || exit;

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
do_action('woocommerce_before_checkout_form', $checkout);

if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}

$provider = function_exists('goody_get_cart_delivery_provider') ? goody_get_cart_delivery_provider() : '';
$provider_choices = function_exists('goody_get_delivery_provider_choices') ? goody_get_delivery_provider_choices() : [];
if (empty($provider_choices)) {
    $provider_choices = [
        'restaurant_delivery' => __('Delivery', 'goody'),
        'pickup' => __('Pickup', 'goody'),
    ];
}
if ($provider === '' || ! isset($provider_choices[$provider])) {
    $provider = array_key_first($provider_choices);
}
$estimate_map = [
    'pickup' => __('15–25 min', 'goody'),
    'restaurant_delivery' => __('30–45 min', 'goody'),
    'ubereats' => __('30–45 min', 'goody'),
];
$default_estimate = isset($estimate_map[$provider]) ? $estimate_map[$provider] : __('30–45 min', 'goody');
$posted_note = isset($_POST['goody_customer_note']) ? sanitize_textarea_field(wp_unslash($_POST['goody_customer_note'])) : '';
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout goody-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
  <div class="goody-checkout__layout">
    <div class="goody-checkout__main">
      <section class="goody-checkout-card">
        <header class="goody-checkout-card__head">
          <span class="goody-checkout-card__icon" aria-hidden="true">1</span>
          <div>
            <h3><?php esc_html_e('Customer Information', 'goody'); ?></h3>
            <p><?php esc_html_e('Please provide your details', 'goody'); ?></p>
          </div>
        </header>
        <div class="goody-checkout-card__body">
          <div id="customer_details" class="goody-checkout-fields">
            <?php do_action('woocommerce_checkout_billing'); ?>
          </div>
        </div>
      </section>

      <section class="goody-checkout-card">
        <header class="goody-checkout-card__head">
          <span class="goody-checkout-card__icon" aria-hidden="true">2</span>
          <div>
            <h3><?php esc_html_e('Order Method', 'goody'); ?></h3>
            <p><?php esc_html_e('How would you like to receive your order?', 'goody'); ?></p>
          </div>
        </header>
        <div class="goody-checkout-card__body">
          <input type="hidden" name="goody_delivery_provider" value="<?php echo esc_attr($provider); ?>" data-goody-provider-input>
          <div class="goody-order-methods" role="radiogroup" aria-label="<?php esc_attr_e('Order method', 'goody'); ?>">
            <?php foreach ($provider_choices as $choice_key => $choice_label) : ?>
              <?php
                $choice_key = sanitize_key((string) $choice_key);
                $is_active = $provider === $choice_key;
                $eta_text = isset($estimate_map[$choice_key]) ? $estimate_map[$choice_key] : __('30–45 min', 'goody');
                $icon_markup = '';
                if (in_array($choice_key, ['pickup'], true)) {
                    $icon_markup = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 7h12l1 4v7H5v-7l1-4zm2 2-.5 2h9L16 9H8zm1.5 6a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm5 0a1.5 1.5 0 100 3 1.5 1.5 0 000-3z"/></svg>';
                } else {
                    $icon_markup = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 18.5A2.5 2.5 0 119 21a2.5 2.5 0 01-2.5-2.5zm9 0A2.5 2.5 0 1118 21a2.5 2.5 0 01-2.5-2.5zM3 5h11.2a1 1 0 01.9.6l1.8 4.1h2.3c.6 0 1.1.3 1.5.8l1.8 2.5c.3.3.4.7.4 1.1v2.4h-2.1a3.6 3.6 0 00-6.8 0h-1.6a3.6 3.6 0 00-6.8 0H3V5zm2 2v7.4h1.6a3.6 3.6 0 016.8 0h1.9l-1.4-3.3H9.4V9.7h3.9L12.5 7H5zm13.3 4.7l1.5 2h1.1l-1.4-2h-1.2z"/></svg>';
                }
              ?>
              <?php if ($is_active) {?>
              <button type="button" class="goody-order-method<?php echo $is_active ? ' is-active' : ''; ?>" data-goody-provider-option="<?php echo esc_attr($choice_key); ?>" data-goody-provider-eta="<?php echo esc_attr($eta_text); ?>" role="radio" aria-checked="<?php echo $is_active ? 'true' : 'false'; ?>">
                <span class="goody-order-method__icon" aria-hidden="true"><?php echo wp_kses($icon_markup, ['svg' => ['viewBox' => true, 'aria-hidden' => true], 'path' => ['d' => true]]); ?></span>
                <strong><?php echo esc_html($choice_label); ?></strong>
                <small><?php echo esc_html($eta_text); ?></small>
              </button>
              <?php } ?>
            <?php endforeach; ?>
          </div>
          <p class="goody-order-method-selected"><?php esc_html_e('Delivery provider:', 'goody'); ?> <strong data-goody-provider-selected-label></strong></p>
        </div>
      </section>

      <section class="goody-checkout-card">
        <header class="goody-checkout-card__head">
          <span class="goody-checkout-card__icon" aria-hidden="true">3</span>
          <div>
            <h3><?php esc_html_e('Special Request', 'goody'); ?></h3>
            <p><?php esc_html_e('Any special request? Optional', 'goody'); ?></p>
          </div>
        </header>
        <div class="goody-checkout-card__body">
          <textarea class="goody-checkout-note" name="goody_customer_note" rows="3" placeholder="<?php esc_attr_e('Any special request? Optional', 'goody'); ?>"><?php echo esc_textarea($posted_note); ?></textarea>
        </div>
      </section>

      <section class="goody-checkout-card goody-checkout-card--soft">
        <header class="goody-checkout-card__head">
          <span class="goody-checkout-card__icon" aria-hidden="true">4</span>
          <div>
            <h3><?php esc_html_e('Estimated Time', 'goody'); ?></h3>
            <p><?php esc_html_e('Your order will be ready in approximately', 'goody'); ?> <strong data-goody-estimate><?php echo esc_html($default_estimate); ?></strong></p>
          </div>
        </header>
      </section>
    </div>

    <aside class="goody-checkout__sidebar">
      <div class="goody-checkout-summary-card">
        <h3 class="goody-checkout-summary-card__title"><?php esc_html_e('Order Summary', 'goody'); ?></h3>
        <?php if (wc_coupons_enabled()) : ?>
          <div class="goody-checkout-summary-card__coupon">
            <?php
              ob_start();
              woocommerce_checkout_coupon_form();
              $coupon_html = (string) ob_get_clean();
              $coupon_html = preg_replace('#<br\s*/?>#i', '', $coupon_html);
              echo $coupon_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
          </div>
        <?php endif; ?>
        <div id="order_review" class="woocommerce-checkout-review-order">
          <?php do_action('woocommerce_checkout_order_review'); ?>
        </div>
      </div>
    </aside>
  </div>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
