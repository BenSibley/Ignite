<?php

// Breadcrumbs
function ct_ignite_breadcrumbs() {

	// Do not display on the homepage
	if ( is_front_page() ) {
		return;
	}

	// Settings
	$separator           = '&gt;';
	$breadcrumbs_id      = 'breadcrumbs';
	$breadcrumbs_classes = 'breadcrumb-trail breadcrumbs';
	$home_title          = 'Home';

	// Get the post information
	global $post;

	// Open the breadcrumbs
	$html = '<div id="' . $breadcrumbs_id . '" class="' . $breadcrumbs_classes . '">';

	// Add Homepage link & separator (always present)
	$html .= '<span class="item-home"><a class="bread-link bread-home" href="' . esc_url( get_home_url() ) . '" title="' . esc_attr( $home_title ) . '">' . esc_attr( $home_title ) . '</a></span>';
	$html .= '<span class="separator separator-home"> ' . esc_attr( $separator ) . ' </span>';

	// Post
	if ( is_singular( 'post' ) ) {

		// Get post category info
		$category = get_the_category();

		// Get category values
		$category_values = array_values( $category );

		// Get last category post is in
		$last_category = end( $category_values );

		// Get parent categories
		$cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );

		// Convert into array
		$cat_parents = explode( ',', $cat_parents );

		// Loop through parent categories and add to breadcrumb trail
		foreach ( $cat_parents as $parent ) {
			$html .= '<span class="item-cat">' . wp_kses( $parent, wp_kses_allowed_html( 'a' ) ) . '</span>';
			$html .= '<span class="separator"> ' . esc_attr( $separator ) . ' </span>';
		}

		// add name of Post
		$html .= '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></span>';
	} // Page
	elseif ( is_singular( 'page' ) ) {

		// if page has a parent page
		if ( $post->post_parent ) {

			// Get all parents
			$parents = get_post_ancestors( $post->ID );

			// Sort parents into the right order
			$parents = array_reverse( $parents );

			// Add each parent to markup
			foreach ( $parents as $parent ) {
				$html .= '<span class="item-parent item-parent-' . esc_attr( $parent ) . '"><a class="bread-parent bread-parent-' . esc_attr( $parent ) . '" href="' . get_permalink( $parent ) . '" title="' . get_the_title( $parent ) . '">' . get_the_title( $parent ) . '</a></span>';
				$html .= '<span class="separator separator-' . esc_attr( $parent ) . '"> ' . esc_attr( $separator ) . ' </span>';
			}
		}
		// Current page
		$html .= '<span class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></span>';
	} // Attachment
	elseif ( is_singular( 'attachment' ) ) {

		// Get the parent post ID
		$parent_id = $post->post_parent;

		// Get the parent post title
		$parent_title = get_the_title( $parent_id );

		// Get the parent post permalink
		$parent_permalink = get_permalink( $parent_id );

		// Add markup
		$html .= '<span class="item-parent"><a class="bread-parent" href="' . esc_url( $parent_permalink ) . '" title="' . esc_attr( $parent_title ) . '">' . esc_attr( $parent_title ) . '</a></span>';
		$html .= '<span class="separator"> ' . esc_attr( $separator ) . ' </span>';

		// Add name of attachment
		$html .= '<span class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></span>';
	} // Custom Post Types
	elseif ( is_singular() ) {

		// Get the post type
		$post_type = get_post_type();

		// Get the post object
		$post_type_object = get_post_type_object( $post_type );

		// Get the post type archive
		$post_type_archive = get_post_type_archive_link( $post_type );

		// Add taxonomy link and separator
		$html .= '<span class="item-cat item-custom-post-type-' . esc_attr( $post_type ) . '"><a class="bread-cat bread-custom-post-type-' . esc_attr( $post_type ) . '" href="' . esc_url( $post_type_archive ) . '" title="' . esc_attr( $post_type_object->labels->name ) . '">' . esc_attr( $post_type_object->labels->name ) . '</a></span>';
		$html .= '<span class="separator"> ' . esc_attr( $separator ) . ' </span>';

		// Add name of Post
		$html .= '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . $post->post_title . '">' . $post->post_title . '</span></span>';
	} // Category
	elseif ( is_category() ) {

		// Add category markup
		$html .= '<span class="item-current item-cat"><span class="bread-current bread-cat" title="' . $post->ID . '">' . single_cat_title( '', false ) . '</span></span>';
	} // Tag
	elseif ( is_tag() ) {

		// Add tag markup
		$html .= '<span class="item-current item-tag"><span class="bread-current bread-tag">' . single_tag_title( '', false ) . '</span></span>';
	} // Author
	elseif ( is_author() ) {

		// Add author markup
		$html .= '<span class="item-current item-author"><span class="bread-current bread-author">' . get_queried_object()->display_name . '</span></span>';
	} // Day
	elseif ( is_day() ) {

		// Add day markup
		$html .= '<span class="item-current item-day"><span class="bread-current bread-day">' . get_the_date() . '</span></span>';
	} // Month
	elseif ( is_month() ) {

		// Add month markup
		$html .= '<span class="item-current item-month"><span class="bread-current bread-month">' . get_the_date( 'F Y' ) . '</span></span>';
	} // Year
	elseif ( is_year() ) {

		// Add year markup
		$html .= '<span class="item-current item-year"><span class="bread-current bread-year">' . get_the_date( 'Y' ) . '</span></span>';
	} // Custom Taxonomy
	elseif ( is_archive() ) {

		// get the name of the taxonomy
		$custom_tax_name = get_queried_object()->name;

		// Add markup for taxonomy
		$html .= '<span class="item-current item-archive"><span class="bread-current bread-archive">' . $custom_tax_name . '</span></span>';
	} // Search
	elseif ( is_search() ) {

		// Add search markup
		$html .= '<span class="item-current item-search"><span class="bread-current bread-search">Search results for: ' . get_search_query() . '</span></span>';
	} // 404
	elseif ( is_404() ) {

		// Add 404 markup
		$html .= '<span>' . 'Error 404' . '</span>';
	}

	// Close breadcrumb container
	$html .= '</div>';

	echo $html;
}