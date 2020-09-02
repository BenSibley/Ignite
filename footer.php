</div><!-- .main -->
<?php
if ( ! is_page_template( 'full-width.php' ) ) {
	get_sidebar( 'primary' );
}
?>
</div><!-- .overflow-container -->
<?php 
// Elementor `footer` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) :
?>
<footer id="site-footer" class="site-footer" role="contentinfo">
	<h1>
		<a href="<?php echo esc_url( home_url() ); ?>">
			<?php bloginfo( 'title' ); ?>
		</a>
	</h1>
	<?php
	if ( get_bloginfo( 'description' ) ) {
		echo '<span class="tagline">' . esc_html( get_bloginfo( "description" ) ) . '</span>';
	}
	?>
	<div class="design-credit">
        <span>
            <?php
            $footer_text = get_theme_mod( 'ct_ignite_footer_text_setting' );
            if ( empty( $footer_text ) ) {
	            $footer_text = sprintf( __( '<a target="_blank" href="%1$s">%2$s WordPress Theme</a> by Compete Themes.', 'ignite' ), 'https://www.competethemes.com/ignite/', wp_get_theme( get_template() ) );
            }
            echo do_shortcode( wp_kses_post( $footer_text ) );
            ?>
        </span>
	</div>
</footer>
<?php endif; ?>
<?php do_action( 'ct_ignite_body_bottom' ); ?>
<?php wp_footer(); ?>
</body>
</html>