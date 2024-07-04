<?php
defined('ABSPATH') or die('No script kiddies please!');

function rhb_register_provider_menus() {
    add_action('admin_menu', function() {
        add_menu_page(
            __('Provider Dashboard', 'referralhub'),
            __('Dashboard', 'referralhub'),
            'view_rhb_dashboard',
            'rhb-provider-dashboard',
            'rhb_provider_dashboard_page_content',
            'dashicons-businessman',
            3
        );

        add_menu_page(
            __('My Referral Fees', 'referralhub'),
            __('My Referral Fees', 'referralhub'),
            'view_rhb_referral_fees',
            'rhb-my-referral-fees',
            'rhb_my_referral_fees_page_content',
            'dashicons-money',
            7
        );
    });
}

rhb_register_provider_menus();

function rhb_provider_dashboard_page_content() {
    include 'dashboard-template-provider.php';
}

function rhb_my_referral_fees_page_content() {
    include 'provider-admin-referral-fees-page-functions.php';
}
