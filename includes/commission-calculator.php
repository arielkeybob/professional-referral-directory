<?php
defined('ABSPATH') or die('No script kiddies please!');

function calculate_commissions($author_id = null, $search_status = 'pending') {
    $commission_type = get_option('pdr_commission_type', 'both'); // Default to 'both' if not set

    $view_commission = 0.00;
    $approval_commission = 0.00;

    // Determinar se deve usar as configurações do autor ou as globais
    $use_author_settings = $author_id && get_user_meta($author_id, 'pdr_override_commission', true) === 'yes';

    if ($use_author_settings) {
        $commission_type = get_user_meta($author_id, 'pdr_commission_type', true) ?: $commission_type; // Usa tipo de comissão do usuário se disponível
    }

    if ($commission_type === 'view' || $commission_type === 'both') {
        $view_commission = $use_author_settings ? get_user_meta($author_id, 'pdr_commission_view', true) : get_option('pdr_general_commission_view', '0.05');
    } else {
        $view_commission = 0.00; // Não calcular comissão por visualização se o tipo for 'approval' apenas
    }

    if (($commission_type === 'approval' || $commission_type === 'both') && $search_status === 'approved') {
        $approval_commission = $use_author_settings ? get_user_meta($author_id, 'pdr_commission_approval', true) : get_option('pdr_general_commission_approval', '0.10');
    } else {
        $approval_commission = 0.00; // Não calcular comissão por aprovação se não está aprovado ou se o tipo é 'view' apenas
    }

    // Normalizar e formatar os valores decimais
    $view_commission = number_format((float)str_replace(',', '.', $view_commission), 2, '.', '');
    $approval_commission = number_format((float)str_replace(',', '.', $approval_commission), 2, '.', '');

    $commissions = [
        'view' => $view_commission,
        'approval' => $approval_commission
    ];

    return $commissions;
}
