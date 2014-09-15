<?php 

/* Add customizer panels, sections, settings, and controls */
add_action( 'customize_register', 'ct_ignite_add_customizer_content' );

function ct_ignite_add_customizer_content( $wp_customize ) {

    /***** Add Custom Controls *****/

    // create number input control
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

    // create url input control
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

    // create textarea control
    class ct_ignite_Textarea_Control extends WP_Customize_Control {
        public $type = 'textarea';

        public function render_content() {
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <textarea rows="8" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
            </label>
        <?php
        }
    }

    // create multi-checkbox/select control
    class ct_ignite_Multi_Checkbox_Control extends WP_Customize_Control {
        public $type = 'multi-checkbox';

        public function render_content() {

            if ( empty( $this->choices ) )
                return;
            ?>
            <label>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
                <select id="comment-display-control" <?php $this->link(); ?> multiple="multiple" style="height: 100%;">
                    <?php
                    foreach ( $this->choices as $value => $label ) {
                        $selected = ( in_array( $value, $this->value() ) ) ? selected( 1, 1, false ) : '';
                        echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
                    }
                    ?>
                </select>
            </label>
        <?php }
    }

    /***** Add Panels *****/

    // Logo panel
    $wp_customize->add_panel( 'ct_ignite_logo_panel', array(
        'priority'       => 30,
        'capability'     => 'edit_theme_options',
        'title'          => __('Logo', 'ignite'),
        'description'    => __('Upload, position, and resize your logo', 'ignite' )
    ) );

    // Font panel
    $wp_customize->add_panel( 'ct_ignite_font_panel', array(
        'priority'       => 50,
        'capability'     => 'edit_theme_options',
        'title'          => __('Font', 'ignite'),
        'description'    => __('Choose a font family and font weight.', 'ignite')
    ) );

	/***** Logo Upload *****/

    // section
	$wp_customize->add_section( 'ct-ignite-upload', array(
        'title'      => __( 'Logo Upload', 'ignite' ),
        'priority'   => 30,
        'capability' => 'edit_theme_options',
        'panel'      => 'ct_ignite_logo_panel'
    ) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
        'default'           => '',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    // control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image', array(
                'label'    => __( 'Upload custom logo.', 'ignite' ),
                'section'  => 'ct-ignite-upload',
                'settings' => 'logo_upload',
			)
		)
	);

    /***** Logo Positioning *****/

    // section
    $wp_customize->add_section( 'ct-logo-positioning', array(
        'title'      => __( 'Logo Positioning', 'ignite' ),
        'priority'   => 31,
        'capability' => 'edit_theme_options',
        'panel'      => 'ct_ignite_logo_panel'
    ) );
    // setting - logo positioning top/bottom
    $wp_customize->add_setting( 'logo_positioning_updown_setting', array(
        'default' => 0,
        'sanitize_callback' => 'ct_ignite_sanitize_integer'
    ) );
    // setting - logo positioning left/right
    $wp_customize->add_setting( 'logo_positioning_leftright_setting', array(
        'default' => 0,
        'sanitize_callback' => 'ct_ignite_sanitize_integer'
    ) );
    // control - logo positioning top/bottom
    $wp_customize->add_control( new ct_ignite_number_input_control(
        $wp_customize, 'logo_positioning_updown_setting', array(
            'label' => __('Up/down', 'ignite'),
            'section' => 'ct-logo-positioning',
            'settings' => 'logo_positioning_updown_setting',
            'type' => 'number',
        )
    ) );
    // control - logo positioning left/right
    $wp_customize->add_control( new ct_ignite_number_input_control(
        $wp_customize, 'logo_positioning_leftright_setting', array(
            'label' => __('Left/right', 'ignite'),
            'section' => 'ct-logo-positioning',
            'settings' => 'logo_positioning_leftright_setting',
            'type' => 'number',
        )
    ) );

    /***** Logo Size *****/

    // section
    $wp_customize->add_section( 'ct-logo-size', array(
        'title'      => __( 'Logo Size', 'ignite' ),
        'priority'   => 32,
        'capability' => 'edit_theme_options',
        'panel'      => 'ct_ignite_logo_panel'
    ) );
    // setting - logo increase/decrease width
    $wp_customize->add_setting( 'logo_size_width_setting', array(
        'default' => 0,
        'sanitize_callback' => 'ct_ignite_sanitize_integer'
    ) );
    // setting - logo increase/decrease height
    $wp_customize->add_setting( 'logo_size_height_setting', array(
        'default' => 0,
        'sanitize_callback' => 'ct_ignite_sanitize_integer'
    ) );
    // control - logo increase/decrease width
    $wp_customize->add_control( new ct_ignite_number_input_control(
        $wp_customize, 'logo_size_width_setting', array(
            'label' => __('Increase max-width', 'ignite'),
            'section' => 'ct-logo-size',
            'settings' => 'logo_size_width_setting',
            'type' => 'number',
        )
    ) );
    // control - logo increase/decrease height
    $wp_customize->add_control( new ct_ignite_number_input_control(
        $wp_customize, 'logo_size_height_setting', array(
            'label' => __('Increase max-height', 'ignite'),
            'section' => 'ct-logo-size',
            'settings' => 'logo_size_height_setting',
            'type' => 'number',
        )
    ) );

    /***** Social Media Icons *****/

    // get the social sites array
    $social_sites = ct_ignite_customizer_social_media_array();

    // set a priority used to order the social sites
    $priority = 5;

    // section
    $wp_customize->add_section( 'ct_ignite_social_settings', array(
        'title'          => __('Social Media Icons', 'ignite'),
        'priority'       => 35,
    ) );

    // create a setting and control for each social site
    foreach($social_sites as $social_site) {

        if( $social_site == 'email' ) {

            $wp_customize->add_setting( "$social_site", array(
                'type'              => 'theme_mod',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'ct_ignite_sanitize_email'
            ) );

            $wp_customize->add_control( $social_site, array(
                'label'   => $social_site . " " . __("address:", 'ignite' ),
                'section' => 'ct_ignite_social_settings',
                'priority'=> $priority,
            ) );
        } else {

            $wp_customize->add_setting( "$social_site", array(
                'type'              => 'theme_mod',
                'capability'        => 'edit_theme_options',
                'sanitize_callback' => 'esc_url_raw'
            ) );

            $wp_customize->add_control( new ct_ignite_url_input_control(
                $wp_customize, $social_site, array(
                    'label'   => $social_site . " " . __("url:", 'ignite' ),
                    'section' => 'ct_ignite_social_settings',
                    'priority'=> $priority,
                )
            ) );
        }
        // increment the priority for next site
        $priority = $priority + 5;
    }

    /***** Layout *****/

    // section
    $wp_customize->add_section( 'ct-layout', array(
        'title'      => __( 'Layout', 'ignite' ),
        'priority'   => 40,
        'capability' => 'edit_theme_options'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_layout_settings', array(
        'default'           => 'right',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_layout_settings',
    ) );
    // control
    $wp_customize->add_control( 'ct_ignite_sidebar_layout', array(
        'label'          => __( 'Pick Your Layout:', 'ignite' ),
        'section'        => 'ct-layout',
        'settings'       => 'ct_ignite_layout_settings',
        'type'           => 'radio',
        'choices'        => array(
            'right'   => __('Right sidebar', 'ignite'),
            'left'  => __('Left sidebar', 'ignite'),
        )
    ) );

    /***** Font Family *****/

    // section
    $wp_customize->add_section( 'ct-font-family', array(
        'title'       => __( 'Font Family', 'ignite' ),
        'priority'    => 55,
        'capability'  => 'edit_theme_options',
        'description' => __('The default font is "Lusitana".', 'ignite'),
        'panel'       => 'ct_ignite_font_panel'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_font_family_settings', array(
        'default'           => 'Lusitana',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_google_font_family'
    ) );
    // control
    $wp_customize->add_control( 'ct_ignite_font_family_settings', array(
        'type'     => 'select',
        'label'    => __( 'Site Font Family', 'ignite' ),
        'section'  => 'ct-font-family',
        'choices'  => array(
            'Lusitana' => 'Lusitana',
            'Roboto' => 'Roboto',
            'Lato' => 'Lato',
            'Droid Serif' => 'Droid Serif',
            'Roboto Slab' => 'Roboto Slab'
        )
    ) );

    /***** Font Weight *****/

    // get the weights available based on the current font
    $font_weights = ct_ignite_get_available_font_weights();

    // section
    $wp_customize->add_section( 'ct-font-weight', array(
        'title'       => __( 'Font Weight', 'ignite' ),
        'priority'    => 56,
        'capability'  => 'edit_theme_options',
        'description' => __("If you've just changed fonts, please save and refresh the page to update available weights.", "ignite"),
        'panel'       => 'ct_ignite_font_panel'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_font_weight_settings', array(
        'default'           => 'regular',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_google_font_weight'
    ) );
    // control
    $wp_customize->add_control( 'ct_ignite_font_weight_settings', array(
        'type'     => 'select',
        'label'    => __( 'Site Font Weight', 'ignite' ),
        'section'  => 'ct-font-weight',
        'choices'  => $font_weights
    ) );

    /***** Background *****/

    // section
    $wp_customize->add_section( 'ct-background', array(
        'title'      => __( 'Background', 'ignite' ),
        'priority'   => 60,
        'capability' => 'edit_theme_options'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_background_color_setting', array(
        'default'           => '#eeede8',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    // control
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'ct_ignite_background_color', array(
            'label'      => __( 'Background Color', 'ignite' ),
            'section'    => 'ct-background',
            'settings'   => 'ct_ignite_background_color_setting',
            'priority'       => 10,
        )
    ) );

    /***** Post Meta Display *****/

    // section
    $wp_customize->add_section( 'ct-post-meta', array(
        'title'      => __( 'Post Meta', 'ignite' ),
        'priority'   => 70,
        'capability' => 'edit_theme_options'
    ) );
    // setting - category
    $wp_customize->add_setting( 'ct_ignite_post_meta_categories_settings', array(
        'default'           => 'show',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting',
    ) );
    // control - category
    $wp_customize->add_control( 'ct_ignite_post_meta_categories_settings', array(
        'label'          => __( 'Show categories after posts?', 'ignite' ),
        'section'        => 'ct-post-meta',
        'settings'       => 'ct_ignite_post_meta_categories_settings',
        'type'           => 'radio',
        'choices'        => array(
            'show'   => __('Show', 'ignite'),
            'hide'  => __('Hide', 'ignite')
        )
    ) );
    // setting - tags
    $wp_customize->add_setting( 'ct_ignite_post_meta_tags_settings', array(
        'default'           => 'show',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting',
    ) );
    // control - tags
    $wp_customize->add_control( 'ct_ignite_post_meta_tags_settings', array(
        'label'          => __( 'Show tags after posts?', 'ignite' ),
        'section'        => 'ct-post-meta',
        'settings'       => 'ct_ignite_post_meta_tags_settings',
        'type'           => 'radio',
        'choices'        => array(
            'show'   => __('Show', 'ignite'),
            'hide'  => __('Hide', 'ignite')
        )
    ) );
    // setting - comments
    $wp_customize->add_setting( 'ct_ignite_post_meta_comments_settings', array(
        'default'           => 'hide',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting',
    ) );
    // control - comments
    $wp_customize->add_control( 'ct_ignite_post_meta_comments_settings', array(
        'label'          => __( 'Show comment count after posts?', 'ignite' ),
        'section'        => 'ct-post-meta',
        'settings'       => 'ct_ignite_post_meta_comments_settings',
        'type'           => 'radio',
        'choices'        => array(
            'show'   => __('Show', 'ignite'),
            'hide'  => __('Hide', 'ignite')
        )
    ) );

    /***** Comments Display *****/

    // section
    $wp_customize->add_section( 'ct_ignite_comment_display', array(
        'title'      => __( 'Comments', 'ignite' ),
        'priority'   => 75,
        'capability' => 'edit_theme_options'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_comments_setting', array(
        'default'           => 'none',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_comments_setting',
    ) );
    // control
    $wp_customize->add_control( new ct_ignite_Multi_Checkbox_Control(
        $wp_customize, 'ct_ignite_comments_setting', array(
            'label'          => __( 'Show comments on:', 'ignite' ),
            'section'        => 'ct_ignite_comment_display',
            'settings'       => 'ct_ignite_comments_setting',
            'type'           => 'multi-checkbox',
            'choices'        => array(
                'posts'   => __('Posts', 'ignite'),
                'pages'  => __('Pages', 'ignite'),
                'attachments'  => __('Attachments', 'ignite'),
                'none'  => __('Do not show', 'ignite')
            )
        )
    ) );

    /***** Footer Text *****/

    // section
    $wp_customize->add_section( 'ct-footer-text', array(
        'title'      => __( 'Footer Text', 'ignite' ),
        'priority'   => 80,
        'capability' => 'edit_theme_options'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_footer_text_setting', array(
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    // control
    $wp_customize->add_control( new ct_ignite_Textarea_Control(
        $wp_customize, 'ct_ignite_footer_text_setting', array(
            'label'          => __( 'Edit the text in your footer', 'ignite' ),
            'section'        => 'ct-footer-text',
            'settings'       => 'ct_ignite_footer_text_setting',
        )
    ) );

    /***** Custom CSS *****/

    // section
    $wp_customize->add_section( 'ct-custom-css', array(
        'title'      => __( 'Custom CSS', 'ignite' ),
        'priority'   => 85,
        'capability' => 'edit_theme_options'
    ) );
    // setting
    $wp_customize->add_setting( 'ct_ignite_custom_css_setting', array(
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'esc_textarea',
    ) );
    // control
    $wp_customize->add_control( new ct_ignite_Textarea_Control(
        $wp_customize, 'ct_ignite_custom_css_setting', array(
            'label'          => __( 'Add Custom CSS Here:', 'ignite' ),
            'section'        => 'ct-custom-css',
            'settings'       => 'ct_ignite_custom_css_setting',
        )
    ) );

    /***** Additional Options *****/

    // section
    $wp_customize->add_section( 'ct-additional-options', array(
        'title'      => __( 'Additional Options', 'ignite' ),
        'priority'   => 90,
        'capability' => 'edit_theme_options'
    ) );
    // setting - show full post
    $wp_customize->add_setting( 'ct_ignite_show_full_post_setting', array(
        'default'           => 'no',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting',
    ) );
    // control - show full post
    $wp_customize->add_control( 'ct_ignite_show_full_post', array(
        'label'          => __( 'Show full post on blog?', 'ignite' ),
        'section'        => 'ct-additional-options',
        'settings'       => 'ct_ignite_show_full_post_setting',
        'type'           => 'radio',
        'choices'        => array(
            'yes'   => __('Yes', 'ignite'),
            'no'  => __('No', 'ignite')
        )
    ) );
    // setting - show/hide breadcrumbs
    $wp_customize->add_setting( 'ct_ignite_show_breadcrumbs_setting', array(
        'default'           => 'yes',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting',
    ) );
    // control - show/hide breadcrumbs
    $wp_customize->add_control( 'ct_ignite_show_breadcrumbs_setting', array(
        'label'          => __( 'Show breadcrumbs?', 'ignite' ),
        'section'        => 'ct-additional-options',
        'settings'       => 'ct_ignite_show_breadcrumbs_setting',
        'type'           => 'radio',
        'choices'        => array(
            'yes'   => __('Yes', 'ignite'),
            'no'  => __('No', 'ignite')
        )
    ) );
    // setting - show/hide author meta
    $wp_customize->add_setting( 'ct_ignite_author_meta_settings', array(
        'default'           => 'show',
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting',
    ) );
    // control - show/hide author meta
    $wp_customize->add_control( 'ct_ignite_show_author_meta', array(
        'label'          => __( 'Show post author info after posts?', 'ignite' ),
        'section'        => 'ct-additional-options',
        'settings'       => 'ct_ignite_author_meta_settings',
        'type'           => 'radio',
        'choices'        => array(
            'show'   => __('Show', 'ignite'),
            'hide'  => __('Hide', 'ignite')
        )
    ) );
    // setting - excerpt length
    $wp_customize->add_setting( 'ct_ignite_excerpt_length_settings', array(
        'default'           => 30,
        'type'              => 'theme_mod',
        'capability'        => 'edit_theme_options',
        'sanitize_callback' => 'absint',
    ) );
    // control - excerpt length
    $wp_customize->add_control( new ct_ignite_number_input_control(
        $wp_customize, 'ct_ignite_excerpt_length_settings', array(
            'label' => 'Word count in automatic excerpts',
            'section' => 'ct-additional-options',
            'settings' => 'ct_ignite_excerpt_length_settings',
            'type' => 'number',
        )
    ) );
}

/***** Custom Sanitization Functions *****/

/*
 * simply using 'intval' is throwing an error, so using this custom sanitization function
 * Used in: Logo Positioning & Logo Size
 */
function ct_ignite_sanitize_integer($input){
    return intval( $input );
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_ignite_sanitize_email( $input ) {

    return sanitize_email( $input );
}

// sanitize layout selection
function ct_ignite_sanitize_layout_settings($input){
    $valid = array(
        'right'   => __('Right sidebar', 'ignite'),
        'left'  => __('Left sidebar', 'ignite'),
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// Sanitize font family
function ct_ignite_sanitize_google_font_family($input){

    $valid = array(
        'Lusitana' => 'Lusitana',
        'Roboto' => 'Roboto',
        'Lato' => 'Lato',
        'Droid Serif' => 'Droid Serif',
        'Roboto Slab' => 'Roboto Slab'
    );

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

// sanitize font weight
function ct_ignite_sanitize_google_font_weight($input){

    // get the available weights
    $font_weights = ct_ignite_get_available_font_weights();

    $valid = $font_weights;

    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}

/*
 * Sanitize Comment display multi-check
 */
function ct_ignite_sanitize_comments_setting($input){

    // valid data
    $valid = array(
        'posts'   => __('Posts', 'ignite'),
        'pages'  => __('Pages', 'ignite'),
        'attachments'  => __('Attachments', 'ignite'),
        'none'  => __('Do not show', 'ignite')
    );

    // loop through array
    foreach( $input as $selection ) {

        // if it's in the valid data, return it
        if ( array_key_exists( $selection, $valid ) ) {
            return $input;
        } else {
            return '';
        }
    }
}

/*
 * sanitize radio buttons with show/hide as choices
 * Used in: Post Author Info & Post Meta
 */
function ct_ignite_sanitize_show_hide_setting($input){
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

/*
 * sanitize radio buttons with yes/no as choices
 * Used in: Show Full Post & Breadcrumbs
 */
function ct_ignite_sanitize_yes_no_setting($input){
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

/***** Helper Functions *****/

// create social media site array
function ct_ignite_customizer_social_media_array() {

	// store social site names in array
	$social_sites = array('twitter', 'facebook', 'google-plus', 'flickr', 'pinterest', 'youtube', 'vimeo', 'tumblr', 'dribbble', 'rss', 'linkedin', 'instagram', 'reddit', 'soundcloud', 'spotify', 'vine','yahoo', 'behance', 'codepen', 'delicious', 'stumbleupon', 'deviantart', 'digg', 'git', 'hacker-news', 'steam', 'vk', 'academia', 'email');
	
	return $social_sites;
}

// set the comment display values on new sites or sites updating to new version with this feature
function compete_themes_set_comment_display_values() {

    // get the current value
    $current_settings = get_theme_mod( 'ct_ignite_comments_setting' );

    // if empty, set to all
    if( empty( $current_settings ) ) {
        set_theme_mod( 'ct_ignite_comments_setting', array( 'posts', 'pages', 'attachments', 'none' ) );
    }
}
add_action( 'init', 'compete_themes_set_comment_display_values' );

// get the available font weights based on the the font family selection
function ct_ignite_get_available_font_weights(){

    // current font is the one saved in the db
    $current_font = get_theme_mod('ct_ignite_font_family_settings');

    if($current_font){
        $selected_font = $current_font;
    } else {
        $selected_font = "Lusitana";
    }
    if($selected_font == "Lusitana"){
        $font_weights = array(
            'regular' => 'Regular',
            '700' => 'Bold'
        );
    }
    elseif($selected_font == "Roboto"){
        $font_weights = array(
            '100' => 'Thin',
            '100italic' => 'Thin Italic',
            '300' => 'Light',
            '300italic' => 'Light Italic',
            'regular' => 'Regular',
            'italic' => 'Italic',
            '500' => 'Medium',
            '500italic' => 'Medium Italic',
            '700' => 'Bold',
            '700italic' => 'Bold Italic',
            '900' => 'Ultra-Bold',
            '900italic' => 'Ultra-Bold Italic',
        );
    }
    elseif($selected_font == "Lato"){
        $font_weights = array(
            '100' => 'Thin',
            '100italic' => 'Thin Italic',
            '300' => 'Light',
            '300italic' => 'Light Italic',
            'regular' => 'Regular',
            'italic' => 'Italic',
            '700' => 'Bold',
            '700italic' => 'Bold Italic',
            '900' => 'Ultra-Bold',
            '900italic' => 'Ultra-Bold Italic',
        );
    }
    elseif($selected_font == "Droid Serif"){
        $font_weights = array(
            'regular' => 'Regular',
            'italic' => 'Italic',
            '700' => 'Bold',
            '700italic' => 'Bold Italic',
        );
    }
    elseif($selected_font == "Roboto Slab"){
        $font_weights = array(
            '100' => 'Thin',
            '300' => 'Light',
            'regular' => 'Regular',
            '700' => 'Bold',
        );
    }

    return $font_weights;
}