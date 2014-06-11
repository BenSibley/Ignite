<?php
if ( is_front_page() ) {
    $heading_opening = "<h1 class='site-title'>";
    $heading_closing = "</h1>";
} else {
    $heading_opening = "<h2 class='site-title'>";
    $heading_closing = "</h2>";
}
if ( get_theme_mod( 'logo_upload') ) {
    $logo = "<span>" . get_bloginfo('name') . "</span><img id='logo' class='logo' src='".esc_url(get_theme_mod( 'logo_upload'))."' alt='".esc_attr( get_bloginfo( 'name' ) )."' />";
} else {
    $logo = get_bloginfo('name');
}

$output = $heading_opening;
$output .= "<a href='".esc_url( home_url() )."' title='".esc_attr( get_bloginfo( 'name' ) )."'>";
$output .= $logo;
$output .= "</a>";
$output .= $heading_closing;

echo $output;
