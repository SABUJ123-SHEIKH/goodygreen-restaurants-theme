<?php
$reservation_url = goody_get_reservation_url();
$reservation_status_url = goody_get_reservation_status_lookup_page_url();
$status_url = $reservation_status_url !== '' ? $reservation_status_url : home_url('/');
$reservation_button_text = trim((string) goody_get_option('reservation_button_text', __('Reserve Now', 'goody')));
$reservation_embed = trim((string) goody_get_option('reservation_embed', ''));

$delivery_title = trim((string) goody_get_option('order_section_title', ''));
if ($delivery_title === '' || $delivery_title === 'Order & Delivery') {
    $delivery_title = 'Goody en|tu casa';
}
$delivery_title_parts = array_map('trim', explode('|', $delivery_title, 2));
$delivery_title_main = $delivery_title_parts[0] ?? $delivery_title;
$delivery_title_accent = $delivery_title_parts[1] ?? '';
if ($delivery_title_accent === '' && stripos($delivery_title_main, 'tu casa') !== false) {
    $delivery_title_main = trim((string) preg_replace('/tu casa/i', '', $delivery_title_main));
    $delivery_title_accent = 'tu casa';
}

$delivery_text = trim((string) goody_get_option('order_section_text', ''));
if ($delivery_text === '' || $delivery_text === 'Fast delivery partners and direct ordering in one place.') {
    $delivery_text = __('No hacemos reparto propio, pero puedes pedir a través de tus apps favoritas. Calidad Goody, cómodamente en casa.', 'goody');
}

$custom_order_label = trim((string) goody_get_option('custom_order_text', ''));
if ($custom_order_label === '' || $custom_order_label === 'Order Direct') {
    $custom_order_label = 'Just Eat';
}

$delivery_cards = [
    [
        'key' => 'glovo',
        'label' => 'Glovo',
        'eta' => '20-35 min',
        'icon' => '🛵',
        'tone' => 'yellow',
        'url' => goody_get_delivery_link('glovo'),
    ],
    [
        'key' => 'ubereats',
        'label' => 'Uber Eats',
        'eta' => '25-40 min',
        'icon' => '🚗',
        'tone' => 'dark',
        'url' => goody_get_delivery_link('ubereats'),
    ],
    [
        'key' => 'custom',
        'label' => $custom_order_label,
        'eta' => '30-45 min',
        'icon' => '🍔',
        'tone' => 'orange',
        'url' => goody_normalize_url_input((string) goody_get_option('custom_order_url', '')),
    ],
    [
        'key' => 'deliveroo',
        'label' => 'Deliveroo',
        'eta' => '25-35 min',
        'icon' => '🏍️',
        'tone' => 'green',
        'url' => goody_get_delivery_link('deliveroo'),
    ],
];

$fallback_delivery_url = $reservation_url ?: home_url('/#menu');
foreach ($delivery_cards as $index => $card) {
    if (trim((string) $card['url']) === '') {
        $delivery_cards[$index]['url'] = $fallback_delivery_url;
    }
}

$visual_icon = $delivery_cards[0]['icon'] ?? '🛵';
$reservation_title = trim((string) goody_get_option('reservation_section_title', __('Book a Table', 'goody')));
if ($reservation_title === '' || $reservation_title === 'Book a Table') {
    $reservation_title = 'Reserva tu|mesa';
}
$reservation_title_parts = array_map('trim', explode('|', $reservation_title, 2));
$reservation_title_main = $reservation_title_parts[0] ?? $reservation_title;
$reservation_title_accent = $reservation_title_parts[1] ?? '';

$reservation_text = trim((string) goody_get_option('reservation_section_text', ''));
if ($reservation_text === '' || $reservation_text === 'Reserve your table in seconds.') {
    $reservation_text = __('Elige fecha, hora y mesa en pocos pasos.', 'goody');
}

$available_days = function_exists('goody_get_available_booking_days') ? goody_get_available_booking_days() : [];
$next_day = ! empty($available_days[0]) && is_array($available_days[0]) ? $available_days[0] : [];
$next_day_display = trim((string) ($next_day['display'] ?? ''));
$today_hours = function_exists('goody_get_today_business_hours') ? goody_get_today_business_hours() : [];
$hours_text = trim((string) (($today_hours['open'] ?? '') . (($today_hours['open'] ?? '') !== '' || ($today_hours['close'] ?? '') !== '' ? ' - ' : '') . ($today_hours['close'] ?? '')));
?>
<section id="order" class="page-section reserve-zone reserve-zone--showcase">
    <div class="container reserve-showcase">
        <div class="reserve-delivery">
            <div class="reserve-delivery__copy">
                <span class="reserve-showcase-kicker"><?php esc_html_e('Delivery', 'goody'); ?></span>
                <h2>
                    <?php echo esc_html($delivery_title_main); ?>
                    <?php if ($delivery_title_accent !== '') : ?>
                        <em><?php echo esc_html($delivery_title_accent); ?></em>
                    <?php endif; ?>
                </h2>
                <p><?php echo esc_html($delivery_text); ?></p>

                <div class="reserve-delivery__apps">
                    <?php foreach ($delivery_cards as $card) : ?>
                        <?php
                        $card_url = goody_normalize_url_input((string) ($card['url'] ?? ''));
                        $is_external = goody_is_external_url($card_url);
                        ?>
                        <a class="reserve-delivery-card reserve-delivery-card--<?php echo esc_attr((string) $card['tone']); ?>" href="<?php echo esc_url($card_url); ?>" <?php if ($is_external) : ?>target="_blank" rel="noopener"<?php endif; ?>>
                            <span class="reserve-delivery-card__icon" aria-hidden="true"><?php echo esc_html((string) $card['icon']); ?></span>
                            <span>
                                <strong><?php echo esc_html((string) $card['label']); ?></strong>
                                <small><?php echo esc_html((string) $card['eta']); ?></small>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="reserve-delivery__visual" aria-hidden="true">
                <span class="reserve-delivery__ring reserve-delivery__ring--outer"></span>
                <span class="reserve-delivery__ring reserve-delivery__ring--inner"></span>
                <span class="reserve-delivery__vehicle"><?php echo esc_html($visual_icon); ?></span>
            </div>
        </div>

        <div class="reserve-booking">
            <div class="reserve-booking__copy">
                <span class="reserve-showcase-kicker"><?php esc_html_e('Reservas', 'goody'); ?></span>
                <h2>
                    <?php echo esc_html($reservation_title_main); ?>
                    <?php if ($reservation_title_accent !== '') : ?>
                        <em><?php echo esc_html($reservation_title_accent); ?></em>
                    <?php endif; ?>
                </h2>
                <p><?php echo esc_html($reservation_text); ?></p>
                <div class="reserve-booking__actions">
                    <?php if ($reservation_url) : ?>
                        <a class="button" href="<?php echo esc_url($reservation_url); ?>"><?php echo esc_html($reservation_button_text); ?></a>
                    <?php endif; ?>
                    <a class="button button--ghost" href="<?php echo esc_url($status_url); ?>"><?php esc_html_e('Check Status', 'goody'); ?></a>
                </div>
            </div>

            <aside class="reserve-booking__panel">
                <span><?php esc_html_e('Horario', 'goody'); ?></span>
                <strong><?php echo esc_html($hours_text !== '' ? $hours_text : __('Hours updating', 'goody')); ?></strong>
                <small><?php echo esc_html($next_day_display !== '' ? sprintf(__('Next booking: %s', 'goody'), $next_day_display) : __('Booking dates updating', 'goody')); ?></small>
            </aside>
        </div>

        <?php if ($reservation_embed !== '') : ?>
            <div class="reserve-showcase__embed">
                <?php echo do_shortcode($reservation_embed); ?>
            </div>
        <?php endif; ?>
    </div>
</section>
