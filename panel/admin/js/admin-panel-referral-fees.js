function updateFilters() {
    var selector = document.getElementById("period-selector");
    var datePicker = document.getElementById("custom-date-picker");
    if (selector.value === "custom") {
        datePicker.style.display = "block";
    } else {
        datePicker.style.display = "none";
        fetchData(selector.value);
    }
}

function fetchData(filterType, startDate = '', endDate = '') {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', ajaxurl, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            // Sucesso na requisição
            document.getElementById('table-container').innerHTML = this.response;
            // Adiciona o evento de clique às linhas da tabela
            addRowClickEvent();
        } else {
            // Tratamos erros do servidor aqui
            console.error('Erro do servidor');
        }
    };
    xhr.onerror = function () {
        // Conexão falhou
        console.error('Erro de conexão');
    };
    xhr.send('action=fetch_referral_fees&filter=' + filterType + '&start_date=' + encodeURIComponent(startDate) + '&end_date=' + encodeURIComponent(endDate));
}

function fetchDataBasedOnDates() {
    var startDate = document.getElementById("start-date").value;
    var endDate = document.getElementById("end-date").value;
    fetchData('custom', startDate, endDate);
}

// Função para adicionar eventos de clique às linhas da tabela que contêm o atributo 'data-href'
function addRowClickEvent() {
    var rows = document.querySelectorAll('tr[data-href]');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-href');
        });
    });
}

// Chamada inicial para adicionar eventos de clique após carregamento da página
document.addEventListener('DOMContentLoaded', function() {
    addRowClickEvent();
});

// Função para adicionar eventos de clique às linhas da tabela que contêm o atributo 'data-href'
function addRowClickEvent() {
    var rows = document.querySelectorAll('tr[data-href]');
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-href');
        });
    });
}

// Chamada inicial para adicionar eventos de clique após carregamento da página
document.addEventListener('DOMContentLoaded', function() {
    addRowClickEvent();
});
