jQuery(document).ready(function($){

    $(".entry-content").fitVids();
    $(".excerpt-content").fitVids();

    // no longer using tappy library here b/c doesn't work when loaded asynchronously
    $('#toggle-navigation').bind('click', onTap);

    function onTap() {
        // do work
        var menuWidth = $('#menu-primary').width();
        var newMenuHeight = $('#overflow-container').height();

        if ($('#site-header').hasClass('toggled')) {
            $('#site-header').removeClass('toggled');
            $('#main').css('transform', 'translateX(' + 0 + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + 0 + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + 0 + 'px)');
            $(window).unbind('scroll');
            // delayed so it isn't seen
            setTimeout(function() {
                $('#menu-primary').removeAttr('style');
            }, 400);
        } else {
            $('#site-header').addClass('toggled');
            $('#menu-primary').css('height', newMenuHeight);
            $('#main').css('transform', 'translateX(' + menuWidth + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + menuWidth + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + menuWidth + 'px)');
            $(window).scroll(onScroll);
        }
    }
    function onScroll() {

        if($('#menu-primary-items').length){
            var menuItemsBottom = $('#menu-primary-items').offset().top + $('#menu-primary-items').height();
        } else {
            var menuItemsBottom = $('.menu-unset').offset().top + $('.menu-unset').height();
        }

        // keep updating var on scroll
        var topDistance = $(window).scrollTop();
        if (topDistance > menuItemsBottom) {
            $(window).unbind('scroll');
            onTap();
        }
    }

    /* see if social media icons can fit and display if they can */
    function showSocialIcons() {

        if($('#menu-primary-items').length){
            var menu = $('#menu-primary-items');
        } else {
            var menu = $('.menu-unset');
        }
        // get widths of all elements involved
        var siteHeaderWidth = $('#site-header').width();

        var menuWidth = menu.width();

        var titleInfoWidth = $('#title-info').width();
        var siteDescriptionWidth = $('#site-description').width();
        var socialIcons = $('#menu-primary').find('.social-media-icons');

        // remove the classes
        $(socialIcons).removeClass('visible visible-top');

        /* multiply # of icons by 68 b/c each is 68px wide */
        var socialIconsWidth = $('#menu-primary').find('.social-media-icons li').length * 26;

        /* If site-header has space for social icons + 48 margin + 48 extra margin, show them */
        if ( (siteHeaderWidth - menuWidth - titleInfoWidth - siteDescriptionWidth) > socialIconsWidth + 96) {
            $(socialIcons).addClass('visible');
        }
        /* if the menu is on the next line, display the social icons */
        if( menu.offset().top > $('#title-info').offset().top ){
            $(socialIcons).addClass('visible-top');
        }
    }
    showSocialIcons();

    /* check to see if social icons can be displayed on resize */
    $(window).on('resize', function(){
        showSocialIcons();
    });

    /* allow keyboard access/visibility for dropdown menu items */
    $('.menu-item a, .page_item a').focus(function(){
        setTimeout(function() {
            $(this).parent('li').addClass('focused');
            $(this).parents('ul').addClass('focused');
        }, 0);
    });
    $('.menu-item a, .page_item a').focusout(function(){
        $(this).parent('li').removeClass('focused');
        $(this).parents('ul').removeClass('focused');
    });
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