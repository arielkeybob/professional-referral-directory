document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Impede o envio normal do formulário

            // Prepara os dados do formulário para serem enviados via AJAX
            var formData = new FormData(form);
            formData.append('action', 'save_contact_details'); // Ação para o WordPress identificar

            // AJAX request para o servidor WordPress
            fetch(ajaxurl, {
                method: 'POST',
                credentials: 'same-origin', // Necessário para cookies/sessão funcionar corretamente
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                // Atualiza o elemento de status com base na resposta
                var saveStatusDiv = document.getElementById('save-status');
                if (data.success) {
                    if (saveStatusDiv) {
                        saveStatusDiv.textContent = 'Salvo'; // Mensagem de sucesso
                    }
                } else {
                    if (saveStatusDiv) {
                        saveStatusDiv.textContent = 'Erro ao salvar'; // Mensagem de erro
                    }
                }
            }).catch(error => {
                console.error('Error:', error);
                var saveStatusDiv = document.getElementById('save-status');
                if (saveStatusDiv) {
                    saveStatusDiv.textContent = 'Erro ao salvar'; // Mensagem de erro em caso de falha na requisição
                }
            });

            // Reseta a flag de formulário modificado após o envio
            formModified = false;
        });

        // Flag para verificar se o formulário foi modificado
        var formModified = false;

        // Detecta alterações em qualquer campo do formulário
        form.addEventListener('change', function() {
            formModified = true;
            var saveStatusDiv = document.getElementById('save-status');
            if (saveStatusDiv) {
                saveStatusDiv.textContent = 'Alterações não salvas...'; // Mensagem de status quando o formulário é modificado
            }
        });

        // Alerta o usuário se tentar sair da página com alterações não salvas
        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                var confirmationMessage = 'Alterações que você fez podem não ser salvas.';
                (e || window.event).returnValue = confirmationMessage; // Padrão para alguns navegadores
                return confirmationMessage; // Padrão para outros navegadores
            }
        });
    }
});
