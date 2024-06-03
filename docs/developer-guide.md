# Guia do Desenvolvedor para ReferralHub

Bem-vindo ao guia do desenvolvedor do ReferralHub. Este documento fornece uma visão geral da arquitetura do plugin, padrões de código e práticas recomendadas para contribuir ou estender o plugin, além de uma descrição detalhada do fluxo de dados e das funções principais.

## Estrutura do Plugin

O ReferralHub segue uma estrutura padrão de plugins do WordPress, que inclui diretórios específicos para administração, lógica de negócios e apresentação, assim como arquivos para ativação e manuseio de eventos.

### Diretórios Principais

- **Admin:** Contém arquivos relacionados à interface administrativa do plugin.
- **Includes:** Arquivos que implementam a lógica central do plugin e funções de utilidade.
- **Public:** Arquivos destinados ao front-end do site.

### Arquivos Principais

- `ReferralHub.php`: Arquivo principal do plugin para inicialização e configuração básica.
- `activation.php`: Gerencia a ativação do plugin e a criação de tabelas.
- `ajax-handlers.php`: Contém os manipuladores para as requisições AJAX.

## Banco de Dados

O plugin cria e utiliza várias tabelas no banco de dados para armazenar dados relacionados aos serviços, contatos, inquiries e Referral Fees.

### Tabelas

- **wp_rhb_contacts**: Armazena dados de contatos.
  - `contact_id`: ID único para o contato.
  - `email`: Email do contato.
  - `default_name`: Nome padrão do contato.

- **wp_rhb_inquiry_data**: Armazena dados dos inquiries realizados.
  - `id`: ID único do inquiry.
  - `service_type`, `service_location`: Tipo e localização do serviço inquirido.
  - `inquiry_date`: Data do inquiry.
  - `service_id`: ID do serviço relacionado.
  - `author_id`: ID do autor do serviço.
  - `contact_id`: ID do contato que realizou o inquiry.
  - `inquiry_status`: Status do inquiry (pending, in negotiation, agreement reached, no deal).
  - `referral_fee_value_view`: Valor da Referral Fee por visualização.
  - `referral_fee_value_agreement_reached`: Valor da Referral Fee por acordo alcançado.
  - `is_paid`: Indica se a Referral Fee foi paga.

- **wp_rhb_author_contact_relations**: Relaciona contatos a autores.
  - `author_contact_id`: ID único da relação.
  - `contact_id`, `author_id`: IDs do contato e do autor.
  - `status`: Status do contato (initial inquiry, engaged, converted, not interested, archived).
  - `custom_name`: Nome personalizado para o contato.

### Fluxo de Gravação de Dados Durante os Inquiries

1. **Recebimento do Inquiry:** Dados são recebidos via AJAX e processados.
2. **Criação/Atualização de Contato:** Verifica se o contato existe e atualiza/cria conforme necessário.
3. **Registro do Inquiry:** Dados do inquiry são armazenados, incluindo o cálculo de Referral Fees baseado no status do inquiry e configurações do autor.

### Cálculo de Referral Fees

O cálculo de Referral Fees é realizado com base no status do inquiry e nas configurações definidas globalmente ou por usuário. As Referral Fees podem ser por visualização ou por inquiry com acordo alcançado, e são ajustadas conforme o resultado do inquiry e as preferências do usuário.

## Padrões de Código

O plugin segue as [Normas de Codificação do WordPress](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) para garantir a qualidade e a consistência do código.

- **Sanitização e Validação:** Todas as entradas são devidamente sanitizadas e validadas.
- **Internacionalização:** Preparado para tradução, seguindo as práticas de internacionalização do WordPress.

## Contribuições

Para contribuir com o ReferralHub, siga estes passos:

1. **Fork o Repositório:** Faça um fork do repositório no GitHub.
2. **Faça suas Mudanças:** Trabalhe em uma branch separada para suas contribuições.
3. **Pull Request:** Envie um pull request com uma descrição clara de suas mudanças.

## Suporte

Para suporte, dúvidas ou sugestões de melhorias, utilize a seção de issues no GitHub do projeto.

---

Desenvolvido por Ariel Souza
