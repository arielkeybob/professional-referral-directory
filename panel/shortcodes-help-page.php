<?php
// Verifica se o WordPress carregou corretamente
if (!defined('ABSPATH')) {
    exit; // Sai se acessado diretamente
}
?>

<div class="wrap">
    <h1>Ajuda de Shortcodes do Plugin ProfessionalDirectory</h1>
    <p>Aqui você encontrará instruções detalhadas sobre como usar os shortcodes disponíveis no plugin ProfessionalDirectory para melhorar a funcionalidade do seu site WordPress.</p>

    <h2>Shortcodes Disponíveis</h2>

    <div class="shortcode-section">
        <h3>Formulário de Busca</h3>
        <p>Este shortcode exibe um formulário de busca que permite aos usuários procurar por serviços profissionais com base no tipo de serviço e localização.</p>
        <div class="shortcode-display">
            <code>[pdr_search_form]</code>
            <button onclick="copyToClipboard(this, '[pdr_search_form]')">Copiar</button>
        </div>
    </div>

    <div class="shortcode-section">
        <h3>Resultados da Pesquisa</h3>
        <p>Este shortcode exibe os resultados da busca. Utilize-o na página que irá mostrar os serviços buscados pelos usuários.</p>
        <div class="shortcode-display">
            <code>[pdr_search_results]</code>
            <button onclick="copyToClipboard(this, '[pdr_search_results]')">Copiar</button>
        </div>
    </div>

    <script>
        // Função para copiar o shortcode e mudar o texto do botão
        function copyToClipboard(btn, shortcode) {
            navigator.clipboard.writeText(shortcode);
            btn.textContent = 'Copiado!';
            setTimeout(function() {
                btn.textContent = 'Copiar';
            }, 3000);
        }
    </script>

    <style>
        .shortcode-section {
            margin-top:60px;
            margin-bottom: 20px;
        }
        .shortcode-display {
            display: flex;
            align-items: center;
        }
        .shortcode-display code {
            margin-right: 10px;
            background: #eee;
            padding: 3px 5px;
            border-radius: 3px;
            font-family: monospace;
        }
        button {
            cursor: pointer;
            padding: 5px 10px;
            background-color: #0073aa;
            color: white;
            border: none;
            border-radius: 3px;
            box-shadow: none;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #006799;
        }
    </style>
</div>
