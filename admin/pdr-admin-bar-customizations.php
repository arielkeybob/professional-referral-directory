<?php
// includes\pdr-admin-bar-customizations.php

function pdrRemoveAdminBar() {
    if (current_user_can('professional')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'pdrRemoveAdminBar');

function pdr_hide_admin_bar_in_dashboard() {
    echo '<style type="text/css">
        #wpadminbar { display: none !important; }
        html.wp-toolbar { padding-top: 0 !important; }
    </style>';
}
add_action('admin_head', 'pdr_hide_admin_bar_in_dashboard');

function pdr_add_custom_admin_bar() {
    if (is_user_logged_in() && is_admin()) {
        $current_user = wp_get_current_user();
        $profile_url = get_edit_profile_url($current_user->ID);
        $logout_url = wp_logout_url();
        $avatar_url = get_avatar_url($current_user->ID);
        // Ajuste o caminho conforme necessário, dependendo da localização do seu arquivo PHP em relação à raiz do WordPress
        $logo_url = '/wp-content/uploads/2024/02/logo-exemplo.jpg';
        $bell_notification_url = plugin_dir_url(__FILE__) . '../public/img/Bell-Notification.png'; // Ajuste este caminho
        $hepl_icon_url = plugin_dir_url(__FILE__) . '../public/img/help-icon.png';

        ?>
        <div id="pdr-custom-admin-bar">
            <div id="pdr-custom-logo" style="width: 17%; display: flex; justify-content: center; align-items: center;">
                <a href="<?php echo home_url(); ?>">
                    <img src="<?php echo home_url($logo_url); ?>" alt="Logo do Site" style="max-width: 100%; max-height: 50px;">
                </a>
            </div>
            <div id="pdr-custom-user" style="width: 83%; display: flex; justify-content: flex-end; align-items: center;">
                <!-- Ícone de ajuda -->
                <div class="pdr-icon-help" style="margin-right: 20px;">
                    <a href="#" title="Ajuda">
                    <img src="<?php echo esc_url($hepl_icon_url); ?>" alt="Hepl" style="height: 24px; width: 24px;">
                    </a> <!-- Link de ajuda será adicionado aqui -->
                </div>
                <!-- Ícone de notificações -->
                <div class="pdr-icon-notification" style="margin-right: 20px;">
                    <a href="#" title="Notificações" class="pdr-dash-notifications">
                        <img src="<?php echo esc_url($bell_notification_url); ?>" alt="Notifications" style="height: 24px; width: 24px;">
                    </a>
                </div>
                <!-- Imagem do usuário e dropdown -->
                <img src="<?php echo esc_url($avatar_url); ?>" alt="User Avatar" id="pdr-user-avatar" style="cursor: pointer; height: 50px; width: 50px; border-radius: 50%; margin-right: 20px;">
                <div id="pdr-user-dropdown" style="display: none; position: absolute; background-color: #f9f9f9; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); padding: 12px; z-index: 100;">
                    <a href="<?php echo esc_url($profile_url); ?>" style="color: black; padding: 12px; text-decoration: none; display: block;">Ver Perfil</a>
                    <a href="<?php echo esc_url($logout_url); ?>" style="color: black; padding: 12px; text-decoration: none; display: block;">Logout</a>
                </div>
            </div>
        </div>
        <?php
    }
}
add_action('in_admin_header', 'pdr_add_custom_admin_bar');




function pdr_custom_admin_bar_scripts() {
    ?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var avatar = document.getElementById('pdr-user-avatar');
            var dropdown = document.getElementById('pdr-user-dropdown');
            
            avatar.onclick = function() {
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            };

            // Opcional: Fechar o dropdown ao clicar fora
            window.onclick = function(event) {
                if (!event.target.matches('#pdr-user-avatar')) {
                    if (dropdown.style.display === 'block') {
                        dropdown.style.display = 'none';
                    }
                }
            };
        });
    </script>
    <?php
}
add_action('admin_head', 'pdr_custom_admin_bar_scripts');

function pdr_enqueue_dashicons() {
    wp_enqueue_style('dashicons');
}
add_action('admin_enqueue_scripts', 'pdr_enqueue_dashicons');
