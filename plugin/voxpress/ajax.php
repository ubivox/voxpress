<?php

function ubivox_ajax_request_handler() {

    header("Content-Type: application/json");

    $email_address = $_POST["email_address"];
    $list_id = $_POST["list_id"];
    $data = json_decode(stripslashes($_POST["data"]), true);

    $api = new UbivoxAPI();

    try {

        $api->call("ubivox.create_subscription_with_data",
                   array($email_address, array($list_id), true, $data));

            echo json_encode(array("status" => "ok"));

    } catch (UbivoxAPIError $e) {

        echo json_encode(array(
            "status" => "error",
            "message" => $e->getMessage(),
        ));

    }

    exit;

}

add_action("wp_ajax_ubivox_subscribe", "ubivox_ajax_request_handler");
add_action("wp_ajax_nopriv_ubivox_subscribe", "ubivox_ajax_request_handler");

