<?php
defined('ABSPATH') or die('No script kiddies please!');

class Contatos_Public {
    public function __construct() {
        // Adicione aqui ações e filtros relevantes, como enfileirar scripts ou estilos.
        add_shortcode('contact_inquiry_form', array($this, 'render_inquiry_form'));
        add_action('wp_ajax_process_inquiry_form', array($this, 'process_inquiry_form'));
        add_action('wp_ajax_nopriv_process_inquiry_form', array($this, 'process_inquiry_form'));
    }

    /**
     * Renderiza o formulário de Inquiry no frontend através de um shortcode.
     */
    public function render_inquiry_form() {
        // Aqui você pode incluir o HTML do formulário, ou incluir um arquivo de template.
        ob_start(); // Inicia o buffer de saída
        ?>
        <form id="formulario-inquiry-contato" action="" method="post">
            <!-- Campos do formulário -->
            <input type="text" name="nome" placeholder="Seu Nome" required>
            <input type="email" name="email" placeholder="Seu Email" required>
            <!-- Adicione mais campos conforme necessário -->
            <input type="submit" value="Inquiry">
        </form>
        <?php
        return ob_get_clean(); // Retorna o conteúdo do buffer e finaliza o buffer.
    }

    /**
     * Processa a submissão do formulário de Inquiry.
     */
    public function process_inquiry_form() {
        check_ajax_referer('seguranca_inquiry_form', 'nonce');

        // Recupere os dados do formulário de Inquiry. Valide e sanitize conforme necessário.
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        // Processa os dados conforme necessário, por exemplo, salvar no banco de dados.

        wp_send_json_success(array('mensagem' => 'Inquiry recebida com sucesso!'));
        wp_die(); // Finaliza a execução no contexto AJAX.
    }

    /**
     * Enfileira scripts específicos para o frontend.
     */
    public function enfileirar_scripts() {
        wp_enqueue_script(
            'pdr-contatos-public-js',
            plugin_dir_url(__FILE__) . 'js/seu-script-public.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize o script para adicionar dados do PHP ao JS, como URLs AJAX e nonces.
        wp_localize_script(
            'pdr-contatos-public-js',
            'seuPlugin',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('seguranca_inquiry_form')
            )
        );
    }
}

// Instancia a classe para garantir que a lógica do frontend seja carregada.
new Contatos_Public();
