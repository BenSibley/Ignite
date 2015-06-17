( function( $ ) {

    /*
     * Following functions are for utilizing the postMessage transport setting
     */

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
                $('#title-container').append('<p id="site-description"></p>');
            }
            tagline.text( to );
        } );
    } );

} )( jQuery );