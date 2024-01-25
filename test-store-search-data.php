<?php
// Inclua os arquivos necessários do seu plugin
require_once '../../../wp-load.php'; // Ajuste o caminho conforme a localização do seu arquivo

// Escreve uma mensagem no debug.log
if (defined('WP_DEBUG') && WP_DEBUG) {
    error_log('Arquivo visitado');
}

require_once 'includes/data-storage-functions.php';

// Dados simulados representando a entrada do formulário
$dados_teste = array(
    'service_type' => 'TipoTeste',
    'service_location' => 'LocalTeste',
    'name' => 'NomeTeste',
    'email' => 'email@teste.com',
);

// Chama a função store_search_data com os dados de teste
store_search_data($dados_teste);
