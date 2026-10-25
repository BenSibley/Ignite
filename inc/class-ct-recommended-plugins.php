<?php
/**
 * CT Recommended Plugins
 *
 * Shows a dismissible admin notice recommending plugins from WordPress.org, with
 * Install/Activate buttons that hand off to WordPress core's own plugin installer.
 * Replaces TGM Plugin Activation.
 *
 * This file is copied into each theme by _shared/sync.sh, which swaps the
 * 'ignite' placeholder for the theme's text domain. Edit the master copy
 * in _shared/recommended-plugins, not the copies inside the themes.
 *
 * Usage (instantiate on admin_init so translations are loaded):
 *
 *     new CT_Recommended_Plugins(array(
 *         'id'      => 'ct-theme-slug',
 *         'plugins' => array(
 *             array('name' => 'Independent Analytics', 'slug' => 'independent-analytics'),
 *         ),
 *         'message' => __('Notice text.', 'theme-slug'),
 *     ));
 *
 * @version 1.0.0
 * @license GPL-2.0-or-later
 */

if (! class_exists('CT_Recommended_Plugins')) {
    class CT_Recommended_Plugins
    {
        const VERSION = '1.0.0';

        private $id;
        private $plugins;
        private $message;

        public function __construct($args)
        {
            $this->id      = sanitize_key($args['id']);
            $this->plugins = $args['plugins'];
            $this->message = $args['message'];

            add_action('admin_notices', array($this, 'render_notice'));
            add_action('wp_ajax_' . $this->ajax_action(), array($this, 'dismiss'));
        }

        public function render_notice()
        {
            if (get_user_meta(get_current_user_id(), $this->meta_key(), true)) {
                return;
            }

            // Don't compete with core's own install and update screens
            $screen = get_current_screen();
            if ($screen && in_array($screen->base, array('update', 'update-core', 'plugin-install'), true)) {
                return;
            }

            $buttons = array();
            foreach ($this->plugins as $plugin) {
                $button = $this->get_button($plugin);
                if ($button) {
                    $buttons[] = $button;
                }
            }

            // Nothing left to recommend, or the user can't act on it
            if (empty($buttons)) {
                return;
            }

            printf(
                '<div class="notice notice-info is-dismissible ct-recommended-plugins" data-action="%1$s" data-nonce="%2$s"><p>%3$s</p><p>%4$s</p></div>',
                esc_attr($this->ajax_action()),
                esc_attr(wp_create_nonce($this->ajax_action())),
                wp_kses_post($this->message),
                implode(' ', $buttons)
            );
            $this->print_script();
        }

        public function dismiss()
        {
            check_ajax_referer($this->ajax_action());
            update_user_meta(get_current_user_id(), $this->meta_key(), 1);
            wp_send_json_success();
        }

        // Returns the Install or Activate button for a plugin, or an empty string if it's already active
        private function get_button($plugin)
        {
            $file = $this->get_plugin_file($plugin['slug']);

            if ($file) {
                if (is_plugin_active($file) || ! current_user_can('activate_plugins')) {
                    return '';
                }
                $url   = wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=' . rawurlencode($file)), 'activate-plugin_' . $file);
                /* translators: %s: plugin name */
                $label = sprintf(__('Activate %s', 'ignite'), $plugin['name']);
            } else {
                if (! current_user_can('install_plugins')) {
                    return '';
                }
                $url   = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=' . rawurlencode($plugin['slug'])), 'install-plugin_' . $plugin['slug']);
                /* translators: %s: plugin name */
                $label = sprintf(__('Install %s', 'ignite'), $plugin['name']);
            }

            return '<a class="button button-primary" href="' . esc_url($url) . '">' . esc_html($label) . '</a>';
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

        // Same key TGMPA used, so users who dismissed the old notice don't see it again
        private function meta_key()
        {
            return 'tgmpa_dismissed_notice_' . $this->id;
        }

        private function ajax_action()
        {
            return 'ct_dismiss_recommended_plugins_' . str_replace('-', '_', $this->id);
        }
    }
}
