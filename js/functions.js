jQuery(document).ready(function ($) {

    var body                    = $('body');
    var main                    = $('#main');
    var siteHeader              = $('#site-header');
    var menuPrimary             = $('#menu-primary');
    var menuPrimaryItems        = $('#menu-primary-items');
    var tagline                 = $('#site-description');
    var socialIcons             = menuPrimary.find('.social-media-icons');
    var menuUnset               = $('.menu-unset');
    var menuLinks               = $('.menu-item a, .page_item a');
    var sidebarPrimaryContainer = $('#sidebar-primary-container');
    var breadcrumbs             = $('.breadcrumbs');

    menuPositioning();
    showSocialIcons();
    adjustSiteHeight();
    objectFitAdjustment();

    $(".entry-content").fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });
    $(".excerpt-content").fitVids({
        customSelector: 'iframe[src*="dailymotion.com"], iframe[src*="slideshare.net"], iframe[src*="animoto.com"], iframe[src*="blip.tv"], iframe[src*="funnyordie.com"], iframe[src*="hulu.com"], iframe[src*="ted.com"], iframe[src*="wordpress.tv"]'
    });

    $(window).on('resize', function () {
        showSocialIcons();
        menuPositioning();
        objectFitAdjustment();

        if (window.innerWidth > 799 && siteHeader.hasClass('toggled')) {
            openPrimaryMenu();
        }
    });

    // allow keyboard access/visibility for dropdown menu items
    menuLinks.focus(function () {
        $(this).parent('li').addClass('focused');
        $(this).parents('ul').addClass('focused');
    });
    menuLinks.focusout(function () {
        $(this).parent('li').removeClass('focused');
        $(this).parents('ul').removeClass('focused');
    });

    // open primary menu
    $('#toggle-navigation').bind('click', openPrimaryMenu);

    // Jetpack infinite scroll event that reloads posts.
    $(document.body).on('post-load', function () {
        objectFitAdjustment();
    });

    // move mobile menu down, so it doesn't overlap extra tall site-header when a large logo is used
    function menuPositioning() {
        if ( window.innerWidth < 800 ) {
            var headerHeight = siteHeader.outerHeight();
            menuPrimary.css('top', headerHeight - 4)
        } else {
            menuPrimary.removeAttr('style');
        }
    }

    function openPrimaryMenu() {

        var menuWidth     = menuPrimary.width();
        var newMenuHeight = $('#overflow-container').height();

        if (siteHeader.hasClass('toggled')) {
            siteHeader.removeClass('toggled');
            main.css('transform', 'translateX(' + 0 + 'px)');
            breadcrumbs.css('transform', 'translateX(' + 0 + 'px)');
            sidebarPrimaryContainer.css('transform', 'translateX(' + 0 + 'px)');
            $(window).unbind('scroll');

            setTimeout(function () {
                menuPrimary.css('height', 'auto');
            }, 400);
        } else {
            siteHeader.addClass('toggled');
            menuPrimary.css('height', newMenuHeight);
            main.css('transform', 'translateX(' + menuWidth + 'px)');
            breadcrumbs.css('transform', 'translateX(' + menuWidth + 'px)');
            sidebarPrimaryContainer.css('transform', 'translateX(' + menuWidth + 'px)');
            $(window).scroll(closeMenuOnScroll);
        }
    }

    function closeMenuOnScroll() {

        var menuItemsBottom = '';
        var topDistance     = $(window).scrollTop();

        if (menuPrimaryItems.length) {
            menuItemsBottom = menuPrimaryItems.offset().top + menuPrimaryItems.height();
        } else {
            menuItemsBottom = menuUnset.offset().top + menuUnset.height();
        }

        if (topDistance > menuItemsBottom) {
            $(window).unbind('scroll');
            openPrimaryMenu();
        }
    }

    function showSocialIcons() {

        if (window.innerWidth > 899) {

            var siteDescriptionWidth = 0;
            var menuWidth            = 0;
            var siteHeaderWidth      = siteHeader.width();
            var titleInfoWidth       = $('#title-info').width();
            var menu                 = '';

            if ( menuPrimaryItems.length ) {
                menu = menuPrimaryItems;
            } else {
                menu = $('.menu-unset ul');
            }
            menuWidth = menu.width();

            if ( tagline.css('display') != 'none' ) {
                siteDescriptionWidth = tagline.width();
            }

            socialIcons.removeClass('visible visible-top');

            // multiply # of icons by 68 b/c each is 68px wide
            var socialIconsWidth = menuPrimary.find('.social-media-icons li').length * 26;

            // If site-header has space for social icons + 48 margin + 48 extra margin, show them
            if ((siteHeaderWidth - menuWidth - titleInfoWidth - siteDescriptionWidth) > socialIconsWidth + 96) {
                socialIcons.addClass('visible');
                menu.removeClass('clear');
            }
            // If site-header does not have space for everything
            if ((siteHeaderWidth - menuWidth - titleInfoWidth - siteDescriptionWidth) < socialIconsWidth + 96) {
                socialIcons.addClass('visible-top');
                menu.addClass('clear');
            }
        }
    }

    // adjust height to fit footer into viewport instead of keeping it just out of view
    function adjustSiteHeight() {
        var footerHeight = $('#site-footer').outerHeight();
        body.css('height', 'calc(100% - ' + footerHeight + 'px)');
    }

    // mimic cover positioning without using cover
    function objectFitAdjustment() {

        // if the object-fit property is not supported
        if (!('object-fit' in document.body.style)) {

            $('.featured-image').each(function () {

                var image = $(this).children('img').add($(this).children('a').children('img'));

                // don't process images twice (relevant when using infinite scroll)
                if (image.hasClass('no-object-fit')) return;

                image.addClass('no-object-fit');

                // if the image is not wide enough to fill the space
                if (image.outerWidth() < $(this).outerWidth()) {

                    image.css({
                        'width': '100%',
                        'min-width': '100%',
                        'max-width': '100%',
                        'height': 'auto',
                        'min-height': '100%',
                        'max-height': 'none'
                    });
                }
                // if the image is not tall enough to fill the space
                if (image.outerHeight() < $(this).outerHeight()) {

                    image.css({
                        'height': '100%',
                        'min-height': '100%',
                        'max-height': '100%',
                        'width': 'auto',
                        'min-width': '100%',
                        'max-width': 'none'
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
function setHasTouch() {

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
function enableTouchDropdown() {

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
function openDropdown(e) {

    // if has 'closed' class...
    if (hasClass(this, 'closed')) {
        // prevent link from being visited
        e.preventDefault();
        // remove 'closed' class to enable link
        this.className = this.className.replace('closed', '');
    }
}

/* fix for skip-to-content link bug in Chrome & IE9 */
window.addEventListener("hashchange", function (event) {

    var element = document.getElementById(location.hash.substring(1));

    if (element) {

        if (!/^(?:a|select|input|button|textarea)$/i.test(element.tagName)) {
            element.tabIndex = -1;
        }
        element.focus();
    }

}, false);