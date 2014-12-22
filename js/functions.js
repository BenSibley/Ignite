jQuery(document).ready(function($){

    $(".entry-content").fitVids();
    $(".excerpt-content").fitVids();


    // in case user has logo increasing the height of the site-header
    function menuPositioning() {

        if( $(window).width() < 800 ) {
            var headerHeight = $('#site-header').outerHeight();

            // reposition menu slider and remove weird gap
            $('#menu-primary').css('top', headerHeight - 4 )
        } else {
            // if < 800 and then resized > 800, remove added style
            $('#menu-primary').removeAttr('style');
        }
    }
    menuPositioning();

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
                $('#menu-primary').css('height', 'auto');
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

        if( $(window).width() > 899 ) {

            // set menu variable to primary or unset
            if ($('#menu-primary-items').length) {
                var menu = $('#menu-primary-items');
            } else {
                var menu = $('.menu-unset ul');
            }

            // get width of the menu
            var menuWidth = menu.width();

            // get widths of site header
            var siteHeaderWidth = $('#site-header').width();

            // get width of the site title/logo container
            var titleInfoWidth = $('#title-info').width();

            // get the social icons
            var socialIcons = $('#menu-primary').find('.social-media-icons');

            // if site description is hidden, 0
            if ($('#site-description').css('display') == 'none') {
                var siteDescriptionWidth = 0;
            }
            // else get the width
            else {
                var siteDescriptionWidth = $('#site-description').width();
            }

            // remove visibility classes, so this works on resize
            $(socialIcons).removeClass('visible visible-top');

            // multiply # of icons by 68 b/c each is 68px wide
            var socialIconsWidth = $('#menu-primary').find('.social-media-icons li').length * 26;

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
    showSocialIcons();

    /* check to see if social icons can be displayed on resize */
    $(window).on('resize', function(){
        showSocialIcons();
        menuPositioning();

        if( $(window).width() > 799 && $('#site-header').hasClass('toggled') ) {
            onTap();
        }

    });

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
    $(document).on('click', reApplyClosedClass);

    // adjust height to fit footer into viewport instead of keeping it just out of view
    function adjustSiteHeight() {

        var footerHeight = $('.site-footer').outerHeight();

        $('body').css('height', 'calc(100% - ' + footerHeight + 'px)');
    }
    adjustSiteHeight();
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