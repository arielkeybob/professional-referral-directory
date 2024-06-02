<?php
defined('ABSPATH') or die('No script kiddies please!');

class PDR_Inquiry_Form {
    public function __construct() {
        add_shortcode('pdr_inquiry_form', array($this, 'render_inquiry_form'));
    }

    public function render_inquiry_form() {
        global $wpdb; // A classe global do WordPress para operações no banco de dados
    
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $name = $current_user->display_name;
            $email = $current_user->user_email;
            $logged_in = true;
        } else {
            $name = '';
            $email = '';
            $logged_in = false;
        }
    
        ob_start();
        ?>
        <!-- Formulário de Filtro (Etapa 1) -->
        <form id="pdr-inquiry-form" method="post">
            <div id="pdr-initial-inquiry">
                <select name="service_type">
                    <option value=""><?php echo esc_html__('Select a Service Type', 'professionaldirectory'); ?></option>
                    <?php
                    // Recupera os termos da taxonomia 'service_type'
                    $terms = get_terms(array(
                        'taxonomy' => 'service_type',
                        'hide_empty' => false,
                    ));
    
                    // Lista cada termo como uma opção
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                    ?>
                </select>
    
                <select name="service_location">
                    <option value=""><?php echo esc_html__('Select a Location', 'professionaldirectory'); ?></option>
                    <?php
                    // Recupera os termos da taxonomia 'service_location'
                    $terms = get_terms(array(
                        'taxonomy' => 'service_location',
                        'hide_empty' => false,
                    ));
    
                    // Lista cada termo como uma opção
                    foreach ($terms as $term) {
                        echo '<option value="' . esc_attr($term->slug) . '">' . esc_html($term->name) . '</option>';
                    }
                    ?>
                </select>
    
                <button type="button" id="pdr-inquiry-btn"><?php echo esc_html__('Next', 'professionaldirectory'); ?></button>
            </div>
    
            <!-- Formulário de Informações Pessoais (Etapa 2) -->
            <div id="pdr-personal-info-form" style="display:none;">
                <?php if ($logged_in): ?>
                    <p><?php _e('You are inquiring as: ', 'professionaldirectory'); echo esc_html($name); ?></p>
                    <input type="hidden" name="name" value="<?php echo esc_attr($name); ?>">
                    <input type="hidden" name="email" value="<?php echo esc_attr($email); ?>">
                    <a href="<?php echo wp_logout_url(get_permalink()); ?>"><?php _e('Change user', 'professionaldirectory'); ?></a>
                <?php else: ?>
                    <input type="text" name="name" placeholder="<?php echo esc_attr__('Name', 'professionaldirectory'); ?>" required>
                    <input type="email" name="email" placeholder="<?php echo esc_attr__('Email', 'professionaldirectory'); ?>" required>
                    <p><a href="<?php echo wp_login_url(get_permalink()); ?>"><?php _e('Already have an account? Log in', 'professionaldirectory'); ?></a></p>
                    <label>
                        <input type="checkbox" name="create_account" id="create-account">
                        <?php _e('Create an account', 'professionaldirectory'); ?>
                    </label>
                    <div id="account-info" style="display: none;">
                        <input type="password" name="password" placeholder="<?php echo esc_attr__('Password', 'professionaldirectory'); ?>">
                        <input type="password" name="confirm_password" placeholder="<?php echo esc_attr__('Confirm Password', 'professionaldirectory'); ?>">
                    </div>
                <?php endif; ?>
    
                <button type="submit"><?php echo esc_html__('Submit', 'professionaldirectory'); ?></button>
            </div>
        </form>
        <script>
            document.getElementById('create-account').addEventListener('change', function() {
                var accountInfo = document.getElementById('account-info');
                if (this.checked) {
                    accountInfo.style.display = 'block';
                } else {
                    accountInfo.style.display = 'none';
                }
            });
    
            document.getElementById('pdr-inquiry-btn').addEventListener('click', function() {
                document.getElementById('pdr-initial-inquiry').style.display = 'none';
                document.getElementById('pdr-personal-info-form').style.display = 'block';
            });
        </script>
        <?php
        return ob_get_clean();
    }
    
}

new PDR_Inquiry_Form();
