( function( $ ) {

    var panel = $('html', window.parent.document);
    var body = $('body');
    var siteTitle = $('.site-title');
    var siteTitleLink = siteTitle.children('a');
    var logo = $('#logo');
    var inlineStyles = $('#ct-ignite-style-inline-css');
    var fontSelectors = "body, h1, h2, h3, h4, h5, h6, input:not([type='checkbox']):not([type='radio']):not([type='submit']):not([type='file']), input[type='submit'], textarea";

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            if( siteTitle.find('img').length == 0 ) {
                siteTitle.children('a').text( to );
            }
        } );
    } );
    // Tagline
    wp.customize( 'blogdescription', function( value ) {
        value.bind( function( to ) {
            var tagline = $('.tagline');
            if( tagline.length == 0 ) {
                $('#menu-primary').prepend('<p id="site-description" class="tagline"></p>');
                if ( $('#menu-secondary').length > 0 ) {
                    $('#menu-secondary').after('<span class="tagline"></span>');
                } else {
                    $('#site-footer').children('h3').after('<span class="tagline"></span>');
                }
            }
            tagline.text( to );
        } );
    } );
    wp.customize( 'logo_upload', function( value ) {
        value.bind( function( to ) {
            var siteTitleText = siteTitleLink.attr('title');
            var logo          = siteTitleText;

            if( to ) {
                logo = "<span class='screen-reader-text'>" + siteTitleText + "</span><img id='logo' class='logo' src='" + to + "' alt='" + siteTitleText + "' />";
            }

            siteTitleLink.empty();
            siteTitleLink.append(logo);
        } );
    } );
    // Logo Position - up/down
    wp.customize( 'logo_positioning_updown_setting', function( value ) {
        value.bind( function( to ) {
            logo.css({
                'bottom': to + 'px',
                'position': 'relative',
                'right'   : 'auto',
                'left'    : 'auto'
            });
        } );
    } );
    // Logo Position - left/right
    wp.customize( 'logo_positioning_leftright_setting', function( value ) {
        value.bind( function( to ) {
            logo.css({
                'left': to + 'px',
                'position': 'relative',
                'right'   : 'auto'
            });
        } );
    } );
    // Logo Size - width
    wp.customize( 'logo_size_width_setting', function( value ) {
        value.bind( function( to ) {
            var newVal = parseInt(to) + 156;
            logo.css('max-width', newVal + 'px');
        } );
    } );
    // Logo Size - height
    wp.customize( 'logo_size_height_setting', function( value ) {
        value.bind( function( to ) {
            var newVal = parseInt(to) + 59;
            logo.css('max-height', newVal + 'px');
        } );
    } );
    // Layout
    wp.customize( 'ct_ignite_layout_settings', function( value ) {
        value.bind( function( to ) {
            body.removeClass('sidebar-left');

            if ( to == 'left' ) {
                body.addClass( 'sidebar-left' );
            }
        } );
    } );
    // Background Color
    wp.customize( 'ct_ignite_background_color_setting', function( value ) {
        value.bind( function( to ) {

            if ( to == '#eeede8' ) {
                $('.overflow-container, .main, .sidebar-primary-container, .breadcrumb-trail').css('background', to);
            } else {
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

    /***** Fonts *****/

    wp.customize( 'ct_ignite_font_family_settings', function( value ) {
        value.bind( function( to ) {

            // change font CSS
            $( fontSelectors ).css('font-family', to);

            /* load new font */

            // get from localization object
            ajaxurl = ignite_ajax.ajaxurl;

            // set up data object
            var data = {
                action: 'format_font_request',
                font: to,
                url: ajaxurl,
                security: '<?php echo $ajax_nonce; ?>'
            };

            // post data received from PHP response
            jQuery.post(ajaxurl, data, function(response) {

                // if valid response
                if( response ){
                    $('<link rel="stylesheet" type="text/css" href="'+response+'" >').appendTo('head');
                }
            });
        });
    } );

    wp.customize( 'ct_ignite_font_weight_settings', function( value ) {
        value.bind( function( to ) {

            var fontStyle = 'normal';

            // change "regular" to 400
            if ( to == 'regular' ) {
                to = '400';
            }
            // change "italic" to 400
            else if ( to == 'italic' ) {
                to = '400';
                fontStyle = 'italic';
            }
            // if contains italic, but wasn't just "italic"
            else if ( to.indexOf( 'italic' ) > -1 ) {
                to = to.replace( 'italic', '' );
                fontStyle = 'italic';
            }

            // change font weight
            $( fontSelectors ).css({
                'font-weight': to,
                'font-style': fontStyle
            });
        } );
    } );

} )( jQuery );