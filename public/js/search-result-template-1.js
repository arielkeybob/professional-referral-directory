
    // Seleciona o elemento service-result
    var serviceResult = document.querySelector('.service-result');
  
    // Cria uma nova div com a classe card-left
    var cardLeft = document.createElement('div');
    cardLeft.className = 'card-left';
  
    // Seleciona os elementos para serem movidos para a nova div
    var serviceThumbnail = document.querySelector('.service-thumbnail');
    var authorDetails = document.querySelector('.service-author-details');
    var readMore = document.querySelector('.read-more');
  
    // Anexa os elementos selecionados à nova div card-left
    cardLeft.appendChild(serviceThumbnail);
    cardLeft.appendChild(authorDetails);
    cardLeft.appendChild(readMore);
  
    // Insere a nova div no início do elemento service-result
    serviceResult.insertBefore(cardLeft, serviceResult.firstChild);
