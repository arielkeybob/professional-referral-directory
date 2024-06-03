<?php
    defined('ABSPATH') or die('No script kiddies please!');
    
// includes\panel-top-bar-customizations.php

function rhbRemoveAdminBar() {
    if (current_user_can('service_provider')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'rhbRemoveAdminBar');

function rhb_hide_admin_bar_in_dashboard() {
    echo '<style type="text/css">
        #wpadminbar { display: none !important; }
        html.wp-toolbar { padding-top: 0 !important; }
    </style>';
}
add_action('admin_head', 'rhb_hide_admin_bar_in_dashboard');

function rhb_add_custom_admin_bar() {
    if (is_user_logged_in() && is_admin()) {
        $current_user = wp_get_current_user();
        $profile_url = get_edit_profile_url($current_user->ID);
        $logout_url = wp_logout_url();
        $avatar_url = get_avatar_url($current_user->ID);

        // Obtem o ID da imagem da logo salvo nas opções do tema
        $logo_id = get_option('rhb_panel_logo');
        // Obtem a URL da imagem a partir do ID
        $logo_url = wp_get_attachment_url($logo_id);
        
        // Se nenhuma imagem foi definida, você pode definir uma imagem padrão
        if (!$logo_url) {
            $logo_url = plugin_dir_url(__FILE__) . '../panel/img/default-logo.png'; // Caminho para a logo padrão
        }

        $bell_notification_url = plugin_dir_url(__FILE__) . '../public/img/Bell-Notification.png'; 
        $help_icon_url = plugin_dir_url(__FILE__) . '../public/img/help-icon.png'; 

        ?>
        <div id="rhb-custom-admin-bar" style="width: 100%; display: flex;">
            <div id="rhb-custom-logo" style="width: 17%; display: flex; justify-content: center; align-items: center;">
                <a href="<?php echo home_url(); ?>">
                    <!-- Aqui usamos a URL da imagem da logo -->
                    <img src="<?php echo esc_url($logo_url); ?>" alt="Site Logo" style="max-width: 100%; max-height: 50px;">
                </a>
            </div>
            <div id="rhb-custom-user" style="width: 83%; display: flex; justify-content: flex-end; align-items: center;">
                <!-- Ícone de ajuda -->
                <div class="rhb-icon-help" style="margin-right: 20px;">
                    <a href="#" title="Ajuda">
                    <img src="<?php echo esc_url($help_icon_url); ?>" alt="Help" style="height: 24px; width: 24px;">
                    </a> <!-- Link de ajuda será adicionado aqui -->
                </div>
                <!-- Ícone de notificações -->
                <div class="rhb-icon-notification" style="margin-right: 20px;">
                    <a href="#" title="Notificações" class="rhb-dash-notifications">
                        <img src="<?php echo esc_url($bell_notification_url); ?>" alt="Notifications" style="height: 24px; width: 24px;">
                    </a>
                </div>
                <!-- Imagem do usuário e dropdown -->
                <img src="<?php echo esc_url($avatar_url); ?>" alt="User Avatar" id="rhb-user-avatar" style="cursor: pointer; height: 50px; width: 50px; border-radius: 50%; margin-right: 20px;">
                <div id="rhb-user-dropdown" style="display: none; position: absolute; background-color: #f9f9f9; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); padding: 12px; z-index: 100;">
                    <a href="<?php echo esc_url($profile_url); ?>" style="color: black; padding: 12px; text-decoration: none; display: block;">Ver Perfil</a>
                    <a href="<?php echo esc_url($logout_url); ?>" style="color: black; padding: 12px; text-decoration: none; display: block;">Logout</a>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('in_admin_header', 'rhb_add_custom_admin_bar');




function rhb_custom_admin_bar_scripts() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var avatar = document.getElementById('rhb-user-avatar');
            var dropdown = document.getElementById('rhb-user-dropdown');
            
            avatar.onclick = function() {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            };

            // Opcional: Fechar o dropdown ao clicar fora
            window.onclick = function(event) {
                if (!event.target.matches('#rhb-user-avatar')) {
                    if (dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    }
                }
            };
        });
    </script>
    <?php
}
add_action('admin_head', 'rhb_custom_admin_bar_scripts');

function rhb_enqueue_dashicons() {
    wp_enqueue_style('dashicons');
}
add_action('admin_enqueue_scripts', 'rhb_enqueue_dashicons');
