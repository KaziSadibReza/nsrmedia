<?php
/**
 * Fiverr Reviews Slider
 * 
 * Renders a dual-row infinite reviews slider with performance optimizations
 * 
 * @package Kadence Child
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Render Fiverr Reviews Slider
 * 
 * Creates a dual-row infinite reviews slider with:
 * - Automatic data duplication for seamless looping
 * - "Coming Soon" placeholders if less than 6 reviews
 * - Responsive breakpoints
 * - Pause on hover
 * - Reverse direction for Row 2
 * 
 * Usage: [fiverr_reviews_slider]
 * 
 * @return string HTML output for the reviews slider
 */
function render_fiverr_reviews_slider() {
    $output = '';

    // -- 1. DATA FETCHING --
    $args = array(
        'post_type'              => 'fiverrr_reviews',
        'posts_per_page'         => -1,
        'post_status'            => 'publish',
        'no_found_rows'          => true, 
        'update_post_term_cache' => false, 
    );
    
    $query = new WP_Query($args);
    $slides_data = array();

    // SVG Assets
    $star_svg = '<svg width="14" height="14" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg"><g clip-path="url(#clip0_480_1665)"><path d="M16.8229 6.111H10.3969L8.41093 0L6.43994 8.083L8.41093 12.223L13.6109 16L11.6249 9.889L16.8229 6.111Z" fill="#E67136"/><path d="M6.426 6.111H0L5.2 9.888L3.213 16L8.413 12.223V0L6.426 6.111Z" fill="#E67136"/></g><defs><clipPath id="clip0_480_1665"><rect width="16.823" height="16" fill="white"/></clipPath></defs></svg>';
    
    $fiverr_logo_svg = '<svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<rect width="28" height="28" rx="14" fill="url(#pattern0_480_1677)"/>
<defs>
<pattern id="pattern0_480_1677" patternContentUnits="objectBoundingBox" width="1" height="1">
<use xlink:href="#image0_480_1677" transform="scale(0.0025)"/>
</pattern>
<image id="image0_480_1677" width="400" height="400" preserveAspectRatio="none" xlink:href="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/4gKgSUNDX1BST0ZJTEUAAQEAAAKQbGNtcwQwAABtbnRyUkdCIFhZWiAAAAAAAAAAAAAAAABhY3NwQVBQTAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA9tYAAQAAAADTLWxjbXMAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAtkZXNjAAABCAAAADhjcHJ0AAABQAAAAE53dHB0AAABkAAAABRjaGFkAAABpAAAACxyWFlaAAAB0AAAABRiWFlaAAAB5AAAABRnWFlaAAAB+AAAABRyVFJDAAACDAAAACBnVFJDAAACLAAAACBiVFJDAAACTAAAACBjaHJtAAACbAAAACRtbHVjAAAAAAAAAAEAAAAMZW5VUwAAABwAAAAcAHMAUgBHAEIAIABiAHUAaQBsAHQALQBpAG4AAG1sdWMAAAAAAAAAAQAAAAxlblVTAAAAMgAAABwATgBvACAAYwBvAHAAeQByAGkAZwBoAHQALAAgAHUAcwBlACAAZgByAGUAZQBsAHkAAAAAWFlaIAAAAAAAAPbWAAEAAAAA0y1zZjMyAAAAAAABDEoAAAXj///zKgAAB5sAAP2H///7ov///aMAAAPYAADAlFhZWiAAAAAAAABvlAAAOO4AAAOQWFlaIAAAAAAAACSdAAAPgwAAtr5YWVogAAAAAAAAYqUAALeQAAAY3nBhcmEAAAAAAAMAAAACZmYAAPKnAAANWQAAE9AAAApbcGFyYQAAAAAAAwAAAAJmZgAA8qcAAA1ZAAAT0AAACltwYXJhAAAAAAADAAAAAmZmAADypwAADVkAABPQAAAKW2Nocm0AAAAAAAMAAAAAo9cAAFR7AABMzQAAmZoAACZmAAAPXP/bAEMABQMEBAQDBQQEBAUFBQYHDAgHBwcHDwsLCQwRDxISEQ8RERMWHBcTFBoVEREYIRgaHR0fHx8TFyIkIh4kHB4fHv/bAEMBBQUFBwYHDggIDh4UERQeHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHh4eHv/AABEIAZABkAMBIgACEQEDEQH/xAAbAAEAAwEBAQEAAAAAAAAAAAAABQYHAQMEAv/EABoBAQACAwEAAAAAAAAAAAAAAAABAwQFBgL/2gAMAwEAAhADEAAAAfUY3BgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAD9J/KS+31fALFx7rywivLDCxX4CKAAAAAAAAAAAAAAAAAAH1SV897WvWH3W7/AKJyAAGa6VmvjURIp5oAAAAAAAAAAAAAAAABLRWne9j9vpzt3VAkAABmulZr41ESKeaAAAAAAAAAAAAAAAAAmdGqFvu6ro97IBxBRVO8y/4vGo11kJGvZvEvOGHjWAAAAAAAAAAAAAAAAAXmz1mzZHYhOWB+cp1TI69EFWgAAAAAAAAAAAAAAAAAAkbJF367oKqtSc+Nkj1ldD2B+Kta0UVVakU1VahVVqFVWoVVahVfkusYqzMUcqAAAAAAAAAAABYb/QL/AHdUHvZAAAAAAAAAcjZKMinMxj8SAAAAAAAAAAABYb/nVyu6aTRj1sJLvzfTNgPQDnI1XKIxHmTRgkkb9j17ibAORknGRTmYx+JAAAAAAAAAAAAABN6s1Zs2R2ATlgeWSa3klWgCvRgNGznRve2mhd0oHIyTjIpzMY/EgAAAAAAAAAAAAAXqzVmzZHYhOWB55HrmR1aAK9EA0bOdG97eaF3SgcjJOMinMxj8SAAAAAAAAAAAAABerNWbNkdiE5YHnkeuZHVoAr0QDRs50b3t5oXdKByMk4yKczGPxIAAAAAAAAAAAAAF6s1Zs2R2ITlgeeR65kdWgCvRANGznRve3mhd0oHIyTjIpzMY/EgAAAAAAAAAAAAAXqzVmzZHYhOWB55HrmR1aAK9EA0bOdG97eaF3SgcjJOMinMxj8SAAAAAAAAAAAAABerNWbNkdiE5YHnkeuZHVoAr0QDRs50b3t5oXdKByMk4yKczGPxIAAAAAAAAAAAAAF6s1Zs2R2ITlgeeR65kdWgCvRANGznRve3mhd0oHIyTjIpzMY/EgAAAAAAAAAAAAASn2156ybCrx6sKvJWCvnmkIqATEOmywq8nIsKvEWHwhQHnEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA/8QAKBAAAAQFBAMBAAMBAAAAAAAAAwQFIAABAhU0QBEUMDUSITICUDOBIZCQ/9oACAEBAAEFAv8Aa9NNVU6CBuuJJJucWc1FoNRaDUWg1FoNQYCqBG+OXLimKiySHTAYVAcnrHJfGTSEzMwg6AqPxWOS+KmFPaGpplTL8ljkviSl5mQAkXLfmscl8RGC/kPfh5lHmUeZR5hY5H4mnaP6conqSshjZgWfmfydPYrZwZEmMP8AK09iNr2fHTi0jQ9mDizBxZQ4IlZFQmzl5lZg4swcWYOLMHFmDizBxZg4swcWYOLMHFlDizBwbS6AS/aQM3rKmB2kDN6ypgdpAzesqYHaQM3rKmB2kYWgI17xSPeKR75SARQxpO94pHvFI94pHvFI94pHvFIDEoEoaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHe09iNr2OReOaqYHeKHhiod3NRdzUXc1F3NRdzUTVjM5OLKI5cK7mou5qLuai7mou5qB1IcYP/AIaf/8QALhEAAAQEBAQGAgMAAAAAAAAAAAECAwQFEDMRMFJxEhQVQhMgITEyQCJRYGJw/9oACAEDAQE/Af5cRGfsEwjyvZJjkH9I5B/SHIV1osVFh9SDlqnfyX6EGoZpr4l5JxZLf6cuhfGXifsQL08s4slv9OVt8LBH+6uupaTxKCpynH8UjrX9RGTHmEcPDh9OCsJrOVHglP1JfCNOM8SiHT4fSEIJBcJVdh23fmWI6fD6R0+H0jp8PpHT4fSOnw+kPQLCUGZJzpVYy37atgebLXUJYwMx47WogRkZYlVTiUfIx47Woh47WoglxCvRJ1ftq2B5uIxEFYTWc9oxGIk949qv21bA8+CsJrOu2snvHtV+2rYHnwVhNZ121k949qv21bA8+CsJrOu2snvHtV+2rYHnwVhNZ121k949qv21bA8+CsJrOu2snvHtV+2rYHnwVhNZ121k949qv21bA89MY8guFJjn4jUOff1B19bvzPGrby2jxSeA5+I1Dn39QOOeMsDP/Bf/xAAiEQABAwQCAgMAAAAAAAAAAAABABAxAhESQAMgEzAhYHD/2gAIAQIBAT8B+34lYlYlYkalNF0BbpXGnQL9q406Ie68i8iqqvpiH5NSkAhYjoRdYhYhYhYhYhYhYj3Ux6zHupPwr9bq/QxoCH5HocxoCH5HolzGgIfkeiXMaAh+R6JcxoCH5HolzGgIfkeiHMaGRWRWRV7veyyKyKyP4L/8QAMRAAAAQBCgQGAgMAAAAAAAAAAAECAyARMjNAcXKBkbHhEiFBUCIwMVFhwRDRQoCQ/9oACAEBAAY/Av7ryJIzP4HJk8eQ/gWI9Ws9hObz2E5vPYTm89hOazBtLklL27RI2mX56EJXz4z9ugkQgkl8F5DuGnZ+Nfhb1BJQkkkXlO4adm5zEzgREUheW7hp2WQvUJR162+Y7hp2VMvonxeV6j1/DuGnZXV2FHwkXE4fQeN1Vhch69pXf/Ua3D6n2tV/9RHZ2g2zUZeGXkKVQplCmUDQSjVKcsUgplCmUKZQplCmUKZQplCmUKZQplCmUKZQW4Tqj4Srarn3V3btbVc+6u7drarn3V3bK2q591d2ytmpxRJLh6inRmKdGYp0ZinRmKdGYp0ZinRmONB8SfeJ2yvqvxHG3jrE7ZX1X4jjbx1idsr6r8Rxt46xO2V9V+I428dYnbK+q/EcbeOsTtlfVfiONvHWJ2yvqvxHG3jrE7ZX1X/1EcbeOsTtlfNDZIMjOXmQmtZbia3luJreW4mt5bia3luJOFvKMm0EiQvchNby3E1vLcTW8txNby3E1rLcG2om5D+P8Nf/xAArEAABAgQFAwQCAwAAAAAAAAABACARYaHxIVBRsfAxQEEwccHRgZGAkOH/2gAIAQEAAT8h/mvIdQRKxsMe3cuqfm/yrtS80vNLzS8fpGUKHHwxEfnKI08PXo9woeDNBh9lKukD0OFLk+NUJ6+fb9roxwA9LhS5MTqxjFrJCaAEAB6fClyUgARIwAQUw8z18vU4UuSxIEQxfFUHnBTApBSCg1CgMPiDJfeZA8ZCAw8ANSi4wWpB+giTqSidSonUqJ1OTVXZwoBFI65Eh4yupbOVrKBUmKP3AfKtYVpCsIQsxij+Pp0Y1BWkK0hWkK0hWkK0hWkK0hWkK0hWEK0hFyESBAx7uu7MyKu7O2PeNXdnbHvHruz0y4tPePG8mI6ohkIAKQwYR1OJgIlYjhCEISEgydAacgepbB1K/nzNOQPUtg6lfX7jTkD1LYOpX8aZpyB6lsHUr+NM05A9S2DqV/GmacgepbB1K/jTNOQPUtg6lfxpmnIHqWwdSv40zTkD1LYOpX8aZpyB6lsHUr+NM05A9S2DqV/GmacgepbB1K/jTNOQPUtg6lfxpsl+pbOUr+dNkt9YCBRjhNY3yUvNLzS80vNCwv2vt5q+NCJHEx1mrzS80vNLzS+UDbDgYF9/0a//2gAMAwEAAgADAAAAEAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAyRzzwAAAAAAAAAAAAAAAAAAC3XPPFwAAAAAAAAAAAAAAAAAATPPPPFwAAAAAAAAAAAAAAAAADvPH0TKgAAAAAAAAAAAAAAAAAoPPMAAAAAAAAAAAAAAAAAAAKZLfPKvPPPLhQAAAAAAAAAAAAKvPPPPPPPPPLAQAAAAAAAAAAAIDI/PCTDJ/PPAQAAAAAAAAAAAAQqfPKwAE/PPAQAAAAAAAAAAAAAqfPAwAAvPPAQAAAAAAAAAAAAAqfPAwAAvPPAQAAAAAAAAAAAAAqfPAwAAvPPAQAAAAAAAAAAAAAqfPAwAAvPPAQAAAAAAAAAAAAAqfPAwAAvPPAQAAAAAAAAAAAAAofPAwAAvPPAQAAAAAAAAAAAAAAc8YQAA8s0AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP/xAAqEQABAgQEBgIDAQAAAAAAAAABABEQMaGxMGGRwSFAQVGB8SBxYHDR4f/aAAgBAwEBPxD8uOsgF9AVnlnk8eEuPKDQa6f4EAYIz66/CiWPJjbeoc+wQAGHxoljyYOsETtEkOwCHYQjMtsUz3/xMUoXd36Edh35OkiN0Bc6Nygs7LnushqUChYCUWmQkshqVkNSshqVkNSshqUG44BMz2+0cWT9nDrllNiiwYLmZAXuAhojiLUwH7ll7gL3ARViTkQY1yymxjlQRn8tk5ORaq4jXLKbHpYzeW0a1cRrllNj0sZvLaNauI1yymx6WM3ltGtXEa5ZTY9LGby2jWriNcspseljN5bRrVxGuWU2PQxm89o1q4jXLKbHGSQBILOLPJjcZJ48Y5Lgs4s8jYkg/oX/xAAiEQABAwQCAgMAAAAAAAAAAAABABAxEUBBYSAhMFFgcHH/2gAIAQIBAT8Q+XgmFqWpGQLQnYoMLequ7mCjiAFSiGAvwqOlLOJyi0rgha0BQUDhkta1rWta1ogB680Xjk8wgtHvgSBKo9qj2gQYeSwifB5vJYRPhypLCJ8OVJYRPhypLCJ8OVJYRPhypLCB8Hm8lgBdArctyJycHBbluW76F//EACoQAAECBAUDBQADAAAAAAAAAAEAESAxQaEhUFFhsUCR8RAwcYHBgZDw/9oACAEBAAE/EP5riBekaT6CGAvVJj6IFCH+CY8EhCS1atXPF0+mTgAYkAyCmUax4vwA+JqfBZOQfq3wtjjALIAaR3mT0OO1mBiiY0DX6DYRQmCYfO53OKmFKMq8ya4EMYGGwTqbB9kGlYAwAEgAhL2SrzJab+KAGJJkEOsmNxOm/BsB6U9kq8yWjfw3NQwuA/SBoaehAMSPtBj9F5xecRZ/dESJw+S8DwYlWjAk8jsgcGiOGAXvpFNBXZPEOrD2B3RByTqSvOLzi84iSS5L5KIBdYiEU6BHHHGHwB9BhmJr/A0ygAbgABJIZYoNG9T5YyoDUAgkA2CUcwiKYODusX2w+++++++++aISmYmdihi6t+Al+AmTbIthisIQiAP1Nsm2TbJtk2ybZNsm2TbJtl8yWMuhdZFPS1K5dZFM+lqV76yK4ZMdUx1U5omIjomOqY6pjqmOqJCLZ4ale+rK2z+YEjt2B7J1bxxeCI5DJmcAAC3YjuhCIhGADk+mHii8UXii8UTChlcly4LEg3BENSveSezJXniO7hdSveSezJXnj3G9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h9SveSezJXniO/h+quOSurzxHdw81V168oQJklwBQMMBFhatWrU2TkEFq8dx64jCcJyBEypDq1atWMhFtjOOQNnIW/o1//2Q=="/>
</defs>
</svg>';

    // Process Posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            
            $avatar = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
            if (!$avatar) $avatar = 'https://www.gravatar.com/avatar/?d=mp';

            $slides_data[] = array(
                'name'   => get_the_title(),
                'avatar' => $avatar,
                'source' => get_field('review_source') ?: 'Via Fiverr',
                'rating' => get_field('rating') ?: 5,
                'review' => get_field('review'),
            );
        }
        wp_reset_postdata();
    }

    // -- 2. FILL & DUPLICATE FOR INFINITE LOOP --
    // Step 1: Ensure we have at least 6 items by adding dummies if needed
    $min_slides_needed = 6;
    $current_count = count($slides_data);
    
    if ($current_count < $min_slides_needed) {
        $needed = $min_slides_needed - $current_count;
        for ($i = 0; $i < $needed; $i++) {
            $slides_data[] = array(
                'name'   => 'Future Client',
                'avatar' => 'https://www.gravatar.com/avatar/?d=mp&f=y', 
                'source' => 'Via Fiverr',
                'rating' => 5,
                'review' => 'This spot is reserved for your amazing review! We are working hard to deliver 5-star service. Join our happy clients soon.',
            );
        }
    }

    // Step 2: DUPLICATE the array until we have at least 12 items
    // This ensures smooth linear looping without "rewinds" or empty spaces
    $target_count = 12;
    while (count($slides_data) < $target_count) {
        $slides_data = array_merge($slides_data, $slides_data);
    }


    // Helper function to render a single slide
    $render_slide = function($slide) use ($star_svg, $fiverr_logo_svg) {
        $stars_html = '';
        for ($k = 0; $k < 5; $k++) {
            if ($k < $slide['rating']) {
                $stars_html .= '<span class="fr-star-icon">' . $star_svg . '</span>';
            }
        }
        
        $html  = '<div class="swiper-slide">';
        $html .= '  <div class="fr-card">';
        $html .= '    <div class="fr-card-header">';
        $html .= '      <img src="' . esc_url($slide['avatar']) . '" alt="Avatar" class="fr-avatar" loading="lazy">';
        $html .= '      <div class="fr-meta">';
        $html .= '        <h4 class="fr-author-name">' . esc_html($slide['name']) . '</h4>';
        $html .= '        <div class="fr-source">' . esc_html($slide['source']) . '</div>';
        $html .= '        <div class="fr-rating">' . $stars_html . '</div>';
        $html .= '      </div>';
        $html .= '      <div class="fr-fiverr-logo">' . $fiverr_logo_svg . '</div>';
        $html .= '    </div>';
        $html .= '    <p class="fr-review-text">' . esc_html($slide['review']) . '</p>';
        $html .= '  </div>';
        $html .= '</div>';
        return $html;
    };

    // -- 3. RENDER SLIDER --
    $output .= '<div class="fr-slider-section">';

    // --- ROW 1 ---
    $output .= '<div class="fr-row">';
    $output .= '  <div class="swiper fr-swiper fr-swiper-row-1">';
    $output .= '    <div class="swiper-wrapper">';
    foreach ($slides_data as $slide) {
        $output .= $render_slide($slide);
    }
    $output .= '    </div>'; 
    $output .= '  </div>'; 
    $output .= '</div>';

    // --- ROW 2 ---
    $output .= '<div class="fr-row">';
    $output .= '  <div class="swiper fr-swiper fr-swiper-row-2">';
    $output .= '    <div class="swiper-wrapper">';
    foreach ($slides_data as $slide) {
        $output .= $render_slide($slide);
    }
    $output .= '    </div>'; 
    $output .= '  </div>'; 
    $output .= '</div>';

    $output .= '</div>'; // End Section

    return $output;
}
add_shortcode('fiverr_reviews_slider', 'render_fiverr_reviews_slider');
