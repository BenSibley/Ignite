<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php wp_head(); ?>
</head>
<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
	<?php 
	if ( function_exists( 'wp_body_open' ) ) {
				wp_body_open();
		} else {
				do_action( 'wp_body_open' );
	} ?>
	<a class="skip-content" href="#main"><?php esc_html_e( 'Skip to content', 'ignite' ); ?></a>
	<?php
	// Elementor `header` location
	if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) :
	?>
	<header class="site-header" id="site-header" role="banner">
		<div id="title-info" class="title-info">
			<?php get_template_part( 'logo' ) ?>
		</div>
		<?php get_template_part( 'menu', 'primary' ); ?>
	</header>
	<?php endif; ?>
	<div id="overflow-container" class="overflow-container">
		<?php ct_ignite_breadcrumbs(); ?>
		<div id="main" class="main" role="main">