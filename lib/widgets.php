<?php
/**
 * Widget Classes for the Totally Booked Plugin.
 * 
 * @version 0.1
 * @package totally-booked
 */
class TB_Book_Widget extends WP_Widget{

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'tb_book_widget', // Base ID
            __( 'Totally Booked Book Widget', 'totally-booked' ), // Name
            array( 'description' => __( 'Displays a single book in the sidebar.', 'totally-booked' ), ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        extract( $args );
        $title = apply_filters( 'widget_title', $instance['title'] );

        echo $before_widget;
        if ( ! empty( $title ) )
            echo $before_title . $title . $after_title;

        $query_args = array(
            'post_type'      => 'tb_book',
            'posts_per_page' => 1,
            'post__in'       => array( (int)$instance['book_id'] )
        );
        $query = new WP_Query( $query_args );

        //Return and spit an error if no book found.
        if( ! $query->have_posts() ){
            echo '<p>' . __( 'The Selected Book Was Not Found', 'totally-booked' ) . '</p>';
            return;
        }

        $query->the_post();

        tb_get_template_part( 'book_widget' );

        wp_reset_query();

        echo $after_widget;
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = strip_tags( $new_instance['title'] );

        $instance['book_id'] = ( empty( $new_instance['book_id'] ) ) ? 0 : (int)$new_instance['book_id'] ;

        return $instance;
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     * @return null
     */
    public function form( $instance ) {

        $title = ( isset( $instance[ 'title' ] ) ) ? esc_html( $instance['title'] ) : '' ;

        $query_args = array(
            'post_type'      => 'tb_book',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        );
        $query = new WP_Query( $query_args );

        if( ! $query->have_posts() ){
            echo '<p>' . __( 'You have not added any books, please add some before using this widget.', 'totally-booked' ) . '</p>';
            return;
        }

        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'totally-booked' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'book_id' ); ?>"><?php _e( 'Choose The Book To Display:', 'totally-booked' ); ?></label>
            <select name="<?php echo $this->get_field_name( 'book_id' ); ?>" id="<?php echo $this->get_field_id( 'book_id' ); ?>">
                <option value=""><?php _e( 'Select A Book', 'totally-booked' ); ?></option>
                <?php
                foreach( $query->posts as $book ){
                    $selected = ( $instance['book_id'] === $book->ID ) ? 'selected="selected"' : '' ;
                    echo '<option ' . $selected . ' value="' . esc_attr( $book->ID ) . '">' . $book->post_title . '</option>';
                }
                ?>
            </select>
        </p>
    <?php


    }


}

/**
 * Register The widgets in this file.
 */
function tb_register_widgets(){
    register_widget( 'TB_Book_Widget' );
}
add_action( 'widgets_init', 'tb_register_widgets' );