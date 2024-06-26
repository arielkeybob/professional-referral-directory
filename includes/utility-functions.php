<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
<?php

defined('ABSPATH') or die('No script kiddies please!');

// Função auxiliar para obter termos de taxonomia como string
function rhb_get_taxonomy_terms_as_string($taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if (empty($terms)) {
        return '';
    }

    $term_list = array_map(function($term) {
        return esc_html($term->name);
    }, $terms);

    return implode(', ', $term_list);
}

// includes/utility-functions.php

function rhb_get_service_thumbnail_url($size = 'full') {
    if (has_post_thumbnail()) {
        return get_the_post_thumbnail_url(null, $size);
    } else {
        return plugins_url('public/img/service-placeholder.png', RHB_MAIN_FILE);
    }
}

function rhb_get_author_email_html() {
    $email = get_the_author_meta('email');
    if ($email) {
        return '<p class="author-email"><i class="fas fa-envelope"></i> ' . esc_html($email) . '</p>';
    }
    return '';
}

function rhb_get_author_url_html() {
    $url = get_the_author_meta('url');
    if ($url) {
        return '<p class="author-website"><i class="fas fa-globe"></i> <a href="' . esc_url($url) . '">' . esc_html($url) . '</a></p>';
    }
    return '';
}

function rhb_get_author_phone_html() {
    $phone = get_the_author_meta('telefone');
    if ($phone) {
        return '<p class="author-info"><i class="fas fa-phone"></i> ' . esc_html($phone) . '</p>';
    }
    return '';
}

function rhb_get_author_social_html() {
    $social = get_the_author_meta('social');
    if ($social) {
        return '<p class="author-info"><i class="fas fa-icons"></i> ' . esc_html($social) . '</p>';
    }
    return '';
}

// Enfileirando css e js do template
$options = get_option('rhb_settings', []);
$template_choice = isset($options['rhb_template_choice']) ? $options['rhb_template_choice'] : 'template-1';
$template_number = str_replace('template-', '', $template_choice); // Isso irá extrair o número do template

// Verifica se os arquivos existem antes de tentar enfileirá-los
$css_file = plugins_url("/public/css/inquiry-result-template-{$template_number}.css", RHB_MAIN_FILE);
$js_file = plugins_url("/public/js/inquiry-result-template-{$template_number}.js", RHB_MAIN_FILE);

// Enfileira o CSS
if (file_exists(plugin_dir_path(RHB_MAIN_FILE) . "public/css/inquiry-result-template-{$template_number}.css")) {
    echo '<link rel="stylesheet" href="' . esc_url($css_file) . '" type="text/css" />';
}

// Enfileira o JS
if (file_exists(plugin_dir_path(RHB_MAIN_FILE) . "public/js/inquiry-result-template-{$template_number}.js")) {
    echo '<script src="' . esc_url($js_file) . '"></script>';
}
