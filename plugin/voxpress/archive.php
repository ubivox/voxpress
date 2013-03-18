<?php

###############################################################################
# Backend
###############################################################################

function ubivox_archived_newsletters($list_id, $count) {

    $api = new UbivoxAPI();

    $key_schedule = "ubivox_archive_next_call_".intval($list_id)."_".$count;
    $key_result = "ubivox_archive_result_".intval($list_id)."_".$count;

    if (get_option($key_schedule, time()) <= time()) {

        try {

            $newsletters = $api->call("ubivox.maillist_archive", array($list_id, $count));

        } catch (UbivoxAPIException $e) {

            $newsletters = get_option($key_result);

            if ($newsletters) {
                return $newsletters;
            }

            delete_option($key_result);
            delete_option($key_schedule);

            return null;

        }

        update_option($key_result, $newsletters);
        update_option($key_schedule, time() + 1800);

    } else {
        $newsletters = get_option($key_result);
    }

    return $newsletters;

}

function ubivox_newsletter($ubivox_id) {

    $api = new UbivoxAPI();

    $key_schedule = "ubivox_newsletter_next_call_".intval($ubivox_id);
    $key_result = "ubivox_newsletter_".intval($ubivox_id);

    if (get_option($key_schedule, time()) <= time()) {

        try {

            $newsletter = $api->call("ubivox.get_delivery", array($ubivox_id));

        } catch (UbivoxAPIException $e) {

            $newsletter = get_option($key_result);

            if ($newsletter) {
                return $newsletter;
            }

            delete_option($key_result);
            delete_option($key_schedule);

            return null;

        }

        update_option($key_result, $newsletter);
        update_option($key_schedule, time() + 1800);

    } else {

        $newsletter = get_option($key_result);

    }

    return $newsletter;

}

###############################################################################
# Frontend
###############################################################################

define(PERMALINKS_ENABLED, get_option("permalink_structure"));

function ubivox_archive_url($newsletter) {
    if (PERMALINKS_ENABLED) {
        return home_url("/newsletter/".sanitize_title($newsletter["subject"]).'-'.intval($newsletter["id"]).'/');
    } else {
        return home_url("?ubivox_newsletter_id=".intval($newsletter["id"]));
    }
}

function ubivox_newsletter_frontend() {

    if (get_query_var("ubivox_newsletter_id")) {
        
        $newsletter = ubivox_newsletter(get_query_var("ubivox_newsletter_id"));

        if ($newsletter["archive_html_body"]) {
            echo $newsletter["archive_html_body"];
        } else {
            status_header(404);
            include(get_404_template());
        }
        exit;
    }

}

add_action("template_redirect", "ubivox_newsletter_frontend");
