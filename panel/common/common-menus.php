<?php
defined('ABSPATH') or die('No script kiddies please!');

function rhb_add_common_hooks() {
    add_action('init', 'rhb_add_roles_and_capabilities');
    add_action('admin_menu', 'rhb_remove_default_dashboard_for_service_providers', 999);
}

function rhb_add_roles_and_capabilities() {
    $role = get_role('service_provider');
    if ($role) {
        $role->add_cap('view_rhb_dashboard');
        $role->add_cap('view_rhb_contacts');
        $role->add_cap('view_rhb_referral_fees');
    }
}

function rhb_remove_default_dashboard_for_service_providers() {
    if (current_user_can('service_provider')) {
        remove_menu_page('index.php');
    }
}

rhb_add_common_hooks();
