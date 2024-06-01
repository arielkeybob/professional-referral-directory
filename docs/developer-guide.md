# Guia do Desenvolvedor para ProfessionalDirectory

Bem-vindo ao guia do desenvolvedor do ProfessionalDirectory. Este documento detalha a arquitetura do plugin, padrões de código, e práticas recomendadas para contribuir ou estender o plugin, além de uma descrição detalhada do fluxo de dados e das funções principais.

## Estrutura do Plugin

O ProfessionalDirectory segue uma estrutura padrão de plugins do WordPress, que inclui diretórios específicos para administração, lógica de negócios e apresentação, assim como arquivos para ativação e manuseio de eventos.

### Diretórios Principais

- **Admin:** Contém arquivos relacionados à interface administrativa do plugin.
- **Includes:** Arquivos que implementam a lógica central do plugin e funções de utilidade.
- **Public:** Arquivos destinados ao front-end do site.

### Arquivos Principais

- `ProfessionalDirectory.php`: Arquivo principal do plugin para inicialização e configuração básica.
- `activation.php`: Gerencia a ativação do plugin e a criação de tabelas.
- `ajax-handlers.php`: Contém os manipuladores para as requisições AJAX.

## Banco de Dados

O plugin cria e utiliza várias tabelas no banco de dados para armazenar dados relacionados aos serviços, contatos, pesquisas e comissões.

### Tabelas

- **wp_pdr_contacts**: Armazena dados de contatos.
  - `contact_id`: ID único para o contato.
  - `email`: Email do contato.
  - `default_name`: Nome padrão do contato.

- **wp_pdr_search_data**: Armazena dados das pesquisas realizadas.
  - `id`: ID único da pesquisa.
  - `service_type`, `service_location`: Tipo e localização do serviço pesquisado.
  - `search_date`: Data da pesquisa.
  - `service_id`: ID do serviço relacionado.
  - `author_id`: ID do autor do serviço.
  - `contact_id`: ID do contato que realizou a pesquisa.
  - `search_status`: Status da pesquisa (pendente, aprovada, rejeitada).
  - `commission_value_view`: Valor da Referral Fees (comissão) por visualização.
  - `commission_value_approval`: Valor da Referral Fees (comissão) por pesquisa aprovada, agora referida como "Referral Fee".
  - `is_paid`: Indica se a Referral Fees (comissão) foi paga.

- **wp_pdr_author_contact_relations**: Relaciona contatos a autores.
  - `author_contact_id`: ID único da relação.
  - `contact_id`, `author_id`: IDs do contato e do autor.
  - `status`: Status do contato (ativo, lead, etc.).
  - `custom_name`: Nome personalizado para o contato.

### Fluxo de Gravação de Dados Durante as Pesquisas

1. **Recebimento da Pesquisa:** Dados são recebidos via AJAX e processados.
2. **Criação/Atualização de Contato:** Verifica se o contato existe e atualiza/cria conforme necessário.
3. **Registro da Pesquisa:** Dados da pesquisa são armazenados, incluindo o cálculo de comissões baseado no status da pesquisa e configurações do autor.

### Cálculo de Referral Fees (Comissões)

O cálculo de comissões, agora referidas como "Referral Fees", é realizado com base no status da pesquisa e nas configurações definidas globalmente ou por usuário. As comissões podem ser por visualização ou por pesquisa aprovada, e são ajustadas conforme o resultado da pesquisa e as preferências do usuário.

## Tipos de Usuários

### Site Owner
O Site Owner é o administrador principal do plugin ProfessionalDirectory. Este usuário possui controle total sobre as configurações do plugin, gerenciamento de conteúdo e administração de todos os aspectos do diretório.
Capacidades:
- Gerencia o plugin e suas configurações.
- Acesso completo ao painel administrativo.
- Capacidade de configurar e ajustar as definições globais do plugin, incluindo taxas de Referral Fees (comissão)  que os Services Providers pagarão ao Website, estilos do diretório e permissões.
- Gerenciamento de todos os Service Providers e Registered Users, incluindo aprovação ou rejeição de cadastros.
- Visualização e gestão de todas as pesquisas e transações financeiras relacionadas às Referral Fees.
- Recebimento de relatórios detalhados sobre as atividades do diretório e performance financeira.
- Pode configurar comissões globais e acessar todas as informações de pesquisas e contatos.

### Visitor

O Visitor representa um usuário não registrado que navega pelo diretório. Este usuário pode visualizar os serviços listados sem a necessidade de criar uma conta.

Capacidades:
- Acesso para visualizar todos os serviços listados no diretório público.
- Pode se tornar um Registered User ao criar uma conta.
- Capacidade de realizar pesquisas no diretório usando filtros disponíveis como tipo de serviço, localização, entre outros.
- Não possui capacidade para interagir diretamente com os Service Providers ou ver informações detalhadas de contato sem registro.

### Registered User
O Registered User é um visitante que optou por registrar-se no site para acessar funcionalidades adicionais. Este tipo de usuário geralmente se registra para obter vantagens como salvar serviços favoritos, receber atualizações ou interagir diretamente com os Service Providers.

Capacidades:
- Todas as capacidades de um Visitor.
- Possibilidade  gerenciar seu próprio perfil de usuário.
- No futuro terá um histórico relacionando as pesquisas que geraram um contrato com o Service Provider.
- No futuro terá capacidade de enviar mensagens diretas aos Service Providers através do sistema de contato integrado.
- Pode ter preferências salvas e acessar funcionalidades adicionais.
- No futuro terá funcionalidades adicionais de personalização da experiência no diretório, como salvar favoritos e configurar alertas de serviço.

### Service Provider
O Service Provider é um usuário profissional ou empresa que se registra no diretório para listar seus serviços. Eles são responsáveis por manter suas listagens atualizadas e pagar as Referral Fees ao Site Owner baseadas nas interações aprovadas dos usuários.

- Gerenciamento de suas próprias listagens de serviços, incluindo adicionar, editar e remover informações.
- Acesso a uma dashboard privada para acompanhar as interações com suas listagens, como visualizações e contatos.
- Obrigação de pagar as Referral Fees ao Site Owner quando um Registered User ou outro usuário realiza uma ação qualificada (como uma reserva ou contratação efetiva a partir da pesquisa).
- Responder a consultas e mensagens de Registered Users diretamente através do sistema do plugin.


## Padrões de Código

O plugin segue as [Normas de Codificação do WordPress](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) para garantir a qualidade e a consistência do código.

- **Sanitização e Validação:** Todas as entradas são devidamente sanitizadas e validadas.
- **Internacionalização:** Preparado para tradução, seguindo as práticas de internacionalização do WordPress.

## Contribuições

Para contribuir com o ProfessionalDirectory, siga estes passos:

1. **Fork o Repositório:** Faça um fork do repositório no GitHub.
2. **Faça suas Mudanças:** Trabalhe em uma branch separada para suas contribuições.
3. **Pull Request:** Envie um pull request com uma descrição clara de suas mudanças.

## Suporte

Para suporte, dúvidas ou sugestões de melhorias, utilize a seção de issues no GitHub do projeto.

---

Desenvolvido por Ariel Souza
