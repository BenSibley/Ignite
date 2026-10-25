<?php
/**
 * CT Recommended Plugins
 *
 * Recommends plugins from WordPress.org with a dismissible admin notice and an
 * Appearance > Recommended Plugins page. Both use Install/Activate buttons that
 * hand off to WordPress core's own plugin installer. Replaces TGM Plugin Activation.
 *
 * Plugins that aren't on WordPress.org yet are left out of the notice and shown
 * as "Coming soon" on the page, so a plugin can be listed before it goes live.
 *
 * This file is copied into each theme by _shared/sync.sh, which swaps the
 * 'ignite' placeholder for the theme's text domain. Edit the master copy
 * in _shared/recommended-plugins, not the copies inside the themes.
 *
 * Usage (instantiate on init: late enough for translations, early enough for admin_menu):
 *
 *     new CT_Recommended_Plugins(array(
 *         'id'      => 'ct-theme-slug',
 *         'plugins' => array(
 *             array(
 *                 'name'        => 'Independent Analytics',
 *                 'slug'        => 'independent-analytics',
 *                 'description' => __('Plugin description.', 'theme-slug'),
 *             ),
 *         ),
 *     ));
 *
 * @version 1.1.0
 * @license GPL-2.0-or-later
 */

if (! class_exists('CT_Recommended_Plugins')) {
    class CT_Recommended_Plugins
    {
        const VERSION   = '1.1.0';
        const PAGE_SLUG = 'ct-recommended-plugins';

        // Plugins TGMPA recommended. Users who dismissed its notice already passed on these.
        private static $legacy_slugs = array('independent-analytics');

        private $id;
        private $plugins;

        public function __construct($args)
        {
            $this->id      = sanitize_key($args['id']);
            $this->plugins = $args['plugins'];

            if (! is_admin()) {
                return;
            }
            add_action('admin_menu', array($this, 'add_page'));
            add_action('admin_notices', array($this, 'render_notice'));
            add_action('wp_ajax_' . $this->ajax_action(), array($this, 'dismiss'));
        }

        public function add_page()
        {
            add_theme_page(
                __('Recommended Plugins', 'ignite'),
                __('Recommended Plugins', 'ignite'),
                'activate_plugins',
                self::PAGE_SLUG,
                array($this, 'render_page')
            );
        }

        public function render_page()
        {
            ?>
            <div class="wrap">
                <h1><?php esc_html_e('Recommended Plugins', 'ignite'); ?></h1>
                <p>
                    <?php
                    /* translators: %s: theme name */
                    printf(esc_html__('Free plugins from the makers of the %s theme.', 'ignite'), esc_html($this->theme_name()));
                    ?>
                </p>
                <table class="widefat striped">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Plugin', 'ignite'); ?></th>
                            <th scope="col"><?php esc_html_e('Description', 'ignite'); ?></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($this->plugins as $plugin) : ?>
                            <tr>
                                <td><strong><?php echo esc_html($plugin['name']); ?></strong></td>
                                <td><?php echo esc_html($plugin['description']); ?></td>
                                <td><?php echo $this->get_action($plugin); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in get_action() ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php
        }

        public function render_notice()
        {
            // Don't compete with core's install and update screens, or repeat what our own page shows
            $screen = get_current_screen();
            if ($screen && (in_array($screen->base, array('update', 'update-core', 'plugin-install'), true) || $screen->id === 'appearance_page_' . self::PAGE_SLUG)) {
                return;
            }

            $plugins = $this->get_notice_plugins();
            if (empty($plugins)) {
                return;
            }

            $buttons = array();
            foreach ($plugins as $plugin) {
                $buttons[] = $this->get_action($plugin);
            }
            $page_link = '<a href="' . esc_url(admin_url('themes.php?page=' . self::PAGE_SLUG)) . '">' . esc_html__('See all recommended plugins', 'ignite') . '</a>';

            printf(
                '<div class="notice notice-info is-dismissible ct-recommended-plugins" data-action="%1$s" data-nonce="%2$s"><p>%3$s</p><p>%4$s</p></div>',
                esc_attr($this->ajax_action()),
                esc_attr(wp_create_nonce($this->ajax_action())),
                /* translators: %s: theme name */
                esc_html(sprintf(__('The makers of the %s theme recommend these free plugins:', 'ignite'), $this->theme_name())),
                implode(' ', $buttons) . ' ' . $page_link
            );
            $this->print_script();
        }

        public function dismiss()
        {
            check_ajax_referer($this->ajax_action());

            // Dismiss only what the notice was showing, so plugins added later still get announced
            $dismissed = $this->get_dismissed();
            foreach ($this->get_notice_plugins() as $plugin) {
                $dismissed[] = $plugin['slug'];
            }
            update_user_meta(get_current_user_id(), $this->meta_key(), array_values(array_unique($dismissed)));
            wp_send_json_success();
        }

        // Plugins the notice should mention: on WordPress.org, not dismissed, and something the user can act on
        private function get_notice_plugins()
        {
            $dismissed = $this->get_dismissed();
            $plugins   = array();
            foreach ($this->plugins as $plugin) {
                if (in_array($plugin['slug'], $dismissed, true)) {
                    continue;
                }
                $status = $this->get_status($plugin['slug']);
                if (($status === 'install' && current_user_can('install_plugins')) || ($status === 'activate' && current_user_can('activate_plugins'))) {
                    $plugins[] = $plugin;
                }
            }
            return $plugins;
        }

        // Returns 'active', 'activate', 'install', or 'unavailable'
        private function get_status($slug)
        {
            $file = $this->get_plugin_file($slug);
            if ($file) {
                return is_plugin_active($file) ? 'active' : 'activate';
            }
            return $this->is_on_wporg($slug) ? 'install' : 'unavailable';
        }

        // Returns the button (or status text) for a plugin
        private function get_action($plugin)
        {
            switch ($this->get_status($plugin['slug'])) {
                case 'active':
                    return '<span class="button button-disabled">' . esc_html__('Active', 'ignite') . '</span>';
                case 'unavailable':
                    return '<span class="button button-disabled">' . esc_html__('Coming soon', 'ignite') . '</span>';
                case 'activate':
                    if (! current_user_can('activate_plugins')) {
                        return '';
                    }
                    $file  = $this->get_plugin_file($plugin['slug']);
                    $url   = wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=' . rawurlencode($file)), 'activate-plugin_' . $file);
                    /* translators: %s: plugin name */
                    $label = sprintf(__('Activate %s', 'ignite'), $plugin['name']);
                    break;
                default:
                    if (! current_user_can('install_plugins')) {
                        return '';
                    }
                    $url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . rawurlencode($plugin['slug'])), 'install-plugin_' . $plugin['slug']);
                    /* translators: %s: plugin name */
                    $label = sprintf(__('Install %s', 'ignite'), $plugin['name']);
            }

            return '<a class="button button-primary" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        }

        // Asks WordPress.org whether a plugin exists, caching the answer for a day (an hour if the request failed)
        private function is_on_wporg($slug)
        {
            $key    = 'ct_rp_wporg_' . md5($slug);
            $cached = get_transient($key);
            if ($cached !== false) {
                return $cached === 'yes';
            }

            if (! function_exists('plugins_api')) {
                require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
            }
            $info = plugins_api('plugin_information', array(
                'slug'   => $slug,
                'fields' => array('sections' => false, 'short_description' => false),
            ));

            if (is_wp_error($info)) {
                // "Plugin not found" means it isn't live yet; anything else is a connection problem worth retrying sooner
                $not_found = strpos($info->get_error_message(), 'not found') !== false;
                set_transient($key, 'no', $not_found ? DAY_IN_SECONDS : HOUR_IN_SECONDS);
                return false;
            }
            set_transient($key, 'yes', DAY_IN_SECONDS);
            return true;
        }

        // Finds an installed plugin's main file (e.g. "independent-analytics/iawp.php") from its folder slug
        private function get_plugin_file($slug)
        {
            if (! function_exists('get_plugins')) {
                require_once ABSPATH . 'wp-admin/includes/plugin.php';
            }
            foreach (array_keys(get_plugins()) as $file) {
                if (strpos($file, $slug . '/') === 0) {
                    return $file;
                }
            }
            return false;
        }

        private function get_dismissed()
        {
            $user_id   = get_current_user_id();
            $dismissed = get_user_meta($user_id, $this->meta_key(), true);
            if (! is_array($dismissed)) {
                $dismissed = array();
            }
            if (get_user_meta($user_id, 'tgmpa_dismissed_notice_' . $this->id, true)) {
                $dismissed = array_merge($dismissed, self::$legacy_slugs);
            }
            return $dismissed;
        }

        // Saves the dismissal when the notice's close button is clicked
        private function print_script()
        {
            ?>
            <script>
                jQuery(function ($) {
                    $(document).on('click', '.ct-recommended-plugins .notice-dismiss', function () {
                        var notice = $(this).closest('.ct-recommended-plugins');
                        $.post(ajaxurl, {action: notice.data('action'), _ajax_nonce: notice.data('nonce')});
                    });
                });
            </script>
            <?php
        }

        // Uses the parent theme's name, so child themes still say e.g. "Apex"
        private function theme_name()
        {
            return wp_get_theme(get_template())->get('Name');
        }

        private function meta_key()
        {
            return 'ct_recommended_plugins_dismissed_' . $this->id;
        }

        private function ajax_action()
        {
            return 'ct_dismiss_recommended_plugins_' . str_replace('-', '_', $this->id);
        }
    }
}
