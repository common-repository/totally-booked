<?php
/**
 * Single Book Template For The Totally Booked Plugin
 * 
 * @version 0.1
 * @package totally-booked
 */

get_header(); ?>

    <?php do_action( 'tb_wrapper_start' ); ?>

        <?php the_post(); ?>
        <?php tb_get_template_part( 'loops/single_book' ); ?>

    <?php do_action( 'tb_wrapper_end' ); ?>

<?php get_footer(); ?>
