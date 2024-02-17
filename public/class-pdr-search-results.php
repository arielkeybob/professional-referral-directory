<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

// Inclui o arquivo data-storage-functions.php para acessar suas funções
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/data-storage-functions.php';


class PDR_Search_Results {
    public function __construct() {
        add_shortcode('pdr_search_results', array($this, 'render_search_results'));
        add_action('wp_ajax_pdr_search', array($this, 'handle_ajax_search'));
        add_action('wp_ajax_nopriv_pdr_search', array($this, 'handle_ajax_search'));
    }

    public function handle_ajax_search() {
        // Registrando o início do método
        //error_log('Iniciando handle_ajax_search');
        
        // Log dos dados POST recebidos
        //error_log('Dados POST recebidos: ' . print_r($_POST, true));
    
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
    

        //error_log('Dados a serem armazenados antes de chamar store_search_data: ' . print_r($data_to_store, true));

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
                $service_id = get_the_ID();
                $author_id = get_the_author_meta('ID');

                // Prepara os dados adicionais
                $additional_data = [
                    'service_id' => $service_id,
                    'author_id' => $author_id
                ];

                // Combina os dados do formulário com os dados adicionais
                $combined_data_to_store = array_merge($data_to_store, $additional_data);

                criar_ou_atualizar_contato($combined_data_to_store);

                // Chama a função store_search_data
                store_search_data($combined_data_to_store);
                 // Supondo que você tenha o array $combined_data_to_store
                //error_log('Dados combinados para armazenamento: ' . print_r($combined_data_to_store, true));

                send_email_to_service_author($service_id);
                send_admin_notification_emails($service_id);

                include plugin_dir_path(PDR_MAIN_FILE) . 'public/templates/content-service.php';
            }
    
            $html = ob_get_clean();
    
            wp_reset_postdata();
            wp_send_json_success($html);
            // Log da resposta AJAX
            //error_log('Resposta AJAX enviada: ' . $html);
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
