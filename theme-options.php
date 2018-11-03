<?php

function ct_ignite_register_theme_page() {
	add_theme_page( sprintf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme() ), sprintf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme() ), 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content' );
}
add_action( 'admin_menu', 'ct_ignite_register_theme_page' );

function ct_ignite_options_content() {

	$customizer_url = add_query_arg(
		array(
			'url'    => get_home_url(),
			'return' => add_query_arg( 'page', 'ignite-options', admin_url( 'themes.php' ) )
		),
		admin_url( 'customize.php' )
	);
	$plus_url = 'https://www.competethemes.com/ignite-plus/?utm_source=wp-dashboard&utm_medium=Dashboard&utm_campaign=Ignite%20Plus%20-%20Dashboard';
	?>
	<div id="ignite-dashboard-wrap" class="wrap ignite-dashboard-wrap">
		<h2><?php printf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme() ); ?></h2>
		<?php do_action( 'theme_options_before' ); ?>
		<div class="main">
			<?php if ( function_exists( 'ct_ignite_plus_init' ) ) : ?>
			<div class="thanks-upgrading" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3>Thanks for upgrading!</h3>
				<p>You can find the new features in the Customizer</p>
			</div>
			<?php endif; ?>
			<?php if ( !function_exists( 'ct_ignite_plus_init' ) ) : ?>
			<div class="getting-started">
				<h3>Get Started with Ignite</h3>
				<p>Follow this step-by-step guide to customize your website with Ignite:</p>
				<a href="https://www.competethemes.com/help/getting-started-ignite/" target="_blank">Read the Getting Started Guide</a>
			</div>
			<div class="plus">
				<h3>Customize More with Ignite Plus</h3>
				<p>Add 14 new customization features to your site with the <a href="<?php echo $plus_url; ?>" target="_blank">Ignite Plus</a> plugin.</p>
				<ul class="feature-list">
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/layouts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Layouts</h4>
							<p>New layouts help your content look and perform its best. You can switch to new layouts effortlessly from the Customizer, or from specific posts or pages.</p>
							<p>Ignite Plus adds 6 new layouts.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/custom-colors.png'; ?>" />
						</div>
						<div class="text">
							<h4>Custom Colors</h4>
							<p>Custom colors let you match the color of your site with your brand. Point-and-click to select a color, and watch your site update instantly.</p>
							<p>Change the color of 35 different elements on your site, in a click.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/fonts.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Fonts</h4>
							<p>Stylish new fonts add character and charm to your content. Select and instantly preview fonts from the Customizer.</p>
							<p>Since Ignite Plus is powered by Google Fonts, it comes with 728 fonts for you to choose from.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/header-image.png'; ?>" />
						</div>
						<div class="text">
							<h4>Flexible Header Image</h4>
							<p>Header images welcome visitors and set your site apart. Upload your image and quickly resize it to the perfect size.</p>
							<p>Display the header image on just the homepage, or leave it sitewide and link it to the homepage.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-videos.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Videos</h4>
							<p>Featured Videos are an easy way to share videos in place of Featured Images. Instantly embed a Youtube video by copying and pasting its URL into an input.</p>
							<p>Ignite Plus auto-embeds videos from Youtube, Vimeo, DailyMotion, Flickr, Animoto, TED, Blip, Cloudup, FunnyOrDie, Hulu, Vine, WordPress.tv, and VideoPress.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-sliders.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Sliders</h4>
							<p>Featured Sliders are an easy way to share image sliders in place of Featured Images. Quickly add responsive sliders to any page or post.</p>
							<p>Ignite Plus integrates with the free Meta Slider plugin with styling and sizing controls for your sliders.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/background-images.png'; ?>" />
						</div>
						<div class="text">
							<h4>Background Images</h4>
							<p>Background images help you stand out from the rest. Upload a unique image to use as the backdrop for your site.</p>
							<p>Background images are automatically centered and sized to fit the screen.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/background-textures.png'; ?>" />
						</div>
						<div class="text">
							<h4>Background Textures</h4>
							<p>Background textures transform the look and feel of your site. Switch to a textured background with a click.</p>
							<p>Ignite Plus includes 39 bundled textures to choose from.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/featured-image-size.png'; ?>" />
						</div>
						<div class="text">
							<h4>Featured Image Size</h4>
							<p>Set each Featured Image to the perfect size. You can change the aspect ratio for all Featured Images and individual Featured Images with ease.</p>
							<p>Ignite Plus includes twelve different aspect ratios for your Featured Images.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/widget-areas.png'; ?>" />
						</div>
						<div class="text">
							<h4>New Widget Areas</h4>
							<p>Utilize a sidebar and four additional widget areas for greater flexibility. Increase ad revenue and generate more email subscribers by adding widgets throughout your site.</p>
							<p>Ignite Plus adds 4 new widget areas.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/fixed-menus.png'; ?>" />
						</div>
						<div class="text">
							<h4>Navigation Styles</h4>
							<p>Fixed position menus help visitors discover your content by keeping the navigation present at all times. Easily switch between menu styles from the Customizer.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/about-me-widget.png'; ?>" />
						</div>
						<div class="text">
							<h4>Premium Widgets</h4>
							<p>Premium widgets show off your site's best content and images. Add and customize the widgets in any widget area.</p>
							<p>Ignite Plus includes four premium widgets.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/footer-menu.png'; ?>" />
						</div>
						<div class="text">
							<h4>Footer Menu</h4>
							<p>The footer menu provides a place for secondary pages. Use it to link to your TOS and privacy policy pages.</p>
							<p>The footer menu can be setup and added easily through the Menus page in your dashboard.</p>
						</div>
					</li>
					<li>
						<div class="image">
							<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/author-social-icons.png'; ?>" />
						</div>
						<div class="text">
							<h4>Author Social Icons</h4>
							<p>Author social icons after each post help readers find and connect with you on social media. Every author can easily add their own social profile URLs.</p>
							<p>Ignite Plus supports all the social sites available in the Customizer.</p>
						</div>
					</li>
				</ul>
				<p><a href="<?php echo $plus_url; ?>" target="_blank">Click here</a> to view Ignite Plus now, and see what it can do for your site.</p>
			</div>
			<div class="plus-ad" style="background-image: url(<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/bg-texture.png'; ?>)">
				<h3>Add Incredible Flexibility to Your Site</h3>
				<p>Start customizing with Ignite Plus today</p>
				<a href="<?php echo $plus_url; ?>" target="_blank">View Ignite Plus</a>
			</div>
			<?php endif; ?>
		</div>
		<div class="sidebar">
			<div class="dashboard-widget">
				<h4>More Amazing Resources</h4>
				<ul>
					<li><a href="https://www.competethemes.com/documentation/ignite-support-center/" target="_blank">Ignite Support Center</a></li>
					<li><a href="https://wordpress.org/support/theme/ignite" target="_blank">Support Forum</a></li>
					<li><a href="https://www.competethemes.com/help/ignite-changelog/" target="_blank">Changelog</a></li>
					<li><a href="https://www.competethemes.com/help/ignite-css-snippets/" target="_blank">CSS Snippets</a></li>
					<li><a href="https://www.competethemes.com/help/child-theme-ignite/" target="_blank">Starter child theme</a></li>
					<li><a href="https://www.competethemes.com/help/ignite-demo-data/" target="_blank">Ignite demo data</a></li>
					<li><a href="<?php echo $plus_url; ?>" target="_blank">Ignite Plus</a></li>
				</ul>
			</div>
			<div class="dashboard-widget">
				<h4>User Reviews</h4>
				<img src="<?php echo trailingslashit(get_template_directory_uri()) . 'assets/images/reviews.png'; ?>" />
				<p>Users are loving Ignite! <a href="https://wordpress.org/support/theme/ignite/reviews/?filter=5#new-post" target="_blank">Click here</a> to leave your own review</p>
			</div>
			<div class="dashboard-widget">
				<h4>Reset Customizer Settings</h4>
				<p><b>Warning:</b> Clicking this buttin will erase the Ignite theme's current settings in the Customizer.</p>
				<form method="post">
					<input type="hidden" name="ignite_reset_customizer" value="ignite_reset_customizer_settings"/>
					<p>
						<?php wp_nonce_field( 'ignite_reset_customizer_nonce', 'ignite_reset_customizer_nonce' ); ?>
						<?php submit_button( 'Reset Customizer Settings', 'delete', 'delete', false ); ?>
					</p>
				</form>
			</div>
		</div>
		<?php do_action( 'theme_options_after' ); ?>
	</div>
<?php }