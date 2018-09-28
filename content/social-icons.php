<?php

$social_sites = ct_ignite_customizer_social_media_array();

foreach ( $social_sites as $social_site ) {
	if ( strlen( get_theme_mod( $social_site ) ) > 0 ) {
		$active_sites[] = $social_site;
	}
}

if ( ! empty( $active_sites ) ) {

	$square_icons = array(
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
				$class = 'fab fa-' . $active_site . '-square';
			} else {
				$class = 'fab fa-' . $active_site;
			}
			if ( $active_site == 'academia' ) {
				$class = 'fas fa-graduation-cap';
			} elseif ( $active_site == 'rss' ) {
				$class = 'fas fa-rss';
			} elseif ( $active_site == 'email-form' ) {
				$class = 'far fa-envelope';
			} elseif ( $active_site == 'podcast' ) {
				$class = 'fas fa-podcast';
			} elseif ( $active_site == 'ok-ru' ) {
				$class = 'fab fa-odnoklassniki';
			} elseif ( $active_site == 'wechat' ) {
				$class = 'fab fa-weixin';
			} elseif ( $active_site == 'phone' ) {
				$class = 'fas fa-phone';
			}

			if ( $active_site == 'email' ) { ?>
				<li>
					<a class="email" target="_blank"
					   href="mailto:<?php echo antispambot( is_email( get_theme_mod( $active_site ) ) ); ?>">
						<i class="fa fa-envelope" title="<?php echo esc_attr_x( 'email', 'noun', 'ignite' ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html_x('email', 'noun', 'ignite'); ?></span>
					</a>
				</li>
			<?php } elseif ( $active_site == 'skype' ) { ?>
				<li>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'http', 'https', 'skype' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				</li>
			<?php } elseif ( $active_site == 'phone' ) { ?>
				<li>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
							href="<?php echo esc_url( get_theme_mod( $active_site ), array( 'tel' ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				</li>
			<?php } else { ?>
				<li>
					<a class="<?php echo esc_attr( $active_site ); ?>" target="_blank"
					   href="<?php echo esc_url( get_theme_mod( $active_site ) ); ?>">
						<i class="<?php echo esc_attr( $class ); ?>" title="<?php echo esc_attr( $active_site ); ?>"></i>
						<span class="screen-reader-text"><?php echo esc_html( $active_site );  ?></span>
					</a>
				</li>
				<?php
			}
		}
	echo "</ul>";
}