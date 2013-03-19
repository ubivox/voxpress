<?php

    // Register admin styles
    function uvx_register_admin_styles() {    
        wp_register_style( 'ubivox-admin', plugins_url('voxpress/styles/ubivox.admin.css') );
        wp_enqueue_style('ubivox-admin');
    }

    if (isset($_GET['page']) && $_GET['page'] == 'voxpress') {
        add_action( 'admin_enqueue_scripts', 'uvx_register_admin_styles' );
    }



?>