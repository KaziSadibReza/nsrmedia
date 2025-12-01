<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove Kadence settings meta box from 'clients_video_slider' post type
 *
 * @return void
 */
function remove_kadence_settings_for_clients_video_slider() {
    // Replace 'your_cpt_slug' with the actual slug of your custom post type.
    remove_meta_box( '_kad_classic_meta_control', 'clients_video_slider', 'side' );
}
add_action( 'add_meta_boxes', 'remove_kadence_settings_for_clients_video_slider', 20 );

/**
 * Remove default Featured Image box
 * @return void
 */
add_action('do_meta_boxes', function() {
    remove_meta_box('postimagediv', 'clients_video_slider', 'side');
});


/**
 * Remove the content editor for the 'clients_video_slider' post type
 *
 * @return void
 */
function remove_editor_clients_video_slider() {
    remove_post_type_support( 'clients_video_slider', 'editor' );
}
add_action( 'admin_menu' , 'remove_editor_clients_video_slider' );