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
            var menu = $('.menu-unset ul');
        }
        // get widths of all elements involved
        var siteHeaderWidth = $('#site-header').width();

        var menuWidth = menu.width();
        var titleInfoWidth = $('#title-info').width();
        var socialIcons = $('#menu-primary').find('.social-media-icons');

        if($('#site-description').css('display') == 'none'){
            var siteDescriptionWidth = 0;
        } else {
            var siteDescriptionWidth = $('#site-description').width();
        }

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
        menuPositioning();
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

});

// wait to see if a touch event is fired
var hasTouch;
window.addEventListener('touchstart', function setHasTouch () {
    hasTouch = true;

    // Remove event listener once fired
    window.removeEventListener('touchstart', setHasTouch);

    // since touch events are definitely being used, turn on the functionality
    // to require a double-click on parent dropdown items
    enableTouchDropdown();

}, false);

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