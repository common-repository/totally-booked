<?php
/**
 * HTML For The Options Page
 *
 * @package totally-booked
 * @since 0.1
 */
$cur_page = ( isset( $_GET['sub_page'] ) ) ? $_GET['sub_page'] : 'general' ;

?>

<div class="wrap">
    <img src="<?php echo TB_URI . 'assets/images/tb_logo.jpg' ?>" alt="Totally Booked" title="Totally Booked" />

    <h2 class="nav-tab-wrapper">

        <?php
        $pages = array(
            'general'      => __( 'General Options', 'totally-booked' ),
            'advanced'     => __( 'Advanced Options', 'totally-booked' ),
            'theme-compat' => __( 'Theme Compatability', 'totally-booked' )
        );

        foreach( $pages as $page => $text ){
            $class = ( $cur_page == $page ) ? 'nav-tab nav-tab-active' : 'nav-tab' ;
            echo '<a href="' . esc_attr( add_query_arg( 'sub_page', $page ) ) . '" class="' . $class . '">' . $text . '</a>';
        }
        ?>

    </h2>

    <div class="options_wrapper" style="padding:20px 0">

        <form method="post" action="options.php">

            <?php switch( $cur_page ):

                case 'general':
                    include TB_ABSPATH . 'lib/admin_templates/pages/general.php';
                    break;

                case 'advanced':
                    include TB_ABSPATH . 'lib/admin_templates/pages/advanced.php';
                    break;

                case 'theme-compat':
                    include TB_ABSPATH . 'lib/admin_templates/pages/theme-compat.php';
                    break;

            endswitch; ?>

        </form>

        <p>
            <?php _e( 'Like This Plugin? You could consider a <a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7TW3H4AEX4U76">Paypal donation</a> or leave a <a target="_blank" href="http://wordpress.org/support/view/plugin-reviews/totally-booked">review on wordpress.org</a>', 'totally-booked' ); ?>
        </p>

    </div>

</div>