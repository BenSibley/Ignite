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
			'title'      => __( 'Logo', 'ignite' ),
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
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image',
				array(
					'label'    => __( 'Upload custom logo.', 'ignite' ),
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
            'title'      => __( 'Logo Positioning', 'ignite' ),
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
                'label' => __('Up/down', 'ignite'),
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
                'label' => __('Left/right', 'ignite'),
                'section' => 'ct-logo-positioning',
                'settings' => 'logo_positioning_leftright_setting',
                'type' => 'number',
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_customize_logo_positioning' );

// simply using 'intval' is throwing an error, so using this custom sanitization function
function ct_ignite_sanitize_integer($input){
    return intval( $input );
}

/* allow logo size to be adjusted */
function ct_ignite_customize_logo_size( $wp_customize ) {

    /* Add the layout section. */
    $wp_customize->add_section(
        'ct-logo-size',
        array(
            'title'      => __( 'Logo Size', 'ignite' ),
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
                'label' => __('Increase max-width', 'ignite'),
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
                'label' => __('Increase max-height', 'ignite'),
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
	$social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram', 'reddit', 'soundcloud', 'spotify', 'vine','yahoo', 'behance', 'codepen', 'delicious', 'stumbleupon', 'deviantart', 'digg', 'git', 'hacker-news', 'steam');
	
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
        'title'          => __('Social Media Icons', 'ignite'),
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
				'label'   => $social_site . " " . __("url:", 'ignite' ),
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
            'title'      => __( 'Post Author Info', 'ignite' ),
            'priority'   => 70,
            'capability' => 'edit_theme_options'
        )
    );
    /* setting */
    $wp_customize->add_setting(
        'ct_ignite_author_meta_settings',
        array(
            'default'           => 'show',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'ct_ignite_sanitize_author_meta_settings',
        )
    );
    /* control */
    $wp_customize->add_control(
        'ct_ignite_show_author_meta',
        array(
            'label'          => __( 'Show post author info after posts?', 'ignite' ),
            'section'        => 'ct-author-meta',
            'settings'       => 'ct_ignite_author_meta_settings',
            'type'           => 'radio',
            'choices'        => array(
                'show'   => __('Show', 'ignite'),
                'hide'  => __('Hide', 'ignite')
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_show_author_meta' );

/* sanitize the radio button input */
function ct_ignite_sanitize_author_meta_settings($input){
    $valid = array(
        'show'   => __('Show', 'ignite'),
        'hide'  => __('Hide', 'ignite')
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/* additional options section */
function ct_ignite_additional_options( $wp_customize ) {

    /* section */
    $wp_customize->add_section(
        'ct-additional-options',
        array(
            'title'      => __( 'Additional Options', 'ignite' ),
            'priority'   => 80,
            'capability' => 'edit_theme_options'
        )
    );
    /* setting */
    $wp_customize->add_setting(
        'ct_ignite_show_full_post_setting',
        array(
            'default'           => 'no',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'ct_ignite_sanitize_show_full_post_setting',
        )
    );
    /* control */
    $wp_customize->add_control(
        'ct_ignite_show_full_post',
        array(
            'label'          => __( 'Show full post on blog?', 'ignite' ),
            'section'        => 'ct-additional-options',
            'settings'       => 'ct_ignite_show_full_post_setting',
            'type'           => 'radio',
            'choices'        => array(
                'yes'   => __('Yes', 'ignite'),
                'no'  => __('No', 'ignite')
            )
        )
    );
    /* setting */
    $wp_customize->add_setting(
        'ct_ignite_show_breadcrumbs_setting',
        array(
            'default'           => 'yes',
            'type'              => 'theme_mod',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'ct_ignite_sanitize_show_full_post_setting',
        )
    );
    /* control */
    $wp_customize->add_control(
        'ct_ignite_show_breadcrumbs_setting',
        array(
            'label'          => __( 'Show breadcrumbs?', 'ignite' ),
            'section'        => 'ct-additional-options',
            'settings'       => 'ct_ignite_show_breadcrumbs_setting',
            'type'           => 'radio',
            'choices'        => array(
                'yes'   => __('Yes', 'ignite'),
                'no'  => __('No', 'ignite')
            )
        )
    );
}
add_action( 'customize_register', 'ct_ignite_additional_options' );

/* sanitize the radio button input */
function ct_ignite_sanitize_show_full_post_setting($input){
    $valid = array(
        'yes'   => __('Yes', 'ignite'),
        'no'  => __('No', 'ignite')
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/* create theme options page */
function ct_ignite_register_theme_page(){
    add_theme_page( 'Upgrade to Ignite Plus', 'Upgrade', 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content');
}
add_action( 'admin_menu', 'ct_ignite_register_theme_page' );

/* callback used to add content to options page */
function ct_ignite_options_content(){
    ?>
    <div id="ignite-dashboard-wrap" class="wrap">
        <h2><?php _e('Ignite Dashboard', 'ignite'); ?></h2>
        <p>
            <?php
            $review_url = "http://wordpress.org/support/view/theme-reviews/ignite";
            printf( __('Thanks for downloading Ignite! If you can, please take one minute to <a target="_blank" href="%s"><strong>leave a review</strong></a>.', 'ignite'), esc_url($review_url));
            ?>
        </p>
        <hr />
        <div>
            <h3><?php _e('Premium Upgrade', 'ignite'); ?></h3>
            <p><?php _e('Get more flexibility and customizations for your site, with Ignite Plus ($29).', 'ignite'); ?></p>
            <p><?php _e('Custom colors, custom backgrounds, over 600+ fonts, and more...', 'ignite'); ?></p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/ignite-plus/"><?php _e('View Ignite Plus', 'ignite'); ?></a>
            </p>
        </div>
        <div class="support">
            <h3><?php _e('Support', 'ignite'); ?></h3>
            <ol>
                <li>
                    <?php
                    $docs_url = 'http://www.competethemes.com/documentation/ignite-knowledgebase/?utm_source=WordPress%20Dashboard&utm_medium=User%20Admin&utm_content=Ignite&utm_campaign=Admin%20Support%20Widgets';
                    $docs_link = sprintf( __( '<a target="_blank" href="%s">Visit the knowledgebase</a> for self-help.', 'ignite' ), esc_url( $docs_url ) );
                    echo $docs_link;
                    ?>
                </li>
                <li>
                    <?php
                    $forum_url = 'http://wordpress.org/support/theme/ignite';
                    $forum_link = sprintf( __( '<a target="_blank" href="%s">Visit the support forum</a> for community support.', 'ignite' ), esc_url( $forum_url ) );
                    echo $forum_link;
                    ?>
                </li>
            </ol>
            <p><?php _e('I (Ben) visit the support forum everyday, so any questions you have will be answered there.', 'ignite'); ?></p>
        </div>
        <div>
            <h3><?php _e('Customize', 'ignite'); ?></h3>
            <p><?php _e('Customize your site with your logo, social profiles, and more with the built-in Customizer.', 'ignite'); ?></p>
            <p><a class="button-primary" href="customize.php"><?php _e('Use the customizer', 'ignite'); ?></a></p>
        </div>
    </div>
    <?php }