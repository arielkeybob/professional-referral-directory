<?php
function get_provider_details($provider_id) {
    global $wpdb;
    $results = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $provider_id
    ));
    return $results;
}