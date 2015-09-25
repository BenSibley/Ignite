<?php

// takes user input from the customizer and outputs linked social media icons

$social_sites = ct_ignite_customizer_social_media_array();

// any inputs that aren't empty are stored in $active_sites array
foreach($social_sites as $social_site) {
	if( strlen( get_theme_mod( $social_site ) ) > 0 ) {
		$active_sites[] = $social_site;
	}
}

// for each active social site, add it as a list item
if(!empty($active_sites)) {

	// icons that should use a special square icon
	$square_icons = array('linkedin', 'twitter', 'vimeo', 'youtube', 'pinterest', 'reddit', 'tumblr', 'steam', 'xing', 'github', 'google-plus', 'behance', 'facebook', 'rss');

	echo "<ul class='social-media-icons'>";

	foreach ($active_sites as $active_site) {

		// get the square or plain class
		if ( in_array( $active_site, $square_icons ) ) {
			$class = 'fa fa-' . $active_site . '-square';
		} else {
			$class = 'fa fa-' . $active_site;
		}
		// add exception for academia
		if( $active_site == 'academia' ) $class = 'fa fa-graduate-cap';

		if ( $active_site == 'email' ) {
			?>
			<li>
				<a class="email" target="_blank" href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
					<i class="fa fa-envelope" title="<?php _e( 'email icon', 'founder' ); ?>"></i>
				</a>
			</li>
		<?php } else { ?>
			<li>
				<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank" href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
					<i class="<?php echo esc_attr( $class ); ?>" title="<?php printf( __( '%s icon', 'founder' ), esc_attr( $active_site ) ); ?>"></i>
				</a>
			</li>
			<?php
		}
	}
	echo "</ul>";
}