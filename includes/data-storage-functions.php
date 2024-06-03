<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once('referral-fee-calculator.php');

/**
 * Adiciona ou atualiza um contato na tabela 'rhb_contacts'.
 */
function adicionar_ou_atualizar_contato($dados) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rhb_contacts';
    if (!isset($dados['email']) || empty($dados['email'])) {
        return false;
    }
    $email = $dados['email'];
    $name = $dados['name'] ?? '';

    $contact = $wpdb->get_row($wpdb->prepare("SELECT contact_id FROM $table_name WHERE email = %s", $email));
    if ($contact) {
        $wpdb->update($table_name, ['default_name' => $name], ['email' => $email]);
        return $contact->contact_id;
    } else {
        $wpdb->insert($table_name, ['email' => $email, 'default_name' => $name]);
        return $wpdb->insert_id;
    }
}

/**
 * Cria ou atualiza a relação entre contato e autor na tabela 'wp_rhb_author_contact_relations'.
 */
function createOrUpdateContactAuthorRelation($contactId, $authorId, $status = 'active', $customName = null) {
    global $wpdb;
    $relationTable = $wpdb->prefix . 'rhb_author_contact_relations';

    $existingRelation = $wpdb->get_row($wpdb->prepare(
        "SELECT author_contact_id FROM $relationTable WHERE contact_id = %d AND author_id = %d",
        $contactId, $authorId
    ));
    if (!$existingRelation) {
        $data = ['contact_id' => $contactId, 'author_id' => $authorId, 'status' => $status];
        if ($customName !== null) {
            $data['custom_name'] = $customName;
        }
        $wpdb->insert($relationTable, $data);
    }
}

/**
 * Armazena os dados do Inquiry associando-os automaticamente a um contato.
 */
function store_inquiry_data($data) {
    global $wpdb;
    $inquiryDataTable = $wpdb->prefix . 'rhb_inquiry_data';
    
    if (!isset($data['service_type'], $data['service_location'], $data['contact_id'], $data['author_id'])) {
        error_log('Dados necessários ausentes para inserção em wp_rhb_inquiry_data.');
        return false;
    }

    if (!isset($data['inquiry_date'])) {
        $data['inquiry_date'] = current_time('mysql');
    }

    // Calculate Referral Fee considering the author's settings
    $referralFees = calculate_referral_fees($data['author_id'], $data['inquiry_status']);

    $insertData = [
        'service_type' => $data['service_type'],
        'service_location' => $data['service_location'],
        'inquiry_date' => $data['inquiry_date'],
        'service_id' => $data['service_id'] ?? 0,
        'author_id' => $data['author_id'],
        'contact_id' => $data['contact_id'],
        'inquiry_status' => $data['inquiry_status'] ?? 'pending',
        'referral_fee_value_view' => $referralFees['view'],
        'referral_fee_value_agreement_reached' => $referralFees['agreement_reached']
    ];

    if (!$wpdb->insert($inquiryDataTable, $insertData)) {
        error_log('Erro ao inserir dados em wp_rhb_inquiry_data: ' . $wpdb->last_error);
        return false;
    }

    error_log("Dados de Inquiry inseridos com sucesso, ID: " . $wpdb->insert_id);
    return true;
}
?>
