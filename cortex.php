<?php
/**
 * Plugin Name:  Cortex
 * Plugin URI:   https://adrizer.com
 * Description:  Adds the Cortex tracking code to article pages.
 * Version:      1.0.3
 * Author:       Adrizer
 * Author URI:   https://adrizer.com
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */

// exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('Cortex')) :

    // Cortex
    final class Cortex
    {

        /** singleton *************************************************************/

        private static $instance;

        // instantiate Cortex
        public static function instance()
        {
            if (!isset(self::$instance) && !(self::$instance instanceof Cortex)) {
                self::$instance = new Cortex;

                self::$instance->setup_constants();

                add_action('plugins_loaded', array(self::$instance, 'includes'));

                self::$instance->hooks();
            }
            return self::$instance;
        }

        // throw error on object clone
        public function __clone()
        {
            // cloning instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'cortex'), '1.6');
        }

        // disable unserializing of the class
        public function __wakeup()
        {
            // unserializing instances of the class is forbidden
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'cortex'), '1.6');
        }

        // setup plugin constants
        private function setup_constants()
        {
            // plugin version
            if (!defined('CORTEX_VERSION')) {
                define('CORTEX_VERSION', '1.0.3');
            }

            // plugin folder path
            if (!defined('CORTEX_PLUGIN_DIR')) {
                define('CORTEX_PLUGIN_DIR', plugin_dir_path(__FILE__));
            }

            // plugin folder URL
            if (!defined('CORTEX_PLUGIN_URL')) {
                define('CORTEX_PLUGIN_URL', plugin_dir_url(__FILE__));
            }

            // plugin root file
            if (!defined('CORTEX_PLUGIN_FILE')) {
                define('CORTEX_PLUGIN_FILE', __FILE__);
            }

            // setup debug constants
            $this->setup_debug_constants();
        }

        private function setup_debug_constants()
        {
            $enable_debug = false;

            $settings = get_option('cortex_settings');

            if ($settings && isset($settings['cortex_enable_debug']) && $settings['cortex_enable_debug'] == "true") {
                $enable_debug = true;
            }

            // Enable script debugging
            if (!defined('CORTEX_SCRIPT_DEBUG')) {
                define('CORTEX_SCRIPT_DEBUG', $enable_debug);
            }

            // Minified JS file name suffix
            if (!defined('CORTEX_JS_SUFFIX')) {
                if ($enable_debug) {
                    define('CORTEX_JS_SUFFIX', '');
                } else {
                    define('CORTEX_JS_SUFFIX', '.min');
                }
            }
        }

        // include required files
        public function includes()
        {
            if (is_admin()) {
                require_once CORTEX_PLUGIN_DIR . 'admin/admin-init.php';
            }
            require_once CORTEX_PLUGIN_DIR . 'includes/helper-functions.php';
        }

         // setup the default hooks and actions
        private function hooks()
        {
            // enqueue header scripts
            add_action('wp_head', array(&$this, 'enqueue_header_scripts'), 1, 1);

            // enqueue body scripts
            add_action('wp_footer', array(&$this, 'enqueue_body_scripts'), 1, 100);

            // enqueue footer scripts
            add_action('wp_footer', array(&$this, 'enqueue_footer_scripts'), 1, 1);

            // add settings link
            $plugin = plugin_basename(__FILE__);
            add_filter("plugin_action_links_$plugin", array(&$this,'add_settings_link'));

            // clean url
            add_filter('clean_url', array(&$this, 'add_attributes_to_tracking_script_src'), 1, 3);
        }

        // enqueue header scripts
        public function enqueue_header_scripts()
        {
            global $post;

            // return if $post is not set
            if (!isset($post) || is_null($post)) {
                return;
            }

            // get the slug of the current post
            $post_slug = $post->post_name;

            // get cortex tracking
            $cortexTracking = cortex_get_option("cortex_tracking");

            // get enable test mode
            $cortexEnableTestMode = cortex_get_option("cortex_enable_test_mode");

            // get cortex test page
            $cortexTestPage = cortex_get_option("cortex_test_page");

            // custom tracking head
            $cortexCustomTrackingHead = cortex_get_option("cortex_custom_tracking_head");
            $cortexCustomTrackingHead = '<script type="text/javascript"></script>' . $cortexCustomTrackingHead;

            // only add tracking script if user opts in
            if ($cortexTracking == 'true') {
                // if test mode is enabled, only enqueue script on test page
                if ($cortexEnableTestMode == 'true') {
                    if ($cortexTestPage == $post_slug) {
                        // enqueue track.min.js
                        wp_register_script('adrizer.js', "//run.adrizer.com/track.min.js");
                        wp_enqueue_script('adrizer.js');

                        // enqueue custom tracking
                        wp_add_inline_script('adrizer.js', $cortexCustomTrackingHead, 'after');
                    }
                } else {
                    // enqueue track.min.js
                    wp_register_script('adrizer.js', "//run.adrizer.com/track.min.js");
                    wp_enqueue_script('adrizer.js');

                    // enqueue custom tracking
                    wp_add_inline_script('adrizer.js', $cortexCustomTrackingHead, 'after');
                }
            }
        }

        // enqueue body scripts
        public function enqueue_body_scripts()
        {
            global $post;

            // return if $post is not set
            if (!isset($post) || is_null($post)) {
                return;
            }

            // get the slug of the current post
            $post_slug = $post->post_name;

            // get cortex tracking
            $cortexTracking = cortex_get_option("cortex_tracking");

            // get enable test mode
            $cortexEnableTestMode = cortex_get_option("cortex_enable_test_mode");

            // get cortex test page
            $cortexTestPage = cortex_get_option("cortex_test_page");

            // custom tracking body
            $cortexCustomTrackingBody = cortex_get_option("cortex_custom_tracking_body");
            $cortexCustomTrackingBody = '<script type="text/javascript"></script>' . $cortexCustomTrackingBody;

            // only add tracking script if user opts in
            if ($cortexTracking == 'true') {
                // if test mode is enabled, only enqueue script on test page
                if ($cortexEnableTestMode == 'true') {
                    if ($cortexTestPage == $post_slug) {
                        // enqueue custom tracking
                        wp_register_script('body.js', ' ');
                        wp_enqueue_script('body.js');

                        // enqueue custom tracking
                        wp_add_inline_script('body.js', $cortexCustomTrackingBody, 'after');
                    }
                } else {
                    // enqueue custom tracking
                    wp_register_script('body.js', ' ');
                    wp_enqueue_script('body.js');

                    // enqueue custom tracking
                    wp_add_inline_script('body.js', $cortexCustomTrackingBody, 'after');
                }
            }
        }

        // enqueue footer scripts
        public function enqueue_footer_scripts()
        {
            global $post;

            // return if $post is not set
            if (!isset($post) || is_null($post)) {
                return;
            }

            // get the slug of the current post
            $post_slug = $post->post_name;

            // get cortex tracking
            $cortexTracking = cortex_get_option("cortex_tracking");

            // get enable test mode
            $cortexEnableTestMode = cortex_get_option("cortex_enable_test_mode");

            // get cortex test page
            $cortexTestPage = cortex_get_option("cortex_test_page");

            // custom tracking foot
            $cortexCustomTrackingFoot = cortex_get_option("cortex_custom_tracking_foot");
            $cortexCustomTrackingFoot = '<script type="text/javascript"></script>' . $cortexCustomTrackingFoot;

            // only add tracking script if user opts in
            if ($cortexTracking == 'true') {
                // if test mode is enabled, only enqueue script on test page
                if ($cortexEnableTestMode == 'true') {
                    if ($cortexTestPage == $post_slug) {
                        // enqueue custom tracking
                        wp_register_script('footer.js', ' ');
                        wp_enqueue_script('footer.js');

                        // enqueue custom tracking
                        wp_add_inline_script('footer.js', $cortexCustomTrackingFoot, 'after');
                    }
                } else {
                    // enqueue custom tracking
                    wp_register_script('footer.js', ' ');
                    wp_enqueue_script('footer.js');

                    // enqueue custom tracking
                    wp_add_inline_script('footer.js', $cortexCustomTrackingFoot, 'after');
                }
            }
        }

        // get organic campaign id
        public function get_organic_campaign_id()
        {
            // get organic campaign id
            $organicCampaignId = cortex_get_option("cortex_organic_campaign_id");

            // return the organic campaign id
            return $organicCampaignId;
        }

        // add attributes to tracking script src
        public function add_attributes_to_tracking_script_src($good_protocol_url, $original_url, $_context)
        {
            // only run this for the tracking script
            if ((strpos($original_url, 'track.min.js') !== false)) {
                // remove the filter
                remove_filter('clean_url', array(&$this, 'add_attributes_to_tracking_script_src'), 1, 3);

                // parse the url
                $url_parts = parse_url($good_protocol_url);

                // get the organic campaign id
                $organicCampaignId = $this->get_organic_campaign_id();

                // return the modified src
                if (isset($organicCampaignId) && !is_null($organicCampaignId) && $organicCampaignId != '') {
                    return '//' . $url_parts['host'] . $url_parts['path'] . "' id='ADRIZER_JS' data-organic-campaing-id='{$organicCampaignId}";
                } else {
                    return '//' . $url_parts['host'] . $url_parts['path'] . "' id='ADRIZER_JS";
                }
            }
            return $good_protocol_url;
        }

        // add settings link
        public function add_settings_link($links)
        {
            $settings_link = '<a href="options-general.php?page=cortex_settings">' . __('Settings') . '</a>';
            array_push($links, $settings_link);
            return $links;
        }
    }

endif; // end if class_exists check


// instantiate cortex instance
function Cortex()
{
    return Cortex::instance();
}

// get Cortex running
Cortex();
