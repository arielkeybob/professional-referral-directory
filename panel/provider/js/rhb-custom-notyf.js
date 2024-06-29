document.addEventListener('DOMContentLoaded', function() {
    // Inicializa a Notyf
    var notyf = new Notyf();

    // Exibe uma notificação de sucesso
    notyf.success('Your changes have been successfully saved!');

    // Exibe uma notificação de erro
    notyf.error('Something went wrong. Please try again.');
});
