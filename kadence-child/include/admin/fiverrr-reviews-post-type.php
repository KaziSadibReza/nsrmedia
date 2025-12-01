<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove Kadence settings meta box from 'fiverrr_reviews' post type
 *
 * @return void
 */
function remove_kadence_settings_for_fiverrr_reviews() {
    // Replace 'your_cpt_slug' with the actual slug of your custom post type.
    remove_meta_box( '_kad_classic_meta_control', 'fiverrr_reviews', 'side' );
}
add_action( 'add_meta_boxes', 'remove_kadence_settings_for_fiverrr_reviews', 20 );


/**
 * Remove the content editor for the 'fiverrr_reviews' post type
 *
 * @return void
 */
function remove_editor_fiverrr_reviews() {
    remove_post_type_support( 'fiverrr_reviews', 'editor' );
}
add_action( 'admin_menu' , 'remove_editor_fiverrr_reviews' );