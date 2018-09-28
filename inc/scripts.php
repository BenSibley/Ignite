<?php

/***** Front-end *****/
function ct_ignite_load_scripts_styles() {

	$font_args = array(
		'family' => urlencode( 'Lusitana:400,700' ),
		'subset' => urlencode( 'latin,latin-ext' )
	);
	$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );

	// Register first to allow deregistration by fonts functionality
	wp_register_style( 'ct-ignite-google-fonts', $fonts_url );
	wp_enqueue_style( 'ct-ignite-google-fonts' );

	wp_enqueue_script( 'ct-ignite-production', get_template_directory_uri() . '/js/build/production.min.js#ct_ignite_asyncload', array( 'jquery' ), '', true );

	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/all.min.css' );

	wp_enqueue_style( 'ct-ignite-style', get_stylesheet_uri() );

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_load_scripts_styles' );

/***** Back-end *****/
function ct_ignite_enqueue_admin_styles( $hook ) {

	if ( $hook == 'appearance_page_ignite-options' || $hook == 'widgets.php' ) {
		wp_enqueue_style( 'ct-ignite-admin-style', get_template_directory_uri() . '/styles/admin-style.min.css' );
	}
	if ( $hook == 'widgets.php' ) {
		// Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs.
		wp_enqueue_media();

		wp_enqueue_script( 'ct-ignite-profile-uploader', get_template_directory_uri() . '/js/build/profile-uploader.min.js' );
	}
}
add_action( 'admin_enqueue_scripts', 'ct_ignite_enqueue_admin_styles' );

/***** Customizer *****/
function ct_ignite_enqueue_customizer_scripts() {
	wp_enqueue_script( 'ct-ignite-customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js', array( 'jquery' ), '', true );
	wp_enqueue_style( 'ct-ignit-customizer-styles', get_template_directory_uri() . '/styles/customizer-style.min.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'ct_ignite_enqueue_customizer_scripts' );

/***** Customizer - PostMessage *****/
function ct_ignite_enqueue_customizer_post_message_scripts() {
	wp_enqueue_script( 'ct-ignite-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js', array( 'jquery' ), '', true );
	wp_localize_script( 'ct-ignite-customizer-post-message-js', 'ignite_ajax',
		array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
add_action( 'customize_preview_init', 'ct_ignite_enqueue_customizer_post_message_scripts' );

// load scripts asynchronously
function ct_ignite_add_async_script( $url ) {

	// if async parameter not present, do nothing
	if ( strpos( $url, '#ct_ignite_asyncload' ) === false ) {
		return $url;
	}

	// if async parameter present, add async attribute
	return str_replace( '#ct_ignite_asyncload', '', $url ) . "' async='async";
}
add_filter( 'clean_url', 'ct_ignite_add_async_script', 11, 1 );