/* ------------------------------------- */
/* Loading / Opening
/* ------------------------------------- */

$(window).on('load', function () {
    "use strict";

    $('.brand-logo, #fp-nav, .main-content, header').addClass('before-loading');

    setTimeout(function () {

        $(".loader").addClass('loaded');

    }, 2600);

    setTimeout(function () {

        $("#loading").addClass('loaded');

    }, 2800);

    setTimeout(function () {

        $(".brand-logo").removeClass('before-loading').addClass('loaded');

    }, 3000);

    setTimeout(function () {

        $("#fp-nav, .main-content").removeClass('before-loading').addClass('loaded');

    }, 3200);

    setTimeout(function () {

        $("header").removeClass('before-loading').addClass('loaded');

    }, 3400);

    setTimeout(function () {

        $("#loading").remove();
        $(".brand-logo, header").addClass('after-load');

    }, 3600);

    setTimeout(function () {

        $("#fp-nav").addClass('notransition');

    }, 4800);

});

$(document).ready(function () {
    "use strict";

    /* ------------------------------------- */
    /* FullPage init
    /* ------------------------------------- */

    $('#fullpage').fullpage({
        menu: '#topMenu',
        anchors: ['home', 'what', 'why', 'technology', 'about'],
        // scrollOverflow: false,
        autoScrolling: true,
        navigation: true,
        navigationPosition: 'left',
        navigationTooltips: ['Home', 'What', 'Why', 'Our Technology', 'About us'],
        licenseKey: '98AFD12E-428246E1-B7908FFE-69A37211'
    });

    /* ------------------------------------- */
    /* Menu button
    /* ------------------------------------- */

    $('#small-screen-menu').on("click", function () {

        $("#fullpage, #fp-nav, .brand-logo, header").toggleClass("menu-opened");
        $("body, html").toggleClass("modal-open");

        $(this).toggleClass('open').toggleClass('custom-menu');
    });

    /* ------------------------------------- */
    /* Transition for nav & logo position
    /* ------------------------------------- */

    $(window).on("load resize", function () {
        if ($(this).width() < 1201) {
            $("#fp-nav").addClass("transition-desktop-mobile");
        }

        else if ($(this).height() < 750) {
            $("#fp-nav").addClass("transition-desktop-mobile");
        }

        else {
            $("#fp-nav").removeClass("transition-desktop-mobile");
        }
    });

    /* ------------------------------------- */
    /* Text rotator on loading screen
    /* ------------------------------------- */

    function dataWord() {

        $("[data-words]").attr("data-words", function (i, d) {
            var $self = $(this),
                $words = d.split("|"),
                tot = $words.length,
                c = 0;

            for (var loadtext = 0; loadtext < tot; loadtext++) { $self.append($('<span/>', { text: $words[loadtext] })); }

            $words = $self.find("span").hide();

            (function loop() {
                $self.animate({ width: $words.eq(c).width() });
                $words.stop().fadeOut().eq(c).fadeIn().delay(750).show(0, loop);
                c = ++c % tot;
            }());

        });

    }

    dataWord();

});