jQuery(document).ready(function($) {

    $('#ignite-avatar-notice').on('click', '.notice-dismiss', function(){

        // set up data object
        var data = {
            action: 'dismiss_ignite_avatar_notice',
            dismissed: true,
            security: '<?php echo $ajax_nonce; ?>'
        };

        // post data received from PHP respond
        jQuery.post(ajaxurl, data);
    });
});