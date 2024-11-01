<?php
/**
 * Class TotallyBookedTemplating
 *
 * @since 0.1
 * @package totally-booked
 */
class TotallyBookedTemplating{


    /**
     * ....
     */
    function __construct(){

        add_filter( 'template_include', array( $this, 'template_include' ) );

    }

    /**
     * Template Include Filter
     *
     * Returns The Path To The Template Or The Default Template On Failure
     *
     * @param string $template
     * @return bool|string
     * @since 0.1
     */
    public function template_include( $template ){

        $_template = false;

        if( is_tax( 'tb_genre' ) || is_tax( 'tb_series' ) || is_tax( 'tb_author' ) || is_post_type_archive( 'tb_book' ) )
            $_template = self::locate_template( 'archives/archive.php' );

        if( is_single() && get_post_type() == 'tb_book' )
            $_template = self::locate_template( 'single/book.php' );

        if( $_template ) return $_template;

        return $template;

    }


    /**
     * Locates A Required Template.
     *
     * Returns the path to the template or false on failure.
     *
     * @param string|array $templates
     * @return bool|string
     * @since 0.1
     */
    public static function locate_template( $templates ){

        $path = false;

        foreach( (array)$templates as $template_file ){

            //Test In The Current Theme
            $maybe_path = locate_template( 'plugins/totally-booked/' . $template_file, false, false );

            //If We Have Something, Set The Path And Continue The Loop.
            if( $maybe_path && ! empty( $maybe_path ) ){
                $path = $maybe_path;
                continue;
            }

            //Otherwise Look In The Plugin Defaults.
            $plugin_path = TB_ABSPATH . 'templates/' . $template_file;
            if( file_exists( $plugin_path ) ){
                $path = $plugin_path;
            }

        }

        //Default Back To The Default.
        if( ! $path || ! file_exists( $path ) )
            return false;

        return $path;

    }

}