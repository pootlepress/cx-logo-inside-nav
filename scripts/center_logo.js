(function ($) {

    $(document).ready(function () {

        $('#navigation .nav_section.second img').load(function () {
            var logoWidth = $(this).width();
            var logoHeight = $(this).height();
            var marginLeft = (-Math.ceil(logoWidth / 2)) + 'px';
            var marginTop = (-Math.ceil(logoHeight / 2)) + 'px';

            $(this).closest('.nav_section').css('margin-left', marginLeft);
            $(this).closest('.nav_section').css('margin-top', marginTop);
            $(this).closest('.nav_section').css('width', logoWidth + 'px');
        });

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
        $(".nav-toggle a:last-child").css('top', '56px');
    }

})(jQuery);
