<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class PDR_Search_Results {
    public function __construct() {
        add_shortcode('pdr_search_results', array($this, 'render_search_results'));
    }

    public function render_search_results() {
        ob_start();
        ?>
        <!-- Local para exibir os resultados da busca -->
        <div id="pdr-search-results"></div>
        <?php
        return ob_get_clean();
    }
}

new PDR_Search_Results();
