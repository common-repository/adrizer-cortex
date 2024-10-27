<?php

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class Cortex_Admin_Ajax
{
    // instance of this class.
    protected $plugin_slug = 'cortex';
    protected $ajax_data;
    protected $ajax_msg;


    public function __construct()
    {
        // retrieve all ajax string to localize
        $this->localize_strings();
        $this->init_hooks();
    }

    public function init_hooks()
    {
        // register backend ajax action
        add_action('wp_ajax_cortex_admin_ajax', array($this, 'cortex_admin_ajax'));
        // load admin ajax js script
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    public function ajax_response($success = true, $message = null, $content = null)
    {
        $response = array(
            'success' => $success,
            'message' => $message,
            'content' => $content
        );

        return $response;
    }

    public function cortex_check_nonce()
    {
        // retrieve nonce
        $nonce = (isset($_POST['nonce'])) ? $_POST['nonce'] : $_GET['nonce'];

        // nonce action for the grid
        $action = 'cortex_admin_nonce';

        // check ajax nounce
        if (!wp_verify_nonce($nonce, $action)) {
            // build response
            $response = $this->ajax_response(false, __('Sorry, an error occurred. Please refresh the page.', 'cortex'));
            // die and send json error response
            wp_send_json($response);
        }
    }

    public function cortex_admin_ajax()
    {
        // check the nonce
        $this->cortex_check_nonce();

        // retrieve data
        $this->ajax_data = (isset($_POST)) ? $_POST : $_GET;

        // retrieve function
        $func = $this->ajax_data['func'];

        switch ($func) {
            case 'cortex_save_settings':
                $response = $this->save_settings_callback();
                break;
            case 'cortex_reset_settings':
                $response = $this->save_settings_callback();
                break;
            default:
                $response = ajax_response(false, __('Sorry, an unknown error occurred...', 'cortex'), null);
                break;
        }

        // send json response and die
        wp_send_json($response);
    }

    public function save_settings_callback()
    {
        // retrieve data from jquery
        $setting_data = $this->ajax_data['setting_data'];

        cortex_update_options($setting_data);

        $template = false;
        // get new restore global settings panel
        if ($this->ajax_data['reset']) {
            ob_start();
            require_once('views/settings.php');
            $template = ob_get_clean();
        }

        $response = $this->ajax_response(true, $this->ajax_data['reset'], $template);
        return $response;
    }


    public function localize_strings()
    {
        $this->ajax_msg = array(
            'box_icons' => array(
                'before' => '<i class="tg-info-box-icon dashicons dashicons-admin-generic"></i>',
                'success' => '<i class="tg-info-box-icon dashicons dashicons-yes"></i>',
                'error' => '<i class="tg-info-box-icon dashicons dashicons-no-alt"></i>'
            ),
            'box_messages' => array(

                'cortex_save_settings' => array(
                    'before' => __('Saving settings ...', 'cortex'),
                    'success' => __('Settings saved!', 'cortex'),
                    'error' => __('Error occured while saving settings!', 'cortex')
                ),
                'cortex_reset_settings' => array(
                    'before' => __('Resetting plugin settings ...', 'cortex'),
                    'success' => __('Plugin settings reset!', 'cortex'),
                    'error' => __('Error occurred while resetting settings!', 'cortex')
                ),
            )
        );
    }

    public function admin_nonce()
    {
        return array(
            'url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('cortex_admin_nonce')
        );
    }

    public function enqueue_admin_scripts()
    {
        $screen = get_current_screen();

        // enqueue only in grid panel
        if (strpos($screen->id, $this->plugin_slug) !== false) {
            // merge nonce to translatable strings
            $strings = array_merge($this->admin_nonce(), $this->ajax_msg);

            // Use minified libraries if CORTEX_SCRIPT_DEBUG is turned off
            $suffix = (defined('CORTEX_SCRIPT_DEBUG') && CORTEX_SCRIPT_DEBUG) ? '' : '.min';

            // register and localize script for ajax methods
            wp_register_script('cortex-admin-ajax-scripts', CORTEX_PLUGIN_URL . 'admin/assets/js/cortex-admin-ajax' . $suffix . '.js', array(), CORTEX_VERSION, true);
            wp_enqueue_script('cortex-admin-ajax-scripts');

            wp_localize_script('cortex-admin-ajax-scripts', 'cortex_admin_global_var', $strings);
        }
    }
}

new Cortex_Admin_Ajax;
