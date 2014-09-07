<?php get_header(); ?>
   
<?php

// The loop
if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();

        /* Blog */
        if( is_home() ) {
            get_template_part( 'content' );
        }
        /* Post */
        elseif( is_singular( 'post' ) ) {
            get_template_part( 'content' );
            comments_template();
        }
        /* Page */
        elseif( is_page() ) {
            get_template_part( 'content', 'page' );
            comments_template();
        }
        /* Attachment */
        elseif( is_attachment() ) {
            get_template_part( 'content', 'attachment' );
            comments_template();
        }
        /* Archive */
        elseif( is_archive() ) {

            /* check if bbPress is active */
            if( function_exists( 'is_bbpress' ) ) {

                /* bbPress forum list */
                if( is_bbpress() ) {
                    get_template_part( 'content/bbpress' );
                }
                /* normal archive */
                else {
                    get_template_part( 'content' );
                }
            }
            /* Archive */
            else {
                get_template_part( 'content' );
            }
        }
        /* bbPress */
        elseif( is_bbpress() ) {
            get_template_part( 'content/bbpress' );
        }
        /* Custom Post Type */
        else {
            get_template_part( 'content' );
        }

    endwhile;
endif; ?>

           
<?php ct_ignite_post_navigation(); ?>
    
<?php get_footer(); ?>