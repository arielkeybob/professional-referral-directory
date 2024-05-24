<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/data-storage-functions.php';

class PDR_Search_Results {
    public function __construct() {
        add_shortcode('pdr_search_results', array($this, 'render_search_results'));
        add_action('wp_ajax_pdr_search', array($this, 'handle_ajax_search'));
        add_action('wp_ajax_nopriv_pdr_search', array($this, 'handle_ajax_search'));
    }

    public function handle_ajax_search() {
        $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';
        $service_location = isset($_POST['service_location']) ? sanitize_text_field($_POST['service_location']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';

        // Certifique-se de que email e nome estão presentes
        if (empty($email) || empty($name)) {
            wp_send_json_error('Email and name are required.');
            wp_die();
        }

        // Obter ou criar contact_id baseado no e-mail fornecido
        $contactId = adicionar_ou_atualizar_contato(['email' => $email, 'name' => $name]);

        $args = [
            'post_type' => 'professional_service',
            'tax_query' => [
                'relation' => 'AND',
                [
                    'taxonomy' => 'service_type',
                    'field'    => 'slug',
                    'terms'    => $service_type,
                ],
                [
                    'taxonomy' => 'service_location',
                    'field'    => 'slug',
                    'terms'    => $service_location,
                ],
            ],
        ];

        $query = new WP_Query($args);

        if ($query->have_posts()) {
            ob_start();

            // Obter a escolha do template
            $template_choice = get_option('pdr_template_choice', 'template-1');
            $template_file = 'search-result-' . $template_choice . '.php';

            while ($query->have_posts()) {
                $query->the_post();
                $service_id = get_the_ID();
                $author_id = get_post_field('post_author', $service_id);

                $data_to_store = [
                    'service_type' => $service_type,
                    'service_location' => $service_location,
                    'name' => $name,
                    'email' => $email,
                    'contact_id' => $contactId,
                    'service_id' => $service_id,
                    'author_id' => $author_id,
                    'search_date' => current_time('mysql'),
                    'search_status' => 'pending', // Inicialmente, todos os estados de pesquisa são definidos como 'pending'
                ];

                // Armazenar os dados da pesquisa
                if (!store_search_data($data_to_store)) {
                    error_log('Failed to store search data.');
                }

                // Criar ou atualizar a relação contato-autor
                createOrUpdateContactAuthorRelation($contactId, $author_id, 'active', null);

                // Incluir o template correto
                include plugin_dir_path(PDR_MAIN_FILE) . 'public/templates/' . $template_file;
            }

            $html = ob_get_clean();
            wp_reset_postdata();
            wp_send_json_success($html);
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
