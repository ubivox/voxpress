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

    if (isset($_GET["page"]) && substr($_GET["page"], 0, 8) == "voxpress") {
        add_action("admin_enqueue_scripts", "uvx_register_admin_styles");
    }


    // Register admin scripts
    // ------------------------------------------------------------------
    function uvx_register_admin_scripts() {    
        wp_enqueue_script("ubivox-admin", plugins_url("voxpress/scripts/ubivox.admin.js"), array( 'jquery', 'json' ), $voxpress_version, true);
    }

    if (isset($_GET["page"]) && substr($_GET["page"], 0, 8) == "voxpress") {
        add_action("admin_enqueue_scripts", "uvx_register_admin_scripts");
    }


###############################################################################
# Public assets
###############################################################################

    // Register public styles
    // ------------------------------------------------------------------
    if (!is_admin()) {
        wp_enqueue_style("ubivox-style-public", plugins_url("voxpress/styles/ubivox.public.css"), array(), $voxpress_version, false);
        wp_enqueue_style("ubivox-style-public-ie8", plugins_url("voxpress/styles/ubivox.public.ie8.css"), array(), $voxpress_version, false);
        
        global $wp_styles;
        $wp_styles->add_data( 'ubivox-style-public-ie8', 'conditional', 'lte IE 8' );
    }

    // Register public scripts
    // ------------------------------------------------------------------
    if (!is_admin()) {
        wp_enqueue_script("ubivox-public-script", plugins_url("voxpress/scripts/ubivox.public.js"), array( 'jquery', 'json2', 'jquery-ui-core', 'jquery-ui-position'), $voxpress_version, true);
        

        wp_localize_script("ubivox-public-script", "ubivox_settings", array( 
            "ajaxurl" => admin_url("admin-ajax.php"), 
            "account_url" => get_option("uvx_account_url")
        ));
    }



