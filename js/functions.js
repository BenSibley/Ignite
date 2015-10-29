jQuery(document).ready(function($){

    var body = $('body');
    var main = $('#main');
    var siteHeader = $('#site-header');
    var menuPrimary = $('#menu-primary');
    var menuPrimaryItems = $('#menu-primary-items');
    var tagline = $('#site-description');
    var menuUnset = $('.menu-unset');

    menuPositioning();
    showSocialIcons();
    adjustSiteHeight();
    objectFitAdjustment();

    /* check to see if social icons can be displayed on resize */
    $(window).on('resize', function(){
        showSocialIcons();
        menuPositioning();
        objectFitAdjustment();

        if( window.innerWidth > 799 && siteHeader.hasClass('toggled') ) {
            onTap();
        }
    });

    $(".entry-content").fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });
    $(".excerpt-content").fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    // open primary menu
    $('#toggle-navigation').bind('click', onTap);

    $(document).on('click', reApplyClosedClass);

    // Jetpack infinite scroll event that reloads posts.
    $( document.body ).on( 'post-load', function () {
        objectFitAdjustment();
    } );

    // in case user has logo increasing the height of the site-header
    function menuPositioning() {

        if( window.innerWidth < 800 ) {
            var headerHeight = siteHeader.outerHeight();

            // reposition menu slider and remove weird gap
            menuPrimary.css('top', headerHeight - 4 )
        } else {
            // if < 800 and then resized > 800, remove added style
            menuPrimary.removeAttr('style');
        }
    }

    function onTap() {
        // do work
        var menuWidth = menuPrimary.width();
        var newMenuHeight = $('#overflow-container').height();

        if ( siteHeader.hasClass('toggled') ) {
            siteHeader.removeClass('toggled');
            main.css('transform', 'translateX(' + 0 + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + 0 + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + 0 + 'px)');
            $(window).unbind('scroll');
            // delayed so it isn't seen
            setTimeout(function() {
                menuPrimary.css('height', 'auto');
            }, 400);
        } else {
            siteHeader.addClass('toggled');
            menuPrimary.css('height', newMenuHeight);
            main.css('transform', 'translateX(' + menuWidth + 'px)');
            $('.breadcrumbs').css('transform', 'translateX(' + menuWidth + 'px)');
            $('#sidebar-primary-container').css('transform', 'translateX(' + menuWidth + 'px)');
            $(window).scroll(onScroll);
        }
    }

    function onScroll() {

        if(menuPrimaryItems.length){
            var menuItemsBottom = menuPrimaryItems.offset().top + menuPrimaryItems.height();
        } else {
            var menuItemsBottom = menuUnset.offset().top + menuUnset.height();
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

        if( window.innerWidth > 899 ) {

            // set menu variable to primary or unset
            if (menuPrimaryItems.length) {
                var menu = menuPrimaryItems;
            } else {
                var menu = $('.menu-unset ul');
            }

            // get width of the menu
            var menuWidth = menu.width();

            // get widths of site header
            var siteHeaderWidth = siteHeader.width();

            // get width of the site title/logo container
            var titleInfoWidth = $('#title-info').width();

            // get the social icons
            var socialIcons = menuPrimary.find('.social-media-icons');

            // if site description is hidden, 0
            if (tagline.css('display') == 'none') {
                var siteDescriptionWidth = 0;
            }
            // else get the width
            else {
                var siteDescriptionWidth = tagline.width();
            }

            // remove visibility classes, so this works on resize
            $(socialIcons).removeClass('visible visible-top');

            // multiply # of icons by 68 b/c each is 68px wide
            var socialIconsWidth = menuPrimary.find('.social-media-icons li').length * 26;

            /* If site-header has space for social icons + 48 margin + 48 extra margin, show them */
            if ((siteHeaderWidth - menuWidth - titleInfoWidth - siteDescriptionWidth) > socialIconsWidth + 96) {
                $(socialIcons).addClass('visible');
                $(menu).removeClass('clear');
            }
            /* If site-header does not have space for everything */
            if ((siteHeaderWidth - menuWidth - titleInfoWidth - siteDescriptionWidth) < socialIconsWidth + 96) {
                $(socialIcons).addClass('visible-top');
                $(menu).addClass('clear');
            }
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

    // reapply closed class for touch device usage
    // doesn't have any impact unless 'touchstart' fired
    function reApplyClosedClass(e) {

        var container = $('.menu-item-has-children');

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.addClass('closed');
        }
    }

    // adjust height to fit footer into viewport instead of keeping it just out of view
    function adjustSiteHeight() {

        var footerHeight = $('.site-footer').outerHeight();

        body.css('height', 'calc(100% - ' + footerHeight + 'px)');
    }

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if( !('object-fit' in document.body.style) ) {

            $('.featured-image').each(function () {

                var image = $(this).children('img').add( $(this).children('a').children('img') );

                image.addClass('no-object-fit');

                // if the image is not tall enough to fill the space
                if ( image.outerHeight() < $(this).outerHeight()) {

                    // is it also not wide enough?
                    if ( image.outerWidth() < $(this).outerWidth()) {
                        image.css({
                            'min-width': '100%',
                            'min-height': '100%',
                            'max-width': 'none',
                            'max-height': 'none'
                        });
                    } else {
                        image.css({
                            'height': '100%',
                            'max-width': 'none'
                        });
                    }
                }
                // if the image is not wide enough to fill the space
                else if ( image.outerWidth() < $(this).outerWidth()) {

                    image.css({
                        'width': '100%',
                        'max-height': 'none'
                    });
                }
            });
        }
    }
});

// wait to see if a touch event is fired
var hasTouch;
window.addEventListener('touchstart', setHasTouch, false);

// require a double-click on parent dropdown items
function setHasTouch () {

    // since touch events are definitely being used, turn on the functionality
    hasTouch = true;

    // Remove event listener once fired
    window.removeEventListener('touchstart', setHasTouch);

    // get the width of the window
    var w = window,
        d = document,
        e = d.documentElement,
        g = d.getElementsByTagName('body')[0],
        x = w.innerWidth || e.clientWidth || g.clientWidth;

    // don't require double clicks for the toggle menu
    if (x > 799) {
        enableTouchDropdown();
    }
}

// require a second click to visit parent navigation items
function enableTouchDropdown(){

    // get all the parent menu items
    var menuParents = document.getElementsByClassName('menu-item-has-children');

    // add a 'closed' class to each and add an event listener to them
    for (i = 0; i < menuParents.length; i++) {
        menuParents[i].className = menuParents[i].className + " closed";
        menuParents[i].addEventListener('click', openDropdown);
    }
}

// check if an element has a class
function hasClass(element, cls) {
    return (' ' + element.className + ' ').indexOf(' ' + cls + ' ') > -1;
}

// open the dropdown without visiting parent link
function openDropdown(e){

    // if has 'closed' class...
    if(hasClass(this, 'closed')){
        // prevent link from being visited
        e.preventDefault();
        // remove 'closed' class to enable link
        this.className = this.className.replace('closed', '');
    }
}

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