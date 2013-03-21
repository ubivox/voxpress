<?php
/*
Plugin Name: Voxpress
Plugin URI: https://bitbucket.org/ubivox/voxpress
Description: Integrates your Wordpress with Ubivox
Version: 0.1
Author: Ubivox Developers
Author URI: https://www.ubivox.com
License: GPL2
*/

$voxpress_version = "0.1";            // Set version for plugin

require "api.php";                    // Ubivox API
require "ajax.php";                   // Ubivox ajax backend
require "archive.php";                // Ubivox archive handling
require "urls.php";                   // Ubivox URLs

require "widgets/subscription.php";   // Ubivox subscription widget
require "widgets/unsubscription.php"; // Ubivox unsubscription widget
require "widgets/archive.php";        // Ubivox archive widget
require "widgets/control_panel.php";  // Ubivox control panel widget

require "woocommerce.php";            // Woocommerce integration

require "admin/init.php";

define(
    "UBIVOX_BASE_URL", get_option("uvx_api_url") ?
    preg_replace('#/xmlrpc/?$#', "", get_option("uvx_api_url")) : null
);

define("UBIVOX_API_CONFIGURED", (bool) get_option("uvx_api_url"));

###############################################################################
# Widgets
###############################################################################

function ubivox_register_widgets() {
    register_widget("Ubivox_Subscription_Widget");
    register_widget("Ubivox_Unsubscription_Widget");
    register_widget("Ubivox_Archive_Widget");
    register_widget("Ubivox_Control_Panel_Widget");
}

if (UBIVOX_API_CONFIGURED) {
    add_action("widgets_init", "ubivox_register_widgets");
}

?>