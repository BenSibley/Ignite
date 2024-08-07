<?php

add_action( 'customize_register', 'ct_ignite_add_customizer_content' );
function ct_ignite_add_customizer_content( $wp_customize ) {

	/***** Add PostMessage Support *****/

	$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	/***** Add Custom Controls *****/

	class ct_ignite_Multi_Checkbox_Control extends WP_Customize_Control {
		public $type = 'multi-checkbox';

		public function render_content() {

			if ( empty( $this->choices ) ) {
				return;
			}
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

	if ( method_exists( 'WP_Customize_Manager', 'add_panel' ) ) {

		// Logo panel
		$wp_customize->add_panel( 'ct_ignite_logo_panel', array(
			'priority'    => 30,
			'title'       => __( 'Logo', 'ignite' ),
			'description' => __( 'Upload, position, and resize your logo', 'ignite' )
		) );

		// Font panel
		$wp_customize->add_panel( 'ct_ignite_font_panel', array(
			'priority'    => 50,
			'title'       => __( 'Font', 'ignite' ),
			'description' => __( 'Choose a font family and font weight.', 'ignite' )
		) );
	}

	/***** Logo Upload *****/

	// section
	$wp_customize->add_section( 'ct-ignite-upload', array(
		'title'    => __( 'Logo Upload', 'ignite' ),
		'priority' => 30,
		'panel'    => 'ct_ignite_logo_panel'
	) );
	// setting
	$wp_customize->add_setting( 'logo_upload', array(
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize, 'logo_image', array(
				'label'    => __( 'Upload custom logo.', 'ignite' ),
				'section'  => 'ct-ignite-upload',
				'settings' => 'logo_upload'
			)
		)
	);

	/***** Logo Positioning *****/

	// section
	$wp_customize->add_section( 'ct-logo-positioning', array(
		'title'    => __( 'Logo Positioning', 'ignite' ),
		'priority' => 31,
		'panel'    => 'ct_ignite_logo_panel'
	) );
	// setting - logo positioning top/bottom
	$wp_customize->add_setting( 'logo_positioning_updown_setting', array(
		'default'           => 0,
		'sanitize_callback' => 'ct_ignite_sanitize_integer',
		'transport'         => 'postMessage'
	) );
	// setting - logo positioning left/right
	$wp_customize->add_setting( 'logo_positioning_leftright_setting', array(
		'default'           => 0,
		'sanitize_callback' => 'ct_ignite_sanitize_integer',
		'transport'         => 'postMessage'
	) );
	// control - logo positioning top/bottom
	$wp_customize->add_control( 'logo_positioning_updown_setting', array(
		'label'    => __( 'Up/down', 'ignite' ),
		'section'  => 'ct-logo-positioning',
		'settings' => 'logo_positioning_updown_setting',
		'type'     => 'number'
	) );
	// control - logo positioning left/right
	$wp_customize->add_control( 'logo_positioning_leftright_setting', array(
		'label'    => __( 'Left/right', 'ignite' ),
		'section'  => 'ct-logo-positioning',
		'settings' => 'logo_positioning_leftright_setting',
		'type'     => 'number'
	) );

	/***** Logo Size *****/

	// section
	$wp_customize->add_section( 'ct-logo-size', array(
		'title'    => __( 'Logo Size', 'ignite' ),
		'priority' => 32,
		'panel'    => 'ct_ignite_logo_panel'
	) );
	// setting - logo increase/decrease width
	$wp_customize->add_setting( 'logo_size_width_setting', array(
		'default'           => 0,
		'sanitize_callback' => 'ct_ignite_sanitize_integer',
		'transport'         => 'postMessage'
	) );
	// setting - logo increase/decrease height
	$wp_customize->add_setting( 'logo_size_height_setting', array(
		'default'           => 0,
		'sanitize_callback' => 'ct_ignite_sanitize_integer',
		'transport'         => 'postMessage'
	) );
	// control - logo increase/decrease width
	$wp_customize->add_control( 'logo_size_width_setting', array(
		'label'    => __( 'Increase max-width', 'ignite' ),
		'section'  => 'ct-logo-size',
		'settings' => 'logo_size_width_setting',
		'type'     => 'number'
	) );
	// control - logo increase/decrease height
	$wp_customize->add_control( 'logo_size_height_setting', array(
		'label'    => __( 'Increase max-height', 'ignite' ),
		'section'  => 'ct-logo-size',
		'settings' => 'logo_size_height_setting',
		'type'     => 'number'
	) );

	/***** Social Media Icons *****/

	// get the social sites array
	$social_sites = ct_ignite_customizer_social_media_array();

	// set a priority used to order the social sites
	$priority = 5;

	// section
	$wp_customize->add_section( 'ct_ignite_social_settings', array(
		'title'       => __( 'Social Media Icons', 'ignite' ),
		'priority'    => 35,
		'description' => __( 'Add the URL for each of your social plusfiles.', 'ignite' )
	) );

	// create a setting and control for each social site
	foreach ( $social_sites as $social_site ) {

		if ( $social_site == 'email' ) {

			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_ignite_sanitize_email'
			) );

			$wp_customize->add_control( $social_site, array(
				'label'    => __( "Email Address", 'ignite' ),
				'section'  => 'ct_ignite_social_settings',
				'priority' => $priority,
			) );
		} else if ( $social_site == 'phone' ) {
			// setting
			$wp_customize->add_setting( $social_site, array(
				'sanitize_callback' => 'ct_ignite_sanitize_phone'
			) );
			// control
			$wp_customize->add_control( $social_site, array(
				'label'    => __( 'Phone', 'ignite' ),
				'section'     => 'ct_ignite_social_settings',
				'priority'    => $priority,
				'type'        => 'text'
			) );
		} else {

			$label = ucfirst( $social_site );

			if ( $social_site == 'rss' ) {
				$label = __('RSS', 'ignite');
			} elseif ( $social_site == 'diaspora' ) {
				$label = __('diaspora*', 'ignite');
			} elseif ( $social_site == 'researchgate' ) {
				$label = __('ResearchGate', 'ignite');
			} elseif ( $social_site == 'soundcloud' ) {
				$label = __('SoundCloud', 'ignite');
			} elseif ( $social_site == 'slideshare' ) {
				$label = __('SlideShare', 'ignite');
			} elseif ( $social_site == 'codepen' ) {
				$label = __('CodePen', 'ignite');
			} elseif ( $social_site == 'stumbleupon' ) {
				$label = __('StumbleUpon', 'ignite');
			} elseif ( $social_site == 'deviantart' ) {
				$label = __('DeviantArt', 'ignite');
			} elseif ( $social_site == 'stack-overflow' ) {
				$label = __('Stack Overflow', 'ignite');
			}  elseif ( $social_site == 'hacker-news' ) {
				$label = __('Hacker News', 'ignite');
			} elseif ( $social_site == 'google-wallet' ) {
				$label = __('Google Wallet', 'ignite');
			} elseif ( $social_site == 'whatsapp' ) {
				$label = __('WhatsApp', 'ignite');
			} elseif ( $social_site == 'qq' ) {
				$label = __('QQ', 'ignite');
			} elseif ( $social_site == 'vk' ) {
				$label = __('VK', 'ignite');
			} elseif ( $social_site == 'ok-ru' ) {
				$label = __('OK.ru', 'ignite');
			}  elseif ( $social_site == 'wechat' ) {
				$label = __('WeChat', 'ignite');
			} elseif ( $social_site == 'tencent-weibo' ) {
				$label = __('Tencent Weibo', 'ignite');
			} elseif ( $social_site == 'paypal' ) {
				$label = __('PayPal', 'ignite');
			} elseif ( $social_site == 'artstation' ) {
				$label = __('ArtStation', 'ignite');
			} elseif ( $social_site == 'twitter' ) {
				$label = __('X (Twitter)', 'ignite');
			} elseif ( $social_site == 'email-form' ) {
				$label = __('Contact Form', 'ignite');
			}

			if ( $social_site == 'skype' ) {
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'ct_ignite_sanitize_skype'
				) );
				$wp_customize->add_control( $social_site, array(
					'label'       => $label,
					'description' => sprintf( __( 'Accepts Skype link protocol (<a href="%s" target="_blank">learn more</a>)', 'ignite' ), 'https://www.competethemes.com/blog/skype-links-wordpress/' ),
					'section'     => 'ct_ignite_social_settings',
					'type'        => 'url',
					'priority'    => $priority
				) );
			} else {
				$wp_customize->add_setting( $social_site, array(
					'sanitize_callback' => 'esc_url_raw'
				) );
				$wp_customize->add_control( $social_site, array(
					'label'    => $label,
					'section'  => 'ct_ignite_social_settings',
					'type'     => 'url',
					'priority' => $priority
				) );
			}
		}
		$priority = $priority + 5;
	}
	// Custom icon 1
	$wp_customize->add_setting( 'social_icon_custom_1', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control( 'social_icon_custom_1', array(
		'label'    => __('Custom icon 1 URL', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_1',
		'type'     => 'url',
		'priority' => $priority + 5
	) );
	$wp_customize->add_setting( 'social_icon_custom_1_image', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'social_icon_custom_1_image', array(
		'label'    => __( 'Custom icon 1 image', 'ignite' ),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_1_image',
		'priority' => $priority + 6
	)));
	$wp_customize->add_setting( 'social_icon_custom_1_size', array(
		'default'			=> '20',
		'sanitize_callback' => 'absint'
	) );
	$wp_customize->add_control( 'social_icon_custom_1_size', array(
		'label'    => __('Custom icon 1 size (px)', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_1_size',
		'type'     => 'number',
		'priority' => $priority + 7
	) );
	// Custom icon 2
	$wp_customize->add_setting( 'social_icon_custom_2', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control( 'social_icon_custom_2', array(
		'label'    => __('Custom icon 2 URL', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_2',
		'type'     => 'url',
		'priority' => $priority + 10
	) );
	$wp_customize->add_setting( 'social_icon_custom_2_image', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'social_icon_custom_2_image', array(
		'label'    => __( 'Custom icon 2 image', 'ignite' ),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_2_image',
		'priority' => $priority + 11
	)));
	$wp_customize->add_setting( 'social_icon_custom_2_size', array(
		'default'			=> '20',
		'sanitize_callback' => 'absint'
	) );
	$wp_customize->add_control( 'social_icon_custom_2_size', array(
		'label'    => __('Custom icon 2 size (px)', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_2_size',
		'type'     => 'number',
		'priority' => $priority + 12
	) );
	// Custom icon 3
	$wp_customize->add_setting( 'social_icon_custom_3', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control( 'social_icon_custom_3', array(
		'label'    => __('Custom icon 3 URL', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_3',
		'type'     => 'url',
		'priority' => $priority + 15
	) );
	$wp_customize->add_setting( 'social_icon_custom_3_image', array(
		'sanitize_callback' => 'esc_url_raw'
	) );
	$wp_customize->add_control(new WP_Customize_Image_Control( $wp_customize, 'social_icon_custom_3_image', array(
		'label'    => __( 'Custom icon 3 image', 'ignite' ),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_3_image',
		'priority' => $priority + 16
	)));
	$wp_customize->add_setting( 'social_icon_custom_3_size', array(
		'default'			=> '20',
		'sanitize_callback' => 'absint'
	) );
	$wp_customize->add_control( 'social_icon_custom_3_size', array(
		'label'    => __('Custom icon 3 size (px)', 'ignite'),
		'section'  => 'ct_ignite_social_settings',
		'settings' => 'social_icon_custom_3_size',
		'type'     => 'number',
		'priority' => $priority + 17
	) );

	/***** Layout *****/

	// section
	$wp_customize->add_section( 'ct-layout', array(
		'title'    => __( 'Layout', 'ignite' ),
		'priority' => 40
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_layout_settings', array(
		'default'           => 'right',
		'sanitize_callback' => 'ct_ignite_sanitize_layout_settings',
		'transport'         => 'postMessage'
	) );

	// control
	$wp_customize->add_control( 'ct_ignite_sidebar_layout', array(
		'label'       => __( 'Pick Your Layout:', 'ignite' ),
		'description' => sprintf( __( 'Want more layouts? <a target="_blank" href="%1$s">Check out %2$s Plus</a>.', 'ignite' ), 'https://www.competethemes.com/ignite-plus/', wp_get_theme( get_template() ) ),
		'section'     => 'ct-layout',
		'settings'    => 'ct_ignite_layout_settings',
		'type'        => 'radio',
		'choices'     => array(
			'right' => __( 'Right sidebar', 'ignite' ),
			'left'  => __( 'Left sidebar', 'ignite' ),
		)
	) );

	/***** Font Family *****/

	// section
	$wp_customize->add_section( 'ct-font-family', array(
		'title'       => __( 'Font Family', 'ignite' ),
		'priority'    => 55,
		'description' => __( 'The default font is "Lusitana".', 'ignite' ),
		'panel'       => 'ct_ignite_font_panel'
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_font_family_settings', array(
		'default'           => 'Lusitana',
		'sanitize_callback' => 'ct_ignite_sanitize_google_font_family',
		'transport'         => 'postMessage'
	) );

	// control
	$wp_customize->add_control( 'ct_ignite_font_family_settings', array(
		'type'        => 'select',
		'label'       => __( 'Site Font Family', 'ignite' ),
		'description' => sprintf( __( 'Want more fonts? <a target="_blank" href="%1$s">Check out %2$s Plus</a>', 'ignite' ), 'https://www.competethemes.com/ignite-plus/', wp_get_theme( get_template() ) ),
		'section'     => 'ct-font-family',
		'choices'     => array(
			'Lusitana'    => 'Lusitana',
			'Roboto'      => 'Roboto',
			'Lato'        => 'Lato',
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
		'description' => __( "If you've just changed fonts, please save and refresh the page to update available weights.", 'ignite' ),
		'panel'       => 'ct_ignite_font_panel'
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_font_weight_settings', array(
		'default'           => 'regular',
		'sanitize_callback' => 'ct_ignite_sanitize_google_font_weight',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'ct_ignite_font_weight_settings', array(
		'type'    => 'select',
		'label'   => __( 'Site Font Weight', 'ignite' ),
		'section' => 'ct-font-weight',
		'choices' => $font_weights
	) );

	/***** Background *****/

	// section
	$wp_customize->add_section( 'ct-background', array(
		'title'    => __( 'Background', 'ignite' ),
		'priority' => 60
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_background_color_setting', array(
		'default'           => '#eeede8',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'postMessage'
	) );

	// control
	$wp_customize->add_control( new WP_Customize_Color_Control(
		$wp_customize, 'ct_ignite_background_color', array(
			'label'       => __( 'Background Color', 'ignite' ),
			'description' => sprintf( __( 'Want background textures and images? <a target="_blank" href="%1$s">Check out %2$s Plus</a>', 'ignite' ), 'https://www.competethemes.com/ignite-plus/', wp_get_theme( get_template() ) ),
			'section'     => 'ct-background',
			'settings'    => 'ct_ignite_background_color_setting',
			'priority'    => 10
		)
	) );

	/***** Post Meta Display *****/

	// section
	$wp_customize->add_section( 'ct-post-meta', array(
		'title'    => __( 'Post Meta', 'ignite' ),
		'priority' => 70
	) );
	// setting - date
	$wp_customize->add_setting( 'ct_ignite_post_meta_date_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - date
	$wp_customize->add_control( 'ct_ignite_post_meta_date_settings', array(
		'label'    => __( 'Show date before posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_date_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - author
	$wp_customize->add_setting( 'ct_ignite_post_meta_author_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - author
	$wp_customize->add_control( 'ct_ignite_post_meta_author_settings', array(
		'label'    => __( 'Show author before posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_author_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - category
	$wp_customize->add_setting( 'ct_ignite_post_meta_categories_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - category
	$wp_customize->add_control( 'ct_ignite_post_meta_categories_settings', array(
		'label'    => __( 'Show categories after posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_categories_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - tags
	$wp_customize->add_setting( 'ct_ignite_post_meta_tags_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - tags
	$wp_customize->add_control( 'ct_ignite_post_meta_tags_settings', array(
		'label'    => __( 'Show tags after posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_tags_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - comments
	$wp_customize->add_setting( 'ct_ignite_post_meta_comments_settings', array(
		'default'           => 'hide',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - comments
	$wp_customize->add_control( 'ct_ignite_post_meta_comments_settings', array(
		'label'    => __( 'Show comment count after posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_comments_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - further reading
	$wp_customize->add_setting( 'ct_ignite_post_meta_further_reading_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - further reading
	$wp_customize->add_control( 'ct_ignite_post_meta_further_reading_settings', array(
		'label'    => __( 'Show prev/next post links after posts?', 'ignite' ),
		'section'  => 'ct-post-meta',
		'settings' => 'ct_ignite_post_meta_further_reading_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );

	/***** Comments Display *****/

	// section
	$wp_customize->add_section( 'ct_ignite_comment_display', array(
		'title'    => _x( 'Comments', 'noun', 'ignite' ),
		'priority' => 75
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_comments_setting', array(
		'default'           => array( 'posts', 'pages', 'attachments', 'none' ),
		'sanitize_callback' => 'ct_ignite_sanitize_comments_setting'
	) );
	// control
	$wp_customize->add_control( new ct_ignite_Multi_Checkbox_Control(
		$wp_customize, 'ct_ignite_comments_setting', array(
			'label'    => __( 'Show comments on:', 'ignite' ),
			'section'  => 'ct_ignite_comment_display',
			'settings' => 'ct_ignite_comments_setting',
			'type'     => 'multi-checkbox',
			'choices'  => array(
				'posts'       => __( 'Posts', 'ignite' ),
				'pages'       => __( 'Pages', 'ignite' ),
				'attachments' => __( 'Attachments', 'ignite' ),
				'none'        => __( 'Do not show', 'ignite' )
			)
		)
	) );

	/***** Footer Text *****/

	// section
	$wp_customize->add_section( 'ct-footer-text', array(
		'title'    => __( 'Footer Text', 'ignite' ),
		'priority' => 80
	) );
	// setting
	$wp_customize->add_setting( 'ct_ignite_footer_text_setting', array(
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'postMessage'
	) );
	// control
	$wp_customize->add_control( 'ct_ignite_footer_text_setting', array(
		'label'    => __( 'Edit the text in your footer', 'ignite' ),
		'type'     => 'textarea',
		'section'  => 'ct-footer-text',
		'settings' => 'ct_ignite_footer_text_setting'
	) );

	/***** Custom CSS *****/

	if ( function_exists( 'wp_update_custom_css_post' ) ) {
		// Migrate any existing theme CSS to the core option added in WordPress 4.7.
		$css = get_theme_mod( 'ct_ignite_custom_css_setting' );
		if ( $css ) {
			$core_css = wp_get_custom_css(); // Preserve any CSS already added to the core option.
			$return = wp_update_custom_css_post( $core_css . $css );
			if ( ! is_wp_error( $return ) ) {
				// Remove the old theme_mod, so that the CSS is stored in only one place moving forward.
				remove_theme_mod( 'ct_ignite_custom_css_setting' );
			}
		}
	} else {
		// section
		$wp_customize->add_section( 'ct-custom-css', array(
			'title'    => __( 'Custom CSS', 'ignite' ),
			'priority' => 85
		) );
		// setting
		$wp_customize->add_setting( 'ct_ignite_custom_css_setting', array(
			'sanitize_callback' => 'ct_ignite_sanitize_css',
			'transport'         => 'postMessage'
		) );
		// control
		$wp_customize->add_control( 'ct_ignite_custom_css_setting', array(
			'label'    => __( 'Add Custom CSS Here:', 'ignite' ),
			'type'     => 'textarea',
			'section'  => 'ct-custom-css',
			'settings' => 'ct_ignite_custom_css_setting'
		) );
	}

	/***** Additional Options *****/

	// section
	$wp_customize->add_section( 'ct-additional-options', array(
		'title'    => __( 'Additional Options', 'ignite' ),
		'priority' => 90
	) );
	// setting - show full post
	$wp_customize->add_setting( 'ct_ignite_show_full_post_setting', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting',
	) );
	// control - show full post
	$wp_customize->add_control( 'ct_ignite_show_full_post', array(
		'label'    => __( 'Show full post on blog?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_show_full_post_setting',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ignite' ),
			'no'  => __( 'No', 'ignite' )
		)
	) );
	// setting - show/hide breadcrumbs
	$wp_customize->add_setting( 'ct_ignite_show_breadcrumbs_setting', array(
		'default'           => 'yes',
		'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting'
	) );
	// control - show/hide breadcrumbs
	$wp_customize->add_control( 'ct_ignite_show_breadcrumbs_setting', array(
		'label'    => __( 'Show breadcrumbs?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_show_breadcrumbs_setting',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ignite' ),
			'no'  => __( 'No', 'ignite' )
		)
	) );
	// setting - show/hide author meta
	$wp_customize->add_setting( 'ct_ignite_author_meta_settings', array(
		'default'           => 'show',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - show/hide author meta
	$wp_customize->add_control( 'ct_ignite_show_author_meta', array(
		'label'    => __( 'Show post author info after posts?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_author_meta_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - parent menu icon
	$wp_customize->add_setting( 'ct_ignite_parent_menu_icon_settings', array(
		'default'           => 'hide',
		'sanitize_callback' => 'ct_ignite_sanitize_show_hide_setting'
	) );
	// control - parent menu icon
	$wp_customize->add_control( 'ct_ignite_parent_menu_icon_settings', array(
		'label'    => __( 'Add icon to parent menu items?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_parent_menu_icon_settings',
		'type'     => 'radio',
		'choices'  => array(
			'show' => __( 'Show', 'ignite' ),
			'hide' => __( 'Hide', 'ignite' )
		)
	) );
	// setting - excerpt length
	$wp_customize->add_setting( 'ct_ignite_excerpt_length_settings', array(
		'default'           => 30,
		'sanitize_callback' => 'absint'
	) );
	// control - excerpt length
	$wp_customize->add_control( 'ct_ignite_excerpt_length_settings', array(
		'label'    => __( 'Word count in automatic excerpts', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_excerpt_length_settings',
		'type'     => 'number'
	) );
	// Read More text - setting
	$wp_customize->add_setting( 'ct_ignite_read_more_text', array(
		'default'           => __( 'Read More', 'ignite' ),
		'sanitize_callback' => 'ct_ignite_sanitize_text'
	) );
	// Read More text - control
	$wp_customize->add_control( 'ct_ignite_read_more_text', array(
		'label'    => __( 'Read More button text', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'ct_ignite_read_more_text',
		'type'     => 'text'
	) );
	// setting - scroll-to-top arrow
	$wp_customize->add_setting( 'scroll_to_top', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting'
	) );
	// control - scroll-to-top arrow
	$wp_customize->add_control( 'scroll_to_top', array(
		'label'    => __( 'Display Scroll-to-top arrow?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'scroll_to_top',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ignite' ),
			'no'  => __( 'No', 'ignite' )
		)
	) );
	// setting - last updated
	$wp_customize->add_setting( 'last_updated', array(
		'default'           => 'no',
		'sanitize_callback' => 'ct_ignite_sanitize_yes_no_setting'
	) );
	// control - last updated
	$wp_customize->add_control( 'last_updated', array(
		'label'    => __( 'Display the date each post was last updated?', 'ignite' ),
		'section'  => 'ct-additional-options',
		'settings' => 'last_updated',
		'type'     => 'radio',
		'choices'  => array(
			'yes' => __( 'Yes', 'ignite' ),
			'no'  => __( 'No', 'ignite' )
		)
	) );
}

/***** Custom Sanitization Functions *****/

/*
 * simply using 'intval' is throwing an error, so using this custom sanitization function
 * Used in: Logo Positioning & Logo Size
 */
function ct_ignite_sanitize_integer( $input ) {
	return intval( $input );
}

/*
 * sanitize email address
 * Used in: Social Media Icons
 */
function ct_ignite_sanitize_email( $input ) {

	return sanitize_email( $input );
}

function ct_ignite_sanitize_layout_settings( $input ) {
	$valid = array(
		'right' => __( 'Right sidebar', 'ignite' ),
		'left'  => __( 'Left sidebar', 'ignite' ),
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_ignite_sanitize_google_font_family( $input ) {

	$valid = array(
		'Lusitana'    => 'Lusitana',
		'Roboto'      => 'Roboto',
		'Lato'        => 'Lato',
		'Droid Serif' => 'Droid Serif',
		'Roboto Slab' => 'Roboto Slab'
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_ignite_sanitize_google_font_weight( $input ) {

	$font_weights = ct_ignite_get_available_font_weights();
	$valid        = $font_weights;

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_ignite_sanitize_comments_setting( $input ) {

	$valid = array(
		'posts'       => __( 'Posts', 'ignite' ),
		'pages'       => __( 'Pages', 'ignite' ),
		'attachments' => __( 'Attachments', 'ignite' ),
		'none'        => __( 'Do not show', 'ignite' )
	);

	foreach ( $input as $selection ) {
		return array_key_exists( $selection, $valid ) ? $input : '';
	}
}

/*
 * sanitize radio buttons with show/hide as choices
 * Used in: Post Author Info & Post Meta
 */
function ct_ignite_sanitize_show_hide_setting( $input ) {
	$valid = array(
		'show' => __( 'Show', 'ignite' ),
		'hide' => __( 'Hide', 'ignite' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

/*
 * sanitize radio buttons with yes/no as choices
 * Used in: Show Full Post & Breadcrumbs
 */
function ct_ignite_sanitize_yes_no_setting( $input ) {
	$valid = array(
		'yes' => __( 'Yes', 'ignite' ),
		'no'  => __( 'No', 'ignite' )
	);

	return array_key_exists( $input, $valid ) ? $input : '';
}

function ct_ignite_sanitize_text( $input ) {
	return wp_kses_post( force_balance_tags( $input ) );
}

function ct_ignite_sanitize_skype( $input ) {
	return esc_url_raw( $input, array( 'http', 'https', 'skype' ) );
}

/***** Helper Functions *****/

function ct_ignite_get_available_font_weights( $current_font = '' ) {

	if ( empty( $current_font ) ) {
		$current_font = get_theme_mod( 'ct_ignite_font_family_settings' );
	}
	if ( empty( $current_font ) ) {
		$current_font = 'Lusitana';
	}

	$font_weights = array();

	if ( $current_font == "Lusitana" ) {
		$font_weights = array(
			'regular' => 'Regular',
			'700'     => 'Bold'
		);
	} elseif ( $current_font == "Roboto" ) {
		$font_weights = array(
			'100'       => 'Thin',
			'100italic' => 'Thin Italic',
			'300'       => 'Light',
			'300italic' => 'Light Italic',
			'regular'   => 'Regular',
			'italic'    => 'Italic',
			'500'       => 'Medium',
			'500italic' => 'Medium Italic',
			'700'       => 'Bold',
			'700italic' => 'Bold Italic',
			'900'       => 'Ultra-Bold',
			'900italic' => 'Ultra-Bold Italic',
		);
	} elseif ( $current_font == "Lato" ) {
		$font_weights = array(
			'100'       => 'Thin',
			'100italic' => 'Thin Italic',
			'300'       => 'Light',
			'300italic' => 'Light Italic',
			'regular'   => 'Regular',
			'italic'    => 'Italic',
			'700'       => 'Bold',
			'700italic' => 'Bold Italic',
			'900'       => 'Ultra-Bold',
			'900italic' => 'Ultra-Bold Italic',
		);
	} elseif ( $current_font == "Droid Serif" ) {
		$font_weights = array(
			'regular'   => 'Regular',
			'italic'    => 'Italic',
			'700'       => 'Bold',
			'700italic' => 'Bold Italic',
		);
	} elseif ( $current_font == "Roboto Slab" ) {
		$font_weights = array(
			'100'     => 'Thin',
			'300'     => 'Light',
			'regular' => 'Regular',
			'700'     => 'Bold',
		);
	}

	return $font_weights;
}

function ct_ignite_sanitize_css( $css ) {
	$css = wp_kses( $css, array( '\'', '\"' ) );
	$css = str_replace( '&gt;', '>', $css );

	return $css;
}

function ct_ignite_sanitize_phone( $input ) {
	if ( $input != '' ) {
		return esc_url_raw( 'tel:' . $input, array( 'tel' ) );
	} else {
		return '';
	}
}

function ct_ignite_customize_preview_js() {
	if ( !(isset($_GET['mailoptin_optin_campaign_id']) || isset($_GET['mailoptin_email_campaign_id'])) ) {
		$url = 'https://www.competethemes.com/ignite-plus/?utm_source=wp-dashboard&utm_medium=Customizer&utm_campaign=Ignite%20Plus%20-%20Customizer';
		$content = "<script>jQuery('#customize-info').prepend('<div class=\"upgrades-ad\"><a href=\"". $url ."\" target=\"_blank\">Customize Colors with Ignite Plus <span>&rarr;</span></a></div>')</script>";
		echo apply_filters('ct_ignite_customizer_ad', $content);
	}
}
add_action('customize_controls_print_footer_scripts', 'ct_ignite_customize_preview_js');