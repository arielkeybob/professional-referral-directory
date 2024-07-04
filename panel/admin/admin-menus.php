<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(__FILE__) . 'class-settings-page.php';

function rhb_register_admin_menus() {
    add_action('admin_menu', function() {
        // General Settings
        add_submenu_page(
            'edit.php?post_type=rhb_service',
            __('General Settings', 'referralhub'),
            __('Settings', 'referralhub'),
            'manage_options',
            'rhb-general-settings',
            'rhb_general_settings_page_content'
        );

        // Setup Wizard
        add_submenu_page(
            'edit.php?post_type=rhb_service',
            __('Setup Wizard', 'referralhub'),
            __('Setup Wizard', 'referralhub'),
            'manage_options',
            'rhb-setup-wizard',
            'rhb_setup_wizard_page_content'
        );

        // Manage Invoices
        add_submenu_page(
            'edit.php?post_type=rhb_service',
            __('Manage Invoices', 'referralhub'),
            __('Invoices', 'referralhub'),
            'manage_options',
            'rhb-invoice',
            'rhb_invoice_page_content'
        );

        // Referral Fees Report and Details
        add_submenu_page(
            'edit.php?post_type=rhb_service',
            __('Referral Fees Report', 'referralhub'),
            __('Referral Fees', 'referralhub'),
            'manage_options',
            'rhb-referral-fees',
            'rhb_referral_fees_page_content'
        );

        add_submenu_page(
            'edit.php?post_type=rhb_service',
            __('Referral Fees Details', 'referralhub'),
            __('Referral Fees Details', 'referralhub'),
            'manage_options',
            'rhb-referral-fees-provider-details',
            'rhb_referral_fees_provider_details_page_content'
        );
    });
}

rhb_register_admin_menus();

function rhb_general_settings_page_content() {
    $settings = new RHB_Settings();
    $settings->render_settings_page();
}

function rhb_setup_wizard_page_content() {
    include plugin_dir_path(__FILE__) . 'setup-wizard.php';
}

function rhb_invoice_page_content() {
    $invoice_id = isset($_GET['invoice_id']) ? intval($_GET['invoice_id']) : null;
    if ($invoice_id) {
        include plugin_dir_path(__FILE__) . 'templates/admin-invoice-management-template.php';
    } else {
        include plugin_dir_path(__FILE__) . 'templates/admin-invoice-management-template.php';
    }
}

function rhb_referral_fees_page_content() {
    include plugin_dir_path(__FILE__) . 'templates/admin-referral-fees-page-template.php';
}

function rhb_referral_fees_provider_details_page_content() {
    $provider_id = isset($_GET['provider_id']) ? intval($_GET['provider_id']) : 0;
    if ($provider_id) {
        include plugin_dir_path(__FILE__) . 'templates/admin-provider-details-template.php';
    } else {
        echo '<p>Error: Provider not found.</p>';
    }
}
