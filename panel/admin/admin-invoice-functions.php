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
    extract($data);

    if (isset($invoice_id) && $invoice_id > 0) {
        $wpdb->update($wpdb->prefix . 'rhb_invoices', [
            'total' => $total_amount,
            'invoice_date' => $invoice_date,
            'provider_id' => $provider_id
        ], ['invoice_id' => $invoice_id]);
    } else {
        $wpdb->insert($wpdb->prefix . 'rhb_invoices', [
            'total' => $total_amount,
            'invoice_date' => $invoice_date,
            'provider_id' => $provider_id
        ]);
        $invoice_id = $wpdb->insert_id;
    }
    return $invoice_id;
}

function get_invoice_items($invoice_id) {
    global $wpdb;
    return $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}rhb_invoice_items WHERE invoice_id = %d", $invoice_id
    ));
}

add_action('wp_ajax_save_invoice', function() {
    check_ajax_referer('save_invoice_nonce', 'nonce');
    $data = $_POST; // Assuma que os dados necessários estão em $_POST
    $invoice_id = save_invoice($data);
    if ($invoice_id) {
        wp_send_json_success(['message' => 'Invoice saved successfully!', 'invoice_id' => $invoice_id]);
    } else {
        wp_send_json_error(['message' => 'Failed to save invoice.']);
    }
});
