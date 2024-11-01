<?php
/**
 * Admin Functionality For The Totally Booked Plugin
 *
 * @package totally-booked
 */

class TotallyBookedAdmin{

    var $pagehook;

    /**
     * Initialise The Object And Sets Up The Hooks.
     *
     * @since 0.1
     */
    function __construct(){

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );

        add_action( 'admin_init', array( $this, 'settings_init' ) );

        add_action( 'admin_init', array( $this, 'admin_enqueues' ) );

        add_action( 'tb_series_edit_form_fields', array( $this, 'add_taxonomy_form_fields' ), 10, 2 );
        add_action( 'tb_genre_edit_form_fields' , array( $this, 'add_taxonomy_form_fields' ), 10, 2 );

        //return apply_filters( "manage_{$this->screen->taxonomy}_custom_column", '', $column_name, $tag->term_id );
        add_filter( 'manage_tb_series_custom_column', array( $this, 'manage_series_custom_column_content' ), 10, 3 );
        add_filter( 'manage_tb_genre_custom_column' , array( $this, 'manage_genre_custom_column_content'  ), 10, 3 );

        //add_filter( "manage_{$this->screen->id}_columns", array( &$this, 'get_columns' ), 0 );
        add_filter( 'manage_edit-tb_series_columns', array( $this, 'manage_tax_custom_columns' ), 10 );
        add_filter( 'manage_edit-tb_genre_columns' , array( $this, 'manage_tax_custom_columns'  ), 10 );

    }

    /**
     * Adds In The Admin Menu
     *
     * @since 0.1
     */
    public function admin_menu(){
        //add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' )
        $this->pagehook = add_submenu_page('edit.php?post_type=tb_book', __( 'Totally Booked Settings', 'totally-booked' ), __( 'Update Settings', 'totally-booked' ), apply_filters( 'tb_options_capability', 'manage_options' ), 'tb_settings', array( $this, 'generate_admin_options' ) );
    }

    /**
     * Generates The Admin options HTML
     *
     * @since 0.1
     */
    public function generate_admin_options(){

        include TB_ABSPATH . 'lib/admin_templates/options.php';

    }

    /**
     * Initialises The Options Via The Settings API
     *
     * @since 0.1
     */
    public function settings_init(){

        /**
         * Add The Settings Sections
         */
        add_settings_section( 'tb_general_options'     , __( 'Post Type / Taxonomy Archive Titles', 'totally-booked' ), __return_false()                         , 'tb_general'      );
        add_settings_section( 'tb_rewrite_options'     , __( 'Rewrite Options', 'totally-booked' )                    , array( $this, 'rewrite_intro_text' )     , 'tb_advanced'     );
        add_settings_section( 'tb_cssjs_options'       , __( 'CSS And Javascript Options', 'totally-booked' )         , __return_false()                         , 'tb_advanced'     );
        add_settings_section( 'tb_archive_options'     , __( 'Archive Display Options', 'totally-booked' )            , __return_false()                         , 'tb_advanced'     );
        add_settings_section( 'tb_popup_options'       , __( 'Popup Display Options', 'totally-booked' )              , __return_false()                         , 'tb_advanced'     );
        add_settings_section( 'tb_theme_compat_options', __( 'Theme Compatibility Options', 'totally-booked' )        , array( $this, 'theme_compat_intro_text' ), 'tb_theme_compat' );

        /**
         * Register The Settings
         */
        register_setting( 'tb_general_options', 'author_archive_title' );
        register_setting( 'tb_general_options', 'genre_archive_title'  );
        register_setting( 'tb_general_options', 'series_archive_title' );
        register_setting( 'tb_general_options', 'book_archive_title'   );

        register_setting( 'tb_advanced', 'book_archive_slug'         );
        register_setting( 'tb_advanced', 'book_author_slug'          );
        register_setting( 'tb_advanced', 'book_genre_slug'           );
        register_setting( 'tb_advanced', 'book_series_slug'          );
        register_setting( 'tb_advanced', 'tb_output_js'              );
        register_setting( 'tb_advanced', 'tb_output_css'             );
        register_setting( 'tb_advanced', 'tb_archive_posts_per_page' );
        register_setting( 'tb_advanced', 'tb_hide_local_bookstore'   );

        register_setting( 'tb_theme_compat', 'tb_wrapper_start' );
        register_setting( 'tb_theme_compat', 'tb_wrapper_end'   );


        /**
         * General Options
         */
        $args = array(
            'name' => 'author_archive_title',
            'description' => __( 'Enter the title for the author archive, Leave this blank to default to the authors name' , 'totally-booked' )
        );
        add_settings_field( 'author_archive_title', __( 'Author Archive Title', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_general', 'tb_general_options', $args );

        $args = array(
            'name' => 'genre_archive_title',
            'description' => __( 'Enter the title for the genre archive, Leave this blank to default to the genre name' , 'totally-booked' )
        );
        add_settings_field( 'genre_archive_title', __( 'Genre Archive Title', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_general', 'tb_general_options', $args );

        $args = array(
            'name' => 'series_archive_title',
            'description' => __( 'Enter the title for the series archive, Leave this blank to default series name' , 'totally-booked' )
        );
        add_settings_field( 'series_archive_title', __( 'Series Archive Title', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_general', 'tb_general_options', $args );

        $args = array(
            'name' => 'book_archive_title',
            'description' => __( 'Enter the title for the book archive, Leave this blank to default to "Book Archives"' , 'totally-booked' )
        );
        add_settings_field( 'book_archive_title', __( 'Books Archive Title', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_general', 'tb_general_options', $args );

        /**
         * Rewrite Options
         */
        $args = array(
            'name' => 'book_archive_slug',
            'description' => __( 'Slug for the books archive page' , 'totally-booked' )
        );
        add_settings_field( 'book_archive_slug', __( 'Books Archive Slug', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_advanced', 'tb_rewrite_options', $args );

        $args = array(
            'name' => 'book_author_slug',
            'description' => __( 'Slug for the author archives' , 'totally-booked' )
        );
        add_settings_field( 'book_author_slug', __( 'Author Archive Slug', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_advanced', 'tb_rewrite_options', $args );

        $args = array(
            'name' => 'book_genre_slug',
            'description' => __( 'Slug for the genre archive' , 'totally-booked' )
        );
        add_settings_field( 'book_genre_slug', __( 'Genre Archive Slug', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_advanced', 'tb_rewrite_options', $args );

        $args = array(
            'name' => 'book_series_slug',
            'description' => __( 'Slug for the series archives' , 'totally-booked' )
        );
        add_settings_field( 'book_series_slug', __( 'Series Archive Slug', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_advanced', 'tb_rewrite_options', $args );

        /**
         * CSS / JS Options
         */
        $args = array(
            'name' => 'tb_output_js',
            'description' => __( 'Uncheck this option to disable javascript output (Not recommended unless you know what you are doing)' , 'totally-booked' )
        );
        add_settings_field( 'tb_output_js', __( 'Output Javascript', 'totally-booked' ), array( $this, 'option_checkbox' ), 'tb_advanced', 'tb_cssjs_options', $args );

        $args = array(
            'name' => 'tb_output_css',
            'description' => __( 'Uncheck this option to disable CSS output (Not recommended unless you know what you are doing)' , 'totally-booked' )
        );
        add_settings_field( 'tb_output_css', __( 'Output CSS', 'totally-booked' ), array( $this, 'option_checkbox' ), 'tb_advanced', 'tb_cssjs_options', $args );

        /**
         * Archive Settings
         */
        $args = array(
            'name' => 'tb_archive_posts_per_page',
            'description' => __( 'Please enter the maximum number of posts to display per page on the Totally Booked archive pages. Leave this blank to revert to the WordPress default.' , 'totally-booked' )
        );
        add_settings_field( 'tb_archive_posts_per_page', __( 'Archive Maximum Posts', 'totally-booked' ), array( $this, 'option_textfield' ), 'tb_advanced', 'tb_archive_options', $args );

        /**
         * Popup Display Settings
         */
        $args = array(
            'name' => 'tb_hide_local_bookstore',
            'description' => __( 'Check this to remove the local bookstore finder from the Buy Now popup.' , 'totally-booked' )
        );
        add_settings_field( 'tb_hide_local_bookstore', __( 'Hide the local bookstore finder in the popup?', 'totally-booked' ), array( $this, 'option_checkbox' ), 'tb_advanced', 'tb_popup_options', $args );


        /**
         * Theme Compat Options
         */
        $args = array(
            'name' => 'tb_wrapper_start',
            'description' => __( 'Enter the HTML snippet to output before the start of the Totally Booked content.' , 'totally-booked' )
        );
        add_settings_field( 'tb_wrapper_start', __( 'Wrapper Start HTML', 'totally-booked' ), array( $this, 'option_textarea' ), 'tb_theme_compat', 'tb_theme_compat_options', $args );

        $args = array(
            'name' => 'tb_wrapper_end',
            'description' => __( 'Enter the HTML snippet to output after the Totally Booked content.' , 'totally-booked' )
        );
        add_settings_field( 'tb_wrapper_end', __( 'Wrapper End HTML', 'totally-booked' ), array( $this, 'option_textarea' ), 'tb_theme_compat', 'tb_theme_compat_options', $args );


    }

    /**
     * Delegate script enqueuing for wp-admin.
     */
    function admin_enqueues(){

        add_action( 'admin_print_styles', array( $this, 'global_admin_styles' ) );

        add_action( 'admin_print_scripts-post-new.php', array( $this, 'post_admin_script' ) );
        add_action( 'admin_print_scripts-post.php'    , array( $this, 'post_admin_script' ) );

        add_action( 'admin_print_styles-post-new.php' , array( $this, 'post_admin_styles' ) );
        add_action( 'admin_print_styles-post.php'     , array( $this, 'post_admin_styles' ) );
        add_action( 'admin_print_styles-edit-tags.php', array( $this, 'post_admin_styles' ) );

        add_action( 'admin_print_scripts-' . $this->pagehook, array( $this, 'post_admin_script' ) );
        add_action( 'admin_print_styles-' . $this->pagehook , array( $this, 'post_admin_styles'  ) );

    }

    /**
     * Enqueue the global admin styles
     */
    public function global_admin_styles(){
        wp_enqueue_style( 'tb_admin_global_css', TB_URI . 'assets/css/tb_admin_global.css' );
    }

    /**
     * Enqueue the required scripts for wp-admin.
     */
    public function post_admin_script(){
        global $post_type;

        if( $post_type === 'tb_book' )
            wp_enqueue_script( 'tb_admin_js', TB_URI . 'assets/js/tb_admin.js' );

    }

    /**
     * Enqueue the required styles for wp-admin.
     */
    public function post_admin_styles(){
        global $post_type;

        if( $post_type === 'tb_book' )
            wp_enqueue_style( 'tb_admin_css', TB_URI . 'assets/css/tb_admin.css' );

    }

    /**
     * Output A Text Field For The Admin Options
     *
     * @since 0.1
     * @uses get_option
     * @uses esc_attr
     */
    public function option_textfield( $args ){

        $value = get_option( $args['name'] );

        echo '<input type="text" name="' . esc_attr( $args['name'] ) . '" value="' . esc_attr( $value ) . '" />';

        if( $args['description'] && ! empty( $args['description'] ) )
            echo '<br /><span class="description">' . esc_html( $args['description'] ) . '</span>';

    }

    /**
     * Output a checkbox for the admin options
     *
     * @since 0.2
     * @param $args
     */
    public function option_checkbox( $args ){

        $value = get_option( $args['name'] );

        $checked = ( $value && $value == 1 ) ? 'checked="checked"' : '' ;

        echo '<input ' . $checked . ' type="checkbox" name="' . esc_attr( $args['name'] ) . '" value="1" />';

        if( $args['description'] && ! empty( $args['description'] ) )
            echo '<br /><span class="description">' . esc_html( $args['description'] ) . '</span>';

    }

    /**
     * Output a textarea for the admin options
     *
     * @since 0.2
     * @param $args
     */
    public function option_textarea( $args ){

        $value = get_option( $args['name'] );

        echo '<textarea rows="5" cols="80" name="' . esc_attr( $args['name'] ) . '">' . esc_textarea( $value ) . '</textarea>';

        if( $args['description'] && ! empty( $args['description'] ) )
            echo '<br /><span class="description">' . esc_html( $args['description'] ) . '</span>';

    }

    /**
     * Add The shortcode text to the taxonomy edit screens
     *
     * @param $tag
     * @param $taxonomy
     * @return bool
     * @since 0.1
     */
    public function add_taxonomy_form_fields( $tag, $taxonomy ){

        switch( $taxonomy ):

            case 'tb_series':
                $code = 'tb_series_gallery';
                $name = __( 'series', 'totally-booked' );
                break;

            case 'tb_genre':
                $code = 'tb_genre_gallery';
                $name = __( 'genre', 'totally-booked' );
                break;

            default:
                return false;
                break;

        endswitch;


        echo '<tr class="form-field">';
            echo '<td colspan="2">';
                echo '<p>' . sprintf( __( 'To display a gallery of the books in this %s, Please enter the following shortcode in any post or page.', 'totally-booked' ), $name ) . '<br /><code>[' . $code . ' slug="' . esc_attr( $tag->slug ) . '"]</code></p>';
            echo '</td>';
        echo '</tr>';

    }

    /**
     * Generate the content for the tb_series shortcode column
     *
     * @param $null
     * @param $column_name
     * @param $term_id
     * @since 0.1
     */
    public function manage_series_custom_column_content( $null, $column_name, $term_id ){

        $term = get_term( $term_id, 'tb_series' );

        if( $column_name == 'shortcode' )
            echo '<code>[tb_series_gallery slug="' . esc_attr( $term->slug ) . '"]</code>';

    }

    /**
     * Add the custom shortcode column to the taxonomy list table.
     *
     * @param $cols
     * @return mixed
     * @since 0.1
     */
    public function manage_tax_custom_columns( $cols ){

        $cols['shortcode'] = __( 'Shortcode', 'totally-booked' );

        return $cols;

    }

    /**
     * Generate the content for the tb_genre shortcode column
     *
     * @param $null
     * @param $column_name
     * @param $term_id
     * @since 0.1
     */
    public function manage_genre_custom_column_content( $null, $column_name, $term_id ){

        $term = get_term( $term_id, 'tb_genre' );

        if( $column_name == 'shortcode' )
            echo '<code>[tb_genre_gallery slug="' . esc_attr( $term->slug ) . '"]</code>';

    }


    /**
     * Generates the intro text for the rewrite slugs options section.
     *
     * @since 0.2
     */
    public function rewrite_intro_text(){

        echo '<p>';
            echo sprintf( __( '
            <strong>These options are meant for the power user and should be used with caution. Adjusting the settings below will modify the core URL for the sections generated by the plugin.</strong><br /><br />

            This rewrite option is helpful for users who, for example, have an existing books page and don\'t want that page overwritten by the plugin.  In this case, you could enter something like "mybooks" in the "Books Archive Slug" field below. This will ensure that any books you add through TotallyBooked will only appear under the URL "mybooks", while any content you have on a “books” page, will not be affected.<br /><br />

            Your primary books page produced by TotallyBooked will then be located at: %s instead of %s, and all the individual books pages created by TotallyBooked will also appear under that “mybooks” heading.<br /><br />

            We strongly recommend that you do NOT modify these values more than once. Any modification once you’ve set them up the first time will change the URLs for all your books pages.  This will break your original links, and generate errors in the search engines.<br /><br />

            If you must change these values, be sure to use a redirection plugin, to 301 redirect the old book URLs to the new ones.', 'totally-booked' ),
                trailingslashit( get_bloginfo( 'url' ) ) . 'mybooks' ,
                trailingslashit( get_bloginfo( 'url' ) ) . 'books'
            );
        echo '</p>';

    }

    public function theme_compat_intro_text(){

        echo '<p>';
            _e( 'We have coded the TotallyBooked plugin to be as compatible as possible with most WordPress themes.  However, because there are thousands of themes, and every theme is coded differently, our system may run into conflicts with theme code once in awhile.<br /><br />

            The extra compatibility options on this page will help you integrate TotallyBooked with ANY theme, (even the one created by that web guy who runs his office from the local bar and doesn’t respond to any of your requests for updates!)<br /><br />

            The two text boxes below represent the HTML before and after the output generated by TotallyBooked. Using these boxes, you can tell TotallyBooked where to display in your theme’s content area.<br /><br />

            The code for these content areas is different for each theme.  So, for example, if you are using the default WordPress theme (Twenty Thirteen) you would put this in the "Wrapper Start HTML" text box:
            <pre>
    &lt;div id="primary" class="site-content"&gt;
        &lt;div id="content" role="main" class="entry-content twentythirteen"&gt;
            </pre>

            and this in the "Wrapper End HTML" box:
            <pre>
        &lt;/div&gt;
    &lt;/div&gt;
            </pre>

            Once you have the correct code in place, click the Save Options button. (Or, if you’re comfortable modifying your site code directly, you can skip this step and follow our <a href="http://wordpress.org/plugins/totally-booked/other_notes/">templating documentation</a> instead.)<br /><br />

            <strong>If you are not comfortable dealing with this level of code, please ask someone who is qualified to help. Making a mistake here will cause the pages generated by TotallyBooked to display in unpredictable ways.</strong>
            ', 'totally-booked' );
        echo '</p>';

    }

}
