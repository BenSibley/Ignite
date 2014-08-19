<?php

/* create theme options page */
function ct_ignite_register_theme_page(){
add_theme_page( 'Ignite Theme Options', 'Theme Options', 'edit_theme_options', 'ignite-options', 'ct_ignite_options_content', 'ct_ignite_options_content');
}
add_action( 'admin_menu', 'ct_ignite_register_theme_page' );

/* callback used to add content to options page */
function ct_ignite_options_content(){
    ?>
    <div id="ignite-dashboard-wrap" class="wrap">
        <h2><?php _e('Ignite Dashboard', 'ignite'); ?></h2>

        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'getting-started'; ?>

        <h2 class="nav-tab-wrapper">
            <a href="?page=ignite-options&tab=getting-started" class="nav-tab <?php echo $active_tab == 'getting-started' ? 'nav-tab-active' : ''; ?>">Getting Started</a>
            <a href="?page=ignite-options&tab=support" class="nav-tab <?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>">Support</a>
            <a href="?page=ignite-options&tab=premium-upgrade" class="nav-tab <?php echo $active_tab == 'premium-upgrade' ? 'nav-tab-active' : ''; ?>">Premium Upgrade</a>
        </h2>

        <?php
        if($active_tab == 'getting-started'){ ?>
            <div class="content content-getting-started">
                <h3><?php _e('Start Customizing', 'ignite'); ?></h3>
                <p><?php _e('Thanks for downloading Ignite!', 'ignite'); ?></p>
                <p><?php _e("To start customizing Ignite, click the 'Customize' option in your menu, or use the button below to get started", 'ignite'); ?>.</p>
                <p>
                    <a class="button-primary" href="customize.php"><?php _e('Use Customizer', 'ignite') ?></a>
                </p>
            </div>
        <?php }
        elseif($active_tab == 'support'){ ?>
            <div class="content content-support">
                <h3><?php _e('Get Support', 'ignite'); ?></h3>
                <p><?php _e("You can find the knowledgebase, tutorials, support forum, and more in the Ignite Support Center", "ignite"); ?>.</p>
                <p>
                    <a target="_blank" class="button-primary" href="http://www.competethemes.com/documentation/ignite-support-center/"><?php _e('Visit Support Center', 'ignite'); ?></a>
                </p>
            </div>
        <?php }
        elseif($active_tab == 'premium-upgrade'){ ?>
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
        <?php } ?>
    </div>
<?php }