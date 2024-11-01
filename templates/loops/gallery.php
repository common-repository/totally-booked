<?php
/**
 * Gallery template for the Totally Booked Plugin.
 * 
 * @version 0.1
 * @package totally-booked
 */
?>
<div id="book_<?php the_ID(); ?>" <?php post_class( 'tb-gallery-item' ); ?>>

    <div class="entry-header">
        <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
    </div>

    <div class="post_thumbnail">
        <a href="<?php the_permalink(); ?>"><?php if( has_post_thumbnail() ) the_post_thumbnail( 'post-thumbnail', array( 'class' => 'alignleft' ) ); ?></a>
    </div>

    <div class="tb_archive_content_wrapper">

        <div class="tb-entry-actions">

            <?php echo tb_get_buynow_link(); ?>

            <a class="tb_button" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'totally-booked' ); ?></a>

            <div class="clear"></div>

        </div>

    </div>

    <div class="clear"></div>

</div>