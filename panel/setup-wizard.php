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

if (isset($_GET['skip'])) {
    update_option('rhb_setup_wizard_completed', true);
    wp_redirect(admin_url());
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php _e('Welcome to ReferralHub', 'referralhub'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f1f1f1;
            margin: 0;
            padding: 0;
        }
        .setup-wrapper {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,.13);
            border-radius: 10px;
        }
        .setup-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .setup-header h1 {
            margin: 0;
        }
        .setup-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .setup-steps div {
            width: 23%;
            padding: 10px;
            background: #f7f7f7;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            text-align: center;
        }
        .setup-steps div.active {
            background: #007cba;
            color: #fff;
            border-color: #007cba;
        }
        .setup-content {
            padding: 20px;
            border-top: 1px solid #e1e1e1;
        }
        .setup-content form {
            display: flex;
            flex-direction: column;
        }
        .setup-content form p {
            margin-bottom: 10px;
        }
        .setup-content form input[type="submit"] {
            align-self: flex-start;
        }
        .setup-footer {
            text-align: center;
            margin-top: 20px;
        }
        .setup-footer a {
            color: #007cba;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="setup-wrapper">
        <div class="setup-header">
            <h1><?php _e('Welcome to ReferralHub', 'referralhub'); ?></h1>
            <p><?php _e('Thank you for installing ReferralHub! Follow the steps below to get started.', 'referralhub'); ?></p>
        </div>
        <div class="setup-steps">
            <div class="step active"><?php _e('Step 1', 'referralhub'); ?></div>
            <div class="step"><?php _e('Step 2', 'referralhub'); ?></div>
            <div class="step"><?php _e('Step 3', 'referralhub'); ?></div>
            <div class="step"><?php _e('Step 4', 'referralhub'); ?></div>
        </div>
        <div class="setup-content">
            <h2><?php _e('Create Automatic Pages', 'referralhub'); ?></h2>
            <?php if (isset($message)): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php echo $message; ?></p>
                </div>
            <?php elseif (isset($_GET['created']) && $_GET['created'] === 'true'): ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php _e('Inquiry page created successfully.', 'referralhub'); ?></p>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <?php wp_nonce_field('rhb_create_pages', 'rhb_create_pages_nonce'); ?>
                <p>
                    <input type="checkbox" id="create_inquiry_page" name="create_inquiry_page" value="1" <?php disabled($page_exists); ?>>
                    <label for="create_inquiry_page"><?php _e('Automatically create the inquiry page', 'referralhub'); ?></label>
                </p>
                <p>
                    <input type="submit" name="rhb_create_pages_submit" class="button button-primary" value="<?php _e('Create Pages', 'referralhub'); ?>" <?php disabled($page_exists); ?>>
                </p>
            </form>
        </div>
        <div class="setup-footer">
            <a href="<?php echo admin_url('edit.php?post_type=rhb_service'); ?>"><?php _e('Skip setup', 'referralhub'); ?></a>
        </div>
    </div>
</body>
</html>
