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

require "api.php";                    // Ubivox API
require "javascript.php";             // Ubivox javascript
require "ajax.php";                   // Ubivox ajax backend

require "widgets/subscription.php";   // Ubivox subscription widget
require "widgets/unsubscription.php"; // Ubivox unsubscription widget
require "widgets/archive.php";        // Ubivox archive widget
require "widgets/control_panel.php";  // Ubivox control panel widget

define(
    "UBIVOX_BASE_URL", get_option("uvx_api_url") ?
    preg_replace('#/xmlrpc/?$#', "", get_option("uvx_api_url")) : null
);

###############################################################################
# Settings page
###############################################################################

add_action("admin_menu", "uvx_setup_menu");

function uvx_setup_menu() {
    add_options_page(
        "Ubivox Options", 
        "Ubivox", 
        "manage_options", 
        "voxpress-options", 
        "uvx_options_page");
}

function uvx_options_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

    require "settings.php";

}

###############################################################################
# Widgets
###############################################################################

function ubivox_register_widgets() {
    register_widget("Ubivox_Subscription_Widget");
    register_widget("Ubivox_Unsubscription_Widget");
    register_widget("Ubivox_Archive_Widget");
    register_widget("Ubivox_Control_Panel_Widget");
}

add_action("widgets_init", "ubivox_register_widgets");

?>