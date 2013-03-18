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
require "archive.php";                // Ubivox archive handling
require "urls.php";                   // Ubivox URLs

require "widgets/subscription.php";   // Ubivox subscription widget
require "widgets/unsubscription.php"; // Ubivox unsubscription widget
require "widgets/archive.php";        // Ubivox archive widget
require "widgets/control_panel.php";  // Ubivox control panel widget

require "woocommerce.php";            // Woocommerce integration

define(
    "UBIVOX_BASE_URL", get_option("uvx_api_url") ?
    preg_replace('#/xmlrpc/?$#', "", get_option("uvx_api_url")) : null
);

###############################################################################
# Settings page
###############################################################################

add_action("admin_menu", "uvx_setup_menu");

function uvx_setup_menu() {

    add_menu_page(
        "Ubivox", 
        "Ubivox",
        "manage_options",
        "voxpress",
        "uvx_page",
        null,
        30
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox Overview",
        "Overview", 
        "manage_options", 
        "voxpress", 
        "uvx_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox Options",
        "Options", 
        "manage_options", 
        "voxpress-options", 
        "uvx_options_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox Statistics",
        "Statistics", 
        "manage_options", 
        "voxpress-statistics", 
        "uvx_statistics_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox WooCommerce Integration",
        "WooCommerce", 
        "manage_options", 
        "voxpress-woocommerce", 
        "uvx_woocommerce_page"
    );

}

function uvx_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

    require "admin/overview.php";

}

function uvx_options_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

    require "admin/settings.php";

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