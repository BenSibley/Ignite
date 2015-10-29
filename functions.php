<?php

// set the content width
if ( ! isset( $content_width ) ) {
    $content_width = 840;
}

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'ct_ignite_theme_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 1.0
 */
if( ! function_exists( 'ct_ignite_theme_setup' ) ) {
    function ct_ignite_theme_setup() {

        // from WordPress core not theme hybrid
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
        add_theme_support( 'title-tag' );

        /*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
        add_theme_support( 'html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ) );

        // adds support for Jetpack infinite scroll feature
        add_theme_support( 'infinite-scroll', array(
            'container' => 'loop-container',
            'footer'    => 'overflow-container'
        ) );

        // add inc folder files
        foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/*.php' ) as $filename ) {
            include $filename;
        }

        // add widget folder files
        foreach ( glob( trailingslashit( get_template_directory() ) . 'inc/widgets/*.php' ) as $filename ) {
            include $filename;
        }

        // adds theme options page
        require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );

        // load text domain
        load_theme_textdomain( 'ignite', get_template_directory() . '/languages' );
    }
}

/* register primary sidebar */
function ct_ignite_register_sidebar(){
    register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'ignite' ),
        'id'           => 'primary',
        'description'  => __( 'The main sidebar', 'ignite' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>'
    ) );
}
add_action('widgets_init','ct_ignite_register_sidebar');

// register primary menu
function ct_ignite_register_menu() {
    register_nav_menu('primary', __('Primary', 'ignite'));
}
add_action('init', 'ct_ignite_register_menu');

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
if( ! function_exists( 'ct_ignite_customize_comments' ) ) {
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
                <span> <?php _e( 'said:', 'ignite' ); ?></span>
            </div>
            <div class="comment-content">
                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em><?php _e( 'Your comment is awaiting moderation.', 'ignite' ) ?></em>
                    <br/>
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <div class="comment-meta">
                <div class="comment-date"><?php comment_date(); ?></div>
                <?php edit_comment_link( __('Edit', 'ignite') ); ?>
                <?php comment_reply_link( array_merge( $args, array(
                    'reply_text' => __( 'Reply', 'ignite' ),
                    'depth'      => $depth,
                    'max_depth'  => $args['max_depth']
                ) ) ); ?>
            </div>
        </article>
    <?php
    }
}

/* added HTML5 placeholders for each default field */
if( ! function_exists( 'ct_ignite_update_fields' ) ) {
    function ct_ignite_update_fields( $fields ) {

        // get commenter object
        $commenter = wp_get_current_commenter();

        // are name and email required?
        $req = get_option( 'require_name_email' );

        // required or optional label to be added
        if ( $req == 1 ) {
            $label = '*';
        } else {
            $label = ' (optional)';
        }

        // adds aria required tag if required
        $aria_req = ( $req ? " aria-required='true'" : '' );

        $fields['author'] =
            '<p class="comment-form-author">
                <label for="author" class="screen-reader-text">' . __( 'Your Name', 'ignite' ) . '</label>
                <input placeholder="' . __( 'Your Name', 'ignite' ) . $label . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
            '" size="30"' . $aria_req . ' />
            </p>';

        $fields['email'] =
            '<p class="comment-form-email">
                <label for="email" class="screen-reader-text">' . __( 'Your Email', 'ignite' ) . '</label>
                <input placeholder="' . __( 'Your Email', 'ignite' ) . $label . '" id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) .
            '" size="30"' . $aria_req . ' />
            </p>';

        $fields['url'] =
            '<p class="comment-form-url">
                <label for="url" class="screen-reader-text">' . __( 'Your Website URL', 'ignite' ) . '</label>
                <input placeholder="' . __( 'Your URL', 'ignite' ) . ' (optional)" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
            '" size="30" />
                </p>';

        return $fields;
    }
}
add_filter('comment_form_default_fields','ct_ignite_update_fields');

if( ! function_exists( 'ct_ignite_update_comment_field' ) ) {
    function ct_ignite_update_comment_field( $comment_field ) {

        $comment_field =
            '<p class="comment-form-comment">
                <label for="comment" class="screen-reader-text">' . __( 'Your Comment', 'ignite' ) . '</label>
                <textarea required placeholder="' . __( 'Enter Your Comment', 'ignite' ) . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
            </p>';

        return $comment_field;
    }
}
add_filter('comment_form_field_comment','ct_ignite_update_comment_field');

// remove allowed tags text after comment form
if( ! function_exists( 'ct_ignite_remove_comments_notes_after' ) ) {
    function ct_ignite_remove_comments_notes_after( $defaults ) {

        $defaults['comment_notes_after'] = '';

        return $defaults;
    }
}

add_action('comment_form_defaults', 'ct_ignite_remove_comments_notes_after');

// excerpt handling
if( ! function_exists( 'ct_ignite_excerpt' ) ) {
    function ct_ignite_excerpt() {

        // make post variable available
        global $post;

        // get the show full post setting
        $setting = get_theme_mod( 'ct_ignite_show_full_post_setting' );

        // check for the more tag
        $ismore = strpos( $post->post_content, '<!--more-->' );

        // if show full post is on and not on a search results page
        if ( ( $setting == 'yes' ) && ! is_search() ) {

			// use the read more link if present
	        if ( $ismore ) {
		        the_content( __( 'Read More', 'ignite' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
	        } else {
		        the_content();
	        }
        } // use the read more link if present
        elseif ( $ismore ) {
            the_content( __( 'Read More', 'ignite' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span>" );
        } // otherwise the excerpt is automatic, so output it
        else {
            the_excerpt();
        }
    }
}

// filter the link on excerpts
if( ! function_exists( 'ct_ignite_excerpt_read_more_link' ) ) {
    function ct_ignite_excerpt_read_more_link( $output ) {
        return $output . "<p><a class='more-link' href='" . get_permalink() . "'>" . __( 'Read More', 'ignite' ) . " <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
    }
}
add_filter('the_excerpt', 'ct_ignite_excerpt_read_more_link');

// switch [...] to ellipsis on automatic excerpt
if( ! function_exists( 'ct_ignite_new_excerpt_more' ) ) {
    function ct_ignite_new_excerpt_more( $more ) {

        // get user set excerpt length
        $new_excerpt_length = get_theme_mod('ct_ignite_excerpt_length_settings');

        // if set to 0, return nothing
        if ( $new_excerpt_length === 0 ) {
            return '';
        }
        // else add the ellipsis
        else {
            return '&#8230;';
        }
    }
}
add_filter('excerpt_more', 'ct_ignite_new_excerpt_more');

// change the length of the excerpts
function ct_ignite_custom_excerpt_length( $length ) {

    $new_excerpt_length = get_theme_mod('ct_ignite_excerpt_length_settings');

    // if there is a new length set and it's not 15, change it
    if(!empty($new_excerpt_length) && $new_excerpt_length != 30){
        return $new_excerpt_length;
    }
    // return 0 if user explicitly sets it to 0
    elseif ( $new_excerpt_length === 0 ) {
        return 0;
    }
    else {
        return 30;
    }
}
add_filter( 'excerpt_length', 'ct_ignite_custom_excerpt_length', 999 );

// turns of the automatic scrolling to the read more link
if( ! function_exists( 'ct_ignite_remove_more_link_scroll' ) ) {
    function ct_ignite_remove_more_link_scroll( $link ) {
        $link = preg_replace( '|#more-[0-9]+|', '', $link );

        return $link;
    }
}

add_filter( 'the_content_more_link', 'ct_ignite_remove_more_link_scroll' );

// for displaying featured images including mobile versions and default versions
if( ! function_exists( 'ct_ignite_featured_image' ) ) {
    function ct_ignite_featured_image() {

        // get post object
        global $post;

        // instantiate featured image var
        $featured_image = '';

        if ( has_post_thumbnail( $post->ID ) ) {

            if ( is_singular() ) {
                $featured_image = '<div class="featured-image">' . get_the_post_thumbnail() . '</div>';
            } else {
                $featured_image = '<div class="featured-image"><a href="' . get_permalink() . '">' . get_the_title() . get_the_post_thumbnail() . '</a></div>';
            }
        }
        if( $featured_image ) {
            echo $featured_image;
        }
    }
}

// functions to allow styling of post count in widgets
add_filter('get_archives_link', 'ct_ignite_archive_count_add_span');
function ct_ignite_archive_count_add_span($links) {
    $links = str_replace('</a>&nbsp;(', '</a><span>', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}
add_filter('wp_list_categories', 'ct_ignite_category_count_add_span');
function ct_ignite_category_count_add_span($links) {
    $links = str_replace('</a> (', '</a> <span>', $links);
    $links = str_replace(')', '</span>', $links);
    return $links;
}

// calls pages for menu if menu not set
function ct_ignite_wp_page_menu() {
    wp_page_menu(array("menu_class" => "menu-unset"));
}

function ct_ignite_body_class( $classes ) {

    global $post;

    /* get layout chosen by user */
    $layout = get_theme_mod('ct_ignite_layout_settings');

    /* if sidebar left layout */
    if($layout == 'left') {
        $classes[] = 'sidebar-left';
    }

	if( get_theme_mod('ct_ignite_parent_menu_icon_settings') == 'show' ) {
		$classes[] = 'parent-icons';
	}

    // add all historic singular classes
    if ( is_singular() ) {
        $classes[] = 'singular';
        if ( is_singular('page') ) {
            $classes[] = 'singular-page';
            $classes[] = 'singular-page-' . $post->ID;
        } elseif ( is_singular('post') ) {
            $classes[] = 'singular-post';
            $classes[] = 'singular-post-' . $post->ID;
        } elseif ( is_singular('attachment') ) {
            $classes[] = 'singular-attachment';
            $classes[] = 'singular-attachment-' . $post->ID;
        }
    }

    return $classes;
}
add_filter( 'body_class', 'ct_ignite_body_class' );

function ct_ignite_post_class_update($classes){

    // add 'excerpt' class to posts when on archive pages
    if ( ! is_singular() ) {
        foreach ( $classes as $key => $class ) {
            $classes[] = 'excerpt';
        }
    } else {
        $classes[] = 'entry';
    }

    return $classes;
}
add_filter( 'post_class', 'ct_ignite_post_class_update' );

/* outputs the inline css to position the logo */
function ct_ignite_logo_positioning_css(){

    $updown =  get_theme_mod( 'logo_positioning_updown_setting');
    $leftright =  get_theme_mod( 'logo_positioning_leftright_setting');

    if($updown || $leftright){

        $css = "
            #site-header .logo {
                position: relative;
                bottom: " . $updown . "px;
                left: " . $leftright . "px;
                right: auto;
                top: auto;
        }";
        wp_add_inline_style('style', $css);
    }
}
add_action('wp_enqueue_scripts','ct_ignite_logo_positioning_css', 20);

/* outputs the inline css to position the logo */
function ct_ignite_logo_size_css(){

    $width =  get_theme_mod( 'logo_size_width_setting');
    $height =  get_theme_mod( 'logo_size_height_setting');

    if($width || $height){

        $max_width = 156 + $width;
        $max_height = 59 + $height;

        $css = "
            #logo {
                max-width: " . $max_width . "px;
                max-height: " . $max_height . "px;
        }";
        wp_add_inline_style('style', $css);
    }
}
add_action('wp_enqueue_scripts','ct_ignite_logo_size_css', 20);

function ct_ignite_custom_css_output(){

    $custom_css = get_theme_mod('ct_ignite_custom_css_setting');

    /* output custom css */
    if($custom_css) {
        $custom_css = wp_filter_nohtml_kses( $custom_css );
        wp_add_inline_style('style', $custom_css);
    }
}
add_action('wp_enqueue_scripts','ct_ignite_custom_css_output', 20);

// add class if no avatars are being shown in the comments
function ct_ignite_show_avatars_check($classes){

    if(get_option('show_avatars')){
        $classes[] = 'avatars';
    } else {
        $classes[] = 'no-avatars';
    }
    return $classes;
}
add_action('comment_class', 'ct_ignite_show_avatars_check');

// implement fonts based on customizer selection
function ct_ignite_change_font(){

    $font = get_theme_mod('ct_ignite_font_family_settings');
    $font_weight = get_theme_mod('ct_ignite_font_weight_settings');
    $font_style = 'normal';

    // if it's not the default or empty
    if ( $font != 'Lusitana' && !empty( $font ) ) {

        // if weight is italic, set it to 400
        if( $font_weight == 'italic' ){
            $font_weight = 400;
            $font_style = 'italic';
        }
        // if weight is a different weight and italic, remove "italic"
        elseif ( strpos( $font_weight, 'italic' ) !== false ) {
            $font_weight = str_replace($font_weight, 'italic', '');
            $font_style = 'italic';
        }
        // if weight is regular, change to 400
        elseif ( $font_weight == 'regular' ) {
            $font_weight = 400;
        }

        // css to be output
        $css = "
            body, h1, h2, h3, h4, h5, h6, input:not([type='checkbox']):not([type='radio']):not([type='submit']):not([type='file']), input[type='submit'], textarea {
                font-family: $font;
                font-style: $font_style;
                font-weight: $font_weight;
            }
        ";
        // output the css
        wp_add_inline_style('style', $css);

        // deregister the default call to Google Fonts
        wp_deregister_style('ct-ignite-google-fonts');

        $fonts_url = ct_ignite_format_font_request( $font );

        // register the new font
        wp_register_style( 'ct-ignite-google-fonts', $fonts_url );

        // enqueue the new GF stylesheet on the front-end
        wp_enqueue_style('ct-ignite-google-fonts');
    }
}
add_action('wp_enqueue_scripts', 'ct_ignite_change_font', 20);

// used to format GF request (ajax and non-ajax)
function ct_ignite_format_font_request( $font ) {

    // by default not an ajax call
    $ajax = false;

    // if is ajax request
    if ( isset( $_POST['font'] ) ) {
        $font = $_POST['font'];
        $ajax = true;
    }

    // get array of fonts and their weights
    $weights = ct_ignite_get_available_font_weights( $font );

    $fonts_url = '';

    if ( is_array( $weights ) && !empty( $weights ) ) {

        // convert to comma-delimited list
        $weights = implode( ',', $weights );

        // turn 'regular' into '400'
        $weights = str_replace( 'regular', '400', $weights );

        // replace any spaces with '+'
        $font = str_replace( ' ', '+', $font );

        // format the font/weight for the request
        $font_request = $font . ':' . $weights;

        // prepare query args
        $font_args = array(
            'family' => $font_request,
            'subset' => urlencode( 'latin,latin-ext' )
        );

        // build request url
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
add_action( 'wp_ajax_format_font_request', 'ct_ignite_format_font_request' );
add_action( 'wp_ajax_nopriv_format_font_request', 'ct_ignite_format_font_request' );

function ct_ignite_background_css(){

    $background_color = get_theme_mod('ct_ignite_background_color_setting');

    if($background_color != '#eeede8'){

        $background_color_css = "
            .overflow-container {
                background: $background_color;
            }
            .main, .sidebar-primary-container, .breadcrumb-trail {
                background: none;
            }
        ";
        wp_add_inline_style('style', $background_color_css);
    }

}
add_action('wp_enqueue_scripts','ct_ignite_background_css', 20);

if ( ! function_exists( '_wp_render_title_tag' ) ) :
    function ct_ignite_add_title_tag() {
    ?>
        <title><?php wp_title( ' | ' ); ?></title>
    <?php
    }
    add_action( 'wp_head', 'ct_ignite_add_title_tag' );
endif;


// create social media site array
if ( !function_exists( 'ct_ignite_customizer_social_media_array' ) ) {
    function ct_ignite_customizer_social_media_array() {

        // store social site names in array
        $social_sites = array(
            'twitter',
            'facebook',
            'google-plus',
            'flickr',
            'pinterest',
            'youtube',
            'vimeo',
            'tumblr',
            'dribbble',
            'rss',
            'linkedin',
            'instagram',
            'reddit',
            'soundcloud',
            'spotify',
            'vine',
            'yahoo',
            'behance',
            'codepen',
            'delicious',
            'stumbleupon',
            'deviantart',
            '500px',
            'foursquare',
            'slack',
            'slideshare',
            'qq',
            'whatsapp',
            'skype',
            'wechat',
            'xing',
            'digg',
            'github',
            'hacker-news',
            'steam',
            'vk',
            'academia',
            'weibo',
            'tencent-weibo',
            'paypal',
            'email'
        );

        return apply_filters( 'ct_ignite_customizer_social_media_array_filter', $social_sites );
    }
}

function ct_ignite_loop_pagination(){

    // don't output if Jetpack infinite scroll is being used
    if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'infinite-scroll' ) )
        return;

    global $wp_query;

    // If there's not more than one page, return nothing.
    if ( 1 >= $wp_query->max_num_pages ) {
        return;
    }

    /* Set up some default arguments for the paginate_links() function. */
    $defaults = array(
        'base'         => add_query_arg( 'paged', '%#%' ),
        'format'       => '',
        'mid_size'     => 1
    );

    $loop_pagination = '<div class="loop-pagination-container"><nav class="pagination loop-pagination">';
    $loop_pagination .= paginate_links( $defaults );
    $loop_pagination .= '</nav></div>';

    return $loop_pagination;
}

// Adds useful meta tags
function ct_ignite_add_meta_elements() {

    $meta_elements = '';

    /* Charset */
    $meta_elements .= sprintf( '<meta charset="%s" />' . "\n", get_bloginfo( 'charset' ) );

    /* Viewport */
    $meta_elements .= '<meta name="viewport" content="width=device-width, initial-scale=1" />' . "\n";

    /* Theme name and current version */
    $theme    = wp_get_theme( get_template() );
    $template = sprintf( '<meta name="template" content="%s %s" />' . "\n", esc_attr( $theme->get( 'Name' ) ), esc_attr( $theme->get( 'Version' ) ) );
    $meta_elements .= $template;

    echo $meta_elements;
}
add_action( 'wp_head', 'ct_ignite_add_meta_elements', 1 );

/* Move the WordPress generator to a better priority. */
remove_action( 'wp_head', 'wp_generator' );
add_action( 'wp_head', 'wp_generator', 1 );