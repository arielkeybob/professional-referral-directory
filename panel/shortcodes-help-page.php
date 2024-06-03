<?php
defined('ABSPATH') or die('No script kiddies please!');
?>

<div class="wrap">
    <h1><?php _e('Plugin ReferralHub Shortcodes Help', 'referralhub'); ?></h1>
    <p><?php _e('Here you will find detailed instructions on how to use the available shortcodes in the ReferralHub plugin to enhance the functionality of your WordPress site.', 'referralhub'); ?></p>

    <h2><?php _e('Available Shortcodes', 'referralhub'); ?></h2>

    <div class="shortcode-section">
        <h3><?php _e('Inquiry Form', 'referralhub'); ?></h3>
        <p><?php _e('This shortcode displays a inquiry form that allows users to inquiry for Service Providers based on service type and location.', 'referralhub'); ?></p>
        <div class="shortcode-display">
            <code>[rhb_inquiry_form]</code>
            <button onclick="copyToClipboard(this, '[rhb_inquiry_form]')"><?php _e('Copy', 'referralhub'); ?></button>
        </div>
    </div>

    <div class="shortcode-section">
        <h3><?php _e('Inquiry Results', 'referralhub'); ?></h3>
        <p><?php _e('This shortcode displays the inquiry results. Use it on the page that will show the services inquired for by users.', 'referralhub'); ?></p>
        <div class="shortcode-display">
            <code>[rhb_inquiry_results]</code>
            <button onclick="copyToClipboard(this, '[rhb_inquiry_results]')"><?php _e('Copy', 'referralhub'); ?></button>
        </div>
    </div>

    <script>
        // Function to copy the shortcode and change the button text
        function copyToClipboard(btn, shortcode) {
            navigator.clipboard.writeText(shortcode);
            btn.textContent = '<?php echo esc_js(__('Copied!', 'referralhub')); ?>';
            setTimeout(function() {
                btn.textContent = '<?php echo esc_js(__('Copy', 'referralhub')); ?>';
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
