<?php

class Ubivox_Archive_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_archive_widget", // Base ID
            "Ubivox Archive", // Name
            array("description" => "Show your most recent archived newsletters")
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<ul>';
        
        try {

            $api = new UbivoxAPI();

            $newsletters = $api->call("ubivox.maillist_archive", 
                                      array($instance["list_id"], $instance["count"]));

        } catch (UbivoxAPIException $e) {
            echo "<p>Connection problems. See settings for details.</p>";
            return;
        }

        foreach ($newsletters as $newsletter) {

            $sent = strtotime($newsletter["send_time"]);
            $sent = date("Y-m-d H:i", $sent);

            echo '<li>';
            echo '<a href="'.esc_attr($newsletter["archive_url"]).'" target="_blank">'.esc_html($newsletter["subject"]).'</a><br>';
            echo '('.$sent.')';
            echo '</li>';
        }

        echo '</ul>';

        echo $args["after_widget"];

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["count"] = intval($new_instance["count"]);
        $instance["title"] = strip_tags($new_instance["title"]);

        return $instance;

    }

    public function form( $instance ) {

        try {

            $api = new UbivoxAPI();

            $lists = $api->call("ubivox.get_maillists");

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

        if (isset($instance["title"])) {
            $title = $instance["title"];
        } else {
            $title = __("Newsletter archive", "ubivox");
        }

        if (isset($instance["count"])) {
            $count = $instance["count"];
        } else {
            $count = 3;
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
        echo '<label for="'.$this->get_field_id("count").'">Newsletters to display:</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('count').'" name="'.$this->get_field_name('count').'" value="'.esc_attr($count).'">';
        echo '</p>';

    }

}
