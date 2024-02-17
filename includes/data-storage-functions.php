<?php
// Prevenção contra acesso direto ao arquivo
if (!defined('WPINC')) {
    die;
}

// Atualização da função store_search_data para usar a nova tabela de contatos
function store_search_data($data) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'pdr_search_data'; // Substitua 'pdr_search_data' pelo nome da sua nova tabela de contatos

    // Adicionando ou atualizando a data de pesquisa e localização do serviço
    $data['service_location'] = $data['service_location'] ?? 'valor_padrao';
    $data['search_date'] = $data['search_date'] ?? current_time('mysql');

    if (!isset($data['service_id']) || !isset($data['author_id'])) {
        return; // Importante ter esses dados, senão a função para
    }

    // Verifica se o e-mail já existe na nova tabela de contatos
    $email = $data['email'];
    $contact_exists = $wpdb->get_var($wpdb->prepare("SELECT id FROM $table_name WHERE email = %s", $email));

    if ($contact_exists) {
        // Atualiza o registro existente
        $wpdb->update(
            $table_name,
            $data, // Supõe que $data já contenha todos os campos necessários
            ['email' => $email] // Condição para encontrar o registro a ser atualizado
        );
    } else {
        // Insere um novo registro
        $wpdb->insert($table_name, $data);
    }
}

    // Função para adicionar ou atualizar contato na nova tabela
    function adicionar_ou_atualizar_contato($dados) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pdr_contacts';
    
        // Certifique-se de que o índice 'name' esteja presente no array $dados.
        // Use 'default_name' como fallback se 'name' não estiver disponível.
        $nome = isset($dados['name']) ? $dados['name'] : (isset($dados['default_name']) ? $dados['default_name'] : null);
    
        if (!$nome) {
            // Log de erro ou manipulação se o nome não estiver disponível
            error_log('Nome não fornecido para adicionar ou atualizar contato.');
            return;
        }
    
        // Verifica se o e-mail já existe na tabela
        $contato_existente = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", $dados['email']));
    
        if (null !== $contato_existente) {
            // Atualiza contato existente com o novo nome
            $wpdb->update($table_name, ['default_name' => $nome], ['email' => $dados['email']]);
        } else {
            // Insere novo contato com o e-mail e o nome fornecidos
            $wpdb->insert($table_name, ['email' => $dados['email'], 'default_name' => $nome]);
        }
    }
    


    // Função para recuperar informações de contato
    function recuperar_informacoes_contato($email) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pdr_contacts';

        return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE email = %s", $email), ARRAY_A);
    }

