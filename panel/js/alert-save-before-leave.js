document.addEventListener('DOMContentLoaded', function() {
    var formModified = false;

    var formElement = document.querySelector('form');
    if (formElement) {
        // Detecta alterações em qualquer campo do formulário
        formElement.addEventListener('input', function() {
            formModified = true;
            // Atualiza o elemento de status de salvamento
            var saveStatusDiv = document.getElementById('save-status');
            if (saveStatusDiv) {
                saveStatusDiv.textContent = 'Alterações não salvas...';
            }
        });

        // Ouvinte para o evento de submit do formulário
        formElement.addEventListener('submit', function() {
            formModified = false; // Resetando o status de modificado
            // Pode remover ou alterar a mensagem de status de salvamento, se desejado
            var saveStatusDiv = document.getElementById('save-status');
            if (saveStatusDiv) {
                saveStatusDiv.textContent = ''; // Limpa o texto ou coloca uma mensagem de "Salvando..."
            }
        });
    }

    window.addEventListener('beforeunload', function(e) {
        if (formModified) {
            // A linha abaixo é necessária para acionar o diálogo de confirmação padrão do navegador.
            (e || window.event).returnValue = 'É possível que as alterações não tenham sido efetuadas.';
        }
    });
});
