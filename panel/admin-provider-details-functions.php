<?php
defined('ABSPATH') or die('No script kiddies please!');

function get_provider_details($provider_id) {
    global $wpdb;
    return $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}users WHERE ID = %d", $provider_id
    ));
}

function get_provider_unpaid_fees_details($provider_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';

    $query = $wpdb->prepare("
        SELECT 
            id,
            service_type,
            inquiry_date,
            referral_fee_value_view,
            referral_fee_value_agreement_reached,
            (referral_fee_value_view + referral_fee_value_agreement_reached) AS total_fee
        FROM $table_name
        WHERE author_id = %d AND is_paid = 0 AND invoiced = 0
        ORDER BY inquiry_date DESC", 
        $provider_id
    );

    return $wpdb->get_results($query);
}

function mark_inquiries_as_invoiced($inquiry_ids) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_inquiry_data';

    foreach ($inquiry_ids as $id) {
        $wpdb->update(
            $table_name,
            ['invoiced' => 1],
            ['id' => $id]
        );
    }
}

/**
 * Cria uma invoice e associa os inquiries selecionados.
 *
 * @param int $provider_id ID do provider.
 * @param array $inquiry_ids IDs dos inquiries a serem faturados.
 * @return int|null ID da invoice criada ou null em caso de falha.
 */
function create_invoice_and_link_inquiries($provider_id, $inquiry_ids) {
    global $wpdb;
    $invoice_table = $wpdb->prefix . 'rhb_invoices';
    $link_table = $wpdb->prefix . 'rhb_invoice_inquiries';

    // Calcula o total
    $total = 0;
    foreach ($inquiry_ids as $id) {
        $fee_details = $wpdb->get_row($wpdb->prepare("SELECT (referral_fee_value_view + referral_fee_value_agreement_reached) AS total_fee FROM {$wpdb->prefix}rhb_inquiry_data WHERE id = %d", $id));
        if ($fee_details) {
            $total += $fee_details->total_fee;
        }
    }

    // Cria o invoice
    $wpdb->insert($invoice_table, [
        'provider_id' => $provider_id,
        'total' => $total,
        'is_paid' => 0
    ]);
    $invoice_id = $wpdb->insert_id;

    // Associa inquiries ao invoice
    foreach ($inquiry_ids as $inquiry_id) {
        $wpdb->insert($link_table, [
            'invoice_id' => $invoice_id,
            'inquiry_id' => $inquiry_id
        ]);
    }

    // Marca inquiries como faturadas
    mark_inquiries_as_invoiced($inquiry_ids);

    return $invoice_id;
}

function handle_ajax_create_invoice() {
    $nonce = $_POST['nonce'] ?? '';

    // Verifica o nonce
    if (!wp_verify_nonce($nonce, 'create_invoice_nonce')) {
        wp_send_json_error(['message' => 'Verificação de segurança falhou.']);
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permissão insuficiente.']);
        return;
    }

    $provider_id = isset($_POST['provider_id']) ? intval($_POST['provider_id']) : null;
    $inquiry_ids = isset($_POST['inquiry_ids']) ? $_POST['inquiry_ids'] : [];
    $invoice_id = create_invoice_and_link_inquiries($provider_id, $inquiry_ids);

    if ($invoice_id) {
        wp_send_json_success(['message' => 'Invoice criada com sucesso', 'invoice_id' => $invoice_id]);
    } else {
        wp_send_json_error(['message' => 'Falha ao criar invoice']);
    }
}

add_action('wp_ajax_create_invoice', 'handle_ajax_create_invoice');
add_action('wp_ajax_nopriv_create_invoice', 'handle_ajax_create_invoice'); // Se você quiser que não-logados possam também criar invoices, o que geralmente não é recomendado.
