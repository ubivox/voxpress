<?php

###############################################################################
# Backend
###############################################################################

function ubivox_archived_newsletters($list_id, $count) {

    $cache_key = "ubivox_archive_".intval($list_id)."_".intval($count);

    $newsletters = get_transient($cache_key);

    if ($newsletters === false) {

        $api = new UbivoxAPI();

        try {

            $newsletters = $api->call("ubivox.maillist_archive", array($list_id, $count));
            ubivox_save_newsletters_from_feed($newsletters);

        } catch (UbivoxAPIException $e) {

            $newsletters = get_transient($cache_key."_older");
            return $newsletters ? $newsletters : null;

        }

        set_transient($cache_key, $newsletters, 1800);
        set_transient($cache_key."_older", $newsletters, 86400);

    }

    return $newsletters;

}

function ubivox_save_newsletters_from_feed($newsletters) {
    foreach ($newsletters as $newsletter) {
        $cache_key = "ubivox_newsletter_".intval($newsletter["id"]);
        set_transient($cache_key, $newsletter["archive_html"], 3600);
        set_transient($cache_key."_older", $newsletter["archive_html"], 86400);
    }
}

function ubivox_newsletter($ubivox_id) {

    $cache_key = "ubivox_newsletter_".intval($ubivox_id);

    $newsletter = get_transient($cache_key);

    if ($newsletter === false) {

        $api = new UbivoxAPI();

        try {

            $newsletter = $api->call("ubivox.get_delivery", array($ubivox_id));
            $newsletter = $newsletter["archive_html_body"];

        } catch (UbivoxAPIException $e) {

            $newsletter = get_transient($cache_key."_older");
            return $newsletter ? $newsletter : null;

        }

        set_transient($cache_key, $newsletter, 3600);
        set_transient($cache_key."_older", $newsletter, 86400);

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

        if ($newsletter) {
            echo $newsletter;
        } else {
            status_header(404);
            include(get_404_template());
        }
        exit;
    }

}

add_action("template_redirect", "ubivox_newsletter_frontend");
