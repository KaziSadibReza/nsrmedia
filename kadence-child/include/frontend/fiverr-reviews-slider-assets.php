<?php
/**
 * Fiverr Reviews Slider Assets
 * 
 * Handles CSS and JavaScript for the Fiverr Reviews Slider
 * 
 * @package Kadence Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue Fiverr Reviews Slider Assets
 * 
 * Loads:
 * - Swiper.js from CDN (shared with works slider)
 * - Custom fiverr-reviews-slider.css
 * - Custom fiverr-reviews-slider.js
 * 
 * @return void
 */
function enqueue_fiverr_reviews_slider_assets() {
    // Swiper CSS from CDN (only if not already enqueued)
    if (!wp_style_is('swiper-bundle', 'enqueued')) {
        wp_enqueue_style( 
            'swiper-bundle', 
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
            array(),
            '11.0.0'
        );
    }
    
    // Custom Fiverr Reviews Slider CSS
    wp_enqueue_style( 
        'fiverr-reviews-slider', 
        get_stylesheet_directory_uri() . '/assets/css/fiverr-reviews-slider.css',
        array('swiper-bundle'),
        '1.0.0'
    );
    
    // Swiper JS from CDN (only if not already enqueued)
    if (!wp_script_is('swiper-bundle', 'enqueued')) {
        wp_enqueue_script( 
            'swiper-bundle', 
            'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
            array(),
            '11.0.0',
            true
        );
    }
    
    // Custom Fiverr Reviews Slider JS
    wp_enqueue_script( 
        'fiverr-reviews-slider', 
        get_stylesheet_directory_uri() . '/assets/js/fiverr-reviews-slider.js',
        array('swiper-bundle'),
        '1.0.0',
        true
    );
}
add_action('wp_enqueue_scripts', 'enqueue_fiverr_reviews_slider_assets');
