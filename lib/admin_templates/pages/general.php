<?php
/**
 * General Page Options Template
 *
 * @version 0.1
 * @package totally-booked
 * @since 0.1
 */

settings_fields( 'tb_general_options' );

do_settings_sections( 'tb_general' );

submit_button( __( 'Save Options', 'totally-booked' ) );