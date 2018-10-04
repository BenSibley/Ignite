<button id="toggle-navigation" class="toggle-navigation"><i class="fas fa-bars"></i></button>
<div class="menu-container menu-primary" id="menu-primary" role="navigation">
	<?php
	if ( get_bloginfo( 'description' ) ) {
		echo '<p id="site-description" class="tagline">' . esc_html( get_bloginfo( "description" ) ) . '</p>';
	}
	wp_nav_menu( array(
		'theme_location' => 'primary',
		'container'      => 'false',
		'menu_class'     => 'menu-primary-items',
		'menu_id'        => 'menu-primary-items',
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'fallback_cb'    => 'ct_ignite_wp_page_menu'
	) );
	get_template_part( 'content/social-icons' );
	?>
</div>