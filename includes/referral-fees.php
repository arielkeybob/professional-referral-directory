<?php
defined('ABSPATH') or die('No script kiddies please!');

function get_unpaid_referral_fees($start_date = null, $end_date = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';

    // Use a coluna correta para identificar o provider, ajustada aqui como 'author_id'.
    $query = "SELECT author_id AS provider_id, SUM(referral_fee_value_view + referral_fee_value_agreement_reached) AS total_due
              FROM $table_name
              WHERE is_paid = 0";

    if (!is_null($start_date)) {
        $query .= $wpdb->prepare(" AND inquiry_date >= %s", $start_date);
    }
    if (!is_null($end_date)) {
        $query .= $wpdb->prepare(" AND inquiry_date <= %s", $end_date);
    }

    $query .= " GROUP BY author_id";
    $results = $wpdb->get_results($query);

    error_log('Referral Fees SQL: ' . $query);
    error_log('Referral Fees Results Count: ' . count($results));

    return $results;
}
