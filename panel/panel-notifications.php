<?php
defined('ABSPATH') or die('No script kiddies please!');

function rhb_obter_notificacoes_ativas() {
    $caminho_json = plugin_dir_path( __FILE__ ) . 'notifications/notifications.json';
    $notificacoes = json_decode(file_get_contents($caminho_json), true);
    return array_filter($notificacoes, function($notification) {
        return $notification['status'] === 'ativo';
    });
}

add_action('wp_ajax_rhb_notification_fechada', 'rhb_notification_fechada_handler');
function rhb_notification_fechada_handler() {
    $user_id = get_current_user_id(); // Obtém o ID do usuário atual
    $notification_id = isset($_POST['notification_id']) ? $_POST['notification_id'] : '';
    if ($notification_id) {
        update_user_meta($user_id, 'rhb_notification_fechada_' . $notification_id, true); // Marca a notificação como fechada para este usuário
    }
    wp_die(); // Finaliza a execução de forma adequada para ações AJAX
}

add_action('admin_notices', 'rhb_exibir_notificacoes_admin', 5);
function rhb_exibir_notificacoes_admin() {
    global $pagenow;
    if (!current_user_can('manage_options')) {
        return;
    }

    $user_id = get_current_user_id();
    $notificacoes = rhb_obter_notificacoes_ativas();
    echo '<div id="rhb-notifications-container">';
    echo '<div class="rhb-notification-slider">';
    foreach ($notificacoes as $notification) {
        if (!get_user_meta($user_id, 'rhb_notification_fechada_' . $notification['id'], true)) {
            $imagem_url = plugins_url('img/' . $notification['imagem'], __FILE__); // Constrói o caminho absoluto
            echo "<div class='notice notice-info is-dismissible rhb-notification' data-notification-id='{$notification['id']}'>
                    <img src='{$imagem_url}' class='rhb-notification-icon' alt='Notification Icon'>
                    <div class='rhb-notification-content'>
                        <p><strong>{$notification['titulo']}</strong></p>
                        <p>{$notification['mensagem']}</p>
                    </div>
                    <button type='button' class='notice-dismiss'><span class='screen-reader-text'>Dismiss this notice.</span></button>
                  </div>";
        }
    }
    echo '</div><div class="rhb-notification-controls"><button class=\'prev\'>Prev</button><button class=\'next\'>Next</button></div>';
    echo '</div>';
}
