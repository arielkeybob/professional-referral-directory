<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/data-storage-functions.php';
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/email-functions.php'; // Garantir que as funções de email estão incluídas

class RHB_Inquiry_Results {
    public function __construct() {
        add_shortcode('rhb_inquiry_results', array($this, 'render_inquiry_results'));
        add_action('wp_ajax_rhb_inquiry', array($this, 'handle_ajax_inquiry'));
        add_action('wp_ajax_nopriv_rhb_inquiry', array($this, 'handle_ajax_inquiry'));
    }

    public function handle_ajax_inquiry() {
        global $wpdb;  // Assegurando que $wpdb esteja definido
        error_log('Iniciando handle_ajax_inquiry');

        $service_type = isset($_POST['service_type']) ? sanitize_text_field($_POST['service_type']) : '';
        $service_location = isset($_POST['service_location']) ? sanitize_text_field($_POST['service_location']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $create_account = isset($_POST['create_account']) ? sanitize_text_field($_POST['create_account']) : '';
        $password = isset($_POST['password']) ? sanitize_text_field($_POST['password']) : '';

        error_log("service_type: $service_type, service_location: $service_location, name: $name, email: $email, create_account: $create_account, password: $password");

        if (empty($email) || empty($name)) {
            wp_send_json_error('Email and name are required.');
            return;
        }

        $contactId = adicionar_ou_atualizar_contato(['email' => $email, 'name' => $name]);
        error_log('contactId: ' . $contactId);

        $args = [
            'post_type' => 'rhb_service',
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

            $options = get_option('rhb_settings', []);
            $template_choice = isset($options['rhb_template_choice']) ? $options['rhb_template_choice'] : 'template-1';
            $template_file = 'inquiry-result-' . $template_choice . '.php';

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
                    'inquiry_date' => current_time('mysql'),
                    'inquiry_status' => 'pending',
                ];

                if (store_inquiry_data($data_to_store)) {
                    error_log("Dados de Inquiry inseridos com sucesso, ID: " . $wpdb->insert_id);
                    // Agendar o envio de e-mails de forma assíncrona
                    schedule_email_to_service_author($service_id, $data_to_store);
                    schedule_admin_notification_emails($service_id, $data_to_store);
                } else {
                    error_log('Falha ao armazenar dados do inquiry.');
                }

                createOrUpdateContactAuthorRelation($contactId, $author_id, 'active', null);

                include plugin_dir_path(RHB_MAIN_FILE) . 'public/templates/' . $template_file;
            }

            $html = ob_get_clean();
            wp_reset_postdata();
            wp_send_json_success($html);
        } else {
            wp_send_json_error('No service found.');
        }

        wp_die(); // Encerra a execução para evitar a saída padrão do WP.
    }

    public function render_inquiry_results() {
        ob_start();
        ?>
        <div id="rhb-inquiry-results"></div>
        <?php
        return ob_get_clean();
    }
}

new RHB_Inquiry_Results();
?>
