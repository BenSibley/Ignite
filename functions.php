<?php

/* Load the core theme framework. */
require_once( trailingslashit( get_template_directory() ) . 'library/hybrid.php' );
new Hybrid();

/* Do theme setup on the 'after_setup_theme' hook. */
add_action( 'after_setup_theme', 'ct_ignite_theme_setup', 10 );

/**
 * Theme setup function.  This function adds support for theme features and defines the default theme
 * actions and filters.
 *
 * @since 1.0
 */

function ct_ignite_theme_setup() {
	
    /* Get action/filter hook prefix. */
	$prefix = hybrid_get_prefix();
    
	/* Theme-supported features go here. */
    add_theme_support( 'hybrid-core-template-hierarchy' );
    add_theme_support( 'loop-pagination' );
    add_theme_support( 'cleaner-gallery' );
    add_theme_support( 'breadcrumb-trail' );

    // from WordPress core not theme hybrid
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'post-thumbnails' );

    // add inc folder files
    foreach (glob(trailingslashit( get_template_directory() ) . 'inc/*.php') as $filename)
    {
        include $filename;
    }

	// add widget folder files
	foreach (glob(trailingslashit( get_template_directory() ) . 'inc/widgets/*.php') as $filename)
	{
		include $filename;
	}
    // adds theme options page
    require_once( trailingslashit( get_template_directory() ) . 'theme-options.php' );

    // load text domain
    load_theme_textdomain('ignite', get_template_directory() . '/languages');
}

/* register primary sidebar */
function ct_ignite_register_sidebar(){
    hybrid_register_sidebar( array(
        'name'         => __( 'Primary Sidebar', 'ignite' ),
        'id'           => 'primary',
        'description'  => __( 'The main sidebar', 'ignite' ),
    ) );
}
add_action('widgets_init','ct_ignite_register_sidebar');

// register primary menu
function ct_ignite_register_menu() {
    register_nav_menu('primary', __('Primary', 'ignite'));
}
add_action('init', 'ct_ignite_register_menu');

/* added to customize the comments. Same as default except -> added use of gravatar images for comment authors */
function ct_ignite_customize_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    global $post;
 
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class="comment">
            <div class="comment-author">
                <?php
                // if is post author
                if( $comment->user_id === $post->post_author ) {
                    ct_ignite_profile_image_output();
                } else {
                    echo get_avatar( get_comment_author_email(), 48 );
                }
                ?>
                <span class="author-name"><?php comment_author_link(); ?></span>
                <span> <?php _e('said:', 'ignite'); ?></span>
            </div>
            <div class="comment-content">
                <?php if ($comment->comment_approved == '0') : ?>
                    <em><?php _e('Your comment is awaiting moderation.', 'ignite') ?></em>
                    <br />
                <?php endif; ?>
                <?php comment_text(); ?>
            </div>
            <div class="comment-meta">
                <div class="comment-date"><?php comment_date(); ?></div>
                <?php edit_comment_link( 'edit' ); ?>
                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'ignite' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            </div>

        </article>
    </li>
    <?php
}

/* added HTML5 placeholders for each default field */
function ct_ignite_update_fields($fields) {

	// get commenter object
    $commenter = wp_get_current_commenter();

	// are name and email required?
    $req = get_option( 'require_name_email' );

	// required or optional label to be added
	if( $req == 1 ) {
		$label = '*';
	} else {
		$label = ' (optional)';
	}

	// adds aria required tag if required
    $aria_req = ( $req ? " aria-required='true'" : '' );

    $fields['author'] =
        '<p class="comment-form-author">
            <label class="screen-reader-text">' . __('Your Name', 'ignite') . '</label>
			<input placeholder="' . __('Your Name', 'ignite') . $label . '" id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
        '" size="30"' . $aria_req . ' />
    	</p>';

    $fields['email'] =
        '<p class="comment-form-email">
            <label class="screen-reader-text">' . __('Your Email', 'ignite') . '</label>
    		<input placeholder="' . __('Your Email', 'ignite') . $label . '" id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
        '" size="30"' . $aria_req . ' />
    	</p>';

    $fields['url'] =
        '<p class="comment-form-url">
            <label class="screen-reader-text">' . __('Your Website URL', 'ignite') . '</label>
			<input placeholder="' . __('Your URL', 'ignite') . ' (optional)" id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) .
        '" size="30" />
            </p>';

    return $fields;
}
add_filter('comment_form_default_fields','ct_ignite_update_fields');

function ct_ignite_update_comment_field($comment_field) {
	
	$comment_field = 
		'<p class="comment-form-comment">
            <label class="screen-reader-text">' . __('Your Comment', 'ignite') . '</label>
			<textarea required placeholder="' . __('Enter Your Comment', 'ignite') . '&#8230;" id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
		</p>';
	
	return $comment_field;
}
add_filter('comment_form_field_comment','ct_ignite_update_comment_field');


// remove allowed tags text after comment form
function ct_ignite_remove_comments_notes_after($defaults){

    $defaults['comment_notes_after']='';
    return $defaults;
}

add_action('comment_form_defaults', 'ct_ignite_remove_comments_notes_after');

// excerpt handling
function ct_ignite_excerpt() {

    // make post variable available
	global $post;

    // make 'read more' setting available
    global $more;

    // get the show full post setting
    $setting = get_theme_mod('ct_ignite_show_full_post_setting');

    // check for the more tag
    $ismore = strpos( $post->post_content, '<!--more-->');

    // if show full post is on, show full post unless on search page
    if(($setting == 'yes') && !is_search()){

        // set read more value for all posts to 'off'
        $more = -1;

        // output the full content
        the_content();
    }
    // use the read more link if present
    elseif($ismore) {
        the_content( __('Read More', 'ignite') . " <span class='screen-reader-text'>" . get_the_title() . "</span>");
    }
    // otherwise the excerpt is automatic, so output it
    else {
        the_excerpt();
    }
}

// filter the link on excerpts
function ct_ignite_excerpt_read_more_link($output) {
    return $output . "<p><a class='more-link' href='". get_permalink() ."'>" . __('Read More', 'ignite') . " <span class='screen-reader-text'>" . get_the_title() . "</span></a></p>";
}
add_filter('the_excerpt', 'ct_ignite_excerpt_read_more_link');


// switch [...] to ellipsis on automatic excerpt
function ct_ignite_new_excerpt_more( $more ) {
	return '&#8230;';
}
add_filter('excerpt_more', 'ct_ignite_new_excerpt_more');

// change the length of the excerpts
function ct_ignite_custom_excerpt_length( $length ) {

    $new_excerpt_length = get_theme_mod('ct_ignite_excerpt_length_settings');

    // if there is a new length set and it's not 15, change it
    if(!empty($new_excerpt_length) && $new_excerpt_length != 30){
        return $new_excerpt_length;
    } else {
        return 30;
    }
}
add_filter( 'excerpt_length', 'ct_ignite_custom_excerpt_length', 999 );

// turns of the automatic scrolling to the read more link 
function ct_ignite_remove_more_link_scroll( $link ) {
	$link = preg_replace( '|#more-[0-9]+|', '', $link );
	return $link;
}

add_filter( 'the_content_more_link', 'ct_ignite_remove_more_link_scroll' );

// Adds navigation through pages in the loop
function ct_ignite_post_navigation() { ?>
    <div class="loop-pagination-container">
        <?php if ( current_theme_supports( 'loop-pagination' ) ) loop_pagination(); ?>
    </div><?php
}

// for displaying featured images including mobile versions and default versions
function ct_ignite_featured_image() {
	
	global $post;
	$has_image = false;

    if (has_post_thumbnail( $post->ID ) ) {
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
		$image = $image[0];
		$has_image = true;
	}  
	if ($has_image == true) {

        if(is_singular()){
            echo "<div class='featured-image' style=\"background-image: url('".$image."')\"></div>";
        } else {
            echo "
                <div class='featured-image' style=\"background-image: url('".$image."')\">
                    <a href='" . get_the_permalink() ."'>" . get_the_title() . "</a>
                </div>
                ";
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

// adds title to homepage
add_filter( 'wp_title', 'ct_ignite_add_homepage_title' );
function ct_ignite_add_homepage_title( $title )
{
    if( empty( $title ) && ( is_home() || is_front_page() ) ) {
        return get_bloginfo( 'title' ) . ' | ' . get_bloginfo( 'description' );
    }
    return $title;
}

// calls pages for menu if menu not set
function ct_ignite_wp_page_menu() {
    wp_page_menu(array("menu_class" => "menu-unset"));
}

function ct_ignite_body_class( $classes ) {

    if ( ! is_front_page() ) {
        $classes[] = 'not-front';
    }

    /* get layout chosen by user */
    $layout = get_theme_mod('ct_ignite_layout_settings');

    /* if sidebar left layout */
    if($layout == 'left') {
        $classes[] = 'sidebar-left';
    }

	if( get_theme_mod('ct_ignite_parent_menu_icon_settings') == 'show' ) {
		$classes[] = 'parent-icons';
	}

    return $classes;
}
add_filter( 'body_class', 'ct_ignite_body_class' );

function ct_ignite_post_class_update($classes){

    $remove = array();
    $remove[] = 'entry';

    if ( ! is_singular() ) {
        foreach ( $classes as $key => $class ) {

            if ( in_array( $class, $remove ) ){
                unset( $classes[ $key ] );
                $classes[] = 'excerpt';
            }
        }
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
add_action('wp_enqueue_scripts','ct_ignite_logo_positioning_css');

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
add_action('wp_enqueue_scripts','ct_ignite_logo_size_css');

function ct_ignite_custom_css_output(){

    $custom_css = get_theme_mod('ct_ignite_custom_css_setting');

    /* output custom css */
    if($custom_css) {
        wp_add_inline_style('style', $custom_css);
    }
}
add_action('wp_enqueue_scripts','ct_ignite_custom_css_output');

// fix for bug with Disqus saying comments are closed
if ( function_exists( 'dsq_options' ) ) {
    remove_filter( 'comments_template', 'dsq_comments_template' );
    add_filter( 'comments_template', 'dsq_comments_template', 99 ); // You can use any priority higher than '10'
}

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

// retrieves the attachment ID from the file URL
function ct_ignite_get_image_id($url) {

    // Split the $url into two parts with the wp-content directory as the separator
    $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

    // Get the host of the current site and the host of the $url, ignoring www
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

    // Return nothing if there aren't any $url parts or if the current host and $url host do not match
    if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }

    // Now we're going to quickly search the DB for any attachment GUID with a partial path match
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;

    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

    // Returns null if no attachment is found
    return $attachment[0];
}

// implement fonts based on customizer selection
function ct_ignite_change_font(){

    // get the current font
    $font = get_theme_mod('ct_ignite_font_family_settings');

    // if it's the default do nothing, otherwise...
    if($font != "Lusitana" && !empty($font)){

        // get the current font weight
        $font_weight = get_theme_mod('ct_ignite_font_weight_settings');

        // check if font style is italic (needs double '==' b/c position may be '0')
        if(strpos($font_weight, 'italic') !== false){

            // if weight is simply italic, set weight to 400
            if($font_weight == 'italic'){
                $font_weight_css = 400;
            }
            // otherwise, remove 'italic' from weight and use integer (ex. 600italic -> 600)
            else {
                $font_weight_css = str_replace($font_weight, 'italic', '');
            }
            // save style as 'italic'
            $font_style = 'italic';
        }
        // if not italic, just use weight integer and set style as 'normal'
        else {
            $font_weight_css = $font_weight;
            $font_style = 'normal';
        }

        // css to be output
        $css = "
            body, h1, h2, h3, h4, h5, h6, input:not([type='checkbox']):not([type='radio']):not([type='submit']):not([type='file']), input[type='submit'], textarea {
                font-family: $font;
                font-weight: $font_weight_css;
                font-style: $font_style;
            }
        ";
        // output the css
        wp_add_inline_style('style', $css);

        // deregister the default call to Google Fonts
        wp_deregister_style('ct-ignite-google-fonts');

        // register the new font
        wp_register_style( 'ct-ignite-google-fonts', '//fonts.googleapis.com/css?family=' . $font . ':' . $font_weight . '');

        // enqueue the new GF stylesheet on the front-end
        if( !is_admin()){
            wp_enqueue_style('ct-ignite-google-fonts');
        }
    }
}
add_action('wp_enqueue_scripts', 'ct_ignite_change_font');

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
add_action('wp_enqueue_scripts','ct_ignite_background_css');

// outputs the user's uploaded profile picture with Gravatar fallback
function ct_ignite_profile_image_output(){

    // if post author has profile image set
    if(get_the_author_meta('user_profile_image')) {

        echo "<div class='author-profile-image'>";

        // get the id based on the image's URL
        $image_id = ct_ignite_get_image_id(get_the_author_meta('user_profile_image'));

        // retrieve the thumbnail size of profile image
        $image_thumb = wp_get_attachment_image($image_id, 'thumbnail');

        // display the image
        echo $image_thumb;

        echo "</div>";
    } else {
        echo get_avatar( get_the_author_meta( 'ID' ), 72 );
    }
}

function ct_ignite_toolbar_link( $wp_admin_bar ) {

	// Create parent nod
	$args = array(
		'id'    => 'ct_ignite_dashboard',
		'title' => __('Ignite Dashboard', 'ignite'),
		'href'  => admin_url() . 'themes.php?page=ignite-options',
		'meta'  => array( 'class' => 'ignite-dashboard' )
	);
	$wp_admin_bar->add_node( $args );

	// Customize
	$args = array(
		'id'    => 'ct_ignite_dashboard_customize',
		'title' => __('Customize', 'ignite'),
		'parent' => 'ct_ignite_dashboard',
		'href'  => admin_url() . 'customize.php',
		'meta'  => array( 'class' => 'ignite-dashboard-customize' )
	);
	$wp_admin_bar->add_node( $args );

	// Support
	$args = array(
		'id'    => 'ct_ignite_dashboard_support',
		'title' => __('Support', 'ignite'),
		'parent' => 'ct_ignite_dashboard',
		'href'  => 'http://www.competethemes.com/documentation/ignite-support-center/',
		'meta'  => array( 'class' => 'ignite-dashboard-support', 'target' => '_blank' )
	);
	$wp_admin_bar->add_node( $args );

	// Upgrade
	$args = array(
		'id'    => 'ct_ignite_dashboard_upgrade',
		'title' => __('Ignite Plus', 'ignite'),
		'parent' => 'ct_ignite_dashboard',
		'href'  => 'https://www.competethemes.com/ignite-plus/',
		'meta'  => array( 'class' => 'ignite-dashboard-support', 'target' => '_blank' )
	);
	$wp_admin_bar->add_node( $args );

}
add_action( 'admin_bar_menu', 'ct_ignite_toolbar_link', 999 );

function ct_tracks_compete_themes_logo_svg() {

	$svg = '<svg width="130px" height="131px" viewBox="0 0 130 131" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
			    <title>logo</title>
			    <desc>logo emblem</desc>
			    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
			        <g id="final">
			            <g id="logo-pattern-4" transform="translate(0.000000, 0.059226)">
			                <rect id="Rectangle-124" fill="#3A9CAB" transform="translate(18.298256, 65.077014) rotate(-45.000000) translate(-18.298256, -65.077014) " x="5.39281551" y="52.2550923" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-123" fill="#3A9CAB" transform="translate(64.932418, 18.221023) rotate(-45.000000) translate(-64.932418, -18.221023) " x="52.0269773" y="5.39910088" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-135" fill="#3A9CAB" transform="translate(88.014847, 41.699442) rotate(-45.000000) translate(-88.014847, -41.699442) " x="75.2576899" y="29.0248437" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-141" fill="#3A9CAB" transform="translate(64.715755, 111.834692) rotate(-45.000000) translate(-64.715755, -111.834692) " x="51.9585979" y="99.1600937" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-139" fill="#3A9CAB" transform="translate(87.929878, 88.483689) rotate(-45.000000) translate(-87.929878, -88.483689) " x="75.1727209" y="75.8090915" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-138" fill="#3A9CAB" transform="translate(110.877822, 65.024312) rotate(-45.000000) translate(-110.877822, -65.024312) " x="98.1206645" y="52.3497141" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-140" fill="#3A9CAB" transform="translate(41.027017, 88.548659) rotate(-45.000000) translate(-41.027017, -88.548659) " x="28.0976324" y="75.7661985" width="25.8108813" height="25.6438435"></rect>
			                <rect id="Rectangle-136" fill="#3A9CAB" transform="translate(41.038668, 41.764751) rotate(-45.000000) translate(-41.038668, -41.764751) " x="28.1332277" y="28.942829" width="25.8108813" height="25.6438435"></rect>
			                <rect id="middle" fill="#4BCBDE" transform="translate(64.793850, 65.087699) rotate(-45.000000) translate(-64.793850, -65.087699) " x="56.1546698" y="56.5044288" width="17.4791955" height="17.3660771"></rect>
			            </g>
			        </g>
			    </g>
			</svg>';

	return $svg;
}