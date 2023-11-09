<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class ProfessionalDirectory_Admin {
    public static function init() {
        add_action('pre_get_posts', [self::class, 'restrict_service_view']);
    }

    public static function restrict_service_view($query) {
        if (is_admin() && $query->is_main_query() && $query->get('post_type') === 'service') {
            // Verifique se a função existe para evitar erros em uma instalação limpa do WordPress.
            if (function_exists('get_current_user_id') && !current_user_can('administrator')) {
                $user_id = get_current_user_id();
                // Ajuste a verificação do papel se necessário.
                if ($user = get_userdata($user_id) && in_array('professional', (array) $user->roles)) {
                    $query->set('author', $user_id);
                }
            }
        }
    }
}

// Inicializa a classe de administração.
ProfessionalDirectory_Admin::init();
