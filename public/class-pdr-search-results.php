<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class PDR_Search_Results {
    public function __construct() {
        add_shortcode('pdr_search_results', array($this, 'render_search_results'));
        add_action('wp_ajax_pdr_search', array($this, 'handle_ajax_search'));
        add_action('wp_ajax_nopriv_pdr_search', array($this, 'handle_ajax_search'));
    }

    public function handle_ajax_search() {
        // Registrando o início do método
        error_log('Iniciando handle_ajax_search');
        
        // Log dos dados POST recebidos
        error_log('Dados POST recebidos: ' . print_r($_POST, true));
    
        $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';
        $service_location = isset($_POST['service_location']) ? sanitize_text_field($_POST['service_location']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    
        // Preparando os dados para armazenamento
        $data_to_store = [
            'service_type' => $service_type,
            'service_location' => $service_location,
            'name' => $name,
            'email' => $email
        ];
    
        // Chamando a função store_search_data
        store_search_data($data_to_store);
    
        $args = array(
            'post_type' => 'professional_service',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'service_type',
                    'field'    => 'slug',
                    'terms'    => $service_type,
                ),
                array(
                    'taxonomy' => 'service_location',
                    'field'    => 'slug',
                    'terms'    => $service_location,
                ),
            ),
        );
    
        $query = new WP_Query($args);
    
        if ($query->have_posts()) {
            ob_start();
    
            while ($query->have_posts()) {
                $query->the_post();
                include plugin_dir_path(PDR_MAIN_FILE) . 'public/templates/content-service.php';
            }
    
            $html = ob_get_clean();
    
            wp_reset_postdata();
            wp_send_json_success($html);
            // Log da resposta AJAX
            error_log('Resposta AJAX enviada: ' . $html);
        } else {
            wp_send_json_error('No service found.');
        }
    
        wp_die();
    }
    

    public function render_search_results() {
        ob_start();
        
        // Verifica se os dados do formulário foram enviados
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Captura os termos das taxonomias do formulário
            $service_type_term = isset($_POST['service_type']) ? $_POST['service_type'] : '';
            $service_location_term = isset($_POST['service_location']) ? $_POST['service_location'] : '';

            // Prepara os argumentos para a consulta
            $args = array(
                'post_type' => 'professional_service',
                'tax_query' => array(
                    'relation' => 'AND',
                    array(
                        'taxonomy' => 'service_type',
                        'field'    => 'slug',
                        'terms'    => $service_type_term
                    ),
                    array(
                        'taxonomy' => 'service_location',
                        'field'    => 'slug',
                        'terms'    => $service_location_term
                    )
                )
            );

            // Realiza a consulta
            $query = new WP_Query($args);

            // Verifica se a consulta encontrou resultados
            if ($query->have_posts()) {
                echo '<ul>';
                while ($query->have_posts()) {
                    $query->the_post();
                    echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
                }
                echo '</ul>';
            } else {
                echo '<p>' . esc_html__('No service found.', 'professionaldirectory') . '</p>';
            }

            // Restaura a consulta original
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__('Use the search form to find services.', 'professionaldirectory'). '</p>';
        }

        ?>
        <!-- Local para exibir os resultados da busca -->
        <div id="pdr-search-results"></div>
        <?php
        return ob_get_clean();
    }
}
new PDR_Search_Results();
