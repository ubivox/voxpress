<?php

###############################################################################
# Menu configuration
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
        "Ubivox Settings",
        "Settings", 
        "manage_options", 
        "voxpress-options", 
        "uvx_options_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox WooCommerce Integration",
        "WooCommerce", 
        "manage_options", 
        "voxpress-woocommerce", 
        "uvx_woocommerce_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox Help & Support",
        "Help &amp; Support", 
        "manage_options", 
        "voxpress-help", 
        "uvx_help_page"
    );



}

function uvx_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    require "overview.php";

}

function uvx_options_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

    require "settings.php";

}


function uvx_help_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }

    require "help.php";

}


function uvx_woocommerce_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    require "woocommerce.php";

}


###############################################################################
# Admin assets
###############################################################################

    // Register admin styles
    // ------------------------------------------------------------------
    function uvx_register_admin_styles() {    
        wp_enqueue_style("ubivox-style-admin", plugins_url("voxpress/styles/ubivox.admin.css"), array(), $voxpress_version, false);
    }

    add_action("admin_enqueue_scripts", "uvx_register_admin_styles");


    // Register admin scripts
    // ------------------------------------------------------------------
    function uvx_register_admin_scripts() {    
        wp_enqueue_script("ubivox-admin", plugins_url("voxpress/scripts/ubivox.admin.js"), array( 'jquery', 'json' ), $voxpress_version, true);
    }

    add_action("admin_enqueue_scripts", "uvx_register_admin_scripts");


