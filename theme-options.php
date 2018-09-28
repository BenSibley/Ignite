<?php

function ct_ignite_register_theme_page() {
	add_theme_page( sprintf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme( get_template() ) ), sprintf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme( get_template() ) ), 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content' );
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
	$support_url = 'https://www.competethemes.com/documentation/ignite-support-center/';
	?>
	<div id="ignite-dashboard-wrap" class="wrap">
		<h2><?php printf( esc_html__( '%s Dashboard', 'ignite' ), wp_get_theme( get_template() ) ); ?></h2>
		<div class="content-boxes">
			<div class="content content-support">
				<h3><?php esc_html_e( 'Get Started', 'ignite' ); ?></h3>
				<p><?php printf( __( 'Not sure where to start? The <strong>%1$s Getting Started Guide</strong> will take you step-by-step through every feature in %1$s.', 'ignite' ), wp_get_theme( get_template() ) ); ?></p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/help/getting-started-ignite/"><?php esc_html_e( 'View Guide', 'ignite' ); ?></a>
				</p>
			</div>
			<div class="content content-premium-upgrade">
				<h3><?php printf( esc_html__( '%s Plus', 'ignite' ), wp_get_theme( get_template() ) ); ?></h3>
				<p><?php printf( esc_html__( 'Download %s Plus and unlock new layouts, custom colors, header images, and more', 'ignite' ), wp_get_theme( get_template() ) ); ?>...</p>
				<p>
					<a target="_blank" class="button-primary"
					   href="https://www.competethemes.com/ignite-plus/"><?php esc_html_e( 'See Full Feature List', 'ignite' ); ?></a>
				</p>
			</div>
			<div class="content content-review">
				<h3><?php esc_html_e( 'Leave a Review', 'ignite' ); ?></h3>
				<p><?php printf( esc_html__( 'Help others find %s by leaving a review on wordpress.org.', 'ignite' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://wordpress.org/support/theme/ignite/reviews/"><?php esc_html_e( 'Leave a Review', 'ignite' ); ?></a>
			</div>
			<div class="content content-presspad">
				<h3><?php esc_html_e( 'Turn Ignite into a Mobile App', 'ignite' ); ?></h3>
				<p><?php printf( esc_html__( '%s can be converted into a mobile app and listed on the App Store and Google Play Store with the help of PressPad News. Read our tutorial to learn more.', 'ignite' ), wp_get_theme( get_template() ) ); ?></p>
				<a target="_blank" class="button-primary" href="https://www.competethemes.com/help/convert-mobile-app-ignite/"><?php esc_html_e( 'Read Tutorial', 'ignite' ); ?></a>
			</div>
		</div>
	</div>
<?php } ?>
