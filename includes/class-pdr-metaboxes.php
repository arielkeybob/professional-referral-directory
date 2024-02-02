<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// require_once 'class-pdr-geolocation.php';

class PDR_Metaboxes {

    // Função para inicializar os meta boxes
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post', [self::class, 'save_meta_boxes']);
    }

    // Função para adicionar meta boxes
    public static function add_meta_boxes() {
        /*
        add_meta_box(
            'pdr_service_location',
            __('Service Location', 'professionaldirectory'),
            [self::class, 'render_location_meta_box'],
            'professional_service', // Tipo de post
            'side',
            'default'
        );
        */

        // Novo metabox para preferência de recebimento de e-mail
        add_meta_box(
            'pdr_email_preference',
            __('Preferência de Recebimento de E-mail', 'professionaldirectory'),
            [self::class, 'render_email_preference_meta_box'],
            'professional_service', // Tipo de post
            'side',
            'high'
        );
    }
    /*
    // Função para renderizar o meta box de localização
    public static function render_location_meta_box($post) {
        wp_nonce_field('pdr_save_location_meta_box_data', 'pdr_location_meta_box_nonce');

        // Obtém os valores meta existentes
        $location = get_post_meta($post->ID, '_pdr_service_location', true);
        
        $latitude = get_post_meta($post->ID, '_pdr_service_latitude', true);
        $longitude = get_post_meta($post->ID, '_pdr_service_longitude', true);

        echo '<label for="pdr_service_location">' . esc_html__('Address:', 'professionaldirectory') . '</label>';
        echo '<input type="text" id="pdr_service_location" name="pdr_service_location" value="' . esc_attr($location) . '" class="widefat" />';
        echo '<p>' . esc_html__('Latitude:', 'professionaldirectory') . ' <span id="pdr_latitude_display">' . esc_html($latitude) . '</span></p>';
        echo '<p>' . esc_html__('Longitude:', 'professionaldirectory') . ' <span id="pdr_longitude_display">' . esc_html($longitude) . '</span></p>';
        
    }
    */

    // Função para renderizar o meta box de preferência de e-mail
    public static function render_email_preference_meta_box($post) {
        wp_nonce_field('pdr_save_email_preference_data', 'pdr_email_preference_nonce');

        // Obtém o valor meta existente
        $email_preference = get_post_meta($post->ID, '_pdr_email_preference', true);

        // Checkbox para a preferência de e-mail
        echo '<label>';
        echo '<input type="checkbox" name="pdr_email_preference" value="1"' . checked($email_preference, '1', false) . '>';
        esc_html_e('Desejo receber notificações por e-mail para este serviço', 'professionaldirectory');
        echo '</label>';
    }

    // Função para salvar os dados dos meta boxes
    public static function save_meta_boxes($post_id) {
        // Verificação de segurança
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_id) || !isset($_POST['pdr_location_meta_box_nonce'], $_POST['pdr_email_preference_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['pdr_location_meta_box_nonce'], 'pdr_save_location_meta_box_data') || !wp_verify_nonce($_POST['pdr_email_preference_nonce'], 'pdr_save_email_preference_data')) {
            return;
        }

        // Salva a localização
        /*
        if (isset($_POST['pdr_service_location'])) {
            update_post_meta($post_id, '_pdr_service_location', sanitize_text_field($_POST['pdr_service_location']));

            // Geocodificar o endereço e salvar as coordenadas
            /*
            $coordinates = PDR_Geolocation::geocode_address($_POST['pdr_service_location']);
            if ($coordinates) {
                update_post_meta($post_id, '_pdr_service_latitude', $coordinates['latitude']);
                update_post_meta($post_id, '_pdr_service_longitude', $coordinates['longitude']);
            }
        }
        */

        // Salva a preferência de e-mail
        $email_preference = isset($_POST['pdr_email_preference']) ? '1' : '0';
        update_post_meta($post_id, '_pdr_email_preference', $email_preference);
    }
}

// Inicializa os meta boxes
PDR_Metaboxes::init();
