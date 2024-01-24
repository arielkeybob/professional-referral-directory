<?php
// Se este arquivo for chamado diretamente, aborte.
if (!defined('WPINC')) {
    die;
}

class ProfessionalDirectory_Users {
    public static function activate() {
        if (!get_role('professional')) {
            add_role(
                'professional',
                'Professional',
                array(
                    'read' => true, // Permite que o usuário leia
                    // Adicione capacidades específicas para o papel 'professional' aqui.
                    // Exemplo: 'edit_services' se você criou uma capacidade personalizada
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

    public static function deactivate() {
        if ($role = get_role('professional')) {
            // Remove as capacidades antes de remover o papel para limpeza.
            $role->remove_cap('edit_services');
            $role->remove_cap('edit_published_services');
            // Remova outras capacidades adicionadas.
        }
        remove_role('professional');
    }
}

