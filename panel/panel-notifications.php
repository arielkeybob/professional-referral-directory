<?php

function pdr_obter_notificacoes_ativas() {
    $caminho_json = plugin_dir_path( __FILE__ ) . 'notifications/notifications.json';
    $notificacoes = json_decode( file_get_contents( $caminho_json ), true );
    return array_filter($notificacoes, function($notificacao) {
        return $notificacao['status'] === 'ativo';
    });
}


add_action('admin_notices', 'pdr_exibir_notificacoes_admin');
function pdr_exibir_notificacoes_admin() {
    global $pagenow;

    // Verifica se o usuário tem permissão de administração
    if (!current_user_can('manage_options')) {
        return;
    }

    // Obtem a tela atual
    $screen = get_current_screen();

    // Verifica se está na tela de configurações do plugin ou no dashboard
    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'professional_service') {
        if ($_GET['page'] != 'myplugin' && $_GET['page'] != 'dashboard-admin') {
            return;
        }
    } else {
        return;
    }

    $notificacoes = pdr_obter_notificacoes_ativas();
    foreach ($notificacoes as $notificacao) {
        // Verifica se a notificação já foi fechada pelo usuário
        if (!isset($_SESSION['pdr_notificacao_fechada_' . $notificacao['id']])) {
            echo "<div class='notice notice-info is-dismissible pdr-notificacao' data-notificacao-id='{$notificacao['id']}'><p><strong>{$notificacao['titulo']}</strong></p><p>{$notificacao['mensagem']}</p></div>";
        }
    }
}

