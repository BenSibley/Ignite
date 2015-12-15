<?php

global $post;

// gets the previous post if it exists
$previous_post = get_adjacent_post( false, '', true );

// if there is a previous post
if ( $previous_post ) {
	// text above the link
	$previous_text = __( 'Previous Post', 'ignite' );
	// if there is a title use it, else call it "The Previous Post"
	$previous_title = get_the_title( $previous_post ) ? get_the_title( $previous_post ) : __( "The Previous Post", 'ignite' );
	// get the post link
	$previous_link = get_permalink( $previous_post );
} // if there isn't a previous post
else {
	// text above the link
	$previous_text = __( 'No Older Posts', 'ignite' );
	// set the title to return to the blog
	$previous_title = __( 'Return to Blog', 'ignite' );
	// link to blog
	if ( get_option( 'show_on_front' ) == 'page' ) {
		$previous_link = get_permalink( get_option( 'page_for_posts' ) );
	} else {
		$previous_link = get_home_url();
	}
}

// gets the next post if it exists
$next_post = get_adjacent_post( false, '', false );

// if there is a next post
if ( $next_post ) {
	// text above the link
	$next_text = __( 'Next Post', 'ignite' );
	// if there is a title use it, else call it "The next Post"
	$next_title = get_the_title( $next_post ) ? get_the_title( $next_post ) : __( "The Next Post", 'ignite' );
	// get the post link
	$next_link = get_permalink( $next_post );
} // if there isn't a next post
else {
	// text above the link
	$next_text = __( 'No Newer Posts', 'ignite' );
	// set the title to return to the blog
	$next_title = __( 'Return to Blog', 'ignite' );
	// link to blog
	if ( get_option( 'show_on_front' ) == 'page' ) {
		$next_link = get_permalink( get_option( 'page_for_posts' ) );
	} else {
		$next_link = get_home_url();
	}
}

?>
<nav class="further-reading">
	<p class="prev">
		<span><?php echo esc_html( $previous_text ); ?></span>
		<a href="<?php echo esc_url( $previous_link ); ?>"><?php echo esc_html( $previous_title ); ?></a>
	</p>

	<p class="next">
		<span><?php echo esc_html( $next_text ); ?></span>
		<a href="<?php echo esc_url( $next_link ); ?>"><?php echo esc_html( $next_title ); ?></a>
	</p>
</nav>