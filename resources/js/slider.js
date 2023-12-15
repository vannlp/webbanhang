
let custom = {
    prevArrow:  `<button type="button" class="slick-prev"><i class="fa-solid fa-chevron-left"></i>  </button>`,
    nextArrow:  `<button type="button" class="slick-next"> <i class="fa-solid fa-chevron-right"></i> </button>`,
};


$(".slider-banner").slick({
    slidesToShow: 2,
    responsive: [
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 1,
                infinite: true,
                dots: true
            }
        }
    ],
    ...custom
});

$(".slick-slide-product").slick({
    slidesToShow: 5,
    responsive: [
        {
            breakpoint: 800,
            settings: {
                slidesToShow: 3,
                infinite: true,
                // dots: true
            }
        },
        {
            breakpoint: 500,
            settings: {
                slidesToShow: 2,
                infinite: true,
                // dots: true
            }
        }
    ],
    ...custom
});

$(".product-slideshow").slick({
    slidesToShow: 1,
    dots: true,
    customPaging: function(slick,index) {
        var targetImage = slick.$slides.eq(index).find('img').attr('src');
        return '<div class="slide-btn"><img src=" ' + targetImage + ' "/></div>';
    },
    ...custom
});