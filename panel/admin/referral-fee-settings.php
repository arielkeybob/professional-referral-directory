<?php
defined('ABSPATH') or die('No script kiddies please!');

function rhb_register_referral_fee_settings() {
    register_setting('rhb_settings_group', 'rhb_settings', 'rhb_sanitize_settings');
}

function rhb_sanitize_settings($input) {
    $sanitized_input = array();
    
    if (isset($input['rhb_referral_fee_type'])) {
        $sanitized_input['rhb_referral_fee_type'] = sanitize_text_field($input['rhb_referral_fee_type']);
    }
    
    if (isset($input['rhb_general_referral_fee_view'])) {
        // Removendo caracteres não numéricos exceto a vírgula, depois convertendo vírgula para ponto
        $sanitized_input['rhb_general_referral_fee_view'] = preg_replace('/[^\d,]/', '', $input['rhb_general_referral_fee_view']);
        $sanitized_input['rhb_general_referral_fee_view'] = str_replace(',', '.', $sanitized_input['rhb_general_referral_fee_view']);
    }
    
    if (isset($input['rhb_general_referral_fee_agreement_reached'])) {
        $sanitized_input['rhb_general_referral_fee_agreement_reached'] = preg_replace('/[^\d,]/', '', $input['rhb_general_referral_fee_agreement_reached']);
        $sanitized_input['rhb_general_referral_fee_agreement_reached'] = str_replace(',', '.', $sanitized_input['rhb_general_referral_fee_agreement_reached']);
    }
    
    return $sanitized_input;
}


add_action('admin_init', 'rhb_register_referral_fee_settings');
