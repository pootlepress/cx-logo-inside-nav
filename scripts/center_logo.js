(function ($) {

    $(document).ready(function () {

        // hide it when page is still loading, so logo is not jumpy
        var $logo = $('#navigation .nav_section.second img');
        $logo.css('visibility', 'hidden');

        imagesLoaded( '#navigation .nav_section.second img', function() {
            var $logo = $('#navigation .nav_section.second img');

            var logoWidth = $logo.get(0).width;
            var logoHeight = $logo.get(0).height;
            console.log('Logo width: ' + logoWidth);
            console.log('Logo height: ' + logoHeight);
            var marginLeft = (-Math.ceil(logoWidth / 2)) + 'px';
            var marginTop = (-Math.ceil(logoHeight / 2)) + 'px';

            $logo.closest('.nav_section').css('margin-left', marginLeft);
            $logo.closest('.nav_section').css('margin-top', marginTop);
            $logo.closest('.nav_section').css('width', logoWidth + 'px');

            var firstPaddingRight = (Math.ceil(logoWidth / 2)) + 'px';
            var thirdPaddingLeft = (Math.ceil(logoWidth / 2)) + 'px';

            $('#navigation .nav_section.first').css('padding-right', firstPaddingRight);
            $('#navigation .nav_section.third').css('padding-left', thirdPaddingLeft);

            $logo.css('visibility', '');

        });

        $(window).resize(function () {
            positionMobileLogo();
        });

        imagesLoaded('.nav-toggle a:last-child img', function () {
            positionMobileLogo();

            $(window).resize(function () {
                positionMobileLogo();
            });
        });
    });

    function positionMobileLogo() {
        var logoWidth = $('.nav-toggle a:last-child img').get(0).width;
        var toggleWidth = $('.nav-toggle').width();

        var leftOffset = (toggleWidth - logoWidth) / 2;
        $(".nav-toggle a:last-child").css('left', leftOffset + 'px');
        $(".nav-toggle a:last-child").css('top', '56px');
    }

})(jQuery);
