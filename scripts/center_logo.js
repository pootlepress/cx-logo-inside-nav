(function ($) {

    $(document).ready(function () {
        $(window).resize(function () {
            positionMobileLogo();
        });

        positionMobileLogo();
    });

    function positionMobileLogo() {
        var logoWidth = $('.nav-toggle a:last-child').width();
        var toggleWidth = $('.nav-toggle').width();

        var leftOffset = (toggleWidth - logoWidth) / 2;
        $(".nav-toggle a:last-child").css('left', leftOffset + 'px');
    }

})(jQuery);
