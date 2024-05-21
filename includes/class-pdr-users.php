<?php
    defined('ABSPATH') or die('No script kiddies please!');

class PDR_Users {

    public static function initialize_user_roles() {
        // Criação e configuração do papel 'professional'
        if (!get_role('professional')) {
            add_role(
                'professional',
                'Professional',
                array(
                    'read' => true, // Permite que o usuário leia
                    // Adicione outras capacidades específicas para o papel 'professional' aqui.
                )
            );
        }

        // Adiciona as capacidades ao papel 'professional' após a criação do papel.
        $role = get_role('professional');
        if ($role) {
            // Adicione as capacidades relacionadas ao tipo de post 'service'.
            $role->add_cap('edit_services');
            $role->add_cap('edit_published_services');
            // Adicione outras capacidades conforme necessário.
        }
    }

    public static function cleanup_user_roles() {
        // Remoção do papel 'professional' e suas capacidades
        if ($role = get_role('professional')) {
            $role->remove_cap('edit_services');
            $role->remove_cap('edit_published_services');
            // Remova outras capacidades adicionadas.
        }
        remove_role('professional');
    }

    public static function add_custom_user_profile_fields($user) {
        // Código para adicionar campos personalizados ao perfil do usuário
        ?>
        <h3><?php _e("Informações Adicionais", "your_textdomain"); ?></h3>

        <table class="form-table">
            <tr>
                <th>
                    <label for="telefone"><?php _e("Telefone"); ?></label>
                </th>
                <td>
                    <input type="text" name="telefone" id="telefone" value="<?php echo esc_attr(get_the_author_meta('telefone', $user->ID)); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Por favor insira seu telefone."); ?></span>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="social"><?php _e("Rede Social"); ?></label>
                </th>
                <td>
                    <input type="text" name="social" id="social" value="<?php echo esc_attr(get_the_author_meta('social', $user->ID)); ?>" class="regular-text" /><br />
                    <span class="description"><?php _e("Por favor insira sua rede social."); ?></span>
                </td>
            </tr>
        </table>
        <?php
    }

    public static function save_custom_user_profile_fields($user_id) {
        // Código para salvar os campos personalizados
        if (!current_user_can('edit_user', $user_id)) {
            return false;
        }

        update_user_meta($user_id, 'telefone', $_POST['telefone']);
        update_user_meta($user_id, 'social', $_POST['social']);
    }

    public static function hideAdminColorSchemeForProfessionals() {
        $user = wp_get_current_user();
        if (in_array('professional', (array)$user->roles)) {
            echo '<style>tr.user-admin-color-wrap { display: none; }</style>';
        }
    }


    public static function register_hooks() {
        // Registra os hooks para adicionar e salvar campos personalizados
        add_action('show_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));
        add_action('edit_user_profile', array(__CLASS__, 'add_custom_user_profile_fields'));

        add_action('personal_options_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        add_action('edit_user_profile_update', array(__CLASS__, 'save_custom_user_profile_fields'));
        // Oculta a opção de esquema de cores para usuários "professional"
        add_action('admin_head', array(__CLASS__, 'hideAdminColorSchemeForProfessionals'));

    }

    
   
    
}

// Chama o método register_hooks na inicialização do plugin
PDR_Users::register_hooks();
