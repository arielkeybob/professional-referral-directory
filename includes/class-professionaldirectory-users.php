<?php
// Se este arquivo for chamado diretamente, aborte.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class ProfessionalDirectory_Users {
	public static function activate() {
		if ( ! get_role( 'professional' ) ) {
			add_role(
				'professional',
				'Professional',
				array(
					'read'         => true,  // Permite que o usuário leia
					// Outras capacidades relevantes para o papel 'professional'.
				)
			);
		}
	}

	public static function deactivate() {
		remove_role( 'professional' );
	}
}

