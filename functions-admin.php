<?php 

/* Add layout option in Customize. */
add_action( 'customize_register', 'ct_customize_register_logo' );

/**
 * Add logo upload in theme customizer screen.
 *
 * @since 1.0
 */
function ct_customize_register_logo( $wp_customize ) {

	/* Add the layout section. */
	$wp_customize->add_section(
		'ct-upload',
		array(
			'title'      => esc_html__( 'Logo', 'ignite' ),
			'priority'   => 60,
			'capability' => 'edit_theme_options'
		)
	);

	/* Add the 'logo' setting. */
	$wp_customize->add_setting(
		'logo_upload',
		array(
			'default'           => '',
			'type'              => 'theme_mod',
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'esc_url',
			//'transport'         => 'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image',
				array(
					'label'    => esc_html__( 'Upload custom logo.', 'ignite' ),
					'section'  => 'ct-upload',
					'settings' => 'logo_upload',
			)
		)
	);

}

function ct_customizer_social_media_array() {

	// store social site names in array
	$social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram');
	
	return $social_sites;
}

// add settings to create various social media text areas.
add_action('customize_register', 'ct_add_social_sites_customizer');

function ct_add_social_sites_customizer($wp_customize) {

	$wp_customize->add_section( 'ct_social_settings', array(
			'title'          => 'Social Media Icons',
			'priority'       => 35,
	) );
		
	$social_sites = ct_customizer_social_media_array();
	$priority = 5;
	
	foreach($social_sites as $social_site) {

		$wp_customize->add_setting( "$social_site", array(
                'type'              => 'theme_mod',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'esc_url'
		) );

		$wp_customize->add_control( $social_site, array(
				'label'   => __( "$social_site url:", 'ct_icon' ),
				'section' => 'ct_social_settings',
				'type'    => 'text',
				'priority'=> $priority,
		) );
	
		$priority = $priority + 5;
	}
}

add_filter( 'cmb_meta_boxes', 'ct_image_credit_meta_box' );

// creates meta box for image credit above the featured image box
function ct_image_credit_meta_box( $meta_boxes ) {
    $prefix = 'ct-';
    $meta_boxes[] = array(
		'id'         => 'image-credit-meta-box',
		'title'      => 'Image Credit Link',
		'pages'      => array( 'post', ), // Post type
		'context'    => 'side',
		'priority'   => 'low',
		'show_names' => true, // Show field names on the left
		'fields'     => array(
            array(
				'name' => 'URL:',
				'desc' => '(Optional) Where did you find the featured image?',
				'id'   => $prefix . 'image-credit-link',
				'type' => 'text_medium',
			)
        )
    );

	return $meta_boxes;
}

// adds widget that aside uses to give people access to support
function ct_add_dashboard_widget() {

	wp_add_dashboard_widget(
                 'ct_dashboard_widget',    // Widget slug.
                 'Support Dashboard',   // Title.
                 'ct_widget_contents' 	  // Display function.
        );	
        
    // Globalize the metaboxes array, this holds all the widgets for wp-admin
 	global $wp_meta_boxes;
 	
 	// Get the regular dashboard widgets array 
 	// (which has our new widget already but at the end)
 	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
 	
 	// Backup and delete our new dashboard widget from the end of the array
 	$example_widget_backup = array( 'ct_dashboard_widget' => $normal_dashboard['ct_dashboard_widget'] );
 	unset( $normal_dashboard['ct_dashboard_widget'] );
 
 	// Merge the two arrays together so our widget is at the beginning
 	$sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );
 
 	// Save the sorted array back into the original metaboxes 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}
add_action( 'wp_dashboard_setup', 'ct_add_dashboard_widget' );

// outputs contents for widget created by aside_add_dashboard_widget
function ct_widget_contents() { ?>

    <ol>
        <li>For self-help, <a target="_blank" href="http://competethemes.com/documentation">visit the knowledgebase</a></li>
        <li>For support, <a target="_blank" href="http://wordpress.org/support/theme/ignite">visit the support forum</a></li>
        <li>If you like Ignite, <a target="_blank" href="http://wordpress.org/support/view/theme-reviews/ignite">take 1 minute to leave a review</a></li>
    </ol>

	<?php
} 

?>