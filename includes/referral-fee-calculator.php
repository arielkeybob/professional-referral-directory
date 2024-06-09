<?php
defined('ABSPATH') or die('No script kiddies please!');

function calculate_referral_fees($author_id = null, $inquiry_status = 'pending') {
    $options = get_option('rhb_settings', []);
    $referral_fee_type = isset($options['rhb_referral_fee_type']) ? $options['rhb_referral_fee_type'] : 'both'; // Default to 'both' if not set

    $view_referral_fee = 0.00;
    $agreement_reached_referral_fee = 0.00;

    // Determinar se deve usar as configurações do autor ou as globais
    $use_author_settings = $author_id && get_user_meta($author_id, 'rhb_override_referral_fee', true) === 'yes';

    if ($use_author_settings) {
        $referral_fee_type = get_user_meta($author_id, 'rhb_referral_fee_type', true) ?: $referral_fee_type; // Usa tipo de Referral Fee do usuário se disponível
    }

    if ($referral_fee_type === 'view' || $referral_fee_type === 'both') {
        $view_referral_fee = $use_author_settings ? get_user_meta($author_id, 'rhb_referral_fee_view', true) : (isset($options['rhb_general_referral_fee_view']) ? $options['rhb_general_referral_fee_view'] : '0.05');
    } else {
        $view_referral_fee = 0.00; // Não calcular Referral Fee por visualização se o tipo for 'agreement_reached' apenas
    }

    if (($referral_fee_type === 'agreement_reached' || $referral_fee_type === 'both') && $inquiry_status === 'agreement_reached') {
        $agreement_reached_referral_fee = $use_author_settings ? get_user_meta($author_id, 'rhb_referral_fee_agreement_reached', true) : (isset($options['rhb_general_referral_fee_agreement_reached']) ? $options['rhb_general_referral_fee_agreement_reached'] : '0.10');
    } else {
        $agreement_reached_referral_fee = 0.00; // Não calcular Referral Fee por acordo se não está aprovado ou se o tipo é 'view' apenas
    }

    // Normalizar e formatar os valores decimais
    $view_referral_fee = number_format((float)str_replace(',', '.', $view_referral_fee), 2, '.', '');
    $agreement_reached_referral_fee = number_format((float)str_replace(',', '.', $agreement_reached_referral_fee), 2, '.', '');

    $referralFees = [
        'view' => $view_referral_fee,
        'agreement_reached' => $agreement_reached_referral_fee
    ];

    return $referralFees;
}
?>
