<?php

if ( get_theme_mod( 'logo_upload' ) ) {
	$logo = "<span class='screen-reader-text'>" . get_bloginfo( 'name' ) . "</span><img id='logo' class='logo' src='" . esc_url( get_theme_mod( 'logo_upload' ) ) . "' alt='" . esc_attr( get_bloginfo( 'name' ) ) . "' />";
} else {
	$logo = get_bloginfo( 'name' );
}

$output = '<div class="site-title">';
$output .= "<a href='" . esc_url( home_url() ) . "'>";
$output .= $logo;
$output .= "</a>";
$output .= '</div>';

echo $output;
