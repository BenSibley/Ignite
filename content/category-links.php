<?php

// Outputs the categories the post was included in with their names hyperlinked to their permalink
// separator removed so links site tightly against each other

$categories = get_the_category($post->ID);
$separator = ' ';
$output = '';
if($categories){
	echo "<p><i class='fa fa-folder-open'></i>";
	foreach($categories as $category) {
		$output .= '<a href="'.get_category_link( $category->term_id ).'" title="' . esc_attr( sprintf( __( "View all posts in %s", 'ignite' ), $category->name ) ) . '">'.$category->cat_name.'</a>'.$separator;
	}
	echo trim($output, $separator);
	echo "</p>";
}