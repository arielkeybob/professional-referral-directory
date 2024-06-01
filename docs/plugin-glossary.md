# Glossário de Termos do Plugin Professional Directory

Este documento fornece uma visão geral dos termos e conceitos utilizados no plugin Professional Directory, facilitando a compreensão e o desenvolvimento.

## Termos Gerais

- **Referral Fee**: Taxa paga pelo Service Provider ao Site Owner por cada Inquiry que resulta em uma contratação de serviço ou por cada visualização de serviço, dependendo das configurações do sistema.

## Tipos de Usuários

- **Site Owner**: Administrador ou dono do site que configura e gerencia o plugin. Responsável por definir as configurações globais de comissão e gerenciar todos os aspectos do diretório.

- **Visitor**: Usuário que acessa o site e realiza inquires sem necessariamente criar uma conta. Não possui capacidades especiais até que se registre.

- **Registered User**: Visitor que criou uma conta no site para acessar funcionalidades adicionais, como gerenciar inquires passados ou interagir de maneira mais direta com os Service Providers.

- **Service Provider**: Usuário que se registra para oferecer seus serviços através do diretório. É responsável por gerenciar suas listagens de serviços e responder a inquires.


### Inquiry
Um inquiry no contexto do Diretório Profissional refere-se à ação realizada por um visitante ou usuário registrado quando eles procuram e interagem com as listagens fornecidas pelos prestadores de serviços. Um inquiry pode levar a comunicação, negociação e, potencialmente, a um acordo contratual entre o usuário e o prestador de serviços.


## Status de Inquiry

Os status de inquiry são utilizados para indicar o estágio atual de um inquiry dentro do sistema. Estes status ajudam o Service Provider a gerenciar suas interações com potenciais clientes:

1. **Pending Response**: Indica que o inquiry foi recebido, mas ainda não foi abordado pelo service provider.
2. **In Negotiation**: Usado quando o service provider iniciou a negociação com o potencial cliente.
3. **Agreement Reached**: Aplicado quando um acordo foi formalizado entre o service provider e o cliente.
4. **No Deal**: Indica que, apesar do contato e potencial negociação, as partes não chegaram a um acordo final.


## Termos de Contatos

- **Contact**: Refere-se a um usuário que realizou um inquiry e agora está associado a um Service Provider dentro do sistema.
- **Contact Status**: Representa o estado atual de um contato dentro do sistema, ajudando os Service Providers a gerenciar e priorizar suas interações com potenciais clientes.

### Status de Contact

Os status de contato são utilizados para classificar a relação atual entre o Service Provider e o contato:

1. **Initial Inquiry**: O contato foi estabelecido, mas ainda não houve interação.
2. **Engaged**: O Service Provider e o contato estão em comunicação ativa.
3. **Converted**: O contato tornou-se um cliente pagante.
4. **Not Interested**: O contato indicou desinteresse nos serviços.
5. **Archived**: O contato foi arquivado para referência futura sem uma interação imediata.

## Termos de Dados
1. **Inquiry Data**: Informações coletadas quando um inquiry é realizado, incluindo tipo de serviço, localização, e outras variáveis relevantes.
2. **Author-Contact Relation**: Registra a relação entre o prestador de serviço e o cliente potencial, incluindo interações e status personalizados.


## Termos Adicionais

### Inquiry Form
O **Inquiry Form** é o formulário preenchido pelos usuários para iniciar um inquiry. Este formulário captura informações essenciais como tipo de serviço, localização e outras preferências que ajudam a filtrar e encontrar os serviços adequados dentro do diretório.

### Inquiry Results
**Inquiry Results** refere-se aos resultados exibidos após um usuário submeter o **Inquiry Form**. Esses resultados são listagens de serviços que correspondem às especificações fornecidas pelo usuário no formulário. Os resultados permitem que o usuário explore as opções disponíveis e possivelmente inicie um inquiry com um ou mais Service Providers.


## Configurações de Referral Fee

O plugin Professional Directory oferece duas áreas distintas para a configuração de Referral Fees, permitindo uma flexibilidade entre configurações gerais e específicas para cada Service Provider.

### General Referral Fee Settings

Esta seção é acessada através das configurações gerais do plugin. Aqui, o Site Owner pode definir as taxas de referral fee que serão aplicadas de maneira padrão a todos os Service Providers. Estas configurações servem como base a menos que especificamente sobrescritas nas configurações individuais de um Service Provider.

### Service Provider-Specific Referral Fee Settings

Localizada na página de perfil de cada Service Provider, acessível pelo Site Owner, esta seção permite configurar taxas de referral fee específicas que sobrepõem as configurações gerais. Estas configurações permitem uma customização detalhada das taxas baseadas em critérios ou acordos específicos com cada Service Provider.


## Implementação de Termos

A implementação destes termos deve ser refletida em todas as interfaces do usuário, documentação interna e externa, e no código do plugin, garantindo que a terminologia seja consistente e compreendida por todos os usuários e desenvolvedores envolvidos.

---
Desenvolvido por Ariel Souza
