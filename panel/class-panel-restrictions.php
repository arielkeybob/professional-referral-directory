<?php
    defined('ABSPATH') or die('No script kiddies please!');

class ProfessionalDirectory_Admin {
    // Construtor da classe
    public function __construct() {
        add_action('pre_get_posts', [$this, 'restrict_service_view']);
        add_filter('views_edit-pdr_service', [$this, 'adjust_service_views']);
    }

    // Função para restringir a visualização de serviços no admin
    public function restrict_service_view($query) {
        if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'pdr_service') {
            if (current_user_can('service_provider') && !current_user_can('administrator')) {
                $query->set('author', get_current_user_id());
            }
        }
    }

    // Função para ajustar as abas na tela de listagem de serviços
    public function adjust_service_views($views) {
        if (get_post_type() === 'pdr_service' && current_user_can('service_provider') && !current_user_can('administrator')) {
            // Remover a aba "All"
            if (isset($views['all'])) {
                unset($views['all']);
            }

            // Definir a aba "Mine" como a aba padrão ativa
            if (isset($views['mine'])) {
                $views['mine'] = str_replace('class="', 'class="current ', $views['mine']);
                // Ajustar o link para não filtrar por status
                $views['mine'] = preg_replace('/<a(.*)>(.*)<\/a>/', '<a$1 aria-current="page">$2</a>', $views['mine']);
            }
        }

        return $views;
    }
}

// Instanciar a classe
new ProfessionalDirectory_Admin();
