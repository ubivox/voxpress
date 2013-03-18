<?php

function ubivox_wc_subscription_checkbox() {
    
    echo '<p class="form-row">';
    echo '<input class="input-checkbox" id="subscription-box" type="checkbox" name="subscribe_newsletter" value="1"> <label for="subscribe_newsletter" class="checkbox">Want to sign up for our newsletter?</label>';
    echo '</p>';

}

if (get_option("uvx_wc_integration")) {
    add_action("woocommerce_checkout_after_customer_details", "ubivox_wc_subscription_checkbox");
}

