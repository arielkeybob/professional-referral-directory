<?php
defined('ABSPATH') or die('No script kiddies please!');

// Verificar se a página já existe
$search_page_id = get_option('pdr_search_page_id');
$page_exists = $search_page_id && get_post_status($search_page_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pdr_create_pages_submit']) && check_admin_referer('pdr_create_pages', 'pdr_create_pages_nonce')) {
    if (isset($_POST['create_search_page']) && !$page_exists) {
        // Cria a página de pesquisa de serviços
        $page_id = wp_insert_post([
            'post_title' => __('Pesquisa de Serviços', 'professional-directory'),
            'post_content' => '[pdr_search_form][pdr_search_results]',
            'post_status' => 'publish',
            'post_type' => 'page'
        ]);
        if ($page_id) {
            update_option('pdr_search_page_id', $page_id);
            $page_exists = true;
            $message = __('Página de pesquisa de serviços criada com sucesso.', 'professional-directory');
        }
    }
}
?>

<div class="wrap">
    <h1><?php _e('Bem-vindo ao Professional Directory', 'professional-directory'); ?></h1>
    <p><?php _e('Obrigado por instalar o Professional Directory! Aqui estão algumas informações para você começar.', 'professional-directory'); ?></p>

    <h2><?php _e('Ações Iniciais', 'professional-directory'); ?></h2>
    <p><?php _e('Algumas configurações para ajustar o plugin às suas necessidades.', 'professional-directory'); ?></p>

    <h2><?php _e('Criar Páginas Automáticas', 'professional-directory'); ?></h2>
    <?php if (isset($message)): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php echo $message; ?></p>
        </div>
    <?php elseif (isset($_GET['created']) && $_GET['created'] === 'true'): ?>
        <div class="notice notice-success is-dismissible">
            <p><?php _e('Página de pesquisa de serviços criada com sucesso.', 'professional-directory'); ?></p>
        </div>
    <?php endif; ?>
    <form method="post" action="">
        <?php wp_nonce_field('pdr_create_pages', 'pdr_create_pages_nonce'); ?>
        <p>
            <input type="checkbox" id="create_search_page" name="create_search_page" value="1" <?php disabled($page_exists); ?>>
            <label for="create_search_page"><?php _e('Criar página de pesquisa de serviços automaticamente', 'professional-directory'); ?></label>
        </p>
        <p>
            <input type="submit" name="pdr_create_pages_submit" class="button button-primary" value="<?php _e('Criar Páginas', 'professional-directory'); ?>" <?php disabled($page_exists); ?>>
        </p>
    </form>

    <h2><?php _e('Suporte', 'professional-directory'); ?></h2>
    <p><?php _e('Consulte a documentação ou entre em contato com o suporte para obter ajuda.', 'professional-directory'); ?></p>
</div>

<style>
    .wrap {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 1px rgba(0,0,0,.04);
    }
    .wrap h1 {
        margin-bottom: 20px;
    }
    .wrap h2 {
        margin-top: 40px;
        margin-bottom: 10px;
    }
</style>
