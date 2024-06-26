<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para verificar se a página atual é uma página do plugin
function rhb_is_plugin_page() {
    $screen = get_current_screen();
    if ($screen->post_type === 'rhb_service' || $screen->id === 'rhb_page_rhb-settings') {
        return true;
    }
    return false;
}

// Função para obter notificações ativas do arquivo JSON
function rhb_obter_notificacoes_ativas() {
    $caminho_json = plugin_dir_path(__FILE__) . 'notifications/notifications.json';
    $notificacoes = json_decode(file_get_contents($caminho_json), true);
    return array_filter($notificacoes, function($notification) {
        return $notification['status'] === 'ativo';
    });
}

// Manipulador para fechar notificações via AJAX
add_action('wp_ajax_rhb_notification_closed', 'rhb_notification_closed_handler');
function rhb_notification_closed_handler() {
    $user_id = get_current_user_id();
    $notification_id = isset($_POST['notification_id']) ? $_POST['notification_id'] : '';
    if ($notification_id) {
        // Recupera o array atual de notificações closed ou inicializa como array vazio
        $closed = get_user_meta($user_id, 'rhb_notifications_closed', true);
        if (!is_array($closed)) {
            $closed = [];
        }
        // Adiciona a nova notificação ao array, se ainda não estiver lá
        if (!in_array($notification_id, $closed)) {
            $closed[] = $notification_id;
        }
        // Atualiza o metadado com o novo array
        update_user_meta($user_id, 'rhb_notifications_closed', $closed);
    }
    wp_die();
}



// Exibe notificações no painel de administração
add_action('admin_notices', 'rhb_exibir_notificacoes_admin');
function rhb_exibir_notificacoes_admin() {
    if (!current_user_can('manage_options') || !rhb_is_plugin_page()) {
        return;
    }

    $user_id = get_current_user_id();
    $closed = get_user_meta($user_id, 'rhb_notifications_closed', true);
    if (!is_array($closed)) {
        $closed = [];
    }

    $notificacoes = rhb_obter_notificacoes_ativas();
    $notificacoes_ativas = array_filter($notificacoes, function($notification) use ($closed) {
        return !in_array($notification['id'], $closed);
    });

    if (!empty($notificacoes_ativas)) {
        echo '<div id="rhb-notifications-container">';
        echo '<div class="rhb-notification-slider">';

        foreach ($notificacoes_ativas as $notification) {
            $imagem_url = plugins_url('img/' . $notification['imagem'], __FILE__);
            echo "<div class='notice notice-info is-dismissible rhb-notification' data-notification-id='{$notification['id']}'>
                    <img src='{$imagem_url}' class='rhb-notification-icon' alt='Notification Icon'>
                    <div class='rhb-notification-content'>
                        <p><strong>{$notification['titulo']}</strong></p>
                        <p>{$notification['mensagem']}</p>
                    </div>
                    <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
                  </div>";
        }

        echo '</div><div class="rhb-notification-controls"><button class=\'prev\'>Prev</button><button class=\'next\'>Next</button></div>';
        echo '</div>';
    }
}



