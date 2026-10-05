# Sistema de Cadastro de Clientes — Trabalho N1

Sistema web em PHP, com arquitetura em camadas (Modelo / DTO / DAO /
Serviço / Controlador / View), igual ao padrão visto em aula — porém
aplicado a um domínio diferente (**clientes**, em vez de produtos de
loja) e com o CRUD completo: **cadastrar, editar, excluir e buscar**.

## Estrutura

```
cadastro-clientes/
├── index.html
├── conexao.php
├── clientes.sql
├── ClienteException.php
├── composer.json
├── modelos/Cliente.php
├── DTOs/ClienteDTO.php
├── DAOs/ClienteDAO.php              (salvar, atualizar, excluir, buscar, buscarPorId)
├── servicos/ClienteServico.php      (validações de negócio)
├── controladores/
│   ├── rota.php                     (acao=salvar | acao=editar)
│   ├── ClienteControlador.php
│   ├── buscarClientes.php
│   ├── editarCliente.php            (carrega o formulário já preenchido)
│   └── excluirCliente.php
├── views/
│   ├── formCadastrarCliente.php
│   ├── formEditarCliente.php
│   └── mostrarClientes.php
└── tests/
    └── CadastroClienteSeleniumTest.php
```

## Banco de dados

1. Crie o banco executando o script `clientes.sql` (cria o banco
   `cadastro_clientes` e a tabela `clientes`).
2. Ajuste usuário/senha do MySQL em `conexao.php`, se necessário
   (padrão: `root` sem senha, como no exemplo de aula).

## Rodando o sistema

1. Coloque a pasta `cadastro-clientes` dentro do diretório servido
   pelo Apache/PHP (ex.: `htdocs`, `www`, ou rode
   `php -S localhost:8000` dentro da pasta).
2. Acesse `index.html` no navegador.

## Testes automatizados (Selenium)

Os testes usam **php-webdriver** (biblioteca oficial do Selenium para
PHP) junto com **PHPUnit**, simulando um usuário real no navegador:
cadastrar → listar → editar → excluir, além de um teste de validação
de campo obrigatório.

### Instalar dependências

```bash
composer install
```

### Subir o Selenium Server

Baixe o `selenium-server-<versão>.jar` e um driver compatível (ex.:
`chromedriver`), depois execute:

```bash
java -jar selenium-server-4.x.x.jar standalone
```

Isso sobe o Selenium em `http://localhost:4444` (padrão esperado
pelos testes).

### Rodar os testes

```bash
BASE_URL=http://localhost:8000/ vendor/bin/phpunit tests/CadastroClienteSeleniumTest.php
```

- `BASE_URL`: URL onde o sistema está publicado (padrão:
  `http://localhost/cadastro-clientes/`).
- `SELENIUM_URL`: URL do Selenium Server (padrão:
  `http://localhost:4444`).

### O que é testado

| Teste | O que verifica |
|---|---|
| `testCadastrarCliente` | Preenche o formulário de cadastro e confirma que o cliente aparece na listagem com mensagem de sucesso |
| `testEditarCliente` | Edita o telefone do cliente criado e confirma a atualização na listagem |
| `testExcluirCliente` | Exclui o cliente e confirma que ele some da listagem |
| `testCadastroComNomeVazioExibeErro` | Tenta cadastrar sem nome e confirma que o sistema exibe "Dados inválidos" e não grava o registro |
