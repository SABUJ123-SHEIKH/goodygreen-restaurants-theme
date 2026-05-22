<?php
$requested_order_id = goody_get_tracking_order_id();
$requested_order_key = goody_get_tracking_order_key();
$tracking_state = goody_get_tracking_state(true, $requested_order_id, $requested_order_key);
$tracking_steps = goody_get_tracking_steps($tracking_state);
$tracking_timeline = is_array($tracking_state['timeline'] ?? null) ? $tracking_state['timeline'] : [];
$tracking_page_url = goody_get_tracking_page_url();
$tracking_consignment = trim((string) ($tracking_state['consignment_id'] ?? ''));
if ($tracking_consignment === '') {
    $tracking_consignment = trim((string) ($tracking_state['order_id'] ?? ''));
}
$tracking_order_id = trim((string) ($tracking_state['order_id'] ?? $requested_order_id));
$tracking_order_key = trim((string) ($tracking_state['order_key'] ?? $requested_order_key));
$tracking_url = goody_normalize_url_input((string) ($tracking_state['url'] ?? ''));
$tracking_is_external = goody_is_external_url($tracking_url);
$tracking_message = trim((string) ($tracking_state['message'] ?? ''));

if (empty($tracking_timeline) && $tracking_message !== '') {
    $tracking_timeline[] = [
        'stage' => trim((string) ($tracking_state['stage'] ?? '')),
        'title' => trim((string) ($tracking_state['status'] ?? __('Tracking Update', 'goody'))),
        'description' => $tracking_message,
        'time' => '',
        'completed' => true,
    ];
}

$shipping_name = trim((string) ($tracking_state['shipping_name'] ?? ''));
$shipping_phone = trim((string) ($tracking_state['shipping_phone'] ?? ''));
$shipping_address = trim((string) ($tracking_state['shipping_address'] ?? ''));
$payment_amount = trim((string) ($tracking_state['payment_amount'] ?? ''));
$payment_method = trim((string) ($tracking_state['payment_method'] ?? ''));
$customer_orders = [];
if (is_user_logged_in() && function_exists('wc_get_orders')) {
    $customer_orders = wc_get_orders([
        'customer_id' => get_current_user_id(),
        'limit' => 12,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
}
?>
<?php get_header(); ?>

<section class="page-section tracking-page">
    <div class="container">
        <div class="card tracking-shell">
            <nav class="tracking-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'goody'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'goody'); ?></a>
                <span>&rsaquo;</span>
                <a href="<?php echo esc_url($tracking_page_url); ?>"><?php esc_html_e('Tracking', 'goody'); ?></a>
                <span>&rsaquo;</span>
                <strong><?php esc_html_e('Delivery Details', 'goody'); ?></strong>
            </nav>

            <form class="tracking-search-form" action="<?php echo esc_url($tracking_page_url); ?>" method="get">
                <label>
                    <span><?php esc_html_e('Order ID', 'goody'); ?></span>
                    <input type="text" name="order_id" value="<?php echo esc_attr($tracking_order_id); ?>" placeholder="<?php esc_attr_e('Enter order ID', 'goody'); ?>">
                </label>
                <label>
                    <span><?php esc_html_e('Order Key', 'goody'); ?></span>
                    <input type="text" name="key" value="<?php echo esc_attr($tracking_order_key); ?>" placeholder="<?php esc_attr_e('Order key (optional)', 'goody'); ?>">
                </label>
                <button class="button" type="submit"><?php esc_html_e('Track Order', 'goody'); ?></button>
            </form>

            <div class="tracking-info-grid">
                <div class="tracking-info-row">
                    <span class="tracking-info-label"><?php esc_html_e('Consignment ID', 'goody'); ?></span>
                    <strong class="tracking-info-value">
                        <?php if ($tracking_consignment !== '') : ?>
                            <span data-tracking-consignment-value><?php echo esc_html($tracking_consignment); ?></span>
                            <button class="goody-track-copy" type="button" data-copy="<?php echo esc_attr($tracking_consignment); ?>"><?php esc_html_e('Copy', 'goody'); ?></button>
                        <?php else : ?>
                            <span data-tracking-consignment-value><?php esc_html_e('Not available', 'goody'); ?></span>
                        <?php endif; ?>
                    </strong>
                </div>

                <div class="tracking-info-row">
                    <span class="tracking-info-label"><?php esc_html_e('Shipping Info', 'goody'); ?></span>
                    <div class="tracking-info-value tracking-info-value--stack" data-tracking-shipping-value>
                        <?php if ($shipping_phone !== '') : ?><strong><?php echo esc_html($shipping_phone); ?></strong><?php endif; ?>
                        <?php if ($shipping_name !== '') : ?><strong><?php echo esc_html($shipping_name); ?></strong><?php endif; ?>
                        <?php if ($shipping_address !== '') : ?><span><?php echo esc_html($shipping_address); ?></span><?php endif; ?>
                        <?php if ($shipping_phone === '' && $shipping_name === '' && $shipping_address === '') : ?><span><?php esc_html_e('Not available', 'goody'); ?></span><?php endif; ?>
                    </div>
                </div>

                <div class="tracking-info-row">
                    <span class="tracking-info-label"><?php esc_html_e('Payment Details', 'goody'); ?></span>
                    <div class="tracking-info-value tracking-info-value--stack" data-tracking-payment-value>
                        <?php if ($payment_amount !== '') : ?><strong><?php echo esc_html($payment_amount); ?></strong><?php endif; ?>
                        <?php if ($payment_method !== '') : ?><strong><?php echo esc_html($payment_method); ?></strong><?php endif; ?>
                        <?php if ($payment_amount === '' && $payment_method === '') : ?><span><?php esc_html_e('Not available', 'goody'); ?></span><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="tracking-box tracking-box--primary tracking-box--page" data-goody-tracking-box data-tracking-base="<?php echo esc_attr(trim((string) goody_get_option('tracking_description', ''))); ?>">
                <div class="tracking-box__head">
                    <h4><?php esc_html_e('Tracking Details', 'goody'); ?></h4>
                    <?php if ($tracking_url !== '') : ?>
                        <a class="button button--ghost" data-goody-tracking-link href="<?php echo esc_url($tracking_url); ?>" <?php if ($tracking_is_external) : ?>target="_blank" rel="noopener"<?php endif; ?>><?php esc_html_e('Provider Link', 'goody'); ?></a>
                    <?php endif; ?>
                </div>
                <p data-goody-tracking-text><?php echo esc_html($tracking_message !== '' ? $tracking_message : __('Enter order ID to load tracking status.', 'goody')); ?></p>
            </div>

            <div class="tracking-steps-wrap">
                <ol class="tracking-steps">
                    <?php foreach ($tracking_steps as $step) : ?>
                        <?php
                        $step_classes = ['tracking-step'];
                        if (! empty($step['done'])) {
                            $step_classes[] = 'is-done';
                        }
                        if (! empty($step['active'])) {
                            $step_classes[] = 'is-active';
                        }
                        ?>
                        <li class="<?php echo esc_attr(implode(' ', $step_classes)); ?>" data-tracking-step="<?php echo esc_attr((string) ($step['key'] ?? '')); ?>">
                            <span class="tracking-step__dot" aria-hidden="true"></span>
                            <span class="tracking-step__label"><?php echo esc_html((string) ($step['label'] ?? '')); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>

            <div class="tracking-timeline" data-goody-tracking-timeline>
                <?php if (! empty($tracking_timeline)) : ?>
                    <?php foreach ($tracking_timeline as $event) : ?>
                        <?php
                        $event_title = trim((string) ($event['title'] ?? ''));
                        $event_desc = trim((string) ($event['description'] ?? ''));
                        $event_time = trim((string) ($event['time'] ?? ''));
                        $event_class = ! empty($event['completed']) ? 'is-done' : '';
                        ?>
                        <article class="tracking-event <?php echo esc_attr($event_class); ?>">
                            <span class="tracking-event__dot" aria-hidden="true"></span>
                            <div class="tracking-event__content">
                                <?php if ($event_title !== '') : ?><h3><?php echo esc_html($event_title); ?></h3><?php endif; ?>
                                <?php if ($event_desc !== '') : ?><p><?php echo esc_html($event_desc); ?></p><?php endif; ?>
                                <?php if ($event_time !== '') : ?><time><?php echo esc_html($event_time); ?></time><?php endif; ?>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="card empty-state">
                        <h3><?php esc_html_e('No tracking events yet', 'goody'); ?></h3>
                        <p><?php esc_html_e('Enter a valid order ID to see live updates.', 'goody'); ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (! empty($customer_orders)) : ?>
                <div class="tracking-orders-list">
                    <h3><?php esc_html_e('My Recent Orders', 'goody'); ?></h3>
                    <div class="tracking-orders-table-wrap">
                        <table class="tracking-orders-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Order', 'goody'); ?></th>
                                    <th><?php esc_html_e('Date', 'goody'); ?></th>
                                    <th><?php esc_html_e('Status', 'goody'); ?></th>
                                    <th><?php esc_html_e('Provider', 'goody'); ?></th>
                                    <th><?php esc_html_e('Track', 'goody'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($customer_orders as $customer_order) : ?>
                                    <?php if (! class_exists('WC_Order') || ! ($customer_order instanceof WC_Order)) { continue; } ?>
                                    <?php
                                    $order_provider = sanitize_text_field((string) $customer_order->get_meta('_goody_delivery_provider', true));
                                    if ($order_provider === '') {
                                        $order_provider = sanitize_text_field((string) $customer_order->get_meta('delivery_provider', true));
                                    }
                                    if ($order_provider === '') {
                                        $order_provider = __('Goody', 'goody');
                                    }
                                    ?>
                                    <tr>
                                        <td>#<?php echo esc_html($customer_order->get_order_number()); ?></td>
                                        <td><?php echo esc_html(goody_format_tracking_datetime($customer_order->get_date_created())); ?></td>
                                        <td><?php echo esc_html(wc_get_order_status_name($customer_order->get_status())); ?></td>
                                        <td><?php echo esc_html($order_provider); ?></td>
                                        <td><a class="button button--ghost tracking-orders-table__button" href="<?php echo esc_url(goody_get_tracking_page_url((string) $customer_order->get_id(), (string) $customer_order->get_order_key())); ?>"><?php esc_html_e('Open', 'goody'); ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_footer(); ?>
