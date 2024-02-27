// Este código vai no arquivo my-custom-script.js no diretório js do seu plugin
document.addEventListener('DOMContentLoaded', function() {
    // Função para mostrar um toast
    function showToast(message) {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "bottom",
            position: "right",
            backgroundColor: "#4fbe87",
        }).showToast();
    }

    // Supondo que você tenha algum gatilho para "Salvo", por exemplo, uma ação AJAX
    document.getElementById('save-button').addEventListener('click', function() {
        // Faça a lógica de salvar aqui, depois mostre o toast
        showToast('Salvo com sucesso!');
    });
});
