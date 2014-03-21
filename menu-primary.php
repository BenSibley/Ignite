<?php if ( has_nav_menu( 'primary' ) ) : ?>

	<span id="toggle-navigation" class="toggle-navigation"><i class="fa fa-bars"></i></span>

	<div class="menu-container menu-primary" id="menu-primary">

        <p><?php bloginfo('description'); ?></p>
		
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => 'menu-primary-items', 'menu_id' => 'menu-primary-items', 'fallback_cb' => 'wp_page_menu' ) ); ?>

        <?php ct_social_media_icons(); // adds social media icons ?>

	</div><!-- #menu-primary .menu-container -->

<?php endif; ?>