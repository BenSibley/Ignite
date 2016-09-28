<?php get_header();

if ( is_home() ) {
	echo '<h1 class="screen-reader-text">' . esc_html( get_bloginfo("name") ) . ' ' . __('Posts', 'ignite') . '</h1>';
} elseif ( is_archive() ) {
	echo '<h1 class="screen-reader-text">' . esc_html( get_the_archive_title() ) . '</h1>';
}

?>
	<div id="loop-container" class="loop-container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				ct_ignite_get_content_template();
			endwhile;
		endif;
		?>
	</div>
<?php

// include loop pagination except for on bbPress Forum root
if ( function_exists( 'is_bbpress' ) ) {
	if ( ! ( is_bbpress() && is_archive() ) ) {
		the_posts_pagination( array(
			'prev_text' => __( 'Previous', 'ignite' ),
			'next_text' => __( 'Next', 'ignite' )
		) );
	}
} else {
	the_posts_pagination( array(
		'prev_text' => __( 'Previous', 'ignite' ),
		'next_text' => __( 'Next', 'ignite' )
	) );
}

get_footer();