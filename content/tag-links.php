<?php

if ( get_theme_mod( 'ct_ignite_post_meta_tags_settings' ) == 'hide' ) {
	return;
}

$tags      = get_the_tags( $post->ID );
$separator = ' ';
$output    = '';
if ( $tags ) {
	echo "<p><i class='fa fa-tag'></i>";
	foreach ( $tags as $tag ) {
		$output .= '<a href="' . get_tag_link( $tag->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'ignite' ), $tag->name ) ) . '">' . $tag->name . '</a>' . $separator;
	}
	echo trim( $output, $separator );
	echo "</p>";
}