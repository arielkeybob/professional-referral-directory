// Admin JavaScript for ReferralHub Plugin
document.addEventListener('DOMContentLoaded', function() {
    var editButton = document.getElementById('edit-name');
    var customNameInput = document.getElementById('custom_name');

    if (editButton && customNameInput) {
        editButton.addEventListener('click', function() {
            customNameInput.removeAttribute('readonly'); // Remove o atributo readonly
            customNameInput.focus(); // Foca no campo para edição
        });
    }

    // Restante do seu código JS...
});
