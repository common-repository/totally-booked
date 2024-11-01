<?php
/**
 * Class To Handle The Creation And Management Of The Post Types And Taxonomies For The Totally Booked Plugin
 *
 * @version 0.1
 * @since 0.1
 * @package totally-booked
 */

class TotallyBookedPostTypes {

    /**
     * Sets Up The Taxonomies, And Post Types, Also Handles The Meta Fields
     *
     * @since 0.1
     */
    function __construct(){

        $this->SetupTaxonomies();

        $this->SetupPosttypes();

        add_action( 'save_post', array( $this, 'save_post' ) );

        add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );

    }

    /**
     * Initiate The Taxonomies
     *
     * @since 0.1
     * @uses register_taxonomy
     */
    public function SetupTaxonomies(){


        $opt = get_option( 'book_genre_slug' );
        $genre_slug = ( $opt && ! empty( $opt )  ) ? $opt : 'genre';

        register_taxonomy( 'tb_genre', array( 'tb_book' ), array(
            'hierarchical'            => true,
            'public'                  => true,
            'show_in_nav_menus'       => true,
            'show_ui'                 => true,
            'query_var'               => 'genre',
            'rewrite'                 => array( 'slug' => apply_filters( 'tb_genre_rewrite_slug', $genre_slug ), 'hierarchical' => false, 'with_front' > false  ),
            'labels'                  => array(
                'name'                       => __( 'Genres', 'totally-booked' ),
                'singular_name'              => __( 'Genre', 'totally-booked' ),
                'search_items'               => __( 'Search Genres', 'totally-booked' ),
                'popular_items'              => __( 'Popular Genres', 'totally-booked' ),
                'all_items'                  => __( 'All Genres', 'totally-booked' ),
                'parent_item'                => __( 'Parent Genre', 'totally-booked' ),
                'parent_item_colon'          => __( 'Parent Genre:', 'totally-booked' ),
                'edit_item'                  => __( 'Edit Genre', 'totally-booked' ),
                'update_item'                => __( 'Update Genre', 'totally-booked' ),
                'add_new_item'               => __( 'New Genre', 'totally-booked' ),
                'new_item_name'              => __( 'New Genre', 'totally-booked' ),
                'separate_items_with_commas' => __( 'Genres separated by comma', 'totally-booked' ),
                'add_or_remove_items'        => __( 'Add or remove Genres', 'totally-booked' ),
                'choose_from_most_used'      => __( 'Choose from the most used Genres', 'totally-booked' ),
                'menu_name'                  => __( 'Your Book Genres', 'totally-booked' ),
            ),
        ) );


        $opt = get_option( 'book_series_slug' );
        $series_slug = ( $opt && ! empty( $opt )  ) ? $opt : 'series';

        register_taxonomy( 'tb_series', array( 'tb_book' ), array(
            'hierarchical'            => true,
            'public'                  => true,
            'show_in_nav_menus'       => true,
            'show_ui'                 => true,
            'query_var'               => 'series',
            'rewrite'                 => array( 'slug' => apply_filters( 'tb_series_rewrite_slug', $series_slug ), 'hierarchical' => true, 'with_front' > false ),
            'labels'                  => array(
                'name'                       =>  __( 'Series', 'totally-booked' ),
                'singular_name'              =>  __( 'Series', 'totally-booked' ),
                'search_items'               =>  __( 'Search Series', 'totally-booked' ),
                'popular_items'              =>  __( 'Popular Series', 'totally-booked' ),
                'all_items'                  =>  __( 'All Series', 'totally-booked' ),
                'parent_item'                =>  __( 'Parent Series', 'totally-booked' ),
                'parent_item_colon'          =>  __( 'Parent Series:', 'totally-booked' ),
                'edit_item'                  =>  __( 'Edit Series', 'totally-booked' ),
                'update_item'                =>  __( 'Update Series', 'totally-booked' ),
                'add_new_item'               =>  __( 'New Series', 'totally-booked' ),
                'new_item_name'              =>  __( 'New Series', 'totally-booked' ),
                'separate_items_with_commas' =>  __( 'Series separated by comma', 'totally-booked' ),
                'add_or_remove_items'        =>  __( 'Add or remove Series', 'totally-booked' ),
                'choose_from_most_used'      =>  __( 'Choose from the most used Series', 'totally-booked' ),
                'menu_name'                  =>  __( 'Your Book Series', 'totally-booked' ),
            ),
        ) );


        $opt = get_option( 'book_author_slug' );
        $author_slug = ( $opt && ! empty( $opt )  ) ? $opt : 'tb-author';

        register_taxonomy( 'tb_author', array( 'tb_book' ), array(
            'hierarchical'            => true,
            'public'                  => true,
            'show_in_nav_menus'       => true,
            'show_ui'                 => true,
            'query_var'               => 'book_author',
            'rewrite'                 => array( 'slug' => apply_filters( 'tb_author_rewrite_slug', $author_slug ), 'hierarchical' => false, 'with_front' > false  ),
            'labels'                  => array(
                'name'                       =>  __( 'Authors', 'totally-booked' ),
                'singular_name'              =>  __( 'Author', 'totally-booked' ),
                'search_items'               =>  __( 'Search Authors', 'totally-booked' ),
                'popular_items'              =>  __( 'Popular Authors', 'totally-booked' ),
                'all_items'                  =>  __( 'All Authors', 'totally-booked' ),
                'parent_item'                =>  __( 'Parent Author', 'totally-booked' ),
                'parent_item_colon'          =>  __( 'Parent Author:', 'totally-booked' ),
                'edit_item'                  =>  __( 'Edit Author', 'totally-booked' ),
                'update_item'                =>  __( 'Update Author', 'totally-booked' ),
                'add_new_item'               =>  __( 'New Author', 'totally-booked' ),
                'new_item_name'              =>  __( 'New Author', 'totally-booked' ),
                'separate_items_with_commas' =>  __( 'Author separated by comma', 'totally-booked' ),
                'add_or_remove_items'        =>  __( 'Add or remove Author', 'totally-booked' ),
                'choose_from_most_used'      =>  __( 'Choose from the most used Authors', 'totally-booked' ),
                'menu_name'                  =>  __( 'Your Book Authors', 'totally-booked' ),
            ),
        ) );

    }

    /**
     * Initiate The Post Types
     *
     * @since 0.1
     * @uses register_post_type
     */
    public function SetupPosttypes(){

        $opt = get_option( 'book_archive_slug' );
        $book_slug = ( $opt && ! empty( $opt )  ) ? $opt : 'books';

        register_post_type( 'tb_book', array(
            'hierarchical'         => true,
            'public'               => true,
            'show_in_nav_menus'    => true,
            'show_ui'              => true,
            'supports'             => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'has_archive'          => true,
            'register_meta_box_cb' => array( $this, 'initialise_meta_boxes' ),
            'taxonomies'           => array( 'tb_author', 'tb_genre', 'tb_series' ),
            'rewrite'              => array( 'slug' => apply_filters( 'tb_book_rewrite_slug', $book_slug ), 'with_front' => false ),
            'menu_icon'            => TB_URI . 'assets/images/menu_icon.png',
            'labels'               => array(
                'name'                => __( 'Books', 'totally-booked' ),
                'singular_name'       => __( 'Book', 'totally-booked' ),
                'add_new'             => __( 'Add New Book', 'totally-booked' ),
                'all_items'           => __( 'View All Books', 'totally-booked' ),
                'add_new_item'        => __( 'New Book', 'totally-booked' ),
                'edit_item'           => __( 'Edit Book', 'totally-booked' ),
                'new_item'            => __( 'New Book', 'totally-booked' ),
                'view_item'           => __( 'View Book', 'totally-booked' ),
                'search_items'        => __( 'Search Books', 'totally-booked' ),
                'not_found'           => __( 'No Books found', 'totally-booked' ),
                'not_found_in_trash'  => __( 'No Books found in trash', 'totally-booked' ),
                'parent_item_colon'   => __( 'Parent Books', 'totally-booked' ),
                'menu_name'           => __( 'Totally Booked', 'totally-booked' ),
            ),
        ) );

    }

    /**
     * Updated Messages For The tb_book Post Type.
     *
     * @param $messages
     * @return array
     * @since 0.1
     */
    function updated_messages( $messages ) {
        global $post, $post_ID;

        $messages['tb_book'] = array(
            0  => '', // Unused. Messages start at index 1.
            1  => sprintf( __( 'Book updated. <a href="%s">View book</a>', 'totally-booked' ), esc_url( get_permalink( $post_ID ) ) ),
            2  => __( 'Custom field updated.', 'totally-booked' ),
            3  => __( 'Custom field deleted.', 'totally-booked' ),
            4  => __( 'Book updated.', 'totally-booked' ),
            /* translators: %s: date and time of the revision */
            5  => isset( $_GET['revision'] ) ? sprintf( __( 'Book restored to revision from %s', 'totally-booked' ), wp_post_revision_title( (int)$_GET['revision'], false ) ) : false,
            6  => sprintf( __( 'Book published. <a href="%s">View book</a>', 'totally-booked' ), esc_url( get_permalink( $post_ID ) ) ),
            7  => __( 'Book saved.', 'totally-booked' ),
            8  => sprintf( __( 'Book submitted. <a target="_blank" href="%s">Preview book</a>', 'totally-booked' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) ),
            9  => sprintf( __( 'Book scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview book</a>', 'totally-booked' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post_ID ) ) ),
            10 => sprintf( __( 'Book draft updated. <a target="_blank" href="%s">Preview book</a>', 'totally-booked' ), esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) )
        );

        return $messages;
    }


    /**
     * Initialises The Meta Boxes
     *
     * @since 0.1
     */
    public function initialise_meta_boxes(){

        add_meta_box( 'book_details', __( 'More Book Details', 'totally-booked' ), create_function( '', "include TB_ABSPATH . 'lib/admin_templates/meta/book_details.php';" ), 'tb_book', 'normal', 'high' );

        add_meta_box( 'book_links'  , __( 'Your Book Links'  , 'totally-booked' ), create_function( '', "include TB_ABSPATH . 'lib/admin_templates/meta/book_links.php';" )  , 'tb_book', 'normal', 'high' );

        add_meta_box( 'reader_links', __( 'Add Reader Extras' , 'totally-booked' ), create_function( '', "include TB_ABSPATH . 'lib/admin_templates/meta/extra_links.php';" ) , 'tb_book', 'normal', 'high' );

    }


    /**
     * Saves The Required Post Meta
     *
     * @param int $post_id
     * @since 0.1
     */
    public function save_post( $post_id ){
        global $post;

        //Only Run On Our Posttype
        if( ! isset( $post->post_type ) || $post->post_type !== 'tb_book' ) return;

        //Skip If Autosaving.
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        //Check Our Nonce
        check_admin_referer( 'tb_save_postmeta', 'tb_nonce' );

        //Get The Meta fields for the book links.
        $metas = $GLOBALS['TotallyBooked']->get_book_link_meta_details();

        //Add In the other meta fields
        $metas['series_title'] = '';
        $metas['coming_soon_text'] = '';
        $metas['isbn_number'] = '';
        $metas['reader_links'] = array();

        //Save The Data.
        foreach( $metas as $meta => $data ){

            if( isset( $_POST[$meta] ) ){
                $val = ( is_string( $_POST[$meta] ) ) ? wp_kses( $_POST[$meta], array() ) : $_POST[$meta] ;
                update_post_meta( $post_id, $meta, $val );
            } else {
                delete_post_meta( $post_id, $meta );
            }

        }

        //If we have popup content cached, we may want to flush it here.
        wp_cache_delete( 'popup_content_' . $post_id , 'totally-booked' );

    }

}