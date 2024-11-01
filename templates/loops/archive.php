<?php
/**
 * Archive Loop Template
 * 
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

?>
<div id="book_<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="post_thumbnail book_cover">
        <?php if( has_post_thumbnail() ) {
            echo '<a href="' . get_permalink( get_the_ID() ) . '">';
                the_post_thumbnail( 'post-thumbnail', array( 'class' => 'alignleft' ) );
            echo '</a>';
        }; ?>
    </div>

    <div class="tb_archive_content_wrapper">

        <div class="entry-header">
            <h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
        </div>

        <?php echo tb_get_coming_soon_text(); ?>

        <?php echo tb_get_reader_links(); ?>

        <div class="entry-content">
            <?php the_excerpt(); ?>
        </div>

        <div class="tb-entry-actions">

            <?php echo tb_get_buynow_link(); ?>

            <a class="tb_button" href="<?php the_permalink(); ?>"><?php _e( 'Read More', 'totally-booked' ); ?></a>

            <div class="clear"></div>

        </div>

    </div>

    <div class="clear"></div>

</div>