<?php

    defined('ABSPATH') or die('No script kiddies please!');
// export-services.php

function export_services_to_csv() {
    // Verifica se o usuário atual tem permissão para exportar
    if (!current_user_can('export')) {
        wp_die('Você não tem permissão para acessar esta funcionalidade.');
    }

    // Define o tipo de post que será exportado
    $args = array(
        'post_type' => 'professional_service', // Substitua pelo slug correto do seu post type
        'posts_per_page' => -1
    );

    $services = get_posts($args);

    // Verifica se há serviços para exportar
    if (empty($services)) {
        wp_die('Nenhum serviço disponível para exportação.');
    }

    // Define os cabeçalhos para o download do arquivo CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="services.csv"');

    // Abre a saída do PHP para escrita do arquivo CSV
    $output = fopen('php://output', 'w');

    // Define e escreve os cabeçalhos das colunas no CSV
    fputcsv($output, array('ID', 'Title', 'Date'));

    // Percorre os serviços e escreve as linhas no CSV
    foreach ($services as $service) {
        fputcsv($output, array(
            $service->ID,
            get_the_title($service->ID),
            $service->post_date
        ));
    }

    // Fecha a saída e encerra o script
    fclose($output);
    exit;
}
