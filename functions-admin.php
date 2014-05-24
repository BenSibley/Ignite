<?php 

/* Add layout option in Customize. */
add_action( 'customize_register', 'ct_ignite_customize_register_logo' );

/**
 * Add logo upload in theme customizer screen.
 *
 * @since 1.0
 */
function ct_ignite_customize_register_logo( $wp_customize ) {

	/* Add the layout section. */
	$wp_customize->add_section(
		'ct-ignite-upload',
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
			'sanitize_callback' => 'esc_url_raw',
			//'transport'         => 'postMessage'
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image',
				array(
					'label'    => esc_html__( 'Upload custom logo.', 'ignite' ),
					'section'  => 'ct-ignite-upload',
					'settings' => 'logo_upload',
			)
		)
	);

}

function ct_ignite_customizer_social_media_array() {

	// store social site names in array
	$social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram');
	
	return $social_sites;
}

// add settings to create various social media text areas.
add_action('customize_register', 'ct_ignite_add_social_sites_customizer');

function ct_ignite_add_social_sites_customizer($wp_customize) {

    /* create custom control for url input so http:// is automatically added */
    class ct_ignite_url_input_control extends WP_Customize_Control {
        public $type = 'url';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="url" <?php $this->link(); ?> value="<?php echo esc_url_raw( $this->value() ); ?>" />
            </label>
        <?php
        }
    }
	$wp_customize->add_section( 'ct_ignite_social_settings', array(
			'title'          => 'Social Media Icons',
			'priority'       => 35,
	) );
		
	$social_sites = ct_ignite_customizer_social_media_array();
	$priority = 5;
	
	foreach($social_sites as $social_site) {

		$wp_customize->add_setting( "$social_site", array(
                'type'              => 'theme_mod',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
		) );

		$wp_customize->add_control(
            new ct_ignite_url_input_control(
            $wp_customize, $social_site, array(
				'label'   => __( "$social_site url:", 'ct_ignite_icon' ),
				'section' => 'ct_ignite_social_settings',
				'type'    => 'text',
				'priority'=> $priority,
		    )
            )
        );
	
		$priority = $priority + 5;
	}
}

/* create theme options page */
function ct_ignite_register_menu_pages(){
    add_menu_page( 'Ignite Theme Options', 'Ignite', 'manage_options', 'ignite-options', 'ct_ignite_options_content', plugins_url( 'myplugin/images/icon.png' ), 81 );
    add_submenu_page( 'ignite-options', 'Options', 'Dashboard', 'manage_options', 'ignite-options', 'ct_ignite_options_content' );
    add_submenu_page( 'ignite-options', 'Get Ignite Plus', 'Premium', 'manage_options', 'ignite-premium', 'ct_ignite_premium_callback' );
}
add_action( 'admin_menu', 'ct_ignite_register_menu_pages' );

/* callback used to add content to options page */
function ct_ignite_options_content(){
    ?>
    <div id="ignite-dashboard-wrap" class="wrap" style="max-width: 600px"><div id="icon-tools" class="icon32"></div>
        <h2>Ignite Dashboard</h2>
        <p>Thanks for downloading Ignite!</p>
        <p>If you can, please take a minute to leave a review so other users know what to expect from this theme.</p>
        <p><a target="_blank" href="http://wordpress.org/support/view/theme-reviews/ignite"><strong>Leave a review</strong></a></p>
        <hr />
        <h3>Customization</h3>
        <p>If you're looking to customize your site, you can upload your logo and add your social media profiles with the customizer.</p>
        <p><a href="customize.php"><strong>Use the customizer</strong></a></p>
        <hr />
        <h3>Support</h3>
        <ol>
            <li><a target="_blank" href="http://www.competethemes.com/documentation/ignite-knowledgebase/?utm_source=WordPress%20Dashboard&utm_medium=User%20Admin&utm_content=Ignite&utm_campaign=Admin%20Support%20Widgets">Visit the knowledgebase</a> for self-help.</li>
            <li><a target="_blank" href="http://wordpress.org/support/theme/ignite">Visit the support forum</a> for community support.</li>
        </ol>
        <p>I (Ben) visit the support forum everyday, so I will find and answer any questions you have there.</p>
        <hr />
        <h3>Premium</h3>
        <p>There is a premium version of Ignite called "Ignite Plus" available. If you're interested in more customization options and functionality for Ignite, take a minute to check out the "Premium" page.</p>
        <p><a href="?page=ignite-premium"><strong>View Premium Page</strong></a></p>
    </div>
    <?php
}

function ct_ignite_premium_callback(){

    echo '<div class="wrap"><div id="icon-tools" class="icon32"></div>';
    echo '<h2>Premium</h2>';
    echo '</div>';
}
