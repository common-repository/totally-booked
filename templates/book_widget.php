<?php
/**
 * Template for the book widget.
 * 
 * @version 0.1
 * @package totally-booked
 */
global $post;
?>
<div class="tb_book_widget">

    <div class="book_cover">
        <?php if( has_post_thumbnail() ) the_post_thumbnail(); ?>
    </div>

    <h4 class="book_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>

    <div class="book_content">

        <?php the_excerpt(); ?>

    </div>

    <div class="tb-entry-actions">

        <?php echo tb_get_buynow_link(); ?>

        <a class="tb_button" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'totally-booked' ); ?></a>

        <div class="tb_clear"></div>

    </div>

</div>