<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

    <!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->
    <!--[if IE 7 ]>    <html class="ie7"> <![endif]-->
    <!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
    <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5shiv/html5shiv.js"></script>
    <![endif]-->
    <!--[if IE 9 ]><html class="ie9"> <![endif]-->
    <!--[if (gt IE 9)|!(IE)]><!--> <html> <!--<![endif]-->
    
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <title><?php wp_title("",true); ?></title>

    <!-- set width to the device viewing the site -->
    <meta name="viewport" content="width=device-width" />

    <?php wp_head(); ?>

</head>

<body id="<?php print get_stylesheet(); ?>" <?php body_class(); ?>>

<!--skip to content link-->
<a class="skip-content" href="#main">Skip to content</a>

<header class="site-header" id="site-header" role="banner">

	<div class="title-info">
		<?php get_template_part('logo')  ?>
	</div>
	
	<?php get_template_part( 'menu', 'primary' ); // adds the primary menu ?>

</header>
<div id="overflow-container" class="overflow-container">

    <?php
    if ( current_theme_supports( 'breadcrumb-trail' ) && !is_search() ) {
        breadcrumb_trail(array(
            'separator' => '>',
            'show_browse' => false,
            'show_on_front' => false)
        );
    }
    ?>
    <div id="main" class="main" role="main">