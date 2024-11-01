<?php
/**
Plugin Name: Totally Booked
Version: 0.6
Description: A plugin to help authors and book affiliates list their books quickly and easily in any WordPress website
Author: Ben Casey
Plugin URI: http://wordpress.org/extend/plugins/totally-booked/
Text Domain: totally-booked
Domain Path: /lang

FILTERS:
    tb_options_capability
    author_rewrite_slug
    genre_rewrite_slug
    series_rewrite_slug
    book_rewrite_slug
    tb_buynow_button_text
    tb_find_store_popup_text
    tb_book_shortcode_template_part
    tb_series_shortcode_template_part
    tb_genre_shortcode_template_part
*/
/**
 * Class TotallyBooked
 *
 * @package totally-booked
 */
class TotallyBooked{

    /**
     * Administration Object
     */
    var $admin = null;

    /**
     * Templating
     */
    var $templating = null;

    /**
     * Base Constructor
     *
     * @since 0.1
     */
    function __construct(){

        //Include The Lib Files
        $this->lib_includes();

        //Run The Initial Setup Function
        $this->initial_setup();

        //Setup The Actions
        $this->setup_actions();

        //Setup The Filters
        $this->setup_filters();

    }

    /**
     * Run The Initial Setup Functionality
     *
     * @since 0.1
     */
    public function initial_setup(){

        register_activation_hook( __FILE__, array( 'TotallyBooked', 'activate' ) );

        register_deactivation_hook( __FILE__, array( 'TotallyBooked', 'deactivate' ) );


        //Some Path Defines
        define( 'TB_ABSPATH' , trailingslashit( WP_PLUGIN_DIR . '/' . str_replace(basename( __FILE__ ) , "" , plugin_basename( __FILE__ ) ) ) );
        define( 'TB_URI'     , trailingslashit( WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__ ) , "" , plugin_basename( __FILE__ ) ) ) );

    }

    /**
     * Includes The Lib Files
     *
     * @since 0.1
     */
    public function lib_includes(){

        require_once 'lib/post_types.php';
        require_once 'lib/admin.php';
        require_once 'lib/templating.php';
        require_once 'lib/template_tags.php';
        require_once 'lib/shortcodes.php';
        require_once 'lib/widgets.php';

    }

    /**
     * Sets Up The WordPress Actions Required For The Plugin To Function.
     *
     * @since 0.1
     * @uses add_action
     */
    public function setup_actions(){

        //Init Functionality
        add_action( 'init', array( $this, 'init' ) );

        //Init Functionality
        add_action( 'plugins_loaded' ,array( $this, 'plugins_loaded' ) );

        //Pre Get posts
        add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

        //Wrapper Start
        add_action( 'tb_wrapper_start', array( $this, 'wrapper_start' ) );

        //Wrapper End
        add_action( 'tb_wrapper_end', array( $this, 'wrapper_end' ) );

    }

    /**
     * Sets Up The WordPress Filters
     *
     * @since 0.1
     */
    public function setup_filters(){

        //Filter The Permalink HTML
        add_filter( 'get_sample_permalink_html', array( $this, 'filter_permalink_html' ), 10, 4 );

        add_filter( 'wpseo_metabox_prio', array( $this, 'filter_wpseo_metabox_prio' ) );
    }

    /**
     * Function To Run On Init
     *
     * @since 0.1
     */
    public function init(){

        //Initialise The Post Types And Taxonomies
        new TotallyBookedPostTypes();

        //Setup The Enqueues
        $this->plugin_enqueues();

    }

    /**
     * Function To Run On plugins_loaded
     *
     * @since 0.1
     */
    public function plugins_loaded(){

        //Load Up The I18n
        load_plugin_textdomain( 'totally-booked', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

        // Initialise The Administration Functionality
        $this->admin = new TotallyBookedAdmin();

        //Initialise The Templating
        $this->templating = new TotallyBookedTemplating();

    }

    /**
     * Plugin Activation
     *
     * Flushes The Rewrite Rules after checking and handling an existing books page.
     *
     * @since 0.1
     */
    public static function activate(){
        global $wp_rewrite;

        /*
         * Test for an existing books page, if one exists, set the option for the books slug to be "tb-books" instead of "books"
         */
        $args = array(
            'name'        => 'books',
            'post_type'   => 'page',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $pages = get_posts( $args );


        if( $pages && ! empty( $pages ) ){

            $old_opt = get_option( 'book_archive_slug' );

            if( ! $old_opt || empty( $old_opt ) )
                update_option( 'book_archive_slug', 'tb-books' );

        }

        /*
         * Test For previous JS / CSS Options, If none, Add them in.
         */
        $opts = array(
            'tb_output_css' => 1,
            'tb_output_js'  => 1
        );

        foreach( $opts as $opt => $val ){
            if( get_option( $opt ) === false )
                update_option( $opt, $val );
        }


        //Flush The Rewrite Rules
        new TotallyBookedPostTypes();
        $wp_rewrite->flush_rules();

    }

    /**
     * Deactivation Function
     *
     * @since 0.1
     */
    public static function deactivate(){

    }

    /**
     * Sets Up The Script And Style Enqueues For The Frontend.
     *
     * @since 0.1
     */
    public function plugin_enqueues(){

        if( is_admin() ) return;

        $output_css = get_option( 'tb_output_css' );
        if( is_string( $output_css ) && $output_css === '1' ){
            wp_enqueue_style( 'totally-booked-css', TB_URI . 'assets/css/totally-booked.css' );
        }

        $output_js = get_option( 'tb_output_js' );
        if( is_string( $output_js ) && $output_js === '1' ){
            wp_enqueue_script( 'totally-booked-js', TB_URI . 'assets/js/totally-booked.js', array( 'jquery' ) );
        }

    }

    /**
     * Filters The Permalink HTML To Add In The Shortcode Snippet.
     *
     * @param $return
     * @param $id
     * @return string
     * @since 0.1
     */
    public function filter_permalink_html( $return, $id ){

        if( get_post_type() !== 'tb_book' ) return $return;

        return $return . '<br />' . __( 'Shortcode:', 'totally-booked' ) . '<code>[tb_book id="' . esc_attr( $id ) . '"]</code>';

    }

    /**
     * Moves The WPSEO Metabox Lower.
     *
     * @return string
     * @since 0.1
     */
    public function filter_wpseo_metabox_prio(){
        return 'low';
    }

    /**
     * Runs Pre Get Posts Functionality.
     *
     * @param $query
     * @since 0.1
     */
    public function pre_get_posts( $query ){

        /**
         * Add Support for simple page ordering.
         */
        if( class_exists( 'Simple_Page_Ordering' ) ){
            if( is_post_type_archive( 'tb_book' ) || is_tax( 'tb_genre' ) || is_tax( 'tb_series' ) || is_tax( 'tb_author' ) ){
                $query->set( 'orderby' , 'menu_order' );
                $query->set( 'order'   , 'ASC' );
            }
        }

        /**
         * Initialise the max posts option
         */
        $maxposts = get_option( 'tb_archive_posts_per_page' );
        if( $maxposts && $maxposts > 0 ){

            if( is_post_type_archive( 'tb_book' ) || is_tax( array( 'tb_genre', 'tb_series', 'tb_author' ) ) ){
                $query->set( 'posts_per_page', (int)$maxposts );
            }

        }

    }

    /**
     * Returns an array of the link meta field details.
     *
     * @return array
     */
    public function get_book_link_meta_details(){

        return array(
            'amazon_url'          => array(
                'title'       => __( 'Amazon URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/amazon_icon.jpg',
                'popup_class' => 'amazon'
            ),
            'audible_url'         => array(
                'title'       => __( 'Audible URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/audible_icon.jpg',
                'popup_class' => 'audible'
            ),
            'barnes_noble_url'    => array(
                'title'       => __( 'Barnes And Noble URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/barnesandnoble_icon.jpg',
                'popup_class' => 'barnes_noble'
            ),
            'books_a_million_url' => array(
                'title'       => __( 'Books A Million URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/booksamillion_icon.jpg',
                'popup_class' => 'booksamillion'
            ),
            'christian_books_url' => array(
                'title'       => __( 'Christianbook URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/christianbook_icon.jpg',
                'popup_class' => 'christian_books'
            ),
            'googleplay_url'      => array(
                'title'       => __( 'Google Play URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/googleplay_icon.jpg',
                'popup_class' => 'googleplay'
            ),
            'indiebound_url'      => array(
                'title'       => __( 'Indiebound URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/indiebound_icon.jpg',
                'popup_class' => 'indiebound'
            ),
            'itunes_url'          => array(
                'title'       => __( 'Itunes URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/itunes_icon.jpg',
                'popup_class' => 'itunes'
            ),
            'kobo_url'            => array(
                'title'       => __( 'Kobo URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/kobo_icon.jpg',
                'popup_class' => 'kobo'
            ),
            'smashwords_url'      => array(
                'title'       => __( 'Smashwords URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/smashwords_icon.jpg',
                'popup_class' => 'itunes'
            ),
            'sony_url'            => array(
                'title'       => __( 'Sony Reader URL', 'totally-booked' ),
                'icon_url'    => TB_URI . 'assets/images/sony_icon.jpg',
                'popup_class' => 'sonyreader'
            )
        );

    }

    /**
     * Output the HTML in the options page if any entered instead of looking for a template file.
     *
     * @since 0.2
     */
    public function wrapper_start(){

        $wrapper_start = get_option( 'tb_wrapper_start' );
        if( $wrapper_start && ! empty( $wrapper_start ) ){
            echo $wrapper_start;
        } else {
            tb_get_template_part( 'parts/wrapper_start' );
        }

    }

    /**
     * Output the HTML in the options page if any entered instead of looking for a template file.
     *
     * @since 0.2
     */
    public function wrapper_end(){

        $wrapper_end = get_option( 'tb_wrapper_end' );
        if( $wrapper_end && ! empty( $wrapper_end ) ){
            echo $wrapper_end;
        } else {
            tb_get_template_part( 'parts/wrapper_end' );
        }

    }

}
$GLOBALS['TotallyBooked'] = new TotallyBooked();