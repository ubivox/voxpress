<?php

define("UBIVOX_ARCHIVE_REGEX", "newsletter/.*?-(\d+)/?$");

function ubivox_flush_rules() {

    $rules = get_option("rewrite_rules");

    if (!isset($rules[UBIVOX_ARCHIVE_REGEX])) {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

}

function ubivox_rewrite_rules($rules) {
    $newrules = array();
    $newrules[UBIVOX_ARCHIVE_REGEX] = 'index.php?ubivox_newsletter_id=$matches[1]';
    return $newrules + $rules;
}

function ubivox_insert_query_vars($vars) {
    array_push($vars, "ubivox_newsletter_id");
    return $vars;
}

add_filter("rewrite_rules_array", "ubivox_rewrite_rules" );
add_filter("query_vars", "ubivox_insert_query_vars" );
add_action("wp_loaded", "ubivox_flush_rules" );
