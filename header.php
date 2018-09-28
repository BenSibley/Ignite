<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<?php wp_head(); ?>
</head>
<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>
	<a class="skip-content" href="#main"><?php esc_html_e( 'Skip to content', 'ignite' ); ?></a>
	<header class="site-header" id="site-header" role="banner">
		<div id="title-info" class="title-info">
			<?php get_template_part( 'logo' ) ?>
		</div>
		<?php get_template_part( 'menu', 'primary' ); ?>
	</header>
	<div id="overflow-container" class="overflow-container">
		<?php ct_ignite_breadcrumbs(); ?>
		<div id="main" class="main" role="main">