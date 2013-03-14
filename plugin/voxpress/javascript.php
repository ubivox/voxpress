<?php

function ubivox_init() {

    wp_enqueue_script("ubivox-ajax", plugins_url("/voxpress/scripts/ubivox.js"), array("jquery", "json2"));
    wp_localize_script("ubivox-ajax", "UbivoxAjax", array("ajaxurl" => admin_url("admin-ajax.php")));

}

add_action("init", "ubivox_init");
