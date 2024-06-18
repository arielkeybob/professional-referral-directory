<?php

function get_provider_details($provider_id) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $provider_id
    ));
}

function get_provider_unpaid_fees_details($provider_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';

    $query = $wpdb->prepare("
        SELECT 
            id,
            service_type,
            inquiry_date,
            referral_fee_value_view,
            referral_fee_value_agreement_reached,
            (referral_fee_value_view + referral_fee_value_agreement_reached) AS total_fee
        FROM $table_name
        WHERE author_id = %d AND is_paid = 0
        ORDER BY inquiry_date DESC", 
        $provider_id
    );

    return $wpdb->get_results($query);
}
