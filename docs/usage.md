# Uso do ReferralHub

Este guia fornece instruções detalhadas sobre como usar o plugin ReferralHub no seu site WordPress.

## Criando e Gerenciando Services

### Para Service Providers

1. **Adicionando um Novo Service:**
   - Acesse `Services > Adicionar Novo`.
   - Preencha os detalhes do serviço, incluindo título, descrição e qualquer informação relevante.
   - Selecione o `Service Type` e `Service Location` apropriados.
   - Publique o serviço.

2. **Editando um Service:**
   - Acesse `Services` e localize o serviço que deseja editar.
   - Faça as alterações necessárias e salve.

3. **Visualizando Métricas:**
   - Acesse `Dashboard`.
   - Filtre pelo período ou serviço desejado.

### Para Site Owners

- Site Owners podem adicionar, editar e excluir qualquer Service da mesma forma, mas têm acesso a todos os services listados no painel.

## Sistema de Referral Fees

O ReferralHub oferece um sistema flexível de referral fees que pode ser configurado tanto globalmente quanto individualmente para cada Service Provider. Existem três tipos principais de referral fees:

- **Por Visualização:** Taxa gerada cada vez que um serviço é visualizado.
- **Por Inquiry Aprovado:** Taxa atribuída quando um inquiry resulta em uma contratação ou outro critério de "aprovação" definido.
- **Combinação das Duas:** Aplica taxas tanto por visualizações quanto por inquiries aprovados.

### Configurações Globais de Referral Fee

- Acesse `Dashboard > Configurações de Referral Fee` para definir as taxas de referral fee padrão que se aplicam a todos os Service Providers, a menos que especificado de outra forma.

### Configurações Específicas de Referral Fee

- Service Providers podem ter taxas de referral fee específicas que sobrescrevem as configurações globais, se a opção "Sobrescrever configurações gerais de referral fee" estiver ativada em seus perfis.
- Essas configurações são acessadas e gerenciadas na página de perfil de cada Service Provider.

## Utilizando Shortcodes

### Inquiry Form

- Use o shortcode `[rhb_inquiry_form]` para adicionar o formulário de inquiry em qualquer página ou post.
- O formulário permite que os usuários busquem services por tipo e localização.

### Inquiry Results

- Após a busca, os resultados são exibidos na mesma página.
- Inclua o shortcode `[rhb_inquiry_results]` na página onde deseja exibir os resultados.

## Configuração de E-mail SMTP

Para garantir o envio eficaz de notificações e outros e-mails pelo plugin, é essencial configurar um plugin SMTP, como o 'Easy SMTP'. Siga as instruções de instalação e configuração do plugin SMTP para assegurar a entrega correta dos e-mails.

## Dicas de Uso

- **Perfis de Service Provider:** Encoraje os Service Providers a manterem seus perfis atualizados para aumentar a relevância nos resultados de inquiry.
- **Categorização Adequada:** Utilize a taxonomia “Service Type” para categorizar os services de forma eficiente.

## Suporte

Se precisar de assistência adicional ou tiver dúvidas sobre o uso do ReferralHub, consulte a seção [FAQ](faq.md) ou entre em contato através do suporte.

---

Desenvolvido por Ariel Souza
