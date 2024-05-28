<?php
defined('ABSPATH') or die('No script kiddies please!');

function calculate_commissions() {
    $commission_type = get_option('pdr_commission_type', 'view');
    $commission_view = str_replace(',', '.', get_option('pdr_general_commission_view', '0.05'));
    $commission_approval = str_replace(',', '.', get_option('pdr_general_commission_approval', '0.10'));

    $commission_view_float = number_format(floatval($commission_view), 2, '.', '');
    $commission_approval_float = number_format(floatval($commission_approval), 2, '.', '');

    $commissions = [
        'view' => 0,
        'approval' => 0
    ];

    switch ($commission_type) {
        case 'view':
            $commissions['view'] = $commission_view_float;
            break;
        case 'approval':
            $commissions['approval'] = $commission_approval_float;
            break;
        case 'both':
            $commissions['view'] = $commission_view_float;
            $commissions['approval'] = $commission_approval_float;
            break;
    }

    return $commissions;
}
