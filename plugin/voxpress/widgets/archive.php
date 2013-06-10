<?php

class Ubivox_Archive_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            "ubivox_archive_widget", // Base ID
            "Ubivox Archive", // Name
            array("description" => __("Show your most recent archived newsletters", "voxpress"))
        );
    }

    public function widget( $args, $instance ) {

        echo $args["before_widget"];

        $title = apply_filters("widget_title", $instance["title"]);

        if (!empty($title)) {
            echo $args["before_title"].$title.$args["after_title"];
        }

        echo '<div class="description">'.esc_html($instance["description"]).'</div>';

        $newsletters = ubivox_archived_newsletters($instance["list_id"], $instance["count"]);

        if (is_array($newsletters)) {

            echo '<ul>';

            foreach ($newsletters as $newsletter) {

                $sent = strtotime($newsletter["send_time"]);
                $sent = date("Y-m-d H:i", $sent);

                $open_class = "open_". $instance["open_action"];

                echo '<li>';
                echo '<small>'.$sent.'</small>';
                echo '<a href="'.esc_attr(ubivox_archive_url($newsletter)).'" class="ubivox_archive_link '.$open_class.'">'.esc_html($newsletter["subject"]).'</a>';
                
                echo '</li>';

            }

            echo '</ul>';

        } else {

            echo __("Archive Unavailable", "voxpress");

        }

        echo $args["after_widget"];

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance["list_id"] = intval($new_instance["list_id"]);
        $instance["count"] = intval($new_instance["count"]);
        $instance["title"] = strip_tags($new_instance["title"]);
        $instance["description"] = strip_tags($new_instance["description"]);
        $instance["open_action"] = strip_tags($new_instance["open_action"]);

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
            $title = __("Newsletter archive", "voxpress");
        }

        if (isset($instance["description"])) {
            $description = $instance["description"];
        } else {
            $description = __("", "ubivox");
        }

        if (isset($instance["open_action"])) {
            $open_action = $instance["open_action"];
        } else {
            $open_action = "lightbox";
        }        


        if (isset($instance["count"])) {
            $count = $instance["count"];
        } else {
            $count = 3;
        }

        // Title
        echo '<p>';
        echo '<label for="'.$this->get_field_id("title").'">'.__("Title", "voxpress").':</label>';
        echo '<input type="text" class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" value="'.esc_attr($title).'">';
        echo '</p>';

        // Description
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("description").'">'. __("Description", "voxpress") .':</label>';
        echo '<textarea class="widefat" style="height:100px" id="'.$this->get_field_id('description').'" name="'.$this->get_field_name('description').'">'.esc_html($description).'</textarea>';
        echo '</div>';

        // List
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("list_id").'">'.__("List", "voxpress").':</label>';
        echo '<select class="widefat" style="width:120px; float:right" id="'.$this->get_field_id('list_id').'" name="'.$this->get_field_name('list_id').'">';

        foreach ($lists as $l) {
            echo '<option value="'.esc_attr($l["id"]).'"';
            if ($l["id"] == $list_id) {
                echo ' selected';
            }
            echo '>'.esc_html($l["title"]).'</option>';
        }

        echo '</select></div>';

        // Open action
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("open_action").'">'. __("Open action", "voxpress") .':';
        echo '<select style="width:120px; float:right" id="'.$this->get_field_id('open_action').'" name="'.$this->get_field_name('open_action').'">';
        
        $open_action_options = array(
            "lightbox" => __("Lightbox", "voxpress"),
            "new_window" => __("In a new window", "voxpress"),
            "same_window" => __("In the same window", "voxpress")
        );

        foreach ($open_action_options as $key => $label) {
            if ($key == $open_action) {
                $default = ' selected="selected"';
            } else {
                $default = "";
            }
            echo '<option value="'. $key .'"'.$default.'>'. $label  .'</option>';
        }

        echo '</select></label>';
        echo '</div>';

        // Number of newsletters
        echo '<div class="ubivox-widget-field">';
        echo '<label for="'.$this->get_field_id("count").'">'. __("Show maximum", "voxpress") .': <div style="float:right;margin-left:5px;margin-top:5px"></div><input type="text" style="width: 45px; float:right; text-align:center" id="'.$this->get_field_id('count').'" name="'.$this->get_field_name('count').'" value="'.esc_attr($count).'"></label>';        
        echo '</div>';

    }

}
