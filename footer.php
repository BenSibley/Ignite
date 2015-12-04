</div> <!-- .main -->

<?php if( ! is_page_template('full-width.php') ) : ?>
	<div id="sidebar-primary-container" class="sidebar-primary-container">
	    <?php get_sidebar( 'primary' ); ?>
	</div>
<?php endif; ?>

</div> <!-- .overflow-container -->

<footer id="site-footer" class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <?php
    if ( get_bloginfo('description') ) {
        echo '<span class="tagline">' . get_bloginfo("description") . '</span>';
    }
    ?>
    <div class="design-credit">
        <span>
            <?php
            /* Get the user's footer text input */
            $user_footer_text = get_theme_mod('ct_ignite_footer_text_setting');

            /* If it's not empty, output their text */
            if( ! empty($user_footer_text) ) {
                echo wp_kses_post( $user_footer_text );
            }
            /* Otherwise, output the default text */
            else {
                $site_url = 'https://www.competethemes.com/ignite/';
                $footer_text = sprintf( __( '<a target="_blank" href="%s">Ignite WordPress Theme</a> by Compete Themes.', 'ignite' ), esc_url( $site_url ) );
                echo wp_kses_post( $footer_text );
            }
            ?>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>