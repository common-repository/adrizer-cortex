<?php

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Cortex_Admin
{
    protected $plugin_slug = 'cortex_settings';

    public function __construct()
    {
        $this->includes();
        $this->init_hooks();
    }

    public function includes()
    {
        // load class admin ajax function
        require_once(CORTEX_PLUGIN_DIR . '/admin/admin-ajax.php');
    }

    public function init_hooks()
    {
        // build admin menu/pages
        add_action('admin_menu', array($this, 'add_plugin_admin_menu'));

        // load admin style sheet and JavaScript.
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        add_action('current_screen', array($this, 'remove_admin_notices'));
    }

    public function remove_admin_notices($screen)
    {
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            add_action('admin_notices', array(&$this, 'remove_notices_start'));
            add_action('admin_notices', array(&$this, 'remove_notices_end'), 999);
        }
    }

    public function remove_notices_start()
    {
        // Turn on output buffering
        ob_start();
    }

    public function remove_notices_end()
    {
        // get current buffer contents and delete current output buffer
        $content = ob_get_contents();
        ob_clean();
    }

    public function add_plugin_admin_menu()
    {
        // add plugin settings submenu page
        add_submenu_page('options-general.php',
          __('AdRizer Settings'),
          __('AdRizer Settings'),
          'manage_options',
          'cortex_settings',
          array(&$this, 'display_settings_page')
        );
    }

    public function display_settings_page()
    {
        require_once('views/admin-header.php');
        require_once('views/admin-banner.php');
        require_once('views/settings.php');
        require_once('views/admin-footer.php');
    }


    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('cortex-admin-styles');

        // get current admin screen
        $screen = get_current_screen();

        // Use minified libraries if CORTEX_SCRIPT_DEBUG is turned off
        $suffix = (defined('CORTEX_SCRIPT_DEBUG') && CORTEX_SCRIPT_DEBUG) ? '' : '.min';

        // if screen is a part of Cortex settings page
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            wp_enqueue_style('cabin-font', 'https://fonts.googleapis.com/css?family=Cabin', false);

            wp_enqueue_script('jquery-ui-datepicker');

            wp_enqueue_script('wp-color-picker');
            wp_enqueue_style('wp-color-picker');

            wp_register_script('cortex-admin-scripts', CORTEX_PLUGIN_URL . 'admin/assets/js/cortex-admin' . $suffix . '.js', array(), CORTEX_VERSION, true);
            wp_enqueue_script('cortex-admin-scripts');

            wp_register_style('cortex-admin-elements-styles', CORTEX_PLUGIN_URL . 'admin/assets/css/cortex-elements.css', array(), CORTEX_VERSION);
            wp_enqueue_style('cortex-admin-elements-styles');

            wp_register_style('cortex-admin-page-styles', CORTEX_PLUGIN_URL . 'admin/assets/css/cortex-admin-page.css', array(), CORTEX_VERSION);
            wp_enqueue_style('cortex-admin-page-styles');
        }
    }
}

new Cortex_Admin;
