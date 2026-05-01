<?php
/*
Template Name: Reservation Page
*/

get_header();
?>
<main id="primary" class="site-main">
    <section class="goody-page-section goody-page-section--reservation">
        <div class="container">
            <?php while (have_posts()) : the_post(); ?>
                <?php
                $content = (string) get_the_content();
                if (has_shortcode($content, 'reservation_booking')) {
                    the_content();
                } else {
                    echo do_shortcode('[reservation_booking]');
                }
                ?>
            <?php endwhile; ?>
        </div>
    </section>
</main>
<?php
get_footer();
