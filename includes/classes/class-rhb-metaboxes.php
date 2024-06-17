<?php
defined('ABSPATH') or die('No script kiddies please!');

// require_once 'class-rhb-geolocation.php';

class RHB_Metaboxes {

    // Função para inicializar os meta boxes
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post', [self::class, 'save_meta_boxes']);
    }

    // Função para adicionar meta boxes
    public static function add_meta_boxes() {
        /*
        add_meta_box(
            'rhb_service_location',
            __('Service Location', 'referralhub'),
            [self::class, 'render_location_meta_box'],
            'rhb_service', // Tipo de post
            'side',
            'default'
        );
        */

        // Novo metabox para preferência de recebimento de e-mail
        add_meta_box(
            'rhb_email_preference',
            __('Preferência de Recebimento de E-mail', 'referralhub'),
            [self::class, 'render_email_preference_meta_box'],
            'rhb_service', // Tipo de post
            'side',
            'high'
        );
    }
    /*
    // Função para renderizar o meta box de localização
    public static function render_location_meta_box($post) {
        wp_nonce_field('rhb_save_location_meta_box_data', 'rhb_location_meta_box_nonce');

        // Obtém os valores meta existentes
        $location = get_post_meta($post->ID, '_rhb_service_location', true);
        
        $latitude = get_post_meta($post->ID, '_rhb_service_latitude', true);
        $longitude = get_post_meta($post->ID, '_rhb_service_longitude', true);

        echo '<label for="rhb_service_location">' . esc_html__('Address:', 'referralhub') . '</label>';
        echo '<input type="text" id="rhb_service_location" name="rhb_service_location" value="' . esc_attr($location) . '" class="widefat" />';
        echo '<p>' . esc_html__('Latitude:', 'referralhub') . ' <span id="rhb_latitude_display">' . esc_html($latitude) . '</span></p>';
        echo '<p>' . esc_html__('Longitude:', 'referralhub') . ' <span id="rhb_longitude_display">' . esc_html($longitude) . '</span></p>';
        
    }
    */

    // Função para renderizar o meta box de preferência de e-mail
    public static function render_email_preference_meta_box($post) {
        wp_nonce_field('rhb_save_email_preference_data', 'rhb_email_preference_nonce');

        // Obtém o valor meta existente
        $email_preference = get_post_meta($post->ID, '_rhb_email_preference', true);

        // Checkbox para a preferência de e-mail
        echo '<label class="rhb-switch">';
        echo '<input type="checkbox" name="rhb_email_preference" value="1" class="rhb-toggle-checkbox"' . checked($email_preference, '1', false) . '>';
        echo '<span class="rhb-slider"></span>';
        esc_html_e('Desejo receber notificações por e-mail para este serviço', 'referralhub');
        echo '</label>';

    }

    // Função para salvar os dados dos meta boxes
    public static function save_meta_boxes($post_id) {
        // Verificação de segurança
 

        // Salva a localização
        /*
        if (isset($_POST['rhb_service_location'])) {
            update_post_meta($post_id, '_rhb_service_location', sanitize_text_field($_POST['rhb_service_location']));

            // Geocodificar o endereço e salvar as coordenadas
            /*
            $coordinates = RHB_Geolocation::geocode_address($_POST['rhb_service_location']);
            if ($coordinates) {
                update_post_meta($post_id, '_rhb_service_latitude', $coordinates['latitude']);
                update_post_meta($post_id, '_rhb_service_longitude', $coordinates['longitude']);
            }
        }
        */

        // Salva a preferência de e-mail
        $email_preference = isset($_POST['rhb_email_preference']) ? '1' : '0';
        update_post_meta($post_id, '_rhb_email_preference', $email_preference);
    }
}

// Inicializa os meta boxes
RHB_Metaboxes::init();
