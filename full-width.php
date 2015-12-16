<?php
/*
 * Template Name: Full-width
 */

$comments_display = get_theme_mod( 'ct_ignite_comments_setting' );

get_header(); ?>

<div id="loop-container" class="loop-container">
	<?php
	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();
			get_template_part( 'content', 'page' );
			if ( is_array( $comments_display ) ) {
				if ( in_array( 'pages', $comments_display ) ) {
					comments_template();
				}
			}
		endwhile;
	endif; ?>
</div>

<?php get_footer();