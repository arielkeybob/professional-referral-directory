<?php
defined('ABSPATH') or die('No script kiddies please!');

// Verificar se a página já existe
$inquiry_page_id = get_option('rhb_inquiry_page_id');
$page_exists = $inquiry_page_id && get_post_status($inquiry_page_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rhb_create_pages_submit']) && check_admin_referer('rhb_create_pages', 'rhb_create_pages_nonce')) {
    if (isset($_POST['create_inquiry_page']) && !$page_exists) {
        // Cria a página de Inquiry de serviços
        $page_id = wp_insert_post([
            'post_title' => __('Inquiry de Serviços', 'referralhub'),
            'post_content' => '[rhb_inquiry_form][rhb_inquiry_results]',
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
        if ($page_id) {
            update_option('rhb_inquiry_page_id', $page_id);
            $page_exists = true;
            $message = __('Página de Inquiry de serviços criada com sucesso.', 'referralhub');
        }
    }
}
?>

<div class="wrap welcome-page">
    <h1><?php _e('Bem-vindo ao ReferralHub', 'referralhub'); ?></h1>
    <p><?php _e('Obrigado por instalar o ReferralHub! Este assistente de configuração ajudará você a começar rapidamente.', 'referralhub'); ?></p>
    
    <div class="welcome-panel">
        <div class="welcome-panel-content">
            <h2><?php _e('Vamos configurar seu diretório', 'referralhub'); ?></h2>
            <p><?php _e('Siga os passos abaixo para configurar as páginas principais e ajustar as configurações do plugin.', 'referralhub'); ?></p>

            <form method="post" action="">
                <?php wp_nonce_field('rhb_create_pages', 'rhb_create_pages_nonce'); ?>
                <h3><?php _e('Criar Páginas Automáticas', 'referralhub'); ?></h3>
                <?php if (isset($message)): ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php echo $message; ?></p>
                    </div>
                <?php elseif (isset($_GET['created']) && $_GET['created'] === 'true'): ?>
                    <div class="notice notice-success is-dismissible">
                        <p><?php _e('Página de Inquiry de serviços criada com sucesso.', 'referralhub'); ?></p>
                    </div>
                <?php endif; ?>
                <p>
                    <input type="checkbox" id="create_inquiry_page" name="create_inquiry_page" value="1" <?php disabled($page_exists); ?>>
                    <label for="create_inquiry_page"><?php _e('Criar página de Inquiry de serviços automaticamente', 'referralhub'); ?></label>
                </p>
                <p>
                    <input type="submit" name="rhb_create_pages_submit" class="button button-primary" value="<?php _e('Criar Páginas', 'referralhub'); ?>" <?php disabled($page_exists); ?>>
                </p>
            </form>
            
            <h3><?php _e('Suporte', 'referralhub'); ?></h3>
            <p><?php _e('Consulte a documentação ou entre em contato com o suporte para obter ajuda.', 'referralhub'); ?></p>
        </div>
    </div>
</div>

<style>
    .wrap.welcome-page {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
        max-width: 800px;
        margin: 20px auto;
    }
    .welcome-panel {
        background: #f1f1f1;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    .welcome-panel-content {
        max-width: 700px;
        margin: auto;
    }
    .welcome-panel h2 {
        margin-top: 0;
    }
    .welcome-panel p {
        font-size: 14px;
        line-height: 1.5;
    }
    .welcome-panel form {
        margin-top: 20px;
    }
    .welcome-panel .button {
        margin-top: 10px;
    }
</style>
