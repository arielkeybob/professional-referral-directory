<?php
// Função para estilos no frontend
function pdr_custom_frontend_styles() {
    ?>
    <style>
        :root {
            --pdr-button-color: <?php echo get_option('prd_button_color', '#000000'); ?>;
            --pdr-button-text-color: <?php echo get_option('prd_button_text_color', '#FFFFFF'); ?>;
            --pdr-button-hover-color: <?php echo get_option('prd_button_hover_color', '#000000'); ?>;
            --pdr-button-text-hover-color: <?php echo get_option('prd_button_text_hover_color', '#FFFFFF'); ?>;
            --pdr-title-font-family: <?php echo get_option('prd_title_font_family', 'Arial, sans-serif'); ?>;
            --pdr-title-color: <?php echo get_option('prd_title_color', '#000000'); ?>;
            --pdr-body-font-family: <?php echo get_option('prd_body_font_family', 'Arial, sans-serif'); ?>;
            --pdr-body-color: <?php echo get_option('prd_body_color', '#000000'); ?>;
        }
    </style>
    <?php
}

// Função para estilos no painel administrativo
function pdr_custom_admin_styles() {
    ?>
    <style>
        :root {
            --pdr-primary-color: <?php echo get_option('prd_primary_color', '#0073aa'); ?>;
            --pdr-secondary-color: <?php echo get_option('prd_secondary_color', '#0073aa'); ?>;
            --pdr-text-color: <?php echo get_option('prd_text_color', '#333333'); ?>;
            --pdr-accent-color: <?php echo get_option('prd_accent_color', '#0073aa'); ?>;
        }
    </style>
    <?php
}

// Adiciona os estilos ao frontend
add_action('wp_head', 'pdr_custom_frontend_styles');

// Adiciona os estilos ao painel administrativo
add_action('admin_head', 'pdr_custom_admin_styles');
