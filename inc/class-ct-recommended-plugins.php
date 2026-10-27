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
 * 'ignite' placeholder for the theme's text domain and copies the plugin
 * icons from _shared/recommended-plugins/icons into the theme. Edit the master
 * copies in _shared/recommended-plugins, not the copies inside the themes.
 *
 * Usage (instantiate on init: late enough for translations, early enough for admin_menu):
 *
 *     new CT_Recommended_Plugins(array(
 *         'id'      => 'ct-theme-slug',
 *         'plugins' => array(
 *             array(
 *                 'name'        => 'Independent Analytics',
 *                 'slug'        => 'independent-analytics',
 *                 // Follows "Name: " in the notice, so it starts lowercase
 *                 'description' => __('a short description.', 'theme-slug'),
 *             ),
 *         ),
 *     ));
 *
 * @version 1.4.0
 * @license GPL-2.0-or-later
 */

if (! class_exists('CT_Recommended_Plugins')) {
    class CT_Recommended_Plugins
    {
        const VERSION   = '1.4.0';
        const PAGE_SLUG = 'ct-recommended-plugins';

        // Where sync.sh puts the plugin icons inside each theme, named <slug>.svg
        const ICON_DIR = 'assets/images/recommended-plugins';

        // Plugins TGMPA recommended. Users who dismissed its notice already passed on these.
        private static $legacy_slugs = array('independent-analytics');

        // Plugins that get a "New" label over their icon in the notice
        private static $new_slugs = array('resolver-ai-chatbot');

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
            add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('admin_notices', array($this, 'render_notice'));
            add_action('wp_ajax_' . $this->ajax_action(), array($this, 'dismiss'));
        }

        // Thickbox powers core's plugin details window that "More Details" opens
        public function enqueue_scripts($hook_suffix)
        {
            if ($hook_suffix === 'appearance_page_' . self::PAGE_SLUG || $this->get_notice_plugins()) {
                add_thickbox();
            }
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
            <?php $this->print_styles(); ?>
            <div class="wrap ct-recommended-plugins-page">
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
                            <tr class="ct-recommended-plugin-<?php echo esc_attr($plugin['slug']); ?>">
                                <td>
                                    <div class="ct-recommended-plugins-page-name">
                                        <?php echo $this->get_icon_html($plugin); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in get_icon_html() ?>
                                        <strong><?php echo esc_html($plugin['name']); ?></strong>
                                    </div>
                                </td>
                                <td><?php echo esc_html(ucfirst($plugin['description'])); ?></td>
                                <td class="ct-recommended-plugins-page-actions">
                                    <?php
                                    // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in get_action() and get_details_link()
                                    echo $this->get_action($plugin) . ($this->get_status($plugin['slug']) !== 'unavailable' ? $this->get_details_link($plugin) : '');
                                    ?>
                                </td>
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

            $rows = '';
            foreach ($plugins as $plugin) {
                $rows .= '<div class="ct-recommended-plugin ct-recommended-plugin-' . esc_attr($plugin['slug']) . '">';
                $rows .= $this->get_icon_html($plugin);
                $rows .= '<div><p><strong>' . esc_html($plugin['name']) . ':</strong> ' . esc_html($plugin['description']) . '</p><p>' . $this->get_link($plugin) . $this->get_details_link($plugin) . '</p></div></div>';
            }

            printf(
                '<div class="notice notice-info is-dismissible ct-recommended-plugins" data-action="%1$s" data-nonce="%2$s"><p><strong>%3$s</strong></p><div class="ct-recommended-plugins-list">%4$s</div></div>',
                esc_attr($this->ajax_action()),
                esc_attr(wp_create_nonce($this->ajax_action())),
                /* translators: %s: theme name */
                esc_html(sprintf(__('The makers of the %s theme recommend these free plugins:', 'ignite'), $this->theme_name())),
                $rows
            );
            $this->print_styles();
            $this->print_script();
        }

        // The plugin's icon, with a "New" label for plugins in $new_slugs, or an empty string if it has no icon
        private function get_icon_html($plugin)
        {
            $icon = $this->get_icon_url($plugin['slug']);
            if (! $icon) {
                return '';
            }
            $html = '<span class="ct-recommended-plugin-icon"><img src="' . esc_url($icon) . '" alt="" width="64" height="64">';
            if (in_array($plugin['slug'], self::$new_slugs, true)) {
                $html .= '<span class="ct-recommended-plugin-new">' . esc_html__('New', 'ignite') . '</span>';
            }
            return $html . '</span>';
        }

        // Link that opens core's plugin details window. It needs plugin-install.php, so only users who can install get it.
        private function get_details_link($plugin)
        {
            if (! current_user_can('install_plugins')) {
                return '';
            }
            $url = self_admin_url('plugin-install.php?tab=plugin-information&plugin=' . rawurlencode($plugin['slug']) . '&TB_iframe=true&width=772&height=600');
            return '<a class="ct-more-details thickbox open-plugin-details-modal" href="' . esc_url($url) . '">' . esc_html__('More Details', 'ignite') . '</a>';
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

        // Returns the button (or status text) for a plugin on the Recommended Plugins page
        private function get_action($plugin)
        {
            $status = $this->get_status($plugin['slug']);
            if ($status === 'active') {
                return '<span class="button button-disabled">' . esc_html__('Active', 'ignite') . '</span>';
            }
            if ($status === 'unavailable') {
                return '<span class="button button-disabled">' . esc_html__('Coming soon', 'ignite') . '</span>';
            }

            $url = $this->get_action_url($plugin, $status);
            if (! $url) {
                return '';
            }
            $label = $status === 'activate'
                /* translators: %s: plugin name */
                ? sprintf(__('Activate %s', 'ignite'), $plugin['name'])
                /* translators: %s: plugin name */
                : sprintf(__('Install %s', 'ignite'), $plugin['name']);

            return '<a class="button button-primary" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        }

        // Returns the "Install Now" or "Activate Now" button for a plugin in the notice
        private function get_link($plugin)
        {
            $status = $this->get_status($plugin['slug']);
            $url    = $this->get_action_url($plugin, $status);
            if (! $url) {
                return '';
            }
            $label = $status === 'activate' ? __('Activate Now', 'ignite') : __('Install Now', 'ignite');

            return '<a class="button button-primary" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
        }

        // Core's nonced install or activate URL, or false if the user can't do that
        private function get_action_url($plugin, $status)
        {
            if ($status === 'activate' && current_user_can('activate_plugins')) {
                $file = $this->get_plugin_file($plugin['slug']);
                return wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=' . rawurlencode($file)), 'activate-plugin_' . $file);
            }
            if ($status === 'install' && current_user_can('install_plugins')) {
                return wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . rawurlencode($plugin['slug'])), 'install-plugin_' . $plugin['slug']);
            }
            return false;
        }

        // URL of the plugin's icon bundled with the theme (copied in by sync.sh), or an empty string if there isn't one
        private function get_icon_url($slug)
        {
            $path = self::ICON_DIR . '/' . $slug . '.svg';
            if (! file_exists(trailingslashit(get_template_directory()) . $path)) {
                return '';
            }
            return trailingslashit(get_template_directory_uri()) . $path;
        }

        // Asks WordPress.org whether a plugin exists, caching the answer for a day (an hour if the request failed)
        private function is_on_wporg($slug)
        {
            $key    = 'ct_rp_available_' . md5($slug);
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

        // Styles for the notice and the Recommended Plugins page
        private function print_styles()
        {
            ?>
            <style>
                .ct-recommended-plugins .ct-recommended-plugin {
                    margin: 0 0 12px;
                    padding: 4px 12px;
                    background: #f6f7f7;
                    border-radius: 4px;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }
                @media (min-width: 1300px) {
                    .ct-recommended-plugins .ct-recommended-plugins-list {
                        display: flex;
                        gap: 12px;
                    }
                    .ct-recommended-plugins .ct-recommended-plugin {
                        flex: 1 1 0;
                        min-width: 0;
                    }
                }
                .ct-recommended-plugin-icon {
                    position: relative;
                    flex-shrink: 0;
                }
                .ct-recommended-plugin-icon img {
                    display: block;
                    border-radius: 4px;
                }
                .ct-recommended-plugin-new {
                    position: absolute;
                    top: -8px;
                    left: -8px;
                    padding: 2px 6px;
                    background: #d63638;
                    color: #fff;
                    font-size: 10px;
                    font-weight: 600;
                    line-height: 1.4;
                    text-transform: uppercase;
                    border-radius: 3px;
                }
                /* Resolver's icon has a white background that blends into the box without a border */
                .ct-recommended-plugin-resolver-ai-chatbot .ct-recommended-plugin-icon img {
                    border: 1px solid #dcdcde;
                }
                .ct-recommended-plugins .ct-recommended-plugin .button {
                    min-height: 2px;
                    line-height: 1.3;
                    padding: 8px 12px;
                    margin-inline-end: 8px;
                }
                .ct-more-details {
                    text-decoration: none;
                }
                .ct-more-details:hover,
                .ct-more-details:focus {
                    text-decoration: underline;
                }
                .ct-recommended-plugins-page td {
                    vertical-align: middle;
                }
                .ct-recommended-plugins-page-name {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                }
                .ct-recommended-plugins-page-actions {
                    white-space: nowrap;
                }
                .ct-recommended-plugins-page-actions .button {
                    margin-inline-end: 8px;
                    vertical-align: middle;
                }
            </style>
            <?php
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
