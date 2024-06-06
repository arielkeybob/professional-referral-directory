document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.rhb-settings-tab');
    const sections = document.querySelectorAll('.rhb-settings-section-content');

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
        });
    });

    if (tabs.length > 0) {
        tabs[0].click();
    }
});
