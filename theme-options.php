<?php

/* create theme options page */
function ct_ignite_register_theme_page(){
add_theme_page( 'Ignite Dashboard', 'Ignite Dashboard', 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content');
}
add_action( 'admin_menu', 'ct_ignite_register_theme_page' );

/* callback used to add content to options page */
function ct_ignite_options_content(){
    ?>
    <div id="ignite-dashboard-wrap" class="wrap">
        <h2><?php _e('Ignite Dashboard', 'ignite'); ?></h2>
        <div class="content content-customization">
            <h3><?php _e('Customization', 'ignite'); ?></h3>
            <p><?php _e('Click the "Customize" link in your menu, or use the button below to get started customizing Ignite', 'ignite'); ?>.</p>
            <p>
                <a class="button-primary" href="customize.php"><?php _e('Use Customizer', 'ignite') ?></a>
            </p>
        </div>
        <div class="content content-support">
	        <h3><?php _e('Support', 'ignite'); ?></h3>
            <p><?php _e("You can find the knowledgebase, changelog, support forum, and more in the Ignite Support Center", "ignite"); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/documentation/ignite-support-center/"><?php _e('Visit Support Center', 'ignite'); ?></a>
            </p>
        </div>
        <div class="content content-resources">
            <h3><?php _e('WordPress Resources', 'ignite'); ?></h3>
            <p><?php _e("Save time and money searching for WordPress products by following our recommendations", "ignite"); ?>.</p>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/wordpress-resources/"><?php _e('View Resources', 'ignite'); ?></a>
            </p>
        </div>
        <div class="content content-premium-upgrade">
            <h3><?php _e('Upgrade to Ignite Plus ($29)', 'ignite'); ?></h3>
            <p><?php _e('Ignite Plus is the premium version of Ignite. By upgrading to Ignite Plus, you get:', 'ignite'); ?></p>
            <ul>
                <li><?php _e('Custom colors', 'ignite'); ?></li>
                <li><?php _e('Background images & textures', 'ignite'); ?></li>
                <li><?php _e('New layouts', 'ignite'); ?></li>
                <li><?php _e('and much more&#8230;', 'ignite'); ?></li>
            </ul>
            <p>
                <a target="_blank" class="button-primary" href="https://www.competethemes.com/ignite-plus/"><?php _e('See Full Feature List', 'ignite'); ?></a>
            </p>
        </div>
    </div>
<?php } ?>
