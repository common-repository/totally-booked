<?php
/**
 * Buy Now Popup Template
 * 
 * @version 0.1
 * @package totally-booked
 */

?>
<div class="tb_buynow_wrapper">

    <a class="tb_button tb_buynow_button" href="javascript:void(0)"><?php echo apply_filters( 'tb_buynow_button_text', __( 'Buy Now', 'totally-booked' ) ); ?></a>

    <div class="tb_buynow_content">

        <a class="close_buynow_popup"></a>

        <div class="buynow_section">

            <h4 class="section_title"><?php echo apply_filters( 'tb_buy_online_popup_title', __( 'Buy This Book Online', 'totally-booked' ) ); ?></h4>

            <div class="retailer_links">

                <?php foreach( $GLOBALS['popup_items'] as $item ){ ?>
                    <div class="retailer_link" class="<?php echo esc_attr( $item['class'] ); ?>'">
                        <a target="_blank" href="<?php echo esc_attr( $item['url'] ); ?>"><img src="<?php echo esc_attr( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['class'] ); ?>" title="<?php echo esc_attr( $item['class'] ); ?>" /></a>
                    </div>
                <?php } ?>

            </div>

            <div class="book_cover">
                <?php if( has_post_thumbnail( get_the_ID() ) ) echo get_the_post_thumbnail( get_the_ID() ); ?>
            </div>

            <div class="tb_clear"></div>

        </div>

        <?php if( ! get_option( 'tb_hide_local_bookstore' ) ) : ?>

            <div class="find_retailer_section">

                <h4 class="section_title"><?php echo apply_filters( 'tb_find_bookstore_popup_title', __( 'Find A Local Bookstore', 'totally-booked' ) ); ?></h4>

                <form class="tb_find_bookstore_form" action="http://maps.google.com/maps">
                    <div style="text-align: center; padding-bottom:20px;">
                        <input type="text" class="clear_on_focus city" data-original_value="City" value="City" name="city" size="20" />,
                        <input type="text" class="clear_on_focus state" data-original_value="State" value="State" name="state" size="4" maxlength="4" />
                        <input type="text" class="clear_on_focus zip" data-original_value="Zip" value="Zip" name="zip" size="5" maxlength="5" />
                        <input class="tb_button" type="submit" name="submit" value="<?php echo apply_filters( 'tb_find_store_popup_text', __( 'Find Store', 'totally-booked' ) ); ?>" /><br /><br /><img src="<?php echo TB_URI; ?>/assets/images/google_logo.gif" />
                    </div>
                </form>

            </div>

        <?php endif; ?>

    </div>

</div>