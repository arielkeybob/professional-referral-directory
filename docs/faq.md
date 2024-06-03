# Perguntas Frequentes (FAQ) do ReferralHub

Este documento contém respostas para as perguntas mais frequentes sobre o plugin ReferralHub.

## Perguntas Gerais

### Q: O que o plugin ReferralHub faz?
**A:** O plugin permite criar e gerenciar um diretório de serviços profissionais no WordPress, incluindo a funcionalidade de busca e categorização de serviços, além de gerenciar referral fees para os serviços visualizados ou aprovados.

### Q: Em quais versões do WordPress o plugin é compatível?
**A:** O plugin é compatível com as versões mais recentes do WordPress. Recomendamos sempre manter seu WordPress atualizado.

## Uso do Plugin

### Q: Como posso adicionar um novo serviço?
**A:** Acesse `Services > Adicionar Novo` no painel administrativo do WordPress e preencha os detalhes necessários.

### Q: Quem pode criar e gerenciar serviços?
**A:** Usuários com o role “Service Provider” e administradores podem criar e gerenciar serviços.

### Q: Quem pode cadastrar Locations e Service Types?
**A:** Até a versão 1.1.2, somente administradores do site podem cadastrar Locations e Service Types. Estamos estudando a viabilidade de permitir que os Service Providers também possam fazer esse cadastro.

### Q: Como funciona o inquiry de serviços sem login?
**A:** Usuários podem realizar inquiries de serviços sem fazer login. Porém eles devem fornecer o nome e email para realizar o inquiry. Opcionalmente, eles podem criar uma conta durante o processo de inquiry.

### Q: Como criar uma conta durante o inquiry de serviços?
**A:** Durante o inquiry, os usuários têm a opção de criar uma conta marcando a opção "Create an account" e fornecendo uma senha. Se a conta for criada com sucesso, o usuário será automaticamente logado.

### Q: O que acontece se o usuário não optar por criar uma conta?
**A:** Se o usuário não optar por criar uma conta, ele ainda poderá realizar o inquiry e ver os resultados fornecendo apenas o nome e o email.

## Gestão de Referral Fees

### Q: Como são gerenciadas as referral fees no ReferralHub?
**A:** O plugin permite a configuração de referral fees de duas formas:
- **Configurações Globais de Referral Fee:** Aplicam-se a todos os Service Providers e podem ser configuradas para referral fees por visualização, por inquiry aprovado, ou ambas.
- **Configurações Específicas de Referral Fee:** Permite que Service Providers com a permissão adequada sobrescrevam as configurações globais para suas próprias referral fees. Esta opção deve ser habilitada por cada Service Provider em seu perfil.

### Q: O que acontece se um Service Provider sobrescrever as configurações de referral fee?
**A:** Se um Service Provider optar por sobrescrever as configurações de referral fee, as referral fees aplicadas serão as definidas em seu perfil, em vez das configurações globais. Isto permite uma maior flexibilidade e personalização.

## Problemas e Soluções

### Q: Estou tendo problemas com a instalação, o que devo fazer?
**A:** Certifique-se de que sua versão do WordPress está atualizada e que você tem permissões administrativas. Para mais detalhes, consulte [Instalação](installation.md).

### Q: Estou tendo problemas com o envio de e-mails. Como posso resolver?
**A:** Se você está enfrentando problemas com o envio de e-mails pelo plugin, recomendamos a utilização de um plugin SMTP, como o 'Easy SMTP'. Configure o plugin SMTP com as informações do seu serviço de e-mail. Isso garante a entrega eficaz dos e-mails e é uma solução comum para problemas relacionados ao envio de e-mails em muitos ambientes de hospedagem WordPress.

### Q: Como posso reportar um bug ou sugerir uma melhoria?
**A:** Reporte bugs e sugira melhorias abrindo uma issue no GitHub do projeto.

## Suporte

### Q: Onde posso obter mais ajuda?
**A:** Se você precisar de mais ajuda, entre em contato através da seção de suporte no GitHub ou consulte a documentação detalhada.

---

Desenvolvido por Ariel Souza
