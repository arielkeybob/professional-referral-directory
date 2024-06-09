document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.rhb-settings-tab');
    const sections = document.querySelectorAll('.rhb-settings-section-content');
    const saveButton = document.querySelector('.rhb-save-button');
    const form = document.querySelector('form');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href').substring(1);

            sections.forEach(section => {
                if (section.id === target) {
                    section.style.display = 'block';
                } else {
                    section.style.display = 'none';
                }
            });

            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            this.classList.add('active');

            // Armazena a aba ativa no localStorage
            localStorage.setItem('rhbActiveTab', target);
        });
    });

    // Abre a aba armazenada no localStorage
    const activeTab = localStorage.getItem('rhbActiveTab');
    if (activeTab) {
        document.querySelector(`.rhb-settings-tab[href="#${activeTab}"]`).click();
    } else if (tabs.length > 0) {
        tabs[0].click();
    }

    saveButton.addEventListener('click', function() {
        form.submit();
    });

    // Script para mostrar/ocultar campos de referral fee
    function toggleReferralFeeFields() {
        const type = document.getElementById('rhb_referral_fee_type').value;
        document.querySelector('input#rhb_general_referral_fee_view').closest('tr').style.display = (type === 'view' || type === 'both') ? 'table-row' : 'none';
        document.querySelector('input#rhb_general_referral_fee_agreement_reached').closest('tr').style.display = (type === 'agreement_reached' || type === 'both') ? 'table-row' : 'none';
    }

    const referralFeeTypeField = document.getElementById('rhb_referral_fee_type');
    if (referralFeeTypeField) {
        referralFeeTypeField.addEventListener('change', toggleReferralFeeFields);
        toggleReferralFeeFields();  // Garante que os campos corretos sejam mostrados inicialmente
    }

    // Script para upload de mídia
    const mediaButtons = document.querySelectorAll('button[id$="_button"]');
    mediaButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.id.replace('_button', '');
            const customUploader = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Use this image'
                },
                multiple: false
            }).on('select', function() {
                const attachment = customUploader.state().get('selection').first().toJSON();
                document.getElementById(id).value = attachment.id;
                document.getElementById(id + '_preview').src = attachment.url;
            }).open();
        });
    });

    const removeButtons = document.querySelectorAll('button[id$="_remove"]');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.id.replace('_remove', '');
            document.getElementById(id).value = '';
            document.getElementById(id + '_preview').src = '';
        });
    });
});
