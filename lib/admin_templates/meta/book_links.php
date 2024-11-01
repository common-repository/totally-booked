<?php
/**
 * The HTML Template For The Book Links Meta Box.
 *
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

global $post;

$metas = $GLOBALS['TotallyBooked']->get_book_link_meta_details();



?>
<p>
    <?php _e( 'Enter the online links to your book below! If you have an affiliate account, build the links in your affiliate account first, then copy and paste them into the applicable boxes on this page. To exclude a bookseller (not recommended) from the generated "Buy This Book" pop-up, simply leave the link box blank. If you want to link to the bookseller, but they don\'t yet have your book in their system, use their primary site url (eg: http://www.amazon.com)', 'totally-booked'  ); ?>
</p>

<?php

//Display The Meta Fields
foreach( $metas as $key => $meta ){
    $val = get_post_meta( $post->ID, $key, true );
    ?>
    <p>
        <label for="<?php esc_attr_e( $key ); ?>"><strong><?php echo $meta['title']; ?></strong></label><br />
        <input type="text" name="<?php esc_attr_e( $key ); ?>" value="<?php esc_attr_e( $val ); ?>" />
    </p>
<?php

}

?>

<input type="hidden" name="tb_nonce" value="<?php echo wp_create_nonce( 'tb_save_postmeta' ); ?>" />