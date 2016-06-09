<?php
/*
Plugin Name: Voxpress
Plugin URI: https://bitbucket.org/ubivox/voxpress
Description: Integrates your Wordpress with Ubivox
Version: 1.1.5
Text Domain: voxpress
Author: Ubivox Developers
Author URI: https://www.ubivox.com
License: GPL2
*/

define("VOXPRESS_VERSION", "1.1.5");   // Set version for plugin

require "libs/IXR_Library.php";

require "api.php";                     // Ubivox API
require "ajax.php";                    // Ubivox ajax backend
require "archive.php";                 // Ubivox archive handling
require "urls.php";                    // Ubivox URLs

require "widgets/subscription.php";    // Ubivox subscription widget
require "widgets/unsubscription.php";  // Ubivox unsubscription widget
require "widgets/archive.php";         // Ubivox archive widget
require "widgets/control_panel.php";   // Ubivox control panel widget
require "widgets/verified_sender.php"; // Ubivox verified sender widget

require "woocommerce.php";             // Woocommerce integration

require "admin/init.php";

define(
    "UBIVOX_BASE_URL", get_option("uvx_api_url") ?
    preg_replace('#/xmlrpc/?$#', "", get_option("uvx_api_url")) : null
);

define("UBIVOX_API_CONFIGURED", (bool) get_option("uvx_api_url"));

define("UBIVOX_HAS_WOOCOMMERCE", in_array("woocommerce/woocommerce.php", apply_filters("active_plugins", get_option("active_plugins"))));


###############################################################################
# Widgets
###############################################################################

function ubivox_register_widgets() {
    register_widget("Ubivox_Subscription_Widget");
    register_widget("Ubivox_Unsubscription_Widget");
    register_widget("Ubivox_Archive_Widget");
    register_widget("Ubivox_Control_Panel_Widget");    
    register_widget("Ubivox_Verified_Sender_Widget");    
}

if (UBIVOX_API_CONFIGURED) {
    add_action("widgets_init", "ubivox_register_widgets");
}


###############################################################################
# Public assets
###############################################################################

    // Register public styles
    // ------------------------------------------------------------------
    if (!is_admin()) {
        wp_enqueue_style("ubivox-style-public", plugins_url("voxpress/styles/ubivox.public.css"), array(), VOXPRESS_VERSION, false);
        wp_enqueue_style("ubivox-style-public-ie8", plugins_url("voxpress/styles/ubivox.public.ie8.css"), array(), VOXPRESS_VERSION, false);
        
        global $wp_styles;
        $wp_styles->add_data( 'ubivox-style-public-ie8', 'conditional', 'lte IE 8' );
    }

    // Register public scripts
    // ------------------------------------------------------------------
    if (!is_admin()) {
        wp_enqueue_script("ubivox-public-script", plugins_url("voxpress/scripts/ubivox.public.js"), array('jquery', 'json2', 'jquery-ui-core', 'jquery-ui-position', 'jquery-effects-core', 'jquery-effects-drop'), VOXPRESS_VERSION, true);
        wp_enqueue_script("jquery-cookie", plugins_url("voxpress/libs/jquery.cookie.js"), array('jquery'), VOXPRESS_VERSION, true);
        

        wp_localize_script("ubivox-public-script", "uvx_settings", array( 
            "ajaxurl" => admin_url("admin-ajax.php"), 
            "account_url" => get_option("uvx_account_url")
        ));
    }


###############################################################################
# i18n
###############################################################################


function voxpress_i18n_init() {
     $plugin_dir = basename(dirname(__FILE__)) . "/languages/";
     load_plugin_textdomain( 'voxpress', false, $plugin_dir );
}
add_action('plugins_loaded', 'voxpress_i18n_init');




?>