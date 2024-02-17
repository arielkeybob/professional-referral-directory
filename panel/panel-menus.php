<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Adiciona o submenu dashboard ao menu do post type Services
function pdr_add_dashboard_capability() {
    $role = get_role('professional');
    if ($role) {
        $role->add_cap('view_pdr_dashboard');
    }
}
add_action('init', 'pdr_add_dashboard_capability');

function pdr_add_dashboard_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Professional',
        'Dashboard',
        'view_pdr_dashboard',
        'pdr-dashboard',
        'pdr_dashboard_page_content'
    );
}
add_action('admin_menu', 'pdr_add_dashboard_submenu');


function pdr_add_contacts_capability() {
    $role = get_role('professional');
    if ($role) {
        $role->add_cap('view_pdr_contacts');
    }
}
add_action('init', 'pdr_add_contacts_capability');

function pdr_add_contacts_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Gerenciamento de Contatos',
        'Contatos',
        'view_pdr_contacts',
        'pdr-contacts',
        'pdr_contacts_page_content'
    );
}
add_action('admin_menu', 'pdr_add_contacts_submenu');


function pdr_dashboard_page_content() {
    include 'templates/dashboard-template-professional.php';
}

add_action('admin_menu', 'add_custom_submenu_page');

function add_custom_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Dashboard do Admin',
        'Dashboard do Admin',
        'manage_options',
        'dashboard-admin',
        'dashboard_admin_page_callback'
    );
}

function dashboard_admin_page_callback() {
    include 'templates/dashboard-template-admin.php';
}

function pdr_add_shortcodes_help_page() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        'Ajuda de Shortcodes',
        'Ajuda de Shortcodes',
        'manage_options',
        'pdr-shortcodes-help',
        'pdr_render_shortcodes_help_page'
    );
}
add_action('admin_menu', 'pdr_add_shortcodes_help_page');

function pdr_render_shortcodes_help_page() {
    include plugin_dir_path(__FILE__) . '/shortcodes-help-page.php';
}

// Adicionando a página de configurações gerais ao submenu
function pdr_add_settings_submenu() {
    add_submenu_page(
        'edit.php?post_type=professional_service',
        __('General Settings', 'professionaldirectory'),
        __('Settings', 'professionaldirectory'),
        'manage_options',
        'settings',
        'pdr_settings_page'
    );
}
add_action('admin_menu', 'pdr_add_settings_submenu');

// Inclui o arquivo da classe das configurações para renderizar a página
function pdr_settings_page() {
    require_once plugin_dir_path(__FILE__) . 'class-settings-page.php';
    $settings_page = new PDR_Settings();
    $settings_page->settings_page();
}


function pdr_contacts_page_content() {
    if (!current_user_can('view_pdr_contacts')) {
        wp_die(__('Você não tem permissão para acessar esta página.', 'professionaldirectory'));
    }

    $args = [
        'post_type' => 'contato', // Certifique-se de que 'contato' é o slug correto do seu CPT
        'posts_per_page' => -1, // Buscar todos os contatos
        'post_status' => 'publish', // Apenas contatos publicados
    ];

    $contatos_query = new WP_Query($args);

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Gerenciamento de Contatos', 'professionaldirectory') . '</h1>';

    if ($contatos_query->have_posts()) {
        // Inicia a tabela de contatos.
        echo '<table class="wp-list-table widefat fixed striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>' . esc_html__('Nome', 'professionaldirectory') . '</th>';
        echo '<th>' . esc_html__('Email', 'professionaldirectory') . '</th>';
        echo '<th>' . esc_html__('Status', 'professionaldirectory') . '</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        while ($contatos_query->have_posts()) {
            $contatos_query->the_post();
            $post_id = get_the_ID();
            $email = get_post_meta($post_id, '_contato_email', true);
            $status = get_post_meta($post_id, '_contato_status', true);

            echo '<tr>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . esc_html($email) . '</td>';
            echo '<td>' . esc_html($status) . '</td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo '<p>' . esc_html__('Nenhum contato encontrado.', 'professionaldirectory') . '</p>';
    }

    echo '</div>';

    // Restaura a consulta global original após um loop customizado
    wp_reset_postdata();
}

