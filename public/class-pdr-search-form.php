<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class PDR_Search_Form {
    public function __construct() {
        add_shortcode('pdr_search_form', array($this, 'render_search_form'));
    }

    public function render_search_form() {
        global $wpdb; // A classe global do WordPress para operações no banco de dados

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Captura os dados do formulário usando a função get_form_data
            $form_data = get_form_data();

            // Chama a função store_search_data para armazenar os dados no banco de dados
            store_search_data($form_data);
        }

        ob_start();
        ?>
        <!-- Formulário de Filtro (Etapa 1) -->
        <form id="pdr-search-form" method="post">
            <div id="pdr-initial-search">
                <select name="service_type">
                    <option value="">Selecione um Tipo de Serviço</option>
                    <?php
                    // Recupera os termos da taxonomia 'service_type'
                    $terms = get_terms(array(
                        'taxonomy' => 'service_type',
                        'hide_empty' => false,
                    ));

                    // Lista cada termo como uma opção
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                    ?>
                </select>
                
                <select name="service_location">
                <option value="">Selecione uma Localização</option>
                <?php
                // Recupera os termos da taxonomia 'service_location'
                $terms = get_terms(array(
                    'taxonomy' => 'service_location',
                    'hide_empty' => false,
                ));

                // Lista cada termo como uma opção
                foreach ($terms as $term) {
                    echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                }
                ?>
                </select>


                <button type="button" id="pdr-search-btn">Next</button>
            </div>

            <!-- Formulário de Informações Pessoais (Etapa 2) -->
            <div id="pdr-personal-info-form" style="display:none;">
                <input type="text" name="name" placeholder="Nome">
                <input type="email" name="email" placeholder="Email">
                <button type="submit">Submit</button>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
}

new PDR_Search_Form();
