<?php
// Verifique se o WordPress foi carregado corretamente
if (!defined('ABSPATH')) {
    exit; // Saída em caso de acesso direto ao arquivo
}

// Comece a montar o HTML para cada resultado da busca
?>
<div class="service-result">
    <h3><?php the_title(); ?></h3>
    <p><?php the_excerpt(); ?></p>
    <a href="<?php the_permalink(); ?>"><?php echo esc_html__('Read more', 'professionaldirectory'); ?></a>
    <!-- Adicione mais detalhes do serviço conforme necessário -->
</div>
