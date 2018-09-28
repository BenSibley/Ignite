<?php

if ( get_theme_mod( 'ct_ignite_post_meta_further_reading_settings' ) == 'hide' ) {
	return;
}

global $post;
$previous_post = get_adjacent_post( false, '', true );

if ( $previous_post ) {
	$previous_text = esc_html__( 'Previous Post', 'ignite' );
	$previous_title = get_the_title( $previous_post ) ? get_the_title( $previous_post ) : esc_html__( "The Previous Post", 'ignite' );
	$previous_link = get_permalink( $previous_post );
} else {
	$previous_text = esc_html__( 'No Older Posts', 'ignite' );
	$previous_title = esc_html__( 'Return to Blog', 'ignite' );
	if ( get_option( 'show_on_front' ) == 'page' ) {
		$previous_link = get_permalink( get_option( 'page_for_posts' ) );
	} else {
		$previous_link = get_home_url();
	}
}

$next_post = get_adjacent_post( false, '', false );

if ( $next_post ) {
	$next_text  = esc_html__( 'Next Post', 'ignite' );
	$next_title = get_the_title( $next_post ) ? get_the_title( $next_post ) : esc_html__( "The Next Post", 'ignite' );
	$next_link  = get_permalink( $next_post );
} else {
	$next_text = esc_html__( 'No Newer Posts', 'ignite' );
	$next_title = esc_html__( 'Return to Blog', 'ignite' );
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