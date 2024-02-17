<?php
// Verifica se o arquivo foi chamado diretamente.
if (!defined('WPINC')) {
    die;
}

class Contatos_Metabox {
    public function __construct() {
        add_action('add_meta_boxes', array($this, 'adicionar_metabox_contato'));
        add_action('save_post', array($this, 'salvar_metadados_contato'));
    }

    /**
     * Adiciona o metabox ao CPT de contatos.
     */
    public function adicionar_metabox_contato() {
        add_meta_box(
            'informacoes_contato', // ID do Metabox
            __('Informações do Contato', 'professionaldirectory'), // Título do Metabox
            array($this, 'renderizar_metabox'), // Callback para renderizar o conteúdo do Metabox
            'contato' // CPT ao qual o Metabox será adicionado
        );
    }

    /**
     * Renderiza o conteúdo do metabox.
     */
    public function renderizar_metabox($post) {
        // Adicione um nonce para verificação
        wp_nonce_field('seguranca_metabox_contato', 'contato_nonce');
        
        // Recupera os valores dos metadados, se disponíveis
        $email = get_post_meta($post->ID, '_contato_email', true);
        $status = get_post_meta($post->ID, '_contato_status', true);
        
        // Campos do formulário para entrada dos metadados
        echo '<label for="contato_email">' . __('Email do Contato', 'professionaldirectory') . '</label>';
        echo '<input type="email" id="contato_email" name="contato_email" value="' . esc_attr($email) . '" class="widefat">';
        
        echo '<label for="contato_status">' . __('Status', 'professionaldirectory') . '</label>';
        echo '<select id="contato_status" name="contato_status" class="widefat">';
        echo '<option value="lead" ' . selected($status, 'lead', false) . '>' . __('Lead', 'professionaldirectory') . '</option>';
        echo '<option value="prospect" ' . selected($status, 'prospect', false) . '>' . __('Prospect', 'professionaldirectory') . '</option>';
        echo '<option value="cliente" ' . selected($status, 'cliente', false) . '>' . __('Cliente', 'professionaldirectory') . '</option>';
        echo '</select>';
    }

    /**
     * Salva os metadados personalizados quando o post é salvo.
     */
    public function salvar_metadados_contato($post_id) {
        // Verifica o nonce
        if (!isset($_POST['contato_nonce']) || !wp_verify_nonce($_POST['contato_nonce'], 'seguranca_metabox_contato')) {
            return;
        }

        // Verifica se o campo está definido e salva os metadados
        if (isset($_POST['contato_email'])) {
            update_post_meta($post_id, '_contato_email', sanitize_email($_POST['contato_email']));
        }

        if (isset($_POST['contato_status'])) {
            update_post_meta($post_id, '_contato_status', sanitize_text_field($_POST['contato_status']));
        }
    }
}

new Contatos_Metabox();
