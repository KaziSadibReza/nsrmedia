/**
 * Works Video Slider JavaScript
 *
 * @package Kadence Child
 * @version 1.0.0
 */

document.addEventListener("DOMContentLoaded", function () {
  // =================================================================
  // VIDEO PERFORMANCE CONTROLLER
  // =================================================================
  // Uses IntersectionObserver to play/pause videos based on visibility
  // High threshold (0.6) ensures only centered videos play

  var videoObserver = new IntersectionObserver(
    function (entries) {
      entries.forEach(function (entry) {
        var vid = entry.target;

        if (entry.isIntersecting) {
          // Video is >60% visible - PLAY IT
          vid.muted = true; // Force mute for autoplay policy
          var playPromise = vid.play();

          if (playPromise !== undefined) {
            playPromise
              .then(() => {
                vid.classList.add("is-playing");
                vid
                  .closest(".swiper-slide")
                  .classList.add("swiper-slide-visible");
              })
              .catch((e) => {
                // Silently handle autoplay blocks
              });
          }
        } else {
          // Video is <60% visible (cut off) - PAUSE IT
          vid.pause();
          vid.classList.remove("is-playing");
          vid.closest(".swiper-slide").classList.remove("swiper-slide-visible");
        }
      });
    },
    {
      threshold: 0.6, // High threshold is key for performance
    }
  );

  // =================================================================
  // OBSERVER SETUP
  // =================================================================
  // Attaches IntersectionObserver to all videos including Swiper clones

  function setupObserver(container) {
    var vids = container.querySelectorAll("video");
    vids.forEach(function (v) {
      videoObserver.unobserve(v); // Prevent duplicate watchers
      videoObserver.observe(v);
    });
  }

  // =================================================================
  // SWIPER INITIALIZATION
  // =================================================================

  const commonConfig = {
    slidesPerView: "auto",
    loop: true,
    centeredSlides: true, // Symmetrical cut-off effect
    allowTouchMove: false,
    cssMode: false,
    on: {
      // Attach observer after Swiper creates clone slides
      init: function () {
        var swiperInstance = this;
        setTimeout(function () {
          setupObserver(swiperInstance.el);
        }, 500); // Delay ensures clones exist
      },
    },
  };

  // Row 1: Horizontal videos (left-to-right)
  var swiper1 = new Swiper(".swiper-row-1", {
    ...commonConfig,
    speed: 8000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
    },
  });

  // Row 2: Vertical videos (right-to-left)
  var swiper2 = new Swiper(".swiper-row-2", {
    ...commonConfig,
    speed: 8000,
    autoplay: {
      delay: 0,
      disableOnInteraction: false,
      reverseDirection: true,
    },
  });

  // =================================================================
  // PAGE VISIBILITY OPTIMIZATION
  // =================================================================
  // Stops animations and videos when user switches tabs

  document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
      // Page hidden - stop everything
      if (swiper1 && swiper1.autoplay) swiper1.autoplay.stop();
      if (swiper2 && swiper2.autoplay) swiper2.autoplay.stop();
      document.querySelectorAll("video").forEach((v) => v.pause());
    } else {
      // Page visible - resume animations
      if (swiper1 && swiper1.autoplay) swiper1.autoplay.start();
      if (swiper2 && swiper2.autoplay) swiper2.autoplay.start();
    }
  });
});
