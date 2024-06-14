document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.rhb-settings-tab');
    const sections = document.querySelectorAll('.rhb-settings-section-content');
    const saveButtons = document.querySelectorAll('.rhb-save-button');
    const form = document.querySelector('form');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            const target = this.getAttribute('href').substring(1);

            sections.forEach(section => {
                section.style.display = (section.id === target) ? 'block' : 'none';
            });

            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            this.classList.add('active');
            localStorage.setItem('rhbActiveTab', target);
        });
    });

    const activeTab = localStorage.getItem('rhbActiveTab');
    if (activeTab) {
        document.querySelector(`.rhb-settings-tab[href="#${activeTab}"]`).click();
    } else if (tabs.length > 0) {
        tabs[0].click();
    }

    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            form.submit();
        });
    });

    function toggleReferralFeeFields() {
        const type = document.getElementById('rhb_referral_fee_type').value;
        const viewField = document.querySelector('input#rhb_general_referral_fee_view').closest('tr');
        const agreementField = document.querySelector('input#rhb_general_referral_fee_agreement_reached').closest('tr');
        viewField.style.display = (type === 'view' || type === 'both') ? 'table-row' : 'none';
        agreementField.style.display = (type === 'agreement_reached' || type === 'both') ? 'table-row' : 'none';
    }

    const referralFeeTypeField = document.getElementById('rhb_referral_fee_type');
    if (referralFeeTypeField) {
        referralFeeTypeField.addEventListener('change', toggleReferralFeeFields);
        toggleReferralFeeFields();
    }

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
                document.getElementById(id).value = attachment.url;
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

    document.querySelectorAll('.rhb-number-field').forEach(input => {
        input.addEventListener('input', function() {
            let rawValue = input.value.replace(/[^0-9]/g, '');
            if (rawValue === '') {
                input.value = '';
                return;
            }
            let integerPart = rawValue.slice(0, -2) || '0';
            let decimalPart = rawValue.slice(-2);
            input.value = new Intl.NumberFormat('pt-BR', {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(parseFloat(integerPart + '.' + decimalPart));
        });
    });

    form.addEventListener('submit', function(e) {
        document.querySelectorAll('.rhb-number-field').forEach(input => {
            let normalizedValue = input.value.replace(/\./g, '').replace(',', '.');
            input.value = parseFloat(normalizedValue).toFixed(2);
        });
    });
});
