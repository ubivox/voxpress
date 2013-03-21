<?php

function ubivox_ajax_subscribe_handler() {

    header("Content-Type: application/json");

    $email_address = $_POST["email_address"];
    $list_id = $_POST["list_id"];
    $data = json_decode(stripslashes($_POST["data"]), true);

    $api = new UbivoxAPI();

    try {

        if (is_array($data) && count($data)) {

            $api->call("ubivox.create_subscription_with_data",
                       array($email_address, array($list_id), true, $data));

        } else {

            $api->call("ubivox.create_subscription",
                       array($email_address, array($list_id), true));

        }

        echo json_encode(array("status" => "ok"));

    } catch (UbivoxAPIError $e) {

        echo json_encode(array(
            "status" => "error",
            "message" => $e->getMessage(),
        ));

    }

    exit;

}

add_action("wp_ajax_ubivox_subscribe", "ubivox_ajax_subscribe_handler");
add_action("wp_ajax_nopriv_ubivox_subscribe", "ubivox_ajax_subscribe_handler");

function ubivox_ajax_unsubscribe_handler() {

    header("Content-Type: application/json");

    $email_address = $_POST["email_address"];
    $list_id = $_POST["list_id"];

    $api = new UbivoxAPI();

    try {

        if ($list_id == "ALL") {

            $api->call("ubivox.cancel_all_subscriptions",
                       array($email_address));

        } else {

            $api->call("ubivox.cancel_subscription",
                       array($email_address, array($list_id)));

        }

        echo json_encode(array("status" => "ok"));

    } catch (UbivoxAPIError $e) {

        echo json_encode(array(
            "status" => "error",
            "message" => $e->getMessage(),
        ));

    }

    exit;

}

add_action("wp_ajax_ubivox_unsubscribe", "ubivox_ajax_unsubscribe_handler");
add_action("wp_ajax_nopriv_ubivox_unsubscribe", "ubivox_ajax_unsubscribe_handler");

