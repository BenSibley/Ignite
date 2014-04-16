<button id="toggle-navigation" class="toggle-navigation"><i class="fa fa-bars"></i></button>

<div class="menu-container menu-primary" id="menu-primary" role="navigation">

    <p><?php bloginfo('description'); ?></p>

    <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => 'false', 'menu_class' => 'menu-primary-items', 'menu_id' => 'menu-primary-items', 'items_wrap' => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>', 'fallback_cb' => 'ct_ignite_wp_page_menu') ); ?>

    <?php ct_ignite_social_media_icons(); // adds social media icons ?>

</div><!-- #menu-primary .menu-container -->