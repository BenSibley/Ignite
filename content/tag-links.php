<?php

// Outputs the tags the post used with their names hyperlinked to their permalink

$tags = get_the_tags($post->ID);
$separator = ' ';
$output = '';
if($tags){
	echo "<p><i class='fa fa-tag'></i>";
	foreach($tags as $tag) {
		$output .= '<a href="'.get_tag_link( $tag->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts tagged %s", 'ignite' ), $tag->name ) ) . '">'.$tag->name.'</a>'.$separator;
	}
	echo trim($output, $separator);
	echo "</p>";
}