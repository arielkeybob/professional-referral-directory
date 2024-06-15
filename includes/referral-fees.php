<?php
defined('ABSPATH') or die('No script kiddies please!');

function get_unpaid_referral_fees($provider_id = null, $start_date = null, $end_date = null) {
    global $wpdb;
    $inquiry_table = $wpdb->prefix . 'rhb_inquiry_data';
    $users_table = $wpdb->base_prefix . 'users';

    $query = "SELECT 
                i.author_id AS provider_id, 
                u.display_name AS provider_name, 
                u.user_email AS provider_email,
                SUM(i.referral_fee_value_view + i.referral_fee_value_agreement_reached) AS total_due
              FROM $inquiry_table i
              JOIN $users_table u ON i.author_id = u.ID
              WHERE i.is_paid = 0";

    if (!is_null($provider_id)) {
        $query .= $wpdb->prepare(" AND i.author_id = %d", $provider_id);
    }
    if (!is_null($start_date)) {
        $query .= $wpdb->prepare(" AND i.inquiry_date >= %s", $start_date);
    }
    if (!is_null($end_date)) {
        $query .= $wpdb->prepare(" AND i.inquiry_date <= %s", $end_date);
    }

    $query .= " GROUP BY i.author_id";
    $results = $wpdb->get_results($query);

    return $results;
}

