<?php

if ( get_theme_mod( 'ct_ignite_post_meta_categories_settings' ) == 'hide' ) {
	return;
}

$categories = get_the_category( $post->ID );
$separator  = ' ';
$output     = '';

if ( $categories ) {
	echo "<div class='entry-categories'>";
	echo "<p><i class='fa fa-folder-open'></i>";
	foreach ( $categories as $category ) {
		$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'ignite' ), $category->name ) ) . '">' . $category->cat_name . '</a>' . $separator;
	}
	echo trim( $output, $separator );
	echo "</p>";
	echo "</div>";
}