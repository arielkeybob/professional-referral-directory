<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class PDR_Metaboxes {

    // Função para inicializar os meta boxes
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post', [self::class, 'save_meta_boxes']);
    }

    // Função para adicionar meta boxes
    public static function add_meta_boxes() {
        // Adiciona o meta box de localização (inativo no momento)
        add_meta_box(
            'pdr_service_location',
            'Localização do Serviço',
            [self::class, 'render_location_meta_box'],
            'professional_service', // Altere para o tipo de post correto se necessário
            'side',
            'default'
        );
    }

    // Função para renderizar o meta box de localização
    public static function render_location_meta_box($post) {
        // Adiciona um campo nonce para verificação
        wp_nonce_field('pdr_save_location_meta_box_data', 'pdr_location_meta_box_nonce');

        // Obtém o valor meta existente
        $location = get_post_meta($post->ID, '_pdr_service_location', true);

        // Campo de localização (por enquanto, inativo)
        echo '<label for="pdr_service_location">Localização:</label>';
        echo '<input type="text" id="pdr_service_location" name="pdr_service_location" value="' . esc_attr($location) . '" disabled />';
    }

    // Função para salvar os dados dos meta boxes
    public static function save_meta_boxes($post_id) {
        // Verifica se o post é autosave ou uma revisão
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_id)) {
            return;
        }

        // Salvar o meta box de localização (quando ativado)
        if (isset($_POST['pdr_location_meta_box_nonce']) && wp_verify_nonce($_POST['pdr_location_meta_box_nonce'], 'pdr_save_location_meta_box_data')) {
            if (isset($_POST['pdr_service_location'])) {
                update_post_meta($post_id, '_pdr_service_location', sanitize_text_field($_POST['pdr_service_location']));
            }
        }
    }
}

// Inicializa os meta boxes
PDR_Metaboxes::init();
