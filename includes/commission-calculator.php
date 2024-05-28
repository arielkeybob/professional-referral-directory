<?php
defined('ABSPATH') or die('No script kiddies please!');

function calculate_commissions($author_id = null) {
    $commission_type = get_option('pdr_commission_type', 'both'); // Default to 'both' if not set

    $view_commission = 0.00;
    $approval_commission = 0.00;

    // Check if author-specific override is enabled
    if ($author_id && get_user_meta($author_id, 'pdr_override_commission', true) == 'yes') {
        $view_commission = get_user_meta($author_id, 'pdr_commission_view', true);
        $approval_commission = get_user_meta($author_id, 'pdr_commission_approval', true);
    } else {
        $view_commission = get_option('pdr_general_commission_view', '0.05');
        $approval_commission = get_option('pdr_general_commission_approval', '0.10');
    }

    // Normalize and format decimal values
    $view_commission = number_format((float)str_replace(',', '.', $view_commission), 2, '.', '');
    $approval_commission = number_format((float)str_replace(',', '.', $approval_commission), 2, '.', '');

    $commissions = [
        'view' => ($commission_type === 'view' || $commission_type === 'both') ? $view_commission : 0.00,
        'approval' => ($commission_type === 'approval' || $commission_type === 'both') ? $approval_commission : 0.00
    ];

    return $commissions;
}
