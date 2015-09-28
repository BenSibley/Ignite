( function( $ ) {

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

    var body = $('body');

    // Site title
    wp.customize( 'blogname', function( value ) {
        value.bind( function( to ) {
            // if there is a logo, don't replace it
            if( $('.site-title').find('img').length == 0 ) {
                $( '.site-title a' ).text( to );
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

} )( jQuery );