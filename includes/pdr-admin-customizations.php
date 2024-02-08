
<?php
// pdr-admin-customizations.php

function pdrRemoveAdminBar() {
    if (current_user_can('professional')) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'pdrRemoveAdminBar');

function pdrRemoveDashboardMenu() {
    if (current_user_can('professional')) {
        remove_menu_page('index.php'); // Remove "Dashboard"
    }
}
add_action('admin_menu', 'pdrRemoveDashboardMenu');

function pdrEnqueueCustomAdminStyle() {
    if (current_user_can('professional')) {
        wp_enqueue_style('pdr-custom-admin-style', plugin_dir_url(__FILE__) . '../admin/css/pdr-admin-customizations.css');
    }
}
add_action('admin_enqueue_scripts', 'pdrEnqueueCustomAdminStyle');




// Filtro para alterar o texto padrão do rodapé Wordpress "Thank you for creating with WordPress."
function pdr_remove_footer_admin () {
    return 'PDR Plugin - By Ariel Souza';
}
add_filter('admin_footer_text', 'pdr_remove_footer_admin');


// Altera o texto sobre a versão do WordPress do rodapé no admin
function pdr_remove_footer_version() {
    return 'Version: ' . PDR_VERSION;
}
add_filter('update_footer', 'pdr_remove_footer_version', 9999);



function pdr_remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}


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
        // Defina o caminho relativo da logo
        $logo_url = '/wp-content/uploads/2024/02/logo-exemplo.jpg'; // Ajuste conforme necessário
        ?>
        <div id="pdr-custom-admin-bar" style="display: flex; justify-content: space-between; align-items: center; width: 100%; background-color: #23282d; padding: 10px 0;">
            <div id="pdr-custom-logo" style="width: 17%; display: flex; justify-content: center; align-items: center;">
                <a href="<?php echo home_url(); ?>">
                    <!-- Ajusta a imagem da logo para se adaptar à largura da div -->
                    <img src="<?php echo home_url($logo_url); ?>" alt="Logo do Site" style="max-width: 100%; max-height: 50px;">
                </a>
            </div>
            <div id="pdr-custom-user" style="width: 83%; display: flex; justify-content: flex-end; align-items: center;">
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




function pdr_custom_admin_bar_styles() {
    // Verifique se o usuário está logado e no painel de administração antes de adicionar os estilos
    if (is_user_logged_in() && is_admin()) {
        echo '<style>
            #pdr-custom-admin-bar {
                background-color: #23282d;
                color: white;
                padding: 10px 0;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 99999;
            }
            #wpcontent, #adminmenuwrap {
                margin-top: 70px; /* Ajuste conforme a altura da sua barra personalizada */
            }
        </style>';
    }
}
add_action('admin_head', 'pdr_custom_admin_bar_styles');


