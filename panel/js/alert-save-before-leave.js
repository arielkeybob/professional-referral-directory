document.addEventListener('DOMContentLoaded', function() {
    var form = document.querySelector('#contact-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevents the normal form submission

            var formData = new FormData(form);
            formData.append('action', 'save_contact_details'); // Action for WordPress to identify

            // AJAX request to the WordPress server
            fetch(ajaxurl, {
                method: 'POST',
                credentials: 'same-origin', // Necessary for cookies/session to work correctly
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Success notification
                    Toastify({
                        text: "Saved successfully!",
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "center",
                        backgroundColor: "#4fbe87",
                        stopOnFocus: true,
                        
                    }).showToast();
                } else {
                    // Error notification with server message, if available
                    Toastify({
                        text: "Error saving: " + (data.message || 'Please try again later.'),
                        duration: 3000,
                        close: true,
                        gravity: "bottom",
                        position: "center",
                        backgroundColor: "#e74c3c",
                        stopOnFocus: true,
                    }).showToast();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Communication error notification
                Toastify({
                    text: "Communication error with the server.",
                    duration: 3000,
                    close: true,
                    gravity: "bottom",
                    position: "center",
                    backgroundColor: "#e74c3c",
                    stopOnFocus: true,
                }).showToast();
            });

            // Resets the form modified flag after submission
            formModified = false;
        });

        var formModified = false;

        // Detects changes in any form field
        form.addEventListener('change', function() {
            formModified = true;
        });

        // Alerts the user if they try to leave the page with unsaved changes
        window.addEventListener('beforeunload', function(e) {
            if (formModified) {
                var confirmationMessage = 'Changes you made may not be saved.';
                (e || window.event).returnValue = confirmationMessage;
                return confirmationMessage;
            }
        });
    }
});
