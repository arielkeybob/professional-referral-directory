document.addEventListener('DOMContentLoaded', function() {
    // Selecione todos os seletores de cor
    var colorPickers = document.querySelectorAll('input[type="color"]');

    colorPickers.forEach(function(picker) {
        // Deriva o nome do campo de texto hexadecimal do nome do seletor de cor
        var hexFieldName = picker.name.replace('rhb_settings[', '').replace(']', '') + '_hex';
        var hexField = document.querySelector('input[name="rhb_settings[' + hexFieldName + ']"]');

        // Quando o valor do seletor de cor muda, atualize o campo de texto hexadecimal
        picker.addEventListener('input', function() {
            if (hexField) {
                hexField.value = picker.value;
            }
        });

        // Quando o valor do campo de texto hexadecimal muda, atualize o seletor de cor
        // se o valor for um hexadecimal válido
        if (hexField) {
            hexField.addEventListener('input', function() {
                var hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
                if (hexPattern.test(hexField.value)) {
                    picker.value = hexField.value;
                }
            });
        }
    });
});
