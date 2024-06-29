<?php
    defined('ABSPATH') or die('No script kiddies please!');

// Certifique-se de incluir ou autoload a classe ContactService.
// Isso pode ser feito no início do seu plugin ou onde essas funções serão chamadas.
require_once plugin_dir_path(__FILE__) . 'class-contact-service.php';

$contactService = new ContactService();

/**
 * Busca o total de Inquirys por um serviço específico.
 *
 * @param int $service_id ID do serviço.
 * @return int Total de Inquirys para o serviço.
 */
function rhb_get_total_inquiries_by_service($service_id) {
    global $contactService;
    return $contactService->getTotalInquiriesByService($service_id);
}

/**
 * Recupera os Inquiries mais recentes.
 *
 * @param int $limit Número de Inquirys a serem retornadas.
 * @return array Lista dos Inquiries mais recentes.
 */
function rhb_get_recent_inquiries($limit = 5) {
    global $contactService;
    return $contactService->getRecentInquiries($limit);
}

/**
 * Calcula a distribuição dos Inquiries por tipo de serviço.
 *
 * @return array Distribuição dos Inquiries.
 */
function rhb_get_inquiries_distribution_by_service_type() {
    global $contactService;
    return $contactService->getInquiriesDistributionByServiceType();
}

/**
 * Retorna os serviços associados ao usuário atual.
 *
 * @return array Serviços do usuário atual.
 */
function rhb_get_services_by_current_user() {
    global $contactService;
    return $contactService->getServicesByCurrentUser();
}

/**
 * Recupera os Inquiries mais recentes associadas ao usuário atual.
 *
 * @param int $limit Número de Inquirys a serem retornadas.
 * @return array Lista dos Inquiries mais recentes do usuário.
 */
function rhb_get_recent_inquiries_for_user($limit = 10) {
    global $contactService;
    return $contactService->getRecentInquiriesForUser($limit);
}
