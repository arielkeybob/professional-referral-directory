<?php
// Verifique se o WordPress foi carregado corretamente
if (!defined('ABSPATH')) {
    exit; // Saída em caso de acesso direto ao arquivo
}

$template_choice = get_option('myplugin_template_choice', 'template-1');
$template_number = str_replace('template-', '', $template_choice); // Isso irá extrair o número do template

// Verifica se os arquivos existem antes de tentar enfileirá-los
$css_file = plugins_url("/public/css/search-result-template-{$template_number}.css", PDR_MAIN_FILE);
$js_file = plugins_url("/public/js/search-result-template-{$template_number}.js", PDR_MAIN_FILE);

// Enfileira o CSS
if (file_exists(plugin_dir_path(PDR_MAIN_FILE) . "public/css/search-result-template-{$template_number}.css")) {
    echo '<link rel="stylesheet" href="' . esc_url($css_file) . '" type="text/css" />';
}

// Enfileira o JS
if (file_exists(plugin_dir_path(PDR_MAIN_FILE) . "public/js/search-result-template-{$template_number}.js")) {
    echo '<script src="' . esc_url($js_file) . '"></script>';
}




// Função auxiliar para obter termos de taxonomia como string
function pdr_get_taxonomy_terms_as_string($taxonomy) {
    $terms = get_the_terms(get_the_ID(), $taxonomy);
    if (empty($terms)) {
        return '';
    }

    $term_list = array_map(function($term) {
        return esc_html($term->name);
    }, $terms);

    return implode(', ', $term_list);
}

// Comece a montar o HTML para cada resultado da busca
?>

<div class="service-result">
    <?php if (has_post_thumbnail()) : ?>
        <div class="service-thumbnail">
            <?php the_post_thumbnail('medium'); ?>
        </div>
    <?php endif; ?>

    <div class="service-content">
        <h3 class="service-title"><?php the_title(); ?></h3>
        <p class="service-excerpt"><?php the_excerpt(); ?></p>
        <p class="service-taxonomy service-type">
            <strong><?php _e('Service Types:', 'professionaldirectory'); ?></strong> 
            <?php echo pdr_get_taxonomy_terms_as_string('service_type'); ?>
        </p>
        <p class="service-taxonomy service-location">
            <strong><?php _e('Service Locations:', 'professionaldirectory'); ?></strong> 
            <?php echo pdr_get_taxonomy_terms_as_string('service_location'); ?>
        </p>
    </div>

    <div class="service-author-details">
        <p class="author-name">
            <?php _e('By', 'professionaldirectory'); ?> <?php the_author(); ?>
        </p>
        <p class="author-email">
            <?php _e('Email:', 'professionaldirectory'); ?> <?php echo esc_html(get_the_author_meta('email')); ?>
        </p>
        <p class="author-website">
            <?php _e('Website:', 'professionaldirectory'); ?> <a href="<?php echo esc_url(get_the_author_meta('url')); ?>">
                <?php echo esc_html(get_the_author_meta('url')); ?>
            </a>
        </p>
        <p class="author-info">
            <?php _e('Telefone:', 'professionaldirectory'); ?> <?php echo esc_html(get_the_author_meta('telefone')); ?><br>
            <?php _e('Rede Social:', 'professionaldirectory'); ?> <?php echo esc_html(get_the_author_meta('social')); ?>
        </p>
    </div>

    <a class="read-more" href="<?php the_permalink(); ?>">
        <?php _e('Read more', 'professionaldirectory'); ?>
    </a>
</div>
