<?php

class Ubivox_Unsubscription_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_unsubscription_widget", // Base ID
            "Ubivox Unsubscription", // Name
            array("description" => "Allow visitors to unsubscribe your lists in Ubivox")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<div class="description">'.esc_html($instance["description"]).'</div>';

        echo '<form method="POST" class="ubivox_unsubscription">';

        echo '<input type="hidden" name="list_id" value="'.esc_attr($instance["list_id"]).'">';

        echo '<p>';
        echo '<label for="'.$this->get_field_id("email_address").'">E-mail:</label><br>';
        echo '<input type="text" name="email_address" id="'.$this->get_field_id("email_address").'" value="">';
        echo '</p>';

        echo '<p>';
        echo '<button class="ubivox_signout_button">'.$instance["button_text"].'</button>';
        echo '</p>';

        echo '</form>';

        echo '<p class="success_text" style="display: none;">';
        echo esc_html($instance["success_text"]);
        echo '</p>';

        echo $args["after_widget"];
    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        if ($new_instance["list_id"] == "ALL") {
            $instance["list_id"] = "ALL";
        } else {
            $instance["list_id"] = intval($new_instance["list_id"]);
        }

        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["description"] = strip_tags($new_instance["description"]);
        $instance["success_text"] = strip_tags($new_instance["success_text"]);

        return $instance;

    }

    public function form( $instance ) {

        try {

            $api = new UbivoxAPI();

            $lists = $api->call("ubivox.get_maillists");

        } catch (UbivoxAPIException $e) {
            echo "<p>". __("Connection problems. See settings for details.", "voxpress") ."</p>";
            return;
        }

        if (isset($instance["list_id"])) {
            $list_id = $instance["list_id"];
        } else {
            $list_id = "ALL";
        }

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $button_text = __("Unsubscribe", "voxpress");
        }

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Newsletter", "voxpress");
        }

        if (isset($instance["description"])) {
            $description = $instance["description"];
        } else {
            $description = __("", "voxpress");
        }

        if (isset($instance["success_text"])) {
            $success_text = $instance["success_text"];
        } else {
            $success_text = __("Thank you.", "ubivox");
        }

        // Title
        echo '<p>';
        echo '<label for="'.$this->get_field_id("title").'">Title:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($title).'">';
        echo '</p>';

        // Description
        echo '<p>';
        echo '<label for="'.$this->get_field_id("description").'">Description:</label>';
        echo '<textarea class="widefat" style="height:100px" id="'.$this->get_field_id('description').'" name="'.$this->get_field_name('description').'">'.esc_html($description).'</textarea>';
        echo '</p>';

        // List
        echo '<p>';
        echo '<label for="'.$this->get_field_id("list_id").'">List:</label>';
        echo '<select class="widefat" style="width:100%;" id="'.$this->get_field_id('list_id').'" name="'.$this->get_field_name('list_id').'">';

        echo '<option value="ALL"'.($list_id == "ALL" ? " selected" : "").'>- All lists in Ubivox -</option>';
        
        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }

        echo '</select></p>';

        // Button text
        echo '<p>';
        echo '<label for="'.$this->get_field_id("button_text").'">Button text:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</p>';

        // Success text
        echo '<p>';
        echo '<label for="'.$this->get_field_id("success_text").'">Success text:</label>';
        echo '<textarea class="widefat" id="'.$this->get_field_id('success_text').'" name="'.$this->get_field_name('success_text').'">'.esc_html($success_text).'</textarea>';
        echo '</p>';

    }

}
