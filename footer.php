</div> <!-- .main -->

<div id="sidebar-primary-container" class="sidebar-primary-container">
    <?php get_sidebar( 'primary' ); ?>
</div>

</div> <!-- .overflow-container -->

<footer class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <span><?php bloginfo('description'); ?></span>
    <div class="design-credit">
        <span>
            <?php
            $site_url = 'http://www.competethemes.com/ignite/';
            $site_link = sprintf( __( '<a target="_blank" href="%s">Ignite WordPress Theme</a> by Compete Themes.', 'ignite' ), esc_url( $site_url ) );
            echo $site_link;
            ?>
        </span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>