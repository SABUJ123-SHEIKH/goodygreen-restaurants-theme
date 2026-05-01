<?php
$offer_id = get_the_ID();
$discount = get_post_meta($offer_id, 'goody_offer_discount_label', true);
$start = get_post_meta($offer_id, 'goody_offer_start_date', true);
$end = get_post_meta($offer_id, 'goody_offer_end_date', true);
$type = get_post_meta($offer_id, 'goody_offer_type', true);
$linked = (array) get_post_meta($offer_id, 'goody_offer_linked_menu_items', true);
?>
<article <?php post_class('card offer-card'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a class="offer-card__image" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('goody-card', ['sizes' => '(max-width: 767px) 46vw, 240px']); ?></a>
    <?php endif; ?>

    <div class="offer-card__content">
        <div class="menu-card__top">
            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
            <?php if ($discount) : ?><span class="badge"><?php echo esc_html($discount); ?></span><?php endif; ?>
        </div>
        <p><?php echo esc_html(get_the_excerpt()); ?></p>

        <div class="offer-meta">
            <?php if ($type) : ?><span><?php echo esc_html(ucfirst($type)); ?></span><?php endif; ?>
            <?php if ($start) : ?><span><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($start))); ?></span><?php endif; ?>
            <?php if ($end) : ?><span><?php esc_html_e('to', 'goody'); ?> <?php echo esc_html(date_i18n(get_option('date_format'), strtotime($end))); ?></span><?php endif; ?>
            <?php if (! empty($linked)) : ?><span><?php echo esc_html(count($linked)); ?> <?php esc_html_e('menu items', 'goody'); ?></span><?php endif; ?>
        </div>
        <a class="text-link" href="<?php the_permalink(); ?>">
            <?php esc_html_e('View offer', 'goody'); ?>
            <?php echo goody_svg('arrow'); ?>
        </a>
    </div>
</article>
