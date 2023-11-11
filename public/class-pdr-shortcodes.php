<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class PDR_Shortcodes {
    public function __construct() {
        // Registrar shortcodes
        add_shortcode('pdr_search_form', array($this, 'render_search_form'));
        add_shortcode('pdr_search_results', array($this, 'render_search_results'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_pdr_search', array($this, 'handle_search'));
        add_action('wp_ajax_nopriv_pdr_search', array($this, 'handle_search'));
    }

    public function render_search_form() {
        ob_start();
        ?>
        <!-- Formulário de Filtro (Etapa 1) -->
        <form id="pdr-search-form" method="post">
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
            <input type="text" name="address" placeholder="Endereço">
            <button type="button" id="pdr-search-btn">Buscar</button>
        </form>

        <!-- Formulário de Informações Pessoais (Etapa 2) -->
        <div id="pdr-personal-info-form" style="display:none;">
            <input type="text" name="name" placeholder="Nome">
            <input type="email" name="email" placeholder="Email">
            <button type="button" id="pdr-submit-personal-info">Enviar</button>
        </div>
        <?php
        return ob_get_clean();
    }

    public function render_search_results() {
        ob_start();
        ?>
        <!-- Local para exibir os resultados da busca -->
        <div id="pdr-search-results"></div>
        <?php
        return ob_get_clean();
    }

    public function enqueue_scripts() {
        wp_enqueue_script('pdr-ajax-script', plugin_dir_url(__FILE__) . 'js/ajax-script.js', array('jquery'));
        wp_localize_script('pdr-ajax-script', 'pdr_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }

    public function handle_search() {
        $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';
        $address = isset($_POST['address']) ? sanitize_text_field($_POST['address']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
        // Se a busca for por tipo de serviço e endereço
        if (!empty($service_type) && !empty($address)) {
            $args = [
                'post_type' => 'professional_service',
                'tax_query' => [
                    [
                        'taxonomy' => 'service_type',
                        'field'    => 'slug',
                        'terms'    => $service_type,
                    ],
                ],
                // Adicione aqui a lógica para o endereço
            ];
            $query = new WP_Query($args);
    
            // Lógica para encontrar o serviço mais próximo com base no endereço
            // ...
    
            // Supondo que você tenha encontrado o serviço mais próximo
            if ($query->have_posts()) {
                $query->the_post();
                $service_data = [
                    'id' => get_the_ID(),
                    'name' => get_the_title(),
                    // Outros detalhes do serviço conforme necessário
                ];
                wp_send_json_success(['message' => 'Serviço encontrado', 'data' => $service_data]);
            } else {
                wp_send_json_error(['message' => 'Nenhum serviço encontrado']);
            }
        } elseif (!empty($name) && !empty($email)) {
            // Lógica para enviar e-mail ao autor do serviço e ao administrador
            // ...
    
            wp_send_json_success(['message' => 'E-mail enviado com sucesso']);
        } else {
            wp_send_json_error(['message' => 'Dados insuficientes fornecidos']);
        }
    }
}

new PDR_Shortcodes();
