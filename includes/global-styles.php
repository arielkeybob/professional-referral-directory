<?php
defined('ABSPATH') or die('No script kiddies please!');

// Função para enfileirar as fontes do Google Fonts
function rhb_enqueue_google_fonts() {
    $options = get_option('rhb_settings', []);
    $fonts_to_enqueue = [];

    if (isset($options['rhb_title_font_family']) && !empty($options['rhb_title_font_family'])) {
        $fonts_to_enqueue[] = $options['rhb_title_font_family'] . ':400,700'; // Assume que você queira 400 e 700 pesos
    }
    if (isset($options['rhb_body_font_family']) && !empty($options['rhb_body_font_family']) && $options['rhb_body_font_family'] !== $options['rhb_title_font_family']) {
        $fonts_to_enqueue[] = $options['rhb_body_font_family'] . ':400,700';
    }

    if (!empty($fonts_to_enqueue)) {
        $query_args = [
            'family' => implode('|', $fonts_to_enqueue),
            'display' => 'swap'
        ];
        $fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
        wp_enqueue_style('rhb-google-fonts', $fonts_url, [], null);
    }
}
add_action('wp_enqueue_scripts', 'rhb_enqueue_google_fonts');

// Função para estilos no frontend
function rhb_custom_frontend_styles() {
    $options = get_option('rhb_settings', []);
    ?>
    <style>
        :root {
            --rhb-button-color: <?php echo isset($options['rhb_button_color']) ? $options['rhb_button_color'] : '#000000'; ?>;
            --rhb-button-text-color: <?php echo isset($options['rhb_button_text_color']) ? $options['rhb_button_text_color'] : '#FFFFFF'; ?>;
            --rhb-button-hover-color: <?php echo isset($options['rhb_button_hover_color']) ? $options['rhb_button_hover_color'] : '#000000'; ?>;
            --rhb-button-text-hover-color: <?php echo isset($options['rhb_button_text_hover_color']) ? $options['rhb_button_text_hover_color'] : '#FFFFFF'; ?>;
            --rhb-title-font-family: '<?php echo isset($options['rhb_title_font_family']) ? $options['rhb_title_font_family'] : 'Arial, sans-serif'; ?>', sans-serif;
            --rhb-title-color: <?php echo isset($options['rhb_title_color']) ? $options['rhb_title_color'] : '#000000'; ?>;
            --rhb-body-font-family: '<?php echo isset($options['rhb_body_font_family']) ? $options['rhb_body_font_family'] : 'Arial, sans-serif'; ?>', sans-serif;
            --rhb-body-color: <?php echo isset($options['rhb_body_color']) ? $options['rhb_body_color'] : '#000000'; ?>;
        }
    </style>
    <?php
}

// Função para estilos no painel administrativo
function rhb_custom_admin_styles() {
    $options = get_option('rhb_settings', []);
    ?>
    <style>
        :root {
            --rhb-primary-color: <?php echo isset($options['rhb_primary_color']) ? $options['rhb_primary_color'] : '#0073aa'; ?>;
            --rhb-secondary-color: <?php echo isset($options['rhb_secondary_color']) ? $options['rhb_secondary_color'] : '#0073aa'; ?>;
            --rhb-text-color: <?php echo isset($options['rhb_text_color']) ? $options['rhb_text_color'] : '#333333'; ?>;
            --rhb-accent-color: <?php echo isset($options['rhb_accent_color']) ? $options['rhb_accent_color'] : '#0073aa'; ?>;
        }
    </style>
    <?php
}

// Adiciona os estilos ao frontend
add_action('wp_head', 'rhb_custom_frontend_styles');

// Adiciona os estilos ao painel administrativo
add_action('admin_head', 'rhb_custom_admin_styles');
?>
