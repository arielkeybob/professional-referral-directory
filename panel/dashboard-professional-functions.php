<?php
// Verifique se este arquivo não está sendo acessado diretamente.
if (!defined('WPINC')) {
    die;
}

// Certifique-se de incluir ou autoload a classe ContactService.
// Isso pode ser feito no início do seu plugin ou onde essas funções serão chamadas.
require_once plugin_dir_path(__FILE__) . 'class-contact-service.php';

$contactService = new ContactService();

/**
 * Busca o total de pesquisas por um serviço específico.
 *
 * @param int $service_id ID do serviço.
 * @return int Total de pesquisas para o serviço.
 */
function pdr_get_total_searches_by_service($service_id) {
    global $contactService;
    return $contactService->getTotalSearchesByService($service_id);
}

/**
 * Recupera as pesquisas mais recentes.
 *
 * @param int $limit Número de pesquisas a serem retornadas.
 * @return array Lista das pesquisas mais recentes.
 */
function pdr_get_recent_searches($limit = 5) {
    global $contactService;
    return $contactService->getRecentSearches($limit);
}

/**
 * Calcula a distribuição das pesquisas por tipo de serviço.
 *
 * @return array Distribuição das pesquisas.
 */
function pdr_get_searches_distribution_by_service_type() {
    global $contactService;
    return $contactService->getSearchesDistributionByServiceType();
}

/**
 * Retorna os serviços associados ao usuário atual.
 *
 * @return array Serviços do usuário atual.
 */
function pdr_get_services_by_current_user() {
    global $contactService;
    return $contactService->getServicesByCurrentUser();
}

/**
 * Recupera as pesquisas mais recentes associadas ao usuário atual.
 *
 * @param int $limit Número de pesquisas a serem retornadas.
 * @return array Lista das pesquisas mais recentes do usuário.
 */
function pdr_get_recent_searches_for_user($limit = 10) {
    global $contactService;
    return $contactService->getRecentSearchesForUser($limit);
}
