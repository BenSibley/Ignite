</div> <!-- .main -->

<div id="sidebar-primary-container" class="sidebar-primary-container">
    <?php get_sidebar( 'primary' ); ?>
</div>

</div> <!-- .overflow-container -->

<footer class="site-footer" role="contentinfo">
    <h3><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('title'); ?></a></h3>
    <span><?php bloginfo('description'); ?></span>
    <div class="design-credit">
        <span><a href="http://www.competethemes.com/ignite/">Ignite WordPress Theme</a> by Compete Themes</span>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>