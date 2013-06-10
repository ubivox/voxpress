<?php

class Ubivox_Control_Panel_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_control_panel_widget", // Base ID
            "Ubivox Control Panel", // Name
            array("description" => "Open your subscriber control panel in a popup.")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<div class="description">'.esc_html($instance["description"]).'</div>';

        echo '<p>';
        echo '<a class="ubivox_control_panel_button" href="'.UBIVOX_BASE_URL.'/control-panel/" target="_blank">'.$instance["button_text"].'</a>';
        echo '</p>';

        echo $args["after_widget"];

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["button_text"] = strip_tags($new_instance["button_text"]);
        $instance["description"] = strip_tags($new_instance["description"]);
        $instance["title"] = strip_tags($new_instance["title"]);

        return $instance;

    }

    public function form( $instance ) {

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Subscriber data", "ubivox");
        }

        if (isset($instance["button_text"])) {
            $button_text = $instance["button_text"];
        } else {
            $button_text = __("Access my data", "ubivox");
        }

        if (isset($instance["description"])) {
            $description = $instance["description"];
        } else {
            $description = __("Access your data and newsletters at Ubivox", "ubivox");
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


        // Button Text
        echo '<p>';
        echo '<label for="'.$this->get_field_id("button_text").'">Button text:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('button_text').'" name="'.$this->get_field_name('button_text').'" value="'.esc_attr($button_text).'">';
        echo '</p>';


    }

}
