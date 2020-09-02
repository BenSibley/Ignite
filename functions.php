<?php

//----------------------------------------------------------------------------------
//	Include all required files
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/breadcrumbs.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/customizer.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/deprecated.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/last-updated-meta-box.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/review.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/scripts.php' );
require_once( trailingslashit( get_template_directory() ) . 'inc/widgets/image_widget.php' );

//----------------------------------------------------------------------------------
//	Include review request
//----------------------------------------------------------------------------------
require_once( trailingslashit( get_template_directory() ) . 'dnh/handler.php' );
new WP_Review_Me( array(
		'days_after' => 14,
		'type'       => 'theme',
		'slug'       => 'ignite',
		'message'    => __( 'Hey! Sorry to interrupt, but you\'ve been using Ignite for a little while now. If you\'re happy with this theme, could you take a minute to leave a review? <i>You won\'t see this notice again after closing it.</i>', 'ignite' )
	)
);

if ( ! function_exists( ( 'ct_ignite_set_content_width' ) ) ) {
	function ct_ignite_set_content_width() {
		if ( ! isset( $content_width ) ) {
			$content_width = 840;
		}
	}
}
add_action( 'after_setup_theme', 'ct_ignite_set_content_width', 0 );

if ( ! function_exists( 'ct_ignite_theme_setup' ) ) {
	function ct_ignite_theme_setup() {

		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption'
		) );
		add_theme_support( 'infinite-scroll', array(
			'container' => 'loop-container',
			'footer'    => 'overflow-container'
		) );
		// Add WooCommerce support
		add_theme_support( 'woocommerce' );
		// Support WooCommerce image gallery features 
		add_theme_support( 'wc-product-gallery-zoom' ); 
		add_theme_support( 'wc-product-gallery-lightbox' ); 
		add_theme_support( 'wc-product-gallery-slider' );

		// Gutenberg - wide & full images
		add_theme_support( 'align-wide' );

		// Gutenberg - add support for editor styles
		add_theme_support('editor-styles');

		// Gutenberg - modify the font sizes
		add_theme_support( 'editor-font-sizes', array(
			array(
					'name' => __( 'small', 'ignite' ),
					'shortName' => __( 'S', 'ignite' ),
					'size' => 13,
					'slug' => 'small'
			),
			array(
					'name' => __( 'regular', 'ignite' ),
					'shortName' => __( 'M', 'ignite' ),
					'size' => 16,
					'slug' => 'regular'
			),
			array(
					'name' => __( 'large', 'ignite' ),
					'shortName' => __( 'L', 'ignite' ),
					'size' => 21,
					'slug' => 'large'
			),
			array(
					'name' => __( 'larger', 'ignite' ),
					'shortName' => __( 'XL', 'ignite' ),
					'size' => 36,
					'slug' => 'larger'
			)
		) );
		
		load_theme_textdomain( 'ignite', get_template_directory() . '/languages' );
	}
}
add_action( 'after_setup_theme', 'ct_ignite_theme_setup', 10 );

//-----------------------------------------------------------------------------
// Load custom stylesheet for the post editor
//-----------------------------------------------------------------------------
if ( ! function_exists( 'ct_ignite_add_editor_styles' ) ) {
	function ct_ignite_add_editor_styles() {
		add_editor_style( 'styles/editor-style.css' );
	}
}
add_action( 'admin_init', 'ct_ignite_add_editor_styles' );

if ( ! function_exists( 'ct_ignite_register_sidebar' ) ) {
	function ct_ignite_register_sidebar() {
		register_sidebar( array(
			'name'          => esc_html__( 'Primary Sidebar', 'ignite' ),
			'id'            => 'primary',
			'description'   => esc_html__( 'The main sidebar', 'ignite' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>'
		) );
	}
}
add_action( 'widgets_init', 'ct_ignite_register_sidebar' );

if ( ! function_exists( 'ct_ignite_register_menu' ) ) {
	function ct_ignite_register_menu() {
		register_nav_menu( 'primary', esc_html__( 'Primary', 'ignite' ) );
	}
}
add_action( 'init', 'ct_ignite_register_menu' );

if ( ! function_exists( 'ct_ignite_customize_comments' ) ) {
	function ct_ignite_customize_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		global $post;
		?>
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-author">
				<?php
				echo get_avatar( get_comment_author_email(), 48 );
				?>
				<span class="author-name"><?php comment_author_link(); ?></span>
				<span> <?php echo esc_html_x( 'said:', 'COMMENTER said:', 'ignite' ); ?></span>
			</div>
			<div class="comment-content">
				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em><?php esc_html_e( 'Your comment is awaiting moderation.', 'ignite' ) ?></em>
					<br/>
				<?php endif; ?>
				<?php comment_text(); ?>
			</div>
			<div class="comment-meta">
				<div class="comment-date"><?php comment_date(); ?></div>
				<?php edit_comment_link( esc_html_x( 'Edit', 'Edit this comment', 'ignite' ) ); ?>
				<?php comment_reply_link( array_merge( $args, array(
					'reply_text' => esc_html_x( 'Reply', 'Reply to this comment', 'ignite' ),
					'depth'      => $depth,
					'max_depth'  => $args['max_depth']
				) ) ); ?>
			</div>
		</article>
		<?php
	}
}

if ( ! function_exists( 'ct_ignite_update_fields' ) ) {
	function ct_ignite_update_fields( $fields ) {

		$commenter = wp_get_current_commenter();
		$req       = get_option( 'require_name_email' );
		$label     = $req ? '*' : ' ' . esc_html__( '(optional)', 'ignite' );
		$aria_req  = $req ? "aria-required='true'" : '';

		$fields['author'] =
			'<p class="comment-form-author">
                <label for="author" class="screen-reader-text">' . esc_html__( 'Your Name', 'ignite' ) . '</label>
                <input placeholder="' . esc_attr__( 'Your Name', 'ignite' ) . esc_attr( $label ) . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
			'" size="30" ' . $aria_req . ' />
            </p>';
		$fields['email'] =
			'<p class="comment-form-email">
                <label for="email" class="screen-reader-text">' . esc_html__( 'Your Email', 'ignite' ) . '</label>
                <input placeholder="' . esc_attr__( 'Your Email', 'ignite' ) . esc_attr( $label ) . '" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
			'" size="30" ' . $aria_req . ' />
            </p>';
		$fields['url'] =
			'<p class="comment-form-url">
                <label for="url" class="screen-reader-text">' . esc_html__( 'Your Website URL', 'ignite' ) . '</label>
                <input placeholder="' . esc_attr__( 'Your URL', 'ignite' ) . ' ' . esc_attr__( '(optional)', 'ignite' ) . '" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
			'" size="30" />
                </p>';

		return $fields;
	}
}
add_filter( 'comment_form_default_fields', 'ct_ignite_update_fields' );

if ( ! function_exists( 'ct_ignite_update_comment_field' ) ) {
	function ct_ignite_update_comment_field( $comment_field ) {

		// don't filter the WooCommerce review form
		if ( function_exists( 'is_woocommerce' ) ) {
			if ( is_woocommerce() ) {
				return $comment_field;
			}
		}
		
		$comment_field =
			'<p class="comment-form-comment">
                <label for="comment" class="screen-reader-text">' . esc_html__( 'Your Comment', 'ignite' ) . '</label>
                <textarea required placeholder="' . esc_attr__( 'Enter Your Comment', 'ignite' ) . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
            </p>';

		return $comment_field;
	}
}
add_filter( 'comment_form_field_comment', 'ct_ignite_update_comment_field', 7 );

if ( ! function_exists( 'ct_ignite_remove_comments_notes_after' ) ) {
	function ct_ignite_remove_comments_notes_after( $defaults ) {
		$defaults['comment_notes_after'] = '';
		return $defaults;
	}
}
add_action( 'comment_form_defaults', 'ct_ignite_remove_comments_notes_after' );

if ( ! function_exists( 'ct_ignite_filter_read_more_link' ) ) {
	function ct_ignite_filter_read_more_link( $custom = false ) {

		if ( is_feed() ) {
			return;
		}
		global $post;
		$ismore             = strpos( $post->post_content, '<!--more-->' );
		$read_more_text     = get_theme_mod( 'ct_ignite_read_more_text' );
		$new_excerpt_length = get_theme_mod( 'ct_ignite_excerpt_length_settings' );
		$excerpt_more       = ( $new_excerpt_length === 0 ) ? '' : '&#8230;';
		$output = '';

		// add ellipsis for automatic excerpts
		if ( empty( $ismore ) && $custom !== true ) {
			$output .= $excerpt_more;
		}
		// Because i18n text cannot be stored in a variable
		if ( empty( $read_more_text ) ) {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'ignite' ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		} else {
			$output .= '<div class="more-link-wrapper"><a class="more-link" href="' . esc_url( get_permalink() ) . '">' . esc_html( $read_more_text ) . '<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span></a></div>';
		}
		return $output;
	}
}
add_filter( 'the_content_more_link', 'ct_ignite_filter_read_more_link' ); // more tags
add_filter( 'excerpt_more', 'ct_ignite_filter_read_more_link', 10 ); // automatic excerpts

// handle manual excerpts
if ( ! function_exists( 'ct_ignite_filter_manual_excerpts' ) ) {
	function ct_ignite_filter_manual_excerpts( $excerpt ) {
		$excerpt_more = '';
		if ( has_excerpt() ) {
			$excerpt_more = ct_ignite_filter_read_more_link( true );
		}
		return $excerpt . $excerpt_more;
	}
}
add_filter( 'get_the_excerpt', 'ct_ignite_filter_manual_excerpts' );

if ( ! function_exists( 'ct_ignite_excerpt' ) ) {
	function ct_ignite_excerpt() {
		global $post;
		$show_full_post = get_theme_mod( 'ct_ignite_show_full_post_setting' );
		$ismore         = strpos( $post->post_content, '<!--more-->' );

		if ( $show_full_post === 'yes' || $ismore ) {
			the_content();
		} else {
			the_excerpt();
		}
	}
}

if ( ! function_exists( 'ct_ignite_custom_excerpt_length' ) ) {
	function ct_ignite_custom_excerpt_length( $length ) {

		$new_excerpt_length = get_theme_mod( 'ct_ignite_excerpt_length_settings' );

		if ( ! empty( $new_excerpt_length ) && $new_excerpt_length != 30 ) {
			return $new_excerpt_length;
		} elseif ( $new_excerpt_length === 0 ) {
			return 0;
		} else {
			return 30;
		}
	}
}
add_filter( 'excerpt_length', 'ct_ignite_custom_excerpt_length', 999 );

if ( ! function_exists( 'ct_ignite_remove_more_link_scroll' ) ) {
	function ct_ignite_remove_more_link_scroll( $link ) {
		$link = preg_replace( '|#more-[0-9]+|', '', $link );
		return $link;
	}
}
add_filter( 'the_content_more_link', 'ct_ignite_remove_more_link_scroll' );

// Yoast OG description has "Continue ReadingTitle of the Post" due to its use of get_the_excerpt(). This fixes that.
function ct_ignite_update_yoast_og_description( $ogdesc ) {
	$read_more_text = get_theme_mod( 'read_more_text' );
	if ( empty( $read_more_text ) ) {
		$read_more_text = esc_html__( 'Continue Reading', 'ignite' );
	}
	$ogdesc = substr( $ogdesc, 0, strpos( $ogdesc, $read_more_text ) );

	return $ogdesc;
}
add_filter( 'wpseo_opengraph_desc', 'ct_ignite_update_yoast_og_description' );

if ( ! function_exists( 'ct_ignite_featured_image' ) ) {
	function ct_ignite_featured_image() {

		global $post;
		$featured_image = '';

		if ( has_post_thumbnail( $post->ID ) ) {

			if ( is_singular() ) {
				$featured_image = '<div class="featured-image">' . get_the_post_thumbnail( $post->ID, 'full' ) . '</div>';
			} else {
				$featured_image = '<div class="featured-image"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . get_the_post_thumbnail( $post->ID, 'full' ) . '</a></div>';
			}
		}
		if ( $featured_image ) {
			echo $featured_image;
		}
	}
}

if ( ! function_exists( 'ct_ignite_archive_count_add_span' ) ) {
	function ct_ignite_archive_count_add_span( $links ) {
		$links = str_replace( '</a>&nbsp;(', '</a><span>', $links );
		$links = str_replace( ')', '</span>', $links );

		return $links;
	}
}
add_filter( 'get_archives_link', 'ct_ignite_archive_count_add_span' );

if ( ! function_exists( 'ct_ignite_category_count_add_span' ) ) {
	function ct_ignite_category_count_add_span( $links ) {
		$links = str_replace( '</a> (', '</a> <span>', $links );
		$links = str_replace( ')', '</span>', $links );

		return $links;
	}
}
add_filter( 'wp_list_categories', 'ct_ignite_category_count_add_span' );

if ( ! function_exists( 'ct_ignite_wp_page_menu' ) ) {
	function ct_ignite_wp_page_menu() {
		wp_page_menu( array( "menu_class" => "menu-unset" ) );
	}
}

if ( ! function_exists( 'ct_ignite_body_class' ) ) {
	function ct_ignite_body_class( $classes ) {

		global $post;
		$layout = get_theme_mod( 'ct_ignite_layout_settings' );

		if ( $layout == 'left' ) {
			$classes[] = 'sidebar-left';
		}
		if ( get_theme_mod( 'ct_ignite_parent_menu_icon_settings' ) == 'show' ) {
			$classes[] = 'parent-icons';
		}
		// add all historic singular classes
		if ( is_singular() ) {
			$classes[] = 'singular';
			if ( is_singular( 'page' ) ) {
				$classes[] = 'singular-page';
				$classes[] = 'singular-page-' . $post->ID;
			} elseif ( is_singular( 'post' ) ) {
				$classes[] = 'singular-post';
				$classes[] = 'singular-post-' . $post->ID;
			} elseif ( is_singular( 'attachment' ) ) {
				$classes[] = 'singular-attachment';
				$classes[] = 'singular-attachment-' . $post->ID;
			}
		}

		return $classes;
	}
}
add_filter( 'body_class', 'ct_ignite_body_class' );

if ( ! function_exists( 'ct_ignite_post_class_update' ) ) {
	function ct_ignite_post_class_update( $classes ) {

		if ( ! is_singular() ) {
			foreach ( $classes as $key => $class ) {
				$classes[] = 'excerpt';
			}
		} else {
			$classes[] = 'entry';
		}

		return $classes;
	}
}
add_filter( 'post_class', 'ct_ignite_post_class_update' );

if ( ! function_exists( 'ct_ignite_logo_positioning_css' ) ) {
	function ct_ignite_logo_positioning_css() {

		$updown    = get_theme_mod( 'logo_positioning_updown_setting' );
		$leftright = get_theme_mod( 'logo_positioning_leftright_setting' );

		if ( $updown || $leftright ) {

			$css = "
            #site-header .logo {
                position: relative;
                bottom: " . $updown . "px;
                left: " . $leftright . "px;
                right: auto;
                top: auto;
        }";
			$css = ct_ignite_sanitize_css( $css );
			wp_add_inline_style( 'ct-ignite-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_logo_positioning_css', 20 );

if ( ! function_exists( 'ct_ignite_logo_size_css' ) ) {
	function ct_ignite_logo_size_css() {

		$width  = get_theme_mod( 'logo_size_width_setting' );
		$height = get_theme_mod( 'logo_size_height_setting' );

		if ( $width || $height ) {

			$max_width  = 156 + $width;
			$max_height = 59 + $height;

			$css = "
            #logo {
                max-width: " . $max_width . "px;
                max-height: " . $max_height . "px;
        }";
			$css = ct_ignite_sanitize_css( $css );
			wp_add_inline_style( 'ct-ignite-style', $css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_logo_size_css', 20 );

if ( ! function_exists( 'ct_ignite_custom_css_output' ) ) {
	function ct_ignite_custom_css_output() {

		if ( function_exists( 'wp_get_custom_css' ) ) {
			$custom_css = wp_get_custom_css();
		} else {
			$custom_css = get_theme_mod( 'ct_ignite_custom_css_setting' );
		}

		if ( $custom_css ) {
			$custom_css = ct_ignite_sanitize_css( $custom_css );
			wp_add_inline_style( 'ct-ignite-style', $custom_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_custom_css_output', 20 );

if ( ! function_exists( 'ct_ignite_show_avatars_check' ) ) {
	function ct_ignite_show_avatars_check( $classes ) {
		$classes[] = get_option( 'show_avatars' ) ? 'avatars' : 'no-avatars';

		return $classes;
	}
}
add_action( 'comment_class', 'ct_ignite_show_avatars_check' );

if ( ! function_exists( 'ct_ignite_change_font' ) ) {
	function ct_ignite_change_font() {

		$font        = get_theme_mod( 'ct_ignite_font_family_settings' );
		$font_weight = get_theme_mod( 'ct_ignite_font_weight_settings' );
		$font_style  = 'normal';

		if ( $font != 'Lusitana' && ! empty( $font ) ) {

			if ( $font_weight == 'italic' ) {
				$font_weight = 400;
				$font_style  = 'italic';
			} elseif ( strpos( $font_weight, 'italic' ) !== false ) {
				$font_weight = str_replace( $font_weight, 'italic', '' );
				$font_style  = 'italic';
			} elseif ( $font_weight == 'regular' ) {
				$font_weight = 400;
			}

			$css = "
            body, h1, h2, h3, h4, h5, h6, input:not([type='checkbox']):not([type='radio']):not([type='submit']):not([type='file']), input[type='submit'], textarea {
                font-family: $font;
                font-style: $font_style;
                font-weight: $font_weight;
            }
        ";

			$css = ct_ignite_sanitize_css( $css );

			wp_add_inline_style( 'ct-ignite-style', $css );

			wp_deregister_style( 'ct-ignite-google-fonts' );

			$fonts_url = ct_ignite_format_font_request( $font );

			wp_register_style( 'ct-ignite-google-fonts', $fonts_url );
			wp_enqueue_style( 'ct-ignite-google-fonts' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_change_font', 20 );

if ( ! function_exists( 'ct_ignite_format_font_request' ) ) {
	function ct_ignite_format_font_request( $font ) {

		$ajax      = false;
		$weights   = ct_ignite_get_available_font_weights( $font );
		$fonts_url = '';

		if ( isset( $_POST['font'] ) ) {
			$font = $_POST['font'];
			$ajax = true;
		}

		if ( is_array( $weights ) && ! empty( $weights ) ) {

			$weights      = implode( ',', $weights );
			$weights      = str_replace( 'regular', '400', $weights );
			$font         = str_replace( ' ', '+', $font );
			$font_request = $font . ':' . $weights;
			$font_args    = array(
				'family' => $font_request,
				'subset' => urlencode( 'latin,latin-ext' )
			);

			$fonts_url = add_query_arg( $font_args, '//fonts.googleapis.com/css' );
			$fonts_url = apply_filters( 'ignite-font-filter', $fonts_url );
			$fonts_url = esc_url_raw( $fonts_url );
		}

		if ( $ajax ) {
			echo $fonts_url;
			die();
		} else {
			return $fonts_url;
		}
	}
}
add_action( 'wp_ajax_format_font_request', 'ct_ignite_format_font_request' );
add_action( 'wp_ajax_nopriv_format_font_request', 'ct_ignite_format_font_request' );

if ( ! function_exists( 'ct_ignite_background_css' ) ) {
	function ct_ignite_background_css() {

		$background_color = get_theme_mod( 'ct_ignite_background_color_setting' );

		if ( $background_color != '#eeede8' ) {

			$background_color_css = "
            .overflow-container {
                background: $background_color;
            }
            .main, .sidebar-primary-container, .breadcrumb-trail {
                background: none;
            }
        ";

			$background_color_css = ct_ignite_sanitize_css( $background_color_css );

			wp_add_inline_style( 'ct-ignite-style', $background_color_css );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ct_ignite_background_css', 20 );

if ( ! function_exists( 'ct_ignite_customizer_social_media_array' ) ) {
	function ct_ignite_customizer_social_media_array() {

		$social_sites = array(
			'twitter',
			'facebook',
			'instagram',
			'linkedin',
			'pinterest',
			'youtube',
			'rss',
			'email',
			'phone',
			'email-form',
			'academia',
			'amazon',
			'artstation',
			'bandcamp',
			'behance',
			'bitbucket',
			'codepen',
			'delicious',
			'deviantart',
			'digg',
			'discord',
			'dribbble',
			'etsy',
			'flickr',
			'foursquare',
			'github',
			'goodreads',
			'google-wallet',
			'hacker-news',
			'medium',
			'meetup',
			'mixcloud',
			'ok-ru',
			'patreon',
			'paypal',
			'pocket',
			'podcast',
			'qq',
			'quora',
			'ravelry',
			'reddit',
			'skype',
			'slack',
			'slideshare',
			'snapchat',
			'soundcloud',
			'spotify',
			'stack-overflow',
			'steam',
			'stumbleupon',
			'telegram',
			'tencent-weibo',
			'tumblr',
			'twitch',
			'untappd',
			'vimeo',
			'vine',
			'vk',
			'wechat',
			'weibo',
			'whatsapp',
			'xing',
			'yahoo',
			'yelp',
			'500px'
		);

		return apply_filters( 'ct_ignite_customizer_social_media_array_filter', $social_sites );
	}
}

if ( ! function_exists( 'ct_ignite_add_meta_elements' ) ) {
	function ct_ignite_add_meta_elements() {

		$meta_elements = '';

		$meta_elements .= sprintf( '<meta charset="%s" />' . "\n", esc_attr( get_bloginfo( 'charset' ) ) );
		$meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

		$theme    = wp_get_theme( get_template() );
		$template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
		$meta_elements .= $template;

		echo $meta_elements;
	}
}
add_action( 'wp_head', 'ct_ignite_add_meta_elements', 1 );

/* Move the WordPress generator to a better priority. */
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );

if ( ! function_exists( 'ct_ignite_get_content_template' ) ) {
	function ct_ignite_get_content_template() {

		// Blog
		if ( is_home() ) {
			get_template_part( 'content', get_post_type() );
		} // Post/Page
		elseif ( is_singular() ) {
			get_template_part( 'content', get_post_type() );
			comments_template();
		}  // Archive
		elseif ( is_archive() ) {

			if ( function_exists( 'is_bbpress' ) ) {

				// bbPress forum list
				if ( is_bbpress() ) {
					get_template_part( 'content/bbpress' );
				} // normal archive
				else {
					get_template_part( 'content' );
				}
			} // Archive
			else {
				get_template_part( 'content' );
			}
		} // bbPress
		elseif ( function_exists( 'is_bbpress' ) && is_bbpress() ) {
			get_template_part( 'content/bbpress' );
		} // Custom Post Type
		else {
			get_template_part( 'content', get_post_type() );
		}
	}
}

// allow skype URIs to be used
if ( ! function_exists( 'ct_ignite_allow_skype_protocol' ) ) {
	function ct_ignite_allow_skype_protocol( $protocols ) {
		$protocols[] = 'skype';

		return $protocols;
	}
}
add_filter( 'kses_allowed_protocols' , 'ct_ignite_allow_skype_protocol' );

if ( ! function_exists( ( 'ct_ignite_settings_notice' ) ) ) {
	function ct_ignite_settings_notice() {

		if ( isset( $_GET['ignite_status'] ) ) {

			if ( $_GET['ignite_status'] == 'deleted' ) {
				?>
				<div class="updated">
					<p><?php esc_html_e( 'Customizer settings deleted.', 'ignite' ); ?></p>
				</div>
				<?php
			}
		}
	}
}
add_action( 'admin_notices', 'ct_ignite_settings_notice' );

//----------------------------------------------------------------------------------
// Output the markup for the optional scroll-to-top arrow 
//----------------------------------------------------------------------------------
function ct_ignite_scroll_to_top_arrow() {
	$setting = get_theme_mod('scroll_to_top');
	
	if ( $setting == 'yes' ) {
		echo '<button id="scroll-to-top" class="scroll-to-top"><span class="screen-reader-text">'. __('Scroll to the top', 'ignite') .'</span><i class="fas fa-arrow-up"></i></button>';
	}
}
add_action( 'ct_ignite_body_bottom', 'ct_ignite_scroll_to_top_arrow');

if ( ! function_exists( 'ct_ignite_reset_customizer_options' ) ) {
	function ct_ignite_reset_customizer_options() {

		if ( empty( $_POST['ignite_reset_customizer'] ) || 'ignite_reset_customizer_settings' !== $_POST['ignite_reset_customizer'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['ignite_reset_customizer_nonce'], 'ignite_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mods_array = array(
			'logo_upload',
			'logo_positioning_updown_setting',
			'logo_positioning_leftright_setting',
			'logo_size_width_setting',
			'logo_size_height_setting',
			'ct_ignite_layout_settings',
			'ct_ignite_font_family_settings',
			'ct_ignite_font_weight_settings',
			'ct_ignite_background_color_setting',
			'ct_ignite_post_meta_date_settings',
			'ct_ignite_post_meta_author_settings',
			'ct_ignite_post_meta_categories_settings',
			'ct_ignite_post_meta_tags_settings',
			'ct_ignite_post_meta_comments_settings',
			'ct_ignite_post_meta_further_reading_settings',
			'ct_ignite_comments_setting',
			'ct_ignite_footer_text_setting',
			'ct_ignite_custom_css_setting',
			'scroll_to_top',
			'ct_ignite_show_full_post_setting',
			'ct_ignite_show_breadcrumbs_setting',
			'ct_ignite_author_meta_settings',
			'ct_ignite_parent_menu_icon_settings',
			'ct_ignite_excerpt_length_settings',
			'ct_ignite_read_more_text',
			'last_updated'
		);

		$social_sites = ct_ignite_customizer_social_media_array();

		// add social site settings to mods array
		foreach ( $social_sites as $social_site ) {
			$mods_array[] = $social_site;
		}

		$mods_array = apply_filters( 'ct_ignite_mods_to_remove', $mods_array );

		foreach ( $mods_array as $theme_mod ) {
			remove_theme_mod( $theme_mod );
		}

		$redirect = admin_url( 'themes.php?page=ignite-options' );
		$redirect = add_query_arg( 'ignite_status', 'deleted', $redirect );

		// safely redirect
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'admin_init', 'ct_ignite_reset_customizer_options' );

//----------------------------------------------------------------------------------
// Output the "Last Updated" date on posts
//----------------------------------------------------------------------------------
function ct_ignite_output_last_updated_date() {
	
	global $post;

	if ( get_the_modified_date() != get_the_date() ) {
		$updated_post = get_post_meta( $post->ID, 'ct_ignite_last_updated', true );
		$updated_customizer = get_theme_mod( 'last_updated' );
		if ( 
			( $updated_customizer == 'yes' && ($updated_post != 'no') )
			|| $updated_post == 'yes' 
			) {
				echo '<p class="last-updated">'. esc_html__("Last updated on", "ignite") . ' ' . get_the_modified_date() . ' </p>';
			}
	}
}