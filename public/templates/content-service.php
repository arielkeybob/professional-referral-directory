<?php
// Verifique se o WordPress foi carregado corretamente
if (!defined('ABSPATH')) {
    exit; // Saída em caso de acesso direto ao arquivo
}

// Comece a montar o HTML para cada resultado da busca
?>
<div class="service-result">

    <!-- Exibindo a thumbnail do post -->
    <?php if (has_post_thumbnail()) : ?>
        <div class="service-thumbnail">
            <?php the_post_thumbnail('medium'); // Altere 'medium' para o tamanho desejado ?>
        </div>
    <?php endif; ?>

    <h3><?php the_title(); ?></h3>

    <!-- Exibindo o nome do autor -->
    <p class="author-name"><?php echo esc_html__('By', 'professionaldirectory'); ?> <?php the_author(); ?></p>

    <p><?php the_excerpt(); ?></p>

    <!-- Exibindo taxonomias do post -->
    <div class="service-taxonomies">
        <?php
        // Obtenha os termos para a taxonomia 'service_type'
        $service_types = get_the_terms(get_the_ID(), 'service_type');
        if (!empty($service_types)) {
            echo '<p><strong>Service Types:</strong> ';
            $type_list = [];
            foreach ($service_types as $type) {
                $type_list[] = esc_html($type->name);
            }
            echo implode(', ', $type_list);
            echo '</p>';
        }

        // Obtenha os termos para a taxonomia 'service_location'
        $service_locations = get_the_terms(get_the_ID(), 'service_location');
        if (!empty($service_locations)) {
            echo '<p><strong>Service Locations:</strong> ';
            $location_list = [];
            foreach ($service_locations as $location) {
                $location_list[] = esc_html($location->name);
            }
            echo implode(', ', $location_list);
            echo '</p>';
        }
        ?>
        <!-- Exibindo o email e o website do autor -->
        <p class="author-email"><?php echo esc_html__('Email:', 'professionaldirectory'); ?> <?php echo esc_html(get_the_author_meta('email')); ?></p>
        <p class="author-website"><?php echo esc_html__('Website:', 'professionaldirectory'); ?> <a href="<?php echo esc_url(get_the_author_meta('url')); ?>"><?php echo esc_url(get_the_author_meta('url')); ?></a></p>

        <!-- Exibindo o nome do autor e campos adicionais -->
        <p class="author-name"><?php echo esc_html__('By', 'professionaldirectory'); ?> <?php the_author(); ?></p>
        <p class="author-info">
            <?php echo esc_html__('Telefone:', 'professionaldirectory') . ' ' . esc_html(get_the_author_meta('telefone')); ?><br>
            <?php echo esc_html__('Rede Social:', 'professionaldirectory') . ' ' . esc_html(get_the_author_meta('social')); ?>
        </p>

        <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Read more', 'professionaldirectory'); ?></a>
    </div>
    <!-- Fim da exibição das taxonomias -->
</div>
