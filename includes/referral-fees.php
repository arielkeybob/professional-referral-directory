<?php
defined('ABSPATH') or die('No script kiddies please!');

function get_unpaid_referral_fees($provider_id = null, $start_date = null, $end_date = null) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';
    
    $query = "SELECT author_id AS provider_id, 
                     user.user_nicename AS provider_name, 
                     user.user_email AS provider_email, 
                     SUM(referral_fee_value_view + referral_fee_value_agreement_reached) AS total_due
              FROM $table_name
              JOIN {$wpdb->users} user ON user.ID = author_id
              WHERE is_paid = 0";

    if (!is_null($provider_id)) {
        $query .= $wpdb->prepare(" AND provider_id = %d", $provider_id);
    }
    if (!is_null($start_date)) {
        $query .= $wpdb->prepare(" AND inquiry_date >= %s", $start_date);
    }
    if (!is_null($end_date)) {
        $adjusted_end_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
        $query .= $wpdb->prepare(" AND inquiry_date < %s", $adjusted_end_date);
    }
    
    $query .= " GROUP BY provider_id";
    $results = $wpdb->get_results($query);

    return $results;
}

