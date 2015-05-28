<?php

###############################################################################
# Menu configuration
###############################################################################

add_action("admin_menu", "uvx_setup_menu");

function uvx_setup_menu() {

    add_menu_page(
        "Voxpress", 
        "Voxpress",
        "manage_options",
        "voxpress",
        "uvx_latest_page",
        "dashicons-email-alt"
    );    

    add_submenu_page(
        "voxpress", 
        "Published",
        "Published", 
        "manage_options", 
        "voxpress", 
        "uvx_latest_page"
    );

    add_submenu_page(
        "voxpress", 
        "Lists",
        "Lists", 
        "manage_options", 
        "voxpress_list", 
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
        "Ubivox Settings",
        "Settings", 
        "manage_options", 
        "voxpress-options", 
        "uvx_options_page"
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




function uvx_ecommerce_page() {

    if (!current_user_can("manage_options")) {
        wp_die(__(
            "You do not have sufficient permissions to access this page."
        ));
    }    

    if (check_missing_config()) return;

    require "ecommerce.php";

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
        wp_enqueue_style("ubivox-style-admin", plugins_url("voxpress/styles/ubivox.admin.css"), array(), VOXPRESS_VERSION, false);
    }

    add_action("admin_enqueue_scripts", "uvx_register_admin_styles");

