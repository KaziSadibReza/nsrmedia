/**
 * Fiverr Reviews Slider JavaScript
 *
 * @package Kadence Child
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function () {
  // =================================================================
  // SWIPER INITIALIZATION FUNCTION
  // =================================================================
  // Re-usable function for cleaner code & correct autoplay initialization

  function initSwiper(selector, reverse) {
    return new Swiper(selector, {
      slidesPerView: 1,
      spaceBetween: 20,
      loop: true,
      // loopedSlides must be >= slidesPerView + a buffer
      // We have duplicate data (>=12 slides), so 8 is plenty safe
      loopedSlides: 8,
      grabCursor: true,
      centeredSlides: true,
      allowTouchMove: true,
      speed: 6000, // Linear speed in milliseconds
      autoplay: {
        delay: 0,
        disableOnInteraction: false, // Continue after manual drag
        pauseOnMouseEnter: true, // Pause on hover
        reverseDirection: reverse,
      },
      breakpoints: {
        768: {
          slidesPerView: 2,
          spaceBetween: 30,
        },
      },
    });
  }

  // =================================================================
  // INITIALIZE ROW 1 (NORMAL DIRECTION)
  // =================================================================

  var swiper1 = initSwiper(".fr-swiper-row-1", false);

  // =================================================================
  // INITIALIZE ROW 2 (REVERSE DIRECTION)
  // =================================================================

  var swiper2 = initSwiper(".fr-swiper-row-2", true);
});
