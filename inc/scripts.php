<?php

/*
 * Front-end scripts
 */
function ct_ignite_load_scripts_styles() {

	wp_register_style( 'ct-ignite-google-fonts', '//fonts.googleapis.com/css?family=Lusitana:400,700');

	// main JS file
	wp_enqueue_script('ct-ignite-production', get_template_directory_uri() . '/js/build/production.min.js#ct_ignite_asyncload', array('jquery'),'', true);

	// Google Fonts
	wp_enqueue_style('ct-ignite-google-fonts');

	// Font Awesome
	wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css');

	// Stylesheet
	wp_enqueue_style('style', get_stylesheet_uri() );

	// enqueue comment-reply script only on posts & pages with comments open ( included in WP core )
	if( is_singular() && comments_open() && get_option('thread_comments') ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action('wp_enqueue_scripts', 'ct_ignite_load_scripts_styles' );

/*
 * Back-end scripts
 */
function ct_ignite_enqueue_admin_styles($hook){

	if ( 'appearance_page_ignite-options' == $hook || 'widgets.php' == $hook ) {
		wp_enqueue_style('admin-style', get_template_directory_uri() . '/styles/admin-style.min.css');
	}

	// if is widgets page
	if( 'widgets.php' == $hook ){

		// Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs.
		wp_enqueue_media();

		// enqueue the JS needed to utilize media uploader on profile image upload
		wp_enqueue_script('ct-profile-uploader', get_template_directory_uri() . '/js/build/profile-uploader.min.js');
	}
}
add_action('admin_enqueue_scripts',	'ct_ignite_enqueue_admin_styles' );

/*
 * Customizer scripts
 */
function ct_ignite_enqueue_customizer_scripts(){

	// JS for hiding/showing Customizer options
	wp_enqueue_script('customizer-js', get_template_directory_uri() . '/js/build/customizer.min.js',array('jquery'),'',true);

	// CSS for styling upgrade ad
	wp_enqueue_style('customizer-styles', get_template_directory_uri() . '/styles/customizer-style.min.css');
}
add_action('customize_controls_enqueue_scripts','ct_ignite_enqueue_customizer_scripts');

/*
 * Script for live updating with customizer options. Has to be loaded separately on customize_preview_init hook
 * transport => postMessage
 */
function ignite_enqueue_customizer_post_message_scripts(){

	// JS for live updating with customizer input
	wp_enqueue_script('ct-ignite-customizer-post-message-js', get_template_directory_uri() . '/js/build/postMessage.min.js',array('jquery'),'',true);

}
add_action('customize_preview_init','ignite_enqueue_customizer_post_message_scripts');

// load scripts asynchronously
function ct_ignite_add_async_script($url) {

	// if async parameter not present, do nothing
	if (strpos($url, '#ct_ignite_asyncload') === false){
		return $url;
	}
	// if async parameter present, add async attribute
	return str_replace('#ct_ignite_asyncload', '', $url)."' async='async";
}
add_filter('clean_url', 'ct_ignite_add_async_script', 11, 1);