<?php
// Verifica se o WordPress carregou corretamente
if (!defined('ABSPATH')) {
    exit; // Sai se acessado diretamente
}
?>

<div class="wrap">
    <h1><?php _e('Plugin ProfessionalDirectory Shortcodes Help', 'professional-directory'); ?></h1>
    <p><?php _e('Here you will find detailed instructions on how to use the available shortcodes in the ProfessionalDirectory plugin to enhance the functionality of your WordPress site.', 'professional-directory'); ?></p>

    <h2><?php _e('Available Shortcodes', 'professional-directory'); ?></h2>

    <div class="shortcode-section">
        <h3><?php _e('Search Form', 'professional-directory'); ?></h3>
        <p><?php _e('This shortcode displays a search form that allows users to search for professional services based on service type and location.', 'professional-directory'); ?></p>
        <div class="shortcode-display">
            <code>[pdr_search_form]</code>
            <button onclick="copyToClipboard(this, '[pdr_search_form]')"><?php _e('Copy', 'professional-directory'); ?></button>
        </div>
    </div>

    <div class="shortcode-section">
        <h3><?php _e('Search Results', 'professional-directory'); ?></h3>
        <p><?php _e('This shortcode displays the search results. Use it on the page that will show the services searched for by users.', 'professional-directory'); ?></p>
        <div class="shortcode-display">
            <code>[pdr_search_results]</code>
            <button onclick="copyToClipboard(this, '[pdr_search_results]')"><?php _e('Copy', 'professional-directory'); ?></button>
        </div>
    </div>

    <script>
        // Function to copy the shortcode and change the button text
        function copyToClipboard(btn, shortcode) {
            navigator.clipboard.writeText(shortcode);
            btn.textContent = '<?php echo esc_js(__('Copied!', 'professional-directory')); ?>';
            setTimeout(function() {
                btn.textContent = '<?php echo esc_js(__('Copy', 'professional-directory')); ?>';
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
