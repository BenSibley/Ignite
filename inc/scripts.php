<?php
// register and enqueue all of the scripts used by Aside
function ct_ignite_load_javascript_files() {

	wp_register_style( 'ct-ignite-google-fonts', '//fonts.googleapis.com/css?family=Lusitana:400,700');

	// enqueues scripts & styles
	if(! is_admin() ) {
		wp_enqueue_script('ct-ignite-production', get_template_directory_uri() . '/js/build/production.min.js#ct_ignite_asyncload', array('jquery'),'', true);

		wp_enqueue_style('ct-ignite-google-fonts');
		wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/font-awesome/css/font-awesome.min.css');
		wp_enqueue_style('style', get_template_directory_uri() . 'style.min.css');
	}
	// enqueues the comment-reply script on posts & pages.  This script is included in WP by default
	if( is_singular() && comments_open() && get_option('thread_comments') ) wp_enqueue_script( 'comment-reply' );
}
add_action('wp_enqueue_scripts', 'ct_ignite_load_javascript_files' );

/* enqueue styles used on theme options page */
function ct_ignite_enqueue_admin_styles($hook){

	if ( 'appearance_page_ignite-options' == $hook ) {
		wp_enqueue_style('style-admin', get_template_directory_uri() . '/style-admin.css');
	}
}
add_action('admin_enqueue_scripts',	'ct_ignite_enqueue_admin_styles' );

function ct_ignite_enqueue_profile_image_uploader($hook) {

	// if is user profile page
	if('profile.php' == $hook || 'user-edit.php' == $hook){

		// Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs.
		wp_enqueue_media();

		// enqueue the JS needed to utilize media uploader on profile image upload
		wp_enqueue_script('ct-profile-uploader', get_template_directory_uri() . '/js/build/profile-uploader.min.js');
	}
}
add_action('admin_enqueue_scripts', 'ct_ignite_enqueue_profile_image_uploader');

/* enqueues scripts and styles used on customizer page */
function ct_ignite_enqueue_customizer_scripts(){

	wp_enqueue_script('multiple-select', get_template_directory_uri() . '/js/build/multiple-select.min.js',array('jquery'),'',true);
	wp_enqueue_style('multiple-select-styles', get_template_directory_uri() . '/styles/multiple-select.css');

	wp_enqueue_script('customizer', get_template_directory_uri() . '/js/build/customizer.min.js',array('jquery'),'',true);
}
add_action('customize_controls_enqueue_scripts','ct_ignite_enqueue_customizer_scripts');

// load all scripts enqueued by theme asynchronously
function ct_ignite_add_async_script($url) {

	// if async parameter not present, do nothing
	if (strpos($url, '#ct_ignite_asyncload') === false){
		return $url;
	}
	// if async parameter present, add async attribute
	return str_replace('#ct_ignite_asyncload', '', $url)."' async='async";
}
add_filter('clean_url', 'ct_ignite_add_async_script', 11, 1);