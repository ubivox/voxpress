<?php

function ubivox_wc_user_data($user_id) {

    $data = array();
    $mapping = get_option("ubivox_data_field_mapping", array());
    $meta = get_user_meta($user_id);

    foreach ($mapping as $ubivox_key => $wc_key) {
        $data[$ubivox_key] = $meta[$wc_key][0];
    }

    return $data;

}

function ubivox_create_subscription($email, $maillist_id) {

    $data = ubivox_wc_user_data(get_current_user_id());

    $api = new UbivoxAPI();

    try {
        $api->call("ubivox.create_subscription", array($email, array($maillist_id), true));
    } catch (UbivoxAPIError $e) {
        # invalid e-mail address
        # already subscribed
        # invalid mailing list
        # opt-in not configured
        return;
    }

    try {
        $api->call("ubivox.set_subscriber_data", array($email, $data));
    } catch (UbivoxAPIError $e) {
        # invalid e-mail address
        # invalid data field
        return;
    }

}

function ubivox_wc_subscription_checkbox() {

    $maillist_id = get_option("ubivox_ecommerce_maillist_id", 0);
    if (!$maillist_id) return;

    woocommerce_form_field(
        "ux_subscribe", 
        array("type" => "checkbox", "class" => array("form-row-wide"), "label" => get_option("ubivox_ecommerce_subscribe_label", "Subscribe to our newsletter?")), 
        get_option("ubivox_ecommerce_subscribe_initial", 0)
    );

    echo "<div class=\"clear\"></div>";

}

function ubivox_wc_subscription_process($order_id, $data) {

    $maillist_id = get_option("ubivox_ecommerce_maillist_id", 0);
    if (!$maillist_id) return;

    if (!isset($_POST["ux_subscribe"])) {
        return;
    }

    ubivox_create_subscription($data["billing_email"], $maillist_id);

}

function ubivox_wc_subscription_process_ppe($order) {

    $maillist_id = get_option("ubivox_ecommerce_maillist_id", 0);
    if (!$maillist_id) return;

    if (!isset($_POST["ux_subscribe"])) {
        return;
    }

    ubivox_create_subscription($order->billing_email, $maillist_id);

    $order->add_order_note(__("Subscription created for user through PayPal Express page.", "voxpress"));

}

function ubivox_wc_subscription_process_register($user_login, $user_email, $errors) {

    $maillist_id = get_option("ubivox_ecommerce_maillist_id", 0);
    if (!$maillist_id) return;

    if (!isset($_POST["ux_subscribe"])) {
        return;
    }

    ubivox_create_subscription($user_email, $maillist_id);

}

function ubivox_wc_sales_tracking($order_id) {

    $target_id = get_option("ubivox_ecommerce_target_id", 0);
    if (!$target_id) return;

    $order = new WC_Order($order_id);

    echo '<img src="'.UBIVOX_BASE_URL.'/target/'.$target_id.'/?'.
        'st_revenue='.$order->get_total().'&amp;'.
        'st_quantity='.$order->get_item_count().'&amp;'.
        'st_currency='.strtoupper(get_woocommerce_currency()).'&amp;'.
        'st_reference='.$order_id.'" height="1" width="1">';

}

if (UBIVOX_HAS_WOOCOMMERCE) {

    add_action("woocommerce_checkout_after_customer_details", "ubivox_wc_subscription_checkbox", 5);
    add_action("woocommerce_ppe_checkout_order_review", "ubivox_wc_subscription_checkbox", 5);
    add_action("register_form", "ubivox_wc_subscription_checkbox");

    add_action("woocommerce_checkout_order_processed", "ubivox_wc_subscription_process", 5, 2 );
    add_action("woocommerce_ppe_do_payaction", "ubivox_wc_subscription_process_ppe", 5, 1 );
    add_action("register_post", "ubivox_wc_subscription_process_register", 5, 3);

    add_action("woocommerce_thankyou", "ubivox_wc_sales_tracking");

}

