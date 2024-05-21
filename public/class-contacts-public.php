<?php
defined('ABSPATH') or die('No script kiddies please!');

class Contatos_Public {
    public function __construct() {
        // Adicione aqui ações e filtros relevantes, como enfileirar scripts ou estilos.
        add_shortcode('formulario_pesquisa_contato', array($this, 'renderizar_formulario_pesquisa'));
        add_action('wp_ajax_processar_formulario_pesquisa', array($this, 'processar_formulario_pesquisa'));
        add_action('wp_ajax_nopriv_processar_formulario_pesquisa', array($this, 'processar_formulario_pesquisa'));
    }

    /**
     * Renderiza o formulário de pesquisa no frontend através de um shortcode.
     */
    public function renderizar_formulario_pesquisa() {
        // Aqui você pode incluir o HTML do formulário, ou incluir um arquivo de template.
        ob_start(); // Inicia o buffer de saída
        ?>
        <form id="formulario-pesquisa-contato" action="" method="post">
            <!-- Campos do formulário -->
            <input type="text" name="nome" placeholder="Seu Nome" required>
            <input type="email" name="email" placeholder="Seu Email" required>
            <!-- Adicione mais campos conforme necessário -->
            <input type="submit" value="Enviar Pesquisa">
        </form>
        <?php
        return ob_get_clean(); // Retorna o conteúdo do buffer e finaliza o buffer.
    }

    /**
     * Processa a submissão do formulário de pesquisa.
     */
    public function processar_formulario_pesquisa() {
        check_ajax_referer('seguranca_formulario_pesquisa', 'nonce');

        // Recupere os dados do formulário de pesquisa. Valide e sanitize conforme necessário.
        $nome = sanitize_text_field($_POST['nome']);
        $email = sanitize_email($_POST['email']);
        // Processa os dados conforme necessário, por exemplo, salvar no banco de dados.

        wp_send_json_success(array('mensagem' => 'Pesquisa recebida com sucesso!'));
        wp_die(); // Finaliza a execução no contexto AJAX.
    }

    /**
     * Enfileira scripts específicos para o frontend.
     */
    public function enfileirar_scripts() {
        wp_enqueue_script(
            'professionaldirectory-contatos-public-js',
            plugin_dir_url(__FILE__) . 'js/seu-script-public.js',
            array('jquery'),
            '1.0.0',
            true
        );

        // Localize o script para adicionar dados do PHP ao JS, como URLs AJAX e nonces.
        wp_localize_script(
            'professionaldirectory-contatos-public-js',
            'seuPlugin',
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('seguranca_formulario_pesquisa')
            )
        );
    }
}

// Instancia a classe para garantir que a lógica do frontend seja carregada.
new Contatos_Public();
