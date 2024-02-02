
    // Seleciona todos os elementos de serviço
    var serviceResults = document.querySelectorAll('.service-result');

    // Itera sobre cada resultado do serviço
    serviceResults.forEach(function(serviceResult) {
        // Cria uma nova div com a classe card-left
        var cardLeft = document.createElement('div');
        cardLeft.className = 'card-left';

        // Seleciona os elementos dentro do resultado do serviço atual para serem movidos
        var serviceThumbnail = serviceResult.querySelector('.service-thumbnail');
        var authorDetails = serviceResult.querySelector('.service-author-details');
        var readMore = serviceResult.querySelector('.read-more');

        // Verifica se os elementos existem antes de tentar movê-los
        if(serviceThumbnail && authorDetails && readMore) {
            // Anexa os elementos selecionados à nova div card-left
            cardLeft.appendChild(serviceThumbnail.cloneNode(true));
            cardLeft.appendChild(authorDetails.cloneNode(true));
            cardLeft.appendChild(readMore.cloneNode(true));

            // Limpa os elementos originais do DOM
            serviceThumbnail.remove();
            authorDetails.remove();
            readMore.remove();

            // Insere a nova div card-left no início do elemento service-result
            serviceResult.insertBefore(cardLeft, serviceResult.firstChild);
        }
    });

