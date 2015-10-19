( function( $ ) {

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

    var panel = $('html', window.parent.document);
    var body = $('body');
    var siteTitle = $('.site-title');
    var inlineStyles = $('#style-inline-css');

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( siteTitle.find('img').length == 0 ) {
                siteTitle.children('a').text( to );
            }
        } );
    } );
    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            var tagline = $('#site-description');
            if( tagline.length == 0 ) {
                $('#menu-primary').prepend('<p id="site-description"></p>');
            }
            tagline.text( to );
        } );
    } );
    // Layout
    wp.customize( 'ct_ignite_layout_settings', function( value ) {
        value.bind( function( to ) {

            // remove left-sidebar class to avoid adding both
            body.removeClass('sidebar-left');

            // add left-sidebar class (right doesn't have/need one)
            if ( to == 'left' ) {
                body.addClass( 'sidebar-left' );
            }
        } );
    } );
    // Background Color
    wp.customize( 'ct_ignite_background_color_setting', function( value ) {
        value.bind( function( to ) {

            // all elements get default background
            if ( to == '#eeede8' ) {
                $('.overflow-container, .main, .sidebar-primary-container, .breadcrumb-trail').css('background', to);
            }  // or all elements get no background except overflow-container
            else {
                $('.overflow-container').css('background', to);
                $('.main, .sidebar-primary-container, .breadcrumb-trail').css('background', 'none');
            }
        });
    } );
    // Footer Text
    wp.customize( 'ct_ignite_footer_text_setting', function( value ) {
        value.bind( function( to ) {

            if ( to == '' ) {
                to = '<a target="_blank" href="https://www.competethemes.com/ignite/">Ignite WordPress Theme</a> by Compete Themes.'
            }
            $('.design-credit').children('span').html(to);
        });
    } );

    /***** Custom CSS *****/

    // get current Custom CSS
    var customCSS = panel.find('#customize-control-ct_ignite_custom_css_setting').find('textarea').val();

    // get the CSS in the inline element
    var allCSS = inlineStyles.text();

    // remove the Custom CSS from the other CSS
    allCSS = allCSS.replace(customCSS, '');

    // update the CSS in the inline element w/o the custom css
    inlineStyles.text(allCSS);

    // add custom CSS to its own style element
    body.append('<style id="style-inline-custom-css" type="text/css">' + customCSS + '</style>');

    // Custom CSS
    wp.customize( 'ct_ignite_custom_css_setting', function( value ) {
        value.bind( function( to ) {
            $('#style-inline-custom-css').remove();
            if ( to != '' ) {
                to = '<style id="style-inline-custom-css" type="text/css">' + to + '</style>';
                body.append( to );
            }
        } );
    } );

} )( jQuery );