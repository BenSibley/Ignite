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
			'priority'   => 30,
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

/* allow logo position to be adjusted */
function ct_ignite_customize_logo_positioning( $wp_customize ) {

    /* create custom control for number input */
    class ct_ignite_number_input_control extends WP_Customize_Control {
        public $type = 'number';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <input type="number" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />
            </label>
        <?php
        }
    }
    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-logo-positioning',
        array(
            'title'      => esc_html__( 'Logo Positioning', 'ignite' ),
            'priority'   => 31,
            'capability' => 'edit_theme_options'
        )
    );
    /* logo positioning top setting. */
    $wp_customize->add_setting(
        'logo_positioning_updown_setting',
        array(
            'default' => 0,
            'sanitize_callback' => 'ct_ignite_sanitize_integer'
        )
    );
    /* logo positioning right setting. */
    $wp_customize->add_setting(
        'logo_positioning_leftright_setting',
        array(
            'default' => 0,
            'sanitize_callback' => 'ct_ignite_sanitize_integer'
        )
    );
    /* top input */
    $wp_customize->add_control(
        new ct_ignite_number_input_control(
            $wp_customize, 'logo_positioning_updown_setting',
            array(
                'label' => 'Up/down',
                'section' => 'ct-logo-positioning',
                'settings' => 'logo_positioning_updown_setting',
                'type' => 'number',
            )
        )
    );
    /* right input */
    $wp_customize->add_control(
        new ct_ignite_number_input_control(
            $wp_customize, 'logo_positioning_leftright_setting',
            array(
                'label' => 'Left/right',
                'section' => 'ct-logo-positioning',
                'settings' => 'logo_positioning_leftright_setting',
                'type' => 'number',
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_customize_logo_positioning' );

/* need custom function because WP only supplies absint and negative numbers need to be allowed */
function ct_ignite_sanitize_integer($input){
    return intval( $input );
}

/* allow logo size to be adjusted */
function ct_ignite_customize_logo_size( $wp_customize ) {

    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-logo-size',
        array(
            'title'      => esc_html__( 'Logo Size', 'ignite' ),
            'priority'   => 32,
            'capability' => 'edit_theme_options'
        )
    );
    /* logo increase/decrease width setting. */
    $wp_customize->add_setting(
        'logo_size_width_setting',
        array(
            'default' => 0,
            'sanitize_callback' => 'ct_ignite_sanitize_integer'
        )
    );
    /* logo increase/decrease height setting. */
    $wp_customize->add_setting(
        'logo_size_height_setting',
        array(
            'default' => 0,
            'sanitize_callback' => 'ct_ignite_sanitize_integer'
        )
    );
    /* top input */
    $wp_customize->add_control(
        new ct_ignite_number_input_control(
            $wp_customize, 'logo_size_width_setting',
            array(
                'label' => 'Increase/decrease',
                'section' => 'ct-logo-size',
                'settings' => 'logo_size_width_setting',
                'type' => 'number',
            )
        )
    );
    /* right input */
    $wp_customize->add_control(
        new ct_ignite_number_input_control(
            $wp_customize, 'logo_size_height_setting',
            array(
                'label' => 'Increase/decrease',
                'section' => 'ct-logo-size',
                'settings' => 'logo_size_height_setting',
                'type' => 'number',
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_customize_logo_size' );


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
				'priority'=> $priority,
		    )
            )
        );
	
		$priority = $priority + 5;
	}
}

/* show/hide the post author info after posts */
function ct_ignite_show_author_meta( $wp_customize ) {

    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-author-meta',
        array(
            'title'      => esc_html__( 'Post Author Info', 'ignite' ),
            'priority'   => 70,
            'capability' => 'edit_theme_options'
        )
    );
    /* Add the color setting. */
    $wp_customize->add_setting(
        'ct_ignite_author_meta_settings',
        array(
            'default'           => 'show',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'ct_ignite_sanitize_author_meta_settings',
        )
    );
    $wp_customize->add_control(
        'ct_ignite_show_author_meta',
        array(
            'label'          => __( 'Show post author info after posts?', 'ignite' ),
            'section'        => 'ct-author-meta',
            'settings'       => 'ct_ignite_author_meta_settings',
            'type'           => 'radio',
            'choices'        => array(
                'show'   => 'Show',
                'hide'  => 'Hide'
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_show_author_meta' );

/* sanitize the radio button input */
function ct_ignite_sanitize_author_meta_settings($input){
    $valid = array(
        'show' => 'Show',
        'hide' => 'Hide'
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/* show full post or excerpt on homepage */
function ct_ignite_additional_options( $wp_customize ) {

    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-additional-options',
        array(
            'title'      => esc_html__( 'Additional Options', 'ignite' ),
            'priority'   => 80,
            'capability' => 'edit_theme_options'
        )
    );
    /* Add the color setting. */
    $wp_customize->add_setting(
        'ct_ignite_show_full_post_setting',
        array(
            'default'           => 'no',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'ct_ignite_sanitize_show_full_post_setting',
        )
    );
    $wp_customize->add_control(
        'ct_ignite_show_full_post',
        array(
            'label'          => __( 'Show full post on blog?', 'ignite' ),
            'section'        => 'ct-additional-options',
            'settings'       => 'ct_ignite_show_full_post_setting',
            'type'           => 'radio',
            'choices'        => array(
                'yes'   => 'Yes',
                'no'  => 'No'
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_additional_options' );

/* sanitize the radio button input */
function ct_ignite_sanitize_show_full_post_setting($input){
    $valid = array(
        'yes' => 'Yes',
        'no' => 'No'
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}


/* create theme options page */
function ct_ignite_register_theme_page(){
    add_theme_page( 'Theme Options', 'Theme Options', 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content');
}
add_action( 'admin_menu', 'ct_ignite_register_theme_page' );

/* callback used to add content to options page */
function ct_ignite_options_content(){
    ?>
    <div id="ignite-dashboard-wrap" class="wrap">
        <h2>Ignite Dashboard</h2>
        <p>Thanks for downloading Ignite!</p>
        <p>If you can, take a minute to <a target="_blank" href="http://wordpress.org/support/view/theme-reviews/ignite"><strong>leave a review</strong></a> so other users know what to expect from this theme.</p>
        <hr />
        <div>
            <h3>Customization</h3>
            <p>Ignite now has more customization options! Try out the new logo size & position tools with the customizer.</p>
            <p><a class="button-primary" href="customize.php">Use the customizer</a></p>
        </div>
        <div class="support">
            <h3>Support</h3>
            <ol>
                <li><a target="_blank" href="http://www.competethemes.com/documentation/ignite-knowledgebase/?utm_source=WordPress%20Dashboard&utm_medium=User%20Admin&utm_content=Ignite&utm_campaign=Admin%20Support%20Widgets">Visit the knowledgebase</a> for self-help.</li>
                <li><a target="_blank" href="http://wordpress.org/support/theme/ignite">Visit the support forum</a> for community support.</li>
            </ol>
            <p>I (Ben) visit the support forum everyday, so any questions you have will be answered there.</p>
        </div>
        <div>
            <h3>Premium</h3>
            <p>There is now a premium version of Ignite called "Ignite Plus" available.</p>
            <p><a target="_blank" class="button-primary" href="http://www.competethemes.com/ignite-plus/">View Ignite Plus</a></p>
        </div>
    </div>
    <hr />
    <div id="ignite-premium-wrap" class="wrap">
        <h2>Ignite Plus</h2>
        <p>Enjoy greater customization and functionality with the Ignite Plus upgrade.</p>
        <div class="odd">
            <h2>Custom Background</h2>
            <p>Change the background of Ignite to any color, any image, or one of 21 bundled textures.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/custom-background.png'; ?>" />
        </div>
        <div class="even">
            <h2>Custom Colors</h2>
            <p>Change the colors of Ignite to match your brand or personal preference and watch your site update instantly.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-custom-colors.jpg'; ?>" />
        </div>
        <div class="odd">
            <h2>3 New Widgets</h2>
            <p>Ignite Plus includes a social media icons, recent posts w/ thumbnails, and recent comments w/ thumbnails widgets.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-new-widgets.jpg'; ?>" />
        </div>
        <div class="even">
            <h2>Layout Options</h2>
            <p>Switch between a left-sidebar and right-sidebar layout with a click of a button.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-layout.jpg'; ?>" />
        </div>
        <div class="odd">
            <h2>Author-specific Social Icons</h2>
            <p>Allow every author on your site to include social icons after every post.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-author-social-icons.jpg'; ?>" />
        </div>
        <div class="even">
            <h2>After-content Widget Area</h2>
            <p>Use the after-content widget area to add email signup forms or other widgets after every blog post.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-after-content-widget.jpg'; ?>" />
        </div>
        <div class="odd">
            <h2>Footer Menu</h2>
            <p>The additional footer menu is perfect for your TOS, privacy policy, and other required pages that don't need a lot of visibility.</p>
            <img src="<?php echo get_template_directory_uri() . '/assets/images/ignite-footer-menu.jpg'; ?>" />
        </div>
        <p class="button-wrap"><a target="_blank" class="button-primary" href="http://www.competethemes.com/ignite-plus/">Download Ignite Plus</a></p>
    </div>
    <?php }