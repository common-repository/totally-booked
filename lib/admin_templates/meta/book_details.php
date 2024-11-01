<?php
/**
 * HTML For The Book Details Meta Box
 *
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

global $post;

//Define The Metas Here
$metas = array(
    'series_title',
    'coming_soon_text',
    'isbn_number'
);

//Extract Them From The Database
foreach( $metas as $meta ){
    ${$meta} = get_post_meta( $post->ID, $meta, true );
}
?>
<p>
    <?php _e( 'Is your book part of a series? Is the book not yet released, but coming soon? Enter those details below if you\'d like them to appear, leave the boxes blank if not.'  ); ?>
</p>

<p>
    <label for="series_title"><strong><?php _e( 'Enter Your Series Title And Book Number in any order (for example: Courageous Series - Book 1 or Book 1 - The Courageous Series)', 'totally-booked' ); ?></strong></label><br />
    <input type="text" name="series_title" value="<?php esc_attr_e( $series_title ); ?>" />
</p>

<p>
    <label for="coming_soon_text"><strong><?php _e( 'Enter Your Coming Soon Text (for example: Coming May, 2014 from XYZ Publishers or Releasing Fall 2013)', 'totally-booked' ); ?></strong></label><br />
    <input type="text" name="coming_soon_text" value="<?php esc_attr_e( $coming_soon_text ); ?>" />
</p>

<p>
    <label for="isbn_number"><strong><?php _e( 'Enter The ISBN Number For Your Book', 'totally-booked' ); ?></strong></label><br />
    <input type="text" name="isbn_number" value="<?php esc_attr_e( $isbn_number ); ?>" />
</p>