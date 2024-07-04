<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once 'admin-provider-details-functions.php';

function get_invoice_details($invoice_id) {
    global $wpdb;
    // Consulta para buscar detalhes da fatura incluindo dados do provider
    $query = $wpdb->prepare("
        SELECT inv.*, usr.display_name as provider_name
        FROM {$wpdb->prefix}rhb_invoices inv
        JOIN {$wpdb->prefix}users usr ON inv.provider_id = usr.ID
        WHERE inv.invoice_id = %d", $invoice_id
    );

    return $wpdb->get_row($query);
}

function save_invoice($data) {
    global $wpdb;
    $invoice_id = isset($data['invoice_id']) ? intval($data['invoice_id']) : 0;
    $total_amount = isset($data['total_amount']) ? floatval($data['total_amount']) : 0.0;
    $invoice_date = isset($data['invoice_date']) ? sanitize_text_field($data['invoice_date']) : current_time('mysql');
    $provider_id = isset($data['provider_id']) ? intval($data['provider_id']) : 0;
    $is_paid = isset($data['is_paid']) ? intval($data['is_paid']) : 0;

    if ($invoice_id > 0) {
        $wpdb->update($wpdb->prefix . 'rhb_invoices', [
            'total' => $total_amount,
            'invoice_date' => $invoice_date,
            'provider_id' => $provider_id,
            'is_paid' => $is_paid
        ], ['invoice_id' => $invoice_id]);
    } else {
        $wpdb->insert($wpdb->prefix . 'rhb_invoices', [
            'total' => $total_amount,
            'invoice_date' => $invoice_date,
            'provider_id' => $provider_id,
            'is_paid' => $is_paid
        ]);
        $invoice_id = $wpdb->insert_id;
    }
    return $invoice_id;
}

function get_invoice_items($invoice_id) {
    global $wpdb;
    $query = $wpdb->prepare("
        SELECT id, service_type, inquiry_date, referral_fee_value_agreement_reached as amount
        FROM {$wpdb->prefix}rhb_inquiry_data
        WHERE invoice_id = %d", $invoice_id
    );

    return $wpdb->get_results($query);
}

add_action('wp_ajax_save_invoice', function() {
    check_ajax_referer('save_invoice_nonce', 'nonce');
    $data = array_map('sanitize_text_field', wp_unslash($_POST));
    $invoice_id = save_invoice($data);
    if ($invoice_id) {
        wp_send_json_success(['message' => 'Invoice saved successfully!', 'invoice_id' => $invoice_id]);
    } else {
        wp_send_json_error(['message' => 'Failed to save invoice.']);
    }
});
