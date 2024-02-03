<?php
function pdr_custom_styles() {
    ?>
    <style>
        :root {
            --pdr-button-color: <?php echo get_option('myplugin_button_color', '#000000'); ?>;
            --pdr-button-text-color: <?php echo get_option('myplugin_button_text_color', '#FFFFFF'); ?>;
            --pdr-button-hover-color: <?php echo get_option('myplugin_button_hover_color', '#000000'); ?>;
            --pdr-button-text-hover-color: <?php echo get_option('myplugin_button_text_hover_color', '#FFFFFF'); ?>;
            --pdr-title-font-family: <?php echo get_option('myplugin_title_font_family', 'Arial, sans-serif'); ?>;
            --pdr-title-color: <?php echo get_option('myplugin_title_color', '#000000'); ?>;
            --pdr-body-font-family: <?php echo get_option('myplugin_body_font_family', 'Arial, sans-serif'); ?>;
            --pdr-body-color: <?php echo get_option('myplugin_body_color', '#000000'); ?>;
        }
        /* Aqui você pode adicionar seletores específicos do plugin e aplicar as variáveis */
    </style>
    <?php
}
add_action('wp_head', 'pdr_custom_styles');
