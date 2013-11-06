<?php

class Ubivox_Verified_Sender_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_verified_sender_widget", // Base ID
            "Ubivox Verified Sender Seal", // Name
            array("description" => "Displays the Verified Sender Seal.")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        if ($instance["url"]) {

            echo '<p class="ubivox-verified-sender-seal">';
            echo '<!-- BEGIN UBIVOX VERIFIED SENDER SEAL -->
<a href="'.$instance["url"].'" target="_blank" title="Newsletter Sender Verified by Ubivox">
  <img src="https://cdn.ubivox.net/verified-sender/'.$instance["badge"].'" height="25" width="145" alt="Newsletter Sender Verified by Ubivox" style="border: 0; box-shadow: none; border-radius: 0;">
</a>
<!-- END UBIVOX VERIFIED SENDER SEAL -->';
            echo '</p>';

        }

        echo $args["after_widget"];

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["badge"] = strip_tags($new_instance["badge"]);

        try {
            $api = new UbivoxAPI();
            $instance["url"] = $api->call("ubivox.account_flag", array("verified_sender_cert_url"));
        } catch (UbivoxAPIException $e) {
            return $old_instance;
        }

        return $instance;

    }

    public function form( $instance ) {

        if (isset($instance["badge"])) {
            $badge = $instance["badge"];
        } else {
            $badge = "ux_vs_01_145x25_transparent.png";
        }

        $badges = array("ux_vs_01_145x25_transparent.png", "ux_vs_02_145x25_transparent.png", "ux_vs_03_145x25_transparent.png", "ux_vs_04_145x25_black.png");

        // Badge
        echo '<p>';
        echo '<label>Choose style:</label>';
        foreach ($badges as $name) {
            echo '<div style="margin-top: 20px; text-align: center;">';
            echo '<input type="radio" class="widefat" id="'.$this->get_field_id('badge').'" name="'.$this->get_field_name('badge').'" value="'.esc_attr($name).'"'.($badge == $name ? " checked": "").'>';
            echo '<img src="https://cdn.ubivox.net/verified-sender/'.esc_attr($name).'" height="25" width="145" style="margin-top: 10px;">';
            echo '</div>';
        }

        echo '</p>';

        
        $api = new UbivoxAPI();
        try {
            $has_verified_sender = $api->call("ubivox.account_flag", array("has_verified_sender"));
        } catch (UbivoxAPIException $e) {
            echo '<p>Could not contact Ubivox API.</p>';
        }

        if ($has_verified_sender == "no") {
            echo "<p><strong>Not activated!</strong></p>";
            echo "<p>Before the seal will appear on your website, you must apply to become a Verified Sender.</p>";
            echo "<p><a href='https://kb.ubivox.com/en/kb/verified-sender/' target='_blank'>Read more in our Knowledge Base</a></p>";
        }

    }

}
