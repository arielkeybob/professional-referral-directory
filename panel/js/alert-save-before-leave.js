document.addEventListener('DOMContentLoaded', function() {
    // Cria uma instância do Notyf com as opções de configuração desejadas
    var notyf = new Notyf({
        duration: 3000, // Duração da notificação
        position: {
            x: 'center', // Posiciona no centro horizontalmente
            y: 'bottom', // Posiciona na parte inferior da tela
        },
        types: [
            {
                type: 'success',
                background: 'green',
                icon: {
                    className: 'material-icons', // Classe do ícone
                    tagName: 'i',
                    text: 'check_circle' // Ícone de sucesso
                },
                dismissible: true
            },
            {
                type: 'error',
                background: 'red',
                icon: {
                    className: 'material-icons', // Classe do ícone
                    tagName: 'i',
                    text: 'error' // Ícone de erro
                },
                dismissible: true
            }
        ],
        ripple: true, // Efeito de ripple ao clicar na notificação
    });

    var form = document.querySelector('#contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Previne o envio padrão do formulário

            var formData = new FormData(form);
            formData.append('action', 'save_contact_details');

            // Envia os dados do formulário via AJAX
            fetch(ajaxurl, {
                method: 'POST',
                credentials: 'same-origin',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    notyf.open({
                        type: 'success',
                        message: 'Saved successfully!'
                    });
                } else {
                    notyf.open({
                        type: 'error',
                        message: 'Error saving: ' + (data.message || 'Please try again later.')
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notyf.open({
                    type: 'error',
                    message: 'Communication error with the server.'
                });
            });

            // Reseta a flag de formulário modificado após a submissão
            formModified = false;
        });

        // Outras lógicas de detecção de mudança de formulário e alerta de saída podem permanecer iguais
    }
});
