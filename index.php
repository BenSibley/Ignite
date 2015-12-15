<?php get_header(); ?>
    <div id="loop-container" class="loop-container">
        <?php
        if ( have_posts() ) :
            while (have_posts() ) :
                the_post();
                ct_ignite_get_content_template();
            endwhile;
        endif;
        ?>
    </div>
<?php

// include loop pagination except for on bbPress Forum root
if( function_exists( 'is_bbpress' ) ) {
    if( ! ( is_bbpress() && is_archive() ) ) {
        echo ct_ignite_loop_pagination();
    }
} else {
    echo ct_ignite_loop_pagination();
}

get_footer();