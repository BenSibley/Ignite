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
            <a href="?page=ignite-options&tab=premium-upgrade" class="nav-tab <?php echo $active_tab == 'premium-upgrade' ? 'nav-tab-active' : ''; ?>">Premium Upgrade</a>
            <a href="?page=ignite-options&tab=license" class="nav-tab <?php echo $active_tab == 'license' ? 'nav-tab-active' : ''; ?>">License</a>
        </h2>

        <?php
        if($active_tab == 'getting-started'){ ?>
            <div class="content content-getting-started">
                <h3><?php _e('Start Customizing', 'ignite'); ?></h3>
                <p><?php _e("Ignite's customization options are available in the Customizer. You can click the 'Customize' option in your menu, or use the button below to get started", "ignite") ?>.</p>
                <p>
                    <a class="button-primary" href="customize.php"><?php _e('Use Customizer', 'ignite') ?></a>
                </p>
                <h3><?php _e('Get Support', 'ignite'); ?></h3>
                <p><?php _e("You can find the knowledgebase, tutorials, support forum, and more in the Ignite Support Center", "ignite"); ?>.</p>
                <p>
                    <a target="_blank" class="button-primary" href="http://www.competethemes.com/ignite-support-center/"><?php _e('Visit Support Center', 'ignite'); ?></a>
                </p>
            </div>
        <?php }
        elseif($active_tab == 'premium-upgrade'){ ?>
            <div class="content content-premium-upgrade">
                <h3><?php _e('Support', 'ignite'); ?></h3>
                <ol>
                    <li>
                        <?php
                        $docs_url = 'http://www.competethemes.com/documentation/ignite-knowledgebase/?utm_source=WordPress%20Dashboard&utm_medium=User%20Admin&utm_content=Ignite&utm_campaign=Admin%20Support%20Widgets';
                        $docs_link = sprintf( __( '<a target="_blank" href="%s">Visit the knowledgebase</a> for self-help.', 'ignite' ), esc_url( $docs_url ) );
                        echo $docs_link;
                        ?>
                    </li>
                    <li>
                        <?php
                        $forum_url = 'http://wordpress.org/support/theme/ignite';
                        $forum_link = sprintf( __( '<a target="_blank" href="%s">Visit the support forum</a> for community support.', 'ignite' ), esc_url( $forum_url ) );
                        echo $forum_link;
                        ?>
                    </li>
                </ol>
                <p><?php _e('I (Ben) visit the support forum everyday, so any questions you have will be answered there.', 'ignite'); ?></p>
            </div>
        <?php }
        elseif($active_tab == 'license'){ ?>
            <div class="content content-license">
                <h3><?php _e('Customize', 'ignite'); ?></h3>
                <p><?php _e('Customize your site with your logo, social profiles, and more with the built-in Customizer.', 'ignite'); ?></p>
                <p><a class="button-primary" href="customize.php"><?php _e('Use the customizer', 'ignite'); ?></a></p>
            </div>
        <?php } ?>
    </div>
<?php }