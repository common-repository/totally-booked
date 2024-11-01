<?php
/**
 * Shortcodes For The Totally Booked Plugin.
 * 
 * @version 0.1
 * @package totally-booked
 */
class TB_Shortcodes{

    function __construct(){

        add_action( 'init', array( $this, 'register_shortcodes' ) );

    }

    /**
     * Register the shortcodes.
     *
     * @since 0.1
     */
    function register_shortcodes(){

        add_shortcode( 'tb_book'          , array( $this, 'book_shortcode'           ) );
        add_shortcode( 'tb_genre_gallery' , array( $this, 'genre_gallery_shortcode'  ) );
        add_shortcode( 'tb_series_gallery', array( $this, 'series_gallery_shortcode' ) );

    }

    /**
     * The shortcode to display the single book layout.
     *
     * [tb_book id="3"]
     *
     * @param $atts
     * @return string
     */
    function book_shortcode( $atts ){
        global $post;

        extract( shortcode_atts( array(
            'id' => false
        ), $atts ));

        if( ! $id ) return '';

        //Store The Original Post Object.
        $original_post = $post;

        $post = get_post( $id );

        //Remove The Shortcode Filter to avoid a repeating loop..
        remove_filter( 'the_content', 'do_shortcode', 11 );

        //Set The Post
        setup_postdata( $post );

        ob_start();

        tb_get_template_part( apply_filters( 'tb_book_shortcode_template_part', 'loops/single_book' ) );

        echo '<div class="clear"></div>';

        $out = ob_get_contents();

        ob_end_clean();

        //Reinstate The Original Post.
        $post = $original_post;
        setup_postdata($original_post);

        //Re-Add The Filter
        add_filter( 'the_content', 'do_shortcode', 11 );

        return $out;
    }

    /**
     * The shortcode to display a single genre gallery
     *
     * [tb_genre_gallery slug="some-slug"]
     *
     * @param $atts
     * @return string
     * @since 0.1
     */
    function genre_gallery_shortcode( $atts ){

        extract( shortcode_atts( array(
            'slug'    => false,
            'columns' => 3
        ), $atts ));

        if( ! $slug ) return '';

        $query_args = array(
            'post_type'      => 'tb_book',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'tb_genre',
                    'field'    => 'slug',
                    'terms'    => esc_attr( $slug )
                )
            )
        );
        $query = new WP_Query( $query_args );

        $out = '';

        ob_start();

        $i = 1;

        echo '<div class="tb_gallery_wrapper">';

            if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();

                tb_get_template_part( apply_filters( 'tb_genre_shortcode_template_part', 'loops/gallery' ) );

                if( $i % (int)$columns === 0 ) echo '<div class="clear"></div>';

                $i++;

            endwhile; endif;

            echo '<div class="clear"></div>';

        echo '</div>';

        $out .= ob_get_contents();
        ob_end_clean();

        return $out;

    }

    /**
     * The shortcode to display a single series gallery
     *
     * [tb_series_gallery slug="some-slug"]
     *
     * @param $atts
     * @return string
     * @since 0.1
     */
    function series_gallery_shortcode( $atts ){

        extract( shortcode_atts( array(
            'slug'    => false,
            'columns' => 3
        ), $atts ));

        if( ! $slug ) return '';

        $query_args = array(
            'post_type'      => 'tb_book',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'tax_query'      => array(
                array(
                    'taxonomy' => 'tb_series',
                    'field'    => 'slug',
                    'terms'    => esc_attr( $slug )
                )
            )
        );
        $query = new WP_Query( $query_args );

        $out = '';


        ob_start();

        $i = 1;

        echo '<div class="tb_gallery_wrapper">';

            if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();

                tb_get_template_part( apply_filters( 'tb_series_shortcode_template_part', 'loops/gallery' ) );

                if( $i % (int)$columns === 0 ) echo '<div class="clear"></div>';

                $i++;

            endwhile; endif;

            echo '<div class="clear"></div>';

        echo '</div>';

        $out .= ob_get_contents();
        ob_end_clean();

        return $out;

    }
}
new TB_Shortcodes();