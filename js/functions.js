jQuery(document).ready(function($){

    $(".entry-content").fitVids();
    $(".excerpt-content").fitVids();

    // use the tappy library here to remove 300ms click delay
    $('#toggle-navigation').bind('tap', onTap)

    function onTap() {
        // do work
        var menuWidth = $('#menu-primary').width();
        var newMenuHeight = $('#overflow-container').height();

        if ($('#site-header').hasClass('toggled')) {
            $('#site-header').removeClass('toggled')
            $('#main').css('transform', 'translateX(' + 0 + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + 0 + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + 0 + 'px)');
            $(window).unbind('scroll');
            // delayed so it isn't seen
            setTimeout(function() {
                $('#menu-primary').removeAttr('style');
            }, 400);
        } else {
            $('#site-header').addClass('toggled')
            $('#menu-primary').css('height', newMenuHeight);
            $('#main').css('transform', 'translateX(' + menuWidth + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + menuWidth + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + menuWidth + 'px)');
            $(window).scroll(onScroll);
        }
    }
    function onScroll() {
        var menuItemsBottom = $('#menu-primary-items').offset().top + $('#menu-primary-items').height();

        // keep updating var on scroll
        var topDistance = $(window).scrollTop();
        if (topDistance > menuItemsBottom) {
            $(window).unbind('scroll');
            onTap();
        }
    }

    /* allow keyboard access/visibility for dropdown menu items */
    $('.menu-item a, .page_item a').focus(function(){
        $(this).parent('li').addClass('focused');
        $(this).parents('ul').addClass('focused');
    });
    $('.menu-item a, .page_item a').focusout(function(){
        $(this).parent('li').removeClass('focused');
        $(this).parents('ul').removeClass('focused');
    });

    /* logo size tools */
    function displayLogoSizeInputs(){

        var logoSelector = $('#logo');
        var maxWidth = 156;
        var maxHeight = 59;

        // set max-width & max-height to initial values
        $(logoSelector).css({
            'max-width': maxWidth,
            'max-height': maxHeight
        });

        // if initially constrained by width, hide height input
        if($(logoSelector).width() == maxWidth){
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting').hide();
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting').show();

            // remove max-width applied to check constraint
            $(logoSelector).removeAttr('style');

            // reset max-height adjustment so it doesn't interfere. This will be save when the user saves.
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting input').val(0);

            // return b/c height could also be true after adjustment
            return;
        }

        // if initially constrained by height, hide width input
        if($(logoSelector).height() == maxHeight){
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting').hide();
            $('html', window.parent.document).find('#customize-control-logo_size_height_setting').show();

            // remove max-width applied to check constraint
            $(logoSelector).removeAttr('style');

            // reset max-width adjustment so it doesn't interfere. This will be save when the user saves.
            $('html', window.parent.document).find('#customize-control-logo_size_width_setting input').val(0);
        }
    }
    displayLogoSizeInputs();
});


/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function(event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }
        element.focus();
    }

}, false);