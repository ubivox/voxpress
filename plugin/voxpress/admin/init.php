<?php

###############################################################################
# Menu configuration
###############################################################################

add_action("admin_menu", "uvx_setup_menu");

function uvx_setup_menu() {

    add_menu_page(
        "Newsletters", 
        "Newsletters",
        "manage_options",
        "voxpress",
        "uvx_lists_page",
        plugins_url("/voxpress/images/icon-admin.png"),
        3
    );    

    add_submenu_page(
        "voxpress", 
        "Lists",
        "Lists", 
        "manage_options", 
        "voxpress", 
        "uvx_lists_page"
    );

    add_submenu_page(
        "voxpress", 
        "Drafts",
        "Drafts", 
        "manage_options", 
        "voxpress_drafts", 
        "uvx_drafts_page"
    );

    add_submenu_page(
        "voxpress", 
        "Latest newsletters",
        "Latest newsletters", 
        "manage_options", 
        "voxpress_latest", 
        "uvx_latest_page"
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
        "Ubivox E-Commerce",
        "E-Commerce", 
        "manage_options", 
        "voxpress-ecommerce", 
        "uvx_ecommerce_page"
    );

    add_submenu_page(
        "voxpress", 
        "Ubivox data field mapping",
        "Data mapping", 
        "manage_options", 
        "voxpress-data", 
        "uvx_data_page"
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

function uvx_lists_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    if (check_missing_config()) return;

    require "lists.php";

}

function uvx_drafts_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    if (check_missing_config()) return;

    require "drafts.php";

}

function uvx_latest_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    if (check_missing_config()) return;

    require "latest.php";

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

    if (check_missing_config()) return;

    require "woocommerce.php";

}


function uvx_data_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    if (check_missing_config()) return;

    require "data.php";

}


function check_missing_config() {

    if (UBIVOX_API_CONFIGURED) {
        return false;
    }

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    require "missing_config.php";

    return true;

}

###############################################################################
# Admin assets
###############################################################################

    // Register admin styles
    // ------------------------------------------------------------------
    function uvx_register_admin_styles() {    
        wp_enqueue_style("simple-grid", plugins_url("voxpress/libs/simplegrid.css"), array(), $voxpress_version, false);
        wp_enqueue_style("ubivox-style-admin", plugins_url("voxpress/styles/ubivox.admin.css"), array(), $voxpress_version, false);
    }

    add_action("admin_enqueue_scripts", "uvx_register_admin_styles");


    // Register admin scripts
    // ------------------------------------------------------------------
    function uvx_register_admin_scripts() {    
        wp_enqueue_script("ubivox-admin", plugins_url("voxpress/scripts/ubivox.admin.js"), array( 'jquery', 'json' ), $voxpress_version, true);
    }

    add_action("admin_enqueue_scripts", "uvx_register_admin_scripts");
