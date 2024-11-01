<?php
/**
 * Totally Booked Archive Template
 *
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */
get_header(); ?>

    <?php do_action( 'tb_wrapper_start' ); ?>

        <header class="archive-header">
            <h1 class="archive-title"><?php tb_archive_title() ?></h1>
        </header><!-- .archive-header -->

        <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

            tb_get_template_part( 'loops/archive' );

        endwhile; endif; ?>

    <?php do_action( 'tb_wrapper_end' ); ?>

<?php get_footer(); ?>