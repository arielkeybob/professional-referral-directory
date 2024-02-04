document.addEventListener('DOMContentLoaded', function() {
    var colorInput = document.querySelector('input[name="myplugin_button_color_hex"]');
    var colorPicker = document.querySelector('input[name="myplugin_button_color"]');

    if (colorInput && colorPicker) {
        // Atualiza o campo de texto quando o seletor de cores muda
        colorPicker.addEventListener('input', function() {
            colorInput.value = colorPicker.value;
        });

        // Atualiza o seletor de cores quando o campo de texto muda
        colorInput.addEventListener('input', function() {
            // Verifica se o valor é um hexadecimal válido
            var hexPattern = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
            if(hexPattern.test(colorInput.value)) {
                colorPicker.value = colorInput.value;
            }
        });
    }
});
