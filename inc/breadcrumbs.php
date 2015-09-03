<?php

// Breadcrumbs
function ct_ignite_breadcrumbs() {

	// Do not display on the homepage
	if ( is_front_page() ) return;

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
			$html .= '<span class="item-cat">' . wp_kses( $parent, wp_kses_allowed_html('a') ). '</span>';
			$html .= '<span class="separator"> ' . esc_attr( $separator ) . ' </span>';
		}

		// add name of Post
		$html .= '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></span>';
	}
	// Page
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
	}
	// Attachment
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
	}
	// Custom Post Types
	elseif ( is_singular() ) {

	}
	// Category
	elseif ( is_category() ) {
		$html .= '<span class="item-current item-cat"><span class="bread-current bread-cat">' . esc_attr( single_cat_title('', false) ) . '</span></span>';
	}
	// Tag
	elseif ( is_tag() ) {

	}
	// Author
	elseif ( is_author() ) {

	}
	// Day
	elseif ( is_day() ) {

	}
	// Month
	elseif ( is_month() ) {

	}
	// Year
	elseif ( is_year() ) {

	}
	// Search
	elseif ( is_search() ) {

	}
	// 404
	elseif ( is_404() ) {

	}
	// Custom Taxonomy
	elseif ( is_tax() ) {

	}
//	if ( is_archive() && !is_tax() && !is_category() ) {
//
//		echo '<span class="item-current item-archive"><span class="bread-current bread-archive">' . post_type_archive_title(false, false) . '</span></span>';
//
//	} elseif ( is_archive() && is_tax() && !is_category() ) {
//
//		$custom_tax_name = get_queried_object()->name;
//		echo '<span class="item-current item-archive"><span class="bread-current bread-archive">' . $custom_tax_name . '</span></span>';
//
//	} elseif ( is_singular( 'post' ) ) {
//
//		// Get post category info
//		$category = get_the_category();
//
//		$category_values = array_values($category);
//
//		// Get last category post is in
//		$last_category = end( $category_values );
//
//		// Get parent any categories and create array
//		$get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
//		$cat_parents = explode(',',$get_cat_parents);
//
//		// Loop through parent categories and store in variable $cat_display
//		$cat_display = '';
//
//		foreach($cat_parents as $parents) {
//			$cat_display .= '<span class="item-cat">'.$parents.'</span>';
//			$cat_display .= '<span class="separator"> ' . $separator . ' </span>';
//		}
//
//		// Check if the post is in a category
//		if( !empty( $last_category ) ) {
//			echo $cat_display;
//			echo '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></span>';
//		} else {
//			echo '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</span></span>';
//		}

//	} elseif ( is_singular( 'page' ) ) {
//
//		// Standard page
//		if( $post->post_parent ){
//
//			// If child page, get parents
//			$anc = get_post_ancestors( $post->ID );
//
//			// Get parents in the right order
//			$anc = array_reverse($anc);
//
//			$parents = '';
//
//			// Parent page loop
//			foreach ( $anc as $ancestor ) {
//				$parents .= '<span class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></span>';
//				$parents .= '<span class="separator separator-' . $ancestor . '"> ' . $separator . ' </span>';
//			}
//
//			// Display parent pages
//			echo $parents;
//
//			// Current page
//			echo '<span class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></span>';

//		} else {
//
//			// Just display current page if not parents
//			echo '<span class="item-current item-' . $post->ID . '"><span class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</span></span>';
//
//		}
//
//	} elseif ( is_singular( 'attachment' ) ) {
//
//		// Get the parent post ID
//		$parent_id = $post->post_parent;
//
//		// Get the parent post Title
//		$parent_title = get_the_title( $parent_id );
//
//		// Get the parent post permalink
//		$parent_permalink = get_permalink( $parent_id );
//
//		$parent_markup = '<span class="item-parent"><a class="bread-parent" href="' . $parent_permalink . '" title="' . $parent_title . '">' . $parent_title . '</a></span>';
//		$parent_markup .= '<span class="separator"> ' . $separator . ' </span>';
//
//		echo $parent_markup;
//
//		// Current page
//		echo '<span class="item-current item-' . $post->ID . '"><span title="' . get_the_title() . '"> ' . get_the_title() . '</span></span>';
//
//	} elseif ( is_tag() ) {
//
//		// Tag page
//
//		// Get tag information
//		$term_id = get_query_var('tag_id');
//		$taxonomy = 'post_tag';
//		$args ='include=' . $term_id;
//		$terms = get_terms( $taxonomy, $args );
//
//		// Display the tag name
//		echo '<span class="item-current item-tag-' . $terms->term_id . ' item-tag-' . $terms[0]->slug . '"><span class="bread-current bread-tag-' . $terms->term_id . ' bread-tag-' . $terms[0]->slug . '">' . $terms[0]->name . '</span></span>';
//
//    } elseif ( is_day() ) {
//
//		// Day archive
//
//		// Year link
//		echo '<span class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></span>';
//		echo '<span class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </span>';
//
//		// Month link
//		echo '<span class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></span>';
//		echo '<span class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </span>';
//
//		// Day display
//		echo '<span class="item-current item-' . get_the_time('j') . '"><span class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</span></span>';
//
//	} elseif ( is_month() ) {
//
//		// Month Archive
//
//		// Year link
//		echo '<span class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></span>';
//		echo '<span class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </span>';
//
//		// Month display
//		echo '<span class="item-month item-month-' . get_the_time('m') . '"><span class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</span></span>';
//
//	} elseif ( is_year() ) {
//
//		// Display year archive
//		echo '<span class="item-current item-current-' . get_the_time('Y') . '"><span class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</span></span>';
//
//	} elseif ( is_author() ) {
//
//		// Auhor archive
//
//		// Get the author information
//		global $author;
//		$userdata = get_userdata( $author );
//
//		// Display author name
//		echo '<span class="item-current item-current-' . $userdata->user_nicename . '"><span class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Author: ' . $userdata->display_name . '</span></span>';
//
//	} elseif ( get_query_var('paged') ) {
//
//		// Paginated archives
//		echo '<span class="item-current item-current-' . get_query_var('paged') . '"><span class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</span></span>';
//
//	} elseif ( is_search() ) {
//
//		// Search results page
//		echo '<span class="item-current item-current-' . get_search_query() . '"><span class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Search results for: ' . get_search_query() . '</span></span>';
//
//	} elseif ( is_404() ) {
//
//		// 404 page
//		echo '<span>' . 'Error 404' . '</span>';
//	}

	// Close breadcrumb container
	$html .= '</div>';

	echo $html;
}