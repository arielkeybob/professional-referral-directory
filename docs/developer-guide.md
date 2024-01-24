# Guia do Desenvolvedor para ProfessionalDirectory

Bem-vindo ao guia do desenvolvedor do ProfessionalDirectory. Este documento fornece uma visão geral da arquitetura do plugin, padrões de código e práticas recomendadas para contribuir ou estender o plugin.

## Estrutura do Plugin

O ProfessionalDirectory segue uma estrutura padrão de plugins do WordPress, incluindo:

- **Diretório Admin:** Contém arquivos relacionados à interface administrativa do plugin.
- **Diretório Includes:** Arquivos principais do plugin para funcionalidades essenciais.
- **Diretório Public:** Arquivos destinados ao front-end do site.
- **Arquivos de Configuração:** Como `ProfessionalDirectory.php`, para configurações iniciais.

## Padrões de Código

O plugin segue as [Normas de Codificação do WordPress](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/) para garantir a qualidade e a consistência do código.

- **Sanitização e Validação:** Todas as entradas são devidamente sanitizadas e validadas.
- **Internacionalização:** O plugin é preparado para tradução, seguindo as práticas de internacionalização do WordPress.

## Desenvolvendo Extensões ou Contribuições

- **Hooks e Filtros:** Utilize hooks e filtros do WordPress para adicionar ou modificar funcionalidades.
- **Documentação:** Comente seu código para explicar a lógica e as decisões de design.
- **Testes:** Teste seu código em diferentes ambientes e versões do WordPress.

## Submetendo Contribuições

Para contribuir com o ProfessionalDirectory, por favor, siga estas etapas:

1. **Fork o Repositório:** Faça um fork do repositório no GitHub.
2. **Faça suas Mudanças:** Trabalhe em uma branch separada para suas contribuições.
3. **Pull Request:** Envie um pull request com uma descrição clara de suas mudanças.

## Suporte

Se precisar de ajuda ou tiver dúvidas sobre o desenvolvimento com o ProfessionalDirectory, entre em contato através da seção de issues no GitHub.

---

Desenvolvido por Ariel Souza