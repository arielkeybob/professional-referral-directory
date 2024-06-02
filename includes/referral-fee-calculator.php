<?php
defined('ABSPATH') or die('No script kiddies please!');

function calculate_referral_fees($author_id = null, $inquiry_status = 'pending') {
    $referral_fee_type = get_option('pdr_referral_fee_type', 'both'); // Default to 'both' if not set

    $view_referral_fee = 0.00;
    $approval_referral_fee = 0.00;

    // Determinar se deve usar as configurações do autor ou as globais
    $use_author_settings = $author_id && get_user_meta($author_id, 'pdr_override_referral_fee', true) === 'yes';

    if ($use_author_settings) {
        $referral_fee_type = get_user_meta($author_id, 'pdr_referral_fee_type', true) ?: $referral_fee_type; // Usa tipo de Referral Fee do usuário se disponível
    }

    if ($referral_fee_type === 'view' || $referral_fee_type === 'both') {
        $view_referral_fee = $use_author_settings ? get_user_meta($author_id, 'pdr_referral_fee_view', true) : get_option('pdr_general_referral_fee_view', '0.05');
    } else {
        $view_referral_fee = 0.00; // Não calcular Referral Fee por visualização se o tipo for 'approval' apenas
    }

    if (($referral_fee_type === 'approval' || $referral_fee_type === 'both') && $inquiry_status === 'approved') {
        $approval_referral_fee = $use_author_settings ? get_user_meta($author_id, 'pdr_referral_fee_approval', true) : get_option('pdr_general_referral_fee_approval', '0.10');
    } else {
        $approval_referral_fee = 0.00; // Não calcular Referral Fee por aprovação se não está aprovado ou se o tipo é 'view' apenas
    }

    // Normalizar e formatar os valores decimais
    $view_referral_fee = number_format((float)str_replace(',', '.', $view_referral_fee), 2, '.', '');
    $approval_referral_fee = number_format((float)str_replace(',', '.', $approval_referral_fee), 2, '.', '');

    $referralFees = [
        'view' => $view_referral_fee,
        'approval' => $approval_referral_fee
    ];

    return $referralFees;
}
