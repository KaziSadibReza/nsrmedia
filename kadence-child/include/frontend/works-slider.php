<?php
/**
 * Works Video Slider
 * 
 * Renders a dual-row infinite video slider with performance optimizations
 * 
 * @package Kadence Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Works Video Slider
 * 
 * Creates a dual-row infinite video slider with performance optimizations:
 * - Row 1: Horizontal/Landscape videos (35vw wide)
 * - Row 2: Vertical/Portrait videos (18vw wide)
 * - Lazy video loading with IntersectionObserver
 * - GPU-accelerated animations
 * - Auto-fills rows with "Coming Soon" placeholders if needed
 * 
 * Usage: [works_video_slider]
 * 
 * @return string HTML output for the video slider
 */
function render_works_video_slider() {

    // --- CONFIGURATION ---
    $min_slides_row_1 = 6;  // Minimum slides for horizontal row
    $min_slides_row_2 = 10; // Minimum slides for vertical row

    // Query all published works slider posts
    $args = array(
        'post_type'      => 'works-sider',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    );

    $query = new WP_Query($args);

    $slides_row_1 = array(); 
    $slides_row_2 = array();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            
            // Get ACF fields
            $video_file = get_field('video'); 
            $sider_type = get_field('sider_type'); 
            
            // Normalize slider type value
            $type_value = '';
            if (is_array($sider_type) && isset($sider_type['value'])) {
                $type_value = $sider_type['value'];
            } elseif (is_string($sider_type)) {
                $type_value = $sider_type;
            }
            $type_value = strtolower(trim($type_value));

            // Extract video URL from various ACF field formats
            $video_url = '';
            if (is_array($video_file)) {
                $video_url = isset($video_file['url']) ? $video_file['url'] : '';
            } elseif (is_numeric($video_file)) {
                $video_url = wp_get_attachment_url($video_file);
            } else {
                $video_url = $video_file;
            }

            // Skip if no valid video URL
            if (empty($video_url)) continue;

            // --- BUILD VIDEO SLIDE HTML ---
            $slide_html = '<div class="swiper-slide video-slide">';
            $slide_html .= '<div class="video-inner">';
            // Critical: preload="none" prevents initial memory spike
            $slide_html .= '<video src="' . esc_url($video_url) . '" preload="none" muted playsinline loop></video>';
            $slide_html .= '</div>';
            $slide_html .= '</div>';

            // Route to appropriate row based on type
            if ( $type_value === 'horizontal' || $type_value === 'row 1' || $type_value === 'landscape' ) {
                $slides_row_1[] = $slide_html;
            } else {
                $slides_row_2[] = $slide_html;
            }

        endwhile;
        wp_reset_postdata();
    endif;

    // --- FILLER LOGIC ---
    // Add "Coming Soon" placeholders if rows don't meet minimum slide requirements
    $fill_func = function($slides, $min_needed) {
        $count = count($slides);
        if ($count === 0) return $slides;
        if ($count < $min_needed) {
            $needed = $min_needed - $count;
            $dummy_html = '<div class="swiper-slide video-slide dummy-slide"><div class="video-inner dummy-content"><span class="dummy-text">COMING SOON</span></div></div>';
            for ($i = 0; $i < $needed; $i++) { 
                $slides[] = $dummy_html; 
            }
        }
        return $slides;
    };

    $final_row_1 = $fill_func($slides_row_1, $min_slides_row_1);
    $final_row_2 = $fill_func($slides_row_2, $min_slides_row_2);

    ob_start(); 
    ?>
    <!-- Works Video Slider Master Container -->
    <div class="works-swiper-wrapper" id="works-slider-master">
        
        <!-- Row 1: Horizontal Videos -->
        <div class="swiper swiper-row-1">
            <div class="swiper-wrapper">
                <?php echo implode('', $final_row_1); ?>
            </div>
        </div>
        
        <!-- Row 2: Vertical Videos -->
        <div class="swiper swiper-row-2">
            <div class="swiper-wrapper">
                <?php echo implode('', $final_row_2); ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('works_video_slider', 'render_works_video_slider');
