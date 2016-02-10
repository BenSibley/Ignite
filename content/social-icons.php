<?php

$social_sites = ct_ignite_customizer_social_media_array();

foreach ( $social_sites as $social_site ) {
	if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
		$active_sites[] = $social_site;
	}
}

if ( ! empty( $active_sites ) ) {

	$square_icons = array(
		'linkedin',
		'twitter',
		'vimeo',
		'youtube',
		'pinterest',
		'rss',
		'reddit',
		'tumblr',
		'steam',
		'xing',
		'github',
		'google-plus',
		'behance',
		'facebook',
		'rss'
	);

	echo "<ul class='social-media-icons'>";

		foreach ( $active_sites as $active_site ) {

			if ( in_array( $active_site, $square_icons ) ) {
				$class = 'fa fa-' . $active_site . '-square';
			} else {
				$class = 'fa fa-' . $active_site;
			}
			if ( $active_site == 'academia' ) {
				$class = 'fa fa-graduate-cap';
			}
			if ( $active_site == 'email-form' ) {
				$class = 'fa fa-envelope-o';
			}

			if ( $active_site == 'email' ) { ?>
				<li>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php esc_attr_e( 'email', 'ignite' ); ?>"></i>
					</a>
				</li>
			<?php } elseif ( $active_site == 'skype' ) { ?>
				<li>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>" title="<?php esc_attr( $active_site ); ?>"></i>
					</a>
				</li>
			<?php } else { ?>
				<li>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>" title="<?php esc_attr( $active_site ); ?>"></i>
					</a>
				</li>
				<?php
			}
		}
	echo "</ul>";
}