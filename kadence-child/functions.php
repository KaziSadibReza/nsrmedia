<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue child theme styles
 */
function kadence_child_enqueue_styles() {
    // Load Parent theme css 
    wp_enqueue_style( 'kadence-parent-style', get_template_directory_uri() . '/style.css' );

    // Load Child theme css
    wp_enqueue_style( 'kadence-child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('kadence-parent-style')
    );
}
add_action( 'wp_enqueue_scripts', 'kadence_child_enqueue_styles' );


/**
 * Include Works Slider Functionality
 */
require_once get_stylesheet_directory() . '/include/frontend/works-slider.php';

/**
 * Include Works Slider assets
 */
require_once get_stylesheet_directory() . '/include/frontend/works-slider-assets.php';

/**
 * Include Works Slider Admin Customizations
 */
require_once get_stylesheet_directory() . '/include/admin/works-post-type.php';