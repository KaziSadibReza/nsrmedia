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
 * Include work slider functions
 */
require_once get_stylesheet_directory() . '/include/frontend/works-slider.php';

/**
 * Include work slider assets
 */
require_once get_stylesheet_directory() . '/include/frontend/works-slider-assets.php';

/**
 * Include fiverr reviews slider functions 
 */
require_once get_stylesheet_directory() . '/include/frontend/fiverr-reviews-slider.php';

/**
 *  Include fiverr reviews slider assets
 */
require_once get_stylesheet_directory() . '/include/frontend/fiverr-reviews-slider-assets.php';

/**
 * Include works slider post admin features
 */
require_once get_stylesheet_directory() . '/include/admin/works-post-type.php';

/**
 * Include fiverr reviews post admin features
 */
require_once get_stylesheet_directory() . '/include/admin/fiverr-reviews-post-type.php';

/**
 * Include clients video slider post admin features
 */
require_once get_stylesheet_directory() . '/include/admin/clients_video_slider-post-type.php';