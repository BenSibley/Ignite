jQuery(document).ready(function($){

    // add multiple select styling
    $('#comment-display-control').multipleSelect({
        selectAll: false
    });

    // hide the display none option
    $.each($('.ms-drop.bottom').find('li'), function(){

        if( $(this).find('input').val() == 'none' ) {
            $(this).hide();
        }
    });

    // Don't show the do not show text
    function ctHideNoneText() {

        // hide the "Do not show" text from the list of selected options
        $('.ms-choice span:contains("Do not show")').each(function(){

            // remove the text
            $(this).html($(this).html().split("Do not show").join(""));

            // remove trailing commas left over
            if( $(this).html().trim().slice(-1) == ',' ) {
                $(this).html( $(this).html().trim().slice(0, -1) );
            }

            // text to display instead if empty
            if (!$(this).text().trim().length) {
                console.log('empty');
                $(this).text("Comments not displaying");
            }
        });
    }
    ctHideNoneText();

    $('.ms-drop.bottom').find('li').bind('click', ctHideNoneText);

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

    wp.customize( 'logo_upload', function( value ) {
        value.bind( function( newval ) {
            // get the <a> holding the logo/site title
            var logoContainer = $('#customize-preview iframe').contents().find('#title-info').find('a');
            // get the name of the site from the <a>
            var siteTitle = logoContainer.attr('title');
            // if there is an image, add the image markup
            if( newval ) {
                var logo = "<span class='screen-reader-text'>" + siteTitle + "</span><img id='logo' class='logo' src='" + newval + "' alt='" + siteTitle + "' />";
            }
            // otherwise just use the site title
            else {
                var logo = siteTitle;
            }
            // empty the content first
            logoContainer.empty();
            // replace with the new logo markup
            logoContainer.append(logo);

        } );
    } );
    // Logo Position - up/down
    wp.customize( 'logo_positioning_updown_setting', function( value ) {
        value.bind( function( newval ) {
            // get the logo from the DOM
            var logo = $('#customize-preview iframe').contents().find('#logo');
            // add new css
            logo.css({
                'bottom': newval + 'px',
                'position': 'relative',
                'right'   : 'auto',
                'left'    : 'auto'
            });
        } );
    } );
    // Logo Position - left/right
    wp.customize( 'logo_positioning_leftright_setting', function( value ) {
        value.bind( function( newval ) {
            // get the logo from the DOM
            var logo = $('#customize-preview iframe').contents().find('#logo');
            // add new css
            logo.css({
                'left': newval + 'px',
                'position': 'relative',
                'right'   : 'auto'
            });
        } );
    } );
    // Logo Size - width
    wp.customize( 'logo_size_width_setting', function( value ) {
        value.bind( function( newval ) {
            // get the logo from the DOM
            var logo = $('#customize-preview iframe').contents().find('#logo');
            // add new css
            var newval = parseInt(newval) + 156;
            logo.css('max-width', newval + 'px');
        } );
    } );
    // Logo Size - height
    wp.customize( 'logo_size_height_setting', function( value ) {
        value.bind( function( newval ) {
            // get the logo from the DOM
            var logo = $('#customize-preview iframe').contents().find('#logo');
            // add new css
            var newval = parseInt(newval) + 59;
            logo.css('max-height', newval + 'px');
        } );
    } );

});
