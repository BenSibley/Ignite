<?php
/*
 * Template Name: Full-width
 */

// get user's comment display setting
$comments_display = get_theme_mod('ct_ignite_comments_setting');

get_header();

// The loop
if ( have_posts() ) :
	while (have_posts() ) :
		the_post();
		get_template_part( 'content', 'page' );

		// error prevention
		if( is_array( $comments_display ) ) {

			// check for pages as a selected option
			if (in_array( 'pages', $comments_display ) ) {
				comments_template();
			}
		}
	endwhile;
endif;

get_footer();