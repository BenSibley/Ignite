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
        $('.ms-choice span:contains("Do not show")').each(function(){
            $(this).html($(this).html().split("Do not show").join(""));

            if (!$(this).text().trim().length) {
                console.log('empty');
                $(this).text("Comments not displaying");
            }
        });
    }
    ctHideNoneText();

    $('.ms-drop.bottom').find('li').bind('click', ctHideNoneText);

});
