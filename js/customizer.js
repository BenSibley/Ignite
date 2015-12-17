jQuery(document).ready(function($){

    $('#comment-display-control').multipleSelect({
        selectAll: false
    });

    // hide the display none option
    $.each($('.ms-drop.bottom').find('li'), function(){

        if( $(this).find('input').val() == 'none' ) {
            $(this).hide();
        }
    });

    ctHideNoneText();

    $('.ms-drop.bottom').find('li').bind('click', ctHideNoneText);

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
                $(this).text("Comments not displaying");
            }
        });
    }

    // move control descriptions for certain sections (advertisements) below the control
    $('#customize-control-ct_ignite_sidebar_layout').find('.customize-control-description').appendTo( '#customize-control-ct_ignite_sidebar_layout' ).css('margin-top', '12px');
    $('#customize-control-ct_ignite_font_family_settings').find('.customize-control-description').appendTo( '#customize-control-ct_ignite_font_family_settings' ).css('margin-top', '12px');
    $('#customize-control-ct_ignite_background_color').find('.customize-control-description').appendTo( '#customize-control-ct_ignite_background_color').css('margin-top', '12px');
});
