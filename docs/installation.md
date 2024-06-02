# Instalação do ProfessionalDirectory

Este documento descreve como instalar e configurar o plugin ProfessionalDirectory no seu site WordPress.

## Pré-requisitos

Antes de instalar o plugin, certifique-se de que você tem:
- Uma instalação do WordPress.
- Acesso administrativo ao seu painel WordPress.

## Instalação Padrão

1. **Baixar o Plugin:** Baixe o arquivo zip do plugin ProfessionalDirectory.
2. **Acessar o Painel WordPress:** Faça login no seu painel administrativo do WordPress.
3. **Carregar o Plugin:** Vá para `Plugins > Adicionar Novo > Enviar Plugin` e carregue o arquivo zip do ProfessionalDirectory.
4. **Instalar e Ativar:** Após o upload, clique em `Instalar Agora` e, em seguida, `Ativar` o plugin.

## Configuração Inicial

Após a instalação e ativação, o plugin ProfessionalDirectory automaticamente:
- Cria o user Role “Service Provider”.
- Cria o Post Type “Services”.
- Cria a taxonomia “Service Type”.
- Cria a taxonomia “Service Location”.
- Cria shortcodes para o inquiry form e inquiry results (veja como usar os shortcodes em [Uso](usage.md)).

## Configuração Adicional para Envio de E-mails

Para garantir o funcionamento adequado do envio de e-mails pelo plugin, é recomendado instalar e configurar um plugin SMTP, como o 'Easy SMTP':
1. **Instalar um Plugin SMTP:** Vá para `Plugins > Adicionar Novo` e procure por 'Easy SMTP' ou um plugin SMTP de sua escolha. Instale e ative o plugin.
2. **Configurar o Plugin SMTP:** Siga as instruções do plugin para configurá-lo com as informações do seu serviço de e-mail.

## Verificações Pós-Instalação

1. **Verifique o Role “Service Provider”:** Acesse `Usuários > Perfis` e confirme se o role “Service Provider” foi criado.
2. **Verifique o Post Type “Services”:** Verifique se o novo tipo de post “Services” está disponível no painel.
3. **Verifique a Taxonomia “Service Type”:** Confirme se a taxonomia está disponível e associada ao Post Type “Services”.
4. **Verifique a Taxonomia “Service Location”:** Confirme se a taxonomia está disponível e associada ao Post Type “Services”.

## Problemas Comuns

Se você encontrar problemas durante a instalação, verifique se:
- Sua versão do WordPress está atualizada.
- Você tem permissões suficientes para instalar plugins.

Para mais informações ou assistência, consulte a seção [FAQ](faq.md) ou entre em contato através do suporte.

---

Desenvolvido por Ariel Souza
