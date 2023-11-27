<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

require_once 'class-pdr-geolocation.php';


class PDR_Metaboxes {

    // Função para inicializar os meta boxes
    public static function init() {
        add_action('add_meta_boxes', [self::class, 'add_meta_boxes']);
        add_action('save_post', [self::class, 'save_meta_boxes']);
    }

    // Função para adicionar meta boxes
    public static function add_meta_boxes() {
        add_meta_box(
            'pdr_service_location',
            'Localização do Serviço',
            [self::class, 'render_location_meta_box'],
            'professional_service', // Tipo de post
            'side',
            'default'
        );
    }

    // Função para renderizar o meta box de localização
    public static function render_location_meta_box($post) {
        wp_nonce_field('pdr_save_location_meta_box_data', 'pdr_location_meta_box_nonce');

        // Obtém os valores meta existentes
        $location = get_post_meta($post->ID, '_pdr_service_location', true);
        $latitude = get_post_meta($post->ID, '_pdr_service_latitude', true);
        $longitude = get_post_meta($post->ID, '_pdr_service_longitude', true);

        echo '<label for="pdr_service_location">Endereço:</label>';
        echo '<input type="text" id="pdr_service_location" name="pdr_service_location" value="' . esc_attr($location) . '" />';
        echo '<p>Latitude: <span id="pdr_latitude_display">' . esc_html($latitude) . '</span></p>';
        echo '<p>Longitude: <span id="pdr_longitude_display">' . esc_html($longitude) . '</span></p>';
    }

    // Função para salvar os dados dos meta boxes
    public static function save_meta_boxes($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE || wp_is_post_revision($post_id)) {
            return;
        }

        if (isset($_POST['pdr_location_meta_box_nonce']) && wp_verify_nonce($_POST['pdr_location_meta_box_nonce'], 'pdr_save_location_meta_box_data')) {
            if (isset($_POST['pdr_service_location'])) {
                $location = sanitize_text_field($_POST['pdr_service_location']);
                update_post_meta($post_id, '_pdr_service_location', $location);

                // Geocodificar o endereço e salvar as coordenadas
                $coordinates = PDR_Geolocation::geocode_address($location);
                if ($coordinates) {
                    update_post_meta($post_id, '_pdr_service_latitude', $coordinates['latitude']);
                    update_post_meta($post_id, '_pdr_service_longitude', $coordinates['longitude']);
                }
            }
        }
    }
}

// Inicializa os meta boxes
PDR_Metaboxes::init();
