$(document).ready(function () {
    $(".hero_slider_inner").slick({
        dots: false,
        infinite: true,
        speed: 1000,
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 2000,
        arrows: true,
    });
});

$(document).ready(function () {
    $(".menu-a, .menu-overlay").click(function (e) {
        $(".sidebar").toggleClass("menu-open");
        $(".main-content").toggleClass("menu-active");
        if ($(window).width() < 992) {
            $(".menu-overlay").fadeToggle(400);
        }
        e.stopPropagation();
    });

    $(".profile_sec").on("click", function () {
        $(".profile-content").toggleClass("profile-open");
    });

    $(window).on("resize", function () {
        if ($(window).width() > 992) {
            $("body").removeClass("inner-page");
        } else {
            $("body").addClass("inner-page");
        }
    });
});
