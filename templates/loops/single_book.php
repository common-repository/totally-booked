<?php
/**
 * 
 * @version 0.1
 * @package totally-booked
 */
?>
<div id="book_<?php the_ID(); ?>" <?php post_class(); ?>>

    <div class="book_cover">

        <?php if( has_post_thumbnail() ) the_post_thumbnail(); ?>

        <div class="clear"></div>

        <div class="book_meta">

            <?php echo tb_get_buynow_link(); ?>

        </div>

    </div>

    <div class="book_content">

        <div class="entry-header">
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </div>

        <?php echo tb_get_coming_soon_text(); ?>

        <?php echo tb_get_reader_links(); ?>

        <div class="entry-content clear">

            <?php $title = get_post_meta( get_the_ID(), 'content_title', true ); if( ! $title ) $title = __( 'About This Book', 'totally-booked' ); ?>

            <h3 class="content-title"><?php esc_html_e( $title ); ?></h3>

            <?php the_content(); ?>

            <?php echo tb_get_entry_meta(); ?>

        </div>

    </div>

    <div class="tb_clear"></div>

</div>