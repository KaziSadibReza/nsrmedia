<?php
/**
 * Works Slider Assets (Styles & Scripts)
 * 
 * Handles all CSS and JavaScript for the Works Video Slider
 * 
 * @package Kadence Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue Works Slider Assets
 * 
 * Loads Swiper.js library and custom CSS/JS files:
 * - Swiper.js from CDN
 * - Custom works-slider.css for layout and styles
 * - Custom works-slider.js for video performance and controls
 * 
 * @return void
 */
function enqueue_works_slider_assets() {
    // Swiper CSS from CDN
    wp_enqueue_style( 
        'swiper-bundle', 
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
        array(),
        '11.0.0'
    );
    
    // Custom Works Slider CSS
    wp_enqueue_style( 
        'works-slider', 
        get_stylesheet_directory_uri() . '/assets/css/works-slider.css',
        array('swiper-bundle'),
        '1.0.0'
    );
    
    // Swiper JS from CDN
    wp_enqueue_script( 
        'swiper-bundle', 
        'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
        array(),
        '11.0.0',
        true
    );
    
    // Custom Works Slider JS
    wp_enqueue_script( 
        'works-slider', 
        get_stylesheet_directory_uri() . '/assets/js/works-slider.js',
        array('swiper-bundle'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_works_slider_assets');
