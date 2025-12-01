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


// =============================================================================
// INCLUDE FILES
// =============================================================================

/**
 * Frontend Components
 */
require_once get_stylesheet_directory() . '/include/frontend/works-slider.php';
require_once get_stylesheet_directory() . '/include/frontend/works-slider-assets.php';
require_once get_stylesheet_directory() . '/include/frontend/fiverr-reviews-slider.php';
require_once get_stylesheet_directory() . '/include/frontend/fiverr-reviews-slider-assets.php';

/**
 * Admin Components
 */
require_once get_stylesheet_directory() . '/include/admin/works-post-type.php';
require_once get_stylesheet_directory() . '/include/admin/fiverrr-reviews-post-type.php';