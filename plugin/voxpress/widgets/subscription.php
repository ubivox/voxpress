<?php

class Ubivox_Subscription_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_subscription_widget", // Base ID
            "Ubivox Subscription", // Name
            array("description" => "Get new subscriptions for your lists in Ubivox")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<form method="POST" class="ubivox_subscription">';

        echo '<input type="hidden" name="list_id" value="'.intval($instance["list_id"]).'">';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("email_address").'">E-mail:</label><br>';
        echo '<input type="text" name="email_address" id="'.$this->get_field_id("email_address").'" value="">';
        echo '</p>';

        foreach ($instance["data_meta"] as $field) {

            if (!in_array($field["key"], $instance["data"])) {
                continue;
            }

            echo '<p class="ubivox_input ubivox_'.$field["datatype"].'">';

            switch ($field["datatype"]) {

            case "textarea":

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<textarea name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" style="width: 100%; height: 50px;"></textarea>';

                break;

            case "checkbox":

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<input type="checkbox" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" value="1">';

                break;

            case "select_multiple":
            case "select":

                if ($field["datatype"] == "select_multiple") {
                    $multiple = " multiple";
                } else {
                    $multiple = "";
                }

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<select name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" style="width: 100%;"'.$multiple.'>';

                foreach ($field["choices"] as $choice) {
                    list($key, $value) = $choice;
                    echo '<option value="'.esc_attr($key).'">'.esc_html($value).'</option>';
                }

                echo '</select>';

                break;

            case "select_radio":

                echo '<label>'.esc_html($field["title"]).':</label><br>';

                foreach ($field["choices"] as $choice) {

                    list($key, $value) = $choice;
                    echo '<input type="radio" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]."_".$key).'" value="'.esc_attr($key).'">&nbsp;';
                    echo '<label for="'.$this->get_field_id("data_".$field["key"]."_".$key).'">'.esc_html($value).'</label><br>';
                }

                break;

            case "select_checkbox":

                echo '<label>'.esc_html($field["title"]).':</label><br>';

                foreach ($field["choices"] as $choice) {

                    list($key, $value) = $choice;
                    echo '<input type="checkbox" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]."_".$key).'" value="'.esc_attr($key).'">&nbsp;';
                    echo '<label for="'.$this->get_field_id("data_".$field["key"]."_".$key).'">'.esc_html($value).'</label><br>';
                }

                break;

            default:

                echo '<label for="'.$this->get_field_id("data_".$field["key"]).'">'.esc_html($field["title"]).':</label><br>';
                echo '<input type="text" name="'.esc_attr($field["key"]).'" id="'.$this->get_field_id("data_".$field["key"]).'" value="">';

            }

            echo '</p>';

        }

        echo '<p>';
        echo '<button class="ubivox_signup_button">'.$instance["button_text"].'</button>';
        echo '</p>';

        echo '</form>';

        echo '<p class="success_text" style="display: none;">';
        echo esc_html($instance["success_text"]);
        echo '</p>';

        echo $args["after_widget"];
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["success_text"] = strip_tags($new_instance["success_text"]);

        try {
            $api = new UbivoxAPI();
            $data_fields = $api->call("ubivox.list_data_fields_details");
        } catch (UbivoxAPIException $e) {
            return $old_instance;
        }

        $data = Array();

        foreach ($data_fields as $field) {
            if (isset($new_instance["data_".$field["id"]])) {
                $data[] = $field["key"];
            }
        }

        $instance["data"] = $data;
        $instance["data_meta"] = $data_fields;

        return $instance;
    }

    public function form( $instance ) {

        try {

            $api = new UbivoxAPI();

            $lists = $api->call("ubivox.get_maillists");
            $data_fields = $api->call("ubivox.list_data_fields_details");

        } catch (UbivoxAPIException $e) {
            echo "<p>Connection problems. See settings for details.</p>";
            return;
        }

        $first_list = $lists[0];
            
        if (isset($instance["list_id"])) {
            $list_id = $instance["list_id"];
        } else {
            $list_id = $first_list["id"];
        }

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $button_text = __("Subscribe", "ubivox");
        }

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Newsletter", "ubivox");
        }

        if (isset($instance["data"])) {
            $data = $instance["data"];
        } else {
            $data = array();
        }

        if (isset($instance["success_text"])) {
            $success_text = $instance["success_text"];
        } else {
            $success_text = __("Thank you.", "ubivox");
        }

        echo '<p>';
        echo '<label for="'.$this->get_field_id("title").'">Title:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($title).'">';
        echo '</p>';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("list_id").'">List:</label>';
        echo '<select class="widefat" style="width:100%;" id="'.$this->get_field_id('list_id').'" name="'.$this->get_field_name('list_id').'">';
        
        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }

        echo '</select></p>';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("button_text").'">Button text:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</p>';

        echo '<p>';
        echo '<label>Data fields:</label><br>';

        foreach ($data_fields as $field) {

            if (in_array($field["key"], $data)) {
                $checked = " checked";
            } else {
                $checked = "";
            }

            echo '<input class="checkbox" type="checkbox" id="'.$this->get_field_id('data_'.$field["id"]).'" name="'.$this->get_field_name('data_'.$field["id"]).'"'.$checked.'>&nbsp;';
            echo '<label for="'.$this->get_field_id('data_'.$field["id"]).'">'.esc_html($field["title"]).' <em>('.esc_html($field["key"]).')</em></label><br>';
        }

        echo '</p>';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("success_text").'">Success text:</label>';
        echo '<textarea class="widefat" id="'.$this->get_field_id('success_text').'" name="'.$this->get_field_name('success_text').'">'.esc_html($success_text).'</textarea>';
        echo '</p>';

    }

}
