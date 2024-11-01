<?php
/**
 * Uninstall Script for totally-booked
 * 
 * @version 0.1
 * @package totally-booked
 */

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) exit ();

global $wp_rewrite;

$options = array(
    'author_archive_title',
    'genre_archive_title',
    'series_archive_title',
    'book_archive_title',
    'book_archive_slug',
    'book_author_slug',
    'book_genre_slug',
    'book_series_slug',
    'tb_output_js',
    'tb_output_css',
    'tb_wrapper_start',
    'tb_wrapper_end',
    'tb_archive_posts_per_page'
);
foreach ( $options as $opt ) {
    delete_option( $opt );
}


//Flush The permalinks
$wp_rewrite->flush_rules();
