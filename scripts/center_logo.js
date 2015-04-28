(function ($) {

    $(document).ready(function () {

        var $navContainer = null;
        var selector = '';
       if ($('.topnav_section').length > 0) {
            $navContainer = $('.topnav_section');
            selector = '.topnav_section';
        } else if ($('#navigation').length > 0) {
            $navContainer = $('#navigation');
            selector = '#navigation';
        }

        if ($navContainer != null) {
            // hide it when page is still loading, so logo is not jumpy
            var $logo = $navContainer.find('.nav_section.second img');
            $logo.css('visibility', 'hidden');

            imagesLoaded( selector + ' .nav_section.second img', function() {
                var $logo = $navContainer.find('.nav_section.second img');

                var logoWidth = $logo.get(0).width;
                var logoHeight = $logo.get(0).height;
//                console.log('Logo width: ' + logoWidth);
//                console.log('Logo height: ' + logoHeight);
                var marginLeft = (-Math.ceil(logoWidth / 2)) + 'px';
                var marginTop = (-Math.ceil(logoHeight / 2)) + 'px';

                //$logo.closest('.nav_section').css('margin-left', marginLeft);
                if ($('#top').length == 0) {
                    //$logo.closest('.nav_section').css('margin-top', marginTop);
                } else {
                    //$logo.closest('.nav_section').css('top', '0');
                }

                $logo.closest('.nav_section').css('width', logoWidth + 'px');

                var firstPaddingRight = (Math.ceil(logoWidth / 2)) + 'px';
                var thirdPaddingLeft = (Math.ceil(logoWidth / 2)) + 'px';

                $navContainer.find('.nav_section.first').css('padding-right', firstPaddingRight);
                $navContainer.find('.nav_section.third').css('padding-left', thirdPaddingLeft);

                $logo.css('visibility', '');

            });
        }




        $(window).resize(function () {
            positionMobileLogo();
            positionBusinessSlider();
        });

        $('.nav-toggle a:last-child img').css('visibility', 'hidden');
        imagesLoaded('.nav-toggle a:last-child img', function () {
            positionMobileLogo();
            positionBusinessSlider();
        });

    });

    function positionBusinessSlider() {
        console.log('Position Business Slider');
        if (window.innerWidth < 768) {
            // is mobile
            if ($('body').hasClass('page-template-template-biz-php')) {
                var $slider = $('#nav-container').next('#loopedSlider');
                if ($slider.length > 0) {
                    var logoHeight = $('.nav-toggle a:last-child img').get(0).height;
                    var logoOffset = $('.nav-toggle a:last-child img').offset();
                    var sliderOffset = $slider.offset();

                    var newSliderOffsetTop = logoOffset.top + logoHeight + 20;

                    var topDiff = newSliderOffsetTop - sliderOffset.top;

                    if (topDiff > 0) {
                        var topPx = topDiff + "px";
                        $slider.css('position', 'relative');
                        $slider.css('top', topPx);
                    }
                }
            }
        } else {
            var $slider = $('#nav-container').next('#loopedSlider');
            if ($slider.length > 0) {
                $slider.css('top', '');
            }
        }
    }

    function positionMobileLogo() {
        var logoWidth = $('.nav-toggle a:last-child img').get(0).width;
        var toggleWidth = $('.nav-toggle').width();

        var leftOffset = (toggleWidth - logoWidth) / 2;
        $(".nav-toggle a:last-child").css('left', leftOffset + 'px');
        $(".nav-toggle a:last-child").css('top', '56px');


        $('.nav-toggle a:last-child img').css('visibility', '');
    }

})(jQuery);
