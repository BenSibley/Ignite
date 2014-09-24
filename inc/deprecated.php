<?php

/* Avoid use of these functions in favor of newer functions */

/*
 * @deprecated 1.36
 * Now template part /content/further-reading.php
 */
function ct_ignite_further_reading() {

	global $post;

	// gets the next & previous posts if they exist
	$previous_blog_post = get_adjacent_post(false,'',true);
	$next_blog_post = get_adjacent_post(false,'',false);

	if(get_the_title($previous_blog_post)) {
		$previous_title = get_the_title($previous_blog_post);
	} else {
		$previous_title = __("The Previous Post", 'ignite');
	}
	if(get_the_title($next_blog_post)) {
		$next_title = get_the_title($next_blog_post);
	} else {
		$next_title = __("The Next Post", 'ignite');
	}

	echo "<nav class='further-reading'>";
	if($previous_blog_post) {
		echo "<p class='prev'>
        		<span>" . __('Previous Post', 'ignite') . "</span>
        		<a href='".get_permalink($previous_blog_post)."'>".$previous_title."</a>
	        </p>";
	} else {
		echo "<p class='prev'>
                <span>" . __('Return to Blog', 'ignite') . "</span>
        		<a href='".esc_url(home_url())."'>" . __('This is the oldest post', 'ignite') . "</a>
        	</p>";
	}
	if($next_blog_post) {

		echo "<p class='next'>
        		<span>" . __('Next Post', 'ignite') . "</span>
        		<a href='".get_permalink($next_blog_post)."'>".$next_title."</a>
	        </p>";
	} else {
		echo "<p class='next'>
                <span>" . __('Return to Blog', 'ignite') . "</span>
        		<a href='".esc_url(home_url())."'>" . __('This is the newest post', 'ignite') . "</a>
        	 </p>";
	}
	echo "</nav>";
}

/*
 * @deprecated 1.36
 * Now template part /content/category-links.php
 */
function ct_ignite_category_display() {

	$categories = get_the_category();
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
}

/*
 * @deprecated 1.36
 * Now template part /content/tag-links.php
 */
function ct_ignite_tags_display() {

	$tags = get_the_tags();
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
}
