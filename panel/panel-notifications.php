<?php
    defined('ABSPATH') or die('No script kiddies please!');
    
function rhb_obter_notificacoes_ativas() {
    $caminho_json = plugin_dir_path( __FILE__ ) . 'notifications/notifications.json';
    $notificacoes = json_decode( file_get_contents( $caminho_json ), true );
    return array_filter($notificacoes, function($notification) {
        return $notification['status'] === 'ativo';
    });
}


add_action('admin_notices', 'rhb_exibir_notificacoes_admin', 5);
function rhb_exibir_notificacoes_admin() {
    global $pagenow;
    if (!current_user_can('manage_options')) {
        return;
    }
    $screen = get_current_screen();
    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'rhb_service') {
        if ($_GET['page'] != 'rhb-general-settings' && $_GET['page'] != 'dashboard-admin') {
            return;
        }
    } else {
        return;
    }

    $notificacoes = rhb_obter_notificacoes_ativas();
echo '<div id="rhb-notifications-container">';
echo '<div class="rhb-notification-slider">';
foreach ($notificacoes as $notification) {
    if (!isset($_SESSION['rhb_notification_fechada_' . $notification['id']])) {
        $imagem_url = plugins_url('img/' . $notification['imagem'], __FILE__);  // Constrói o caminho absoluto
        echo "<div class='notice notice-info is-dismissible rhb-notification' data-notification-id='{$notification['id']}'>
                <img src='{$imagem_url}' class='rhb-notification-icon' alt='Notification Icon'>
                <div class='rhb-notification-content'>
                    <p><strong>{$notification['titulo']}</strong></p>
                    <p>{$notification['mensagem']}</p>
                </div>
              </div>";
    }
}
echo '</div><div class="rhb-notification-controls"><button class="prev">Prev</button><button class="next">Next</button></div>';
echo '</div>';
}


