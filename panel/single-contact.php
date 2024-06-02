<?php
// single-contact.php
ob_start();
defined('ABSPATH') or die('No script kiddies please!');

// Inclui a classe ContactService para gerenciar as consultas de contato.
require_once 'class-contact-service.php';

$contactService = new ContactService();
$contact_id = isset($_GET['contact_id']) ? intval($_GET['contact_id']) : 0;

// Segurança: Verifique permissões e nonce.
if (!current_user_can('view_pdr_contacts') || !isset($_GET['contact_nonce']) || !wp_verify_nonce($_GET['contact_nonce'], 'view_contact_details_' . $_GET['contact_id'])) {
    wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
}

// Lógica para buscar os dados do contato, custom_name, status, e inquiries associadas.
$contact = $contactService->getContactDetailsById($contact_id);
$author_id = get_current_user_id();
$customDetails = $contactService->getCustomNameAndStatus($contact_id, $author_id);
$custom_name = $customDetails ? $customDetails->custom_name : '';
$status = $customDetails ? $customDetails->status : '';
$inquiries = $contactService->getInquiriesByContactId($contact_id, $author_id);

// Define variáveis globais ou de contexto para serem usadas no template.
$js_url = plugins_url('/js/alert-save-before-leave.js', __FILE__); // Ajuste conforme necessário.

// Inclui o arquivo de template, passando as variáveis necessárias.
require_once __DIR__ . '\contact_details_template.php';

ob_end_flush();
