<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
    die;
}

class ProfessionalDirectory_MetaBoxes {

    public function __construct() {
        add_action( 'add_meta_boxes', [ $this, 'add_custom_meta_box' ] );
        add_action( 'save_post', [ $this, 'save_custom_meta' ] );
    }

    // Função para adicionar meta box
    public function add_custom_meta_box() {
        add_meta_box(
            'service_details',                // ID único para a meta box
            __('Service Details', 'professionaldirectory'), // Título da meta box
            [ $this, 'custom_meta_box_html' ], // Callback que renderiza o HTML da meta box
            'professional_service',           // Tipo de post onde a meta box deve aparecer
            'normal',                         // Contexto onde a meta box deve aparecer ('normal', 'side', 'advanced')
            'high'                            // Prioridade dentro do contexto onde a meta box deve aparecer
        );
    }

    // Callback para renderizar o HTML da meta box
    public function custom_meta_box_html( $post ) {
        // Adicionar campos personalizados aqui
        $value = get_post_meta( $post->ID, '_service_details', true );

        // Nonce field para validação
        wp_nonce_field( 'custom_meta_box_nonce', 'meta_box_nonce' );

        // HTML para a meta box
        echo '<label for="service_details">' . __('Service Details', 'professionaldirectory') . '</label>';
        echo '<input type="text" id="service_details" name="service_details" value="' . esc_attr( $value ) . '" />';
    }

    // Função para salvar os dados do meta box
    public function save_custom_meta( $post_id ) {
        // Verificar se o nonce é válido
        if ( ! isset( $_POST['meta_box_nonce'] ) || ! wp_verify_nonce( $_POST['meta_box_nonce'], 'custom_meta_box_nonce' ) ) {
            return;
        }

        // Verificar se o campo está definido
        if ( ! isset( $_POST['service_details'] ) ) {
            return;
        }

        // Verificar permissões de usuário
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Atualizar os dados no banco de dados
        update_post_meta( $post_id, '_service_details', sanitize_text_field( $_POST['service_details'] ) );
    }
}

// Instanciar a classe
new ProfessionalDirectory_MetaBoxes();
