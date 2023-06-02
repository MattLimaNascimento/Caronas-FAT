document.addEventListener('cardsCarregados', function () {
  var swiper = new Swiper(".slide-content", {
    slidesPerView: 3,
    spaceBetween: 20,
    loop: true,
    centerSlide: true,
    fade: true,
    slidesPerGroup: 3,
    loopFillGroupWithBlank: true,
    grabCursor: 'true',
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
      dynamicBullets: true
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev"
    },
    breakpoints: {
      0: {
        slidesPreView: 1
      },
      520: {
        slidesPreView: 2
      },
      950: {
        slidesPreView: 3
      }
    }
  });
});