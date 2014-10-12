/*
 * Adapted from: http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
 */
jQuery(document).ready(function($){
// Uploading files
    var file_frame;

    // attach event listener for User profile image
    $('#user-profile-upload').on('click', runMediaUploader);

    // attach 'live' event listener to widgets panel to dynamically handle click event
    $( "#widgets-right" ).on( "click", '.image-upload', runMediaUploader );

    // handle media uploads
    function runMediaUploader(event){

        event.preventDefault();

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: $( this ).data( 'uploader_title' ),
            button: {
                text: $( this ).data( 'uploader_button_text' ),
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected, run a callback.
        file_frame.on( 'select', function() {

            // We set multiple to false so only get one image from the uploader
            attachment = file_frame.state().get('selection').first().toJSON();

            // change preceding input's value to the attachment url
            $(event.currentTarget).prev().val(attachment.url);

            // add the image to the image preview div (author profile page only)
            $('#image-preview').attr('src', attachment.url);
        });

        // Finally, open the modal
        file_frame.open();
    }
});