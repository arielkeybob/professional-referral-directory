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




// Em content-service.php
include_once(plugin_dir_path(__FILE__) . '../../includes/utility-functions.php');




// Comece a montar o HTML para cada resultado da busca
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<div class="service-result">
    
        <div class="service-thumbnail">
            <?php 
            echo '<img src="' . pdr_get_service_thumbnail_url('medium') . '" alt="Service Thumbnail">';
            ?>
        </div>
    

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
        <i class="fas fa-user"></i> <?php the_author(); ?>
    </p>
    <p class="author-email">
        <i class="fas fa-envelope"></i> <?php echo esc_html(get_the_author_meta('email')); ?>
    </p>
    <p class="author-website">
        <i class="fas fa-globe"></i> <a href="<?php echo esc_url(get_the_author_meta('url')); ?>">
            <?php echo esc_html(get_the_author_meta('url')); ?>
        </a>
    </p>
    <p class="author-info">
        <i class="fas fa-phone"></i> <?php echo esc_html(get_the_author_meta('telefone')); ?><br>
        <i class="fas fa-icons"></i> <?php echo esc_html(get_the_author_meta('social')); ?>
    </p>
</div>


    <a class="read-more" href="<?php the_permalink(); ?>">
        <?php _e('Read more', 'professionaldirectory'); ?>
    </a>
</div>
