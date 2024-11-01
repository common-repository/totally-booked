<?php
/**
 * Template Tag Functions For The Totally Booked plugin.
 *
 * @version 0.1
 * @since 0.1
 * @package totally-booked
 */

if( ! function_exists( 'tb_archive_title' ) ){

    /**
     * Gets The Archive Title Set In The Options, With Normal Defaults.
     *
     * Pass true to echo instead or returning the value
     *
     * @param bool $echo
     * @return mixed|null|string|void
     * @since 0.1
     */
    function tb_archive_title( $echo = true ){

        $out = __( 'Archive' );

        if( is_tax( 'tb_genre' ) ){
            $opt = get_option( 'genre_archive_title' );
            $out = ( $opt ) ? $opt : single_term_title( '', false ) ;
        }

        if( is_tax( 'tb_author' ) ){
            $opt = get_option( 'author_archive_title' );
            $out = ( $opt ) ? $opt : single_term_title( '', false ) ;
        }

        if( is_tax( 'tb_series' ) ){
            $opt = get_option( 'series_archive_title' );
            $out = ( $opt ) ? $opt : single_term_title( '', false ) ;
        }

        if( is_post_type_archive( 'tb_book' ) ){
            $opt = get_option( 'book_archive_title' );
            $out = ( $opt ) ? $opt : __( 'Books Archives', 'totally-booked' ) ;
        }

        if( ! $echo ) return $out;

        echo $out;

        return;

    }

}

if( ! function_exists( 'tb_get_template_part' ) ){

    /**
     * Gets A Template Part, Looking First In The Theme Templates Directory.
     *
     * @param string $base
     * @param string $slug
     * @since 0.1
     */
    function tb_get_template_part( $base, $slug = '' ){

        $templates = array( $base . '.php' );

        if( ! empty( $slug ) )
            $templates[] = "$base-$slug.php";

        $template = TotallyBookedTemplating::locate_template( $templates );

        if( file_exists( $template ) )
            include $template;

    }

}

if( ! function_exists( 'tb_get_extra_reader_links' ) ){

    /**
     * Gets The Extra Reader Links, Returns A Blank String On Failure.
     *
     * @param int $post_id
     * @return string|void
     * @since 0.1
     */
    function tb_get_extra_reader_links( $post_id = 0 ){

        if( $post_id === 0 )
            $post_id = get_the_ID();

        //Need A Post ID.
        if( ! $post_id )
            return __( 'You must use The tb_extra_reader_links tag from within a WordPress loop or pass in the appropriate post ID.', 'totally-booked' );

        $links = get_post_meta( $post_id, 'reader_links', true );

        if( ! $links || empty( $links ) )
            return '';

        $out = '<ul class="extra_reader_links">';
            foreach( $links as $link ){
                $out .= '<li><a href="' . esc_attr( $link['url'] ) . '"></a>' . esc_html( $link['text'])  . '</a></li>';
            }
        $out .= '</ul>';

        return $out;

    }

}

if( ! function_exists( 'tb_get_buynow_link' ) ){

    /**
     * Gets the buy now button, this is also where the HTML template for the popup is pulled in.
     *
     * @param int $post_id
     * @return string|void
     * @since 0.1
     */
    function tb_get_buynow_link( $post_id = 0 ){

        if( $post_id === 0 )
            $post_id = get_the_ID();

        //Need A Post ID.
        if( ! $post_id )
            return __( 'You must use The tb_get_buynow_link tag from within a WordPress loop or pass in the appropriate post ID.', 'totally-booked' );

        $metas = $GLOBALS['TotallyBooked']->get_book_link_meta_details();

        //Look First In The Cache
        $cache = wp_cache_get( 'popup_items_' . $post_id , 'totally-booked' );

        if( $cache && ! empty( $cache ) ){
            $GLOBALS['popup_items'] = $cache;
            tb_get_template_part( 'buynow_popup' );
            return;
        }

        //Start with a blank array
        $popup_items = array();

        foreach( $metas as $meta => $details ){

            $url = get_post_meta( $post_id, $meta, true );

            if( $url && ! empty( $url ) ):

                $popup_items[] = array(
                    'url' => $url,
                    'image' => $details['icon_url'],
                    'class' => $details['popup_class']
                );

            endif;

        }

        //If No Items, Return Nothing..
        if( empty( $popup_items ) ) return '';

        //Set The Items To The Cache
        wp_cache_set( 'popup_items_' . $post_id , $popup_items, 'totally-booked' );

        $GLOBALS['popup_items'] = $popup_items;
        tb_get_template_part( 'buynow_popup' );

    }

}

if( ! function_exists( 'tb_get_coming_soon_text' ) ){

    /**
     * Gets The Coming Soon text.
     *
     * @param int $post_id
     * @return string
     * @since 0.1
     * @uses get_the_ID
     * @uses get_post_meta
     * @uses esc_html
     */
    function tb_get_coming_soon_text( $post_id = 0 ){

        if( $post_id === 0 )
            $post_id = get_the_ID();

        $metas = array(
            'series_title',
            'coming_soon_text',
            'isbn_number'
        );

        $out = '';

        foreach( $metas as $meta ){
            $val = get_post_meta( $post_id, $meta, true );

            $before_text = ( $meta == 'isbn_number' ) ? __( 'ISBN: ', 'totally-booked' ) : '' ;

            if( $val && ! empty( $val ) ){
                $out .= '<p class="tb_coming_soon">' . $before_text . esc_html( $val ) . '</p>';
            }

        }

        return $out;

    }

}

if( ! function_exists( 'tb_get_reader_links' ) ){

    /**
     * Gets The User Defined Reader Links
     *
     * @param $post_id
     * @return string
     * @since 0.1
     */
    function tb_get_reader_links( $post_id = 0 ){

        if( $post_id === 0 )
            $post_id = get_the_ID();

        $links = get_post_meta( $post_id , 'reader_links', true );

        if( ! $links || empty( $links ) ) return '';

        $out = '<ul class="tb_reader_links">';
            foreach( $links as $link ){
                $out .= '<li><a target="_blank" href="' . esc_attr( $link['url'] ) . '">' . esc_html( $link['text'] ) . '</a></li>';
            }
        $out .= '</ul>';

        return $out;

    }

}

if( ! function_exists( 'tb_get_entry_meta' ) ){

    /**
     * Gets the entry meta for the book. returns a blank string if none found.
     *
     * @return string
     * @param $post_id
     * @since 0.1
     */
    function tb_get_entry_meta( $post_id = 0 ){

        if( $post_id === 0 )
            $post_id = get_the_ID();


        $taxonomies = array(
            'tb_author' => __( 'Author', 'totally-booked' ),
            'tb_series' => __( 'Series', 'totally-booked' ),
            'tb_genre'  => __( 'Genre' , 'totally-booked' )
        );

        $term_links = array();

        foreach ( $taxonomies as $taxonomy => $title ) {

            $terms = get_the_terms( $post_id, $taxonomy );

            if ( is_wp_error( $terms ) || empty( $terms ) ) continue;

            //Set a flag if the post has multiple terms
            $has_multiple = ( count( $terms ) > 1 ) ? true : false ;

            /**
             * If we have more than one term, we need to handle it appropriately.
             */
            switch( $taxonomy ):

                case 'tb_author':

                    if( $has_multiple ){
                        foreach( $terms as $term ){

                            $link = get_term_link( $term, $taxonomy );

                            if( ! is_wp_error( $link ) )
                                $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . sprintf( __( 'View more books written by the author "%s".', 'totally-booked' ), $term->name ) . '</a>';

                        }
                    } else {

                        $link = get_term_link( array_shift( $terms ), $taxonomy );

                        if( ! is_wp_error( $link ) )
                            $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . __( 'View more books written by this author.', 'totally-booked' ) . '</a>';

                    }


                    break;

                case 'tb_series':

                    if( $has_multiple ){
                        foreach( $terms as $term ){

                            $link = get_term_link( $term, $taxonomy );

                            if( ! is_wp_error( $link ) )
                                $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . sprintf( __( 'View more books in the series "%s".', 'totally-booked' ), $term->name ) . '</a>';

                        }
                    } else {

                        $link = get_term_link( array_shift( $terms ), $taxonomy );

                        if( ! is_wp_error( $link ) )
                            $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . __( 'View more books in this series', 'totally-booked' ) . '</a>';

                    }


                    break;

                case 'tb_genre':

                    if( $has_multiple ){
                        foreach( $terms as $term ){

                            $link = get_term_link( $term, $taxonomy );

                            if( ! is_wp_error( $link ) )
                                $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . sprintf( __( 'View more books in the genre "%s".', 'totally-booked' ), $term->name ) . '</a>';

                        }
                    } else {

                        $link = get_term_link( array_shift( $terms ), $taxonomy );

                        if( ! is_wp_error( $link ) )
                            $term_links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . __( 'View more books in this genre', 'totally-booked' ) . '</a>';

                    }


                    break;

            endswitch;

        }

        $out = '';

        if( ! empty( $term_links ) ):

            $out .= '<div class="tb-entry-meta">';
                $out .= '<ul>';
                        foreach ( $term_links as $term_link ) {
                            $out .= '<li>' . $term_link . '</li>';
                        }
                $out .= '</ul>';
            $out .= '</div>';

        endif;

        return $out;

    }

}